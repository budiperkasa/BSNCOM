<?php
include_once(MODULES_PATH . 'locations_predefined/classes/locations_level.class.php');
include_once(MODULES_PATH . 'locations_predefined/classes/predefined_location.class.php');
include_once(MODULES_PATH . 'google_maps/classes/location_geoname.class.php');

class locationsModel extends model
{
    private $locations_db;
    private $_loc_level_id;
    private $_location_id;
    
    // --------------------------------------------------------------------------------------------
    // locations levels methods
    // --------------------------------------------------------------------------------------------
    public function setLocationsLevelId($level_id)
    {
    	$this->_loc_level_id = $level_id;
    }

    public function selectAllLevels()
    {
        $this->db->select('id');
        $this->db->select('order_num');
        $this->db->select('name');
        $this->db->from('locations_levels');
        $this->db->order_by('order_num');
		$result = $this->db->get()->result_array();
		
		$locations_levels = array();
		foreach ($result AS $row) {
			$locations_level = new locations_level;
			$locations_level->setLocationsLevelFromArray($row);
			$locations_levels[] = $locations_level;
		}
		return $locations_levels;
    }
    
    public function getLocationsLevelsCount()
    {
    	$this->db->select('count(*) AS levels_count');
        $this->db->from('locations_levels');
		$query = $this->db->get();
		$row = $query->row_array();

        return $row['levels_count'];
    }
    
    /**
     * saves the order of locations levels by its weight
     *
     * @param string $serialized_order
     */
    public function setLocLevelsOrder($serialized_order)
    {
    	$a = explode("=", $serialized_order);
    	$start = 1;
    	foreach ($a AS $row) {
    		$b = explode("&", $row);
    		foreach ($b AS $id) {
    			$id = trim($id, "_id");
    			if (is_numeric($id)) {
    				$this->db->set('order_num', $start++);
    				$this->db->where('id', $id);
    				$this->db->update('locations_levels');
    			}
    		}
    	}
    }
    
    /**
     * is there locations level with such name in the DB?
     *
     * @param string $name
     */
    public function is_loc_level_name($name)
    {
    	$this->db->select();
		$this->db->from('locations_levels');
		$this->db->where('name', $name);
		if (!is_null($this->_loc_level_id)) {
			$this->db->where('id !=', $this->_loc_level_id);
		}
		$query = $this->db->get();

		return $query->num_rows();
    }
    
    public function getNewLocationsLevel()
    {
    	$locations_level = new locations_level;
    	return $locations_level;
    }
    
    public function saveLocationsLevel($form_result)
    {
    	$this->db->select_max('order_num');
    	$query = $this->db->get('locations_levels');
    	if ($row = $query->row())
    		$order_num = $row->order_num + 1;
    	else 
    		$order_num = 1;
    	
        $this->db->set('name', $form_result['name']);
        $this->db->set('order_num', $order_num);
        if ($this->db->insert('locations_levels')) {
        	$system_settings = registry::get('system_settings');
        	if (@$system_settings['language_areas_enabled']) {
        		translations::saveTranslations(array('locations_levels', 'name', $this->db->insert_id()));
        	}
        	return true;
        }
    }
    
    public function getLocationsLevelFromForm($form_result)
    {
		$locations_level = new locations_level;
		$locations_level->setLocationsLevelFromArray($form_result);

        return $locations_level;
    }
    
    public function getLocationsLevelById()
    {
    	$this->db->select();
        $this->db->where('id', $this->_loc_level_id);
        $query = $this->db->get('locations_levels');

        if ($query->num_rows()) {
            $locations_level = new locations_level;
            $locations_level->setLocationsLevelFromArray($query->row_array());
            return $locations_level;
        } else {
            return false;
        }
    }
    
    public function saveLocationsLevelById($form_result)
    {
    	$this->db->set('name', $form_result['name']);
        $this->db->where('id', $this->_loc_level_id);
        return $this->db->update('locations_levels');
    }
    
