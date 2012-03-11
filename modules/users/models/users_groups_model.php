<?php
include_once(MODULES_PATH . 'users/classes/users_group.class.php');

class users_groupsModel extends model
{
	private $_users_group_id = 0;
	private $_tmp_users_groups = array();
	
	public function setUsersGroupId($users_group_id)
    {
    	$this->_users_group_id = $users_group_id;
    }
	
	/**
     * return array of all users groups
     *
     * @return array
     */
    public function getUsersGroups()
    {
    	$this->db->select();
    	$this->db->from('users_groups');
    	$this->db->orderby('id');
    	$query = $this->db->get();

    	foreach ($query->result_array() AS $row) {
    		$users_group = new users_group;
    		$users_group->setUsersGroupFromArray($row);
    		$this->_cached_users_groups[$row['id']] = $users_group;
    	}
    	return $this->_cached_users_groups;
    }
    
    public function getDefaultUsersGroup()
    {
    	$this->db->select();
    	$this->db->from('users_groups');
    	$this->db->where('default_group', 1);

    	if ($row = $this->db->get()->row_array()) {
	    	$users_group = new users_group;
	    	$users_group->setUsersGroupFromArray($row);
	    	$this->_cached_users_groups[$row['id']] = $users_group;
	    	return $users_group;
    	} else {
    		return false;
    	}
    }
    
    /**
     * sets if users may register in this group
     *
     * @param int $group_id
     * @param bool $may_register
     */
    public function setMayRegisterOfGroup($group_id, $may_register)
    {
    	if ($group_id != 1) {
			$this->db->set('may_register', $may_register);
			$this->db->where('id', $group_id);
			return $this->db->update('users_groups');
    	}
    	return false;
    }
    
    /**
     * sets the default users group id
     *
     * @param int $id
     * @return bool
     */
    public function setDefaultUsersGroup($id)
    {
    	// set to 0 all users groups
		$this->db->set('default_group', '0');
        $this->db->update('users_groups');

        // set to 1 users group with id=$id
		$this->db->set('default_group', '1');
		$this->db->where('id', $id);
        return $this->db->update('users_groups');
    }
    
    /**
     * return new users group object
     *
     * @return obj
     */
    public function getNewUsersGroup()
    {
    	$users_group = new users_group;
    	return $users_group;
    }
    
    /**
     * build users group object from form
     *
     * @param array $form
     * @return obj
     */
    public function getUsersGroupFromForm($form)
    {
    	$users_group = new users_group;
    	$users_group->setUsersGroupFromArray($form);
    	
    	return $users_group;
    }
    
    /**
     * saves new users group
     *
     * @param array $form
     */
    public function saveUsersGroup($form)
    {
        $this->db->set('name', $form['name']);
        $this->db->set('is_own_page', $form['is_own_page']);
    	$this->db->set('meta_enabled', $form['meta_enabled']);
    	$this->db->set('use_seo_name', $form['use_seo_name']);
    	$this->db->set('logo_enabled', $form['logo_enabled']);
    	if ($form['logo_enabled']) {
	    	$this->db->set('logo_size', $form['logo_width'] . '*' . $form['logo_height']);
	    	$this->db->set('logo_thumbnail_size', $form['logo_thumbnail_width'] . '*' . $form['logo_thumbnail_height']);
    	}
        if ($this->db->insert('users_groups')) {
	        $group_id = $this->db->insert_id();
	        
	        // Create content fields group
	        $this->db->set('name', LANG_USERS_PROFILE_GROUP_CUSTOM_NAME . ' "' . $form['name'] . '"');
	        $this->db->set('custom_name', USERS_PROFILE_GROUP_CUSTOM_NAME);
	        $this->db->set('custom_id', $group_id);
	        return $this->db->insert('content_fields_groups');
        }
    }
    
    /**
     * validation function
     *
     * @param string $name
     * @return bool
     */
    public function is_name($name)
    {
    	$this->db->select();
    	$this->db->where('name', $name);
    	$this->db->where('id !=', $this->_users_group_id);
    	$this->db->from('users_groups');
    	$query = $this->db->get();

		return $query->num_rows();
    }
    
