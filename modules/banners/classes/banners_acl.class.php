<?php

/**
 * realize bannersAcl object as singleton:
 * - one entry point;
 * - one object in the whole system
 * - object allowed from modules and views
 *
 */
class bannersAcl extends contentAcl
{
	private static $instance = null;
	
	private function __construct()
	{
	}

	public static function getInstance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new bannersAcl;
			return self::$instance;
		} else {
			return self::$instance;
		}
	}

	/**
	 * Checks if admin access banner or just a user wants to edit self banner
	 *
	 * @param int $banner_id
	 * @param string $admin_access
	 */
	public function checkBannerAccess($banner_id, $admin_access)
	{
		if (!$this->getBannerAccess($banner_id, $admin_access)) {
			return show_404();
		}
	}

	public function getBannerAccess($user_id, $admin_access)
	{
		$CI = &get_instance();
		$CI->load->model('acl', 'acl');
		
		$permissions = $CI->acl->getAccessTableForUserGroup($CI->session->userdata('user_group_id'));
		if (!$CI->acl->checkAccess($permissions, $admin_access)) {
			$CI->db->select('owner_id');
			$CI->db->from('banners');
			$CI->db->where('id', $user_id);
			$CI->db->where('owner_id', $CI->session->userdata('user_id'));
			$query = $CI->db->get();

			return $query->num_rows();
		} else {
			return true;
		}
	}
}
?>