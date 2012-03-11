<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once(MODULES_PATH . 'users/classes/user.class.php');

class authorizationModel extends Model
{
	// select user row from DB
    public function checkLogin($email, $password)
    {
    	$this->db->select('u.*');
    	$this->db->from('users AS u');
    	$this->db->where('u.email', $email);
        $this->db->where('u.password', md5($password));
        $this->db->where('u.status', 2); // active user
        $query = $this->db->get();
        
        return $query->row_array();
    }

    public function setAuthorization($user_array, $remember_me = false)
    {
    	$user = new user($user_array['group_id'], $user_array['id']);
    	// Sets the user profile and linked content fields
    	$user->setUserFromArray($user_array);
    	// Update last login date and ip information
    	$user->setLoginInfo();
    	
    	$userdata = array(
    		'user_login' => $user->login, 
    		'user_id' => $user->id, 
    		'user_group_id' => $user->group_id,
    		'user_status' => $user->status,
    		'user_email' => $user->email,
    		'secure_hash' => md5($this->input->ip_address().$user->id.$user->email.$user->group_id.registry::get('secret')),
    	);

    	// Sets user session vars (user profile, id, user group id)
    	$this->session->set_userdata($userdata);
    	
    	// Remeber user's login fo 2 days
    	if ($remember_me) {
    		if ($this->config->item('remember_me_expiration'))
    			$expiration = $this->config->item('remember_me_expiration');
    		else 
    			$expiration = 172800;

	    	set_cookie(array(
	    		'name'   => 'remember_me',
	    		'value'  => serialize($userdata),
	    		'expire' => $expiration,
			));
    	}

        return $user;
    }

    public function checkAuthorization()
    {
    	if ($remember_me = get_cookie('remember_me')) {
    		@$remember_me = unserialize($remember_me);
    		if ($remember_me['secure_hash'] == md5($this->input->ip_address().$remember_me['user_id'].$remember_me['user_email'].$remember_me['user_group_id'].registry::get('secret'))) {
		    	// Sets user session vars (user profile, id, user group id) from cookie
		    	$this->session->set_userdata($remember_me);
    		}
    	}

    	return $this->session->userdata('user_id');
    }

    public function unsetAuthorization()
    {
    	$this->session->unset_userdata(array(
    		'user_login' => '',
    		'user_id' => '',
    		'user_group_id' => '',
    		'user_status' => '',
    		'user_email' => '',
    	));

    	delete_cookie('remember_me');
    }
}
?>