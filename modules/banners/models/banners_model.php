<?php
include_once(MODULES_PATH . 'banners/classes/banner.class.php');
include_once(MODULES_PATH . 'notifications/classes/notification_sender.class.php');

class bannersModel extends model
{
	private $_banner_id;
	
	public function setBannerId($banner_id)
	{
		$this->_banner_id = $banner_id;
	}
	
	/**
     * Select all banners table using paginator,
     * this executes optimized method with 3 queries
     *
     * @return array
     */
    public function selectBanners($orderby = 'id', $direction = 'desc', $args = array())
    {
    	// -----------------------------------------------------------------------------------
    	// Number of rows needs
    	//
    	$this->db->select('count(DISTINCT b.id) as count_rows');
    	$this->db->from('banners as b');

    	if (isset($args['search_block_id'])) {
    		$this->db->like('b.block_id', urldecode($args['search_block_id']));
    	}
    	
    	if (isset($args['search_owner'])) {
    		$this->db->join('users as u', 'b.owner_id=u.id', 'left');
    		$this->db->like('u.login', urldecode(html_entity_decode($args['search_owner'])));
    	}

    	if (isset($args['search_status'])) {
    		$this->db->where('b.status', urldecode($args['search_status']));
    	}

    	if (isset($args['search_creation_date'])) {
    		$this->db->where('TO_DAYS(b.creation_date) = ', 'TO_DAYS("' . date("Y-m-d", $args['search_creation_date']) . '")', false);
    	}
    	if (isset($args['search_from_creation_date'])) {
    		$this->db->where('TO_DAYS(b.creation_date) >= ', 'TO_DAYS("' . date("Y-m-d", $args['search_from_creation_date']) . '")', false);
    	}
    	if (isset($args['search_to_creation_date'])) {
    		$this->db->where('TO_DAYS(b.creation_date) <= ', 'TO_DAYS("' . date("Y-m-d", $args['search_to_creation_date']) . '")', false);
    	}

    	$query = $this->db->get();
    	$row = $query->row_array('count_rows');
    	$this->paginator->setCount($row['count_rows']);

    	// -----------------------------------------------------------------------------------
    	// Select id of listings that will be shown
    	//
    	$this->db->distinct();
    	$this->db->select('b.id');
    	$this->db->from('banners as b');

    	if ($orderby)
    		$this->db->order_by('b.' . $orderby, $direction);
    		
    	if (isset($args['search_block_id'])) {
    		$this->db->like('b.block_id', urldecode($args['search_block_id']));
    	}

    	if (isset($args['search_owner'])) {
    		$this->db->join('users as u', 'b.owner_id=u.id', 'left');
    		$this->db->like('u.login', urldecode(html_entity_decode($args['search_owner'])));
    	}

    	if (isset($args['search_status'])) {
    		$this->db->where('b.status', urldecode($args['search_status']));
    	}

    	if (isset($args['search_creation_date'])) {
    		$this->db->where('TO_DAYS(b.creation_date) = ', 'TO_DAYS("' . date("Y-m-d", $args['search_creation_date']) . '")', false);
    	}
    	if (isset($args['search_from_creation_date'])) {
    		$this->db->where('TO_DAYS(b.creation_date) >= ', 'TO_DAYS("' . date("Y-m-d", $args['search_from_creation_date']) . '")', false);
    	}
    	if (isset($args['search_to_creation_date'])) {
    		$this->db->where('TO_DAYS(b.creation_date) <= ', 'TO_DAYS("' . date("Y-m-d", $args['search_to_creation_date']) . '")', false);
    	}

    	$query = $this->db->get();
    	$ids = $this->paginator->getResultIds($query->result_array());

    	if (!empty($ids)) {
	    	$this->db->select('b.*');
	    	$this->db->from('banners as b');
	    	if ($orderby)
    			$this->db->order_by('b.' . $orderby, $direction);
	    	$_array = array();
	    	foreach ($ids AS $id) {
	    		$_array[] = $id['id'];
	    	}
	    	$this->db->where_in('b.id', $_array);
	    	if ($orderby)
    			$this->db->order_by('b.' . $orderby, $direction);
    		$array = $this->db->get()->result_array();
    		$banners = array();
    		foreach ($array AS $row) {
    			$banner = new banner($row['block_id']);
    			$banner->setBannerFromArray($row);
    			$banner->buildObject();
    			$banners[] = $banner;
    		}
    		return $banners;
    	} else {
    		return array();
    	}
    }
    
