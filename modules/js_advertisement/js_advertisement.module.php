<?php
class js_advertisementModule
{
	public $title = "Javascript and Flash Advertisement blocks module";
	public $version = "0.2";
	public $description = "Activate Javascript and Flash Advertisement blocks. For example Google Adsense ads. Settings on the 'Settings/JS Advertisement settings'";
	
	public $lang_files = "js_advertisement.php";
	
	public function routes()
	{
		$route['admin/js_advertisement/'] = array(
			'title' => LANG_JSADVERTISEMENT_MANAGE_BLOCKS_TITLE,
			'access' => 'Edit system settings',
		);
		
		$route['admin/js_advertisement/create/'] = array(
			'title' => LANG_JSADVERTISEMENT_CREATE_TITLE,
			'action' => 'create',
			'access' => 'Edit system settings',
		);
		
		$route['admin/js_advertisement/edit/:num'] = array(
			'title' => LANG_JSADVERTISEMENT_EDIT_TITLE,
			'action' => 'edit',
			'access' => 'Edit system settings',
		);
		
		$route['admin/js_advertisement/delete/:num'] = array(
			'title' => LANG_JSADVERTISEMENT_DELETE_TITLE,
			'action' => 'delete',
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
						LANG_JSADVERTISEMENT_SETTINGS_MENU => array(
							'url' => 'admin/js_advertisement/',
							'access' => 'Edit system settings',
							'sinonims' => array(
								array('admin', 'js_advertisement', 'create'),
								array('admin', 'js_advertisement', 'edit', '%+'),
								array('admin', 'js_advertisement', 'delete', '%+'),
							),
						),
					),
				),
			),
		);

		return $menu;
	}

	public function hooks()
	{
		$hook['js_advertisement_append'] = array(
			'exclusions' => array(
				'admin/:any',
				'login/(.*)',
				'locations/build_drop_box',
				'locations/ajax_autocomplete_request/',
				'refresh_captcha/',
				'locations/get_locations_path_by_id/',
			),
		);
		
		return $hook;
	}
}
?>