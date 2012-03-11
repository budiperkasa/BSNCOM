<?php

include_once(MODULES_PATH . 'settings/classes/listings_views_set.class.php');
include_once(MODULES_PATH . 'content_pages/classes/content_page.class.php');
include_once(MODULES_PATH . 'acl/classes/content_acl.class.php');
include_once(MODULES_PATH . 'content_fields/classes/field.class.php');
include_once(MODULES_PATH . 'content_fields/classes/content_fields.class.php');
include_once(MODULES_PATH . 'content_fields/classes/search_content_fields.class.php');
include_once(MODULES_PATH . 'google_maps/classes/listings_markers.class.php');

class frontendController extends controller
{
	public function index($location)
	{
		//$this->output->enable_profiler(TRUE);

		$this->load->model('types', 'types_levels');
		$this->load->model('settings', 'settings');
		$this->load->model('listings', 'listings');
		$view = $this->load->view();

		// --------------------------------------------------------------------------------------------
		// Select types and levels in hierarchical array
		// --------------------------------------------------------------------------------------------
		$types = $this->types->getTypesLevels();
		$view->assign('types', $types);
		// --------------------------------------------------------------------------------------------

		// --------------------------------------------------------------------------------------------
		// Get frontend settings - listings views object
		// --------------------------------------------------------------------------------------------
		$listings_views = $this->settings->selectViewsByTypes($types);
		$view->assign('listings_views', $listings_views);
		// --------------------------------------------------------------------------------------------

		// --------------------------------------------------------------------------------------------
		// Select listings from DB
		// --------------------------------------------------------------------------------------------
		$paginator = new pagination;
		$this->listings->setPaginator($paginator);
		$listings_of_type = array();
		foreach ($types AS $type) {
			$listings_view = $listings_views->getViewByTypeIdAndPage($type->id, 'index');
			$paginator->setNumPerPage($listings_view->getListingsNumberFromFormat());
			$listings_of_type[$type->id] = $this->listings->selectListings(array('page' => 'index', 'search_type' => $type, 'search_status' => 1, 'search_users_status' => 2, 'search_levels' => $listings_view->levels_visible, 'search_location' => registry::get('current_location')), $listings_view->order_by, $listings_view->order_direction);
		}
		$view->assign('listings_of_type', $listings_of_type);
		// --------------------------------------------------------------------------------------------

		$view->assign('current_type_id', 0);

		// --------------------------------------------------------------------------------------------
		// Search block settings
		// --------------------------------------------------------------------------------------------
		$base_url = site_url('search/');
		$search_fields = new searchContentFields(GLOBAL_SEARCH_GROUP_CUSTOM_NAME, 0);
		$advanced_search_fields = new searchContentFields(GLOBAL_SEARCH_GROUP_CUSTOM_NAME, 0, 'advanced');

		$view->assign('base_url', $base_url);
		$view->assign('search_fields', $search_fields);
		$view->assign('advanced_search_fields', $advanced_search_fields);
		// --------------------------------------------------------------------------------------------
		
		$this->session->set_userdata('source_page', array());

		$view->display('frontend/index.tpl');
	}

