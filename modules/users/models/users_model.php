<?php
include_once(MODULES_PATH . 'notifications/classes/notification_sender.class.php');
include_once(MODULES_PATH . 'users/classes/user.class.php');
include_once(MODULES_PATH . 'users/classes/users_group.class.php');

class usersModel extends model
{
	private $_user_id;
	private $_cached_users = array();
	
	public function setUserId($user_id)
	{
		$this->_user_id = $user_id;
	}
	
    /**
     * Select all users table using paginator,
     * this executes optimized method with 3 queries
     *
     * @return array
     */
    public function selectUsers($orderby = 'id', $direction = 'asc', $args = array())
    {
    	// -----------------------------------------------------------------------------------
    	// Number of rows needs
    	//
    	$this->db->select('count(*) as count_rows');
    	$this->db->from('users');
    	if (isset($args['search_login'])) {
    		$this->db->like('login', urldecode(html_entity_decode($args['search_login'])));
    	}
    	if (isset($args['search_email'])) {
    		$this->db->like('email', urldecode(html_entity_decode($args['search_email'])));
    	}
    	if (isset($args['search_group'])) {
    		$this->db->where('group_id', urldecode($args['search_group']));
    	}
    	if (isset($args['search_status'])) {
    		$this->db->where('status', urldecode($args['search_status']));
    	}
    	if (isset($args['search_last_visit_date'])) {
    		$this->db->where('TO_DAYS(last_login_date) = ', 'TO_DAYS("' . date("Y-m-d", $args['search_last_visit_date']) . '")', false);
    	}
    	if (isset($args['search_from_last_visit_date'])) {
    		$this->db->where('TO_DAYS(last_login_date) >= ', 'TO_DAYS("' . date("Y-m-d", $args['search_from_last_visit_date']) . '")', false);
    	}
    	if (isset($args['search_to_last_visit_date'])) {
    		$this->db->where('TO_DAYS(last_login_date) <= ', 'TO_DAYS("' . date("Y-m-d", $args['search_to_last_visit_date']) . '")', false);
    	}
    	$query = $this->db->get();
    	$row = $query->row_array('count_rows');
    	$this->paginator->setCount($row['count_rows']);

    	// -----------------------------------------------------------------------------------
    	// Select id of users that will be shown
    	//
    	$this->db->select('id');
    	$this->db->from('users AS u');
    	if ($orderby)
    		$this->db->order_by($orderby, $direction);
    	if (isset($args['search_login'])) {
    		$this->db->like('u.login', urldecode(html_entity_decode($args['search_login'])));
    	}
    	if (isset($args['search_email'])) {
    		$this->db->like('u.email', urldecode(html_entity_decode($args['search_email'])));
    	}
    	if (isset($args['search_group'])) {
    		$this->db->where('u.group_id', urldecode($args['search_group']));
    	}
    	if (isset($args['search_status'])) {
    		$this->db->where('u.status', urldecode($args['search_status']));
    	}
    	if (isset($args['search_last_visit_date'])) {
    		$this->db->where('TO_DAYS(u.last_login_date) = ', 'TO_DAYS("' . date("Y-m-d", $args['search_last_visit_date']) . '")', false);
    	}
    	if (isset($args['search_from_last_visit_date'])) {
    		$this->db->where('TO_DAYS(u.last_login_date) >= ', 'TO_DAYS("' . date("Y-m-d", $args['search_from_last_visit_date']) . '")', false);
    	}
    	if (isset($args['search_to_last_visit_date'])) {
    		$this->db->where('TO_DAYS(u.last_login_date) <= ', 'TO_DAYS("' . date("Y-m-d", $args['search_to_last_visit_date']) . '")', false);
    	}
    	$query = $this->db->get();
    	$ids = $this->paginator->getResultIds($query->result_array());

    	if (!empty($ids)) {
	    	$this->db->select('*');
	    	$this->db->from('users');
	    	$_array = array();
	    	foreach ($ids AS $id) {
	    		$_array[] = $id['id'];
	    	}
	    	$this->db->where_in('id', $_array);
	    	if ($orderby)
	    		$this->db->order_by($orderby, $direction);
	    	$query = $this->db->get();
	    	$users = array();
    		foreach ($query->result_array() AS $row) {
    			$user = new user($row['group_id'], $row['id']);
    			$user->setUserFromArray($row);
    			$users[] = $user;
    		}
    		return $users;
    	} else {
    		return array();
    	}
    }
    
