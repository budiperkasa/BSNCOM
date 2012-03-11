<?php
include_once(MODULES_PATH . 'content_fields/classes/field.class.php');
include_once(MODULES_PATH . 'content_fields/classes/content_fields.class.php');
include_once(MODULES_PATH . 'content_fields/classes/search_content_fields.class.php');

class listing
{
    public $id;
    public $level_id;
    public $owner_id;
    public $title;
    public $seo_title;
    public $listing_description;
    public $listing_meta_description;
    public $listing_keywords;
    public $logo_file;
    public $status;
    public $creation_date;
    public $expiration_date;
    public $last_modified_date;
    public $was_prolonged_times;
    public $claim_row;
    public $claim_user;
    
    /**
     * levelClass object
     *
     */
    public $level = null;
    /**
     * typeClass object
     *
     */
    public $type = null;
    /**
     * package object, where listing was created
     *
     */
    public $package = null;
    
    public $user;

    public $content_fields;
    
    private $executed_title;
    private $categories_array = array();
    private $locations_array = array();
    
    public $ratings = array();
    public $avg_rating = null;
    
    public $reviews_count = 0;
    //public $reviews_block;
    
    public $images;
    public $videos;
    public $files;
    
    private $_fully_load = true;
    
    /**
     * listing object may be not fully loaded (content fields objects/user object/package object/claim row will not be loaded)
     */
    public function __construct($level_id, $listing_id = null, $page = null, $fully_load = true)
    {
    	$this->_fully_load = $fully_load;
    	
		if (empty($listing_id)) {
			$this->id = 'new';
		} else {
			$this->id = $listing_id;
		}

        $this->level_id = $level_id;
        $this->owner_id = '';
        $this->title = '';
        $this->seo_title = '';
        $this->listing_description = '';
        $this->listing_meta_description = '';
    	$this->listing_keywords = '';
        $this->logo_file = '';
        // 1: active
        // 2: blocked
        // 3: suspended
        // 4: unapproved
        // 5: not paid
        $this->status = 1;
        $this->creation_date = '';
        $this->expiration_date = '';
        $this->last_modified_date = '';
        $this->was_prolonged_times = 0;
        $this->ability_to_claim = 0;
        
        // --------------------------------------------------------------------------------------------
        // Default location, especially for new listing
        // --------------------------------------------------------------------------------------------
        $location = new listingLocation;
		$this->locations_array = array($location);
		// --------------------------------------------------------------------------------------------
		
		// --------------------------------------------------------------------------------------------
		// Retrieve level and type objects
		// --------------------------------------------------------------------------------------------
		$CI = &get_instance();
		$CI->load->model('levels', 'types_levels');
		$CI->load->model('types', 'types_levels');
		$CI->levels->setLevelId($level_id);
		$this->level = $CI->levels->getLevelById();
		$CI->types->setTypeId($this->level->type_id);
		$this->type = $CI->types->getTypeById();
		// --------------------------------------------------------------------------------------------
		
		// --------------------------------------------------------------------------------------------
		// --------------------------------------------------------------------------------------------
		if ($this->_fully_load) {
			if ($listing_id && $CI->load->is_module_loaded('packages')) {
				$CI->load->model('packages', 'packages');
				$this->package = $CI->packages->isListingInPackage($this->id);
			}
		}
		// --------------------------------------------------------------------------------------------

		// --------------------------------------------------------------------------------------------
		// Additional content fields of listing
		if ($this->_fully_load) {
    		$this->content_fields = new contentFields(LISTINGS_LEVEL_GROUP_CUSTOM_NAME, $level_id, $listing_id, $page);
		}
    	// --------------------------------------------------------------------------------------------
    	
    	if (!$this->level->eternal_active_period) {
    		$this->expiration_date = date("Y-m-d H:i:s", mktime() + (
	    											($this->level->active_days) +
	    											($this->level->active_months*30) +
	    											($this->level->active_years*365)
	    											)*86400);
    	}
    }