	public function listings($seo_title)
	{
		//$this->output->enable_profiler(TRUE);

		$this->load->model('types', 'types_levels');
		$view = $this->load->view();

		$listing = registry::get('current_listing');
		$view->assign('listing', $listing);
		$view->assign('current_type', $listing->type);
		// --------------------------------------------------------------------------------------------

		// --------------------------------------------------------------------------------------------
		// Select types and levels in hierarchical array
		// --------------------------------------------------------------------------------------------
		$types = $this->types->getTypesLevels();
		$view->assign('types', $types);
		// --------------------------------------------------------------------------------------------
		
		// --------------------------------------------------------------------------------------------
		// Assign images, videos, files
		// --------------------------------------------------------------------------------------------
		if ($images = $listing->getAssignedImages()) {
			// Logo will be assigned as first image in the gallery
			if ($listing->level->logo_enabled && $listing->logo_file) {
				$logo_image = new listingImage();
				$logo_image->setImageFromArray(array('title' => $listing->title(), 'file' => $listing->logo_file));
				$images = array_merge(array($logo_image), $images);
			}
		}
		$view->assign('images', $images);
		
		$videos = $listing->getAssignedVideos();
		$view->assign('videos', $videos);
		
		$files = $listing->getAssignedFiles();
		$view->assign('files', $files);
		
		$available_options_count = 0;
		if ($listing->type->locations_enabled && $listing->locations_count())
			$available_options_count++;
		if ($listing->level->video_count && $videos)
			$available_options_count++;
		if ($listing->level->files_count && $files)
			$available_options_count++;
		if ($listing->level->reviews_mode != 'disabled')
			$available_options_count++;
		$view->assign('available_options_count', $available_options_count);
		// --------------------------------------------------------------------------------------------

		// --------------------------------------------------------------------------------------------
		// Build meta information fields of page
		// --------------------------------------------------------------------------------------------
		$view->assign('title', $listing->title());
		$view->assign('description', $listing->listing_meta_description);
		$view->assign('keywords', $listing->listing_keywords);
		// --------------------------------------------------------------------------------------------

		// --------------------------------------------------------------------------------------------
		// Search block settings
		// --------------------------------------------------------------------------------------------
		if ($listing->type->search_type == 'global') {
			$group_name = GLOBAL_SEARCH_GROUP_CUSTOM_NAME;
			$search_group_id = 0;
			$cache_index = GLOBAL_SEARCH_GROUP_CUSTOM_NAME;
			$base_url = site_url('search/');
		} else {
			$group_name = LOCAL_SEARCH_GROUP_CUSTOM_NAME;
			$search_group_id = $listing->type->id;
			$cache_index = LOCAL_SEARCH_GROUP_CUSTOM_NAME . '_' . $listing->type->id;
			$base_url = site_url('search/search_type/' . $listing->type->seo_name . '/');
			$view->assign('base_url', $base_url);
		}

		$search_fields = new searchContentFields($group_name, $search_group_id);
		$advanced_search_fields = new searchContentFields($group_name, $search_group_id, 'advanced');

		$view->assign('search_fields', $search_fields);
		$view->assign('advanced_search_fields', $advanced_search_fields);
		// --------------------------------------------------------------------------------------------

		// --------------------------------------------------------------------------------------------
		// Breadcrumbs building
		// --------------------------------------------------------------------------------------------
		$breadcrumbs = $this->session->userdata('source_page');
		$view->assign('breadcrumbs', $breadcrumbs);
		// --------------------------------------------------------------------------------------------

		if ($view->template_exists('frontend/listing_page-' . $listing->level->id . '.tpl')) {
			$template = 'frontend/listing_page-' . $listing->level->id . '.tpl';
		} else {
			$template = 'frontend/listing_page.tpl';
		}
		
		$view->display($template);
	}

	public function download($file_id)
	{
		$this->load->model('files', 'listings');
		if (!($file = $this->files->getFileById($file_id))) {
			exit(0);
		}

		$users_content_server_path = $this->config->item('users_content_server_path');
		$users_content_settings = $this->config->item('users_content');
		// Read the file's contents
		if (is_file($file->file))
			$data = file_get_contents($file->file);
		elseif (is_file($users_content_server_path . $users_content_settings['listing_file']['upload_to'] . $file->file)) {
			$data = file_get_contents($users_content_server_path . $users_content_settings['listing_file']['upload_to'] . $file->file);
		}

		if ($file->file_format != 'undefined') {
			$name = $file->title . '.' . $file->file_format;
		} else {
			$info = pathinfo($file->file);
			$name = $file->title . '.' . $info['extension'];
		}

		$this->load->helper('download');
		force_download($name, $data);
	}
	
