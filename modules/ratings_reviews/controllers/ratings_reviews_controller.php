<?php
include_once(MODULES_PATH . 'ratings_reviews/classes/reviews_block.class.php');

class ratings_reviewsController extends controller
{
	public function rate($objects_table, $object_id)
	{
		$this->load->model('ratings');
		$rating = $this->input->post('rating');
		
		$system_settings = registry::get('system_settings');
		$user_login = $this->session->userdata('user_login');
		
		if ($rating < 1 || $rating > 5) {
			return;
		}
		
		if (!$system_settings['anonym_rates_reviews']) {
			if (!$user_login) {
				return;
			}
		}

		if ($this->ratings->isNotRated($objects_table, $object_id)) {
			if ($last_id = $this->ratings->rateObject($objects_table, $object_id, $rating)) {
				$last_rating = $this->ratings->getRatingById($last_id);
				$last_rating->getObject();
				$last_rating->getObject()->cleanCache();
				$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('mixed_reviews_comments'));
			}
		}

		$ratings = $this->ratings->getRatings($objects_table, $object_id);
		$avg_rating = $this->ratings->buildAverageRating($ratings, $objects_table, $object_id);
		echo $avg_rating->avg_value;
	}
	
	/**
	 * validation function
	 *
	 * @param string $email
	 * @return bool
	 */
	public function check_captcha($captcha)
	{
		if ($this->session->userdata('captcha_word') != $captcha) {
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	public function add_review($objects_table, $object_id/*, $mode*/)
	{
		$this->load->model('reviews');
		
		$system_settings = registry::get('system_settings');
		$user_login = $this->session->userdata('user_login');
		
		if (!htmlspecialchars($this->input->post('review'))) {
			$error = LANG_REVIEW_BODY_ERROR;
		}
		
		if (strlen(strip_tags($this->input->post('review'))) > REVIEW_MAX_LENGTH) {
			$error = LANG_REVIEW_LENGTH_ERROR;
		}
		
		if (!$system_settings['anonym_rates_reviews']) {
			if (!$user_login) {
				$error = LANG_REVIEW_LOGIN_ERROR;
			}
		} else {
			// Anonyms allowed
			if (!$user_login) {
				if (!$this->check_captcha($this->input->post('captcha'))) {
					$error = LANG_CHECK_CAPTCHA;
				}
				if (!$this->input->post('anonym_email') || !$this->form_validation->valid_email($this->input->post('anonym_email'))) {
					$error = LANG_REIVEW_EMAIL_ERROR;
				}
				if (!$this->input->post('anonym_name')) {
					$error = LANG_REVIEW_NAME_ERROR;
				}
			}
		}
		
		//$review = htmlspecialchars($this->input->post('review'));
		$review = $this->input->post('review');
		$review = breakLongWords($review, 35);
		//if ($mode == 'comments')
			$parent_id = $this->input->post('parent_id');
		/*else 
			$parent_id = 0;*/
		$anonym_name = $this->input->post('anonym_name');
		$anonym_email = $this->input->post('anonym_email');
		
		if (!isset($error))
			if ($last_id = $this->reviews->addReview($objects_table, $object_id, $review, $parent_id, $anonym_name, $anonym_email)) {
				$last_review = $this->reviews->getReviewById($last_id);
				$last_review->getObject();
				$last_review->object->cleanCache();
				$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('mixed_reviews_comments'));
	    		$error = '';
	    	}

		// JQuery .post needs JSON response
		echo json_encode(array('error_msg' => _utf8_encode($error)));
	}
	
	public function refresh_reviews()
	{
		$params = json_decode($this->input->post('params'));

		include_once(BASEPATH . 'view/smarty/libs/plugins/function.render_frontend_block.php');
		echo smarty_function_render_frontend_block($params, null);
	}


	// --------------------------------------------------------------------------------------------
	// Manage ratings & reviews routes
	// --------------------------------------------------------------------------------------------
	/**
	 * Bulk ratings delete
	 *
	 */
	public function massRatingsDelete()
    {
    	$this->load->model('ratings', 'ratings_reviews');

		$ratings_ids = $this->input->searchPostItems('cb_');
		if (empty($ratings_ids)) {
			$ratings_ids = $this->input->post('options');
		}
		$ratings_array = array();
		if (!empty($ratings_ids)) {
			foreach ($ratings_ids AS $id) {
				$rating = $this->ratings->getRatingById($id);
				$rating->getObject();
				$rating->object->checkAccess('Manage self ratings', 'Manage all ratings');
				$ratings_array[$id] = '';
			}
		} else {
			$this->setError(LANG_RATINGS_SELECT_ERROR);
			redirect($this->session->userdata('ratings_back_page'));
		}

        if ($this->input->post('yes')) {
            if ($this->ratings->deleteRatings($ratings_array)) {
                $this->setSuccess(LANG_RATINGS_DELETE_SUCCESS);
                redirect($this->session->userdata('ratings_back_page'));
            }
        }

        if ($this->input->post('no')) {
        	redirect($this->session->userdata('ratings_back_page'));
        }

        $view  = $this->load->view();
		$view->assign('options', $ratings_array);
        $view->assign('heading', LANG_DELETE_RATINGS);
        $view->assign('question', LANG_DELETE_RATINGS_QUEST);
        $view->display('backend/delete_common_item.tpl');
    }

	public function edit_reviews($review_id)
	{
		$this->load->model('reviews', 'ratings_reviews');
		$review = $this->reviews->getReviewById($review_id);
		$review->getUser();
		$review->getObject();
		if (!$review->object->isObject())
			show_404("Object connected with this review wasn't found!");

		$review->object->checkAccess('Manage self reviews', 'Manage all reviews');

		if ($this->input->post('submit')) {
			$this->form_validation->set_rules('review_body', LANG_REVIEW_BODY, 'required');
    		
    		if ($this->form_validation->run() !== FALSE) {
    			if ($this->reviews->saveReviewById($review_id, $this->form_validation->set_value('review_body'))) {
    				$review->object->cleanCache();

    				$this->setSuccess(LANG_REVIEW_SAVED_SUCCESS);
    				redirect($this->session->userdata('reviews_back_page'));
    			}
    		} else {
    			$review->review =  $this->form_validation->set_value('review_body');
    		}
		}
		
		$review->object->setBreadcrumbs(LANG_EDIT_REVIEWS_TITLE);

		$view = $this->load->view();
		$view->assign('review', $review);
		$view->display('ratings_reviews/admin_review_settings.tpl');
	}
	
	/**
	 * Bulk reviews delete
	 *
	 */
	public function massReviewsDelete()
    {
    	$this->load->model('reviews', 'ratings_reviews');

		$reviews_ids = $this->input->searchPostItems('cb_');
		if (empty($reviews_ids)) {
			$reviews_ids = $this->input->post('options');
		}
		
		$reviews_to_delete_array = array();
		$reviews_array = array();
		if (!empty($reviews_ids)) {
			foreach ($reviews_ids AS $id) {
				$review = $this->reviews->getReviewById($id);
				$review->getUser();
				$review->getObject();
				$review->object->checkAccess('Manage self reviews', 'Manage all reviews');

				$reviews_array[] = $review;
				if ($review->user_id)
					$user_name = $review->user->login;
				else
					$user_name = $review->anonym_name;
				$reviews_to_delete_array[$id] = 'Review of: ' . $user_name . ' ' . date("Y-m-d h:i:s", strtotime($review->date_added));
			}
		} else {
			$this->setError(LANG_REVIEWS_SELECT_ERROR);
			redirect($this->session->userdata('reviews_back_page'));
		}

        if ($this->input->post('yes')) {
        	foreach ($reviews_array AS $review) {
				$reviews_db = $this->reviews->getReviewsArray($review->objects_table, $review->object_id); 
	            $this->reviews->deleteReviews($reviews_db, $reviews_to_delete_array);
        	}
			$this->setSuccess(LANG_REVIEWS_DELETE_SUCCESS);
			redirect($this->session->userdata('reviews_back_page'));
        }

        if ($this->input->post('no')) {
        	redirect($this->session->userdata('reviews_back_page'));
        }

        $view  = $this->load->view();
		$view->assign('options', $reviews_to_delete_array);
        $view->assign('heading', LANG_DELETE_REVIEWS);
        $view->assign('question', LANG_DELETE_REVIEWS_QUEST);
        $view->display('backend/delete_common_item.tpl');
    }
    
    /**
	 * Bulk mark reviews as spam
	 *
	 */
	public function massReviewsSpam()
    {
    	$this->load->model('reviews', 'ratings_reviews');

		$reviews_ids = $this->input->searchPostItems('cb_');

		if (!empty($reviews_ids)) {
			foreach ($reviews_ids AS $id) {
				$review = $this->reviews->getReviewById($id);
				$review->getUser();
				$review->getObject();
				$review->object->checkAccess('Manage self reviews', 'Manage all reviews');
			}
		} else {
			$this->setError(LANG_REVIEWS_SELECT_ERROR);
			redirect($this->session->userdata('reviews_back_page'));
		}

		if ($this->reviews->spamReviews($reviews_ids)) {
			$this->setSuccess(LANG_REVIEWS_SPAM_SUCCESS);
			redirect($this->session->userdata('reviews_back_page'));
		}
    }
    
    /**
	 * Bulk reviews activation
	 *
	 */
	public function massReviewsActive()
    {
    	$this->load->model('reviews', 'ratings_reviews');

		$reviews_ids = $this->input->searchPostItems('cb_');

		if (!empty($reviews_ids)) {
			foreach ($reviews_ids AS $id) {
				$review = $this->reviews->getReviewById($id);
				$review->getUser();
				$review->getObject();
				$review->object->checkAccess('Manage self reviews', 'Manage all reviews');
			}
		} else {
			$this->setError(LANG_REVIEWS_SELECT_ERROR);
			redirect($this->session->userdata('reviews_back_page'));
		}

		if ($this->reviews->activateReviews($reviews_ids)) {
			$this->setSuccess(LANG_REVIEWS_ACTIVATE_SUCCESS);
			redirect($this->session->userdata('reviews_back_page'));
		}
    }
    
    public function search_reviews($objects_table, $argsString = '')
	{
		$args = parseUrlArgs($argsString);

		// Search url needs for 'asc_desc_insert.base_url' argument of smarty function
		$clean_url = site_url('admin/reviews/' . $objects_table . '/search/');
		$base_url = $clean_url;
		$search_url = $base_url;
		if (isset($args['search_login'])) {
			$search_login = $args['search_login'];
			$search_url .= 'search_login/' . $search_login . '/';
		}
		if (isset($args['search_anonyms'])) {
			$search_anonyms = $args['search_anonyms'];
			$search_url .= 'search_anonyms/' . $search_anonyms . '/';
		}
		if (isset($args['search_status'])) {
			$search_status = $args['search_status'];
			$search_url .= 'search_status/' . $search_status . '/';
		}
		if (isset($args['search_date_added'])) {
			$search_date_added = $args['search_date_added'];
			$search_url .= 'search_date_added/' . $search_date_added . '/';
		}
		if (isset($args['search_from_date_added'])) {
			$search_from_date_added = $args['search_from_date_added'];
			$search_url .= 'search_from_date_added/' . $search_from_date_added . '/';
		}
		if (isset($args['search_to_date_added'])) {
			$search_to_date_added = $args['search_to_date_added'];
			$search_url .= 'search_to_date_added/' . $search_to_date_added . '/';
		}
		
		// Paginator url needs for '.../page/x/' url modification
		$paginator_url = $search_url;
		if (isset($args['orderby'])) {
			$orderby = $args['orderby'];
			$paginator_url .= 'orderby/' . $args['orderby'] . '/';
		} else {
			$orderby = 'id';
		}
		if (isset($args['direction'])) {
			$direction = $args['direction'];
			$paginator_url .= 'direction/' . $args['direction'] . '/';
		} else {
			$direction = 'desc';
		}

		$paginator = new pagination(array('args' => $args, 'url' => $paginator_url, 'num_per_page' => 10));
		$this->load->model('reviews', 'ratings_reviews');
		$this->reviews->setPaginator($paginator);
		$reviews = $this->reviews->selectReviews($objects_table, $orderby, $direction, $args);

		$view = $this->load->view();
		$view->assign('reviews', $reviews);
		$view->assign('reviews_count', $paginator->count());
		$view->assign('paginator', $paginator->placeLinksToHtml());

		$view->assign('args', $args);
		$view->assign('orderby', $orderby);
		$view->assign('direction', $direction);
		$view->assign('base_url', $base_url);
		$view->assign('search_url', $search_url);
		
		$mode = 'single';
		if (isset($args["search_date_added"])) {
			$view->assign('date_added', date("y-m-d", $args["search_date_added"]));
			$view->assign('date_added_tmstmp', $args["search_date_added"]);
		}
		if (isset($args["search_from_date_added"])) {
			$view->assign('from_date_added', date("y-m-d", $args["search_from_date_added"]));
			$view->assign('from_date_added_tmstmp', $args["search_from_date_added"]);
			$mode = 'range';
		}
		if (isset($args["search_to_date_added"])) {
			$view->assign('to_date_added', date("y-m-d", $args["search_to_date_added"]));
			$view->assign('to_date_added_tmstmp', $args["search_to_date_added"]);
			$mode = 'range';
		}
		$view->assign('mode', $mode);

		// Add js library for Send Message feature
		$view->addJsFile('jquery.jqURL.js');

		$this->session->set_userdata('reviews_back_page', uri_string());
		$view->display('ratings_reviews/admin_search_reviews.tpl');
	}
}



/**
 * breaks words longer than $max_chars by '-' symbol
 * 
*/
function breakLongWords($string, $max_chars = 20)
{
	$length = strlen($string);

	$wrap = 0;
	$skip = 0;
	$returnvar = '';
	for ($i=0; $i<=$length; $i=$i+1) {
    	$char = substr($string, $i, 1);
    	if ($char == "<")
    		$skip=1;
    	elseif ($char == ">")
    		$skip=0;
    	elseif ($char == " ")
    		$wrap=0;

		if ($skip==0)
			$wrap=$wrap+1;

		$returnvar .= $char;

		if ($wrap > ($max_chars-1)) {
			$returnvar = $returnvar . "-";
			$wrap = 0;
		}
	}
	return $returnvar;
} 
?>