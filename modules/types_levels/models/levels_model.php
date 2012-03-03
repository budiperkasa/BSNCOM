<?php
include_once(MODULES_PATH . 'types_levels/classes/level.class.php');

class levelsModel extends model
{
    private $_type_id;
    private $_level_id;
    private $_cached_levels_objects = array();

    public function setTypeId($type_id)
    {
        $this->_type_id = $type_id;
    }

    public function setLevelId($level_id)
    {
        $this->_level_id = $level_id;
    }

    /**
     * saves the order of levels by its weight
     *
     * @param string $serialized_order
     */
    public function setLevelsOrder($serialized_order)
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
    				$this->db->update('levels');
    			}
    		}
    	}
    	return true;
    }

    public function getNewLevel()
    {
		$level = new levelClass;
        $level->type_id = $this->_type_id;
        return $level;
    }
    
    public function getLevelFromForm($form_result)
    {
		$level = new levelClass;

        foreach ($form_result as $key => $value) {
            $level->$key = $value;
        }

        $level->logo_size = $form_result['logo_width'] . '*' . $form_result['logo_height'];
        $level->images_size = $form_result['images_width'] . '*' . $form_result['images_height'];
        $level->images_thumbnail_size = $form_result['images_thumbnail_width'] . '*' . $form_result['images_thumbnail_height'];
        $level->video_size = $form_result['video_width'] . '*' . $form_result['video_height'];
        $level->maps_size = $form_result['maps_width'] . '*' . $form_result['maps_height'];

        return $level;
    }
    
    /**
     * is there level with such name in the DB?
     *
     * @param string $name
     */
    public function is_level_name($name)
    {
    	$this->db->select();
		$this->db->from('levels');
		$this->db->where('name', $name);
		$this->db->where('type_id', $this->_type_id);
		if (!is_null($this->_level_id)) {
			$this->db->where('id !=', $this->_level_id);
		}
		$query = $this->db->get();

		return $query->num_rows();
    }

    public function saveLevel($type, $form_result)
    {
    	$this->db->select_max('order_num');
    	$this->db->where('type_id', $this->_type_id);
    	$query = $this->db->get('levels');
    	if ($row = $query->row())
    		$order_num = $row->order_num + 1;
    	else 
    		$order_num = 1;
    	
        $this->db->set('type_id', $this->_type_id);
        $this->db->set('name', $form_result['name']);
        $this->db->set('order_num', $order_num);
        if (!$form_result['eternal_active_period']) {
	        $this->db->set('active_years', $form_result['active_years']);
	        $this->db->set('active_months', $form_result['active_months']);
	        $this->db->set('active_days', $form_result['active_days']);
    	} else {
    		$this->db->set('active_years', 0);
	        $this->db->set('active_months', 0);
	        $this->db->set('active_days', 0);
    	}
    	$this->db->set('allow_to_edit_active_period', $form_result['allow_to_edit_active_period']);
        $this->db->set('featured', $form_result['featured']);
        $this->db->set('title_enabled', $form_result['title_enabled']);
        $this->db->set('seo_title_enabled', $form_result['seo_title_enabled']);
        $this->db->set('meta_enabled', $form_result['meta_enabled']);
        $this->db->set('description_mode', $form_result['description_mode']);
        if ($form_result['description_mode'] == 'enabled')
        	$this->db->set('description_length', $form_result['description_length']);
        if ($type->categories_type != 'disabled')
        	$this->db->set('categories_number', $form_result['categories_number']);
        if ($type->locations_enabled)
        	$this->db->set('locations_number', $form_result['locations_number']);
        $this->db->set('preapproved_mode', $form_result['preapproved_mode']);
        $this->db->set('logo_enabled', $form_result['logo_enabled']);
        $this->db->set('logo_size', $form_result['logo_width'] . '*' . $form_result['logo_height']);
        $this->db->set('images_count', $form_result['images_count']);
        $this->db->set('images_size', $form_result['images_width'] . '*' . $form_result['images_height']);
        $this->db->set('images_thumbnail_size', $form_result['images_thumbnail_width'] . '*' . $form_result['images_thumbnail_height']);
        $this->db->set('video_count', $form_result['video_count']);
        $this->db->set('video_size', $form_result['video_width'] . '*' . $form_result['video_height']);
        $this->db->set('files_count', $form_result['files_count']);
        if ($type->locations_enabled) {
	        $this->db->set('maps_enabled', $form_result['maps_enabled']);
	        $this->db->set('maps_size', $form_result['maps_width'] . '*' . $form_result['maps_height']);
        }
        $this->db->set('option_print', $form_result['option_print']);
        $this->db->set('option_pdf', $form_result['option_pdf']);
        $this->db->set('option_quick_list', $form_result['option_quick_list']);
        $this->db->set('option_email_friend', $form_result['option_email_friend']);
        $this->db->set('option_email_owner', $form_result['option_email_owner']);
        $this->db->set('option_report', $form_result['option_report']);
        $this->db->set('social_bookmarks_enabled', $form_result['social_bookmarks_enabled']);
        $this->db->set('ratings_enabled', $form_result['ratings_enabled']);
        $this->db->set('reviews_mode', $form_result['reviews_mode']);
        if ($form_result['reviews_mode'] != 'disabled')
        	$this->db->set('reviews_richtext_enabled', $form_result['reviews_richtext_enabled']);

        if ($this->db->insert('levels')) {
        	$level_id = $this->db->insert_id();
        	
        	$CI = &get_instance();
        	$CI->load->model('users_groups', 'users');
        	$users_groups = $CI->users_groups->getUsersGroups();
        	foreach ($users_groups AS $users_group) {
        		$this->db->set('group_id', $users_group->id);
	    		$this->db->set('objects_name', 'levels');
	    		$this->db->set('object_id', $level_id);
	    		$this->db->insert('users_groups_content_permissions');
        	}

        	// Create content fields group
        	$this->db->set('name', LANG_LISTINGS_LEVEL_GROUP_CUSTOM_NAME_1 . ' "' . $form_result['name'] . '" ' . LANG_LISTINGS_LEVEL_GROUP_CUSTOM_NAME_2 . ' "' . $type->name . '"');
	        $this->db->set('custom_name', LISTINGS_LEVEL_GROUP_CUSTOM_NAME);
	        $this->db->set('custom_id', $level_id);
	        $this->db->insert('content_fields_groups');
	        
        	$system_settings = registry::get('system_settings');
        	if (@$system_settings['language_areas_enabled']) {
        		translations::saveTranslations(array('levels', 'name', $level_id));
        	}
        	return true;
        }
    }
    
    public function getLevelById($level_id = null)
    {
    	if (is_null($level_id))
    		$level_id = $this->_level_id;
    	
    	if (isset($this->_cached_levels_objects[$level_id]))
    		return $this->_cached_levels_objects[$level_id];
    	else {
	        $this->db->select();
	        $this->db->from('levels');
	        $this->db->where('id', $level_id);
	        $query = $this->db->get();
	
	        if ($query->num_rows()) {
				$level = new levelClass;
				$level->setLevelFromArray($query->row_array());
				$this->_cached_levels_objects[$level_id] = $level;
				return $level;
	        } else {
	        	return false;
	        }
    	}
    }

    public function saveLevelById($type, $form_result)
    {
        $this->db->set('name', $form_result['name']);
        if (!$form_result['eternal_active_period']) {
	        $this->db->set('active_years', $form_result['active_years']);
	        $this->db->set('active_months', $form_result['active_months']);
	        $this->db->set('active_days', $form_result['active_days']);
    	} else {
    		$this->db->set('active_years', 0);
	        $this->db->set('active_months', 0);
	        $this->db->set('active_days', 0);
    	}
    	$this->db->set('allow_to_edit_active_period', $form_result['allow_to_edit_active_period']);
        $this->db->set('featured', $form_result['featured']);
        $this->db->set('title_enabled', $form_result['title_enabled']);
        $this->db->set('seo_title_enabled', $form_result['seo_title_enabled']);
        $this->db->set('meta_enabled', $form_result['meta_enabled']);
        $this->db->set('description_mode', $form_result['description_mode']);
        $this->db->set('description_length', $form_result['description_length']);
        if ($type->categories_type != 'disabled')
        	$this->db->set('categories_number', $form_result['categories_number']);
        if ($type->locations_enabled)
        	$this->db->set('locations_number', $form_result['locations_number']);
        $this->db->set('preapproved_mode', $form_result['preapproved_mode']);
        $this->db->set('logo_enabled', $form_result['logo_enabled']);
        $this->db->set('logo_size', $form_result['logo_width'] . '*' . $form_result['logo_height']);
        $this->db->set('images_count', $form_result['images_count']);
        $this->db->set('images_size', $form_result['images_width'] . '*' . $form_result['images_height']);
        $this->db->set('images_thumbnail_size', $form_result['images_thumbnail_width'] . '*' . $form_result['images_thumbnail_height']);
        $this->db->set('video_count', $form_result['video_count']);
        $this->db->set('video_size', $form_result['video_width'] . '*' . $form_result['video_height']);
        $this->db->set('files_count', $form_result['files_count']);
        if ($type->locations_enabled) {
	        $this->db->set('maps_enabled', $form_result['maps_enabled']);
	        $this->db->set('maps_size', $form_result['maps_width'] . '*' . $form_result['maps_height']);
        }
        $this->db->set('option_print', $form_result['option_print']);
        $this->db->set('option_pdf', $form_result['option_pdf']);
        $this->db->set('option_quick_list', $form_result['option_quick_list']);
        $this->db->set('option_email_friend', $form_result['option_email_friend']);
        $this->db->set('option_email_owner', $form_result['option_email_owner']);
        $this->db->set('option_report', $form_result['option_report']);
        $this->db->set('social_bookmarks_enabled', $form_result['social_bookmarks_enabled']);
        $this->db->set('ratings_enabled', $form_result['ratings_enabled']);
        $this->db->set('reviews_mode', $form_result['reviews_mode']);
        if ($form_result['reviews_mode'] != 'disabled')
        	$this->db->set('reviews_richtext_enabled', $form_result['reviews_richtext_enabled']);
        $this->db->where('id', $this->_level_id);

        if ($this->db->update('levels')) {
        	// Update content fields group
        	$this->db->set('name', LANG_LISTINGS_LEVEL_GROUP_CUSTOM_NAME_1 . ' "' . $form_result['name'] . '" ' . LANG_LISTINGS_LEVEL_GROUP_CUSTOM_NAME_2 . ' "' . $type->name . '"');
	        $this->db->where('custom_name', LISTINGS_LEVEL_GROUP_CUSTOM_NAME);
	        $this->db->where('custom_id', $this->_level_id);
	        $this->db->update('content_fields_groups');

        	return true;
        }
    }

    public function deleteLevelById()
    {
    	// Delete level
        $this->db->where('id', $this->_level_id);
        $this->db->delete('levels');
        
        // Delete content fields group
        $this->db->where('custom_name', LISTINGS_LEVEL_GROUP_CUSTOM_NAME);
        $this->db->where('custom_id', $this->_level_id);
        $this->db->delete('content_fields_groups');

        // Delete listings with their locations, images, videos, files
        $CI = &get_instance();
        
        // Delete level items from packages
        if ($CI->load->is_module_loaded("packages")) {
        	$this->db->where('level_id', $this->_level_id);
    		$this->db->delete('packages_items');
        }
        
        $CI->load->model('listings', 'listings');
        
        $this->db->select('id');
        $this->db->from('listings');
        $this->db->where('level_id', $this->_level_id);
        $query = $this->db->get();
        foreach ($query->result_array() AS $row)
        	$CI->listings->deleteListingById($row['id']);

        return true;
    }
    
    public function getContentGroupIdByLevelId()
    {
    	$this->db->select('id');
    	$this->db->where('custom_name', LISTINGS_LEVEL_GROUP_CUSTOM_NAME);
    	$this->db->where('custom_id', $this->_level_id);
    	$this->db->from('content_fields_groups');
    	$query = $this->db->get();
    	
    	if ($row = $query->row_array())
    		return $row['id'];
    }
    
    public function getLevelsOfType()
    {
    	$cache_id = 'levels_of_type_'.$this->_type_id;
    	if (!$cache = $this->cache->load($cache_id)) {
			$this->db->select();
			$this->db->from('levels');
			$this->db->where('type_id', $this->_type_id);
			$this->db->order_by('order_num');
			$query = $this->db->get();
	
			$levels = array();
			if ($query->num_rows()) {
				$array = $query->result_array();
				foreach ($array AS $row) {
					$level = new levelClass;
					$level->setLevelFromArray($row);

					$this->setLevelId($level->id);
					$level->content_fields_group_id = $this->getContentGroupIdByLevelId();

					$levels[] = $level;
				}
	    	}

			$this->cache->save($levels, $cache_id, array('types', 'levels'));
		} else {
			$levels = $cache;
		}
		
		return $levels;
    }
}
?>