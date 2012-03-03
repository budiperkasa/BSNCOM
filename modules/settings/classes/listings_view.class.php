<?php

class listingsView
{
	public $type_id;
	public $view;
	public $format;
	public $order_by = 'l.creation_date';
	public $order_direction = 'desc';
	public $levels_visible = array();
	
	public function __construct($row = null)
	{
		if ($row) {
			$this->type_id = $row['type_id'];
			$this->view = $row['view'];
			$this->format = $row['format'];
			$this->order_by = $row['order_by'];
			$this->order_direction = $row['order_direction'];
			if ($row['levels_visible']) {
				$this->levels_visible = explode(',', $row['levels_visible']);
			}
		}
	}
	
	public function getListingsNumberFromFormat()
	{
		$matrix = explode('*', $this->format);
		if (count($matrix) > 1) {
			$result = $matrix[0]*$matrix[1];
		} else {
			$result = $this->format;
		}
		return $result;
	}
	
	public function getViewName()
	{
		switch ($this->view) {
			case 'full':
				return LANG_FRONTEND_SETTING_FULL;
				break;
			case 'semitable':
				return LANG_FRONTEND_SETTING_SEMITABLE;
				break;
			case 'short':
				return LANG_FRONTEND_SETTING_SHORT;
				break;
			case 'quicklist':
				return LANG_FRONTEND_SETTING_QUICK_LIST;
				break;
		}
	}
	
	public function getOrderBy()
	{
		switch ($this->order_by) {
			case 'l.creation_date':
				return LANG_SEARCH_CREATION_DATE;
				break;
			case 'l.title':
				return LANG_SEARCH_LISTING_TITLE;
				break;
			case 'lev.order_num':
				return LANG_SEARCH_INFO_VALUE;
				break;
			case 'rating':
				return LANG_SEARCH_RATING;
				break;
			case 'rev_count':
				return LANG_SEARCH_REVIEWS_COUNT;
				break;
			case 'rev_last':
				return LANG_SEARCH_LAST_REVIEW_DATE;
				break;
			case 'random':
				return LANG_SEARCH_RANDOM;
				break;
		}
	}
}
?>