	public function print_listing($seo_title)
	{
		$this->load->model('types', 'types_levels');
		$view = $this->load->view();

		$listing = registry::get('current_listing');
		$view->assign('listing', $listing);
		$view->assign('current_type', $listing->type);
		// --------------------------------------------------------------------------------------------

		// --------------------------------------------------------------------------------------------
		// Select types and levels in hierarchical array
		// --------------------------------------------------------------------------------------------
		$types = $this->types->getTypesLevels();
		$view->assign('types', $types);
		// --------------------------------------------------------------------------------------------

		// --------------------------------------------------------------------------------------------
		// Assign images, videos, files
		// --------------------------------------------------------------------------------------------
		if ($images = $listing->getAssignedImages()) {
			if ($listing->level->logo_enabled && $listing->logo_file) {
				$logo_image = new listingImage();
				$logo_image->setImageFromArray(array('title' => $listing->title(), 'file' => $listing->logo_file));
				$images = array_merge(array($logo_image), $images);
			}
		}
		$view->assign('images', $images);
		
		$videos = $listing->getAssignedVideos();
		$view->assign('videos', $videos);
		
		$files = $listing->getAssignedFiles();
		$view->assign('files', $files);
		// --------------------------------------------------------------------------------------------

		// --------------------------------------------------------------------------------------------
		// Build meta information fields of page
		// --------------------------------------------------------------------------------------------
		$view->assign('title', $listing->title());
		// --------------------------------------------------------------------------------------------

		$view->display('frontend/listing_print.tpl');
	}
	
	public function users($seo_title)
	{
		//$this->output->enable_profiler(TRUE);

		$this->load->model('users', 'users');
		$this->load->model('types', 'types_levels');
		$view = $this->load->view();

		// --------------------------------------------------------------------------------------------
		// Get user by its seo login or ID
		// --------------------------------------------------------------------------------------------
		/*if (!($user = $this->users->getUserByUrl($seo_title))) {
			exit("This user doesn't exist!");
		}
		$view->assign('user', $user);
		registry::set('current_user', $user);*/

		$user = registry::get('current_user');
		$view->assign('user', $user);
		// --------------------------------------------------------------------------------------------
		
		// --------------------------------------------------------------------------------------------
		// Build meta information fields of page
		// --------------------------------------------------------------------------------------------
		$view->assign('title', $user->login);
		if ($user->users_group->meta_enabled && $user->meta_description) {
			$view->assign('description', $user->meta_description);
			$view->assign('keywords', $user->meta_keywords);
		}
		// --------------------------------------------------------------------------------------------
		
		// --------------------------------------------------------------------------------------------
		// Select types and levels in hierarchical array
		// --------------------------------------------------------------------------------------------
		$types = $this->types->getTypesLevels();
		$view->assign('types', $types);
		// --------------------------------------------------------------------------------------------
		
		// --------------------------------------------------------------------------------------------
		// Search block settings
		// --------------------------------------------------------------------------------------------
		$base_url = site_url('search/');
		$search_fields = new searchContentFields(GLOBAL_SEARCH_GROUP_CUSTOM_NAME, 0);
		$advanced_search_fields = new searchContentFields(GLOBAL_SEARCH_GROUP_CUSTOM_NAME, 0, 'advanced');

		$view->assign('base_url', $base_url);
		$view->assign('search_fields', $search_fields);
		$view->assign('advanced_search_fields', $advanced_search_fields);
		// --------------------------------------------------------------------------------------------
		
		if ($view->template_exists('frontend/user_page-' . $user->users_group->id . '.tpl')) {
			$template = 'frontend/user_page-' . $user->users_group->id . '.tpl';
		} else {
			$template = 'frontend/user_page.tpl';
		}
		
		$view->display($template);
	}
	
	public function print_user($seo_title)
	{
		$this->load->model('users', 'users');
		$view = $this->load->view();

		$user = registry::get('current_user');
		$view->assign('user', $user);
		// --------------------------------------------------------------------------------------------

		$view->display('frontend/user_print.tpl');
	}

