<?php

function attachCalendar($CI)
{
	// --------------------------------------------------------------------------------------------
	// Extract settings
	// --------------------------------------------------------------------------------------------
	$CI->load->model('calendar', 'calendar');
	$settings = $CI->calendar->getSettings();

	if (is_numeric($settings['connected_field'])) {
		// Content field was connected
		$CI->load->model('content_fields', 'content_fields');
		$CI->content_fields->setFieldId($settings['connected_field']);
		$field = $CI->content_fields->getFieldArrayById();
		$connected_field = 'search_' . $field['seo_name'];
	} else {
		// Creation date field was connected
		$connected_field = $settings['connected_field'];
	}
	
	if ($settings['connected_type_id']) {
		$CI->load->model('types', 'types_levels');
    	$types = $CI->types->getTypesLevels();
		foreach ($types AS $type) {
			if ($type->id == $settings['connected_type_id']) {
				$connected_type_seo_name = $type->seo_name;
				$connected_type_id = $type->id;
			}
		}
		$connected_type = 'search_type/' . $connected_type_seo_name .'/';
	} else {
		$connected_type = '';
	}
	// --------------------------------------------------------------------------------------------
	
	// --------------------------------------------------------------------------------------------
	// Get visibility
	// --------------------------------------------------------------------------------------------
	$index = false;
	$global = false;
	if ($CI->router->fetch_module() == 'frontend' && $CI->router->fetch_class() == 'frontend') {
		switch ($CI->router->fetch_method()) {
			case 'index':
				$index = true;
				break;
			case 'listings':
				$current_listing = registry::get('current_listing');
				$listing_id = $current_listing->id;
				$type_id = $current_listing->type->id;
				break;
			case 'types':
				if ($current_type = registry::get('current_type'))
					$type_seo_name = $current_type->seo_name;
				break;
			case 'categories':
				if ($current_type = registry::get('current_type'))
					$type_seo_name = $current_type->seo_name;
				break;
			case 'search':
				if ($current_type = registry::get('current_type'))
					$type_seo_name = $current_type->seo_name;
				break;
		}
	}

	$show = false;
	if ($index) {
		if ($settings['visibility_on_index'])
			$show = true;
		else 
			$show = false;
	} else {
		if ($settings['visibility_for_all_types']) {
			$show = true;
		} else {
			if (isset($type_seo_name)) {
				if ($type_seo_name == $connected_type_seo_name)
					$show = true;
				else 
					$show = false;
			} elseif (isset($type_id)) {
				if ($type_id == $connected_type_id)
					$show = true;
				else 
					$show = false;
			}
		}
	}
	// --------------------------------------------------------------------------------------------
	
	if ($show) {
		$use_advanced = '';
		if (is_numeric($settings['connected_field'])) {
			// Does this field in advanced mode
			$CI->db->select('mode');
			$CI->db->from('search_fields_groups AS sfg');
			$CI->db->join('search_fields_to_groups AS sftg', 'sftg.search_group_id=sfg.id', 'left');
			$CI->db->where('sftg.field_id', $settings['connected_field']);
			$CI->db->where('sfg.custom_id', $connected_type_id);
			$query = $CI->db->get();

			foreach ($query->result_array() AS $row) {
				if ($row['mode'] == 'advanced')
					$use_advanced = 'use_advanced/true/';
			}
		}
		
		/*$uri_string = trim($CI->uri->uri_string, '/');
		$argsString = str_replace('search/', '', $uri_string);
		$args = parseUrlArgs($argsString);*/
		$args = registry::get('current_search_params_array');

		if (isset($args[$connected_field])) {
			// Receives date in unix timestamp and also in 'Y-m-d' formats
			if (is_numeric($args[$connected_field]) && (strtotime(date("Y-m-d", $args[$connected_field]))) !== FALSE)
				$tmstmp = $args[$connected_field];
			elseif (($tmstmp = strtotime($args[$connected_field])) !== FALSE && strtotime($args[$connected_field]) != -1)
				$tmstmp = strtotime($args[$connected_field]);
		} else 
			$tmstmp = null;

		$url = site_url('search/' . $connected_type . $use_advanced . $connected_field);

		$view = $CI->load->view();
		$view->assign('name', $settings['name']);
		$view->assign('url', $url);
		$view->assign('defaultDate', $tmstmp);
		return $view->fetch('calendar/calendar_block.tpl');
	}
}
?>