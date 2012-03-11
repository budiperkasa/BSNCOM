<?php

/**
* Append my coupons count to the 'View my coupons' menu item
*
*/
function buildMyCouponsMenuItem($CI)
{
	$menu_list = registry::get('menu');

	if ($CI->session->userdata('user_id')) {
		$CI->load->model('discount_coupons', 'discount_coupons');
		$coupons_count = $CI->discount_coupons->getMyCouponsCount();
		if ($coupons_count) {
			$my_coupons_key = $menu_list[LANG_VIEW_MY_COUPONS];
			unset($menu_list[LANG_VIEW_MY_COUPONS]);

			$menu_list[LANG_VIEW_MY_COUPONS . ' <b>(' . $coupons_count  . ')</b>'] = $my_coupons_key;
			registry::set('menu', $menu_list);
		}
	}
}

function assignCoupons($CI, $args)
{
	$content_access_obj = contentAcl::getInstance();
	// Only users with 'Use coupons' permission may be assigned
	if ($content_access_obj->isPermission('Use coupons')) {
		$event = array_pop($args);
	   	$attrs = array_pop($args);
	   	
	   	if ($user_id = $CI->session->userdata('user_id')) {
		   	$CI->load->model('discount_coupons', 'discount_coupons');
		   	$coupons = $CI->discount_coupons->getAllCoupons();
		   	
			switch ($event) {
				case 'Listing creation':
					$CI->load->model('listings', 'listings');
					$listings_count = $CI->listings->getMyListingsCount();
					foreach ($coupons AS $coupon) {
						// Assign if first listing of user or any new listing
						if (in_array('any_listing_creation', $coupon->assign_events) || (in_array('first_listing_creation', $coupon->assign_events) && $listings_count === 0)) {
							$coupon->assignToUser($user_id);
						}
					}
					break;
				case 'Banner creation':
					$CI->load->model('banners', 'banners');
					$banners_count = $CI->banners->getMyBannersCount();
					foreach ($coupons AS $coupon) {
						// Assign if first banner of user or any new banner
						if (in_array('any_banner_creation', $coupon->assign_events) || (in_array('first_banner_creation', $coupon->assign_events) && $banners_count === 0)) {
							$coupon->assignToUser($user_id);
						}
					}
					break;
				case 'Transaction completion':
					$CI->load->model('payment', 'payment');
					$transactions_count = $CI->payment->getMyTransactionsCount();
					foreach ($coupons AS $coupon) {
						// Assign if first transaction of user or any new transaction
						if (in_array('any_transaction', $coupon->assign_events) || (in_array('first_transaction', $coupon->assign_events) && $transactions_count === 0)) {
							$coupon->assignToUser($user_id);
						}
					}
					break;
				case 'Account creation step 2':
					foreach ($coupons AS $coupon) {
						// Assign coupon on registration
						if (in_array('registration', $coupon->assign_events)) {
							$coupon->assignToUser($user_id);
						}
					}
					break;
			}
	   	}
	}
}
?>