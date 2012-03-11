<?php
include_once(MODULES_PATH . 'packages/classes/package.class.php');
include_once(MODULES_PATH . 'packages/classes/user_packages.class.php');

class packagesModel extends model
{
	private $_package_id;
	
    public function setPackageId($package_id)
    {
    	$this->_package_id = $package_id;
    }

    public function getAllPackages()
    {
    	$levels_model = $this->load->model('levels', 'types_levels');
    	
    	$this->db->select('*');
    	$this->db->from('packages AS p');
    	$this->db->orderby('p.order_num');
		$query = $this->db->get();
		
		$packages = array();
		foreach ($query->result_array() AS $row) {
			$package = new packageClass;
			$package->setPackageFromArray($row);

			$this->db->select('*');
			$this->db->from('packages_items as pi');
			$this->db->where('pi.package_id', $package->id);
			$this->db->join('levels as lev', 'lev.id=pi.level_id');
			$this->db->orderby('lev.type_id');
			$this->db->orderby('lev.order_num');
			$query = $this->db->get();
			foreach ($query->result_array() AS $items_row) {
				$package->setItem($levels_model->getLevelById($items_row['level_id']), $items_row['listings_count']);
			}

			$packages[] = $package;
		}
		return $packages;
    }
    
    /**
     * saves the order of packages by its weight
     *
     * @param string $serialized_order
     */
    public function setPackagesOrder($serialized_order)
    {
    	$a = explode("=", $serialized_order);
    	$start = 1;
    	foreach ($a AS $row) {
    		$b = explode("&", $row);
    		foreach ($b AS $id) {
    			$id = trim($id, "_id");
    			if (is_numeric($id)) {
    				$this->db->set('order_num', $start++);
    				$this->db->where('id', $id);
    				$this->db->update('packages');
    			}
    		}
    	}
    }
    
    public function getNewPackage()
    {
		$package = new packageClass;
        return $package;
    }
    
    public function getPackageFromForm($form_result)
    {
		$package = new packageClass;
		$package->setPackageFromArray($form_result);

        return $package;
    }
    
    /**
     * is there package with such name in the DB?
     *
     * @param string $name
     */
    public function is_package_name($name)
    {
    	$this->db->select();
		$this->db->from('packages');
		$this->db->where('name', $name);
		if (!is_null($this->_package_id)) {
			$this->db->where('id !=', $this->_package_id);
		}
		$query = $this->db->get();

		return $query->num_rows();
    }
    
    public function savePackage($form_result)
    {
    	$this->db->select_max('order_num');
    	$query = $this->db->get('packages');
    	if ($row = $query->row())
    		$order_num = $row->order_num + 1;
    	else 
    		$order_num = 1;
    	
        $this->db->set('name', $form_result['name']);
        $this->db->set('order_num', $order_num);
        if ($this->db->insert('packages')) {
        	$package_id = $this->db->insert_id();

        	$system_settings = registry::get('system_settings');
        	if (@$system_settings['language_areas_enabled']) {
        		translations::saveTranslations(array('packages', 'name', $this->db->insert_id()));
        	}
        	return true;
        }
    }
    
    public function getPackageById($package_id = null)
    {
    	if (is_null($package_id))
    		$package_id = $this->_package_id;

    	$this->db->select('*');
    	$this->db->from('packages AS p');
    	$this->db->where('id', $package_id);
		$query = $this->db->get();

		if ($query->num_rows()) {
			$levels_model = $this->load->model('levels', 'types_levels');

			$package = new packageClass;
			$package->setPackageFromArray($query->row_array());
			
			$this->db->select('*');
			$this->db->from('packages_items as pi');
			$this->db->where('pi.package_id', $package_id);
			$this->db->join('levels as lev', 'lev.id=pi.level_id');
			$this->db->orderby('lev.type_id');
			$this->db->orderby('lev.order_num');
			$query = $this->db->get();
			foreach ($query->result_array() AS $row) {
				$package->setItem($levels_model->getLevelById($row['level_id']), $row['listings_count']);
			}
	
			return $package;
		} else {
			return false;
		}
    }
    
    public function savePackageById($form_result)
    {
    	$this->db->set('name', $form_result['name']);
    	$this->db->where('id', $this->_package_id);
    	return $this->db->update('packages');
    }
    
