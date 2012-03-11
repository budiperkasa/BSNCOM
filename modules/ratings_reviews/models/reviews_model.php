<?php
include_once(MODULES_PATH . 'ratings_reviews/classes/review.class.php');

class reviewsModel extends Model
{
	protected $mode = 'admin';
	
	public function setMode($mode)
	{
		$this->mode = $mode;
	}
	
	public function getReviewsArray($objects_table = null, $objects_ids = array())
	{
		$this->db->select('rev.*');
		$this->db->from('reviews AS rev');
		if ($objects_table)
			if (is_array($objects_table))
				$this->db->where_in('rev.objects_table', $objects_table);
			else
				$this->db->where('rev.objects_table', $objects_table);
		if ($objects_ids)
			if (is_array($objects_ids))
				$this->db->where_in('rev.object_id', $objects_ids);
			else 
				$this->db->where('rev.object_id', $objects_ids);
		$this->db->order_by('rev.date_added');
		$query = $this->db->get();

		return $query->result_array();
	}
	
	public function getReviewsStructured($objects_table, $object_id)
	{
		$reviews_array = $this->getReviewsArray($objects_table, $object_id);
		
		$reviews_structured_array = array();
		$this->buildReviewsRecursively($objects_table, $object_id, $reviews_structured_array, $reviews_array);
		
		return $reviews_structured_array;
	}
	
	private function buildReviewsRecursively($objects_table, $object_id, &$reviews_structured_array, $reviews_db, $parent_review = null)
	{
		if (is_null($parent_review))
			$parent_review_id = 0;
		else
			$parent_review_id = $parent_review->id;
		
		foreach ($reviews_db AS $key=>$reviews_item) {
			if ($reviews_item['parent_id'] == $parent_review_id) {
				$review = new review($reviews_item);
				$review->setMode($this->mode);
				$review->setRating($objects_table, $object_id);
				if (!is_null($parent_review)) {
					$parent_review->attachChild($review);
					unset($reviews_db[$key]);
				} else {
					$reviews_structured_array[] = $review;
				}
				$this->buildReviewsRecursively($objects_table, $object_id, $reviews_structured_array, $reviews_db, $review);
			}
		}
	}

	public function getReviewsCount($objects_table = null, $objects_ids = array())
	{
		$this->db->select('COUNT(id) AS reviews_count');
		$this->db->from('reviews');
		//$this->db->where('objects_table', $objects_table);
		if ($objects_table)
			if (is_array($objects_table))
				$this->db->where_in('objects_table', $objects_table);
			else
				$this->db->where('objects_table', $objects_table);
		if ($objects_ids)
			if (is_array($objects_ids))
				$this->db->where_in('object_id', $objects_ids);
			else 
				$this->db->where('object_id', $objects_ids);
		/*if (is_numeric($objects_ids) && !empty($objects_ids)) {
    		$this->db->where('object_id', $objects_ids);
    	} elseif (is_array($objects_ids) && !empty($objects_ids)) {
    		$this->db->where_in('object_id', $objects_ids);
    	}*/
		if ($this->mode != 'admin') {
			$this->db->where('status', 1);
		}
		$row = $this->db->get()->row_array();
		return $row['reviews_count'];
	}

	public function addReview($objects_table, $object_id, $review, $parent_id, $anonym_name, $anonym_email)
	{
		if ($this->session->userdata('user_id')) {
			$user_id = $this->session->userdata('user_id');
		} else {
			$user_id = 0;
		}
		
		$ip = $this->input->ip_address();

		$event_params = array(
			'OBJECTS_TABLE' => $objects_table,
			'OBJECT_ID' => $object_id,
			'REVIEW_BODY' => $review,
			'PARENT_REIVEW_ID' => $parent_id,
			'USER_ID' => $user_id,
			'ANONYM_NAME' => $anonym_name,
			'ANONYM_EMAIL' => $anonym_email,
			'IP' => $ip,
		);
		events::callEvent('Review creation', $event_params);
		
		// Check this comment/review for spam
		$sprotector = new spamProtector();
    	if ($sprotector->isSpam($ip, $review, null, $anonym_name, $anonym_email))
    		$status = 2;
    	else 
    		$status = 1;

		$this->db->set('objects_table', $objects_table);
		$this->db->set('object_id', $object_id);
		$this->db->set('review', $review);
		$this->db->set('parent_id', $parent_id);
		$this->db->set('anonym_name', $anonym_name);
		$this->db->set('anonym_email', $anonym_email);
		$this->db->set('user_id', $user_id);
		$this->db->set('status', $status);
		$this->db->set('ip', $ip);
		$this->db->set('date_added', date("Y-m-d H:i:s"));
		if ($this->db->insert('reviews'))
			return $this->db->insert_id();
		else 
			return false;
	}
	
	public function getReviewById($review_id)
	{
		$this->db->select('rev.*');
		$this->db->from('reviews AS rev');
		$this->db->where('rev.id', $review_id);
		$query = $this->db->get();

		$review = new review($query->row_array());
		return $review;
	}
	
	public function saveReviewById($review_id, $review_body)
	{
		$this->db->set('review', $review_body);
		$this->db->where('id', $review_id);
		return $this->db->update('reviews');
	}
	
