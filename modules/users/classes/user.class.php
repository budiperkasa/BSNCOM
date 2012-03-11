<?php
include_once(MODULES_PATH . 'content_fields/classes/content_fields.class.php');
include_once(MODULES_PATH . 'users/classes/users_group.class.php');

class user
{
	public $id;
	public $group_id;
	public $users_group;
	public $status;
	public $login;
	public $seo_login;
	public $meta_description;
	public $meta_keywords;
	public $user_logo_image;
	public $password;
	public $email;
	public $last_ip;
	public $last_login_date;
	public $registration_date;
	public $registration_hash;
	public $facebook_uid;
	public $use_facebook_logo;
	public $facebook_logo_file;

	public $content_fields;
	
	/**
	 *
	 * @param int $user_group_id - custom content fields group name builds from PREFIX and user group id (required)
	 * @param int $user_id
	 */
	public function __construct($users_group_id, $user_id = null)
	{
		if (is_null($user_id))
			$this->id = 'new';
		else 
			$this->id = $user_id;

		$this->group_id = $users_group_id;

		// 1: first user
		// 2: active
        // 3: blocked
		$this->status = 2;

		// Link user profile to content fields group
		$CI = &get_instance();
		$CI->load->model('users_groups', 'users');
		$this->users_group = $CI->users_groups->getUsersGroupById($users_group_id);

		$this->content_fields = new contentFields(USERS_PROFILE_GROUP_CUSTOM_NAME, $users_group_id, $user_id);
	}
	
	/**
	 * Sets user profile
	 *
	 * @param array $array
	 */
	public function setUserFromArray($row)
    {
    	if (isset($row['id']))
    		$this->id = $row['id'];
    	if (isset($row['status']))
        	$this->status = $row['status'];
        $this->login = $row['login'];
        if ($this->users_group->is_own_page && $this->users_group->use_seo_name && isset($row['seo_login']))
        	$this->seo_login = $row['seo_login'];
        if ($this->users_group->is_own_page && $this->users_group->meta_enabled) {
        	if (isset($row['meta_description']))
        		$this->meta_description = $row['meta_description'];
        	if (isset($row['meta_keywords']))
        		$this->meta_keywords = $row['meta_keywords'];
        }
        if ($this->users_group->logo_enabled && isset($row['meta_keywords']))
    		$this->user_logo_image = $row['user_logo_image'];
    	if (isset($row['password']))
        	$this->password = $row['password'];
        $this->email = $row['email'];
        if (isset($row['last_ip']))
        	$this->last_ip = $row['last_ip'];
        if (isset($row['last_login_date']))
        	$this->last_login_date = $row['last_login_date'];
        if (isset($row['registration_date']))
        	$this->registration_date = $row['registration_date'];
        if (isset($row['registration_hash']))
        	$this->registration_hash = $row['registration_hash'];
        if (isset($row['facebook_uid'])) {
        	$this->facebook_uid = $row['facebook_uid'];
        	$this->use_facebook_logo = $row['use_facebook_logo'];
        	$this->facebook_logo_file = $row['facebook_logo_file'];
        }

        $this->content_fields->select();
    }
    
    public function inputMode()
    {
    	return $this->content_fields->inputMode();
    }
    
    public function outputMode()
    {
    	return $this->content_fields->outputMode();
    }
    
    public function validateFields($form_validation)
    {
    	$this->content_fields->validate($form_validation);
    }
    
    public function fieldsCount()
    {
    	return $this->content_fields->fieldsCount();
    }

    /**
     * Update last login date and ip information of user
     *
     */
    public function setLoginInfo()
    {
    	$CI = &get_instance();
    	$CI->load->model('users', 'users');
    	$CI->users->setUserId($this->id);
    	
    	$date = date("Y-m-d H:i:s");
    	$ip = $CI->input->ip_address();
    	$CI->users->setLoginInfo($date, $ip);
    }
    
    public function saveFields($user_id, $form_result)
	{
		$this->content_fields->setObjectId($user_id);
		return $this->content_fields->save($form_result);
	}
	
	public function updateFields($form_result)
	{
		$this->content_fields->select();
		return $this->content_fields->update($form_result);
	}
	
	public function deleteFields()
	{
		$this->content_fields->select();
		return $this->content_fields->delete();
	}

	public function renderThmbImage()
	{
		$CI = &get_instance();
		$users_content = $CI->config->item('users_content_http_path');
		
		$public_path = registry::get('public_path');
		
		$width = $this->users_group->explodeSize('logo_thumbnail_size', 0);
		$height = $this->users_group->explodeSize('logo_thumbnail_size', 1);
		
		$resizer_code = '
		<script language="javascript" type="text/javascript">
		$(document).ready(function() {
			if ($(".user_image_' . $this->id . '").css("height")>$(".user_image_' . $this->id . '").css("width")) {
				var h = ' . $height . ';
				var w = Math.ceil($(".user_image_' . $this->id . '").css("width")/$(".user_image_' . $this->id . '").css("height") * ' . $width . ');
				
			} else {
				var w = ' . $width . ';
				var h = Math.ceil($(".user_image_' . $this->id . '").css("height")/$(".user_image_' . $this->id . '").css("width") * ' . $height . ');
			}
			$(".user_image_' . $this->id . '").css({ height: h, width: w });
		});
		</script>';

		if ($this->facebook_uid && $this->use_facebook_logo && $this->facebook_logo_file) {
			// Facebook logo
			return $resizer_code . '<img class="user_image_' . $this->id . '" src="' . $this->facebook_logo_file . '" />';
		} elseif ($this->user_logo_image) {
			// Custom uploaded logo
        	return $resizer_code . '<img class="user_image_' . $this->id . '" src="' . $users_content . 'users_images/users_thmbs_logos/' . $this->user_logo_image . '" />';
		} else {
			// Default logo
			return $resizer_code . '<img class="user_image_' . $this->id . '" src="' . $public_path . 'images/default_avatar.jpg" />';
		}
	}
	
	public function getUniqueId()
	{
		if ($this->users_group->use_seo_name && $this->seo_login) {
			return $this->seo_login;
		} else {
			return $this->id;
		}
	}
	
	public function profileUrl()
	{
		return site_url('users/' . $this->getUniqueId());
	}
}
?>