    public function getUserArrayById($user_id = null)
    {
    	if (is_null($user_id))
    		$user_id = $this->_user_id;
    	
    	$this->db->select();
    	$this->db->from('users as u');
    	$this->db->where('u.id', $user_id);
    	$query = $this->db->get();
    	
    	if ($query->num_rows()) {
    		return $query->row_array();
    	} else {
    		return false;
    	}
    }

    public function getUserById($user_id = null)
    {
    	if (is_null($user_id))
    		$user_id = $this->_user_id;

    	$cache_id = 'user_' . $user_id;
    	if (!$cache = $this->cache->load($cache_id)) {
			if ($row = $this->getUserArrayById($user_id)) {
	    		$user = new user($row['group_id'], $user_id);
	    		$user->setUserFromArray($row);
	    	} else {
	    		return false;
	    	}

			$this->cache->save($user, $cache_id, array('users', 'users_' . $user_id));
		} else {
			$user = $cache;
		}

		return $user;
    }
    
    public function getUserByUrl($url_part)
    {
    	$this->db->select('id');
    	$this->db->select('group_id');
    	$this->db->from('users');
    	if (is_numeric($url_part))
    		$this->db->where('id', $url_part);
    	else
    		$this->db->where('seo_login', $url_part);
    	$row = $this->db->get()->row_array();


    	if (isset($this->_cached_users[$row['id']]))
    		return $this->_cached_users[$row['id']];

    	if ($row = $this->getUserArrayById($row['id'])) {
    		$user = new user($row['group_id'], $row['id']);
    		$user->setUserFromArray($row);
    		
    		$this->_cached_users[$row['id']] = $user;
    		return $this->_cached_users[$row['id']];
    	} else {
    		return false;
    	}
    }
    
    public function getUserByLogin($login)
    {
    	$this->db->select();
    	$this->db->select('group_id');
    	$this->db->from('users');
    	$this->db->where('login', $login);
    	if ($row = $this->db->get()->row_array()) {
	    	if (isset($this->_cached_users[$row['id']]))
	    		return $this->_cached_users[$row['id']];

    		$user = new user($row['group_id'], $row['id']);
    		$user->setUserFromArray($row);

    		$this->_cached_users[$row['id']] = $user;
    		return $this->_cached_users[$row['id']];
    	} else {
    		return false;
    	}
    }

    public function saveUserGroup($form)
    {
    	$this->db->set('group_id', $form['group_id']);
    	$this->db->where('id', $this->_user_id);
    	$this->db->where('id !=', '1');
    	return $this->db->update('users');
    }
    
    public function saveUserStatus($form)
    {
    	$this->db->set('status', $form['status']);
    	$this->db->where('id', $this->_user_id);
    	$this->db->where('id !=', '1');
    	return $this->db->update('users');
    }
    
    /**
     * Update last login date and ip information of user
     *
     */
    public function setLoginInfo($date, $ip)
    {
    	$this->db->set('last_ip', $ip);
    	$this->db->set('last_login_date', $date);
		$this->db->where('id', $this->_user_id);

    	return $this->db->update('users');
    }
    
    /**
     * checks is user with such email already existed?
     *
     * @param string $email
     */
    public function is_email($email)
    {
    	$this->db->select();
    	$this->db->where('email', $email);
    	if ($this->_user_id)
    		$this->db->where('id !=', $this->_user_id);
    	$this->db->from('users');
    	$query = $this->db->get();

		return $query->num_rows();
    }
    
