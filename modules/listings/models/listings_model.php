<?php

include_once(MODULES_PATH . 'types_levels/classes/level.class.php');
include_once(MODULES_PATH . 'types_levels/classes/type.class.php');
include_once(MODULES_PATH . 'locations_predefined/classes/predefined_location.class.php');
include_once(MODULES_PATH . 'listings/classes/listing.class.php');
include_once(MODULES_PATH . 'listings/classes/listing_image.class.php');
include_once(MODULES_PATH . 'listings/classes/listing_file.class.php');
include_once(MODULES_PATH . 'listings/classes/listing_location.class.php');
include_once(MODULES_PATH . 'google_maps/classes/location_geoname.class.php');
include_once(MODULES_PATH . 'listings/classes/search_core.class.php');
include_once(MODULES_PATH . 'notifications/classes/notification_sender.class.php');
include_once(MODULES_PATH . 'categories/classes/category.class.php');

class listingsModel extends model {

  private $_listing_id;

  public function setListingId($listing_id) {
	$this->_listing_id = $listing_id;
  }

  /**
   * get listing row by seo name or ID
   *
   * @param string/integer $url_part
   * @return array
   */
  public function getListingRowByUrl($url_part) {
	$this->db->select();
	$this->db->from('listings');
	if (is_numeric($url_part))
	  $this->db->where('id', $url_part);
	else
	  $this->db->where('seo_title', $url_part);
	return $this->db->get()->row_array();
  }

  public function getLevelIdByListingId($listing_id = null) {
	if (is_null($listing_id))
	  $listing_id = $this->_listing_id;

	$this->db->select('level_id');
	$this->db->from('listings');
	$this->db->where('id', $listing_id);
	$query = $this->db->get();

	if ($query->num_rows) {
	  $row = $query->row_array();
	  return $row['level_id'];
	} else
	  return false;
  }

  /**
   * is there listing with such seoname in the DB?
   *
   * @param string $seoname
   */
  public function is_listing_seo_name($seoname) {
	$this->db->select();
	$this->db->from('listings');
	$this->db->where('seo_title', $seoname);
	if (!is_null($this->_listing_id)) {
	  $this->db->where('id !=', $this->_listing_id);
	}
	$query = $this->db->get();

	return $query->num_rows();
  }

  public function saveListing($form, $level, $type, $user_package_id = null) {
	if ($level) {
	  $this->db->set('level_id', $level->id);
	  $this->db->set('owner_id', $this->session->userdata('user_id'));

	  if ($level->title_enabled)
		$this->db->set('title', $form['name']);
	  if ($level->seo_title_enabled)
		$this->db->set('seo_title', $form['seo_name']);
	  if ($level->description_mode != 'disabled')
		$this->db->set('listing_description', $form['listing_description']);
	  if ($level->meta_enabled) {
		$this->db->set('listing_meta_description', $form['listing_meta_description']);
		$this->db->set('listing_keywords', str_replace("\n", ", ", $form['listing_keywords']));
	  }
	  if (isset($form['listing_logo_image']))
		$this->db->set('logo_file', $form['listing_logo_image']);
	  $this->db->set('last_modified_date', date("Y-m-d H:i:s"));

	  if ($level->preapproved_mode)
		$this->db->set('status', 4); // unapproved
	  else
		$this->db->set('status', 1); // active

	  $this->db->set('creation_date', date("Y-m-d H:i:s"));
	  $this->db->set('last_modified_date', date("Y-m-d H:i:s"));
	  // Set custom expiration date if user have permission and listing not under eternal active period
	  $content_access_obj = contentAcl::getInstance();
	  if ($content_access_obj->isPermission('Edit listings expiration date') && !$level->eternal_active_period) {
		$this->db->set('expiration_date', date("Y-m-d H:i:s", strtotime($form['expiration_date'])));
	  } else {
		$this->db->set('expiration_date', date("Y-m-d H:i:s", (mktime() + (
						($level->active_days) +
						($level->active_months * 30) +
						($level->active_years * 365)
						) * 86400)));
	  }

	  $this->db->set('was_prolonged_times', '0');

	  if ($type->categories_type != 'disabled' && $level->categories_number) {
		$categories_array = unserialize($form['serialized_categories_list']);
		$categories_array = array_filter($categories_array);
	  }

	  if (!$type->locations_enabled || ($type->locations_enabled && count($form['location_id[]']) <= $level->locations_number)
			  && ($type->categories_type == 'disabled' || count($categories_array) <= $level->categories_number)) {
		if ($this->db->insert('listings')) {
		  $listing_id = $this->db->insert_id();

		  $system_settings = registry::get('system_settings');
		  if (isset($system_settings['language_areas_enabled']) && $system_settings['language_areas_enabled']) {
			translations::saveTranslations(array('listings', 'title', $listing_id));
			translations::saveTranslations(array('listings', 'listing_description', $listing_id));
		  }

		  if (!is_null($user_package_id)) {
			$this->db->set('user_package_id', $user_package_id);
			$this->db->set('listing_id', $listing_id);
			$this->db->insert('packages_listings');
		  }

		  // --------------------------------------------------------------------------------------------
		  // Does this listing can be claimed?
		  // --------------------------------------------------------------------------------------------
		  $content_access_obj = contentAcl::getInstance();
		  if ($content_access_obj->isPermission('Manage ability to claim')) {
			if ($form['ability_to_claim']) {
			  $this->db->set('listing_id', $listing_id);
			  $this->db->set('ability_to_claim', 1);
			  $this->db->set('from_user_id', $this->session->userdata('user_id'));
			  $this->db->insert('listings_claims');
			}
		  }
		  // --------------------------------------------------------------------------------------------

		  if ($type->locations_enabled && $level->locations_number) {
			$CI = &get_instance();
			$CI->load->model('locations', 'locations_predefined');
			$locations_levels = $CI->locations->selectAllLevels();

			foreach ($form['location_id[]'] AS $key => $virtual_location_id) {
			  // Insert new location

			  $use_predefined_locations = 0;
			  if ($system_settings['predefined_locations_mode'] != 'disabled') {
				// Save predefined location
				$lastlevel_loc_id = 0;
				foreach ($locations_levels AS $locations_level)
				  if ($form['loc_level_' . $locations_level->order_num . '[]'][$key])
					$lastlevel_loc_id = $form['loc_level_' . $locations_level->order_num . '[]'][$key];
				if ($lastlevel_loc_id) {
				  $this->db->set('predefined_location_id', $lastlevel_loc_id);

				  if ($system_settings['predefined_locations_mode'] == 'only' || (is_array($form['use_predefined_locations']) && in_array($form['location_id[]'][$key], $form['use_predefined_locations']))) {
					$use_predefined_locations = 1;

					$location = $CI->locations->getLocationById($lastlevel_loc_id);
					$this->db->set('geocoded_name', $location->geocoded_name);

					/* if (!($geocoded_name = $form['geocoded_name[]'][$key])) {
					  $location = $CI->locations->getLocationById($lastlevel_loc_id);
					  $geocoder = new locationGeoname;
					  if ($geocoded_name = $geocoder->geonames_request($location->getChainAsString()))
					  $this->db->set('geocoded_name', $geocoded_name);
					  } else
					  $this->db->set('geocoded_name', $geocoded_name); */
				  }
				  $this->db->set('use_predefined_locations', $use_predefined_locations);
				}
			  }

			  if ($system_settings['predefined_locations_mode'] != 'only') {
				// Save location string
				if (trim($form['location[]'][$key]) && !$use_predefined_locations) {
				  $this->db->set('location', trim($form['location[]'][$key]));
				  if (!($geocoded_name = $form['geocoded_name[]'][$key])) {
					$geocoder = new locationGeoname;
					if ($geocoded_name = $geocoder->geonames_request(trim($form['location[]'][$key])))
					  $this->db->set('geocoded_name', $geocoded_name);
				  } else
					$this->db->set('geocoded_name', $geocoded_name);
				  if ($location = $CI->locations->getLocationByGeocodedName($geocoded_name)) {
					$this->db->set('predefined_location_id', $location->id);
				  }
				}
			  }

			  if ($use_predefined_locations || ($system_settings['predefined_locations_mode'] != 'only' && trim($form['location[]'][$key]))) {
				$this->db->set('listing_id', $listing_id);
				$this->db->set('address_line_1', $form['address_line_1[]'][$key]);
				$this->db->set('address_line_2', $form['address_line_2[]'][$key]);
				if ($type->zip_enabled)
				  $this->db->set('zip_or_postal_index', $form['zip_or_postal_index[]'][$key]);
				if ($level->maps_enabled) {
				  if (is_array($form['manual_coords']) && in_array($form['location_id[]'][$key], $form['manual_coords']))
					$manual = 1;
				  else
					$manual = 0;
				  $this->db->set('manual_coords', $manual);
				  $this->db->set('map_coords_1', $form['map_coords_1[]'][$key]);
				  $this->db->set('map_coords_2', $form['map_coords_2[]'][$key]);
				  $this->db->set('map_zoom', $form['map_zoom[]'][$key]);
				  $this->db->set('map_icon_id', $form['map_icon_id[]'][$key]);
				  $this->db->set('map_icon_file', $form['map_icon_file[]'][$key]);
				}
				$this->db->insert('listings_in_locations');
				// Clean cache
				$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_counts'));

				$location_id = $this->db->insert_id();
				// Save locations translations using virtual IDs
				if (isset($system_settings['language_areas_enabled']) && $system_settings['language_areas_enabled']) {
				  translations::saveTranslations(array('listings_in_locations', 'location', $location_id, $virtual_location_id));
				  translations::saveTranslations(array('listings_in_locations', 'address_line_1', $location_id, $virtual_location_id));
				  translations::saveTranslations(array('listings_in_locations', 'address_line_2', $location_id, $virtual_location_id));
				}
			  }
			}
		  }

		  if ($type->categories_type != 'disabled' && $level->categories_number) {
			foreach ($categories_array AS $category_id) {
			  $this->db->set('listing_id', $listing_id);
			  $this->db->set('category_id', $category_id);
			  $this->db->insert('listings_in_categories');
			  // Clean cache
			  $this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_counts'));
			}
		  }

		  return $listing_id;
		} else {
		  return false;
		}
	  } else {
		return false;
	  }
	} else {
	  return false;
	}
  }