    public function deletePackageById()
    {
    	$this->db->where('id', $this->_package_id);
    	$this->db->delete('packages');
    	
    	$this->db->where('package_id', $this->_package_id);
    	$this->db->delete('packages_items');
    	
    	/*$this->db->select('pl.listing_id');
    	$this->db->from('packages_listings AS pl');
    	$this->db->join('packages_users AS pu', 'pu.id=pl.user_package_id', 'left');
    	$this->db->where('pu.package_id', $this->_package_id);
    	$query = $this->db->get();
    	
    	$CI = &get_instance();
    	$listings_model = $CI->load->model('listings', 'listings');
    	foreach ($query->result_array() AS $row) {
    		$listings_model->deleteListingById($row['listing_id']);
    	}*/

    	$this->db->query('DELETE pl, pu FROM packages_users AS pu LEFT JOIN packages_listings AS pl ON pu.id=pl.user_package_id WHERE pu.package_id = ' . $this->_package_id);

    	$this->db->where('package_id', $this->_package_id);
    	$this->db->delete('packages_price');
    	
    	return true;
    }
    
    public function savePackageItems($input, $limited_level_ids, $unlimited_level_ids)
    {
    	foreach ($unlimited_level_ids AS $id) {
    		$this->db->set('level_id', $id);
	    	$this->db->set('package_id', $this->_package_id);
	    	$this->db->set('listings_count', 'unlimited');
	    	$this->db->on_duplicate_insert('packages_items');
    	}
    	foreach ($limited_level_ids AS $id) {
    		if (array_search($id, $unlimited_level_ids) === FALSE) {
	    		$this->db->set('level_id', $id);
		    	$this->db->set('package_id', $this->_package_id);
		    	$this->db->set('listings_count', $input->post('count_'.$id));
		    	$this->db->on_duplicate_insert('packages_items');
    		}
    	}
    	return true;
    }
    
    // --------------------------------------------------------------------------------------------
    // Use packages functions
    // --------------------------------------------------------------------------------------------
    /**
     * User selects package - link it with user's account
     *
     */
    public function addPackageToUser()
    {
    	$this->db->set('package_id', $this->_package_id);
    	$this->db->set('user_id', $this->session->userdata('user_id'));
    	$this->db->set('status', 1);
    	$this->db->set('creation_date', date("Y-m-d H:i:s"));
    	if ($this->db->insert('packages_users')) {
    		return $this->db->insert_id();
    	} else {
    		return false;
    	}
    }
    
    public function getMyPackagesCount()
    {
    	$this->db->select('count(*) AS packages_count');
    	$this->db->where('user_id', $this->session->userdata('user_id'));
    	$this->db->from('packages_users');
    	$query = $this->db->get();
    	$row = $query->row_array();
    	
    	return $row['packages_count'];
    }
    
    /**
     * returns an object of all users' packages
     *
     * @param int $user_id
     * @return userPackagesClass object
     */
    public function getUserPackages($user_id = null)
    {
    	if (is_null($user_id))
    		$user_id = $this->session->userdata('user_id');

    	$this->db->select();
		$this->db->from('packages_users');
		$this->db->where('user_id', $user_id);
		$this->db->order_by('creation_date');
		$query = $this->db->get();

		$user_packages = new userPackagesClass($user_id);
		foreach ($query->result_array() AS $row) {
			$user_packages->setPackage($row);
		}
		return $user_packages;
    }
    
    /**
     * returns an object of all users' packages
     *
     * @param int $user_package_id
     * @return userPackagesClass object
     */
    public function getUserPackagesByUserPackageId($user_package_id)
    {
    	$this->db->select();
		$this->db->from('packages_users');
		$this->db->where('id', $user_package_id);
		$row = $this->db->get()->row_array();

		$user_packages = new userPackagesClass($row['user_id']);
		$user_packages->setPackage($row);
		return $user_packages;
    }
    
