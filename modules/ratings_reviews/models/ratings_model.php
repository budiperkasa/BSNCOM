<?php
include_once(MODULES_PATH . 'ratings_reviews/classes/rating.class.php');
include_once(MODULES_PATH . 'ratings_reviews/classes/avg_rating.class.php');

class ratingsModel extends Model
{
	public function isNotRated($objects_table, $object_id)
	{
		if (!get_cookie('rating-' . $objects_table . '-' . $object_id)) {
			$ip = $this->input->ip_address();
			
			if (!($user_id = $this->session->userdata('user_id'))) {
				$user_id = 0;
			}

			$this->db->select('value');
			$this->db->from('ratings');
			$this->db->where('objects_table', $objects_table);
			$this->db->where('object_id', $object_id);
			if ($user_id) {
				$this->db->where('user_id', $user_id);
			} else {
				$this->db->where('ip', $ip);
			}
			return !$this->db->get()->num_rows();
		} else {
			return false;
		}
	}
	
	public function getRatings($objects_table, $object_id)
	{
		$this->db->select('r.*');
		$this->db->from('ratings AS r');
		$this->db->where('r.objects_table', $objects_table);
		$this->db->where('r.object_id', $object_id);
		$array = $this->db->get()->result_array();

		$ratings = array();
		foreach ($array AS $row) {
			$rating = new rating($row);
			$rating->getUser();
			$rating->getObject();
			$ratings[] = $rating;
		}
		return $ratings;
	}

	public function buildAverageRating($ratings, $object_table, $object_id)
	{
		$avg_rating = new avgRating($ratings, $object_table, $object_id);
		return $avg_rating;
	}
	
	public function getRatingByUserId($objects_table, $object_id, $user_id)
	{
		$this->db->select('r.*');
		$this->db->from('ratings AS r');
		$this->db->where('r.objects_table', $objects_table);
		$this->db->where('r.object_id', $object_id);
		$this->db->where('r.user_id', $user_id);
		$query = $this->db->get();
		
		if ($row = $query->row_array()) {
			$rating = new rating($row);
			//$rating->getUser();
			//$rating->getObject();
			return $rating;
		} else 
			return false;
	}
	
	/*public function getRatingsCounts($objects_table, $object_id)
	{
		$this->db->select('COUNT(rating) AS ratings_count');
		$this->db->select_avg('rating', 'ratings_avg');
		$this->db->from('ratings');
		$this->db->where('objects_table', $objects_table);
		$this->db->where('object_id', $object_id);
		$query = $this->db->get();
		return $query->row_array();
	}*/
	
	public function rateObject($objects_table, $object_id, $rating)
	{
		if ($this->session->userdata('user_id')) {
			$user_id = $this->session->userdata('user_id');
		} else {
			$user_id = 0;
		}
		
		set_cookie('rating-' . $objects_table . '-' . $object_id, $rating, 31536000);
		
		$ip = $this->input->ip_address();

		$this->db->set('objects_table', $objects_table);
		$this->db->set('object_id', $object_id);
		$this->db->set('user_id', $user_id);
		$this->db->set('ip', $ip);
		$this->db->set('value', $rating);
		$this->db->set('date_added', date("Y-m-d H:i:s"));
		if ($this->db->insert('ratings'))
			return $this->db->insert_id();
		else 
			return false;
	}
	
	public function getRatingById($rating_id)
	{
		$this->db->select('rat.*');
		$this->db->from('ratings AS rat');
		$this->db->where('rat.id', $rating_id);
		$query = $this->db->get();

		$rating = new rating($query->row_array());
		return $rating;
	}
	
	public function deleteRatings($ratings_array)
    {
    	if (count($ratings_array)) {
    		foreach (array_keys($ratings_array) AS $rating_id) {
    			$rating = $this->getRatingById($rating_id);
				$rating->getObject();
    			$rating->object->cleanCache();
    		}

    		$this->db->where_in('id', array_keys($ratings_array));
    		return $this->db->delete('ratings');
    	}
    }
}
?>