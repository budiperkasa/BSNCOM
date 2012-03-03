<?php
class backendModel extends model
{
	/**
	 * select active, blocked, suspended, suspended, unapproved, not paid listings
	 *
	 * @return array
	 */
	public function getListingsSummary()
	{
		$this->db->select('status');
		$this->db->select('count(if(status=1, 1, null)) AS active_status', false);
		$this->db->select('count(if(status=2, 1, null)) AS blocked_status', false);
		$this->db->select('count(if(status=3, 1, null)) AS suspended_status', false);
		$this->db->select('count(if(status=4, 1, null)) AS unapproved_status', false);
		$this->db->select('count(if(status=5, 1, null)) AS not_paid_status', false);
		$this->db->from('listings');
		$this->db->group_by('status');
		$query = $this->db->get();
		
		return $query->result_array();
	}
	/**
	 * select  self active, blocked, suspended, suspended, unapproved, not paid listings
	 *
	 * @return array
	 */
	public function getMyListingsSummary()
	{
		$this->db->select('status');
		$this->db->select('count(if(status=1, 1, null)) AS active_status', false);
		$this->db->select('count(if(status=2, 1, null)) AS blocked_status', false);
		$this->db->select('count(if(status=3, 1, null)) AS suspended_status', false);
		$this->db->select('count(if(status=4, 1, null)) AS unapproved_status', false);
		$this->db->select('count(if(status=5, 1, null)) AS not_paid_status', false);
		$this->db->from('listings');
		$this->db->group_by('status');
		$this->db->where('owner_id', $this->session->userdata('user_id'));
		$query = $this->db->get();
		
		return $query->result_array();
	}
	/**
	 * select active, blocked users
	 *
	 * @return array
	 */
	public function getUsersSummary()
	{
		$this->db->select('status');
		$this->db->select('count(if(status=1, 1, null)) AS unverified_status', false);
		$this->db->select('count(if(status=2, 1, null)) AS active_status', false);
		$this->db->select('count(if(status=3, 1, null)) AS blocked_status', false);
		$this->db->from('users');
		$this->db->group_by('status');
		$query = $this->db->get();
		
		return $query->result_array();
	}
	/**
	 * select users counts by users groups
	 *
	 * @return array
	 */
	public function getUsersGroupsSummary()
	{
		$this->db->select('ug.id');
		$this->db->select('ug.name');
		$this->db->select('count(*) AS users_count_in_group');
		$this->db->join('users_groups AS ug', 'u.group_id=ug.id', 'left');
		$this->db->from('users AS u');
		$this->db->group_by('ug.id');
		$query = $this->db->get();
		
		return $query->result_array();
	}

	public function getClaimsUnapproved()
	{
		$this->db->select('count(*) AS unapproved_claims');
		$this->db->from('listings_claims');
		$this->db->where('approved', 0);
		$this->db->where('to_user_id !=', 0);
		$row = $this->db->get()->row_array();
		
		return $row['unapproved_claims'];
	}
	
	public function languagesFilesUpdate($uversion, $udir)
	{
		$i18n_dir = $udir . 'i18n';
		if (is_dir($i18n_dir)) {
			$languages = directory_map(LANGPATH);
		    $languages = array_merge(array_keys($languages), explode('|', DEFAULT_LANGS));
		    $i18n_dir_map = directory_map($i18n_dir);
		    //foreach ($languages AS $lang_code=>$lang_folder) {
		    foreach ($languages AS $lang_code) {
		    	// If site has special languages, those are not in updates folder - place updates contstants from english
				if (array_key_exists($lang_code, $i18n_dir_map))
					$update_lang_code = $lang_code;
				else
					$update_lang_code = 'en';
				$update_folder = $i18n_dir . DIRECTORY_SEPARATOR . $update_lang_code . DIRECTORY_SEPARATOR;
				foreach ($i18n_dir_map[$update_lang_code] as $file) {
					$update_file = $update_folder . $file;
					$update_constants = file_get_contents($update_file);

					$dest_file = LANGPATH . $lang_code . DIRECTORY_SEPARATOR . $file;
					// Update existed lang file or copy
					if (is_file($dest_file)) {
						$dest_constants = file_get_contents($dest_file);
						$dest_constants = trim($dest_constants, '?>');
						$dest_constants .= "

/* Update to " . $uversion . " */
";
						$update_constants = trim($update_constants, '<?php');
						$update_constants = trim($update_constants, '?>');
						$dest_constants .= $update_constants;
						file_put_contents($dest_file, $dest_constants . '
?>');
					} else {
						@copy($update_file, $dest_file);
						@chmod($dest_file, 0777);
					}
				}
			}

			// --------------------------------------------------------------------------------------------
	    	// In order to avoid 'MYSQL server has gone away' problem
	    	// --------------------------------------------------------------------------------------------
	    	$this->db->reconnect();
	    	// --------------------------------------------------------------------------------------------
		}
	}
}
?>