	public function types($location, $seo_name, $argsString = '')
	{
		//$this->output->enable_profiler(TRUE);
		
		$args = parseUrlArgs($argsString);
		$system_settings = registry::get('system_settings');
		
		$this->load->model('types', 'types_levels');
		$this->load->model('settings', 'settings');
		$this->load->model('listings', 'listings');
		$view = $this->load->view();

		/*if (!$type = $this->types->getTypeBySeoName($seo_name))
			exit();
		$view->assign('type', $type);
		$view->assign('current_type', $type);
		registry::set('current_type', $type);*/
		
		$type = registry::get('current_type');
		$view->assign('type', $type);
		$view->assign('current_type', $type);

		// --------------------------------------------------------------------------------------------
		// Select types and levels in hierarchical array
		// --------------------------------------------------------------------------------------------
		$types = $this->types->getTypesLevels();
		$view->assign('types', $types);
		// --------------------------------------------------------------------------------------------

		// --------------------------------------------------------------------------------------------
		// Get frontend settings - listings views object
		// --------------------------------------------------------------------------------------------
		$listings_views = $this->settings->selectViewsByTypes($types);
		$listings_view = $listings_views->getViewByTypeIdAndPage($type->id, 'types');
		$view->assign('listings_views', $listings_views);
		$view->assign('view', $listings_view);
		// --------------------------------------------------------------------------------------------
		
		// --------------------------------------------------------------------------------------------
		// Orderby and its direction pagination block
		// --------------------------------------------------------------------------------------------
		if (isset($args['orderby']) && isset($args['direction'])) {
			$orderby = $args['orderby'];
			$direction = $args['direction'];
		} else {
			$orderby = $listings_view->order_by;
			$direction = $listings_view->order_direction;
		}
		$view->assign('orderby', $orderby);
		$view->assign('direction', $direction);
		// --------------------------------------------------------------------------------------------
		
		// --------------------------------------------------------------------------------------------
		// Select listings from DB
		// --------------------------------------------------------------------------------------------
		$paginator_url = site_url($type->getUrl());
		$paginator = new pagination(array('args' => $args, 'url' => $paginator_url, 'num_per_page' => $listings_view->getListingsNumberFromFormat()));
		$this->listings->setPaginator($paginator);
		$listings = $this->listings->selectListings(array('page' => 'types', 'search_type' => $type, 'search_status' => 1, 'search_users_status' => 2, 'search_levels' => $listings_view->levels_visible, 'search_location' => registry::get('current_location')), $orderby, $direction);

		$view->assign('listings_paginator', $paginator);
		$view->assign('listings', $listings);
		$view->assign('order_url', $paginator_url);
		// --------------------------------------------------------------------------------------------

		// --------------------------------------------------------------------------------------------
		// Search block settings
		// --------------------------------------------------------------------------------------------
		if ($type->search_type == 'global') {
			$group_name = GLOBAL_SEARCH_GROUP_CUSTOM_NAME;
			//$search_cache_index = GLOBAL_SEARCH_GROUP_CUSTOM_NAME;
			$search_group_id = 0;
		} else {
			$group_name = LOCAL_SEARCH_GROUP_CUSTOM_NAME;
			$search_group_id = $type->id;
			//$search_cache_index = LOCAL_SEARCH_GROUP_CUSTOM_NAME . '_' . $type->id;
			$base_url = site_url("search/search_type/" . $type->seo_name);
			$view->assign('base_url', $base_url);
		}

		$search_fields = new searchContentFields($group_name, $search_group_id);
		$advanced_search_fields = new searchContentFields($group_name, $search_group_id, 'advanced');

		$view->assign('search_fields', $search_fields);
		$view->assign('advanced_search_fields', $advanced_search_fields);
		// --------------------------------------------------------------------------------------------
		
		// --------------------------------------------------------------------------------------------
		// Build meta information fields of page
		// --------------------------------------------------------------------------------------------
		
		if ($type->meta_title)
			$view->assign('title', $type->meta_title);
		else 
			$view->assign('title', $type->name);
		if ($type->meta_description)
			$view->assign('description', $type->meta_description);
		//$view->assign('keywords', $type->listing_keywords);
		// --------------------------------------------------------------------------------------------
		
		$this->session->set_userdata('source_page', array(current_url() => $type->name));

		$view->display('frontend/types_page.tpl');
	}

