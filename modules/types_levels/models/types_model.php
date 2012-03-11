<?php
include_once(MODULES_PATH . 'types_levels/classes/type.class.php');

class typesModel extends model
{
    private $_type_id;
    
    private $_cached_types_objects = array();

    public function setTypeId($type_id)
    {
        $this->_type_id = $type_id;
    }
    /**
     * selects all types from DB
     *
     * @return array
     */
    /*public function getTypesArray()
    {
        $this->db->select('t.*');
        $this->db->select('count(l.id) AS l_count');
        $this->db->from('types AS t');
        $this->db->join('levels AS l', 'l.type_id=t.id', 'left');
        $this->db->group_by('t.id');
        $this->db->order_by('t.order_num');
        $query = $this->db->get();

        return $query->result_array();
    }*/
    
    /**
     * saves the order of types by its weight
     *
     * @param string $serialized_order
     */
    public function setTypesOrder($serialized_order)
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
    				$this->db->update('types');
    			}
    		}
    	}
    }
    
    public function getNewType()
    {
		$type = new typeClass;
        return $type;
    }
    
    public function getTypeFromForm($form_result)
    {
		$type = new typeClass;
		$type->setTypeFromArray($form_result);

        return $type;
    }
    
    /**
     * is there type with such name in the DB?
     *
     * @param string $name
     */
    public function is_type_name($name)
    {
    	$this->db->select();
		$this->db->from('types');
		$this->db->where('name', $name);
		if (!is_null($this->_type_id)) {
			$this->db->where('id !=', $this->_type_id);
		}
		$query = $this->db->get();

		return $query->num_rows();
    }
    
    /**
     * is there type with such seoname in the DB?
     *
     * @param string $seoname
     */
    public function is_type_seoname($seoname)
    {
    	$this->db->select();
		$this->db->from('types');
		$this->db->where('seo_name', $seoname);
		if (!is_null($this->_type_id)) {
			$this->db->where('id !=', $this->_type_id);
		}
		$query = $this->db->get();

		return $query->num_rows();
    }
    
    public function saveType($form_result)
    {
    	$system_settings = registry::get('system_settings');
    	
    	$this->db->select_max('order_num');
    	$query = $this->db->get('types');
    	if ($row = $query->row())
    		$order_num = $row->order_num + 1;
    	else 
    		$order_num = 1;
    	
        $this->db->set('name', $form_result['name']);
        $this->db->set('seo_name', $form_result['seo_name']);
        $this->db->set('meta_title', $form_result['meta_title']);
        $this->db->set('meta_description', $form_result['meta_description']);
        $this->db->set('locations_enabled', $form_result['locations_enabled']);
        $this->db->set('zip_enabled', $form_result['zip_enabled']);
        if (!$system_settings['single_type_structure']) {
	        $this->db->set('search_type', $form_result['search_type']);
	        $this->db->set('what_search', $form_result['what_search']);
	        $this->db->set('where_search', $form_result['where_search']);
	        $this->db->set('categories_search', $form_result['categories_search']);
	        $this->db->set('categories_type', $form_result['categories_type']);
        }
        $this->db->set('order_num', $order_num);
        if ($this->db->insert('types')) {
        	$group_id = $this->db->insert_id();
        	
        	// Create search fields group of this type, if 'search_type' == 'local'
        	if (!$system_settings['single_type_structure']) {
	        	if ($form_result['search_type'] == 'local') {
	        		// For quick search
	        		$this->db->set('name', LANG_LOCAL_SEARCH_GROUP_CUSTOM_NAME . ' "' . $form_result['name'] . '"');
	        		$this->db->set('custom_name', LOCAL_SEARCH_GROUP_CUSTOM_NAME);
	        		$this->db->set('custom_id', $group_id);
	        		$this->db->set('mode', 'quick');
	        		$this->db->insert('search_fields_groups');
	        		
	        		// For advanced page
	        		$this->db->set('name', LANG_LOCAL_SEARCH_GROUP_CUSTOM_NAME . ' "' . $form_result['name'] . '"');
	        		$this->db->set('custom_name', LOCAL_SEARCH_GROUP_CUSTOM_NAME);
	        		$this->db->set('custom_id', $group_id);
	        		$this->db->set('mode', 'advanced');
	        		$this->db->insert('search_fields_groups');
	        	}
        	}
        	
        	$system_settings = registry::get('system_settings');
        	if (@$system_settings['language_areas_enabled']) {
        		translations::saveTranslations(array('types', 'name', $this->db->insert_id()));
        	}
        	return true;
        }
    }
    
    public function getTypeById($type_id = null)
    {
    	if (is_null($type_id))
    		$type_id = $this->_type_id;

    	if (isset($this->_cached_types_objects[$type_id]))
    		return $this->_cached_types_objects[$type_id];
    	else {
	        $this->db->select();
	        $this->db->where('id', $type_id);
	        $query = $this->db->get('types');
	
	        if ($query->num_rows()) {
	            $type = new typeClass;
	            $type->setTypeFromArray($query->row_array());
	            $this->_cached_types_objects[$type_id] = $type;
	            return $type;
	        } else {
	            return false;
	        }
    	}
    }
    
    public function getTypeBySeoName($seo_name)
    {
		$this->db->select();
		$this->db->where('seo_name', $seo_name);
		$query = $this->db->get('types');
		if ($query->num_rows()) {
			$type = new typeClass;
			$type->setTypeFromArray($query->row_array());
			return $type;
		} else {
			return false;
		}
    }
    
    public function saveTypeByIdWhenSingleStructure($type)
    {
    	$this->db->set('search_type', 'global');
		$this->db->set('categories_type', 'global');
        $this->db->where('id', $type->id);
        if ($this->db->update('types')) {
        	$this->db->where('custom_name', LOCAL_SEARCH_GROUP_CUSTOM_NAME);
        	$this->db->where('custom_id', $type->id);
        	return $this->db->delete('search_fields_groups');
        }
    }
    
    public function saveTypeById($form_result)
    {
    	$system_settings = registry::get('system_settings');

        $this->db->set('name', $form_result['name']);
        $this->db->set('seo_name', $form_result['seo_name']);
        $this->db->set('meta_title', $form_result['meta_title']);
        $this->db->set('meta_description', $form_result['meta_description']);
        $this->db->set('locations_enabled', $form_result['locations_enabled']);
        $this->db->set('zip_enabled', $form_result['zip_enabled']);
        if (!$system_settings['single_type_structure']) {
	        $this->db->set('search_type', $form_result['search_type']);
	        $this->db->set('what_search', $form_result['what_search']);
	        $this->db->set('where_search', $form_result['where_search']);
	        $this->db->set('categories_search', $form_result['categories_search']);
	        $this->db->set('categories_type', $form_result['categories_type']);
        }
        $this->db->where('id', $this->_type_id);
        if ($this->db->update('types')) {
        	if (!$system_settings['single_type_structure']) {
	        	// Create search fields group of this type, if 'search_type' == 'local'
	        	if ($form_result['search_type'] == 'local') {
	        		$this->db->set('name', LANG_LOCAL_SEARCH_GROUP_CUSTOM_NAME . ' "' . $form_result['name'] . '"');
	        		$this->db->set('custom_name', LOCAL_SEARCH_GROUP_CUSTOM_NAME);
	        		$this->db->set('custom_id', $this->_type_id);
	        		$this->db->set('mode', 'quick');
	        		$this->db->on_duplicate_insert('search_fields_groups');
	        		
	        		$this->db->set('name', LANG_LOCAL_SEARCH_GROUP_CUSTOM_NAME . ' "' . $form_result['name'] . '"');
	        		$this->db->set('custom_name', LOCAL_SEARCH_GROUP_CUSTOM_NAME);
	        		$this->db->set('custom_id', $this->_type_id);
	        		$this->db->set('mode', 'advanced');
	        		$this->db->on_duplicate_insert('search_fields_groups');
	        	} else {
	        		$this->db->where('custom_name', LOCAL_SEARCH_GROUP_CUSTOM_NAME);
			        $this->db->where('custom_id', $this->_type_id);
			        $this->db->delete('search_fields_groups');
	        	}
        	}
        	return true;
        }
    }
    
    public function deleteTypeById()
    {

        // --------------------------------------------------------------------------------------------
        // Delete content fields groups
        //$this->loadTypesLevels();
        /*$levels = $this->getLevelsOfType();
        foreach ($levels AS $level) {
        	$this->db->where('id', $level['content_fields_group_id']);
	        $this->db->delete('content_fields_groups');
        }*/
        
        // --------------------------------------------------------------------------------------------
        // Delete search fields group
        $this->db->where('custom_name', LOCAL_SEARCH_GROUP_CUSTOM_NAME);
        $this->db->where('custom_id', $this->_type_id);
        $this->db->delete('search_fields_groups');

        // --------------------------------------------------------------------------------------------
        // Delete listings with their locations, images, videos, files
        $CI = &get_instance();
        
        /*$this->db->select('l.id');
        $this->db->from('listings AS l');
        $this->db->join('levels AS lev', 'lev.id=l.level_id', 'left');
        $this->db->where('lev.type_id', $this->_type_id);
        $query = $this->db->get();
        foreach ($query->result_array() AS $row)
        	$CI->listings->deleteListingById($row['id']);*/

        // --------------------------------------------------------------------------------------------
        // Delete levels
        /*$this->db->where('type_id', $this->_type_id);
        $this->db->delete('levels');*/

        $CI->load->model('levels', 'types_levels');
        $type = $this->getTypeById();
        $type->buildLevels();
        foreach ($type->levels AS $level) {
        	$CI->levels->setLevelId($level->id);
        	$CI->levels->deleteLevelById();
        }
        
        // Delete type
        $this->db->where('id', $this->_type_id);
        $this->db->delete('types');
        
        return true;
    }
    
    /*public function getLevelsOfType()
    {
    	$this->db->select('l.*', false);
    	$this->db->select('cfg.id AS content_fields_group_id');
    	$this->db->from('levels AS l');
    	$this->db->join('content_fields_groups AS cfg', 'l.id=cfg.custom_id');
    	$this->db->where('l.type_id', $this->_type_id);
    	$this->db->where('cfg.custom_name', LISTINGS_LEVEL_GROUP_CUSTOM_NAME);
    	$this->db->orderby('order_num');
    	$query = $this->db->get();
    	return $query->result_array();
    }*/
    
    // --------------------------------------------------------------------------------------------
    // Get all types objects with its levels objects
    // --------------------------------------------------------------------------------------------
    public function getTypesLevels()
    {
    	$this->db->select();
    	$this->db->from('types');
    	$this->db->order_by('order_num');
    	$query = $this->db->get();

    	$types = array();
    	if ($query->num_rows()) {
    		$array = $query->result_array();
    		foreach ($array AS $row) {
	    		$type = new typeClass;
	    		$type->setTypeFromArray($row);
	    		$type->buildLevels();
	    		$this->_cached_types_objects[$row['id']] = $type;
	    		$types[$row['id']] = $type;
    		}
    	}
    	return $types;
    }
    
/**
	 * select all types with local categories from DB
	 *
	 * @return array
	 */
	public function selectLocalCategoriesTypes()
	{
		$this->db->select();
		$this->db->where('categories_type', 'local');
		$query = $this->db->get('types');

		return $query->result_array();
	}
}
?>