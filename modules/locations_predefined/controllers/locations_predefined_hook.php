<?php

function handleLocation($CI)
{
	if ($CI->router->fetch_module() == 'frontend' && $CI->router->fetch_class() == 'frontend') {
		switch ($CI->router->fetch_method()) {
			case 'index':
				$current_route_args = registry::get('current_route_args');
				$location_seo_name = $current_route_args[0];
				break;
			case 'types':
				$current_route_args = registry::get('current_route_args');
				$location_seo_name = $current_route_args[0];
				break;
			case 'categories':
				$current_route_args = registry::get('current_route_args');
				$location_seo_name = $current_route_args[0];
				break;
			case 'search':
				$current_route_args = registry::get('current_route_args');
				$location_seo_name = $current_route_args[0];
				break;
		}
		if (isset($location_seo_name) && $location_seo_name && $location_seo_name != 'any') {
			$CI->session->set_userdata('current_location_seo_name', $location_seo_name);
		} elseif (isset($location_seo_name) && $location_seo_name == 'any') {
			$CI->session->unset_userdata(array('current_location_seo_name' => ''));
		}
		
		if ($current_location_seo_name = $CI->session->userdata('current_location_seo_name')) {
			$CI->load->model('locations', 'locations_predefined');
			if ($location = $CI->locations->getLocationBySeoName($current_location_seo_name))
				registry::set('current_location', $location);
		}
	}
}
?>