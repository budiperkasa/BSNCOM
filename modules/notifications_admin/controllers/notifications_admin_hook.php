<?php
include_once(MODULES_PATH . 'notifications/classes/notification_sender.class.php');

function na_handleEvents($CI, $args)
{
   	$admin_event = array_pop($args);
   	$event_tokens = array_pop($args);
   	$tokens = $event_tokens;
   	
   	switch ($admin_event) {
   		case 'Listing creation':
   			$event = 'Listing creation Admin notification';
   			$tokens['USER_NAME'] = $event_tokens['RECIPIENT_NAME'];
   			$tokens['USER_EMAIL'] = $event_tokens['RECIPIENT_EMAIL'];
   			break;
   		case 'Account creation step 2':
   			$event = 'Account creation Admin notification';
   			$tokens['USER_NAME'] = $event_tokens['RECIPIENT_NAME'];
   			$tokens['USER_EMAIL'] = $event_tokens['RECIPIENT_EMAIL'];
   			break;
   	}
   	$system_settings = registry::get('system_settings');
   	$site_settings = registry::get('site_settings');

   	$tokens['RECIPIENT_EMAIL'] = $system_settings['website_email'];
   	$tokens['RECIPIENT_NAME'] = $site_settings['website_title'];

   	$notification = new notificationSender($event);
   	$notification->send($tokens);
}
?>