    public function deleteLocationsLevelById()
    {
    	$this->db->where('id', $this->_loc_level_id);
        $this->db->delete('locations_levels');

    	// Update all levels weights after delete
    	$loc_levels = $this->selectAllLevels();
    	$count = 1;
    	foreach ($loc_levels AS $level) {
    		$this->db->set('order_num', $count);
    		$this->db->where('id', $level->id);
    		$this->db->update('locations_levels');
    		$count++;
    	}

    	// Delete unnecessary locations after appropriate levels were deleted
        $locations_db = $this->selectLocationsFromDB();

        $level_max = count($loc_levels);
        
        $locations_to_delete = array();
        $curr_level = 1;
        for ($i = 0; $i < count($locations_db); $i++) {
            if ($locations_db[$i]['parent_id'] == 0) {
                if ($curr_level > $level_max) {
                    $locations_to_delete[] = $locations_db[$i]['id'];
                }
                $this->recursiveLocationsToDeleteFind($locations_db, $i, $curr_level, $level_max, $locations_to_delete);
            }
        }

        if (!empty($locations_to_delete)) {
            $this->db->where_in('id', $locations_to_delete);
            $this->db->delete('locations');
        }
        return true;
    }
    
    public function recursiveLocationsToDeleteFind($locations_db, $i, &$curr_level, $level_max, &$locations_to_delete)
    {
        $this_level = $curr_level;
        $this_level++;
        for ($j = 0; $j < count($locations_db); $j++) {
            if ($locations_db[$j]['parent_id'] == $locations_db[$i]['id']) {
                if ($this_level > $level_max) {
                    $locations_to_delete[] = $locations_db[$j]['id'];
                }
                $this->recursiveLocationsToDeleteFind($locations_db, $j, $this_level, $level_max, $locations_to_delete);
            }
        }
    }
    
    // --------------------------------------------------------------------------------------------
    // Admin Locations functions
    // --------------------------------------------------------------------------------------------
    public function setLocationId($location_id)
    {
    	$this->_location_id = $location_id;
    }

    public function selectLocationsFromDB($return_objects = false, $is_only_labeled = false)
    {
    	$CI = &get_instance();
    	$cache_index = 'locations_' . $return_objects . '_' . $is_only_labeled;
    	if (!$cache = $CI->cache->load($cache_index)) {
			// Select each column separately because there may be error when language areas enabled,
	    	// order by statement doesn't auto-rewrite when we select from another language
	        $this->db->select('id');
	        $this->db->select('parent_id');
	        $this->db->select('tree_path');
	        $this->db->select('use_as_label');
	        $this->db->select('name');
	        $this->db->select('seo_name');
	        $this->db->select('geocoded_name');
			$this->db->from('locations');
	        $this->db->order_by('name');
	        if ($is_only_labeled)
	        	$this->db->where('use_as_label', true);
	        $this->locations_db = $this->db->get()->result_array();

	        $locations_objects = array();
		    if ($return_objects) {
		    	foreach ($this->locations_db AS $location_array) {
		    		$location = new predefinedLocation;
		    		$location->setLocationFromArray($location_array);
		    		$locations_objects[] = $location;
		    	}
		    }
    		$CI->cache->save(array($this->locations_db, $locations_objects), $cache_index, array('locations'));
	    } else {
	    	list($this->locations_db, $locations_objects) = $cache;
	    }

	    if ($return_objects) {
	    	return $locations_objects;
	    } else
    		return $this->locations_db;
    }
    
	public function labelLocationById($location_id = null)
    {
    	if (is_null($location_id)) {
    		$location_id = $this->_location_id;
    	}

    	$this->db->select('use_as_label');
    	$this->db->where('id', $location_id);
    	$row = $this->db->get('locations')->row_array();

    	if ($row['use_as_label'])
    		$this->db->set('use_as_label', false);
    	else
    		$this->db->set('use_as_label', true);
    	$this->db->where('id', $location_id);
    	$this->db->update('locations');
    	if (!$row['use_as_label']) {
    		// ALL parent locations also MUST be labeled
		   	if ($chain_array = $this->getLocationsChainFromId($location_id)) {
		   		array_shift($chain_array);
		   		foreach ($chain_array AS $parent_location) {
		   			if (!$parent_location->use_as_label) {
		   				$this->db->set('use_as_label', 1);
		   				$this->db->where('id', $parent_location->id);
	    				$this->db->update('locations');
		   			}
		   		}
		   	}
    	} else {
    		// We can't unlabel location if it has even one labeled child
    		if ($children = $this->getAllChildrenOfLocation($location_id)) {
    			foreach ($children AS $child_location) {
    				if ($child_location->use_as_label) {
    					$this->db->set('use_as_label', 1);
				    	$this->db->where('id', $location_id);
				    	$this->db->update('locations');
				    	return false;
				    	break;
    				}
    			}
    		}
    	}
    	return true;
    }
    
