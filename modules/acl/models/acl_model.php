<?php
class aclModel extends model
{
	private static $permissions_of_group = array();
	private static $content_permissions_of_group = array();

	/**
	 * checks access of users group for the passed access
	 *
	 * @param int $user_group_id
	 * @param string $access
	 * @return bool
	 */
    public function checkAccess($user_group_id, $access)
    {
    	$permissions = $this->getAccessTableForUserGroup($user_group_id);
    	
    	if (!is_array($access)) {
    		$access = array($access);
    	}
    	
    	if (array_key_exists('AND', $access)) {
			// If there AND connector - all accesses must be in access table
			foreach ($access as $access_item) {
				if (!in_array($access_item, $permissions)) {
					return false;
				}
			}
		} else {
			// If there no connectors or they are OR - at least one access must be in access table
			foreach ($access as $access_item) {
				if (in_array($access_item, $permissions)) {
    				return true;
    			}
			}
			return false;
		}
    	return true;
    }

    /**
     * select access permissions of passed users group id
     * and saves it in static array variable
     *
     * @param int $user_group_id
     * @return array
     */
    public function getAccessTableForUserGroup($user_group_id)
    {
    	if (!empty(self::$permissions_of_group))
    		return self::$permissions_of_group;

        $this->db->select('ugp.function_access');
        $this->db->from('users_groups_permissions AS ugp');
        // If user wasn't logged in - take access table of default users group
        if ($user_group_id !== FALSE) {
        	$this->db->where('ugp.group_id', $user_group_id);
        } else {
        	$this->db->join('users_groups AS ug', 'ug.id=ugp.group_id');
        	$this->db->where('ug.default_group', 1);
        }
        $query = $this->db->get();

        $permissions = array();
        foreach ($query->result_array() as $row) {
        	$permissions[] = $row['function_access'];
        }
        self::$permissions_of_group = $permissions;
        
        /* ---------------------------------------------------------------
        *
        * Implement access to 'Control permissions' function only for root admin user
        *
        */
        if ($this->session->userdata('user_id') == 1) {
    		self::$permissions_of_group[] = 'Control permissions';
    	}

        return self::$permissions_of_group;
    }
    
    
    /**
	 * checks access of users group for the passed access
	 *
	 * @param int $user_group_id
	 * @param string $access
	 * @return bool
	 */
    public function checkContentAccess($user_group_id, $object_name, $object_id)
    {
    	$permissions = $this->getContentAccessTableForUserGroup($object_name, $user_group_id);

    	return in_array($object_id, $permissions);
    }
    
    /**
     * select access permissions of passed users group id
     * and saves it in static array variable
     *
     * @param int $user_group_id
     * @return array
     */
    public function getContentAccessTableForUserGroup($objects_name, $user_group_id)
    {
    	if (!empty(self::$content_permissions_of_group[$objects_name]))
    		return self::$content_permissions_of_group[$objects_name];

        $this->db->select('ugcp.object_id');
        $this->db->from('users_groups_content_permissions AS ugcp');
        // If user wasn't logged in - take access table of default users group
        if ($user_group_id !== FALSE) {
        	$this->db->where('ugcp.group_id', $user_group_id);
        } else {
        	$this->db->join('users_groups AS ug', 'ug.id=ugcp.group_id');
        	$this->db->where('ug.default_group', 1);
        }
        $this->db->where('ugcp.objects_name', $objects_name);
        $query = $this->db->get();

        $permissions = array();
        foreach ($query->result_array() as $row) {
        	$permissions[] = $row['object_id'];
        }
        self::$content_permissions_of_group[$objects_name] = $permissions;

        return self::$content_permissions_of_group[$objects_name];
    }
}
?>