    public function setListingFromArray($listing_array, $categories_array = array(), $locations_array = array())
    {
    	$this->id                  = $listing_array['id'];
        $this->level_id            = $listing_array['level_id'];
        $this->owner_id            = $listing_array['owner_id'];
        if ($this->level->title_enabled)
        	$this->title               = $listing_array['title'];
        if ($this->level->seo_title_enabled)
        	$this->seo_title           = $listing_array['seo_title'];
        if ($this->level->description_mode != 'disabled') {
        	$this->listing_description = $listing_array['listing_description'];
        }
        if ($this->level->meta_enabled) {
	    	$this->listing_meta_description    = $listing_array['listing_meta_description'];
	    	$this->listing_keywords    = $listing_array['listing_keywords'];
        }
        $this->logo_file           = $listing_array['logo_file'];
        $this->status              = $listing_array['status'];
        
        $this->creation_date = $listing_array['creation_date'];
        $this->expiration_date = $listing_array['expiration_date'];
        $this->last_modified_date = $listing_array['last_modified_date'];
        $this->was_prolonged_times = $listing_array['was_prolonged_times'];

		$this->categories_array = $categories_array;
		
		// Here we will give the ability to enter first location after listing was created
		// this location is initially empty
		if ($locations_array)
			$this->locations_array = $locations_array;

		if ($this->_fully_load) {
        	$this->content_fields->select();
		}
        
        // --------------------------------------------------------------------------------------------
		// Retrieve user object
		// --------------------------------------------------------------------------------------------
		$CI = &get_instance();
		if ($this->_fully_load) {
			$CI->load->model('users', 'users');
			$this->user = $CI->users->getUserById($this->owner_id);
		}
		// --------------------------------------------------------------------------------------------
		
		// --------------------------------------------------------------------------------------------
		// Is listing may be claimed
		// --------------------------------------------------------------------------------------------
		if ($this->_fully_load) {
			if ($this->claim_row = $CI->listings->getClaimRow($this->id)) { 
				if ($this->claim_row['to_user_id']) {
					$CI->load->model('users', 'users');
					$this->claim_user = $CI->users->getUserById($this->claim_row['to_user_id']);
					if ($this->claim_row['ability_to_claim'])
						$this->ability_to_claim = 1;
				}
			}
		}
		// --------------------------------------------------------------------------------------------
    }
    
    public function getListingFromForm($form)
	{
		$system_settings = registry::get('system_settings');
		
		if (isset($form['id']))
			$this->id = $form['id'];
		if ($this->level->title_enabled)
			$this->title = $form['name'];
		if ($this->level->seo_title_enabled)
			$this->seo_title = $form['seo_name'];
		if ($this->level->description_mode != 'disabled') {
        	$this->listing_description = $form['listing_description'];
        }
		if ($this->level->meta_enabled && (isset($form['listing_meta_description']) || isset($form['listing_keywords']))) {
    		$this->listing_meta_description = $form['listing_meta_description'];
    		$this->listing_keywords = str_replace(", ", "\n", $form['listing_keywords']);
		}
		if (isset($form['ability_to_claim']))
			if ($this->claim_row)
				$this->claim_row = array_merge($this->claim_row, array('ability_to_claim' => $form['ability_to_claim']));
		if ($this->type->locations_enabled && $this->level->locations_number && isset($form['location_id[]'])) {
			$CI = &get_instance();
			$CI->load->model('locations', 'locations_predefined');
			$locations_levels = $CI->locations->selectAllLevels();

			$locations = array();
			foreach ($form['location_id[]'] AS $key=>$location_item) {
				$location = new listingLocation;

				$use_predefined_locations = false;
				$lastlevel_loc_id = 0;
				if ($system_settings['predefined_locations_mode'] != 'disabled') {
					// Save predefined location
					foreach ($locations_levels AS $locations_level)
						if ($form['loc_level_' . $locations_level->order_num . '[]'][$key])
							$lastlevel_loc_id = $form['loc_level_' . $locations_level->order_num . '[]'][$key];
					if ($system_settings['predefined_locations_mode'] == 'only' || (is_array($form['use_predefined_locations']) && in_array($form['location_id[]'][$key], $form['use_predefined_locations'])))
						$use_predefined_locations = true;
				}
				$location_string = null;
				if ($system_settings['predefined_locations_mode'] != 'only') {
					// Save location string
					$location_string = $form['location[]'][$key];
				}

				// Is there maps enabled
				if ($this->level->maps_enabled) {
					if (is_array($form['manual_coords']) && in_array($form['location_id[]'][$key], $form['manual_coords']))
						$manual = 1;
					else 
						$manual = 0;

		    		$location_array = array(
		    			'id' => $form['location_id[]'][$key],
		    			'listing_id' => $this->id,
		    			'location' => $location_string,
		    			'geocoded_name' => $form['geocoded_name[]'][$key],
		    			'predefined_location_id' => $lastlevel_loc_id,
		    			'use_predefined_locations' => $use_predefined_locations,
		    			'address_line_1' => $form['address_line_1[]'][$key],
		    			'address_line_2' => $form['address_line_2[]'][$key],
		    			'manual_coords' => $manual,
		    			'map_coords_1' => $form['map_coords_1[]'][$key],
		    			'map_coords_2' => $form['map_coords_2[]'][$key],
		    			'map_zoom' => $form['map_zoom[]'][$key],
		    			'map_icon_id' => $form['map_icon_id[]'][$key],
		    			'map_icon_file' => $form['map_icon_file[]'][$key],
		    			'virtual_id' => $form['location_id[]'][$key]
					);
				} else {
					$location_array = array(
						'id' => $form['location_id[]'][$key],
		    			'listing_id' => $this->id,
		    			'location' => $location_string,
						'geocoded_name' => $form['geocoded_name[]'][$key],
		    			'predefined_location_id' => $lastlevel_loc_id,
		    			'use_predefined_locations' => $use_predefined_locations,
		    			'address_line_1' => $form['address_line_1[]'][$key],
		    			'address_line_2' => $form['address_line_2[]'][$key],
					);
				}
				if ($this->type->zip_enabled) {
					$location_array['zip_or_postal_index'] = $form['zip_or_postal_index[]'][$key];
				}
				$location->setLocationFromArray($location_array);
	    		$locations[] = $location;
			}
			$this->locations_array = $locations;
		}
		if ($this->level->logo_enabled) {
			$this->logo_file = $form['listing_logo_image'];
		}

		if ($this->level->categories_number) {
			if ($form['serialized_categories_list']) {
				$categories_list = array_unique(unserialize($form['serialized_categories_list']));
				$this->categories_array = array();
				$CI = &get_instance();
				$CI->load->model('categories', 'categories');
				foreach ($categories_list AS $cat_id)
					$this->categories_array[] = $CI->categories->getCategoryById($cat_id);
			} else 
				$this->categories_array = array();
		}

		if (isset($form['expiration_date']))
			$this->expiration_date = date("Y-m-d H:i:s", strtotime($form['expiration_date']));
	}

