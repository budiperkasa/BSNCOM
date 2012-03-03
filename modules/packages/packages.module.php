<?php
class packagesModule
{
	public $title = "Packages";
	public $version = "0.1";
	public $description = "Membership packages";
	public $type = "custom";

	public $permissions = array(
		'Manage packages',
		'Use packages',
		'Change user packages status');

	public $lang_files = "packages.php";
	
	/**
	 * Coupons module depends on payment module
	 */
	public $depends_on = 'payment';
	
	public function routes()
	{
		// --------------------------------------------------------------------------------------------
		// Manage packages
		// --------------------------------------------------------------------------------------------
		$route['admin/packages/'] = array(
			'title' => LANG_MANAGE_PACKAGES_TITLE,
			'access' => 'Manage packages',
		);
		
		$route['admin/packages/search/(.*)'] = array(
			'title' => LANG_SEARCH_PACKAGES_TITLE,
			'action' => 'search',
			'access' => 'Manage packages',
		);
		
		$route['admin/packages/create/'] = array(
			'title' => LANG_CREATE_PACKAGE_TITLE,
			'action' => 'create',
			'access' => 'Manage packages',
		);
		
		$route['admin/packages/edit/:num'] = array(
			'title' => LANG_EDIT_PACKAGE_TITLE,
			'action' => 'edit',
			'access' => 'Manage packages',
		);
		
		$route['admin/packages/delete/:num'] = array(
			'title' => LANG_DELETE_PACKAGE_TITLE,
			'action' => 'delete',
			'access' => 'Manage packages',
		);
		
		$route['admin/packages/items/:num'] = array(
			'title' => LANG_MANAGE_PACKAGE_ITEMS_TITLE,
			'action' => 'items',
			'access' => 'Manage packages',
		);
		
		$route['admin/packages_settings/'] = array(
			'title' => LANG_SETTINGS_LISTINGS_CREATION_MODE,
			'action' => 'packages_settings',
			'access' => 'Edit system settings',
		);
		// --------------------------------------------------------------------------------------------
		
		$route['admin/packages/add/'] = array(
			'title' => LANG_ADD_PACKAGE_TITLE,
			'action' => 'add',
			'access' => 'Use packages',
		);
		$route['admin/packages/add/:num'] = array(
			'action' => 'add',
			'access' => 'Use packages',
		);
		
		$route['admin/packages/my/'] = array(
			'title' => LANG_VIEW_MY_PACKAGES,
			'action' => 'my',
			'access' => 'Use packages',
		);
		
		$route['admin/packages/change_status/:num'] = array(
			'title' => LANG_PACKAGES_CHANGE_STATUS,
			'action' => 'change_status',
			'access' => 'Change user packages status',
		);

		return $route;
	}

	public function menu()
	{
		$menu[LANG_ADD_MY_PACKAGE] = array(
			'weight' => 9,
			'url' => 'admin/packages/add/',
			'access' => 'Use packages',
			'sinonims' => array(array('admin', 'packages', 'add', '%')),
			'icon' => 'packages',
		);
		
		$menu[LANG_VIEW_MY_PACKAGES] = array(
			'weight' => 10,
			'url' => 'admin/packages/my/',
			'access' => 'Use packages',
			'icon' => 'my_packages',
			'sinonims' => array(
				array('admin', 'packages', 'change_status', '%+')
			),
		);
		
		$menu[LANG_PACKAGES_MENU] = array(
			'children' => array(
				LANG_PACKAGES_PAYMENT_SETTINGS_MENU => array(
					'url' => 'admin/packages/payment/',
					// Only when both 'payment' and 'packages' modules enabled
					'access' => array('AND' => 'Manage payment settings', 'Manage packages'),
					'sinonims' => array(array('admin', 'packages', 'payment', 'settings', 'group_id', '%', 'package_id', '%')),
				),
				LANG_MANAGE_PACKAGES_MENU => array(
					'url' => 'admin/packages/',
					'access' => 'Manage packages',
					'sinonims' => array(
						array('admin', 'packages', 'create', '%+'),
						array('admin', 'packages', 'edit', '%+'), 
						array('admin', 'packages', 'delete', '%+'), 
						array('admin', 'packages', 'items', '%+'), 
						array('admin', 'packages', 'prices', '%+'),
					),
				),
				LANG_SEARCH_PACKAGES_MENU => array(
					'url' => 'admin/packages/search/',
					'access' => 'Manage packages',
					'sinonims' => array(
						array('admin', 'packages', 'search', '%+'),
					),
				),
			),
		);

		$menu[LANG_SETTINGS_MENU] = array(
			'children' => array(
				LANG_MISCELLANEOUS_SETTINGS_MENU => array(
					'children' => array(
						LANG_SETTINGS_LISTINGS_CREATION_MODE => array(
							'url' => 'admin/packages_settings/',
							'action' => 'packages_settings',
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
		$hook['buildMyPackagesMenuItem'] = array(
			'weight' => 2,
		);
		
		// --------------------------------------------------------------------------------------------
		// Packages processing
		// --------------------------------------------------------------------------------------------
		/**
		 * get prices and save it in the registry
		 */
		$hook['packages_getPrices'] = array(
			'file' => 'payment_packages_hook.php',
			'inclusions' => array(
				'admin/packages/add/:any',
				'admin/listings/prolong/:any',
				'admin/listings/change_level/:any',
				'advertise/',
			),
		);

		/**
		 * For advertise page
		 */
		$hook['packages_getPricesOfDefaultGroup'] = array(
			'file' => 'payment_packages_hook.php',
			'inclusions' => array(
				'advertise/',
			),
		);
		
		/**
		 * on listing create and prolong
		 */
		$hook['checkIfPaymentPackages'] = array(
			'file' => 'payment_packages_hook.php',
			'events' => array('Package addition'),
		);
		// --------------------------------------------------------------------------------------------

		return $hook;
	}
}
?>