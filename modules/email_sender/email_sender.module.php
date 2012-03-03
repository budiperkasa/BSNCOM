<?php
class email_senderModule
{
	public $title = "Email sender";
	public $version = "0.2";
	public $description = "Email sender and sender pages controller.";
	public $type = "core";
	
	public $lang_files = "email_sender.php";
	
	public function routes()
	{
		$route['email/send/listing_id/:num/target/:any/(.*)'] = array(
			'title' => LANG_SEND_EMAIL_MESSAGE_TITLE,
			'action' => 'send_listing',
		);
		
		$route['email/send/user_id/:num/(.*)'] = array(
			'title' => LANG_SEND_EMAIL_MESSAGE_TITLE,
			'action' => 'send_user',
		);

		return $route;
	}
}
?>