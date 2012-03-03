<?php
class calendarModule
{
	public $title = "Calendar widget";
	public $version = "0.1";
	public $description = "Calendar block assigned to content field and type";
	public $lang_files = "calendar.php";

	public function routes()
	{
		$route['admin/calendar_settings/'] = array(
			'title' => LANG_CALENDAR_SETTINGS_TITLE,
			'action' => 'calendar_settings',
			'access' => 'Edit system settings',
		);

		return $route;
	}
	
	public function menu()
	{
		$menu[LANG_SETTINGS_MENU] = array(
			'children' => array(
				LANG_MISCELLANEOUS_SETTINGS_MENU => array(
					'children' => array(
						LANG_CALENDAR_SETTINGS_MENU => array(
							'url' => 'admin/calendar_settings/',
							'access' => 'Edit system settings',
						),
					),
				),
			),
		);

		return $menu;
	}
}
?>