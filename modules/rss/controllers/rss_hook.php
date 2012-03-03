<?php

function getRssUrl($items = 'listings')
{
	$url = 'rss/' . $items . '/';
	if ($current_search_params = registry::get('current_search_params_url')) {
		$url .= $current_search_params;
	} else {
		if ($current_location = registry::get('current_location')) {
			$url .= 'search_location/' . $current_location->seo_name . '/';
		}
		if ($current_type = registry::get('current_type')) {
			$url .= 'search_type/' . $current_type->seo_name . '/';
		}
		if ($current_category = registry::get('current_category')) {
			$url .= 'search_category/' . $current_category->seo_name . '/';
		}
		if ($current_user = registry::get('current_user')) {
			$url .= 'search_owner/' . urlencode($current_user->login) . '/';
		}
		if ($current_listing = registry::get('current_listing')) {
			$url .= 'search_listing/' . $current_listing->getUniqueId() . '/';
		}
	}

	return site_url($url);
}

function getRssTitle()
{
	$title = '';
	
	$CI = &get_instance();
	if ($CI->router->fetch_module() == 'frontend' && $CI->router->fetch_class() == 'frontend') {
		switch ($CI->router->fetch_method()) {
			case 'index':
				$title = LANG_RSS_FEED_RECENT_LISTINGS;
				break;
			case 'types':
				$title = LANG_RSS_FEED_TYPE_LISTINGS;
				break;
			case 'categories':
				$title = LANG_RSS_FEED_CATEGORIES_LISTINGS;
				break;
			case 'search':
				$title = LANG_RSS_FEED_SEARCH_LISTINGS;
				break;
		}
	}
	if ($title && $current_location = registry::get('current_location')) {
		$title .= ' ' . LANG_RSS_FEED_IN_LOCATION . ' ' . $current_location->getChainAsString();
	}
	return $title;
}
?>