	public function labelLocations($list)
    {
		$a = array_unique(explode('*', $list));
		$res = true;
		foreach ($a as $id) {
			if (!empty($id)) {
				if (!$this->labelLocationById($id))
					$res = false;
			}
		}
		return $res;
    }

    /**
     * builds tree of locations
     * 
     * @param array $locations_array
     * @param array $locations_tree
     * @param int $from_location_id
     * @param int/string $max_depth
     */
    /*public function buildLocationsTree($locations_array, &$locations_tree, $from_location_id = 0, $max_depth = 'max')
    {
    	foreach ($locations_array AS $location_row) {
			if ($location_row['parent_id'] == $from_location_id) {
				$location = new predefinedLocation;
				$location->setLocationFromArray($location_row);
				$location->buildChildren($locations_array, $max_depth);
				$locations_tree[] = $location;
			}
		}
    }*/
    
    public function saveLocations($list)
    {
        $values = array();
        $a = explode('*', $list);
        foreach ($a as $v) {
            if (!empty($v)) {
                $item = str_replace(']', '', str_replace('[', '', $v));
                $b = explode('-', $item);
                if (count($b) == 1) {
                    $id = $b[0];
                    $parent_id = 0;
                } else {
                    $id = $b[1];
                    $parent_id = $b[0];
                }

                $values[$id] = $parent_id;
            }
        }
        if (count($values)) {
        	foreach ($values AS $id=>$parent_id) {
        		$this->db->set('parent_id', $parent_id);
        		$this->db->where('id', $id);
        		$this->db->update('locations');
        	}
        	$locations_db = $this->selectLocationsFromDB();
        	foreach ($values AS $id=>$parent_id) {
        		$this->db->select('id');
        		$this->db->select('parent_id');
        		$this->db->from('locations');
        		$this->db->like('tree_path', '-'.$id, 'both');
        		$result = $this->db->get()->result_array();
        		
        		foreach ($result AS $location_row) {
        			$path = array();
					$path[] = $location_row['id'];
					$this->getLocationsTreePathRecursively($location_row['parent_id'], $locations_db, $path);
					$path[] = 0;
					$path = array_reverse($path);
					$path_string = '-'.implode('-', $path);

					// --------------------------------------------------------------------------------------------
					// Geocode and resave listings_in_locations
					// --------------------------------------------------------------------------------------------
					$location = $this->getLocationById($location_row['id']);
			    	$geocoder = new locationGeoname;
			    	if ($geocoded_name = $geocoder->geonames_request($location->getChainAsString())) {
			    		$this->db->set('geocoded_name', $geocoded_name);
			    	}
			    	$this->db->set('tree_path', $path_string);
					$this->db->where('id', $location_row['id']);
					$this->db->update('locations');
					
					if ($geocoded_name) {
						$this->db->set('geocoded_name', $geocoded_name);
				    	$this->db->where('predefined_location_id', $location_row['id']);
				    	$this->db->update('listings_in_locations');
					}
					// --------------------------------------------------------------------------------------------
        		}
        	}
        }
        return true;
    }
    
    public function deleteLocations($list)
    {
        $a = explode('*', $list);
        foreach ($a as $id) {
            if (!empty($id)) {
                $this->deleteLocationById($id);
            }
        }
        return true;
    }

    public function getNewLocation()
    {
    	$location = new predefinedLocation;
    	return $location;
    }
    
    public function getLocationFromForm($form)
    {
    	$location = new predefinedLocation;
    	$location->setLocationFromArray($form);
    	return $location;
    }
    
    public function is_location_seoname($seo_name)
    {
    	$this->db->select();
		$this->db->from('locations');
		$this->db->where('seo_name', $seo_name);
		if (!is_null($this->_location_id)) {
			$this->db->where('id !=', $this->_location_id);
		}
		$query = $this->db->get();

		return $query->num_rows();
    }

