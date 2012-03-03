<?php
class settingsModule
{
	public $title = "System and site settings module";
	public $version = "0.1";
	public $description = "System, site, frontend settings management module.";
	public $type = "core";
	public $permissions = array('Edit system settings', 'Site settings edit', 'Edit web services settings');
	
	public $lang_files = "settings.php";
	
	public function routes()
	{
		$route['admin/settings/'] = array(
			'title' => LANG_SYSTEM_SETTINGS_TITLE,
			'access' => 'Edit system settings',
		);

		$route['admin/settings/site/'] = array(
			'title' => LANG_SITE_SETTINGS_TITLE,
			'action' => 'site',
			'access' => 'Site settings edit',
		);

		$route['admin/settings/frontend/'] = array(
			'title' => LANG_FRONTEND_SETTINGS_TITLE,
			'action' => 'frontend',
			'access' => 'Edit system settings',
		);
		
		$route['admin/settings/frontend/configure/:any/'] = array(
			'title' => LANG_FRONTEND_CONFIGURATION_TITLE,
			'action' => 'frontend_configure',
			'access' => 'Edit system settings',
		);
		$route['admin/settings/frontend/configure/:any/:num'] = array(
			'title' => LANG_FRONTEND_CONFIGURATION_TITLE,
			'action' => 'frontend_configure',
			'access' => 'Edit system settings',
		);
		
		/*$route['admin/settings/frontend/configure/:any/'] = array(
			'title' => LANG_FRONTEND_CONFIGURATION_TITLE,
			'action' => 'frontend_configure_alone_pages',
			'access' => 'Edit system settings',
		);*/
		
		$route['admin/settings/get_site_logo/'] = array(
			'action' => 'get_site_logo',
			'access' => 'Edit system settings',
		);

		$route['admin/settings/services/'] = array(
			'title' => LANG_FRONTEND_SERVICES_TITLE,
			'action' => 'services',
			'access' => 'Edit web services settings',
		);
		
		$route['admin/settings/titles/'] = array(
			'title' => LANG_TITLES_TEMPLATES_TITLE,
			'action' => 'titles',
			'access' => 'Edit web services settings',
		);
	
		return $route;
	}
	
	public function menu()
	{
		$menu[LANG_SETTINGS_MENU] = array(
			'weight' => 56,
			'children' => array(
				LANG_SYSTEM_SETTINGS_MENU => array(
					'weight' => 1,
					'url' => 'admin/settings',
					'access' => 'Edit system settings',
				),
				LANG_SITE_SETTINGS_MENU => array(
					'weight' => 2,
					'url' => 'admin/settings/site',
					'access' => 'Site settings edit',
				),
				LANG_FRONTEND_SETTINGS_MENU => array(
					'weight' => 3,
					'url' => 'admin/settings/frontend/',
					'sinonims' => array(array('admin', 'settings', 'frontend', 'configure', '%+')),
					'access' => 'Edit system settings',
				),
				LANG_WEBSERVICES_MENU => array(
					'weight' => 4,
					'url' => 'admin/settings/services',
					'access' => 'Edit web services settings',
				),
				LANG_TITLES_TEMPLATES_MENU => array(
					'weight' => 5,
					'url' => 'admin/settings/titles',
					'access' => 'Edit web services settings',
				),
			),
		);

		return $menu;
	}

	public function hooks()
	{
		$hook['runAutoBlocker'] = array(
			'weight' => 1,
			'exclusions' => array(
				'ajax/(.*)',
				'update/(.*)',
				'update_langs/(.*)'
			),
		);
		
		$hook['selectSiteSettings'] = array(
			'weight' => 0,
		);

		return $hook;
	}
}
?>