  public function saveListingById($form, $level, $type, $existed_listing_obj) {
	$system_settings = registry::get('system_settings');

	$content_access_obj = contentAcl::getInstance();

	if ($level->title_enabled)
	  $this->db->set('title', $form['name']);
	if ($level->seo_title_enabled)
	  $this->db->set('seo_title', $form['seo_name']);
	if ($level->description_mode != 'disabled')
	  $this->db->set('listing_description', $form['listing_description']);
	if ($level->meta_enabled) {
	  $this->db->set('listing_meta_description', $form['listing_meta_description']);
	  $this->db->set('listing_keywords', str_replace("\n", ", ", $form['listing_keywords']));
	}
	if (isset($form['listing_logo_image']))
	  $this->db->set('logo_file', $form['listing_logo_image']);
	$this->db->set('last_modified_date', date("Y-m-d H:i:s"));
	// Set custom expiration date if user have permission and listing not under eternal active period
	if ($content_access_obj->isPermission('Edit listings expiration date') && !$level->eternal_active_period) {
	  $this->db->set('expiration_date', date("Y-m-d H:i:s", strtotime($form['expiration_date'])));
	}
	$this->db->where('id', $this->_listing_id);

	if ($type->categories_type != 'disabled' && $level->categories_number) {
	  $categories_array = unserialize($form['serialized_categories_list']);
	  $categories_array = array_filter($categories_array);
	}

	if (!$type->locations_enabled || ($type->locations_enabled && count($form['location_id[]']) <= $level->locations_number)
			&& ($type->categories_type == 'disabled' || count($categories_array) <= $level->categories_number)) {
	  if ($this->db->update('listings')) {
		if ($type->locations_enabled && $level->locations_number) {
		  $CI = &get_instance();
		  $CI->load->model('locations', 'locations_predefined');
		  $locations_levels = $CI->locations->selectAllLevels();

		  // Save listings in locations
		  $actual_locations_array = array();
		  $loc_array = $this->getListingLocations();
		  foreach ($loc_array AS $location)
			$actual_locations_array[] = $location->id;

		  foreach ($form['location_id[]'] AS $key => $virtual_location_id) {
			if (!in_array($virtual_location_id, $actual_locations_array)) {
			  // Insert new location

			  $use_predefined_locations = 0;
			  if ($system_settings['predefined_locations_mode'] != 'disabled') {
				// Save predefined location
				$lastlevel_loc_id = 0;
				foreach ($locations_levels AS $locations_level)
				  if ($form['loc_level_' . $locations_level->order_num . '[]'][$key])
					$lastlevel_loc_id = $form['loc_level_' . $locations_level->order_num . '[]'][$key];
				if ($lastlevel_loc_id) {
				  $this->db->set('predefined_location_id', $lastlevel_loc_id);

				  if ($system_settings['predefined_locations_mode'] == 'only' || (is_array($form['use_predefined_locations']) && in_array($form['location_id[]'][$key], $form['use_predefined_locations']))) {
					$use_predefined_locations = 1;
					$location = $CI->locations->getLocationById($lastlevel_loc_id);
					$this->db->set('geocoded_name', $location->geocoded_name);

					/* $location = $CI->locations->getLocationById($lastlevel_loc_id);
					  $geocoder = new locationGeoname;
					  if ($geocoded_name = $geocoder->geonames_request($location->getChainAsString()))
					  $this->db->set('geocoded_name', $geocoded_name); */
				  }
				  $this->db->set('use_predefined_locations', $use_predefined_locations);
				}
			  }

			  if ($system_settings['predefined_locations_mode'] != 'only') {
				// Save location string
				/* if (trim($form['location[]'][$key]) && !$use_predefined_locations) {
				  $this->db->set('location', trim($form['location[]'][$key]));
				  $geocoder = new locationGeoname;
				  if ($geocoded_name = $geocoder->geonames_request(trim($form['location[]'][$key])))
				  $this->db->set('geocoded_name', $geocoded_name);
				  } */
				if (trim($form['location[]'][$key]) && !$use_predefined_locations) {
				  $this->db->set('location', trim($form['location[]'][$key]));
				  if (!($geocoded_name = $form['geocoded_name[]'][$key])) {
					$geocoder = new locationGeoname;
					if ($geocoded_name = $geocoder->geonames_request(trim($form['location[]'][$key])))
					  $this->db->set('geocoded_name', $geocoded_name);
				  } else
					$this->db->set('geocoded_name', $geocoded_name);
				  if ($location = $CI->locations->getLocationByGeocodedName($geocoded_name)) {
					$this->db->set('predefined_location_id', $location->id);
				  }
				}
			  }

			  if ($use_predefined_locations || ($system_settings['predefined_locations_mode'] != 'only' && trim($form['location[]'][$key]))) {
				$this->db->set('listing_id', $this->_listing_id);
				$this->db->set('address_line_1', $form['address_line_1[]'][$key]);
				$this->db->set('address_line_2', $form['address_line_2[]'][$key]);
				if ($type->zip_enabled)
				  $this->db->set('zip_or_postal_index', $form['zip_or_postal_index[]'][$key]);
				if ($level->maps_enabled) {
				  if (is_array($form['manual_coords']) && in_array($form['location_id[]'][$key], $form['manual_coords']))
					$manual = 1;
				  else
					$manual = 0;
				  $this->db->set('manual_coords', $manual);
				  $this->db->set('map_coords_1', $form['map_coords_1[]'][$key]);
				  $this->db->set('map_coords_2', $form['map_coords_2[]'][$key]);
				  $this->db->set('map_zoom', $form['map_zoom[]'][$key]);
				  $this->db->set('map_icon_id', $form['map_icon_id[]'][$key]);
				  $this->db->set('map_icon_file', $form['map_icon_file[]'][$key]);
				}
				$this->db->insert('listings_in_locations');
				// Clean cache
				$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_counts'));

				$location_id = $this->db->insert_id();
				// Save locations translations using virtual IDs
				if (isset($system_settings['language_areas_enabled']) && $system_settings['language_areas_enabled']) {
				  translations::saveTranslations(array('listings_in_locations', 'location', $location_id, $virtual_location_id));
				  translations::saveTranslations(array('listings_in_locations', 'address_line_1', $location_id, $virtual_location_id));
				  translations::saveTranslations(array('listings_in_locations', 'address_line_2', $location_id, $virtual_location_id));
				}
			  }
			} else {
			  // Update existed location
			  $location_id = $virtual_location_id;

			  $use_predefined_locations = 0;
			  if ($system_settings['predefined_locations_mode'] != 'disabled') {
				// Save predefined location
				$lastlevel_loc_id = 0;
				foreach ($locations_levels AS $locations_level)
				  if ($form['loc_level_' . $locations_level->order_num . '[]'][$key])
					$lastlevel_loc_id = $form['loc_level_' . $locations_level->order_num . '[]'][$key];
				if ($lastlevel_loc_id) {
				  $this->db->set('predefined_location_id', $lastlevel_loc_id);

				  if ($system_settings['predefined_locations_mode'] == 'only' || (is_array($form['use_predefined_locations']) && in_array($form['location_id[]'][$key], $form['use_predefined_locations']))) {
					$use_predefined_locations = 1;
					$location = $CI->locations->getLocationById($lastlevel_loc_id);
					$this->db->set('geocoded_name', $location->geocoded_name);
					/* $geocoder = new locationGeoname;
					  if ($geocoded_name = $geocoder->geonames_request($location->getChainAsString()))
					  $this->db->set('geocoded_name', $geocoded_name); */
				  }
				  $this->db->set('use_predefined_locations', $use_predefined_locations);
				}
			  }

			  if ($system_settings['predefined_locations_mode'] != 'only') {
				// Save location string
				if (trim($form['location[]'][$key]) && !$use_predefined_locations) {
				  $this->db->set('location', trim($form['location[]'][$key]));
				  if (!($geocoded_name = $form['geocoded_name[]'][$key])) {
					$geocoder = new locationGeoname;
					if ($geocoded_name = $geocoder->geonames_request(trim($form['location[]'][$key])))
					  $this->db->set('geocoded_name', $geocoded_name);
				  } else
					$this->db->set('geocoded_name', $geocoded_name);
				  if ($location = $CI->locations->getLocationByGeocodedName($geocoded_name)) {
					$this->db->set('predefined_location_id', $location->id);
				  }
				}

				/* if (trim($form['location[]'][$key]) && !$use_predefined_locations) {
				  $this->db->set('location', trim($form['location[]'][$key]));
				  $geocoder = new locationGeoname;
				  if ($geocoded_name = $geocoder->geonames_request(trim($form['location[]'][$key])))
				  $this->db->set('geocoded_name', $geocoded_name);
				  } */
			  }

			  $this->db->set('address_line_1', $form['address_line_1[]'][$key]);
			  $this->db->set('address_line_2', $form['address_line_2[]'][$key]);
			  if ($type->zip_enabled)
				$this->db->set('zip_or_postal_index', $form['zip_or_postal_index[]'][$key]);
			  if ($level->maps_enabled) {
				if (is_array($form['manual_coords']) && in_array($form['location_id[]'][$key], $form['manual_coords']))
				  $manual = 1;
				else
				  $manual = 0;
				$this->db->set('manual_coords', $manual);
				$this->db->set('map_coords_1', $form['map_coords_1[]'][$key]);
				$this->db->set('map_coords_2', $form['map_coords_2[]'][$key]);
				$this->db->set('map_zoom', $form['map_zoom[]'][$key]);
				$this->db->set('map_icon_id', $form['map_icon_id[]'][$key]);
				$this->db->set('map_icon_file', $form['map_icon_file[]'][$key]);
			  }
			  $this->db->where('id', $location_id);
			  $this->db->update('listings_in_locations');
			  // Clean cache
			  $this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_counts'));

			  $keys = array_keys($actual_locations_array, $location_id);
			  unset($actual_locations_array[$keys[0]]);
			}
		  }
		  // Delete locations those were unchecked
		  foreach ($actual_locations_array AS $location_id) {
			$this->db->delete('listings_in_locations', array('id' => $location_id));
		  }
		}

		// --------------------------------------------------------------------------------------------
		// Does this listing can be claimed?
		// --------------------------------------------------------------------------------------------
		if ($content_access_obj->isPermission('Manage ability to claim')) {
		  if ($form['ability_to_claim']) {
			$this->db->set('listing_id', $this->_listing_id);
			$this->db->set('ability_to_claim', 1);
			$this->db->set('from_user_id', $existed_listing_obj->owner_id);
			$this->db->on_duplicate_insert('listings_claims');
		  } else {
			$this->db->delete('listings_claims', array('listing_id' => $this->_listing_id));
		  }
		}
		// --------------------------------------------------------------------------------------------


		if ($type->categories_type != 'disabled' && $level->categories_number) {
		  // Save listings in categories
		  $actual_categories = $this->getListingCategories();
		  $actual_categories_array = array();
		  foreach ($actual_categories AS $category)
			$actual_categories_array[] = $category->id;
		  foreach ($categories_array AS $category_id) {
			if (!in_array($category_id, $actual_categories_array)) {
			  $this->db->set('listing_id', $this->_listing_id);
			  $this->db->set('category_id', $category_id);
			  $this->db->on_duplicate_insert('listings_in_categories');
			  // Clean cache
			  $this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_counts'));
			} else {
			  $keys = array_keys($actual_categories_array, $category_id);
			  unset($actual_categories_array[$keys[0]]);
			}
		  }

		  // Delete categories those were unchecked
		  foreach ($actual_categories_array AS $category_id) {
			$this->db->delete('listings_in_categories', array('listing_id' => $this->_listing_id, 'category_id' => $category_id));
			// Clean cache
			$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_counts'));
		  }
		}
		return true;
	  } else {
		return false;
	  }
	} else {
	  return false;
	}
  }

