<?php

class avgRating
{
	public $ratings = array();
	public $total_value = 0;
	public $avg_value = 0;
	public $ratings_count = 0;
	public $active = true;
	public $objects_table;
	public $object_id;

	public $id;
	public $url_to_rate;

	public function __construct($ratings, $objects_table, $object_id)
	{
		$this->ratings = $ratings;
		$this->ratings_count = count($ratings);
		$this->objects_table = $objects_table;
		$this->object_id = $object_id;

		foreach ($ratings AS $rating) {
			$this->total_value += $rating->value;
		}
		if ($this->ratings_count)
			$this->avg_value = number_format($this->total_value/$this->ratings_count, 2);
		
		$this->url_to_rate = site_url('rate/' . $objects_table . '/' . $object_id . '/');
		
		$CI = &get_instance();
		$CI->load->model('ratings', 'ratings_reviews');
		if (!$CI->ratings->isNotRated($objects_table, $object_id)) {
			$this->active = false;
		} else {
			$system_settings = registry::get('system_settings');
			if (!$system_settings['anonym_rates_reviews'] && !$CI->session->userdata('user_id')) {
				$this->active = false;
			}
		}
	}
	
	public function getObjectId()
	{
		return $this->objects_table . '-' . $this->object_id;
	}
	
	public function setInactive()
	{
		$this->active = false;
	}

	public function view()
	{
		$CI = &get_instance();
		$view = $CI->load->view();
		$view->assign('avg_rating', $this);
		return $view->fetch('ratings_reviews/avg_rating.tpl');
	}
}
?>