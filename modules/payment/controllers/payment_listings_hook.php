<?php

/**
 * On listing creation and prolong,
 * select listings prices table from DB and saves it in registry
 *
 */
function listings_getPrices($CI)
{
	$CI->load->model('payment_listings', 'payment');
	$listings_prices = $CI->payment_listings->selectListingsPricesByGroupId();

	registry::set('listings_prices', $listings_prices);
}

function listings_getDifferencePrices($CI)
{
	$listing_id = $CI->uri->args[0];
	
	$CI->load->model('listings', 'listings');
	$CI->load->model('payment_listings', 'payment');
	$CI->load->model('levels', 'types_levels');
	$CI->load->model('types', 'types_levels');
	
	$CI->listings->setListingId($listing_id);
	$current_level_id = $CI->listings->getLevelIdByListingId();

	$current_level = $CI->levels->getLevelById($current_level_id);

	$current_type = $CI->types->getTypeById($current_level->type_id);
	$current_type->buildLevels();

	$listing = new listing($current_level_id, $listing_id);
	$listing->setListingFromArray($CI->listings->getListingRowById(), $CI->listings->getListingCategories(), $CI->listings->getListingLocations());
	
	$listings_prices = registry::get('listings_prices');
	// This array is working, we will change and pass to listings controller
	$listings_prices_working = $listings_prices;
		
	$CI->payment_listings->calculatePriceDifferences($listing, $current_type->levels, $listings_prices, $listings_prices_working);

	// Pass to listings controller
	registry::set('listings_prices', $listings_prices_working);
}
	
/**
 * select listings prices table from DB and saves it in registry
 *
 */
function listings_getPricesOfDefaultGroup($CI)
{
	$CI->load->model('payment_listings', 'payment');
	$listings_prices = $CI->payment_listings->selectListingsPricesByDefaultGroup();

   	registry::set('default_listings_prices', $listings_prices);
}
	
/**
 * on listing create and prolong hook
 * 
 * Check the price of listing by user_group_id and listing level_id,
 * if payment - create invoice, listing status: not paid,
 * if free - exit;
 *
 */
function checkIfPaymentListings($CI, $args)
{
   	$event = array_pop($args);
   	$attrs = array_pop($args);

   	$listing_id = $attrs['LISTING_ID'];
   	$listing_title = LANG_LISTING_INVOICE_TITLE . ": '" . $attrs['LISTING_TITLE'] . "'";

   	$listings_prices = registry::get('listings_prices');

   	$CI->load->model('listings', 'listings');
   	$CI->listings->setListingId($listing_id);
   	$level_id = $CI->listings->getLevelIdByListingId();

   	foreach ($listings_prices AS $price) {
   		if ($price['level_id'] == $level_id) {
   			$price_currency = $price['currency'];
   			$price_value = $price['value'];
   			break;
   		}
   	}
   	
   	$listing = new listing($level_id, $listing_id);

   	// If price > 0 and  listing not in package
   	if (isset($price_value) && $price_value > 0 && !$listing->package) {
   		// not free listing - create invoice
   		$CI->load->model('payment', 'payment');
   		if ($CI->payment->createInvoice('listings', $listing_id, $listing_title, $CI->session->userdata('user_id'), $price_currency, $price_value)) {
   			// listing status: not paid
   			$CI->load->model('listings', 'listings');
   			$CI->listings->saveListingStatus(5);
		}
   	}
}

/**
 * on listing change level
 * 
 * Check the price of listing by user_group_id and listing level_id,
 * if payment - create invoice, listing status: not paid,
 * if free - exit;
 *
 */
function checkIfPaymentListingsOnLevelChange($CI, $args)
{
   	$event = array_pop($args);
   	$attrs = array_pop($args);

   	$listing_id = $attrs['LISTING_ID'];

   	$old_level_id = $attrs['OLD_LISTING_LEVEL_ID'];
   	$new_level_id = $attrs['NEW_LISTING_LEVEL_ID'];

   	$listings_prices = registry::get('listings_prices');

   	foreach ($listings_prices AS $price) {
   		if ($price['level_id'] == $new_level_id) {
   			$price_difference_currency = $price['currency'];
   			$price_difference_value = $price['value'];
   			break;
   		}
   	}
   	
	$listing = new listing($old_level_id, $listing_id);

   	// If price difference > 0 and listing not in package
   	if (isset($price_difference_value) && $price_difference_value > 0 && !$listing->package) {
   		// Create new upgrades record in DB with
   		$CI->load->model('payment_listings', 'payment');
   		$record_id = $CI->payment_listings->createListingsUpgradeRecord($listing_id, $old_level_id, $new_level_id);
   		$title = LANG_LISTING_UPGRADE_INVOICE_TITLE." '".$attrs['LISTING_TITLE']."' ".LANG_LISTING_UPGRADE_FROM_LEVEL." '".$attrs['OLD_LISTING_LEVEL']."' ".LANG_LISTING_UPGRADE_TO_LEVEL." '".$attrs['NEW_LISTING_LEVEL']."'";
   		
   		// not free listing - create invoice
   		$CI->load->model('payment', 'payment');
   		if ($CI->payment->createInvoice('listings_upgrade', $record_id, $title, $CI->session->userdata('user_id'), $price_difference_currency, $price_difference_value, true)) {
   			// listing status: not paid
   			$CI->load->model('listings', 'listings');
   			$CI->listings->saveListingStatus(5);
		}
   	}
}
?>