  /**
   * Saves listing level
   *
   * @param int $level_id
   * @return bool
   */
  public function saveListingLevelAndExpirationDate($level_id, $new_time) {
	$this->db->set('level_id', $level_id);
	$this->db->set('expiration_date', date("Y-m-d H:i:s", $new_time));
	$this->db->set('last_modified_date', date("Y-m-d H:i:s"));
	$this->db->where('id', $this->_listing_id);
	return $this->db->update('listings');
  }

  /**
   * Saves listing status
   *
   * @param int $status
   * @return bool
   */
  public function saveListingStatus($status) {
	$this->db->set('status', $status);
	$this->db->where('id', $this->_listing_id);
	return $this->db->update('listings');
  }

  /**
   * select recursively categories childs
   *
   * @param array $categories
   * @param int $parent_id
   * @param array $ids
   */
  public function getChildCategoriesIdsRecursive($categories, $parent_id, &$ids) {
	foreach ($categories AS $category) {
	  if ($category['parent_category_id'] == $parent_id) {
		$ids[] = $category['id'];
		$this->getChildCategoriesIdsRecursive($categories, $category['id'], $ids);
	  }
	}
  }

  /**
   * Select all listings table using paginator,
   * this executes optimized method with 3 queries
   *
   * @return array
   */
  public function selectListings($args = array(), $orderby = 'id', $direction = 'desc', $search_fields_listings_ids_array = array(), $categories_db = array(), $fully_load = true) {
	$CI = &get_instance();

	if (isset($args['page']) && $args['page'])
	  $page = $args['page'];
	else
	  $page = null;

	$current_type = null;
	if (isset($args['search_type']) && $args['search_type']) {
	  if (is_numeric($args['search_type'])) {
		$CI->load->model('types', 'types_levels');
		$current_type = $CI->types->getTypeById($args['search_type']);
	  } elseif (is_object($args['search_type'])) {
		$current_type = $args['search_type'];
	  } elseif (is_string($args['search_type'])) {
		$CI->load->model('types', 'types_levels');
		$current_type = $CI->types->getTypeBySeoName($args['search_type']);
	  }
	}

	if (isset($args['only_with_logos']) && ($args['only_with_logos'] === TRUE || $args['only_with_logos'] === 'true')) {
	  $only_with_logos = true;
	} else {
	  $only_with_logos = false;
	}

	if (isset($args['search_featured']) && ($args['search_featured'] === TRUE || $args['search_featured'] === 'true')) {
	  $search_featured = true;
	} else {
	  $search_featured = false;
	}

	$search_owner_id = null;
	$search_owner_login = null;
	$search_owners = null;
	if (isset($args['search_owner']) && $args['search_owner']) {
	  if (is_object($args['search_owner'])) {
		$search_owner = $args['search_owner'];
		$search_owner_id = $search_owner->id;
	  } elseif (is_array($args['search_owner'])) {
		$search_owners = $args['search_owner'];
	  } elseif (is_numeric($args['search_owner'])) {
		$search_owner_id = $args['search_owner'];
	  } elseif (is_string($args['search_owner'])) {
		$search_owner_login = urldecode(html_entity_decode($args['search_owner']));
	  }
	}

	// --------------------------------------------------------------------------------------------
	// If we need to order by content field value - prepare its type and ID
	if (strpos($orderby, 'cf.') !== FALSE) {
	  $this->db->select('cf.type AS type');
	  $this->db->select('cf.id AS id');
	  $this->db->from('content_fields AS cf');
	  $this->db->where('cf.seo_name', substr($orderby, 3));
	  $row = $this->db->get()->row_array();
	  $content_field_type = $row['type'];
	  $content_field_id = $row['id'];
	}
	// --------------------------------------------------------------------------------------------

	if (isset($args['search_by_ids']) && $args['search_by_ids']) {
	  if (is_numeric($args['search_by_ids'])) {
		$search_by_ids = array($args['search_by_ids']);
	  } elseif (!is_array($args['search_by_ids'])) {
		$search_by_ids = array_filter(explode(',', $args['search_by_ids']));
	  } else {
		$search_by_ids = $args['search_by_ids'];
	  }
	} else {
	  $search_by_ids = array();
	}

	if (isset($args['except_listings']) && $args['except_listings']) {
	  if (is_numeric($args['except_listings'])) {
		$except_listings = array($args['except_listings']);
	  } elseif (!is_array($args['except_listings'])) {
		$except_listings = array_filter(explode(',', $args['except_listings']));
	  } else {
		$except_listings = $args['except_listings'];
	  }
	} else {
	  $except_listings = array();
	}


	// --------------------------------------------------------------------------------------------
	// For Search page
	// --------------------------------------------------------------------------------------------
	if ($page == 'search' || $page == 'admin' || (isset($args['search']) && $args['search'] == 'true')) {
	  $search_core = new searchCore;
	  $search_core->setArgs($args);
	  $what_listings_ids = $search_core->processWhat();
	  // When radius search processing - it doesn't matter the results for 'Where?' field,
	  // we need exact coordinates of location, then we will calculate distance from these coordinates
	  if (!isset($args['where_radius']) || !$args['where_radius'])
		$where_listings_ids = $search_core->processWhere();
	  else
		$where_listings_ids = array();
	} else {
	  $what_listings_ids = array();
	  $where_listings_ids = array();
	}
	// If search by 'where?' didn't give any results - finish search
	if (isset($args['where_search']) && $args['where_search'] && empty($where_listings_ids) && (!isset($args['where_radius']) || !$args['where_radius']))
	  return array();

	// Radius search
	$where_radius_ids = array();
	if (isset($args['where_radius']) && $args['where_radius'] && isset($args['where_search']) && $args['where_search']) {
	  $where_radius = $args['where_radius'];

	  $geocoder = new locationGeoname;
	  if ($coordinates = $geocoder->geonames_request($args['where_search'], 'coordinates')) {
		$x = $coordinates[1];
		$y = $coordinates[0];
		$system_settings = registry::get('system_settings');
		if ($system_settings['search_in_raduis_measure'] == 'miles')
		  $R = COORDS_MILES_MULTIPLIER; // earth's mean radius in miles
		else
		  $R = COORDS_KILOMETERS_MULTIPLIER; // earth's mean radius in km

		registry::set('radius_search_args', array('where_search' => $args['where_search'], 'radius' => $where_radius, 'map_coord_1' => $x, 'map_coord_2' => $y));

		$this->db->select('lil.listing_id');
		//$this->db->select('POWER((lil.map_coords_1-' . $x . '), 2)+POWER((lil.map_coords_2-' . $y . '), 2) AS distance', false);
		$dLat = '((lil.map_coords_1-' . $x . ')*PI()/180)';
		$dLong = '((lil.map_coords_2-' . $y . ')*PI()/180)';
		$a = '(sin(' . $dLat . '/2) * sin(' . $dLat . '/2) + cos(' . $x . '*pi()/180) * cos(lil.map_coords_1*pi()/180) * sin(' . $dLong . '/2) * sin(' . $dLong . '/2))';
		$c = '2*atan2(sqrt(' . $a . '), sqrt(1-' . $a . '))';
		$sql = $R . '*' . $c;

		$this->db->select($sql . ' AS distance', false);
		$this->db->from('listings_in_locations as lil');
		$this->db->having('distance <= ' . $where_radius);
		$this->db->order_by('distance');

		// This is heavy sql query, so we process it standalone and then retrieve IDs and pass them further
		$result = $this->db->get()->result_array();
		foreach ($result AS $row) {
		  $where_radius_ids[] = $row['listing_id'];
		}
	  }
	  // If search in radius didn't give any results - finish search
	  if (!$where_radius_ids)
		return array();
	}

	// Get listings ids from results queries of content fields
	foreach ($search_fields_listings_ids_array AS $listings_ids_array) {
	  if (is_array($listings_ids_array)) {
		$condition = key($listings_ids_array);
		$listings_ids = $listings_ids_array[$condition];
		if ($listings_ids) {
		  if ($condition == 'AND') {
			if ($what_listings_ids)
			  $what_listings_ids = array_intersect($what_listings_ids, $listings_ids);
			elseif (!isset($args['what_search']))
			  $what_listings_ids = $listings_ids;
		  } elseif ($condition == 'OR') {
			$what_listings_ids = array_merge($what_listings_ids, $listings_ids);
		  }
		} else {
		  if ($condition == 'AND') {
			return array();
		  }
		}
	  }
	}

	// If search by 'what?' and search content fields didn't give any results - finish search
	if (isset($args['what_search']) && $args['what_search'] && empty($what_listings_ids))
	  return array();

	$list = array();
	//var_dump($search_fields_listings_ids_array);
	//var_dump($what_listings_ids);
	//var_dump($where_listings_ids);
	//var_dump($where_radius_ids);
	if ($what_listings_ids)
	  $list[] = $what_listings_ids;
	if ($where_listings_ids)
	  $list[] = $where_listings_ids;
	if ($where_radius_ids)
	  $list[] = $where_radius_ids;
	if ($list) {
	  if (count($list) > 1)
		$search_by_ids = call_user_func_array('array_intersect', $list);
	  else
		$search_by_ids = $list[0];

	  if ($except_listings && $search_by_ids) {
		// Remove except_listings from results
		$search_by_ids = array_diff($search_by_ids, $except_listings);
	  }
	  if (empty($search_by_ids))
		return array();
	}
	// --------------------------------------------------------------------------------------------

	if (isset($args['search_category']) && $args['search_category']) {
	  if (is_array($args['search_category'])) {
		$top_categories_ids = $args['search_category'];
	  } elseif (is_numeric($args['search_category'])) {
		$top_categories_ids = array($args['search_category']);
	  } elseif (is_string($args['search_category'])) {
		$top_categories_ids = array_values(array_filter(explode(',', urldecode(html_entity_decode($args['search_category'])))));
		if (count($top_categories_ids) == 1 && !is_numeric($top_categories_ids[0])) {
		  $CI->load->model('categories', 'categories');
		  $category = $CI->categories->getCategoryBySeoName($top_categories_ids[0]);
		  $top_categories_ids = array($category->id);
		}
	  } elseif (is_object($args['search_category'])) {
		$top_categories_ids = array($args['search_category']->id);
	  }
	  array_walk($top_categories_ids, "trim");

	  // Select categories, if they weren't passed into function
	  if (!$categories_db) {
		$CI->load->model('categories', 'categories');
		if (!is_null($current_type)) {
		  if ($current_type->categories_type == 'local')
			$CI->categories->setTypeId($current_type->id);
		}
		$categories_db = $CI->categories->selectCategoriesFromDB();
	  }
	  $categories_ids = array();
	  foreach ($top_categories_ids AS $id)
		$this->getChildCategoriesIdsRecursive($categories_db, $id, $categories_ids);
	  $categories_ids = array_merge($top_categories_ids, $categories_ids);
	} else {
	  $categories_ids = null;
	}

	// During search in radius - it doesn't matter which location we have
	if (!(isset($args['where_radius']) && $args['where_radius']) && ((isset($args['search_location']) && $args['search_location']) || (isset($args['predefined_location_id']) && is_numeric($args['predefined_location_id'])))) {
	  $CI->load->model('locations', 'locations_predefined');
	  if (is_numeric($args['search_location'])) {
		// By ID
		$search_location = $CI->locations->getLocationById($args['search_location']);
	  } elseif (is_object($args['search_location'])) {
		// This is ready object
		$search_location = $args['search_location'];
	  } elseif ($args['predefined_location_id']) {
		// By location's ID from autocomplete field
		$search_location = $CI->locations->getLocationById($args['predefined_location_id']);
	  } else {
		// By seo name
		$search_location = $CI->locations->getLocationBySeoName($args['search_location']);
	  }
	  $locations_children_objs = $CI->locations->getAllChildrenOfLocation($search_location);
	  $locations_children_objs[] = $search_location;
	  $search_locations_ids = array();
	  foreach ($locations_children_objs AS $location_obj) {
		$search_locations_ids[] = $location_obj->id;
	  }
	} else {
	  $search_location = null;
	  $search_locations_ids = null;
	}

	if (isset($args['search_levels']) && $args['search_levels']) {
	  if (is_numeric($args['search_levels'])) {
		$search_levels = array($args['search_levels']);
	  } elseif (is_array($args['search_levels'])) {
		$search_levels = $args['search_levels'];
	  } elseif (is_string($args['search_levels'])) {
		$search_levels = explode(',', $args['search_levels']);
	  } else {
		$search_levels = null;
	  }
	} else {
	  $search_levels = null;
	}
	// --------------------------------------------------------------------------------------------
	// --------------------------------------------------------------------------------------------
	// --------------------------------------------------------------------------------------------
	// --------------------------------------------------------------------------------------------
	// -----------------------------------------------------------------------------------
	// Select id of listings that will be shown
	$this->db->select('l.id');
	$this->db->from('listings as l');
	$this->db->join('levels as lev', 'lev.id=l.level_id', 'left');
	$this->db->join('types as t', 't.id=lev.type_id', 'left');
	$this->db->join('users as u', 'l.owner_id=u.id', 'left');

	if ($only_with_logos) {
	  $this->db->where('lev.logo_enabled', 1);
	  $this->db->where('l.logo_file !=', '');
	}

	if ($search_featured) {
	  $this->db->where('lev.featured', 1);
	}

	if (isset($args['search_claimed_listings']) && $args['search_claimed_listings']) {
	  if ($args['search_claimed_listings'] != 'any') {
		$this->db->join('listings_claims AS lc', 'lc.listing_id=l.id', 'left');
		if ($args['search_claimed_listings'] == 'ability_to_claim') {
		  $this->db->where('lc.ability_to_claim', 1);
		  $this->db->where('lc.approved', 0);
		} elseif ($args['search_claimed_listings'] == 'claimed') {
		  $this->db->where('lc.ability_to_claim', 0);
		  $this->db->where('lc.approved', 0);
		  $this->db->where('lc.to_user_id !=', 0);
		} elseif ($args['search_claimed_listings'] == 'approved_claim') {
		  $this->db->where('lc.to_user_id !=', 0);
		  $this->db->where('lc.approved', 1);
		}
	  }
	}

	if ($current_type) {
	  $this->db->where('t.id', $current_type->id);
	}
	if ($page == 'search') {
	  if ($current_type && $current_type->search_type == 'local') {
		$this->db->where('t.search_type', 'local');
	  } elseif ($current_type && $current_type->search_type == 'global') {
		$this->db->where('t.search_type', 'global');
	  }
	  $this->db->where('t.search_type !=', 'disabled');
	}

	if ($search_levels) {
	  $this->db->where_in('lev.id', $search_levels);
	}

	if ($categories_ids) {
	  $this->db->join('listings_in_categories as lic', 'l.id=lic.listing_id', 'left');
	  $this->db->where_in('lic.category_id', $categories_ids);

	  if (is_null($current_type) || $current_type->categories_type == 'global') {
		$this->db->where('t.categories_type', 'global');
	  }
	}

	if ($search_owner_id || $search_owner_login) {
	  if ($search_owner_id)
		$this->db->where('u.id', $search_owner_id);
	  if ($search_owner_login)
		$this->db->where('u.login', $search_owner_login);
	}

	if (isset($args['search_status']) && $args['search_status']) {
	  $this->db->where('l.status', urldecode($args['search_status']));
	}

	if (isset($args['search_users_status']) && $args['search_users_status']) {
	  $this->db->where('u.status', urldecode($args['search_users_status']));
	}

	if (isset($args['search_creation_date']) && $args['search_creation_date']) {
	  // Receives date in unix timestamp and also in 'Y-m-d' formats
	  if (($tmstmp = strtotime($args['search_creation_date'])) !== FALSE && strtotime($args['search_creation_date']) != -1)
		$this->db->where('TO_DAYS(l.creation_date) = ', 'TO_DAYS("' . date("Y-m-d", $tmstmp) . '")', false);
	  else
		$this->db->where('TO_DAYS(l.creation_date) = ', 'TO_DAYS("' . date("Y-m-d", $args['search_creation_date']) . '")', false);
	}
	if (isset($args['search_from_creation_date']) && $args['search_from_creation_date']) {
	  // Receives date in unix timestamp and also in 'Y-m-d' formats
	  if (($tmstmp = strtotime($args['search_from_creation_date'])) !== FALSE && strtotime($args['search_from_creation_date']) != -1)
		$this->db->where('TO_DAYS(l.creation_date) >= ', 'TO_DAYS("' . date("Y-m-d", $tmstmp) . '")', false);
	  else
		$this->db->where('TO_DAYS(l.creation_date) >= ', 'TO_DAYS("' . date("Y-m-d", $args['search_from_creation_date']) . '")', false);
	}
	if (isset($args['search_to_creation_date']) && $args['search_to_creation_date']) {
	  // Receives date in unix timestamp and also in 'Y-m-d' formats
	  if (($tmstmp = strtotime($args['search_from_creation_date'])) !== FALSE && strtotime($args['search_from_creation_date']) != -1)
		$this->db->where('TO_DAYS(l.creation_date) <= ', 'TO_DAYS("' . date("Y-m-d", $tmstmp) . '")', false);
	  else
		$this->db->where('TO_DAYS(l.creation_date) <= ', 'TO_DAYS("' . date("Y-m-d", $args['search_to_creation_date']) . '")', false);
	}

	if ($search_location) {
	  $this->db->join('listings_in_locations AS lil', 'lil.listing_id=l.id', 'left');
	  $where_sql = '';
	  if ($search_location->geocoded_name)
		$where_sql = '(lil.geocoded_name LIKE "%' . $search_location->geocoded_name . '" AND t.locations_enabled=1 AND lev.locations_number>0) OR ';
	  $this->db->where('(' . $where_sql . 'lil.predefined_location_id IN (' . implode(',', $search_locations_ids) . '))', null, false);
	}

	if ($search_by_ids) {
	  $this->db->where_in('l.id', $search_by_ids);
	}

	$search_sql = str_replace("\n", " ", $this->db->_compile_select());
	$this->db->_reset_select();

	$this->db->select('l.id');
	$this->db->from('listings as l');
	$this->db->join('levels as lev', 'lev.id=l.level_id', 'left');
	$this->db->join('users as u', 'l.owner_id=u.id', 'left');
	$this->db->where_in('l.id', $search_sql, false);
	//$this->db->where_not_in('l.title', 'untranslated'); // except untranslated listings
	if ($orderby == 'rating') {
	  $this->db->select('AVG(r.value) AS rating');
	  $this->db->join('ratings AS r', 'l.id=r.object_id', 'left');
	  $this->db->where('(r.objects_table="listings" OR r.objects_table IS NULL)');
	  $this->db->groupby('l.id');
	}
	if ($orderby == 'rev_count') {
	  $this->db->select('COUNT(rev.id) AS rev_count');
	  $this->db->join('reviews AS rev', 'rev.object_id=l.id', 'left');
	  $this->db->where('(rev.objects_table="listings" OR rev.objects_table IS NULL)');
	  $this->db->where('(rev.status=1 OR rev.status IS NULL)');
	  $this->db->groupby('l.id');
	}
	if ($orderby == 'rev_last') {
	  $this->db->select('MAX(rev.date_added) AS rev_last');
	  $this->db->join('reviews AS rev', 'rev.object_id=l.id', 'left');
	  $this->db->where('(rev.objects_table="listings" OR rev.objects_table IS NULL)');
	  $this->db->where('(rev.status=1 OR rev.status IS NULL)');
	  $this->db->groupby('l.id');
	}
	if (($orderby && $direction) && (!isset($what_listings_ids) || !$what_listings_ids) && (!isset($where_listings_ids) || !$where_listings_ids) && (!isset($where_radius_ids) || !$where_radius_ids)) {
	  if ($orderby != 'random' && strpos($orderby, 'cf.') === FALSE) {
		// Order by date, title, level order, listings ratings, .......
		$this->db->order_by($orderby, $direction);
	  }
	} elseif (!isset($where_radius_ids) && !$where_radius_ids) {
	  // When any request in 'what' or 'where' search fields,
	  // then order listings by relevance
	  if (isset($what_listings_ids) && $what_listings_ids) {
		$orderby = 'CASE l.id';
		$i = 1;
		foreach ($what_listings_ids AS $id) {
		  $orderby .= ' WHEN ' . $id . ' THEN ' . $i;
		  $i++;
		}
		$orderby .= ' END';
		$this->db->order_by($orderby);
	  }
	  if (isset($where_listings_ids) && $where_listings_ids) {
		$orderby = 'CASE l.id';
		$i = 1;
		foreach ($where_listings_ids AS $id) {
		  $orderby .= ' WHEN ' . $id . ' THEN ' . $i;
		  $i++;
		}
		$orderby .= ' END';
		$this->db->order_by($orderby);
	  }
	}
	// Order by distance
	if (isset($where_radius_ids) && $where_radius_ids && $orderby == 'distance' && $direction) {
	  if (isset($where_radius_ids) && $where_radius_ids) {
		$orderby = 'CASE l.id';
		$i = 1;
		foreach ($where_radius_ids AS $id) {
		  $orderby .= ' WHEN ' . $id . ' THEN ' . $i;
		  $i++;
		}
		$orderby .= ' END';
		$this->db->order_by($orderby, $direction);
	  }
	}
	// Secondly order by date
	$this->db->order_by('l.creation_date', 'desc');
	$query = $this->db->get();
	$result_array = $query->result_array();
	$all_ids = array();
	foreach ($result_array AS $id) {
	  $all_ids[] = $id['id'];
	}

	if ($except_listings) {
	  // Remove except_listings from results
	  $all_ids = array_diff($all_ids, $except_listings);
	  if (empty($all_ids)) {
		$this->db->_reset_select();
		return array();
	  }
	}

	if (isset($this->paginator) && $this->paginator)
	  $this->paginator->setCount(count($all_ids));

	// Order by content field value
	// select ids, connected with those listings
	if (!empty($all_ids) && strpos($orderby, 'cf.') !== FALSE) {
	  $field_class = $content_field_type . 'Class';
	  $field = new $field_class;
	  if (method_exists($field, 'orderby')) {
		$ordered_ids = $field->orderby($all_ids, LISTINGS_LEVEL_GROUP_CUSTOM_NAME, $content_field_id, $direction);
		// In result ids first will be ordered_ids, than other
		$result_ids = array_merge($ordered_ids, array_diff($all_ids, $ordered_ids));
	  } else {
		if ($content_field_type == 'varchar') {
		  $this->db->select('is_numeric');
		  $this->db->from('content_fields_type_' . $content_field_type);
		  $this->db->where('field_id', $content_field_id);
		  $rowType = $this->db->get()->result_array();
		}
		$this->db->distinct();
		$this->db->select('l.id');
		$this->db->from('listings as l');
		$this->db->join('content_fields_groups AS cfg', 'cfg.custom_id=l.level_id', 'left');
		$this->db->where('cfg.custom_name', LISTINGS_LEVEL_GROUP_CUSTOM_NAME);

		$this->db->join('content_fields_to_groups AS cftg', 'cftg.group_id=cfg.id', 'left');

		$this->db->join('content_fields_type_' . $content_field_type . '_data AS cftd', 'cftd.object_id=l.id', 'left');
		$this->db->where('cftg.field_id', $content_field_id);
		$this->db->where('(cftd.field_id=' . $content_field_id . ' OR cftd.field_id IS NULL)');
		if (isset($rowType[0]['is_numeric'])) {
		  $orderingBy = $rowType[0]['is_numeric'] == 1 ? 'cast(cftd.field_value AS SIGNED)' : 'cftd.field_value';
		} else {
		  $orderingBy = 'cftd.field_value';
		}
		$this->db->order_by($orderingBy, $direction);
		$this->db->where_in('l.id', $all_ids);
		$query = $this->db->get();
		$ordered_array = $query->result_array();
		$ordered_ids = array();
		foreach ($ordered_array AS $id) {
		  $ordered_ids[] = $id['id'];
		}
		// In result ids first will be ordered_ids, than other
		$result_ids = array_merge($ordered_ids, array_diff($all_ids, $ordered_ids));
	  }
	} else {
	  $result_ids = $all_ids;
	}

	if ($orderby == 'random') {
	  shuffle($result_ids);
	}

	if (isset($this->paginator) && $this->paginator) {
	  $ids = $this->paginator->getResultIds($result_ids);
	} else {
	  $ids = $result_ids;
	}

	if ($page == 'admin')
	  $page = null;

	if (!empty($ids)) {
	  $result_listings = array();
	  foreach ($ids AS $id) {
		$result_listings[] = $this->getListingById($id, $page);
	  }
	  return $result_listings;
	  // --------------------------------------------------------------------------------------------
	} else {
	  // No listings found
	  $this->db->_reset_select();
	  return array();
	}
  }

