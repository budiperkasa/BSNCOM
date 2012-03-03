<?php

class listingsSemitableWrapperClass extends wrapperClass
{
	public function render()
	{
		$view_format = $this->params['view_format'];
		$listings_array = $this->params['items_array'];
		
		if ($view_format['columns'])
			$columns = $view_format['columns'];
		else 
			$columns = 2;

		$table_width = 540;
		$td_space_width = 10;
		$td_padding_border = 12;
		$td_width = (($table_width-($columns-1)*$td_space_width)/$columns) - $td_padding_border;

		$CI = &get_instance();
		$view = $CI->load->view();
		$view->assign('columns', $columns);
		$view->assign('td_width', $td_width);
		$view->assign('td_space_width', $td_space_width);
		$view->assign('td_padding_border', $td_padding_border);
		$view->assign('listings_array', $listings_array);
		return $view->fetch('frontend/wrappers/wrapper_listings_semitable.tpl');
	}
}
?>