	public function categories($location, $type_seo_name = '', $argsString = '')
	{
		//$this->output->enable_profiler(TRUE);
		
		$system_settings = registry::get('system_settings');
		
		$this->load->model('categories', 'categories', 'categories_model');
		$this->load->model('types', 'types_levels');
		$this->load->model('settings', 'settings');
		$this->load->model('listings', 'listings');
		$view = $this->load->view();

		/*if ($type_seo_name) {
			// Local
			if (!($type = $this->types->getTypeBySeoName($type_seo_name))) {
				exit(0);
			}
			$current_type_id = $type->id;
			$view->assign('type', $type);
			$view->assign('current_type', $type);
			registry::set('current_type', $type);
			$this->categories_model->setTypeId($type->id);
			
			$argsString = str_replace('type/' . $type_seo_name . '/', '', $argsString);
		} else {
			// Global
			$current_type_id = 0;
			$type = null;
		}
		//$categories_db = $this->categories_model->selectCategoriesFromDB();
		$selected_categories_chain = array();
		$a = explode('/', $argsString);
		foreach ($a AS $category_seo_name) {
			foreach ($categories_db AS $category_row) {
				if ($category_row['seo_name'] == $category_seo_name) {
	    			$selected_categories_chain[] = $category_row['seo_name'];
	    		}
			}
		}
		$argsString = str_replace(implode('/', $selected_categories_chain), '', $argsString);
		$args = parseUrlArgs($argsString);

		$category = $this->categories_model->getCategoryBySeoName(end($selected_categories_chain));
		$category->buildChildren();
		$view->assign('current_category', $category);
		registry::set('current_category', $category);*/
		
		
		
		if (!($type = registry::get('current_type')))
			$current_type_id = 0;
		else
			$current_type_id = $type->id;
		$category = registry::get('current_category');
		$view->assign('type', $type);
		$view->assign('current_type', $type);
		$view->assign('current_category', $category);
		
		// --------------------------------------------------------------------------------------------
		if ($type_seo_name)
			$argsString = str_replace('type/' . $type_seo_name . '/', '', $argsString);
		$array = $category->getChainAsArray();
		foreach ($array AS $category) {
			$chain[] = $category->seo_name;
		}
		$argsString = str_replace(implode('/', $chain), '', $argsString);
		$args = parseUrlArgs($argsString);
		// --------------------------------------------------------------------------------------------

		// --------------------------------------------------------------------------------------------
		// Select types and levels in hierarchical array
		// --------------------------------------------------------------------------------------------
		$types = $this->types->getTypesLevels();
		$view->assign('types', $types);
		// --------------------------------------------------------------------------------------------

		// --------------------------------------------------------------------------------------------
		// Get frontend settings - listings views object
		// --------------------------------------------------------------------------------------------
		$listings_views = $this->settings->selectViewsByTypes($types);
		$listings_view = $listings_views->getViewByTypeIdAndPage(0, 'categories');
		$view->assign('listings_views', $listings_views);
		$view->assign('view', $listings_view);
		// --------------------------------------------------------------------------------------------
		
		// --------------------------------------------------------------------------------------------
		// Orderby and its direction pagination block
		// --------------------------------------------------------------------------------------------
		if (isset($args['orderby']) && isset($args['direction'])) {
			$orderby = $args['orderby'];
			$direction = $args['direction'];
		} else {
			$orderby = $listings_view->order_by;
			$direction = $listings_view->order_direction;
		}
		$view->assign('orderby', $orderby);
		$view->assign('direction', $direction);
		// --------------------------------------------------------------------------------------------
		
		// --------------------------------------------------------------------------------------------
		// Select listings from DB
		// --------------------------------------------------------------------------------------------
		$paginator_url = site_url($category->getUrl());
		$paginator = new pagination(array('args' => $args, 'url' => $paginator_url, 'num_per_page' => $listings_view->getListingsNumberFromFormat()));
		$this->listings->setPaginator($paginator);
		$listings = $this->listings->selectListings(array('page' => 'categories', 'search_type' => $type, 'search_category' => $category->id, 'search_status' => 1, 'search_users_status' => 2, 'search_location' => registry::get('current_location')), $orderby, $direction, array());
		
		$view->assign('listings_paginator', $paginator);
		$view->assign('listings', $listings);
		$view->assign('order_url', $paginator_url);
		// --------------------------------------------------------------------------------------------

		// --------------------------------------------------------------------------------------------
		// Search block settings
		// --------------------------------------------------------------------------------------------
		if (!$type || $type->search_type == 'global') {
			$group_name = GLOBAL_SEARCH_GROUP_CUSTOM_NAME;
			$search_cache_index = GLOBAL_SEARCH_GROUP_CUSTOM_NAME;
		} else {
			$group_name = LOCAL_SEARCH_GROUP_CUSTOM_NAME;
			$search_cache_index = LOCAL_SEARCH_GROUP_CUSTOM_NAME . '_' . $type->id;
			$base_url = site_url("search/search_type/" . $type->seo_name);
			$view->assign('base_url', $base_url);
		}

		$search_fields = new searchContentFields($group_name, $current_type_id);
		$advanced_search_fields = new searchContentFields($group_name, $current_type_id, 'advanced');

		$view->assign('search_fields', $search_fields);
		$view->assign('advanced_search_fields', $advanced_search_fields);
		// --------------------------------------------------------------------------------------------
		
		// --------------------------------------------------------------------------------------------
		// Build meta information fields of page
		// --------------------------------------------------------------------------------------------
		
		if ($category->meta_title)
			$view->assign('title', $category->meta_title);
		else 
			$view->assign('title', $category->name);
		if ($category->meta_description)
			$view->assign('description', $category->meta_description);
		//$view->assign('keywords', $type->listing_keywords);
		// --------------------------------------------------------------------------------------------
		
		$this->session->set_userdata('source_page', array(current_url() => $category->name));

		$view->display('frontend/categories_page.tpl');
	}