  /**
   * Select listings of current user(my listings) from listings table
   *
   * @return array
   */
  public function selectMyListings($orderby = 'id', $direction = 'desc') {
	return $this->selectListings(array('search_owner' => $this->session->userdata('user_id')), $orderby, $direction);
  }

  public function getListingById($listing_id = null, $page = null) {
	if (is_null($listing_id))
	  $listing_id = $this->_listing_id;

	$cache_index = 'listing_' . $listing_id . '_' . $page;
	if (!$cache = $this->cache->load($cache_index)) {
	  if ($level_id = $this->getLevelIdByListingId($listing_id)) {
		$this->setListingId($listing_id);
		$listing = new listing($level_id, $listing_id, $page);
		$listing->setListingFromArray($this->getListingRowById(), $this->getListingCategories(), $this->getListingLocations());
	  } else
		return false;

	  $this->cache->save($listing, $cache_index, array('listings_' . $listing_id, 'users_' . $listing->user->id, 'listings', 'categories', 'locations', 'content_fields'));
	} else {
	  $listing = $cache;
	}

	return $listing;
  }

  public function getListingRowById($id = null) {
	if (is_null($id)) {
	  $id = $this->_listing_id;
	}

	$this->db->select('l.*');
	$this->db->select('u.login as owner_login');
	$this->db->select('lev.name as level');
	$this->db->select('t.name as type');
	$this->db->from('listings as l');
	$this->db->join('users as u', 'l.owner_id=u.id', 'left');
	$this->db->join('levels as lev', 'lev.id=l.level_id', 'left');
	$this->db->join('types as t', 't.id=lev.type_id', 'left');
	$this->db->where('l.id', $id);
	$query = $this->db->get();

	return $query->row_array();
  }

