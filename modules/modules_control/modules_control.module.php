<?php
class modules_controlModule
{
	public $title = "Modules Control";
	public $version = "0.3";
	public $description = "Manage modules, ability to install/uninstall custom modules.";
	public $type = "core";
	public $permissions = array('Modules control');
	
	public $lang_files = "modules_control.php";
	
	public function routes()
	{
		$route['admin/modules/'] = array(
			'title' => LANG_MODULES_CONTROL_TITLE,
			'access' => 'Modules control',
		);

		return $route;
	}
	
	public function menu()
	{
		$menu[LANG_SETTINGS_MENU] = array(
			'children' => array(
				LANG_MODULES_LIST_MENU => array(
					'weight' => 4,
					'url' => 'admin/modules/',
					'access' => 'Modules control',
				),
			),
		);

		return $menu;
	}
}
?>