    /**
     * returns complete users group object
     *
     * @return obj
     */
    public function getUsersGroupById($users_group_id = null)
    {
    	if (is_null($users_group_id))
    		$users_group_id = $this->_users_group_id;
    	
    	if (isset($this->_cached_users_groups[$users_group_id])) {
    		return $this->_cached_users_groups[$users_group_id];
    	} else {
	        $this->db->select();
	        $this->db->from('users_groups');
	        $this->db->where('id', $users_group_id);
	        if ($row = $this->db->get()->row_array()) {
		        $users_group = new users_group;
		        $users_group->setUsersGroupFromArray($row);
		        
		        $this->_cached_users_groups[$users_group_id] = $users_group;
		    	
		    	return $users_group;
	        }
    	}
    }
    
    /**
     * saves existed users group
     *
     * @param array $form
     * @return bool
     */
    public function saveUsersGroupById($form)
    {
    	$this->db->set('name', $form['name']);
    	$this->db->set('is_own_page', $form['is_own_page']);
    	$this->db->set('meta_enabled', $form['meta_enabled']);
    	$this->db->set('use_seo_name', $form['use_seo_name']);
    	$this->db->set('logo_enabled', $form['logo_enabled']);
    	if ($form['logo_enabled']) {
	    	$this->db->set('logo_size', $form['logo_width'] . '*' . $form['logo_height']);
	    	$this->db->set('logo_thumbnail_size', $form['logo_thumbnail_width'] . '*' . $form['logo_thumbnail_height']);
    	}
    	$this->db->where('id', $this->_users_group_id);
        return $this->db->update('users_groups');
    }
    
    /**
     * deletes users group by its id
     *
     * @return bool
     */
    public function deleteUsersGroupById()
    {
    	$current_users_group = $this->getUsersGroupById();

    	// Delete users group
        $this->db->where('id', $this->_users_group_id);
        $this->db->where('id !=', '1');
        $this->db->delete('users_groups');
        
        // If we delete default users group,
    	// then set another last group as default
    	if ($current_users_group->default_group) {
	    	$this->db->select_max('id');
	    	$this->db->from('users_groups');
	    	$query = $this->db->get();
	    	$row = $query->row_array();
	    	$max_id = $row['id'];
	
	    	$this->db->set('default_group', 1);
	    	$this->db->set('may_register', 1);
	    	$this->db->where('id', $max_id);
	    	$this->db->update('users_groups');
    	}
        
        // Delete users
        $this->db->select('id');
        $this->db->from('users');
        $this->db->where('group_id', $this->_users_group_id);
        $this->db->where('group_id !=', '1');
        $query = $this->db->get();
        
        $CI = &get_instance();
        $CI->load->model('users', 'users');
	    foreach ($query->result_array() AS $row)
	        $CI->users->deleteUserById($row['id']);

        // Delete permissions
        $this->db->where('group_id', $this->_users_group_id);
        $this->db->delete('users_groups_permissions');
        
        // Delete content permissions
        $this->db->where('group_id', $this->_users_group_id);
        $this->db->delete('users_groups_content_permissions');
        
        // Delete content fields group
        $this->db->where('custom_name', USERS_PROFILE_GROUP_CUSTOM_NAME);
        $this->db->where('custom_id', $this->_users_group_id);
        $this->db->delete('content_fields_groups');
        
        return true;
    }
    
    /**
     * Look through and collect all permissions of the system,
     * routes and menu items collect in components_loader.php
     *
     */
    public function collectPermissionsOfSystem()
    {
    	$permissions = registry::get('permissions');
		ksort($permissions);
		foreach ($permissions AS $module=>$permission) {
			sort($permissions[$module]);
		}
		// Permissions list must not have 'Control permissions' permission
		$key = array_search('Control permissions', $permissions['users']);
		unset($permissions['users'][$key]);
		
		return $permissions;
    }
    