  public function deleteListings($listings_array) {
	if (count($listings_array)) {
	  foreach ($listings_array AS $id => $val) {
		$this->deleteListingById($id);
	  }
	  return true;
	} else
	  return false;
  }

  public function deleteListingById($listing_id = null) {
	if (empty($listing_id)) {
	  $listing_id = $this->_listing_id;
	}

	$this->db->delete('listings_in_categories', array('listing_id' => $listing_id));
	$this->db->delete('listings_in_locations', array('listing_id' => $listing_id));
	$this->db->delete('listings_claims', array('listing_id' => $listing_id));

	$this->db->delete('images', array('listing_id' => $listing_id));

	$this->db->delete('files', array('listing_id' => $listing_id));

	$this->db->delete('videos', array('listing_id' => $listing_id));

	$this->db->delete('ratings', array('objects_table' => 'listings', 'object_id' => $listing_id));
	$this->db->delete('reviews', array('objects_table' => 'listings', 'object_id' => $listing_id));

	// Delete level items from packages
	$CI = &get_instance();
	if ($CI->load->is_module_loaded("packages")) {
	  $this->db->where('listing_id', $listing_id);
	  $this->db->delete('packages_listings');
	}

	return $this->db->delete('listings', array('id' => $listing_id));
  }

  public function blockListings($listings_ids) {
	// Clean cache
	$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_counts'));

	// Clean cache
	foreach ($listings_ids AS $listing_id)
	  $this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_' . $listing_id));

	$this->db->set('status', '2');
	$this->db->where_in('id', $listings_ids);
	return $this->db->update('listings');
  }

