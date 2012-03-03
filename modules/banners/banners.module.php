<?php
class bannersModule
{
	public $title = "Banners management module";
	public $version = "0.1";
	public $description = "Controls banner advertisement blocks and manages its content";
	public $permissions = array('Manage banners blocks', 'Manage all banners', 'Manage self banners', 'Create banners');
	
	public $lang_files = "banners.php";
	
	public function routes()
	{
		$route['admin/banners/search/(.*)'] = array(
			'title' => LANG_BANNERS_SEARCH_TITLE,
			'access' => 'Manage all banners',
		);
		
		$route['admin/banners/my/(.*)'] = array(
			'title' => LANG_BANNERS_MY_TITLE,
			'action' => 'my',
			'access' => 'Manage self banners',
		);
		
		$route['admin/banners/create/'] = array(
			'title' => LANG_BANNERS_ADD_BANNER_TITLE,
			'action' => 'create',
			'access' => 'Create banners',
		);
		
		$route['admin/banners/create/block_id/:num'] = array(
			'title' => LANG_BANNERS_ADD_BANNER_TITLE,
			'action' => 'create',
			'access' => 'Create banners',
		);
		
		$route['ajax/banners/get_image/:num'] = array(
			'action' => 'get_image',
			'access' => array('Create banners', 'Manage all banners'),
		);
		$route['ajax/banners/get_image_by_url/:num'] = array(
			'action' => 'get_image_by_url',
			'access' => array('Create banners', 'Manage all banners'),
		);
		
		$route['admin/banners/edit/:num'] = array(
			'title' => LANG_BANNERS_EDIT_BANNER_TITLE,
			'action' => 'edit',
			'access' => array('Manage self banners', 'Manage all banners'),
		);
		
		$route['admin/banners/view/:num'] = array(
			'title' => LANG_BANNERS_VIEW_BANNER_TITLE,
			'action' => 'view',
			'access' => array('Manage self banners', 'Manage all banners'),
		);
		
		$route['admin/banners/delete/:num'] = array(
			'title' => LANG_BANNERS_DELETE_BANNER_TITLE,
			'action' => 'delete',
			'access' => array('Manage self banners', 'Manage all banners'),
		);
		
		$route['admin/banners/delete/'] = array(
			'title' => LANG_BANNERS_DELETE_BANNER_TITLE,
			'action' => 'massDelete',
			'access' => array('Manage self banners', 'Manage all banners'),
		);
		
		$route['admin/banners/block/'] = array(
			'action' => 'block',
			'access' => 'Manage all banners',
		);
		
		$route['admin/banners/activate/'] = array(
			'action' => 'activate',
			'access' => 'Manage all banners',
		);
		
		$route['admin/banners/change_status/:num'] = array(
			'title' => LANG_BANNERS_CHANGE_STATUS_BANNER_TITLE,
			'action' => 'change_status',
			'access' => 'Manage all banners',
		);

		$route['admin/banners/prolong/:num'] = array(
			'action' => 'prolong',
			'access' => array('Manage self banners', 'Manage all banners'),
		);
		
		// --------------------------------------------------------------------------------------------
		// Manage banners blocks
		// --------------------------------------------------------------------------------------------
		$route['admin/banners_blocks/'] = array(
			'title' => LANG_BANNERS_MANAGE_BLOCKS_TITLE,
			'access' => 'Manage banners blocks',
			'controller' => 'banners_blocks',
		);
		
		$route['admin/banners_blocks/create/'] = array(
			'title' => LANG_BANNERS_BLOCKS_CREATE_TITLE,
			'action' => 'create',
			'access' => 'Manage banners blocks',
			'controller' => 'banners_blocks',
		);
		
		$route['admin/banners_blocks/edit/:num'] = array(
			'title' => LANG_BANNERS_BLOCKS_EDIT_TITLE,
			'action' => 'edit',
			'access' => 'Manage banners blocks',
			'controller' => 'banners_blocks',
		);
		
		$route['admin/banners_blocks/delete/:num'] = array(
			'title' => LANG_BANNERS_BLOCKS_DELETE_TITLE,
			'action' => 'delete',
			'access' => 'Manage banners blocks',
			'controller' => 'banners_blocks',
		);
		
		// --------------------------------------------------------------------------------------------
		// Payment routes:
		// we will process banners prices management here - because banners module may be switched on/off,
		// instead of core listings module
		// --------------------------------------------------------------------------------------------
		$route['admin/banners/payment/'] = array(
			'controller' => 'banners_payment',
			'title' => LANG_VIEW_PAYMENT_SETTINGS_TITLE,
			'action' => 'prices',
			'access' => 'Manage payment settings',
		);
		
		$route['admin/banners/payment/settings/group_id/:num/block_id/:num/'] = array(
			'controller' => 'banners_payment',
			'title' => LANG_PAYMENT_SETTINGS_TITLE,
			'action' => 'payment_settings',
			'access' => 'Manage payment settings',
		);
		// ------------------------------------------------------------------------
		
		// ------------------------------------------------------------------------
		
		$route['ajax/banners/click_tracing/:num'] = array(
			'action' => 'click_tracing',
		);

		return $route;
	}
	