	public function search($location, $argsString = '')
	{
		//$this->output->enable_profiler(TRUE);

		$args = parseUrlArgs($argsString);
		$system_settings = registry::get('system_settings');

		$this->load->model('categories', 'categories');
		$this->load->model('settings', 'settings');
		$this->load->model('types', 'types_levels');
		$this->load->model('listings', 'listings');
		$view = $this->load->view();
		$view->assign('args', $args);
		
		// --------------------------------------------------------------------------------------------
		// Select types and levels in hierarchical array
		// --------------------------------------------------------------------------------------------
		$types = $this->types->getTypesLevels();
		$view->assign('types', $types);
		// --------------------------------------------------------------------------------------------
		
		if (isset($args['search_type'])) {
			$type = registry::get('current_type');
			
			$search_type_seoname = $args['search_type'];
			$view->assign('current_type', $type);
			$search_type_id = $type->id;
		} else {
			$search_type_id = 0;
			$type = null;
		}

		if (isset($args['use_advanced'])) {
			$use_advanced = true;
		} else {
			$use_advanced = false;
		}

		if (isset($args['search_category'])) {
			$categories_ids = array_filter(explode(',', urldecode(html_entity_decode($args['search_category']))));
			array_walk($categories_ids, "trim");
			$view->assign('search_categories_array', $categories_ids);
		}

		// --------------------------------------------------------------------------------------------
		// Analyze search arguments
		// Search url needs for 'asc_desc_insert.base_url' argument of smarty function
		// --------------------------------------------------------------------------------------------
		/*$search_url = '';
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
			$search_type_seoname = $args['search_type'];
			$search_url .= 'search_type/' . $search_type_seoname . '/';

			$this->load->model('types', 'types_levels');
			if (!($type = $this->types->getTypeBySeoName($search_type_seoname))) {
				exit(0);
			}
			$search_type_id = $type->id;
			$view->assign('current_type', $type);
			registry::set('current_type', $type);
		} else {
			$search_type_id = 0;
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
			$view->assign('search_categories_array', $categories_ids);
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
		}*/
		
		// --------------------------------------------------------------------------------------------
		// Store search params into registry (without params of content fields!)
		// --------------------------------------------------------------------------------------------
		$search_url = registry::get('current_search_params_url');
		// --------------------------------------------------------------------------------------------

		// --------------------------------------------------------------------------------------------
		// Get frontend settings - listings views object
		// --------------------------------------------------------------------------------------------
		$listings_views = $this->settings->selectViewsByTypes($types);
		$listings_view = $listings_views->getViewByTypeIdAndPage(0, 'search');
		$view->assign('listings_views', $listings_views);
		$view->assign('view', $listings_view);
		// --------------------------------------------------------------------------------------------
		
		// --------------------------------------------------------------------------------------------
		// Orderby and its direction
		// --------------------------------------------------------------------------------------------
		if (isset($args['orderby']) && isset($args['direction'])) {
			$orderby = $args['orderby'];
			$direction = $args['direction'];
		} else {
			if ((!isset($args['where_radius']) || !$args['where_radius']) || (!isset($args['where_search']) || !$args['where_search'])) {
				$orderby = $listings_view->order_by;
				$direction = $listings_view->order_direction;
			} else {
				// When search by radius - by default we must order by distance from center of search 
				$orderby = 'distance';
				$direction = 'asc';
			}
		}
		// --------------------------------------------------------------------------------------------

		// --------------------------------------------------------------------------------------------
		// Search block settings
		// --------------------------------------------------------------------------------------------
		if (!$type || $type->search_type == 'global') {
			$group_name = GLOBAL_SEARCH_GROUP_CUSTOM_NAME;
			$search_cache_index = GLOBAL_SEARCH_GROUP_CUSTOM_NAME;
		} else {
			$group_name = LOCAL_SEARCH_GROUP_CUSTOM_NAME;
			$search_cache_index = LOCAL_SEARCH_GROUP_CUSTOM_NAME . '_' . $type->id;
			$base_url = site_url("search/search_type/" . $search_type_seoname);
			$view->assign('base_url', $base_url);
		}

		$search_fields = new searchContentFields($group_name, $search_type_id);
		$advanced_search_fields = new searchContentFields($group_name, $search_type_id, 'advanced');

		$search_sql_array = $search_fields->validateSearch(LISTINGS_LEVEL_GROUP_CUSTOM_NAME, $args, $use_advanced, $search_url_rebuild);
		$search_url .= $search_url_rebuild;
		$view->assign('search_fields', $search_fields);
		$view->assign('advanced_search_fields', $advanced_search_fields);
		// --------------------------------------------------------------------------------------------
		
		// --------------------------------------------------------------------------------------------
		// Select listings from DB
		// --------------------------------------------------------------------------------------------
		$paginator = new pagination(array('args' => $args, 'url' => site_url('search/' . $search_url), 'num_per_page' => $listings_view->getListingsNumberFromFormat()));
		$this->listings->setPaginator($paginator);
		$listings = $this->listings->selectListings(array_merge($args, array('page' => 'search', 'search_type' => $type, 'search_status' => 1, 'search_users_status' => 2, 'search_location' => registry::get('current_location'))), $orderby, $direction, $search_sql_array);
		
		$view->assign('listings_paginator', $paginator);
		$view->assign('listings', $listings);
		$view->assign('order_url', site_url('search/' . $search_url));
		$view->assign('orderby', $orderby);
		$view->assign('direction', $direction);
		// --------------------------------------------------------------------------------------------

		$this->session->set_userdata('source_page', array(current_url() => LANG_BREADCRUMBS_SEARCH));

		$view->display('frontend/search_page.tpl');
	}

