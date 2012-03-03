<?php
function smarty_insert_route($args, &$view)
{
	$route_str = "route = new Array(";
	$CI = &get_instance();
	$route = $CI->uri->uri_string();
	$route = explode("/", $route);
	$route_str .= "'" . implode("', '", array_filter($route)) . "'";
	$route_str .= ")";
	return $route_str;
}
?>
