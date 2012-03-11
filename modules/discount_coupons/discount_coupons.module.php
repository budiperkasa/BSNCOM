<?php
class discount_couponsModule
{
	public $title = "Discount Coupons";
	public $version = "1";
	public $description = "The system may receive disount codes during invoices payment.";
	public $type = "custom";

	public $permissions = array(
		'Manage coupons',
		'Use coupons');

	public $lang_files = "coupons.php";
	
	/**
	 * Coupons module depends on payment module
	 */
	public $depends_on = 'payment';
	
	public function routes()
	{
		$route['admin/coupons/'] = array(
			'title' => LANG_MANAGE_COUPONS_TITLE,
			'access' => 'Manage coupons',
		);
		
		$route['admin/coupons/search/(.*)'] = array(
			'title' => LANG_SEARCH_COUPONS_USAGE_TITLE,
			'action' => 'search',
			'access' => 'Manage coupons',
		);
		
		$route['admin/coupons/create/'] = array(
			'title' => LANG_CREATE_COUPON_TITLE,
			'action' => 'create',
			'access' => 'Manage coupons',
		);
		
		$route['admin/coupons/edit/:num'] = array(
			'title' => LANG_EDIT_COUPON_TITLE,
			'action' => 'edit',
			'access' => 'Manage coupons',
		);
		
		$route['admin/coupons/delete/:num'] = array(
			'title' => LANG_DELETE_COUPON_TITLE,
			'action' => 'delete',
			'access' => 'Manage coupons',
		);
		
		$route['admin/coupons/send/user_id/:num'] = array(
			'action' => 'send_coupon',
			'access' => 'Manage coupons',
		);
		// --------------------------------------------------------------------------------------------

		$route['admin/coupons/my/'] = array(
			'title' => LANG_VIEW_MY_COUPONS,
			'action' => 'my',
			'access' => 'Use coupons',
		);

		return $route;
	}

	public function menu()
	{
		$menu[LANG_VIEW_MY_COUPONS] = array(
			'weight' => 11,
			'url' => 'admin/coupons/my/',
			'access' => 'Use coupons',
			'icon' => 'my_coupons',
		);
		
		$menu[LANG_COUPONS_MENU] = array(
			'children' => array(
				LANG_MANAGE_COUPONS_MENU => array(
					'url' => 'admin/coupons/',
					'access' => 'Manage coupons',
					'sinonims' => array(
						array('admin', 'coupons', 'create', '%+'),
						array('admin', 'coupons', 'edit', '%+'), 
						array('admin', 'coupons', 'delete', '%+'), 
					),
				),
				LANG_SEARCH_COUPONS_USAGE_MENU => array(
					'url' => 'admin/coupons/search/',
					'access' => 'Manage coupons',
					'sinonims' => array(
						array('admin', 'coupons', 'search', '%+'),
					),
				),
			),
		);

		return $menu;
	}
	
	public function hooks()
	{
		$hook['buildMyCouponsMenuItem'] = array(
			'weight' => 2,
		);

		$hook['assignCoupons'] = array(
			'events' => array('Listing creation', 'Banner creation', 'Transaction completion', 'Account creation step 2'),
		);

		return $hook;
	}
}
?>