    public function saveLocation($form, $parent_id = 0)
    {
    	$this->db->set('parent_id', $parent_id);
    	$this->db->set('name', $form['name']);
    	$this->db->set('seo_name', $form['seo_name']);
    	if (isset($form['geocoded_name']))
    		$this->db->set('geocoded_name', $form['geocoded_name']);
    	if ($this->db->insert('locations')) {
    		$new_id = $this->db->insert_id();

    		$this->db->select('id');
    		$this->db->select('name');
	        $this->db->select('parent_id');
			$this->db->from('locations');
	        $locations_db = $this->db->get()->result_array();

	        $path = array();
			$path[] = $new_id;
			$this->getLocationsTreePathRecursively($parent_id, $locations_db, $path);
			$path[] = 0;
			$path = array_reverse($path);
			$path_string = '-'.implode('-', $path);

			// --------------------------------------------------------------------------------------------
			// Geocode and resave listings_in_locations
			// --------------------------------------------------------------------------------------------
			$locations = array();
			foreach ($path AS $id) {
				foreach ($locations_db AS $location_row) {
					if ($location_row['id'] == $id) {
						$locations[] = $location_row['name'];
					}
				}
			}
			if (!isset($form['geocoded_name']) || !($geocoded_name = $form['geocoded_name'])) {
	    		$geocoder = new locationGeoname;
		    	if ($geocoded_name = $geocoder->geonames_request(implode(', ', $locations))) {
		    		$this->db->set('geocoded_name', $geocoded_name);
		    	} else {
		    		var_dump(implode(', ', $locations));
		    	}
			}
	    	$this->db->set('tree_path', $path_string);
			$this->db->where('id', $new_id);
			$this->db->update('locations');
			
			if ($geocoded_name) {
				$this->db->set('geocoded_name', $geocoded_name);
		    	$this->db->where('predefined_location_id', $new_id);
		    	$this->db->update('listings_in_locations');
			}
			// --------------------------------------------------------------------------------------------

        	$system_settings = registry::get('system_settings');
        	if (@$system_settings['language_areas_enabled']) {
        		translations::saveTranslations(array('locations', 'name', $new_id));
        	}
        	return $new_id;
    	}
    }
    
    public function getLocationsTreePathRecursively($parent_id, $locations_array, &$path)
	{
		foreach ($locations_array AS $location_row) {
			if ($location_row['id'] == $parent_id) {
				$path[] = $location_row['id'];
				$this->getLocationsTreePathRecursively($location_row['parent_id'], $locations_array, $path);
				break;
			}
		}
	}
    
    public function getLocationById($location_id = null)
    {
    	if (is_null($location_id)) {
    		$location_id = $this->_location_id;
    	}
    	
    	$this->db->select();
    	$this->db->from('locations');
    	$this->db->where('id', $location_id);
    	$query = $this->db->get();

    	if ($query->num_rows()) {
	    	$location = new predefinedLocation;
	    	$location->setLocationFromArray($query->row_array());
	    	return $location;
    	}
    }
    
    public function saveLocationById($form)
    {
    	$this->db->set('name', $form['name']);
    	$this->db->set('seo_name', $form['seo_name']);
    	$this->db->set('geocoded_name', $form['geocoded_name']);
    	$this->db->where('id', $this->_location_id);
    	if ($this->db->update('locations')) {
    		// --------------------------------------------------------------------------------------------
			// Geocode and resave listings_in_locations
			// --------------------------------------------------------------------------------------------
			$location = $this->getLocationById($this->_location_id);

    		if (!($geocoded_name = $form['geocoded_name'])) {
	    		$geocoder = new locationGeoname;
		    	if ($geocoded_name = $geocoder->geonames_request($form['name'])) {
		    		$this->db->set('geocoded_name', $geocoded_name);
			    	$this->db->where('predefined_location_id', $this->_location_id);
			    	$this->db->update('listings_in_locations');
		    	
			    	$this->db->set('geocoded_name', $geocoded_name);
			    	$this->db->where('id', $this->_location_id);
			    	$this->db->update('locations');
		    	}
		    }
	    	// --------------------------------------------------------------------------------------------
	    	return true;
    	}
    }
    
