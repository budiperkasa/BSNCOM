<?php
include_once(MODULES_PATH . 'listings/classes/listing.class.php');
include_once(MODULES_PATH . 'listings/classes/gapi.class.php');
include_once(MODULES_PATH . 'acl/classes/content_acl.class.php');

class statisticsController extends controller
{
	public function statistics($listing_id, $from_to_date_string)
	{
		// Get date from url string
		if ($from_to_date_string) {
			$arr = explode('/', $from_to_date_string);
			$from_date = $arr[0];
			$js_from_date = date("Y/m/d", strtotime($from_date));
			$to_date = $arr[1];
			$js_to_date = date("Y/m/d", strtotime($to_date));
		}
		// Or get last month
		if (!$from_to_date_string || !strtotime($from_date) || !strtotime($to_date)) {
			$from_date = date("Y-m-d", mktime(0, 0, 0, date('m')-1, date('d')));
			$js_from_date = date("Y/m/d", mktime(0, 0, 0, date('m')-1, date('d')));
			$to_date = date("Y-m-d");
			$js_to_date = date("Y/m/d");
		}

		$this->load->model('listings');
		$this->listings->setListingId($listing_id);
		$level_id = $this->listings->getLevelIdByListingId();
		
		$content_access_obj = contentAcl::getInstance();
		$content_access_obj->checkListingAccess($listing_id, 'View all statistics');

		$listing = new listing($level_id, $listing_id);
		$listing->setListingFromArray($this->listings->getListingRowById(), $this->listings->getListingCategories(), $this->listings->getListingLocations());
		
		if ($this->config->item('index_page'))
			$ga_listings_path = '/' . $this->config->item('index_page');
		else
			$ga_listings_path = '';
		$ga_listings_path .= $this->config->item('ga_listings_path');

		$pageviews = array();
		$dates = array();
		$pageviews_count = 0;
		// --------------------------------------------------------------------------------------------
		$system_settings = registry::get('system_settings');
		$ga = new gapi($system_settings['google_analytics_email'], $system_settings['google_analytics_password']);
		$ga->requestReportData($system_settings['google_analytics_profile_id'], array('pagePath', 'date'), array('pageviews'), 'date', 'pagePath == ' . $ga_listings_path . $listing->getUniqueId() . '/', $from_date, $to_date, 1, 1000);
		foreach($ga->getResults() as $result) {
			$pageviews[] = $result->getPageviews();
			$calc_date = mktime(0, 0, 0, (substr($result->getDate(), 4, 2)), (substr($result->getDate(), 6, 2)), (substr($result->getDate(), 0, 4)))*1000;
			$dates[] = $calc_date;

			$pageviews_count+=$result->getPageviews();
		}
		// --------------------------------------------------------------------------------------------

		//$flot_datas_visits = array();
		$flot_datas_views = array();
		//var_dump($dates);
		for ($i=1; $i<=count($dates); $i++) {
			$flot_datas_views[] = '['.$dates[$i-1].','.$pageviews[$i-1].']';
		}
		$flot_data_views = '['.implode(',',$flot_datas_views).']';

		// We will be able to select such periods
		$periods_to_select = array(
			LANG_STATISTICS_MONTH_PERIOD => site_url('admin/listings/statistics/'.$listing_id.'/'),
			LANG_STATISTICS_WHOLETIME_PERIOD => site_url('admin/listings/statistics/'.$listing_id.'/'.date('Y-m-d', strtotime($listing->creation_date)).'/'.date('Y-m-d')),
			LANG_STATISTICS_WEEK_PERIOD => site_url('admin/listings/statistics/'.$listing_id.'/'.date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')-7)).'/'.date('Y-m-d')),
		);
		// Process all months of last year
		for ($i = 12; $i >= 1; $i--) {
			$date_name = date("M Y", mktime(0, 0, 0, date('m')-$i));
			$start_date = date("Y-m-d", mktime(0, 0, 0, date('m')-$i, 1));
			$end_date = date("Y-m-d", mktime(0, 0, 0, date('m')-($i-1), 1));

			if (strtotime($listing->creation_date) <= strtotime($end_date)) {
				$periods_to_select[$date_name] = site_url('admin/listings/statistics/'.$listing_id.'/'.$start_date.'/'.$end_date);
			}
		}

		if (strpos($this->session->userdata('back_page'), 'admin/listings/search/') !== FALSE) {
		    registry::set('breadcrumbs', array(
		    	$this->session->userdata('back_page') => LANG_SEARCH_LISTINGS_TITLE,
		    	'admin/listings/view/' . $listing_id => LANG_VIEW_LISTING . ' "' . $listing->title . '"',
		    	LANG_LISTINGS_STATISTICS . ' "' . $listing->title() . '"',
		    ));
	    } elseif (strpos($this->session->userdata('back_page'), 'admin/listings/my/') !== FALSE) {
	    	registry::set('breadcrumbs', array(
		   		$this->session->userdata('back_page') => LANG_VIEW_MY_LISTINGS_TITLE,
		   		'admin/listings/view/' . $listing_id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
		   		LANG_LISTINGS_STATISTICS . ' "' . $listing->title() . '"',
		   	));
	    }

		$view = $this->load->view();
		$view->assign('flot_data_views', $flot_data_views);
		$view->assign('dates', $dates);
		$view->assign('js_from_date', $js_from_date);
		$view->assign('js_to_date', $js_to_date);
		$view->assign('listing', $listing);
		$view->assign('periods_to_select', $periods_to_select);
		$view->assign('pageviews_count', $pageviews_count);
		$view->addJsFile('jquery.flot.excanvas.min.js');
		$view->addJsFile('jquery.flot.min.js');
		$view->display('listings/admin_listing_statistics.tpl');
	}
}
?>