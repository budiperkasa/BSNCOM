<?php
include_once(MODULES_PATH . 'settings/classes/listings_view.class.php');

class listingsViewsSet
{
	public $views = array();
	public $available_listings_views = array(
		'full',
		'short',
		'semitable',
	);
	public $available_pages = array(
		'by_types' => array(
			'index' => LANG_INDEX_PAGE_TH,
			'types' => LANG_TYPES_PAGE_TH,
		),
		'mixed_types' => array(
			'categories' => LANG_CATEGORIES_PAGE_TH,
			'search' => LANG_SEARCH_PAGE_TH,
			'quicklist' => LANG_QUICK_LIST_PAGE_TH,
		),
	);
	
	public function __construct($types, $db_array)
	{
		$views = array();

		foreach ($db_array As $row) {
			$page = $row['page_name'];
			include_once(MODULES_PATH . 'settings/classes/listings_views/' . $page . '_listings_view.class.php');
			$class_name = $page . 'ListingsView';
			$default_view = new $class_name($row);
			$default_view->setTypeId();
			$views[$row['type_id']][$row['page_name']] = $default_view;
		}
		foreach ($types AS $type) {
			foreach ($this->available_pages['by_types'] AS $page=>$page_name) {
				if (!isset($views[$type->id][$page])) {
					include_once(MODULES_PATH . 'settings/classes/listings_views/' . $page . '_listings_view.class.php');
					$class_name = $page . 'ListingsView';
					$default_view = new $class_name;
					$default_view->setTypeId($type->id);
					$default_view->setDefaults();
					$views[$type->id][$page] = $default_view;
				}
			}
		}
		foreach ($this->available_pages['mixed_types'] AS $page=>$page_name) {
			if (!isset($views[0][$page])) {
				include_once(MODULES_PATH . 'settings/classes/listings_views/' . $page . '_listings_view.class.php');
				$class_name = $page . 'ListingsView';
				$default_view = new $class_name;
				$default_view->setTypeId();
				$default_view->setDefaults();
				$views[0][$page] = $default_view;
			}
		}

		foreach ($views AS $views_by_types) {
			foreach ($views_by_types AS $views_by_pages) {
				$this->views[] = $views_by_pages;
			}
		}
	}
	
	public function getViewByTypeIdAndPage($type_id, $page)
	{
		foreach ($this->views AS $view) {
			if ($view->type_id == $type_id && $view->page_key == $page) {
				return $view;
			}
		}
	}
}
?>