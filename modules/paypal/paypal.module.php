<?php
class paypalModule
{
	public $title = "PayPal";
	public $version = "0.2";
	public $description = "PayPal gateway module.";
	
	public $lang_files = "paypal.php";
	
	/**
	 * PayPal module depends on payment module
	 */
	public $depends_on = 'payment';
	
	public function routes()
	{
		$route['admin/payment/gateway/paypal/invoice_id/:num/quantity/:num/'] = array(
			'title' => LANG_PAYPAL_REDIRECT_TITLE,
		);
		
		$route['paypal/ipn/'] = array(
			'action' => 'ipn',
		);
		
		$route['admin/payment/settings/paypal/'] = array(
			'title' => LANG_PAYPAL_SETTINGS,
			'action' => 'settings',
			'access' => 'Manage payment settings',
		);

		return $route;
	}

	public function menu()
	{
		$menu[LANG_PAYEMENT_MENU]['children'] = array(
			LANG_PAYMENT_GATEWAYS_MENU => array(
				'weight' => 1,
				'children' => array(
					LANG_PAYPAL_SETTINGS => array(
						'url' => 'admin/payment/settings/paypal',
						'access' => 'Manage payment settings',
					),
				),
			),
		);
		
		return $menu;
	}
}
?>