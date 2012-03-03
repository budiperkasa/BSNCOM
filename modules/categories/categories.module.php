<?php
class categoriesModule
{
	public $title = "Categories";
	public $version = "0.1";
	public $description = "Categories tree management module. Has the ability of unlimited depth of categories, using drag-and-drop JS methods.";
	public $type = "core";
	public $permissions = array('Edit categories');
	
	public $lang_files = "categories.php";
	
	public function routes()
	{
		// --------------------------------------------------------------------------------------------
		// Manage global categories
		// --------------------------------------------------------------------------------------------
		$route['admin/categories/'] = array(
			'title' => LANG_MANAGE_CATEGORIES_TITLE,
			'action' => 'categories',
			'access' => 'Edit categories',
		);
		
		$route['admin/categories/create/'] = array(
			'title' => LANG_CREATE_CATEGORY_TITLE,
			'action' => 'create',
			'access' => 'Edit categories',
		);
		
		$route['admin/categories/create_child/:num'] = array(
			'title' => LANG_CREATE_CHILD_CATEGORY_TITLE,
			'action' => 'create_child',
			'access' => 'Edit categories',
		);
		
		$route['admin/categories/edit/:num'] = array(
			'title' => LANG_EDIT_CATEGORY_TITLE,
			'action' => 'edit',
			'access' => 'Edit categories',
		);
		
		$route['admin/categories/delete/:num'] = array(
			'title' => LANG_DELETE_CATEGORY_TITLE,
			'action' => 'delete',
			'access' => 'Edit categories',
		);
		
		/*$route['ajax/categories_request/'] = array(
			'action' => 'ajax_categories_request',
		);*/

		// --------------------------------------------------------------------------------------------
		// Manage categories by listings types
		// --------------------------------------------------------------------------------------------
		$route['admin/categories/by_type/'] = array(
			'title' => LANG_CHOOSE_TYPE_OF_CATEGORIES_TITLE,
			'controller' => 'categories_by_type',
			'action' => 'choose_type',
			'access' => 'Edit categories',
		);
		
		$route['admin/categories/by_type/:num'] = array(
			'title' => LANG_MANAGE_CATEGORIES_BY_TYPE_TITLE,
			'controller' => 'categories_by_type',
			'action' => 'categories_by_type',
			'access' => 'Edit categories',
		);
		
		/*$route['ajax/categories_request/by_type/:num/'] = array(
			'controller' => 'categories_by_type',
			'action' => 'ajax_categories_request',
			'access' => 'Edit categories',
		);*/
		
		$route['admin/categories/by_type/:num/create/'] = array(
			'title' => LANG_CREATE_CATEGORY_BY_TYPE_TITLE,
			'controller' => 'categories_by_type',
			'action' => 'create_by_type',
			'access' => 'Edit categories',
		);
		
		$route['admin/categories/by_type/:num/create_child/:num'] = array(
			'title' => LANG_CREATE_CHILD_CATEGORY_BY_TYPE_TITLE,
			'controller' => 'categories_by_type',
			'action' => 'create_child_by_type',
			'access' => 'Edit categories',
		);
		
		$route['admin/categories/by_type/:num/edit/:num'] = array(
			'title' => LANG_EDIT_CATEGORY_BY_TYPE_TITLE,
			'controller' => 'categories_by_type',
			'action' => 'edit_by_type',
			'access' => 'Edit categories',
		);
		
		$route['admin/categories/by_type/:num/delete/:num'] = array(
			'title' => LANG_DELETE_CATEGORY_BY_TYPE_TITLE,
			'controller' => 'categories_by_type',
			'action' => 'delete_by_type',
			'access' => 'Edit categories',
		);
		
		// --------------------------------------------------------------------------------------------
		// AJAX routes
		// --------------------------------------------------------------------------------------------
		$route['ajax/categories/get_categories_path/type_id/:num'] = array(
			'action' => 'get_categories_path',
		);
		$route['ajax/categories/get_categories_path/'] = array(
			'action' => 'get_categories_path',
		);

		$route['ajax/categories/is_icons/type_id/:num'] = array(
			'controller' => 'map_markers',
			'action' => 'is_icons',
		);
		$route['ajax/categories/is_icons/'] = array(
			'controller' => 'map_markers',
			'action' => 'is_icons',
		);
		
		$route['ajax/categories/send_suggestion/'] = array(
			'action' => 'send_suggestion',
		);
		
		$route['ajax/categories_request/(.*)'] = array(
			'action' => 'ajax_categories_request',
		);

		// --------------------------------------------------------------------------------------------
		// Manage/select map markers icons
		// --------------------------------------------------------------------------------------------
		$route['admin/categories/select_icons_for_categories/:any'] = array(
			'controller' => 'map_markers',
			'title' => LANG_CATEGORIES_SELECT_ICONS_TITLE,
			'action' => 'select_icons_for_categories',
			'access' => 'Edit categories',
		);
		
		$route['admin/categories/select_icons_for_listings/:any/type_id/:num'] = array(
			'controller' => 'map_markers',
			'title' => LANG_CATEGORIES_SELECT_ICONS_TITLE,
			'action' => 'select_icons_for_listings',
		);
		$route['admin/categories/select_icons_for_listings/:any'] = array(
			'controller' => 'map_markers',
			'title' => LANG_CATEGORIES_SELECT_ICONS_TITLE,
			'action' => 'select_icons_for_listings',
		);

		$route['admin/manage_map_icons_themes/'] = array(
			'controller' => 'map_markers',
			'title' => LANG_MANAGE_MARKER_ICONS_THEMES_TITLE,
			'action' => 'manage_map_icons_themes',
			'access' => 'Edit categories',
		);
		$route['admin/manage_map_icons/folder_name/:num'] = array(
			'controller' => 'map_markers',
			'title' => LANG_MANAGE_MARKER_ICONS_TITLE,
			'action' => 'manage_map_icons',
			'access' => 'Edit categories',
		);

		return $route;
	}
	
	public function menu()
	{
		$menu[LANG_CATEGORIES_MENU] = array(
			'weight' => 52,
			'access' => 'Edit categories',
			'children' => array(
				LANG_MANAGE_CATEGORIES_MENU => array(
					'weight' => 1,
					'url' => 'admin/categories/',
					'sinonims' => array(
						array('admin', 'categories', 'create'),
						array('admin', 'categories', 'create_child', '%'),
						array('admin', 'categories', 'edit', '%'),
						array('admin', 'categories', 'delete', '%'),
					),
				),
				LANG_MANAGE_CATEGORIES_BY_TYPE_MENU => array(
					'weight' => 2,
					'url' => 'admin/categories/by_type/',
					'sinonims' => array(
						array('admin', 'categories', 'by_type', '%+'),
						array('admin', 'categories', 'by_type', 'create'),
						array('admin', 'categories', 'by_type', 'create_child', '%'),
						array('admin', 'categories', 'by_type', 'edit', '%'),
						array('admin', 'categories', 'by_type', 'delete', '%'),
					),
				),
				LANG_MANAGE_MARKER_ICONS_THEMES_MENU => array(
					'weight' => 3,
					'url' => 'admin/manage_map_icons_themes/',
					'sinonims' => array(
						array('admin', 'manage_map_icons', 'folder_name', '%'),
					),
				),
			),
		);
	
		return $menu;
	}
}
?>