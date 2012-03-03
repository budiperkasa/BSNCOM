<?php
class alertpayModule
{
	public $title = "AlertPay";
	public $version = "1";
	public $description = "AlertPay gateway module.";
	
	public $lang_files = "alertpay.php";
	
	/**
	 * AlertPay module depends on payment module
	 */
	public $depends_on = 'payment';
	
	public function routes()
	{
		$route['admin/payment/gateway/alertpay/invoice_id/:num/quantity/:num/'] = array(
			'title' => LANG_ALERTPAY_REDIRECT_TITLE,
		);
		
		// Set Alert URL in AlterPay account: http://www.yoursite.com/alertpay/ipn/
		$route['alertpay/ipn/'] = array(
			'action' => 'ipn',
		);
		
		$route['admin/payment/settings/alertpay/'] = array(
			'title' => LANG_ALERTPAY_SETTINGS,
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
					LANG_ALERTPAY_SETTINGS => array(
						'url' => 'admin/payment/settings/alertpay',
						'access' => 'Manage payment settings',
					),
				),
			),
		);
		
		return $menu;
	}
}
?>