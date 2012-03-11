<?php
class content_pagesModule
{
	public $title = "Content pages";
	public $version = "0.2";
	public $description = "Content pages uses for additional information providing. Module also allows to place content pages link in the top menu of the frontend part.";
	public $type = "core";
	public $permissions = array('Manage content pages');
	
	public $lang_files = "content_pages.php";
	
	public function routes()
	{
		$route['admin/pages/manage/(.*)'] = array(
			'title' => LANG_MANAGE_CONTENT_PAGES_TITLE,
			'access' => 'Manage content pages',
		);
		
		$route['admin/pages/edit/:num/'] = array(
			'title' => LANG_EDIT_CONTENT_PAGE_TITLE,
			'access' => 'Manage content pages',
			'action' => 'edit',
		);

		$route['admin/pages/create/'] = array(
			'title' => LANG_CREATE_CONTENT_PAGE_TITLE,
			'access' => 'Manage content pages',
			'action' => 'create',
		);

		$route['admin/pages/delete/:num/'] = array(
			'title' => LANG_DELETE_CONTENT_PAGE_TITLE,
			'access' => 'Manage content pages',
			'action' => 'delete',
		);
		
		$route['admin/pages/preview/:num/'] = array(
			'title' => LANG_VIEW_CONTENT_PAGE,
			'access' => 'Manage content pages',
			'action' => 'preview',
		);
	
		return $route;
	}
	
	public function menu()
	{
		$menu[LANG_CONTENT_PAGES_MENU] = array(
			'weight' => 58,
			'access' => 'Manage content pages',
			'children' => array(
				LANG_CREATE_CONTENT_PAGE_MENU => array(
					'icon' => 'filenew',
					'url' => 'admin/pages/create/',
				),
				LANG_MANAGE_CONTENT_PAGE_MENU => array(
					'url' => 'admin/pages/manage/',
					'sinonims' => array(
						array('admin', 'pages', 'manage', '%+'),
						array('admin', 'pages', 'edit', '%'), 
						array('admin', 'pages', 'delete', '%'), 
						array('admin', 'pages', 'preview', '%'), 
					),
				),
			),
		);

		return $menu;
	}
}
?>