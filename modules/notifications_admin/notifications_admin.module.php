<?php
class notifications_adminModule
{
	public $title = "Admin notifications";
	public $version = "0.1";
	public $description = "Admin notifications during listing creation or new user sign up.";

	/**
	 * hook send email notifications events
	 *
	 */
	public function hooks()
	{
		$hook['na_handleEvents'] = array(
			'events' => array('Listing creation', 'Account creation step 2'),
		);
		
		return $hook;
	}
}
?>