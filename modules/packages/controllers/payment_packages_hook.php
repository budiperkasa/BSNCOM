<?php

/**
 * On listing creation and prolong,
 * select listings prices table from DB and saves it in registry
 *
 */
function packages_getPrices($CI)
{
	$CI->load->model('payment_packages', 'payment');
	$packages_prices = $CI->payment_packages->selectPackagesPricesByGroupId();

	registry::set('packages_prices', $packages_prices);
}

/**
 * select listings prices table from DB and saves it in registry
 *
 */
function packages_getPricesOfDefaultGroup($CI)
{
	$CI->load->model('payment_packages', 'payment');
	$packages_prices = $CI->payment_packages->selectPackagesPricesByDefaultGroup();

   	registry::set('default_packages_prices', $packages_prices);
}
	
/**
 * on packages addition
 * 
 * Check the price of packages by user_group_id and package package_id,
 * if payment - create invoice, package status: not paid,
 * if free - exit;
 *
 */
function checkIfPaymentPackages($CI, $args)
{
   	$event = array_pop($args);
   	$attrs = array_pop($args);

   	$package_id = $attrs['PACKAGE_ID'];
   	$user_package_id = $attrs['USER_PACKAGE_ID'];
   	$package_name = $attrs['PACKAGE_NAME'];

   	$packages_prices = registry::get('packages_prices');
   	if (isset($packages_prices[$package_id]['currency'])) {
		$price_currency = $packages_prices[$package_id]['currency'];
		$price_value = $packages_prices[$package_id]['value'];
   	}

   	// If price > 0
   	if (isset($price_value) && $price_value > 0) {
   		// not free listing - create invoice
   		$CI->load->model('payment', 'payment');
   		// Create invoice with fixed price
   		if ($CI->payment->createInvoice('packages', $user_package_id, $package_name, $CI->session->userdata('user_id'), $price_currency, $price_value, true)) {
   			$CI->load->model('packages', 'packages');
   			// package status: not paid
   			$CI->packages->savePackageStatus($user_package_id, 3);
		}
   	}
}
?>