    public function selectMyBanners($orderby = 'id', $direction = 'desc')
    {
    	// -----------------------------------------------------------------------------------
    	// Number of rows needs
    	//
    	$this->db->select('count(DISTINCT b.id) as count_rows');
    	$this->db->from('banners as b');
    	$this->db->where('b.owner_id', $this->session->userdata('user_id'));

    	$query = $this->db->get();
    	$row = $query->row_array('count_rows');
    	$this->paginator->setCount($row['count_rows']);

    	// -----------------------------------------------------------------------------------
    	// Select id of listings that will be shown
    	//
    	$this->db->distinct();
    	$this->db->select('b.id');
    	$this->db->from('banners as b');
    	if ($orderby)
    		$this->db->order_by('b.' . $orderby, $direction);
    	$this->db->where('b.owner_id', $this->session->userdata('user_id'));
    	$query = $this->db->get();
    	$ids = $this->paginator->getResultIds($query->result_array());

    	if (!empty($ids)) {
	    	$this->db->select('b.*');
	    	$this->db->from('banners as b');
	    	if ($orderby)
    			$this->db->order_by('b.' . $orderby, $direction);
	    	$_array = array();
	    	foreach ($ids AS $id) {
	    		$_array[] = $id['id'];
	    	}
	    	$this->db->where_in('b.id', $_array);
	    	if ($orderby)
    			$this->db->order_by('b.' . $orderby, $direction);
    		$array = $this->db->get()->result_array();
    		$banners = array();
    		foreach ($array AS $row) {
    			$banner = new banner($row['block_id']);
    			$banner->setBannerFromArray($row);
    			$banner->buildObject();
    			$banners[] = $banner;
    		}
    		return $banners;
    	} else {
    		return array();
    	}
    }

	public function getNewBanner($block_id)
    {
		$banner = new banner($block_id);
        return $banner;
    }
    
    public function getBannerFromForm($block_id, $form)
    {
    	$banner = new banner($block_id);
		$banner->setBannerFromArray($form);
		$banner->buildObject();
		return $banner;
    }

	public function saveBanner($block_id, $form, $banners_block)
	{
		$this->db->set('block_id', $block_id);
		$this->db->set('url', $form['url']);
		$this->db->set('banner_file', $form['banner_file']);
		$this->db->set('owner_id', $this->session->userdata('user_id'));
		$this->db->set('status', 1);
		$this->db->set('creation_date', date("Y-m-d H:i:s"));
    	$this->db->set('expiration_date', date("Y-m-d H:i:s", (mktime() + (
    											($banners_block->active_days) +
    											($banners_block->active_months*30) +
    											($banners_block->active_years*365)
    											)*86400)));
    	$this->db->set('was_prolonged_times', '0');
    	$this->db->set('clicks_expiration_count', $banners_block->clicks_limit);
    	$this->db->set('is_uploaded_flash', $form['is_uploaded_flash']);
    	if (isset($form['remote_image_url'])) {
			$this->db->set('use_remote_image', $form['use_remote_image']);
	    	$this->db->set('remote_image_url', $form['remote_image_url']);
	    	$this->db->set('is_loaded_flash', $form['is_loaded_flash']);
		}
		if (!$form['use_all_locations'] && isset($form['locations_checked_list']))
			$this->db->set('checked_locations', serialize($form['locations_checked_list']));
		else 
			$this->db->set('checked_locations', '');
		if (!$form['use_all_categories'] && isset($form['categories_checked_list']))
			$this->db->set('checked_categories', serialize($form['categories_checked_list']));
		else 
			$this->db->set('checked_categories', '');
		$this->db->insert('banners');
		return $this->db->insert_id();
	}
	
	public function getBannerById($banner_id = null)
	{
		if (is_null($banner_id))
			$banner_id = $this->_banner_id;
		
		$this->db->select();
		$this->db->from('banners');
		$this->db->where('id', $banner_id);
		$row = $this->db->get()->row_array();

		$banner = new banner($row['block_id']);
		$banner->setBannerFromArray($row);
		$banner->buildObject();
		return $banner;
	}
	
