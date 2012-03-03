<?php
class types_levelsModule
{
	public $title = "Types and Levels";
	public $version = "0.1";
	public $description = "The key objects of the directory management system - types and levels of listings.";
	public $type = "core";
	public $permissions = array('Manage types and levels');
	
	public $lang_files = "types_levels.php";
	
	public function routes()
	{
		$route['admin/types/'] = array(
			'title' => LANG_VIEW_TYPES_TITLE,
			'controller' => 'types',
			'access' => 'Manage types and levels',
		);
		
		$route['admin/types/create/'] = array(
			'title' => LANG_CREATE_TYPE_TITLE,
			'controller' => 'types',
			'action' => 'create',
			'access' => 'Manage types and levels',
		);
		
		$route['admin/types/edit/:num/'] = array(
			'title' => LANG_EDIT_TYPE_TITLE,
			'controller' => 'types',
			'action' => 'edit',
			'access' => 'Manage types and levels',
		);
		
		$route['admin/types/delete/:num/'] = array(
			'title' => LANG_TYPE_DELETE_SUCCESS,
			'controller' => 'types',
			'action' => 'delete',
			'access' => 'Manage types and levels',
		);

		//-------------------------------------------------------------------
		
		$route['admin/levels/type_id/:num/'] = array(
			'title' => LANG_VIEW_LEVELS_TITLE,
			'controller' => 'levels',
			'action' => 'levels_of_type',
			'access' => 'Manage types and levels',
		);
		
		$route['admin/levels/create/type_id/:num/'] = array(
			'title' => LANG_CREATE_LEVEL_TITLE,
			'controller' => 'levels',
			'action' => 'level_create',
			'access' => 'Manage types and levels',
		);

		$route['admin/levels/edit/:num/'] = array(
			'title' => LANG_EDIT_LEVEL_TITLE,
			'controller' => 'levels',
			'action' => 'level_edit',
			'access' => 'Manage types and levels',
		);

		$route['admin/levels/delete/:num/'] = array(
			'title' => LANG_DELETE_LEVEL_TITLE,
			'controller' => 'levels',
			'action' => 'level_delete',
			'access' => 'Manage types and levels',
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
		$menu[LANG_TYPES_LEVELS_MENU] = array(
			'weight' => 50,
			'access' => 'Manage types and levels',
			'children' => array(
				LANG_CREATE_TYPE_MENU => array(
					'url' => 'admin/types/create/',
					'icon' => 'filenew',
				),
				LANG_ALL_TYPES_MENU => array(
					'url' => 'admin/types/',
				),
			),
		);

		return $menu;
	}
	
	public function hooks()
	{
		// Will be processed in 'admin_menu' module
		$hook['buildMenu'] = array(
			'events' => array('Build menu'),
		);
	
		return $hook;
	}
}
?>