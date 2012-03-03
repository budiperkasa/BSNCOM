<?php
include_once(MODULES_PATH . 'notifications/classes/notification_sender.class.php');

/**
* Append my listings count to the 'View my listings' menu item
*
*/
function buildMyListingsMenuItem($CI)
{
	if ($CI->session->userdata('user_id')) {
		$CI->load->model('listings', 'listings');
		$listings_count = $CI->listings->getMyListingsCount();
		if ($listings_count) {
			$menu_list = registry::get('menu');
	
			$my_listings_key = $menu_list[LANG_VIEW_MY_LISTING_MENU];
			unset($menu_list[LANG_VIEW_MY_LISTING_MENU]);
	
			$menu_list[LANG_VIEW_MY_LISTING_MENU . ' <b>(' . $listings_count  . ')</b>'] = $my_listings_key;
			registry::set('menu', $menu_list);
		}
	}
}

function listings_runAutoBlocker($CI)
{
	$CI->load->model('listings', 'listings');
	$CI->listings->suspendExpiredActiveListings();
}

function sendReviewNotification($CI, $args)
{
	$event = array_pop($args);

	/*$attrs = array(
		'OBJECTS_TABLE' => $objects_table,
		'OBJECT_ID' => $object_id,
		'REVIEW_BODY' => $review,
		'PARENT_REIVEW_ID' => $parent_id,
		'USER_ID' => $user_id,
		'ANONYM_NAME' => $anonym_name,
		'ANONYM_EMAIL' => $anonym_email,
		'IP' => $ip,
	);*/

	$attrs = array_pop($args);
	if (isset($attrs['OBJECTS_TABLE']) && $attrs['OBJECTS_TABLE'] == 'listings') {
		$CI->load->model('listings', 'listings');
		$listing_id = $attrs['OBJECT_ID'];
		if ($listing = $CI->listings->getListingById($listing_id)) {
			$CI->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_' . $listing_id));

			$event_params = array(
				'LISTING_TITLE' => $listing->title(),
				'LISTING_URL' => site_url($listing->url()),
				'RECIPIENT_NAME' => $listing->user->login,
				'RECIPIENT_EMAIL' => $listing->user->email,
				'REVIEW_BODY' => 
								str_replace('&nbsp;', ' ', 
								htmlspecialchars_decode(
								strip_tags(
									$attrs['REVIEW_BODY']
								))),
			);
			$notification = new notificationSender('Review creation for listing');
			$notification->send($event_params);
			
			if (isset($attrs['PARENT_REIVEW_ID']) && $parent_id = $attrs['PARENT_REIVEW_ID']) {
				$CI->load->model('reviews', 'ratings_reviews');
				$review = $CI->reviews->getReviewById($parent_id);
				$review->getUser();
				$review->getObject();
				$event_params = array(
					'LISTING_TITLE' => $listing->title(),
					'LISTING_URL' => site_url($listing->url()),
					'RECIPIENT_NAME' => $review->reviewer_name(),
					'RECIPIENT_EMAIL' => $review->reviewer_email(),
					'REVIEW_BODY' => strip_tags($attrs['REVIEW_BODY']),
				);
				$notification = new notificationSender('Reply on your comment');
				$notification->send($event_params);
			}
		}
	}
}
?>