    /**
     * Select all packages table using paginator,
     * this executes optimized method with 3 queries
     *
     * @return array
     */
    public function selectPackages($orderby = 'pu.creation_date', $direction = 'desc', $args = array())
    {
    	// -----------------------------------------------------------------------------------
    	// Number of rows needs
    	//
    	$this->db->select('count(*) as count_rows');
    	$this->db->from('packages_users AS pu');
    	if (isset($args['search_login'])) {
    		$this->db->join('users AS u', 'u.id=pu.user_id', 'left');
    		$this->db->like('u.login', urldecode(html_entity_decode($args['search_login'])));
    	}
    	if (isset($args['search_status'])) {
    		$this->db->where('pu.status', urldecode($args['search_status']));
    	}
    	if (isset($args['search_package'])) {
    		$this->db->where('pu.package_id', urldecode($args['search_package']));
    	}
    	if (isset($args['search_addition_date'])) {
    		$this->db->where('TO_DAYS(pu.creation_date) = ', 'TO_DAYS("' . date("Y-m-d", $args['search_addition_date']) . '")', false);
    	}
    	if (isset($args['search_from_addition_date'])) {
    		$this->db->where('TO_DAYS(pu.creation_date) >= ', 'TO_DAYS("' . date("Y-m-d", $args['search_from_addition_date']) . '")', false);
    	}
    	if (isset($args['search_to_addition_date'])) {
    		$this->db->where('TO_DAYS(pu.creation_date) <= ', 'TO_DAYS("' . date("Y-m-d", $args['search_to_addition_date']) . '")', false);
    	}
    	$query = $this->db->get();
    	$row = $query->row_array('count_rows');
    	$this->paginator->setCount($row['count_rows']);

    	// -----------------------------------------------------------------------------------
    	// Select id of packages that will be shown
    	//
    	$this->db->select('pu.id');
    	$this->db->from('packages_users AS pu');
    	if ($orderby)
    		$this->db->order_by($orderby, $direction);
    	if (isset($args['search_login'])) {
    		$this->db->join('users AS u', 'u.id=pu.user_id', 'left');
    		$this->db->like('u.login', urldecode(html_entity_decode($args['search_login'])));
    	}
    	if (isset($args['search_status'])) {
    		$this->db->where('pu.status', urldecode($args['search_status']));
    	}
    	if (isset($args['search_package'])) {
    		$this->db->where('pu.package_id', urldecode($args['search_package']));
    	}
    	if (isset($args['search_addition_date'])) {
    		$this->db->where('TO_DAYS(pu.creation_date) = ', 'TO_DAYS("' . date("Y-m-d", $args['search_addition_date']) . '")', false);
    	}
    	if (isset($args['search_from_addition_date'])) {
    		$this->db->where('TO_DAYS(pu.creation_date) >= ', 'TO_DAYS("' . date("Y-m-d", $args['search_from_addition_date']) . '")', false);
    	}
    	if (isset($args['search_to_addition_date'])) {
    		$this->db->where('TO_DAYS(pu.creation_date) <= ', 'TO_DAYS("' . date("Y-m-d", $args['search_to_addition_date']) . '")', false);
    	}
    	if ($orderby)
	    	$this->db->order_by($orderby, $direction);
    	$query = $this->db->get();
    	$ids = $this->paginator->getResultIds($query->result_array());

    	if (!empty($ids)) {
	    	$this->db->select('*');
	    	$this->db->from('packages_users AS pu');
	    	$_array = array();
	    	foreach ($ids AS $id) {
	    		$_array[] = $id['id'];
	    	}
	    	$this->db->where_in('pu.id', $_array);
	    	if ($orderby)
	    		$this->db->order_by($orderby, $direction);
	    	$query = $this->db->get();
	    	$users_packages = array();
    		foreach ($query->result_array() AS $row) {
    			$users_package = new userPackage($row);
    			$users_package->buildPackageObj();
    			$users_package->setUserObj();
    			$users_package->countListingsLeft();
    			$users_packages[] = $users_package;
    		}
    		return $users_packages;
    	} else {
    		return array();
    	}
    }
    
    /**
     * returns number of listings already created under membership package of level_id
     *
     * @param int $user_package_id
     * @param int $level_id
     * @return int
     */
    public function getUsedListingsCount($user_package_id, $level_id)
    {
    	$this->db->select('pl.listing_id');
    	$this->db->from('packages_listings AS pl');
    	$this->db->join('listings AS l', 'pl.listing_id=l.id', 'left');
    	$this->db->where('pl.user_package_id', $user_package_id);
    	$this->db->where('l.level_id', $level_id);
    	$query = $this->db->get();
    	return $query->num_rows();
    }
    
    public function savePackageStatus($user_package_id, $status)
    {
    	$this->db->set('status', $status);
    	$this->db->where('id', $user_package_id);
    	return $this->db->update('packages_users');
    }
    
    /**
     * checks if listing was created under one of existed packages, if yes - return package object
     *
     * @param int $listing_id
     * @return obj
     */
    public function isListingInPackage($listing_id)
    {
    	$this->db->select('p.id');
    	$this->db->from('packages AS p');
    	$this->db->join('packages_users AS pu', 'pu.package_id=p.id', 'left');
    	$this->db->join('packages_listings AS pl', 'pl.user_package_id=pu.id', 'left');
    	$this->db->where('pl.listing_id', $listing_id);
    	$query = $this->db->get();

    	if ($query->num_rows()) {
    		$row = $query->row_array();
    		return $this->getPackageById($row['id']);
    	} else {
    		return false;
    	}
    }
    
    public function getPackageByUsersPackage($user_package_id)
    {
    	$this->db->select('package_id');
    	$this->db->from('packages_users');
    	$this->db->where('id', $user_package_id);
    	$query = $this->db->get();

    	if ($query->num_rows()) {
    		$row = $query->row_array();
    		return $this->getPackageById($row['package_id']);
    	} else {
    		return false;
    	}
    }
    // --------------------------------------------------------------------------------------------
}
?>