    /**
     * get the table of active users groups/permissions
     *
     * @return array
     */
    public function getUsersGroupsPermissions()
    {
        $this->db->select('ugp.id AS permission_id');
        $this->db->select('ugp.function_access AS function_access');
        $this->db->select('ugp.group_id AS group_id');
        $this->db->select('ug.name AS group_name');
        $this->db->from('users_groups AS ug');
        $this->db->join('users_groups_permissions AS ugp', 'ug.id=ugp.group_id');
        $query = $this->db->get();

        return $query->result_array();
    }
    
    /**
     * Compare permissions checked in the table and existed in the DB
     * 
     * Only admins group has 'Control permissions' access
     * and nobody could delete or change it
     *
     * @param array $post_permissions - permissions checked in the table
     * @param array $users_groups_permissions - permissions from the DB
     * @return bool
     */
    public function saveUsersGroupsPermissions($post_permissions, $users_groups_permissions)
    {
    	// Insert new permissions
    	foreach ($post_permissions AS $post_permission) {
    		$existed = FALSE;
    		foreach ($users_groups_permissions AS $db_permission) {
    			if ($post_permission['group_id'] == $db_permission['group_id'] && $post_permission['function_access'] == $db_permission['function_access']) {
    				$existed = TRUE;
    				break;
    			}
    		}
    		if (!$existed) {
    			$this->db->set('group_id', $post_permission['group_id']);
	    		$this->db->set('function_access', $post_permission['function_access']);
	    		$this->db->insert('users_groups_permissions');
    		}
    	}

    	// Delete inactive permissions
    	foreach ($users_groups_permissions AS $db_permission) {
    		$existed = FALSE;
    		foreach ($post_permissions AS $post_permission) {
    			if ($post_permission['group_id'] == $db_permission['group_id'] && $post_permission['function_access'] == $db_permission['function_access']) {
    				$existed = TRUE;
    				break;
    			}
    		}
    		
    		// Prevent deletion of 'Admin backend' permission for admins group
    		if ($db_permission['group_id'] == 1 && $db_permission['function_access'] == 'Admin backend')
    			$existed = TRUE;

    		if (!$existed) {
	    		$this->db->where('id', $db_permission['permission_id']);
	    		$this->db->delete('users_groups_permissions');
    		}
    	}
    	
    	return true;
    }
    
    /**
     * Compare permissions checked in the table and existed in the DB
     * 
     * @param array $post_permissions - permissions checked in the table
     * @param array $content_permissions - permissions from the DB
     * @return bool
     */
    public function saveContentUsersGroupsPermissions($objects_name, $post_permissions, $content_permissions)
    {
    	// Insert new permissions
    	foreach ($post_permissions AS $post_permission) {
    		$existed = FALSE;
    		foreach ($content_permissions AS $db_permission) {
    			if ($post_permission['group_id'] == $db_permission['group_id'] && $post_permission['object_id'] == $db_permission['object_id']) {
    				$existed = TRUE;
    				break;
    			}
    		}
    		if (!$existed) {
    			$this->db->set('group_id', $post_permission['group_id']);
	    		$this->db->set('objects_name', $objects_name);
	    		$this->db->set('object_id', $post_permission['object_id']);
	    		$this->db->insert('users_groups_content_permissions');
    		}
    	}

    	// Delete inactive permissions
    	foreach ($content_permissions AS $db_permission) {
    		$existed = FALSE;
    		foreach ($post_permissions AS $post_permission) {
    			if ($post_permission['group_id'] == $db_permission['group_id'] && $post_permission['object_id'] == $db_permission['object_id']) {
    				$existed = TRUE;
    				break;
    			}
    		}
    		if (!$existed) {
	    		$this->db->where('id', $db_permission['permission_id']);
	    		$this->db->delete('users_groups_content_permissions');
    		}
    	}
    	
    	return true;
    }
    
    public function getContentPermissions($objects_name)
    {
    	$this->db->select('id AS permission_id');
    	$this->db->select('group_id');
    	$this->db->select('object_id');
    	$this->db->from('users_groups_content_permissions');
    	$this->db->where('objects_name', $objects_name);
    	$query = $this->db->get();

        return $query->result_array();
    }
}
?>