    public function inputMode()
    {
    	if ($this->_fully_load)
    		return $this->content_fields->inputMode();
    }
    
    public function outputMode($view)
    {
    	if ($this->_fully_load)
    		return $this->content_fields->outputMode($view);
    }
    
    public function validateFields($form)
    {
    	if ($this->_fully_load)
    		$this->content_fields->validate($form);
    }
    
    public function saveFields($listing_id, $form)
	{
		if ($this->_fully_load) {
			$this->content_fields->setObjectId($listing_id);
			return $this->content_fields->save($form);
		}
	}
	
	public function updateFields($form)
	{
		if ($this->_fully_load) {
			$this->content_fields->select();
			return $this->content_fields->update($form);
		}
	}
	
	public function deleteFields()
	{
		if ($this->_fully_load) {
			$this->content_fields->select();
			return $this->content_fields->delete();
		}
	}

	/**
	 * Place richtext editor with content or pure text if richtext option disabled
	 *
	 * @return string/html
	 */
	public function description()
	{
		if ($this->level->description_mode == 'richtext') {
			include_once(BASEPATH . 'richtext_editor/richtextEditor.php');
			$Editor = new richtextEditor('listing_description', $this->listing_description);
			$Editor->Width = 750;
			$Editor->Height = 400;
			return $Editor->CreateHtml();
        } else {
        	return $this->listing_description;
        }
	}
	
	public function listing_description_teaser()
	{
		if ($break_pos = strpos($this->listing_description, '<!--break-->')) {
			return substr($this->listing_description, 0, $break_pos);
		} else 
			return $this->listing_description;
	}

	// --------------------------------------------------------------------------------------------
	/**
	 * build custom title of listing
	 *
	 * @return string
	 */
	public function title($template = null)
	{
		if (!$template)
			$template= $this->level->titles_template;
		if (!$template)
			return $this->title;
		
		if ($this->executed_title)
			return $this->executed_title;

		$tokens = array(
			'LISTING_ID' => '_getListingId',
			'CUSTOM_TITLE' => '_getCustomTitle',
			'USER_LOGIN' => '_getUserLogin',
			'USER_EMAIL' => '_getUserEmail',
			'FIELD_([^\%]+)' => '_getFieldValue',
			'CATEGORY_(\d+)' => '_getCategory',
			'LOCATION_(\d+)' => '_getLocation',
			'CATEGORIES\(([^\)]+)\)' => '_getAllCategories',
			'LOCATIONS\(([^\)]+)\)' => '_getAllLocations',
		);

		foreach ($tokens AS $pattern=>$function_name) {
	    	$template = preg_replace_callback("/%".$pattern."%/", array($this, $function_name), $template);
		}

		$this->executed_title = $template;
		return $this->executed_title;
	}
	
	public function _getListingId()
	{
		return $this->id;
	}
	