	public function deleteReviews($reviews_db, &$reviews_array)
    {
    	if (count($reviews_array)) {
    		$reviews_to_delete = array();
    		foreach ($reviews_array AS $review_item=>$val) {
    			$reviews_to_delete[] = $review_item;
	    		foreach ($reviews_db AS $review_item_db) {
	    			if ($review_item == $review_item_db['parent_id']) {
	    				$reviews_to_delete[] = $review_item_db['id'];
	    				$this->findReviewsToDeleteRecursively($reviews_db, $review_item_db['id'], $reviews_to_delete);
	    			}
	    		}
    		}

    		foreach ($reviews_to_delete AS $review_id) {
    			$review = $this->getReviewById($review_id);
				$review->getObject();
    			$review->object->cleanCache();
    		}
    		
    		foreach ($reviews_to_delete AS $id) {
    			if (isset($reviews_array[$id]))
    				unset($reviews_array[$id]);
    		}

    		$this->db->where_in('id', $reviews_to_delete);
    		return $this->db->delete('reviews');
    	}
    }
    
    /**
     * find all children of review/comment that we need to delete,
     * then we will delete them either
     *
     * @param array $reviews_db - reviews of object
     * @param int $parent_id
     * @param array $reviews_to_delete - return added items in this array
     */
    private function findReviewsToDeleteRecursively($reviews_db, $parent_id, &$reviews_to_delete)
    {
    	foreach ($reviews_db AS $review_item_db) {
	    	if ($parent_id == $review_item_db['parent_id']) {
	    		$reviews_to_delete[] = $review_item_db['id'];
	    		$this->findReviewsToDeleteRecursively($reviews_db, $review_item_db['id'], $reviews_to_delete);
	    	}
	    }
    }
    
    public function spamReviews($reviews_ids)
    {
    	if (count($reviews_ids)) {
    		foreach ($reviews_ids AS $reviews_id) {
    			$review = $this->getReviewById($reviews_id);
				$review->getObject();
    			$review->object->cleanCache();
    		}
    		
    		$this->db->set('status', 2);
    		$this->db->where_in('id', $reviews_ids);
    		return $this->db->update('reviews');
    	}
    }
    
    public function activateReviews($reviews_ids)
    {
    	if (count($reviews_ids)) {
    		foreach ($reviews_ids AS $reviews_id) {
    			$review = $this->getReviewById($reviews_id);
				$review->getObject();
    			$review->object->cleanCache();
    		}

    		$this->db->set('status', 1);
    		$this->db->where_in('id', $reviews_ids);
    		return $this->db->update('reviews');
    	}
    }
    
    /**
     * Select all reviews table using paginator,
     * this executes optimized method with 3 queries
     *
     * @return array
     */
    public function selectReviews($objects_table = null, $orderby = 'id', $direction = 'asc', $args = array())
    {
    	$this->db->select('r.id');
    	$this->db->from('reviews AS r');
    	if ($objects_table)
			if (is_array($objects_table))
				$this->db->where_in('r.objects_table', $objects_table);
			else
				$this->db->where('r.objects_table', $objects_table);
    	if ($orderby)
    		$this->db->order_by($orderby, $direction);
    	if (isset($args['search_login'])) {
    		$this->db->join('users AS u', 'u.id=r.user_id', 'left');
    		$this->db->like('u.login', urldecode(html_entity_decode($args['search_login'])));
    	}
    	if (isset($args['search_anonyms'])) {
    		if ($args['search_anonyms'] == 'anonyms') {
    			$this->db->where('r.user_id', 0);
    		}
    	}
    	if (isset($args['search_status'])) {
    		$this->db->where('r.status', urldecode($args['search_status']));
    	}
    	if (isset($args['search_date_added'])) {
    		$this->db->where('TO_DAYS(r.date_added) = ', 'TO_DAYS("' . date("Y-m-d", $args['search_date_added']) . '")', false);
    	}
    	if (isset($args['search_from_date_added'])) {
    		$this->db->where('TO_DAYS(r.date_added) >= ', 'TO_DAYS("' . date("Y-m-d", $args['search_from_date_added']) . '")', false);
    	}
    	if (isset($args['search_to_date_added'])) {
    		$this->db->where('TO_DAYS(r.date_added) <= ', 'TO_DAYS("' . date("Y-m-d", $args['search_to_date_added']) . '")', false);
    	}
    	if (isset($args['objects_ids']) && is_numeric($args['objects_ids']) && !empty($args['objects_ids'])) {
    		$this->db->where('r.object_id', $args['objects_ids']);
    	} elseif (isset($args['objects_ids']) && is_array($args['objects_ids']) && !empty($args['objects_ids'])) {
    		$this->db->where_in('r.object_id', $args['objects_ids']);
    	}
	    $result_array = $this->db->get()->result_array();
    	$all_ids = array();
    	foreach ($result_array AS $id) {
    		$all_ids[] = $id['id'];
    	}

    	if (isset($this->paginator) && $this->paginator) {
    		$this->paginator->setCount(count($all_ids));
    		$all_ids = $this->paginator->getResultIds($all_ids);
    	}

    	if (!empty($all_ids)) {
	    	$this->db->select('*');
	    	$this->db->from('reviews AS r');
	    	$this->db->where_in('r.id', $all_ids);
	    	if ($orderby)
	    		$this->db->order_by($orderby, $direction);
	    	$query = $this->db->get();
	    	$reviews = array();
    		foreach ($query->result_array() AS $row) {
    			$review = new review($row);
				$review->setMode($this->mode);
				$review->setRating($objects_table, $row['object_id']);

				if (isset($args['search_location']) && $args['search_location']) {
					$object_name = $objects_table . "Object";
					$review_object = new $object_name($row['object_id']);
					if ($review_object->checkIsInLocation($args['search_location']))
						$reviews[] = $review;
				} else {
    				$reviews[] = $review;
				}
    		}
    		return $reviews;
    	} else {
    		return array();
    	}
    }
}
?>