	public function saveBannerById($form)
	{
		$this->db->set('url', $form['url']);
		$this->db->set('banner_file', $form['banner_file']);
		$this->db->set('is_uploaded_flash', $form['is_uploaded_flash']);
		if (isset($form['remote_image_url'])) {
			$this->db->set('use_remote_image', $form['use_remote_image']);
	    	$this->db->set('remote_image_url', $form['remote_image_url']);
	    	$this->db->set('is_loaded_flash', $form['is_loaded_flash']);
		}
		if (!$form['use_all_locations'] && isset($form['locations_checked_list']))
			$this->db->set('checked_locations', serialize($form['locations_checked_list']));
		else 
			$this->db->set('checked_locations', '');
		if (!$form['use_all_categories'] && isset($form['categories_checked_list']))
			$this->db->set('checked_categories', serialize($form['categories_checked_list']));
		else 
			$this->db->set('checked_categories', '');
		$this->db->where('id', $this->_banner_id);
		return $this->db->update('banners');
	}
	
	public function deleteBanners($banners_array)
    {
    	if (count($banners_array)) {
	    	foreach ($banners_array AS $id=>$val) {
	    		$this->setBannerId($id);
		    	$this->deleteBannerById($id);
	    	}
	    	return true;
    	} else 
    		return false;
    }
	
	public function deleteBannerById()
	{
		return $this->db->delete('banners', array('id' => $this->_banner_id));
	}
	
	public function blockBanners($banners_ids)
    {
	    $this->db->set('status', '2');
	    $this->db->where_in('id', $banners_ids);
	    return $this->db->update('banners');
    }
    
    public function activateBanners($banners_ids)
    {
	    $this->db->set('status', '1');
	    $this->db->where_in('id', $banners_ids);
	    return $this->db->update('banners');
    }
    
    /**
     * Saves banner status
     *
     * @param int $status
     * @return bool
     */
    public function saveBannerStatus($status)
    {
    	$this->db->set('status', $status);
    	$this->db->where('id', $this->_banner_id);
    	return $this->db->update('banners');
    }
    
    public function prolongBanner($banner)
    {
    	$this->db->set('status', 1); // active
    	$this->db->set('expiration_date', date("Y-m-d H:i:s", (mktime() + (
    											($banner->block->active_days) +
    											($banner->block->active_months*30) +
    											($banner->block->active_years*365)
    											)*86400)));
    	$this->db->set('clicks_expiration_count', 'clicks_expiration_count+' . $banner->block->clicks_limit, false);
    	$this->db->set('was_prolonged_times', 'was_prolonged_times+1', false);
    	$this->db->where('id', $this->_banner_id);
    	return $this->db->update('banners');
    }
    
    public function suspendExpiredActiveBanners()
    {
    	$this->db->select('b.*');
    	$this->db->from('banners AS b');
    	$this->db->join('banners_blocks AS bb', 'bb.id=b.block_id', 'left');
    	$this->db->where('b.expiration_date <=', 'NOW()', false);
    	$this->db->where('b.status', 1); // active banners
    	// suspend banners only with active period limitation or both with active period and clicks limitation
    	$this->db->where("(bb.limit_mode = 'active_period' OR bb.limit_mode = 'both')");
    	$array = $this->db->get()->result_array();

    	$ids = array();
    	$banners = array();
    	foreach ($array AS $row) {
    		$ids[] = $row['id'];

    		$banner = new banner($row['block_id']);
    		$banner->setBannerFromArray($row);
    		$banner->buildObject();
    		$banners[] = $banner;
    	}
    	foreach ($banners AS $banner) {
    		$event_params = array(
				'BANNER_ID' => $banner->id, 
				'BANNER_URL' => $banner->url, 
				'RECIPIENT_NAME' => $banner->user->login,
				'RECIPIENT_EMAIL' => $banner->user->email
			);
    		$notification = new notificationSender('Banner expiration');
			$notification->send($event_params);
    		events::callEvent('Banner expiration', $event_params);
    	}
    	
    	if (!empty($ids)) {
	    	$this->db->set('status', 3);
	    	$this->db->where_in('id', $ids);
	    	return $this->db->update('banners');
    	}
    }
    
    public function getMyBannersCount()
    {
    	$this->db->select('count(*) AS banners_count');
    	$this->db->from('banners');
    	$this->db->where('owner_id', $this->session->userdata('user_id'));
    	$row = $this->db->get()->row_array();
    	
    	return $row['banners_count'];
    }
    