    public function deleteLocationById($location_id = null)
    {
    	if (is_null($location_id)) {
    		$location_id = $this->_location_id;
    	}
    	
    	$locations_db = $this->selectLocationsFromDB();
    	
    	foreach ($locations_db AS $location_row) {
    		if ($location_row['parent_id'] == $location_id) {
    			$this->deleteLocationById($location_row['id']);
    		}
    	}
    	
    	if ($location = $this->getLocationById($location_id)) {
    		$this->db->set('predefined_location_id', 0);
	    	$this->db->set('location', $location->geocoded_name);
	    	$this->db->set('use_predefined_locations', false);
	    	$this->db->where('predefined_location_id', $location_id);
	    	$this->db->where('use_predefined_locations', true);
	    	$this->db->update('listings_in_locations');
    	}
    	
    	$this->db->where('id', $location_id);
    	return $this->db->delete('locations');
    }

    public function getLocationBySeoName($seo_name)
    {
    	$this->db->select();
		$this->db->from('locations');
        $this->db->where('seo_name', $seo_name);
        $query = $this->db->get();
        
        if ($query->num_rows()) {
        	$row = $query->row_array();
        	$location = new predefinedLocation;
        	$location->setLocationFromArray($row);
        	return $location;
        } else 
        	return false;
    }
    
	public function getLocationByGeocodedName($geocoded_name)
    {
    	$this->db->select();
		$this->db->from('locations');
        $this->db->where('geocoded_name', $geocoded_name);
        $query = $this->db->get();
        
        if ($query->num_rows()) {
        	$row = $query->row_array();
        	$location = new predefinedLocation;
        	$location->setLocationFromArray($row);
        	return $location;
        } else 
        	return false;
    }

    /**
     * will return a chain of locations from this to root
     *
     * @param int $parent_id
     * @return array
     */
    public function getLocationsChainFromId($parent_id)
    {
    	// Explode path and remove root level
    	$parent_location_obj = $this->getLocationById($parent_id);
    	$parent_locations = array_reverse(explode('-', $parent_location_obj->tree_path));
    	

    	$selected_locations_chain = array();
		foreach ($parent_locations as $location_id) {
			if ($location_id != 0)
				$selected_locations_chain[] = $this->getLocationById($location_id);
		}
		return $selected_locations_chain;
    }

    /**
     * Select locations that are on the same level as $parent_id
     *
     * @param int $parent_id
     * @param int $location_id - this location may be set as 'selected'
     * @return array
     */
    public function selectAllSisters($parent_id, $location_id = null)
    {
    	$locations_db = $this->selectLocationsFromDB();
    	
    	$locations_array = array();
    	foreach ($locations_db as $location) {
			if ($parent_id == $location['parent_id']) {
				$predefined_location = new predefinedLocation;
				$predefined_location->setLocationFromArray($location);
				if ($location_id == $predefined_location->id) {
					$predefined_location->setSelected();
				}
				$locations_array[] = $predefined_location;
			}
		}
		return $locations_array;
    }

    /**
     * It builds multi-dimention array of locations' geocoded names (from "listings in locations" table), like:
     * array([Usa] =>
     *   array([California] =>
     *     array([Los Angeles] => null,
     *           [Fresno]      => null
     *     )
     *   )
     * )
     *  then saves this array as the tree into "locations" table (if this part of tree doesn't existed yet + also re-GeoCode locations),
     *  then we re-GeoCode locations in "listings in locations" table and set new predefined location ID,
     *  in other words it takes global locations from "listings in locations" table and puts them into "locations" table,
     *  synchronizing it
     */
    public function synchronize_locations()
    {
    	$locations_levels_count = $this->getLocationsLevelsCount();

    	$this->db->distinct();
    	$this->db->select('geocoded_name');
    	$this->db->from('listings_in_locations');
    	$this->db->where('geocoded_name !=', '');
    	$array = $this->db->get()->result_array();
    	$tree_array = array();
    	foreach ($array AS $row) {
    		$row_array = explode(', ', $row['geocoded_name']);
    		$this->buildLocationsTreeRecursively(0, array_reverse($row_array), $tree_array);
    	}
    	$this->saveLocationsTreeRecursively($locations_levels_count, 0, $tree_array);

    	return true;
    }
    
    private function buildLocationsTreeRecursively($i, $row_array, &$tree)
    {
    	if (isset($row_array[$i]) && !isset($tree[$row_array[$i]])) {
    		$tree[$row_array[$i]] = null;
    	}
    	if ($i<count($row_array)) {
    		$this->buildLocationsTreeRecursively($i+1, $row_array, $tree[$row_array[$i]]);
    	}
    }
    