	public function menu()
	{
		$menu[LANG_BANNERS_CREATE_BANNER_MENU] = array(
			'weight' => 5,
			'url' => 'admin/banners/create/',
			'access' => 'Create banners',
			'sinonims' => array(
				array('admin', 'banners', 'create', 'block_id', '%'),
			),
			'icon' => 'banners',
		);
		
		$menu[LANG_BANNERS_MY_MENU] = array(
			'weight' => 6,
			'url' => 'admin/banners/my/',
			'access' => 'Manage self banners',
			'sinonims' => array(
				array('admin', 'banners', 'my', '%+'),
			),
			'icon' => 'my_banners',
		);
		
		$menu[LANG_BANNERS_MENU] = array(
			'weight' => 62,
			'children' => array(
				LANG_BANNERS_PAYMENT_SETTINGS_MENU => array(
					'weight' => 1,
					'url' => 'admin/banners/payment/',
					'access' => 'Manage payment settings',
					'sinonims' => array(array('admin', 'banners', 'payment', 'settings', 'group_id', '%', 'block_id', '%')),
				),
				LANG_BANNERS_SEARCH_MENU => array(
					'weight' => 2,
					'url' => 'admin/banners/search/',
					'access' => 'Manage all banners',
					'sinonims' => array(
						array('admin', 'banners', 'search', '%+'),
						array('admin', 'banners', 'view', '%+'),
						array('admin', 'banners', 'edit', '%+'),
						array('admin', 'banners', 'delete', '%+'),
						array('admin', 'banners', 'change_status', '%+'),
					),
				),
				LANG_BANNERS_BLOCKS_SETTINGS_MENU => array(
					'weight' => 3,
					'url' => 'admin/banners_blocks/',
					'access' => 'Manage banners blocks',
					'sinonims' => array(
						array('admin', 'banners_blocks', 'create'),
						array('admin', 'banners_blocks', 'edit', '%+'),
						array('admin', 'banners_blocks', 'delete', '%+'),
					),
				),
			),
		);

		return $menu;
	}

	public function hooks()
	{
		$hook['banners_append'] = array(
			'exclusions' => array(
				'admin/:any',
				'ajax/:any',
				'login/(.*)',
				'locations/build_drop_box',
				'locations/ajax_autocomplete_request/',
				'refresh_captcha/',
				'locations/get_locations_path_by_id/',
				//'listings/:any',
			),
		);

		$hook['addBannersAdvertise'] = array(
			'viewTrigger' => array('post', '#content_wrapper'),
			'inclusions' => array(
				'advertise/',
			),
		);

		$hook['buildMyBannersMenuItem'] = array(
			'weight' => 2,
		);

		/**
 		 * set banner upload path and allowed formats
 		 */
		$hook['defineBannersConfig'] = array(
			/*'inclusions' => array(
				'ajax/files_upload/banner_file/',
				'admin/banners/create/',
				'admin/banners/create/block_id/:num',
				'admin/banners/edit/:num',
				'ajax/banners/get_image/:num',
				'ajax/banners/get_image_by_url/:num',
			),*/
		);

		$hook['banners_runAutoBlocker'] = array(
			'events' => array('Auto blocker run')
		);
		
		// --------------------------------------------------------------------------------------------
		// Prices management
		// --------------------------------------------------------------------------------------------
		/**
		 * get prices and save it in the registry
		 */
		$hook['banners_getPrices'] = array(
			'file' => 'banners_payment_hook.php',
			'inclusions' => array(
				'admin/banners/create/:any',
				'admin/banners/prolong/:any',
				'advertise/',
			),
		);
		
		/**
		 * for advertisement page
		 */
		$hook['banners_getPricesOfDefaultGroup'] = array(
			'file' => 'banners_payment_hook.php',
			'inclusions' => array(
				'advertise/',
			),
		);
		
		/**
		 * on banner create and prolong
		 */
		$hook['checkIfPaymentBanners'] = array(
			'file' => 'banners_payment_hook.php',
			'events' => array('Banner creation', 'Banner prolonging'),
		);
		// --------------------------------------------------------------------------------------------

		return $hook;
	}
}
?>