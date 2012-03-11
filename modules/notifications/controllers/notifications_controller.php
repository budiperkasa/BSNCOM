<?php
include_once(MODULES_PATH . 'notifications/classes/notification.class.php');

class notificationsController extends controller
{
	public function __construct($components)
	{
		// Disable global XSS filtering
		$this->config = &load_class('Config');
		$this->config->set_item('global_xss_filtering', FALSE);

		parent::Controller($components);
	}
	
    public function index()
    {
    	$this->load->model('notifications');
    	$notifications = $this->notifications->getAllNotifications();
    	
    	$view = $this->load->view();
    	$view->assign('notifications', $notifications);
    	$view->display('notifications/notifications.tpl');
    }
    
    public function edit($notification_id)
    {
    	$this->load->model('notifications');
    	$this->notifications->setNotificationId($notification_id);
    	if (!$notification_array = $this->notifications->getNotificationById()) {
	    	exit(0);
	    }
    	
    	$notification = new notificationClass;
    	if ($this->input->post('submit')) {
			$this->form_validation->set_rules('body', LANG_NOTIFICATION_BODY, 'required|max_length[10000]');
			$this->form_validation->set_rules('subject', LANG_NOTIFICATION_SUBJECT, 'required|max_length[200]');

    		if ($this->form_validation->run() !== FALSE) {
    			if ($this->notifications->saveNotification($this->form_validation->result_array())) {
    				$this->setSuccess(LANG_NOTIFICATION_SEND_SUCCESS_1 . ' "' . $notification_array['event'] . '" ' . LANG_NOTIFICATION_SEND_SUCCESS_2);
    				redirect('admin/notifications/');
    			}
    		} else {
    			$notification->setNotificationFromArray($this->form_validation->result_array());
    		}
    	} else {
    		$notification->setNotificationFromArray($notification_array);
    	}
    	
    	registry::set('breadcrumbs', array(
	    	'admin/notifications/' => LANG_VIEW_EMAIL_NOTIFICATIONS_TITLE,
	    	LANG_EDIT_EMAIL_NOTIFICATIONS_TITLE . " '" . $notification_array['event'] ."'",
	    ));

    	$view = $this->load->view();
    	$view->assign('notification', $notification);
    	$view->assign('notification_array', $notification_array);
    	$view->display('notifications/notification_settings.tpl');
    }
}
?>