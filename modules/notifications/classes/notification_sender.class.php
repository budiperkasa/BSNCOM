<?php
class notificationSender
{
	private $_id;
	private $_event;
	private $_subject;
	private $_body;
	
	public function __construct($event)
	{
		$this->_event = $event;
		
		$CI = &get_instance();
		$CI->load->model('notifications', 'notifications');
		if ($row = $CI->notifications->getNotificationByEvent($this->_event))
			$this->setNotificationFromArray($row);
	}
	
	public function setNotificationFromArray($array)
	{
		$this->_id          = $array['id'];
		$this->_event       = $array['event'];
		$this->_subject     = $array['subject'];
		$this->_body        = $array['body'];
	}
	
	public function send($tokens)
	{
		if (!is_null($this->_id)) {
		   	$system_settings = registry::get('system_settings');
		   	$site_settings = registry::get('site_settings');
	
		   	// Add system and site settings to tokens
		   	$tokens = array_merge($tokens, $system_settings, $site_settings);
	
		   	$tokens['WEBSITE_URL'] = base_url();
		   	
		   	$message = $this->_body;
		   	$subject = $this->_subject;
	
		    foreach ($tokens AS $token=>$value) {
		    	if (!is_array($value)) {
			    	$message = str_ireplace('%' . $token . '%', $value, $message);
			    	$subject = str_ireplace('%' . $token . '%', $value, $subject);
		    	}
		    }
	
		    $CI = &get_instance();
		    $CI->load->library('email');
		    if ($CI->config->item('use_smtp_mail') && $CI->config->item('smtp_host') && $CI->config->item('smtp_port')) {
		    	$config = array(
		    		'protocol' => 'smtp',
		    		'smtp_host' => $CI->config->item('smtp_host'),
		    		'smtp_port' => $CI->config->item('smtp_port'),
		    		'smtp_user' => $CI->config->item('smtp_username'),
		    		'smtp_pass' => $CI->config->item('smtp_password'),
		    	);
		    	$CI->email->initialize($config);
		    }
		    $CI->email->from($system_settings['website_email'], $site_settings['website_title']);
		   	$CI->email->to($tokens['RECIPIENT_EMAIL'], $tokens['RECIPIENT_NAME']);
		   	$CI->email->reply_to($system_settings['website_email'], $site_settings['website_title']);
		   	$CI->email->subject($subject);
		   	$CI->email->message($message);
		
		   	return $CI->email->send();
		} else {
			return false;
		}
	}
}
?>