	public function node($node_url)
	{
		$this->load->model('content_pages', 'content_pages');
		$this->load->model('types', 'types_levels');
		$view = $this->load->view();

		// --------------------------------------------------------------------------------------------
		// Select types and levels in hierarchical array
		// --------------------------------------------------------------------------------------------
		$types = $this->types->getTypesLevels();
		$view->assign('types', $types);
		// --------------------------------------------------------------------------------------------
		
		// --------------------------------------------------------------------------------------------
		// Search block settings
		// --------------------------------------------------------------------------------------------
		$search_fields = new searchContentFields(GLOBAL_SEARCH_GROUP_CUSTOM_NAME, 0);
		$advanced_search_fields = new searchContentFields(GLOBAL_SEARCH_GROUP_CUSTOM_NAME, 0, 'advanced');
		
		$view->assign('search_fields', $search_fields);
		$view->assign('advanced_search_fields', $advanced_search_fields);
		// --------------------------------------------------------------------------------------------
		
		if ($node_id = $this->content_pages->getNodeIdByUrl($node_url)) {
			$this->content_pages->setNodeId($node_id);
	
			$node = new content_page($node_id);
			$node->setPageFromArray($this->content_pages->getNodeById());
			$view->assign('node', $node);
	
			if ($node->meta_title())
				$view->assign('title', $node->meta_title());
			else
				$view->assign('title', $node->title());
			if ($node->meta_description())
				$view->assign('description', $node->meta_description());

			if ($view->template_exists('frontend/node-' . $node_id . '.tpl')) {
	        	$template = 'frontend/node-' . $node_id . '.tpl';
	        } else {
	        	$template = 'frontend/node.tpl';
	        }
	        $view->display($template);
		} else 
			show_404();
	}

