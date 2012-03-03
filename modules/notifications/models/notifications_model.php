<?php
class notificationsModel extends model
{
	private $_notification_id;
	
	public function setNotificationId($notification_id)
	{
		$this->_notification_id = $notification_id;
	}
	
    /**
     * select all email notifications from DB
     *
     * @return array
     */
    public function getAllNotifications()
    {
    	$this->db->select();
    	$this->db->from('email_notification_templates');
    	$query = $this->db->get();
    	
    	return $query->result_array();
    }
    
    /**
     * select notification record from DB by Id
     *
     * @param int $notification_id
     * @return array
     */
    public function getNotificationById()
    {
    	$this->db->select();
    	$this->db->from('email_notification_templates');
    	$this->db->where('id', $this->_notification_id);
    	$query = $this->db->get();
    	
    	return $query->row_array();
    }
    
    /**
     * select notification record from DB by event name
     *
     * @param string $event
     * @return array
     */
    public function getNotificationByEvent($event)
    {
    	$this->db->select();
    	$this->db->from('email_notification_templates');
    	$this->db->where('event', $event);
    	$query = $this->db->get();
    	
    	return $query->row_array();
    }
    
    /**
     * save notification by id
     *
     * @param object $form
     * @return bool
     */
    public function saveNotification($form)
    {
    	$this->db->set('body', $form['body']);
    	$this->db->set('subject', $form['subject']);
    	$this->db->where('id', $this->_notification_id);
    	return $this->db->update('email_notification_templates');
    }
}
?>