<?php

function includeJQueryAndStyles($CI)
{
	$system_settings = registry::get('system_settings');

	$view = $CI->load->view();
	$view->addCssFile('frontend_style.css');
	$view->addCssFile('nyroModal.css');
	$view->addCssFile('jquery.lightbox-0.5.css');
	$view->addCssFile('ui/jquery-ui-1.8.9.custom.css');
	$view->addCssFile('ui/query-ui-customizations.css');
	$view->addCssFile('jquery.jgrowl.css');
	$view->addCssFile('jq_carousel_skin/skin.css');

	/*$view->addJsFile('jquery-1.4.4.min.js');*/
	$view->addExternalJsFile('https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js');
	$view->addExternalJsFile('https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.14/jquery-ui.min.js');
	//$view->addJsFile('ui/jquery-ui-1.8.14.custom.min.js');

	$view->addJsFile('jquery.nyroModal.custom.js');
	$view->addJsFile('jquery.jgrowl_compressed.js');
	$view->addJsFile('js_functions.js');
	$view->addJsFile('jquery.lightbox-0.5.pack.js');
	$view->addJsFile('jquery.jqURL.js');
	// There were some problems with full cookie.js name of this file, so now it was renamed to coo_kie.js
	$view->addJsFile('jquery.coo_kie.js');
	$view->addJsFile('jquery.rater.js');
	$view->addJsFile('phprpc/phpserializer.js');
	$view->addJsFile('phprpc/utf.js');
	$view->addJsFile('jquery.treeview.js');
	$view->addJsFile('jquery.jcarousel.min.js');
	
	$view->addJsFile('jquery.jstree.js');
	// There are some customizations in jstree plugin's css, they set base width of the categories tree
	$view->addCssFile('jsTree_themes_v10/jstree-customizations.css');
	
	$view->addJsFile('jquery.form.js');
	$view->addJsFile('swfobject.js');

	$language_code = registry::get('current_language');
	if ($language_code && $language_code != 'en')
		$view->addJsFile('content_fields/i18n/jquery-ui-i18n.js');

	// don't foget to uncomment this line
	//$view->addExternalJsFile('http://maps.googleapis.com/maps/api/js?v=3.4&sensor=false&language=' . $language_code);
	$view->addExternalJsFile('http://maps.googleapis.com/maps/api/js?v=3.4&sensor=false&language=en');
	$view->addJsFile('google_maps_view.js');
}

function getCurrentType($CI)
{
	$current_route_args = registry::get('current_route_args');
	$location = $current_route_args[0];
	$seo_name = $current_route_args[1];
	
	$CI->load->model('types', 'types_levels');
	
	if (!$type = $CI->types->getTypeBySeoName($seo_name))
		exit(0);

	registry::set('current_type', $type);
}

function getCurrentCategory($CI)
{
	$current_route_args = registry::get('current_route_args');
	$location = $current_route_args[0];
	$type_seo_name = $current_route_args[1];
	$argsString = $current_route_args[2];

	$CI->load->model('categories', 'categories', 'categories_model');
	$CI->load->model('types', 'types_levels');

	if ($type_seo_name) {
		// Local
		if (!($type = $CI->types->getTypeBySeoName($type_seo_name))) {
			exit(0);
		}
		registry::set('current_type', $type);
		$CI->categories_model->setTypeId($type->id);

		$argsString = str_replace('type/' . $type_seo_name . '/', '', $argsString);
	}
	$categories_db = $CI->categories_model->selectCategoriesFromDB();
	$selected_categories_chain = array();
	$a = explode('/', $argsString);
	foreach ($a AS $category_seo_name) {
		foreach ($categories_db AS $category_row) {
			if ($category_row['seo_name'] == $category_seo_name) {
	   			$selected_categories_chain[] = $category_row['seo_name'];
	   		}
		}
	}

	if ($category = $CI->categories_model->getCategoryBySeoName(implode('/', $selected_categories_chain))) {
		$category->buildChildren();
		registry::set('current_category', $category);
	}
}

function getCurrentListing($CI)
{
	$current_route_args = registry::get('current_route_args');
	$seo_title = $current_route_args[0];
	
	$CI->load->model('listings', 'listings');

	// --------------------------------------------------------------------------------------------
	// Get listing by its seo name or ID
	// --------------------------------------------------------------------------------------------
	if (!($listing_row = $CI->listings->getListingRowByUrl($seo_title))) {
		exit("This listing doesn't exist or wasn't translated!");
	}
	$listing_id = $listing_row['id'];
	$listing = $CI->listings->getListingById($listing_id);
	if ($listing->status != 1) {
		exit('Listing blocked or expired!');
	}
	if ($listing->user->status != 2) {
		exit('Owner of listing blocked or unverified!');
	}

	registry::set('current_listing', $listing);
	registry::set('current_type', $listing->type);
}

function getCurrentUser($CI)
{
	$current_route_args = registry::get('current_route_args');
	$seo_title = $current_route_args[0];
	
	$CI->load->model('users', 'users');
	
	if (!($user = $CI->users->getUserByUrl($seo_title))) {
		exit("This user doesn't exist!");
	}
	registry::set('current_user', $user);
}