  public function activateListings($listings_ids) {
	// Clean cache
	$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_counts'));

	// Clean cache
	foreach ($listings_ids AS $listing_id)
	  $this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_' . $listing_id));

	$this->db->set('status', '1');
	$this->db->where_in('id', $listings_ids);
	return $this->db->update('listings');
  }

  /**
   * Select all categories of listing
   *
   * @return array
   */
  public function getListingCategories($listing_id = null) {
	if (!$listing_id)
	  $listing_id = $this->_listing_id;

	$this->db->select('c.*');
	$this->db->from('categories AS c');
	$this->db->join('listings_in_categories AS lic', 'c.id=lic.category_id', 'left');
	$this->db->where('lic.listing_id', $listing_id);
	$this->db->order_by('lic.id');
	$query = $this->db->get();

	$categories_db = $query->result_array();

	$categories = array();
	foreach ($categories_db AS $category_row) {
	  $category = new category;
	  $category->setCategoryFromArray($category_row);
	  $categories[] = $category;
	}

	return $categories;
  }

  /**
   * Select all locations of listing
   *
   * @return array
   */
  public function getListingLocations($listing_id = null) {
	if (!$listing_id)
	  $listing_id = $this->_listing_id;

	$this->db->select('lic.*');
	$this->db->from('listings_in_locations AS lic');
	$this->db->where('lic.listing_id', $listing_id);
	$this->db->order_by('lic.id');
	$query = $this->db->get();

	$locations_db = $query->result_array();

	$locations = array();
	foreach ($locations_db AS $location_row) {
	  $location = new listingLocation;
	  $location->setLocationFromArray($location_row);
	  $locations[] = $location;
	}
	return $locations;
  }

