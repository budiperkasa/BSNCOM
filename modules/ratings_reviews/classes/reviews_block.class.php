<?php
include_once(MODULES_PATH . 'ratings_reviews/classes/review.class.php');

class reviewsBlock
{
	public $objects_table;
	public $objects_ids;
	public $mode;
	
	public $reviews_structured_array = array();
	public $reviews_count = 0;
	
	public $is_rechtext = false;

	public function __construct($objects_table, $objects_ids, $mode, $params)
	{
		$this->objects_table = $objects_table;
		$this->objects_ids = $objects_ids;
		$this->mode = $mode;
		$this->is_richtext = false;
		if (isset($params['is_richtext']))
			$this->is_richtext = $params['is_richtext'];
		
		$CI = &get_instance();
		$CI->load->model('reviews', 'ratings_reviews');
		$CI->reviews->setMode($mode);
		
		$block = new blockClass($params);
		$cache_id = $block->compileCacheId();
		
		$cache_index = 'reviews_comments_block_' . md5(serialize($cache_id));
		if ((isset($params['no_cache']) && $params['no_cache'] == "true") || !$cache = $CI->cache->load($cache_index)) {
			$cache_tags = array();
			// When there isn't exact objects table or any exact objects items - this is reviews block with mixed cache,
			// it will be refreshed most often than another reviews blocks
			if (!$objects_table || !$objects_ids)
				$cache_tags[] = 'mixed_reviews_comments';
			if (!is_null($objects_table) && is_numeric($objects_ids) && !empty($objects_ids)) {
				$this->reviews_structured_array = $CI->reviews->getReviewsStructured($objects_table, $objects_ids);
				$this->reviews_count = $CI->reviews->getReviewsCount($objects_table, $objects_ids);
				$cache_tags[] = $objects_table . '_' . $objects_ids;
				$objects_ids = array($objects_ids);
			} else {
				if (isset($params['orderby']))
					$orderby = $params['orderby'];
				else 
					$orderby = 'id';
				if (isset($params['direction']))
					$direction = $params['direction'];
				else 
					$direction = 'asc';

				if (isset($params['limit'])) {
					$paginator = new pagination(array('num_per_page' => $params['limit']));
					$CI->reviews->setPaginator($paginator);
				}
				$this->reviews_structured_array = $CI->reviews->selectReviews($objects_table, $orderby, $direction, $params);
				$this->reviews_count = count($this->reviews_structured_array);
				foreach ($this->reviews_structured_array AS $review) {
					$cache_tags[] = $review->objects_table . '_' . $review->object_id;
				}
			}
			
			$reviews_rows = $CI->reviews->getReviewsArray($objects_table, $objects_ids);
			foreach ($reviews_rows AS $row) {
				if ($row['user_id'])
					$cache_tags[] = 'users_' . $row['user_id'];
			}

			$CI->cache->save(array($this->reviews_structured_array, $this->reviews_count), $cache_index, $cache_tags);
		} else {
			list($this->reviews_structured_array, $this->reviews_count) = $cache;
		}
	}
	
	/**
	 * When add review body - places richtext with content or textarea if richtext option disabled
	 *
	 * @return string/html
	 */
	public function placeCommentArea()
	{
		if ($this->is_richtext) {
			include_once(BASEPATH . 'richtext_editor/richtextEditor.php');
			$Editor = new richtextEditor('review_body', '');
			//$Editor->Width = 550;
			$Editor->Height = 200;
			$Editor->ToolbarSet = 'Review';
			$Editor->Config['ImageUpload'] = false;
			$Editor->Config['ImageBrowser'] = false;
			return $Editor->CreateHtml();
		} else 
			return '<textarea id="review_body" style="width:100%;" rows="6"></textarea>';
	}

	/**
	 * renders reviews and comments as tree
	 *
	 */
	/*public function showReviews($comment_area_enabled = true)
	{
		$system_settings = registry::get('system_settings');
		
		$CI = &get_instance();
		$CI->load->model('users', 'users');
		$user = $CI->users->getUserById($CI->session->userdata('user_id'));

		//$anonym_rates_reviews = $system_settings['anonym_rates_reviews'];
		//$user_login = $CI->session->userdata('user_login');
		//$user_id = $CI->session->userdata('user_id');

		$view = $CI->load->view();
		//$view->assign('objects_table', $this->objects_table);
		//$view->assign('object_id', $this->object_id);
		//$view->assign('mode', $this->mode);
		//$view->assign('anonym_rates_reviews', $anonym_rates_reviews);
		//$view->assign('user_login', $user_login);
		//$view->assign('reviews_count', $this->reviews_count);
		//$view->assign('reviews_structured_array', $this->reviews_structured_array);
		$view->assign('comment_area_enabled', $comment_area_enabled);
		//$view->assign('comment_area', $this->placeCommentArea()); 
		//$view->assign('is_richtext', $this->is_richtext);
		$view->assign('reviews_block', $this);
		$view->assign('user', $user);

		// If anonym user - insert captcha
		if ($system_settings['anonym_rates_reviews'] && !$user) {
			$CI->load->plugin('captcha');
			$captcha = create_captcha($CI);
			$view->assign('captcha', $captcha);
		}
		return $view->fetch('ratings_reviews/reviews_block.tpl');
	}*/
}
?>