    /**
     * checks is user with such login name already existed?
     *
     * @param string $login
     */
    public function is_login($login)
    {
    	$this->db->select();
    	$this->db->where('login', $login);
    	if ($this->_user_id)
    		$this->db->where('id !=', $this->_user_id);
    	$this->db->from('users');
    	$query = $this->db->get();

		return $query->num_rows();
    }
    
    /**
     * checks is user with such seo login name already existed?
     *
     * @param string $seo_login
     */
    public function is_seo_login($seo_login)
    {
    	$this->db->select();
    	$this->db->where('seo_login', $seo_login);
    	if ($this->_user_id)
    		$this->db->where('id !=', $this->_user_id);
    	$this->db->from('users');
    	$query = $this->db->get();

		return $query->num_rows();
    }
    
    public function activateUser($hash)
    {
    	// Delete inactive users from DB
    	$this->db->delete('users', array('status' => 1, 'registration_date <' => date("Y-m-d H:i:s", time() - 3600)));
    	
    	$this->db->select();
    	$this->db->from('users');
    	$this->db->where('status', 1);
    	$this->db->where('registration_hash', $hash);
    	$query = $this->db->get();
    	if ($row = $query->row_array()) {
    		$this->db->set('status', 2);
    		$this->db->where('id', $row['id']);
    		if ($this->db->update('users')) {

    			$this->db->select();
		    	$this->db->from('users');
		    	$this->db->where('id', $row['id']);
    			return $this->db->get()->row_array();
    		} else {
    			return FALSE;
    		}
    	}
    	return FALSE;
    }
    
    public function isHash($hash)
    {
    	$this->db->select();
    	$this->db->from('users');
    	$this->db->where('registration_hash', $hash);
    	$query = $this->db->get();

    	if ($row = $query->row_array()) {
    		$user = new user($row['group_id'], $row['id']);
    		$user->setUserFromArray($row);
    		return $user;
    	} else {
    		return false;
    	}
    }
    
    public function saveUserHash($hash, $email)
    {
    	$this->db->set('registration_hash', $hash);
    	$this->db->where('email', $email);
    	if ($this->db->update('users')) {
    		$this->db->select();
	    	$this->db->from('users');
	    	$this->db->where('email', $email);
	    	$query = $this->db->get();

	    	return $query->row_array();
    	}
    }
    
    /**
     * saves existed user's login information
     *
     * @param array $form
     * @param bool $my_profile_id
     */
    public function saveUser($form_result, $user, $is_my_profile = null)
    {
		if (!empty($form_result['password'])) {
			$this->db->set('password', md5($form_result['password']));
		}
		$this->db->set('email', $form_result['email']);
		$this->db->set('login', $form_result['login']);
		if (isset($form_result['status'])) {
			$this->db->set('status', $form_result['status']);
		}
		if (!empty($is_my_profile)) {
			$this->db->where('id', $this->session->userdata('user_id'));
		} else {
			$this->db->where('id', $this->_user_id);
		}
		
		if ($user->users_group->is_own_page) {
			if ($user->users_group->use_seo_name)
				$this->db->set('seo_login', $form_result['seo_login']);
			if ($user->users_group->meta_enabled) {
				$this->db->set('meta_description', $form_result['meta_description']);
				$this->db->set('meta_keywords', $form_result['meta_keywords']);
			}
		}
		if ($user->users_group->logo_enabled) {
			if ($this->load->is_module_loaded('facebook') && $user->facebook_uid) {
				$this->db->set('use_facebook_logo', $form_result['use_facebook_logo']);
				if (isset($form_result['use_facebook_logo']) && $form_result['use_facebook_logo']) {
					$this->db->set('facebook_logo_file', $form_result['facebook_logo_file']);
				} else {
					// Use custom uploaded logo
					$this->db->set('use_facebook_logo', false);
					$this->db->set('user_logo_image', $form_result['user_logo_image']);
				}
			} else {
				// Use custom uploaded logo
				$this->db->set('user_logo_image', $form_result['user_logo_image']);
			}
		}

		if ($this->db->update('users')) {
			// Update user object in the session
			if (!empty($is_my_profile)) {
				$this->session->set_userdata(array('user_login' => $form_result['login']));
				$this->session->set_userdata(array('user_email' => $form_result['email']));
			}
			return true;
		}
    }
    
