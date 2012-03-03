<?php
class paymentModule
{
	public $title = "Payment";
	public $version = "0.2";
	public $description = "Payment module. Controls payment, invoices, transactions.";
	public $permissions = array(
		'View all invoices',
		'View self invoices',
		//'Delete invoices',
		'View all transactions',
		'View self transactions',
		//'Delete transactions',
		'Create transaction manually',
		'Manage payment settings',
		);
	
	public $lang_files = "payment.php";
	
	public function routes()
	{
		$route['admin/payment/invoices/search/(.*)'] = array(
			'title' => LANG_SEARCH_INVOICES_TITLE,
			'action' => 'search_invoices',
			'access' => 'View all invoices',
		);
		
		$route['admin/payment/invoices/my/(.*)'] = array(
			'title' => LANG_VIEW_MY_INVOICES_TITLE,
			'action' => 'my_invoices',
			'access' => array('View self invoices', 'View all invoices'),
		);
		
		$route['admin/payment/invoices/pay/:num/'] = array(
			'title' => LANG_PAY_INVOICE_TITLE,
			'action' => 'pay',
			'access' => array('View self invoices', 'View all invoices'),
		);
		
		$route['admin/payment/invoices/print/:num/'] = array(
			'title' => LANG_PRINT_INVOICE_TITLE,
			'action' => 'print_invoice',
			'access' => array('View self invoices', 'View all invoices'),
		);
		
		$route['admin/payment/invoices/success/:num/'] = array(
			'title' => LANG_TRANSACTION_IN_PROGRESS_TITLE,
			'action' => 'success',
			'access' => array('View self invoices', 'View all invoices'),
		);

		$route['admin/payment/transactions/search/(.*)'] = array(
			'title' => LANG_SEARCH_TRANSACTIONS_TITLE,
			'action' => 'search_transactions',
			'access' => 'View all transactions',
		);
		
		$route['admin/payment/transactions/my/(.*)'] = array(
			'title' => LANG_VIEW_MY_TRANSACTIONS_MENU,
			'action' => 'my_transactions',
			'access' => array('View self transactions', 'View all transactions'),
		);
		
		$route['admin/payment/transactions/create/'] = array(
			'title' => LANG_CREATE_TRANSACTIONS_TITLE,
			'action' => 'create_transaction',
			'access' => 'Create transaction manually',
		);
		
		$route['admin/payment/transactions/create/:num'] = array(
			'title' => LANG_CREATE_TRANSACTIONS_TITLE,
			'action' => 'create_transaction',
			'access' => 'Create transaction manually',
		);
		
		// --------------------------------------------------------------------------------------------
		// Listings routes
		// --------------------------------------------------------------------------------------------
		$route['admin/listings/payment/'] = array(
			'controller' => 'payment_listings',
			'title' => LANG_VIEW_PAYMENT_SETTINGS_TITLE,
			'action' => 'prices',
			'access' => 'Manage payment settings',
		);
		
		$route['admin/listings/payment/settings/group_id/:num/level_id/:num/'] = array(
			'controller' => 'payment_listings',
			'title' => LANG_PAYMENT_SETTINGS_TITLE,
			'action' => 'payment_settings',
			'access' => 'Manage payment settings',
		);
		// --------------------------------------------------------------------------------------------
		
		// --------------------------------------------------------------------------------------------
		// Packages routes
		// --------------------------------------------------------------------------------------------
		$route['admin/packages/payment/'] = array(
			'controller' => 'payment_packages',
			'title' => LANG_VIEW_PAYMENT_SETTINGS_TITLE,
			'action' => 'prices',
			// Only when both 'payment' and 'packages' modules enabled
			'access' => array('AND' => 'Manage payment settings', 'Manage packages'),
		);
		
		$route['admin/packages/payment/settings/group_id/:num/package_id/:num/'] = array(
			'controller' => 'payment_packages',
			'title' => LANG_PAYMENT_SETTINGS_TITLE,
			'action' => 'payment_settings',
			// Only when both 'payment' and 'packages' modules enabled
			'access' => array('AND' => 'Manage payment settings', 'Manage packages'),
		);
		// --------------------------------------------------------------------------------------------

		return $route;
	}
	