  public function prolongListing($listing) {
	$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_counts', 'listings_' . $listing->id));

	$this->db->set('status', 1); // active
	// Prolong listing to the date, that calculate like NOW + active period
	if ($listing->level->active_days != 0 || $listing->level->active_months != 0 || $listing->level->active_years != 0) {
	  $this->db->set('expiration_date', date("Y-m-d H:i:s", (mktime() + (
					  ($listing->level->active_days) +
					  ($listing->level->active_months * 30) +
					  ($listing->level->active_years * 365)
					  ) * 86400)));
	} else {
	  // If this is eternal active period - just make it like NOW + 10 days (for levels with ability to edit active period of listings)
	  $this->db->set('expiration_date', date("Y-m-d H:i:s", (mktime() + 864000)));
	}
	$this->db->set('last_modified_date', date("Y-m-d H:i:s"));
	$this->db->set('was_prolonged_times', 'was_prolonged_times+1', false);
	$this->db->where('id', $this->_listing_id);
	return $this->db->update('listings');
  }

  public function getMyListingsCount() {
	$this->db->select('count(*) AS listings_count');
	$this->db->from('listings');
	$this->db->where('owner_id', $this->session->userdata('user_id'));
	$query = $this->db->get();
	$row = $query->row_array();

	return $row['listings_count'];
  }