function getCurrentSearchParams($CI)
{
	$current_route_args = registry::get('current_route_args');
	$location = $current_route_args[0];
	$argsString = $current_route_args[1];
	
	$args = parseUrlArgs($argsString);
	
	$search_url = '';
	if (isset($args['what_search'])) {
		$what_search = $args['what_search'];
		$search_url .= 'what_search/' . $what_search . '/';
	}
	if (isset($args['what_match'])) {
		$what_match = $args['what_match'];
		$search_url .= 'what_match/' . $what_match . '/';
	}
	if (isset($args['where_search'])) {
		$where_search = $args['where_search'];
		$search_url .= 'where_search/' . $where_search . '/';
	}
	if (isset($args['where_radius'])) {
		$where_radius = $args['where_radius'];
		$search_url .= 'where_radius/' . $where_radius . '/';
	}
	if (isset($args['search_type'])) {
		$CI->load->model('types', 'types_levels');
		if (is_numeric($args['search_type'])) {
			$search_type_id = $args['search_type'];
			$search_url .= 'search_type/' . $search_type_id . '/';
	
			if (!($type = $CI->types->getTypeById($search_type_id))) {
				exit('No such ID of type!');
			}
		} else {
			$search_type_seoname = $args['search_type'];
			$search_url .= 'search_type/' . $search_type_seoname . '/';

			if (!($type = $CI->types->getTypeBySeoName($search_type_seoname))) {
				exit('No such seo name of type!');
			}
		}
		registry::set('current_type', $type);
	} else {
		$type = null;
	}

	if (isset($args['use_advanced'])) {
		$use_advanced = true;
		$search_url .= 'use_advanced/true/';
	} else {
		$use_advanced = false;
	}

	if (isset($args['search_category'])) {
		$search_category = $args['search_category'];
		$search_url .= 'search_category/' . $search_category . '/';
		
		$categories_ids = array_filter(explode(',', urldecode(html_entity_decode($args['search_category']))));
		array_walk($categories_ids, "trim");
	}
	if (isset($args['search_owner'])) {
		$search_owner = $args['search_owner'];
		$search_url .= 'search_owner/' . $search_owner . '/';
	}
	if (isset($args['search_status'])) {
		$search_status = $args['search_status'];
		$search_url .= 'search_status/' . $search_status . '/';
	}
	if (isset($args["search_creation_date"])) {
		$search_creation_date = $args['search_creation_date'];
		$search_url .= 'search_creation_date/' . $search_creation_date . '/';
	}
	if (isset($args['search_from_creation_date'])) {
		$search_from_creation_date = $args['search_from_creation_date'];
		$search_url .= 'search_from_creation_date/' . $search_from_creation_date . '/';
	}
	if (isset($args['search_to_creation_date'])) {
		$search_to_creation_date = $args['search_to_creation_date'];
		$search_url .= 'search_to_creation_date/' . $search_to_creation_date . '/';
	}
	
	registry::set('current_search_params_url', $search_url);
	registry::set('current_search_params_array', $args);
}

function checkQuickList()
{
	if (isset($_COOKIE['favourites']))
		$favourites = unserialize($_COOKIE['favourites']);
	else
		$favourites = array();
	return array_values(array_filter($favourites));
}

/*function isMobile() {
	if(isset($_SERVER["HTTP_X_WAP_PROFILE"])) {
		return true;
	}
	if(preg_match("/wap\.|\.wap/i",$_SERVER["HTTP_ACCEPT"])) {
		return true;
	}
	if(isset($_SERVER["HTTP_USER_AGENT"])){
		$user_agents = array("midp", "j2me", "avantg", "docomo", "novarra", "palmos", "palmsource", "240x320", "opwv", "chtml", "pda", "windows\ ce", "mmp\/", "blackberry", "mib\/", "symbian", "wireless", "nokia", "hand", "mobi", "phone", "cdm", "up\.b", "audio", "SIE\-", "SEC\-", "samsung", "HTC", "mot\-", "mitsu", "sagem", "sony", "alcatel", "lg", "erics", "vx", "NEC", "philips", "mmm", "xx", "panasonic", "sharp", "wap", "sch", "rover", "pocket", "benq", "java", "pt", "pg", "vox", "amoi", "bird", "compal", "kg", "voda", "sany", "kdd", "dbt", "sendo", "sgh", "gradi", "jb", "\d\d\di", "moto");
		foreach($user_agents as $user_string){
			if(preg_match("/".$user_string."/i",$_SERVER["HTTP_USER_AGENT"])) {
				return true;
			}
		}
	}
	// Apple's product are a bit different so lets see if they are using iphone, ipad or ipod device
	if(preg_match("/iphone/i",$_SERVER["HTTP_USER_AGENT"]) || preg_match("/ipad/i",$_SERVER["HTTP_USER_AGENT"]) || preg_match("/ipod/i",$_SERVER["HTTP_USER_AGENT"])) {
		return true;
	}
	// None of the above? Then it's probably not a mobile device.
	return false;
}

function setMobileTheme()
{
	if (!isMobile()) {
		$system_settings = registry::get('system_settings');
		$system_settings['design_theme'] = 'mobile';
		registry::set('system_settings', $system_settings);
		
		$current_language_db_obj = registry::get('current_language_db_obj');
        $current_language_db_obj->custom_theme = 'mobile';
        registry::set('current_language_db_obj', $current_language_db_obj);
	}
}*/
?>