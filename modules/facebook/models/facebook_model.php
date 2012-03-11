<?php
include_once(MODULES_PATH . 'notifications/classes/notification_sender.class.php');

class facebookModel extends Model
{
	public function getFcbUser($fcb_uid)
	{
		$this->db->select();
		$this->db->from('users');
		$this->db->where('facebook_uid', $fcb_uid);
		$query = $this->db->get();
		
		return $query->row_array();
	}
	
	public function insertFcbUser($fcb_user)
	{
		$this->db->select('id');
		$this->db->from('users_groups');
		$this->db->where('default_group', 1);
		$query = $this->db->get();
		if ($row = $query->row_array()) {
			$default_user_group_id = $row['id'];
			$hash = md5(time());
			$password = substr($hash, 0, 7);
			
			// raise Facebook account creation event
			$event_params = array(
				'PASSWORD' => $password, 
				'RECIPIENT_NAME' => $fcb_user['name'],
				'RECIPIENT_EMAIL' => $fcb_user['email']
			);
			$notification = new notificationSender('Facebook account creation');
			$notification->send($event_params);
			events::callEvent('Facebook account creation', $event_params);
	
			$this->db->set('login', $fcb_user['name']);
			$this->db->set('password', md5($password));
			$this->db->set('email', $fcb_user['email']);
			$this->db->set('group_id', $default_user_group_id);
			$this->db->set('status', 2);
			$this->db->set('registration_date', date("Y-m-d H:i:s"));
			$this->db->set('registration_hash', $hash);
			$this->db->set('facebook_uid', $fcb_user['uid']);
			$this->db->set('use_facebook_logo', true);
			$this->db->set('facebook_logo_file', $fcb_user['picture']);
			return $this->db->insert('users');
		} else {
			return false;
		}
	}
	
	public function updateFcbUser($fcb_user)
	{
		$this->db->set('login', $fcb_user['name']);
		$this->db->set('email', $fcb_user['email']);
		$this->db->set('user_logo_image', $fcb_user['picture']);
		$this->db->where('facebook_logo_file', $fcb_user['uid']);
		return $this->db->update('users');
	}
}
?>