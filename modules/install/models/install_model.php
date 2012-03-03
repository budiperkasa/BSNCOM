<?php

class installModel extends Model
{
	public function createRootUser($form)
	{
		$this->db->set('group_id', 1);
		$this->db->set('status', 2);
		$this->db->set('login', $form['user_login']);
		$this->db->set('email', $form['user_email']);
		$this->db->set('password', md5($form['user_password']));
		$this->db->set('registration_date', date("Y-m-d H:i:s"));
		return $this->db->insert('users');
	}
	
	public function setSettings($form)
	{
		$this->db->set('value', $form['website_title']);
		$this->db->where('name', 'website_title');
		if ($this->db->update('site_settings')) {
			$this->db->set('value', $form['website_email']);
			$this->db->where('name', 'website_email');
			return $this->db->update('system_settings');
		} else {
			return false;
		}
	}
	
	public function completeInstallation()
	{
		$this->db->set('name', 'completed_installation');
		$this->db->set('value', 1);
		return $this->db->insert('system_settings');
	}
}
?>