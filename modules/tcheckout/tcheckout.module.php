<?php
class tcheckoutModule
{
	public $title = "2Checkout";
	public $version = "0.1";
	public $description = "2Checkout gateway module.";
	
	public $lang_files = "2checkout.php";
	
	/**
	 * 2Checkout module depends on payment module
	 */
	public $depends_on = 'payment';
	
	public function routes()
	{
		$route['admin/payment/gateway/tcheckout/invoice_id/:num/quantity/:num/'] = array(
			'title' => LANG_2CHECKOUT_REDIRECT_TITLE,
		);
		
		$route['tcheckout/ipn/'] = array(
			'action' => 'ipn',
		);
		
		$route['admin/payment/settings/2checkout/'] = array(
			'title' => LANG_2CHECKOUT_SETTINGS,
			'action' => 'settings',
			'access' => 'Manage payment settings',
		);

		return $route;
	}
	
	public function menu()
	{
		$menu[LANG_PAYEMENT_MENU]['children'] = array(
			LANG_PAYMENT_GATEWAYS_MENU => array(
				'weight' => 2,
				'children' => array(
					LANG_2CHECKOUT_SETTINGS => array(
						'url' => 'admin/payment/settings/2checkout',
						'access' => 'Manage payment settings',
					),
				),
			),
		);
		
		return $menu;
	}
}
?>