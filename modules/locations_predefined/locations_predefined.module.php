<?php
class locations_predefinedModule
{
	public $title = "Predefined Locations";
	public $version = "0.1";
	public $description = "Locations tree management module. Has the ability to manage locations and unlimited depth locations levels using JS and AJAX.";
	public $permissions = array('Manage predefined locations');
	public $type = "core";
	public $lang_files = "locations_predefined.php";
	
	public function routes()
	{
		$route['admin/locations/levels/'] = array(
			'title' => LANG_VIEW_LOCATIONS_LEVELS_TITLE,
			'controller' => 'admin_locations',
			'action' => 'levels',
			'access' => 'Manage predefined locations',
		);
		
		$route['admin/locations/levels/create/'] = array(
			'title' => LANG_CREATE_LOCATIONS_LEVEL_TITLE,
			'controller' => 'admin_locations',
			'action' => 'levels_create',
			'access' => 'Manage predefined locations',
		);
		
		$route['admin/locations/levels/edit/:num'] = array(
			'title' => LANG_EDIT_LOCATIONS_LEVEL_TITLE,
			'controller' => 'admin_locations',
			'action' => 'levels_edit',
			'access' => 'Manage predefined locations',
		);
		
		$route['admin/locations/levels/delete/:num'] = array(
			'title' => LANG_DELETE_LOCATIONS_LEVEL_TITLE,
			'controller' => 'admin_locations',
			'action' => 'levels_delete',
			'access' => 'Manage predefined locations',
		);
		
		// ----------------------------------------------------------
		// Admin Locations
		$route['admin/locations/'] = array(
			'title' => LANG_VIEW_LOCATIONS_TITLE,
			'controller' => 'admin_locations',
			'access' => 'Manage predefined locations',
		);
		
		$route['admin/locations/save/'] = array(
			'controller' => 'admin_locations',
			'action' => 'save',
			'access' => 'Manage predefined locations',
		);
		
		$route['admin/locations/delete/'] = array(
			'controller' => 'admin_locations',
			'action' => 'delete',
			'access' => 'Manage predefined locations',
		);
		
		$route['admin/locations/synchronize/'] = array(
			'controller' => 'admin_locations',
			'action' => 'synchronize',
			'access' => 'Manage predefined locations',
		);
		
		$route['admin/locations/regeocode/'] = array(
			'controller' => 'admin_locations',
			'action' => 'regeocode',
			'access' => 'Manage predefined locations',
		);
		
		$route['admin/locations/label/'] = array(
			'controller' => 'admin_locations',
			'action' => 'label',
			'access' => 'Manage predefined locations',
		);
		$route['admin/locations/label/:num'] = array(
			'controller' => 'admin_locations',
			'action' => 'label',
			'access' => 'Manage predefined locations',
		);
		
		$route['admin/locations/create/'] = array(
			'title' => LANG_CREATE_LOCATION_TITLE,
			'controller' => 'admin_locations',
			'action' => 'location_create',
			'access' => 'Manage predefined locations',
		);
		
		$route['admin/locations/create_child/:num'] = array(
			'title' => LANG_CREATE_CHILD_LOCATION_TITLE,
			'controller' => 'admin_locations',
			'action' => 'location_create_child',
			'access' => 'Manage predefined locations',
		);
		
		$route['admin/locations/edit/:num'] = array(
			'title' => LANG_EDIT_LOCATION_TITLE,
			'controller' => 'admin_locations',
			'action' => 'location_edit',
			'access' => 'Manage predefined locations',
		);
		
		$route['admin/locations/delete/:num'] = array(
			'title' => LANG_DELETE_LOCATION_TITLE,
			'controller' => 'admin_locations',
			'action' => 'location_delete',
			'access' => 'Manage predefined locations',
		);

		$route['admin/locations/settings/'] = array(
			'title' => LANG_LOCATIONS_SETTINGS_TITLE,
			'controller' => 'admin_locations',
			'action' => 'locations_settings',
			'access' => 'Edit system settings',
		);
		
		$route['ajax/locations_request/(.*)'] = array(
			'controller' => 'admin_locations',
			'action' => 'ajax_locations_request',
			'access' => 'Manage predefined locations',
		);
		
		
		
		$route['ajax/locations/build_drop_box/'] = array(
			'controller' => 'users_locations',
			'action' => 'build_drop_box',
		);
		
		$route['ajax/locations/autocomplete_request/'] = array(
			'controller' => 'users_locations',
			'action' => 'autocomplete_request',
		);
		
		/*$route['locations/get_locations_path_by_id/'] = array(
			'controller' => 'users_locations',
			'action' => 'get_locations_path_by_id',
		);
		
		$route['locations/ajax_autocomplete_request/'] = array(
			'controller' => 'users_locations',
			'action' => 'ajax_autocomplete_request',
		);*/

		return $route;
	}
	
	public function menu()
	{
		$menu[LANG_LOCATIONS_MENU] = array(
			'weight' => 53,
			'access' => 'Manage predefined locations',
			'children' => array(
				LANG_MANAGE_LOCATIONS_LEVELS_MENU => array(
					'weight' => 1,
					'url' => 'admin/locations/levels/',
					'sinonims' => array(
						array('admin', 'locations', 'levels', 'create'),
						array('admin', 'locations', 'levels', 'edit', '%'),
						array('admin', 'locations', 'levels', 'delete', '%'),
					),
				),
				LANG_MANAGE_LOCATIONS_MENU => array(
					'weight' => 2,
					'url' => 'admin/locations/',
					'sinonims' => array(
						array('admin', 'locations', 'create'),
						array('admin', 'locations', 'create_child'),
						array('admin', 'locations', 'edit', '%'),
						array('admin', 'locations', 'delete', '%'),
					),
				),
			),
		);
		
		
		$menu[LANG_SETTINGS_MENU] = array(
			'children' => array(
				LANG_MISCELLANEOUS_SETTINGS_MENU => array(
					'weight' => 60,
					'children' => array(
						LANG_LOCATIONS_SETTINGS_MENU => array(
							'url' => 'admin/locations/settings/',
							'access' => 'Edit system settings',
						),
					),
				),
			),
		);
	
		return $menu;
	}
	
	public function hooks()
	{
		$hooks['handleLocation'] = array(
			'weight' => 0,
			'exclusions' => array(
				'admin/:any',
				'login/',
				'ajax/:any',
			),
		);
		
		return $hooks;
	}

}
?>