    public function getActiveBannersOfBlock($block_id)
    {
    	$this->db->select('b.*');
    	$this->db->from('banners AS b');
    	$this->db->join('banners_blocks AS bb', 'bb.id=b.block_id', 'left');
    	$this->db->join('users AS u', 'u.id=b.owner_id', 'left');
    	$this->db->where('bb.id', $block_id);
    	$this->db->where('b.status', 1); // active banners
    	$this->db->where('u.status', 2); // active users
    	$array = $this->db->get()->result_array();

    	$ids = array();
    	$banners = array();
    	foreach ($array AS $row) {
    		$banner = new banner($row['block_id']);
    		$banner->setBannerFromArray($row);
    		$banner->buildObject();
    		
    		// Is banner allowed in current location and in current category?
    		$current_location = registry::get('current_location');
    		$current_category = registry::get('current_category');
    		if ($current_location || $current_category) {
    			if ((!$current_location || ($current_location && in_array($current_location->id, $banner->getCheckedLocations()))) && (!$current_category || ($current_category && in_array($current_category->id, $banner->getCheckedCategories()))))
    				$banners[] = $banner;
    		} else 
    			$banners[] = $banner;
    	}
    	return $banners;
    }
    
    public function chooseBannerToView($banners)
    {
    	foreach ($banners AS $count=>$banner) {
    		if ($banner->queue == 1 || $count == (count($banners)-1)) {
    			if (count($banners) > 1) {
	    			if ($count == (count($banners)-1)) {
	    				$this->putInQueue($banners[0]->id);
	    			} else {
	    				$this->putInQueue($banners[$count+1]->id);
	    			}
    			}
    			$this->takeOffQueue($banner->id);

    			return $banner;
    			break;
    		}
    	}
    }
    
    public function putInQueue($banner_id)
    {
    	$this->db->set('queue', 1);
    	$this->db->where('id', $banner_id);
    	return $this->db->update('banners');
    }
    
    public function takeOffQueue($banner_id)
    {
    	$this->db->set('queue', 0);
    	$this->db->where('id', $banner_id);
    	return $this->db->update('banners');
    }
    
    public function incrementView($banner_id)
    {
    	$this->db->set('views', 'views+1', false);
    	$this->db->where('id', $banner_id);
    	$this->db->update('banners');
    }
    
    public function incrementBannerClick()
    {
    	// increment banner clicks field
    	$this->db->select('id');
    	$this->db->where('banner_id', $this->_banner_id);
    	$this->db->where('ip', $this->input->ip_address());
    	$this->db->from('banners_clicks_tracing');
    	$query = $this->db->get();
    	if (!$query->result_array()) {
    		$this->db->set('clicks_count', 'clicks_count+1', false);
    		$this->db->set('clicks_expiration_count', 'clicks_expiration_count-1', false);
    		$this->db->where('id', $this->_banner_id);
    		$this->db->update('banners');
    	}
    	
    	// save ip address of user, that clicked
    	$this->db->set('banner_id', $this->_banner_id);
    	$this->db->set('ip', $this->input->ip_address());
    	$this->db->on_duplicate_insert('banners_clicks_tracing');
    	
    	$banner = $this->getBannerById($this->_banner_id);

    	// check if clicks limit equal or lower than clicks count of current banner
    	// only with active period limitation or both with active period and clicks limitation
    	if ($banner->clicks_expiration_count <= $banner->clicks_count && ($banner->block->limit_mode == 'clicks' || $banner->block->limit_mode == 'both')) {
    		// suspend banner
    		$this->saveBannerStatus(3);

    		$event_params = array(
				'BANNER_ID' => $banner->id,
				'BANNER_URL' => $banner->url,
				'RECIPIENT_NAME' => $banner->user->login,
				'RECIPIENT_EMAIL' => $banner->user->email
			);
    		$notification = new notificationSender('Banner expiration');
			$notification->send($event_params);
    		events::callEvent('Banner expiration', $event_params);
    	}
    }

    public function getBannersSummary()
	{
		$this->db->select('status');
		$this->db->select('count(if(status=1, 1, null)) AS active_status');
		$this->db->select('count(if(status=2, 1, null)) AS blocked_status');
		$this->db->select('count(if(status=3, 1, null)) AS suspended_status');
		$this->db->select('count(if(status=4, 1, null)) AS not_paid_status');
		$this->db->from('banners');
		$this->db->group_by('status');
		$query = $this->db->get();
		
		return $query->result_array();
	}
	
	public function getMyBannersSummary()
	{
		$this->db->select('status');
		$this->db->select('count(if(status=1, 1, null)) AS active_status');
		$this->db->select('count(if(status=2, 1, null)) AS blocked_status');
		$this->db->select('count(if(status=3, 1, null)) AS suspended_status');
		$this->db->select('count(if(status=4, 1, null)) AS not_paid_status');
		$this->db->from('banners');
		$this->db->group_by('status');
		$this->db->where('owner_id', $this->session->userdata('user_id'));
		$query = $this->db->get();
		
		return $query->result_array();
	}
}
?>