    public function createUser($group_id, $user, $activation_link, $form, $status = 1) // default status unverified
    {
		$this->db->set('login', $form['login']);
		$this->db->set('password', md5($form['password']));
		$this->db->set('email', $form['email']);
		$this->db->set('group_id', $group_id);
		$this->db->set('status', $status);
		if ($user->users_group->is_own_page && isset($form['seo_login'])) {
			if ($user->users_group->use_seo_name)
				$this->db->set('seo_login', $form['seo_login']);
			if ($user->users_group->meta_enabled) {
				$this->db->set('meta_description', $form['meta_description']);
				$this->db->set('meta_keywords', $form['meta_keywords']);
			}
		}
		if ($user->users_group->logo_enabled) {
			if (isset($form['user_logo_image'])) {
				$this->db->set('user_logo_image', $form['user_logo_image']);
			}
			/*if ($this->load->is_module_loaded('facebook') && isset($form['user_logo_image'])) {
				$this->db->set('use_facebook_logo', 0);
			}*/
		}
		$this->db->set('registration_date', date("Y-m-d H:i:s"));
		$this->db->set('registration_hash', $activation_link);
		$this->db->insert('users');
		
		return $this->db->insert_id();
    }
    
    public function saveNewPassword($hash, $new_pass)
    {
	    $this->db->set('password', md5($new_pass));
	    $this->db->where('registration_hash', $hash);
	    return $this->db->update('users');
    }

    public function blockUsers($users_ids)
    {
    	// Protect first user from blocking
    	if ($key = array_search(1, $users_ids)) {
    		unset($users_ids[$key]);
    	}

	    $this->db->set('status', '3');
	    $this->db->where_in('id', $users_ids);
	    if ($this->db->update('users')) {
	    	foreach ($users_ids AS $id) {
	    		$user = $this->getUserById($id);
	
	    		// Raise event User blocking
	    		$event_params = array(
					'RECIPIENT_NAME' => $user->login,
					'RECIPIENT_EMAIL' => $user->email,
	    			'USER' => $user
				);
	    		$notification = new notificationSender('User blocking');
				$notification->send($event_params);
				events::callEvent('User blocking', $event_params);
	    	}
	    	return true;
	    }
    }
    
    public function deleteUserById()
    {
    	// Delete user
        $this->db->where('id', $this->_user_id);
        $this->db->where('id !=', '1');
        $this->db->delete('users');

    	// Delete listings with their locations, images, videos, files
        $CI = &get_instance();
        $CI->load->model('listings', 'listings');
        
        $this->db->select('id');
        $this->db->from('listings');
        $this->db->where('owner_id', $this->_user_id);
        $this->db->where('owner_id !=', '1');
        $query = $this->db->get();
        foreach ($query->result_array() AS $row)
        	$CI->listings->deleteListingById($row['id']);

        // Delete all user's claiming
        $this->db->or_where('from_user_id', $this->_user_id);
        $this->db->or_where('to_user_id', $this->_user_id);
        $this->db->delete('listings_claims');
        
    	return true;
    }

    public function getSuggestions($query)
    {
    	$this->db->select('login');
    	$this->db->from('users');
    	$this->db->like('login', $query, 'after');
    	$sql_query = $this->db->get();
    	
    	return $sql_query->result_array();
    }
}
?>