    public function saveLocationsTreeRecursively($locations_levels_count, $parent_location_id, $tree, &$orig_row_array = array())
    {
    	if (is_array($tree)) {
    		$CI = &get_instance();

    		foreach ($tree AS $name=>$thread) {
    			$row_array = $orig_row_array;
	    		$row_array[] = $name;
	    		
	    		if (count($row_array) > $locations_levels_count) {
	    			$geocoded_name_array = array_slice($row_array, 0, $locations_levels_count-1);
	    			$geocoded_name_array[] = end($row_array);
	    		} else {
	    			$geocoded_name_array = $row_array;
	    		}
	    		
	    		//$form['name'] = $name;
				$form['name'] = end($geocoded_name_array);

	    		$form['seo_name'] = friendly_seo_string($name);
	    		if (!($existed_location_id = $this->isGeoLocation(array_reverse($geocoded_name_array)))) {
	    			if ($this->is_location_seoname($form['seo_name'])) {
		    			$x = 1;
		    			while ($x) {
		    				if ($this->is_location_seoname($form['seo_name'].'-'.$x)) $x++;
							else { $form['seo_name'] = $form['seo_name'].'-'.$x; $x=false; }
		    			}
	    			}
					$new_location_id = $this->saveLocation($form, $parent_location_id);
					if (is_array($thread)) {
		    			$this->saveLocationsTreeRecursively($locations_levels_count, $new_location_id, $thread, $row_array);
		    		}
		    		$existed_location_id = $new_location_id;
	    		}
	    		$this->setPredefinedLocationIdInListingsLocations(array_reverse($geocoded_name_array), $existed_location_id);
	    		if (is_array($thread)) {
	    			$this->saveLocationsTreeRecursively($locations_levels_count, $existed_location_id, $thread, $row_array);
	    			continue;
	    		}
    		}

		    //$this->reGeoCodeListingsLocation(implode(', ', array_reverse($row_array)), $existed_location_id);
    	}
    }
    
    /**
     * Is there such geocoded location in DB
     *
     * @param array/string $name_array
     * @return false or existed location's ID
     */
    public function isGeoLocation($geo_name)
    {
    	if (is_array($geo_name)) {
    		$geo_name = implode(', ', $geo_name);
    	}
    	
    	$this->db->select('id');
    	$this->db->from('locations');
    	$this->db->where('geocoded_name', $geo_name);
    	$query = $this->db->get();
    	if ($query->num_rows()) {
    		$row = $query->row_array();
    		return $row['id'];
    	} else {
    		var_dump($geo_name);
    		return false;
    	}
    }
    
    /**
     * sets new predefined location ID into "listings in locations" table by its GeoCoded name
     *
     * @param array/string $geo_name
     * @param int $predefined_location_id
     */
    public function setPredefinedLocationIdInListingsLocations($geo_name, $predefined_location_id)
    {
    	if (is_array($geo_name)) {
    		$geo_name = implode(', ', $geo_name);
    	}
    	
    	$this->db->set('predefined_location_id', $predefined_location_id);
    	$this->db->where('geocoded_name', $geo_name);
    	return $this->db->update('listings_in_locations');
    }
    
    /**
     * re-GeoCode location in "locations" and in "listings in locations" tables
     *
     * @param string $lic_geocoded_name - old geocoded name
     * @return bool
     */
    public function reGeoCodeLocation($old_geocoded_name)
    {
    	// --------------------------------------------------------------------------------------------
		// Geocode location
		// --------------------------------------------------------------------------------------------
    	$geocoder = new locationGeoname;
    	if (!($new_geocoded_name = $geocoder->geonames_request($old_geocoded_name))) {
    		return false;
    	}
    	// --------------------------------------------------------------------------------------------

    	// update "listings_in_locations" table
    	$this->db->set('geocoded_name', $new_geocoded_name);
    	$this->db->where('geocoded_name', $old_geocoded_name);
    	$this->db->update('listings_in_locations');
    	
    	// update "locations" table
    	$this->db->set('geocoded_name', $new_geocoded_name);
    	$this->db->where('geocoded_name', $old_geocoded_name);
    	$this->db->update('locations');
    }
    
