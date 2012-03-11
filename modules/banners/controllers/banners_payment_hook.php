<?php

/**
 * On banners creation and prolong,
 * select banners prices table from DB and saves it in registry
 *
 */
function banners_getPrices($CI)
{
	$CI->load->model('banners_payment', 'banners');
	$banners_prices = $CI->banners_payment->selectBannersPricesByGroupId();

	registry::set('banners_prices', $banners_prices);
}
	
/**
 * select banners prices table from DB and saves it in registry
 *
 */
function banners_getPricesOfDefaultGroup($CI)
{
	$CI->load->model('banners_payment', 'banners');
	$banners_prices = $CI->banners_payment->selectBannersPricesByDefaultGroup();

   	registry::set('default_banners_prices', $banners_prices);
}
	
/**
 * on banners create and prolong hook
 * 
 * Check the price of banner by user_group_id and banner block_id,
 * if payment - create invoice, banner status: not paid,
 * if free - exit;
 *
 */
function checkIfPaymentBanners($CI, $args)
{
   	$event = array_pop($args);
   	$attrs = array_pop($args);

   	$banner_id = $attrs['BANNER_ID'];
   	$banner_url = $attrs['BANNER_URL'];
   	$banner_title = LANG_BANNER_INVOICE_TITLE . ": '" . $banner_url . "'";

   	$banners_prices = registry::get('banners_prices');
   	
   	$CI->load->model('banners', 'banners');
   	$CI->banners->setBannerId($banner_id);
   	$banner_obj = $CI->banners->getBannerById();
   	$block_id = $banner_obj->block_id;

   	foreach ($banners_prices AS $price) {
   		if ($price['block_id'] == $block_id) {
   			$price_currency = $price['currency'];
   			$price_value = $price['value'];
   			break;
   		}
   	}

   	// If module payment loaded and price > 0
   	if ($CI->load->is_module_loaded('payment') && isset($price_value) && $price_value > 0) {
   		// not free listing - create invoice
   		$CI->load->model('payment', 'payment');
   		if ($CI->payment->createInvoice('banners', $banner_id, $banner_title, $CI->session->userdata('user_id'), $price_currency, $price_value)) {
   			// listing status: not paid
   			$CI->banners->saveBannerStatus(4);
		}
   	}
}
?>