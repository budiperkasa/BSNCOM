<?php
class banner
{
    public $id;
    public $block_id;
    public $block;
    public $owner_id;
    public $user;
    public $url;
    public $banner_file;
    public $remote_image_url;
    public $use_remote_image;
    public $is_uploaded_flash;
    public $is_loaded_flash;
    public $status;
    public $creation_date;
    public $expiration_date;
    public $was_prolonged_times;
    public $views;
    public $clicks_count;
    public $clicks_expiration_count;
    public $queue;
    public $checked_locations;
    public $checked_categories;

    public function __construct($block_id)
    {
        $this->id = 'new';
        $this->block_id = $block_id;
        $this->owner_id = 0;
        $this->url = '';
        $this->banner_file = '';
        $this->remote_image_url = '';
        $this->use_remote_image = 0;
        $this->is_uploaded_flash = 0;
        $this->is_loaded_flash = 0;
        $this->status = 1;
        $this->creation_date = '';
        $this->expiration_date = '';
        $this->was_prolonged_times = 0;
        $this->views = 0;
        $this->clicks_count = 0;
        $this->clicks_expiration_count = 0;
        $this->queue = 0;
        $this->checked_locations = array();
        $this->checked_categories = array();
        
        $CI = &get_instance();
    	$CI->load->model('banners_blocks', 'banners');
    	$this->block = $CI->banners_blocks->getBannersBlockById($block_id);
    }

    public function setBannerFromArray($array)
    {
    	if (isset($array['id']))
        	$this->id                   = $array['id'];
		if (isset($array['id']))
			$this->owner_id             = $array['owner_id'];
        $this->url                      = prep_url($array['url']);
        $this->banner_file              = $array['banner_file'];
        if (isset($array['remote_image_url'])) {
        	$this->use_remote_image     = $array['use_remote_image'];
        	$this->remote_image_url     = $array['remote_image_url'];
        	$this->is_loaded_flash      = $array['is_loaded_flash'];
        }
        if (isset($array['is_uploaded_flash']))
        	$this->is_uploaded_flash    = $array['is_uploaded_flash'];
        if (isset($array['status']))
			$this->status               = $array['status'];
        if (isset($array['creation_date']))
			$this->creation_date        = $array['creation_date'];
		if (isset($array['expiration_date']))
			$this->expiration_date      = $array['expiration_date'];
		if (isset($array['was_prolonged_times']))
			$this->was_prolonged_times  = $array['was_prolonged_times'];
		if (isset($array['views']))
			$this->views                = $array['views'];
		if (isset($array['clicks_count']))
			$this->clicks_count         = $array['clicks_count'];
		if (isset($array['clicks_expiration_count']))
			$this->clicks_expiration_count = $array['clicks_expiration_count'];
		if (isset($array['queue']))
			$this->queue                = $array['queue'];
		if (isset($array['checked_locations']))
			$this->checked_locations    = $array['checked_locations'];
		if (isset($array['checked_categories']))
			$this->checked_categories   = $array['checked_categories'];
    }
    
    public function buildObject()
    {
    	$CI = &get_instance();

    	$CI->load->model('users', 'users');
    	$this->user = $CI->users->getUserById($this->owner_id);
    	
    	$CI->load->model('banners_blocks', 'banners');
    	$this->block = $CI->banners_blocks->getBannersBlockById($this->block_id);
    }
    
    /*public function adminDisplayUploaded()
    {
    	$CI = &get_instance();
		$users_content = $CI->config->item('users_content_http_path');

		if (!$this->is_uploaded_flash) {
	    	return '<img id="img" src="' . $users_content . 'banners/' . $this->banner_file . '" />';
		} else {
			return '<script language="javascript" type="text/javascript">
    			swfobject.embedSWF("' . $users_content . 'banners/' . $this->banner_file . '", "img_div_border", "' . $this->block->explodeSize('block_size', 0) . '", "' . $this->block->explodeSize('block_size', 1) . '", "9.0.0");
    		</script>';
		}
    }
    
    public function adminDisplayLoaded()
    {
    	if (!$this->is_loaded_flash) {
    		return '<img id="remote_image_' . $this->id . '" src="' . $this->remote_image_url . '"/>';
    	} else {
    		return '<script language="javascript" type="text/javascript">
    			swfobject.embedSWF("' . $this->remote_image_url . '", "remote_image_wrapper_' . $this->id . '", "' . $this->block->explodeSize('block_size', 0) . '", "' . $this->block->explodeSize('block_size', 1) . '", "9.0.0");
    		</script>';
    	}
    }*/
    
    public function display()
    {
    	if ($this->use_remote_image) {
    		
    	}
    	if (!$this->is_loaded_flash) {
    		return '<img id="remote_image_' . $this->id . '" src="' . $this->remote_image_url . '"/>';
    	} else {
    		return '<script language="javascript" type="text/javascript">
    			swfobject.embedSWF("' . $this->remote_image_url . '", "remote_image_wrapper_' . $this->id . '", "' . $this->block->explodeSize('block_size', 0) . '", "' . $this->block->explodeSize('block_size', 1) . '", "9.0.0");
    		</script>';
    	}
    }
    
    public function isAllLocationsChecked()
    {
    	if (empty($this->checked_locations))
    		return true;
    	
    }
    
    public function getCheckedLocations()
    {
    	$labeled_locations = array();
    	
    	if (empty($this->checked_locations)) {
	    	$CI = &get_instance();
	    	$CI->load->model('locations', 'locations_predefined');
	    	$locations = $CI->locations->selectLocationsFromDB(false, true);
	    	foreach ($locations AS $location) {
	    		$labeled_locations[] = $location['id'];
	    	}
    	} else {
    		$labeled_locations = unserialize($this->checked_locations);
    	}

    	return $labeled_locations;
    }
    
	public function isAllCategoriesChecked()
    {
    	if (empty($this->checked_categories))
    		return true;
    	
    }
    
    public function getCheckedCategories()
    {
    	$labeled_categories = array();

    	if (empty($this->checked_categories)) {
	    	$CI = &get_instance();
	    	$CI->db->select('id');
	    	$CI->db->from('categories');
	    	$result = $CI->db->get()->result_array();
	    	foreach ($result AS $category) {
	    		$labeled_categories[] = $category['id'];
	    	}
    	} else {
    		$labeled_categories = unserialize($this->checked_categories);
    	}

    	return $labeled_categories;
    }
}
?>