    /**
     * re-GeoCode location in "listings in locations" table (+ set new predefined location ID, if not null)
     *
     * @param string $old_geocoded_name - old geocoded name
     * @param int $location_id
     * @return bool
     */
    public function reGeoCodeListingsLocation($old_geocoded_name, $location_id = null)
    {
    	// --------------------------------------------------------------------------------------------
		// Geocode location
		// --------------------------------------------------------------------------------------------
    	$geocoder = new locationGeoname;
    	if (!($new_geocoded_name = $geocoder->geonames_request($old_geocoded_name))) {
    		if (is_null($location_id))
    			return false;
    	}
    	// --------------------------------------------------------------------------------------------
    	
    	$this->db->set('geocoded_name', $new_geocoded_name);
    	if (!is_null($location_id))
    		$this->db->set('predefined_location_id', $location_id);
    	$this->db->where('geocoded_name', $old_geocoded_name);
    	return $this->db->update('listings_in_locations');
    }
    
    /**
     * Get absolutely ALL children of location
     * @param id/obj/seoname $location
     */
    public function getAllChildrenOfLocation($location, $is_only_labeled = false)
    {
    	if (is_numeric($location)) {
    		// By ID
   			$location_obj = $this->getLocationById($location);
   		} elseif (is_object($location))
   			// This is ready object
   			$location_obj = $location;
   		else {
   			// By seo name
   			$location_obj = $this->getLocationBySeoName($location);
   		}
   		
		$this->db->select('id');
		$this->db->select('parent_id');
		$this->db->select('tree_path');
		$this->db->select('use_as_label');
		$this->db->select('name');
		$this->db->select('seo_name');
		$this->db->select('geocoded_name');
   		$this->db->from('locations');
   		$this->db->like('tree_path', $location_obj->tree_path . '-', 'after');
   		if ($is_only_labeled)
   			$this->db->where('use_as_label', true);
   		$this->db->orderby('name');
   		$query = $this->db->get();
   		$result = $query->result_array();
   		
   		$locations = array();
   		foreach ($result AS $row) {
	   		$location = new predefinedLocation;
		    $location->setLocationFromArray($row);
		    $locations[] = $location;
   		}
   		return $locations;
    }
    
/**
     * Get only direct children of location
     * @param id/obj/seoname $location (if 0 - this is root)
     */
    public function getDirectChildrenOfLocation($location, $is_only_labeled = false)
    {
    	if (is_numeric($location)) {
    		// By ID
   			$location_id = $location;
   		} elseif (is_object($location))
   			// This is ready object
   			$location_id = $location->id;
   		else {
   			// By seo name
   			$location_id = $this->getLocationBySeoName($location)->id;
   		}
   		
		$this->db->select('id');
		$this->db->select('parent_id');
		$this->db->select('tree_path');
		$this->db->select('use_as_label');
		$this->db->select('name');
		$this->db->select('seo_name');
		$this->db->select('geocoded_name');
   		$this->db->from('locations');
   		$this->db->where('parent_id', $location_id);
   		if ($is_only_labeled)
   			$this->db->where('use_as_label', true);
   		$this->db->orderby('name');
   		$query = $this->db->get();
   		$result = $query->result_array();
   		
   		$locations = array();
   		foreach ($result AS $row) {
	   		$location = new predefinedLocation;
		    $location->setLocationFromArray($row);
		    $locations[] = $location;
   		}
   		return $locations;
    }
    
    /**
     *  we will re-GeoCode locations if districts or provinces modes were changed
     *
     */
    public function reGeoCodeAllLocations()
    {
    	$locations = array();
    	
    	$this->db->distinct();
    	$this->db->select('loc.geocoded_name');
    	$this->db->from('locations AS loc');
    	$result = $this->db->get()->result_array();
    	foreach ($result AS $row) {
   			$locations[] = $row['geocoded_name'];
   		}
    	
    	$this->db->distinct();
    	$this->db->select('lil.geocoded_name');
    	$this->db->from('listings_in_locations AS lil');
    	$result = $this->db->get()->result_array();
    	foreach ($result AS $row) {
   			$locations[] = $row['geocoded_name'];
   		}
    	
   		$locations = array_unique($locations);
   		foreach ($locations AS $location_geoname) {
   			$this->reGeoCodeLocation($location_geoname);
   		}
   		return true;
    }
    
    public function setPredefinedLocationsModeForListings($mode)
    {
    	$this->db->set('use_predefined_locations', $mode);
    	$this->db->update('listings_in_locations');
    }
}
?>