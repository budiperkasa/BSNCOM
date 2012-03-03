<?php
/**
 * Web 2.0 Classifieds script smarty function
 * 
 * inserts ascending/descending links on the base of base_url params
 *
 * @param array $params
 * base_url - url string, the base of the link
 * orderby - we need to order by this field
 * orderby_query - current orderby field
 * direction - ascending/descending
 * 
 * @param object $smarty
 * @return string
 */
function smarty_function_asc_desc_insert($params, $smarty)
{
	if (isset($params['base_url'])) {
		$base_url = $params['base_url'];
	} else {
		$base_url = $smarty->get_template_vars('clean_url');
	}
	
	if (isset($params['default_direction'])) {
		$default_direction = $params['default_direction'];
	} else {
		$default_direction = 'desc';
	}
	
	if ($params['orderby'] == $params['orderby_query'] && $params['direction'] == 'asc') {
		$base_url .= 'orderby/' . $params['orderby'] . '/direction/desc/';
		$output = '<a class="descending" title="' . LANG_SORT_DESCENDING . '" href="' . $base_url . '" rel="nofollow">';
	} elseif ($params['orderby'] == $params['orderby_query'] && $params['direction'] == 'desc') {
		$base_url .= 'orderby/' . $params['orderby'] . '/direction/asc/';
		$output = '<a class="ascending" title="' . LANG_SORT_ASCENDING . '" href="' . $base_url . '" rel="nofollow">';
	} else {
		$base_url .= 'orderby/' . $params['orderby'] . '/direction/' . $default_direction . '/';
		if ($default_direction == 'asc')
			$output = '<a href="' . $base_url . '" title="' . LANG_SORT_ASCENDING . '" rel="nofollow">';
		else
			$output = '<a href="' . $base_url . '" title="' . LANG_SORT_DESCENDING . '" rel="nofollow">';
	}
	$output .= $params['title'] .'</a></th>';

	return $output;
}
?>