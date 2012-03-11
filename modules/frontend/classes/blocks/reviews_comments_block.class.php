<?php

class reviews_commentsBlockClass extends blockClass
{
	public function getItems()
	{
		/* Params:

		objects_table [string] - not required
		objects_ids [int/array] - not required
		review_item_template - path to reviews template (like 'ratings_reviews/review_item-latest.tpl')
		reviews_mode ['reviews', 'comments']
		admin_mode [bool] - it is admin to edit/delete items, in other case we just render the block
		comment_area_enabled [bool] - ability to add reviews/comments (also here is condition about user logged in or anonyms)
		is_richtext [bool] - we may place richtext editor for blocks with ability to add reviews/comments
		search_login - string
		search_anonyms ['anonyms', false] - Search only anonyms or both anonyms and registered users
		search_status - int
		search_date_added - timestamp
		search_from_date_added - timestamp
		search_to_date_added - timestamp
		orderby - string
		direction - string
		limit - int

		*/
		
		include_once(MODULES_PATH . 'ratings_reviews/classes/reviews_block.class.php');
		$reviews_mode = 'reviews';
		if (isset($this->params['admin_mode']) && $this->params['admin_mode'])
			$reviews_mode = 'admin';
		elseif (isset($this->params['reviews_mode']))
			$reviews_mode = $this->params['reviews_mode'];

		$objects_table = null;
		if (isset($this->params['objects_table']))
			$objects_table = $this->params['objects_table'];
		$objects_ids = array();
		if (isset($this->params['objects_ids']))
			$objects_ids = $this->params['objects_ids'];

		$this->params['reviews_block'] = new reviewsBlock($objects_table, $objects_ids, $reviews_mode, $this->params);

		$CI = &get_instance();
		$CI->load->model('users', 'users');
		$this->params['user'] = $CI->users->getUserById($CI->session->userdata('user_id'));
		// If anonym user - insert captcha
		$system_settings = registry::get('system_settings');
		if ($system_settings['anonym_rates_reviews'] && !$this->params['user']) {
			$CI->load->plugin('captcha');
			$this->params['captcha'] = create_captcha($CI);
		}
	}
}
?>