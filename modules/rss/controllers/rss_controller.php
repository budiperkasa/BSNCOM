<?php

class rssController extends controller
{
	public function index($items, $argsString)
	{
		$url_args = parseUrlArgs($argsString);
		$system_settings = registry::get('system_settings');
		$site_settings = registry::get('site_settings');
		$users_content_path = trim($this->config->item('users_content_http_path'), '/');

		switch ($items) {
			case 'listings':
				$this->load->model('listings', 'listings');
				$paginator = new pagination(array('num_per_page' => RSS_LISTINGS_COUNT));
				$this->listings->setPaginator($paginator);
				$listings = $this->listings->selectListings(array_merge($url_args, array('search_status' => 1, 'search_users_status' => 2)), 'l.creation_date', 'desc');

				include_once(MODULES_PATH . 'rss/classes/listings_webcast.class.php');
				$webcast = new listingsWebCast($site_settings['website_title'], site_url(), $site_settings['description'], $users_content_path . '/users_images/site_logo/' . $system_settings['site_logo_file'], $users_content_path);
				$webcast->getListingsArray($listings);
				$webcast->run();
				break;
		}
	}
}
?>