  public function getListingArrayById($listing_id) {
	$query = new query;
	$query->select();
	$query->from('listings');
	$query->where('id', $listing_id);

	$this->db->execQuery($query);

	return $this->db->rowAsArray();
  }

  public function suspendExpiredActiveListings() {
	$this->db->select('l.id');
	$this->db->select('l.title');
	$this->db->select('u.login AS owner_login');
	$this->db->select('u.email AS owner_email');
	$this->db->from('listings AS l');
	$this->db->join('levels AS lev', 'l.level_id=lev.id', 'left');
	$this->db->join('users AS u', 'u.id=l.owner_id', 'left');
	$this->db->where('l.expiration_date <=', 'NOW()', false);
	$this->db->where('l.status', 1);
	$this->db->where('u.status', 2);
	// Expire listings, those are not under eternal active period OR where users set their own expiration date
	$this->db->where('(lev.active_years!=0 OR lev.active_months!=0 OR lev.active_days!=0 OR lev.allow_to_edit_active_period!=0)', null, false);
	$query = $this->db->get();

	$listings = $query->result_array();

	$ids = array();
	foreach ($listings AS $listing) {
	  $ids[] = $listing['id'];

	  $event_params = array(
		  'LISTING_ID' => $listing['id'],
		  'LISTING_TITLE' => $listing['title'],
		  'RECIPIENT_NAME' => $listing['owner_login'],
		  'RECIPIENT_EMAIL' => $listing['owner_email']
	  );
	  $notification = new notificationSender('Listing expiration');
	  $notification->send($event_params);
	  events::callEvent('Listing expiration', $event_params);

	  // Clean cache
	  $this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_counts', 'listings_' . $listing['id']));
	}

	if (!empty($ids)) {
	  $this->db->set('status', 3);
	  $this->db->where_in('id', $ids);
	  return $this->db->update('listings');
	}
  }

  // --------------------------------------------------------------------------------------------
  // Claiming methods
  // --------------------------------------------------------------------------------------------
  public function getClaimRow($listing_id) {
	$this->db->select();
	$this->db->from('listings_claims');
	$this->db->where('listing_id', $listing_id);
	return $this->db->get()->row_array();
  }

  public function setClaim($claim_row_id) {
	$this->db->set('ability_to_claim', 0);
	$this->db->set('to_user_id', $this->session->userdata('user_id'));
	$this->db->where('id', $claim_row_id);
	return $this->db->update('listings_claims');
  }

  public function approveClaim($listing_id) {
	$this->db->set('approved', 1);
	$this->db->set('ability_to_claim', 0);
	$this->db->where('listing_id', $listing_id);
	return $this->db->update('listings_claims');
  }

  public function declineClaim($listing_id) {
	$this->db->set('approved', 0);
	$this->db->set('ability_to_claim', 1);
	$this->db->set('to_user_id', 0);
	$this->db->where('listing_id', $listing_id);
	return $this->db->update('listings_claims');
  }

  public function delegateListingToUser($listing_id, $user_id) {
	$this->db->set('owner_id', $user_id);
	$this->db->where('id', $listing_id);
	return $this->db->update('listings');
  }

  public function rollBackListingFromUser($listing_id, $initial_user_id) {
	$this->db->set('owner_id', $initial_user_id);
	$this->db->where('id', $listing_id);
	return $this->db->update('listings');
  }

  // --------------------------------------------------------------------------------------------
}

?>