<?php
class notificationsModule
{
	public $title = "Notifications management";
	public $version = "0.1";
	public $description = "Users receive email notification on different system events, like new listing creation or user blocking.";
	public $type = "core";
	public $permissions = array('Manage email notifications');
	
	public $lang_files = "notifications.php";
	
	public function routes()
	{
		$route['admin/notifications/'] = array(
			'title' => LANG_VIEW_EMAIL_NOTIFICATIONS_TITLE,
			'action' => 'index',
			'access' => 'Manage email notifications',
		);
		
		$route['admin/notifications/edit/:num/'] = array(
			'title' => LANG_EDIT_EMAIL_NOTIFICATIONS_TITLE,
			'action' => 'edit',
			'access' => 'Manage email notifications',
		);

		return $route;
	}
	
	/**
	 * sets the list in the main admin menu
	 *
	 * @return array
	 */
	public function menu()
	{
		$menu[LANG_EMAIL_NOTIFICATIONS_MENU] = array(
			'weight' => 57,
			'access' => 'Manage email notifications',
			'children' => array(
				LANG_VIEW_EMAIL_NOTIFICATIONS_TITLE => array(
					'url' => 'admin/notifications/',
					'sinonims' => array(
						array('admin', 'notifications', 'edit', '%')
					),
				),
			),
		);

		return $menu;
	}
}
?>