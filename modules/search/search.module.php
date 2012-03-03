<?php
class searchModule
{
	public $title = "Search";
	public $version = "0.1";
	public $description = "Search module controls content search fields and its types of search.";
	public $type = "core";
	
	public $lang_files = "search.php";
	
	public function routes()
	{
		$route['admin/search/global/(quick|advanced)/'] = array(
			'title' => LANG_MANAGE_GLOBAL_SEARCH_TITLE,
			'access' => 'Manage content fields',
		);
		
		$route['admin/search/by_type/'] = array(
			'title' => LANG_MANAGE_SEARCH_BYTYPE_TITLE,
			'action' => 'choose_type',
			'access' => 'Manage content fields',
		);
		
		$route['admin/search/by_type/:num/'] = array(
			'title' => LANG_MANAGE_SEARCH_BYTYPE_TITLE,
			'action' => 'by_type',
			'access' => 'Manage content fields',
		);
		
		$route['admin/search/save/:num/'] = array(
			'action' => 'save_fields_of_group',
			'access' => 'Manage content fields',
		);

		return $route;
	}
	
	public function menu()
	{
		$menu[LANG_SEARCH_MENU] = array(
			'weight' => 59,
			'access' => 'Manage content fields',
			'children' => array(
				LANG_MANAGE_GLOBAL_SEARCH_MENU => array(
					'weight' => 1,
					'children' => array(
						LANG_QUICK_SEARCH_MODE => array(
							'weight' => 1,
							'url' => 'admin/search/global/quick/',
						),
						LANG_ADVANCED_SEARCH_MODE => array(
							'weight' => 2,
							'url' => 'admin/search/global/advanced/',
						),
					),
				),
				LANG_MANAGE_SEARCH_BYTYPE_MENU => array(
					'weight' => 2,
					'url' => 'admin/search/by_type/',
					'sinonims' => array(
						array('admin', 'search', 'by_type', '%')
					),
				),
			),
		);
		
		return $menu;
	}
}
?>