	public function menu()
	{
		$menu[LANG_VIEW_MY_INVOICES_MENU] = array(
			'weight' => 7,
			'url' => 'admin/payment/invoices/my/',
			'access' => 'View self invoices',
			'sinonims' => array(
				array('admin', 'payment', 'invoices', 'my', '%+'),
				array('admin', 'payment', 'invoices', 'pay', '%+'),
				array('admin', 'payment', 'gateway', '%+'),
				array('admin', 'payment', 'invoices', 'success'),
			),
			'icon' => 'invoices',
		);
		
		$menu[LANG_VIEW_MY_TRANSACTIONS_MENU] = array(
			'weight' => 8,
			'url' => 'admin/payment/transactions/my/',
			'access' => 'View self transactions',
			'sinonims' => array(
				array('admin', 'payment', 'transactions', 'my', '%+'),
			),
			'icon' => 'transactions',
		);
		
		$menu[LANG_LISTINGS_MENU]['children'] = array(
			LANG_PAYMENT_SETTINGS_MENU => array(
				'weight' => 1,
				'url' => 'admin/listings/payment/',
				'sinonims' => array(array('admin', 'listings', 'payment', 'settings', 'group_id', '%', 'level_id', '%')),
				'access' => 'Manage payment settings',
			)
		);

		$menu[LANG_PAYEMENT_MENU] = array(
			'weight' => 61,
			'children' => array(
				LANG_SEARCH_INVOICE_MENU => array(
					'weight' => 2,
					'url' => 'admin/payment/invoices/search/',
					'sinonims' => array(
						array('admin', 'payment', 'invoices', 'search', '%+'),
					),
					'access' => 'View all invoices',
				),
				LANG_SEARCH_TRANSACTIONS_MENU => array(
					'weight' => 3,
					'url' => 'admin/payment/transactions/search/',
					'sinonims' => array(
						array('admin', 'payment', 'transactions', 'search', '%+'),
					),
					'access' => 'View all transactions',
				),
				LANG_CREATE_TRANSACTIONS_MENU => array(
					'weight' => 4,
					'url' => 'admin/payment/transactions/create/',
					'sinonims' => array(
						array('admin', 'payment', 'transactions', 'create', '%+'),
					),
					'access' => 'Create transaction manually',
				),
			),
		);

		return $menu;
	}

	public function hooks()
	{
		$hook['buildMyInvoicesMenuItem'] = array(
			'weight' => 2,
		);
		
		$hook['buildMyTransactionsMenuItem'] = array(
			'weight' => 2,
		);
		
		// --------------------------------------------------------------------------------------------
		// Listings processing
		// --------------------------------------------------------------------------------------------
		/**
		 * get prices and save it in the registry
		 */
		$hook['listings_getPrices'] = array(
			'file' => 'payment_listings_hook.php',
			'inclusions' => array(
				'admin/listings/create/:any',
				'admin/listings/prolong/:any',
				'admin/listings/change_level/:any',
				'advertise/',
			),
		);
		
		/**
		 * When user needs to upgrade level of listing - we need to calculate differences for payment
		 */
		$hook['listings_getDifferencePrices'] = array(
			'file' => 'payment_listings_hook.php',
			'inclusions' => array(
				'admin/listings/change_level/:any',
			),
		);
		
		/**
		 * For advertise page
		 */
		$hook['listings_getPricesOfDefaultGroup'] = array(
			'file' => 'payment_listings_hook.php',
			'inclusions' => array(
				'advertise/',
			),
		);
		
		/**
		 * on listing create and prolong
		 */
		$hook['checkIfPaymentListings'] = array(
			'file' => 'payment_listings_hook.php',
			'events' => array('Listing creation', 'Listing prolonging'),
		);
		
		/**
		 * on listing change level
		 */
		$hook['checkIfPaymentListingsOnLevelChange'] = array(
			'file' => 'payment_listings_hook.php',
			'events' => 'Listing change level',
		);
		// --------------------------------------------------------------------------------------------
		
		

		return $hook;
	}
}
?>