	public function quick_list($argsString = '')
	{
		//$this->output->enable_profiler(TRUE);
		
		$args = parseUrlArgs($argsString);
		
		$system_settings = registry::get('system_settings');

		$this->load->model('types', 'types_levels');
		$this->load->model('listings', 'listings');
		$this->load->model('settings', 'settings');
		$view = $this->load->view();

		// --------------------------------------------------------------------------------------------
		// Select types and levels in hierarchical array
		// --------------------------------------------------------------------------------------------
		$types = $this->types->getTypesLevels();
		$view->assign('types', $types);
		// --------------------------------------------------------------------------------------------

		// --------------------------------------------------------------------------------------------
		// Get frontend settings - listings views object
		// --------------------------------------------------------------------------------------------
		$listings_views = $this->settings->selectViewsByTypes($types);
		$listings_view = $listings_views->getViewByTypeIdAndPage(0, 'quicklist');
		$view->assign('listings_views', $listings_views);
		$view->assign('view', $listings_view);
		// --------------------------------------------------------------------------------------------
		
		// --------------------------------------------------------------------------------------------
		// Orderby and its direction
		// --------------------------------------------------------------------------------------------
		if (isset($args['orderby']) && isset($args['direction'])) {
			$orderby = $args['orderby'];
			$direction = $args['direction'];
		} else {
			$orderby = $listings_view->order_by;
			$direction = $listings_view->order_direction;
		}
		$view->assign('orderby', $orderby);
		$view->assign('direction', $direction);
		// --------------------------------------------------------------------------------------------
		
		// --------------------------------------------------------------------------------------------
		// Extract cookies into favourites array
		// --------------------------------------------------------------------------------------------
		$quick_list_ids = checkQuickList();
		// --------------------------------------------------------------------------------------------
		
		// --------------------------------------------------------------------------------------------
		// Select listings from DB
		// --------------------------------------------------------------------------------------------
		$paginator_url = site_url('quick_list/');
		$paginator = new pagination(array('args' => $args, 'url' => $paginator_url, 'num_per_page' => LISTINGS_PER_PAGE_ON_QUICKLIST_PAGE));
		$this->listings->setPaginator($paginator);
		$listings = $this->listings->selectListings(array('search_by_ids' => $quick_list_ids, 'search_status' => 1, 'search_users_status' => 2), $orderby, $direction);
		
		$view->assign('listings_paginator', $paginator);
		$view->assign('listings', $listings);
		$view->assign('order_url', $paginator_url);
		// --------------------------------------------------------------------------------------------

		// --------------------------------------------------------------------------------------------
		// Search block settings
		// --------------------------------------------------------------------------------------------
		$search_fields = new searchContentFields(GLOBAL_SEARCH_GROUP_CUSTOM_NAME, 0);
		$advanced_search_fields = new searchContentFields(GLOBAL_SEARCH_GROUP_CUSTOM_NAME, 0, 'advanced');

		$view->assign('search_fields', $search_fields);
		$view->assign('advanced_search_fields', $advanced_search_fields);
		// --------------------------------------------------------------------------------------------

		$this->session->set_userdata('source_page', array(current_url() => LANG_QUICK_LIST));

		$view->display('frontend/quick_list.tpl');
	}
	
	public function advertise()
	{
		$this->load->model('types', 'types_levels');
		$view = $this->load->view();

		// --------------------------------------------------------------------------------------------
		// Select types and levels in hierarchical array
		// --------------------------------------------------------------------------------------------
		$types = $this->types->getTypesLevels();

		// Levels listings prices
		if ($this->session->userdata('user_id'))
	    	$listings_prices = registry::get('listings_prices');
	    else 
	    	$listings_prices = registry::get('default_listings_prices');

		if ($listings_prices) {
			foreach ($types AS $type_id=>$type) {
				foreach ($type->levels AS $level_id=>$level) {
					foreach ($listings_prices AS $price) {
						if ($level->id == $price['level_id']) {
							$types[$type_id]->levels[$level_id]->setPrice($price['value'], $price['currency']);
						}
					}
				}
			}
		}
		
		if ($this->load->is_module_loaded('packages')) {
			$this->load->model('packages', 'packages');
			$packages = $this->packages->getAllPackages();
			if ($this->session->userdata('user_id'))
				$packages_prices = registry::get('packages_prices');
			else
				$packages_prices = registry::get('default_packages_prices');
			$view->assign('packages', $packages);
			$view->assign('packages_prices', $packages_prices);
		}

		$view->assign('types', $types);
		$view->assign('listings_prices', $listings_prices);
		$view->display('frontend/advertise_with_us.tpl');
	}
}
?>