	public function _getCustomTitle()
	{
		return $this->title;
	}
	
	public function _getUserLogin()
	{
		if ($this->_fully_load)
			return $this->user->login;
	}
	
	public function _getUserEmail()
	{
		if ($this->_fully_load)
			return $this->user->email;
	}
	
	public function _getFieldValue($field_seo_name)
	{
		if ($this->_fully_load && $field_seo_name[1]) {
			foreach ($this->content_fields->getFieldsObjects() AS $field_obj) {
				if (strtolower($field_obj->field->seo_name) == strtolower($field_seo_name[1]))
					return $field_obj->getValueAsString();
			}
		}
	}
	
	public function _getCategory($cat_index)
	{
		if (is_numeric($cat_index[1]))
			foreach ($this->categories_array() AS $key=>$category) {
				if ($key+1 == $cat_index[1])
					return $category->name;
			}
	}
	
	public function _getLocation($loc_index)
	{
		if (is_numeric($loc_index[1]))
			foreach ($this->locations_array() AS $key=>$location) {
				if ($key+1 == $loc_index[1])
					return $location->location();
			}
	}
	
	public function _getAllCategories($glue)
	{
		$categories = array();
		foreach ($this->categories_array() AS $category) {
			$categories[] = $category->name;
		}
		return implode($glue[1], $categories);
	}
	
	public function _getAllLocations($glue)
	{
		$locations = array();
		foreach ($this->locations_array() AS $location) {
			$locations[] = $location->location();
		}
		return implode($glue[1], $locations);
	}
	// --------------------------------------------------------------------------------------------
	
	public function getAssignedImages()
	{
		if ($this->level->images_count && is_null($this->images)) {
			$CI = &get_instance();
			$CI->load->model('images', 'listings');
			$CI->images->setListingId($this->id);
			$this->images = $CI->images->selectImagesByListingId();
			return $this->images;
		} else {
			return $this->images;
		}
	}
	public function getAssignedVideos()
	{
		if ($this->level->video_count && is_null($this->videos)) {
			$CI = &get_instance();
			$CI->load->model('videos', 'listings');
			$CI->videos->setListingId($this->id);
			$this->videos = $CI->videos->selectActiveVideosByListingId();
			return $this->videos;
		} else {
			return $this->videos;
		}
	}
	public function getAssignedFiles()
	{
		if ($this->level->files_count && is_null($this->files)) {
			$CI = &get_instance();
			$CI->load->model('files', 'listings');
			$CI->files->setListingId($this->id);
			$this->files = $CI->files->selectFilesByListingId();
			return $this->files;
		} else {
			return $this->files;
		}
	}
	
	public function url()
	{
		$id = $this->getUniqueId();
		return "listings/" . $id;
	}
	
	public function categories_array()
	{
		return $this->categories_array;
	}
	
	public function locations_count($is_only_on_map = false)
	{
		$locations = array();
		foreach ($this->locations_array AS $location) {
			// Exclude empty locations
			if ($location->id == 'new')
				continue;

			// Exclude locations without generated markers on map
			if ($is_only_on_map && $location->map_coords_1 == 0 && $location->map_coords_2 == 0) {
				continue;
			}

			$locations[] = $location;
		}
		return count($locations);
	}
	
	public function locations_array()
	{
		return $this->locations_array;
	}
	
	public function getRatings()
	{
		if (is_null($this->avg_rating)) {
	    	$CI = &get_instance();
			$CI->load->model('ratings', 'ratings_reviews');
	    	$this->ratings = $CI->ratings->getRatings('listings', $this->id);
			$this->avg_rating = $CI->ratings->buildAverageRating($this->ratings, 'listings', $this->id);
		}
    	return $this->avg_rating;
	}
	
	public function getReviewsCount()
	{
		if ($this->reviews_count == 0) {
	    	if ($this->level->reviews_mode && $this->level->reviews_mode != 'disabled') {
	    		$CI = &get_instance();
				$CI->load->model('reviews', 'ratings_reviews');
	    		$this->reviews_count = $CI->reviews->getReviewsCount('listings', $this->id);
	    	}
		}
    	return $this->reviews_count;
	}

	public function getUniqueId()
	{
		if ($this->level->seo_title_enabled && $this->seo_title) {
			return $this->seo_title;
		} else {
			return $this->id;
		}
	}

	public function view($view_name)
	{
		$template = 'frontend/listings/listing_' . $view_name . '.tpl';
		
		$CI = &get_instance();
		$view = $CI->load->view();
		$view->assign('listing', $this);
		return $view->fetch($template);
	}
}
?>