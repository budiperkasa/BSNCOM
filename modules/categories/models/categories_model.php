<?php
include_once(MODULES_PATH . 'categories/classes/category.class.php');

class categoriesModel extends model
{
    private $categories_db = array();
	public $_type_id = 0;
    public $_category_id;
    
    public function setTypeId($type_id)
    {
    	// If it is not false or 0
    	if ($type_id)
    		$this->_type_id = $type_id;
    }
    
    public function setCategoryId($category_id)
    {
    	$this->_category_id = $category_id;
    }

    public function selectCategoriesFromDB()
    {
        $this->db->select('id');
        $this->db->select('type_id');
        $this->db->select('parent_category_id');
        $this->db->select('tree_path');
        $this->db->select('name');
        $this->db->select('seo_name');
        $this->db->select('selected_icons_serialized');
        $this->db->select('meta_title');
        $this->db->select('meta_description');
		$this->db->from('categories');
		$this->db->where('type_id', $this->_type_id);
		//$this->db->where_not_in('name', 'untranslated'); // except untranslated listings
		if ($this->config->item('categories_alphobetical_order'))
        	$this->db->order_by('name');
        else
        	$this->db->order_by('order_num');

        $this->categories_db = $this->db->get()->result_array();

        return $this->categories_db;
    }

    public function is_category_seoname($seo_name)
    {
    	$this->db->select();
		$this->db->from('categories');
		$this->db->where('type_id', $this->_type_id);
		$this->db->where('seo_name', $seo_name);
		if (!is_null($this->_category_id)) {
			$this->db->where('id !=', $this->_category_id);
		}
		$query = $this->db->get();

		return $query->num_rows();
    }
    
    public function getNewCategory()
    {
    	$category = new category;
    	return $category;
    }
    
    public function getCategoryFromForm($form)
    {
    	$category = new category;
    	$category->setCategoryFromArray($form);
    	return $category;
    }
    
    public function saveCategory($form)
    {
    	$this->db->set('name', $form['name']);
    	$this->db->set('seo_name', $form['seo_name']);
    	$this->db->set('meta_title', $form['meta_title']);
    	$this->db->set('meta_description', $form['meta_description']);
    	$this->db->set('selected_icons_serialized', $form['selected_icons_serialized']);
    	$this->db->set('type_id', $this->_type_id);
    	if ($this->db->insert('categories')) {
    		$this->db->set('tree_path', '-0-'.$this->db->insert_id());
			$this->db->where('id', $this->db->insert_id());
			$this->db->update('categories');

        	$system_settings = registry::get('system_settings');
        	if (@$system_settings['language_areas_enabled']) {
        		translations::saveTranslations(array('categories', 'name', $this->db->insert_id()));
        		translations::saveTranslations(array('categories', 'meta_title', $this->db->insert_id()));
        		translations::saveTranslations(array('categories', 'meta_description', $this->db->insert_id()));
        	}
        	return true;
        }
    }
    
    public function saveCategoryChild($parent_id, $form)
    {
    	$this->db->set('parent_category_id', $parent_id);
    	$this->db->set('name', $form['name']);
    	$this->db->set('seo_name', $form['seo_name']);
    	$this->db->set('meta_title', $form['meta_title']);
    	$this->db->set('meta_description', $form['meta_description']);
    	$this->db->set('selected_icons_serialized', $form['selected_icons_serialized']);
    	$this->db->set('type_id', $this->_type_id);
    	if ($this->db->insert('categories')) {
    		$categories_db = $this->selectCategoriesFromDB();
	        $path = array();
			$path[] = $this->db->insert_id();
			$this->getCategoriesTreePathRecursively($parent_id, $categories_db, $path);
			$path[] = 0;
			$path = array_reverse($path);
			$path_string = '-'.implode('-', $path);
	
			$this->db->set('tree_path', $path_string);
			$this->db->where('id', $this->db->insert_id());
			$this->db->update('categories');

        	$system_settings = registry::get('system_settings');
        	if (@$system_settings['language_areas_enabled']) {
        		translations::saveTranslations(array('categories', 'name', $this->db->insert_id()));
        		translations::saveTranslations(array('categories', 'meta_title', $this->db->insert_id()));
        		translations::saveTranslations(array('categories', 'meta_description', $this->db->insert_id()));
        	}
        	return true;
        }
    }
    
    public function getCategoryById($category_id = null)
    {
    	if (!$category_id) {
    		$category_id = $this->_category_id;
    	}
    	
    	$selected_category_row = false;
    	if (!empty($this->categories_db)) {
    		foreach ($this->categories_db AS $category_row) {
    			if ($category_row['id'] == $category_id) {
    				$selected_category_row = $category_row;
    				break;
    			}
    		}
    	}
    	if (!$selected_category_row) {
    		$this->db->select();
	    	$this->db->from('categories');
	    	$this->db->where('id', $category_id);
	    	$selected_category_row = $this->db->get()->row_array();
    	}

    	$category = new category;
    	if ($selected_category_row)
    		$category->setCategoryFromArray($selected_category_row);
    	return $category;
    }
    
    public function getCategoryBySeoName($seo_name)
    {
    	$a = array_filter(explode('/', $seo_name));

	    $this->db->select();
	    $this->db->from('categories');
	    $this->db->where('seo_name', end($a));
	    $this->db->where('type_id', $this->_type_id);
	    $selected_category_row = $this->db->get()->row_array();

    	$category = new category;
    	if ($selected_category_row) {
    		$category->setCategoryFromArray($selected_category_row);
    		return $category;
    	} else 
    		return false;
    }
    
    public function saveCategoryById($form)
    {
    	$this->db->set('name', $form['name']);
    	$this->db->set('seo_name', $form['seo_name']);
    	$this->db->set('meta_title', $form['meta_title']);
    	$this->db->set('meta_description', $form['meta_description']);
    	$this->db->set('selected_icons_serialized', $form['selected_icons_serialized']);
    	$this->db->where('id', $this->_category_id);
    	if ($this->db->update('categories')) {
    		$this->updateMapMarkerIcons();
    		return true;
    	}
    }
    
    public function deleteCategoryById($category_id = null)
    {
    	if (is_null($category_id)) {
    		$category_id = $this->_category_id;
    	}
    	
    	$categories_db = $this->selectCategoriesFromDB();
    	
    	foreach ($categories_db AS $category_row) {
    		if ($category_row['parent_category_id'] == $category_id) {
    			$this->deleteCategoryById($category_row['id']);
    		}
    	}
    	
    	// Delete listings in categories records
    	$this->db->where('category_id', $category_id);
    	$this->db->delete('listings_in_categories');

    	$this->db->where('id', $category_id);
    	return $this->db->delete('categories');
    }

    public function saveCategories($list)
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
        	$i = 0;
        	foreach ($values AS $id=>$parent_id) {
        		$i++;
        		$this->db->set('parent_category_id', $parent_id);
        		$this->db->set('order_num', $i);
        		$this->db->where('id', $id);
        		$this->db->update('categories');
        	}
        	$categories_db = $this->selectCategoriesFromDB();
        	foreach ($values AS $id=>$parent_id) {
        		$this->db->select('id');
        		$this->db->select('parent_category_id');
        		$this->db->from('categories');
        		$this->db->like('tree_path', '-'.$id, 'both');
        		$result_array = $this->db->get()->result_array();
        		
        		foreach ($result_array AS $category_row) {
        			$path = array();
					$path[] = $category_row['id'];
					$this->getCategoriesTreePathRecursively($category_row['parent_category_id'], $categories_db, $path);
					$path[] = 0;
					$path = array_reverse($path);
					$path_string = '-'.implode('-', $path);
				
					$this->db->set('tree_path', $path_string);
					$this->db->where('id', $category_row['id']);
					$this->db->update('categories');
        		}
        	}
        	$this->updateMapMarkerIcons();
        }
        return true;
    }

	public function getCategoriesTreePathRecursively($parent_category_id, $categories_array, &$path)
	{
		foreach ($categories_array AS $category_row) {
			if ($category_row['id'] == $parent_category_id) {
				$path[] = $category_row['id'];
				$this->getCategoriesTreePathRecursively($category_row['parent_category_id'], $categories_array, $path);
				break;
			}
		}
	}

	/**
     * Get absolutely ALL children of category
     * @param id/obj/seoname $category (0 - root IS NOT possible)
     */
    public function getAllChildrenOfCategory($category)
    {
    	if (is_numeric($category)) {
    		// By ID
   			$category_obj = $this->getCategoryById($category);
   		} elseif (is_object($category))
   			// This is ready object
   			$category_obj = $category;
   		else {
   			// By seo name
   			$category_obj = $this->getCategoryBySeoName($category);
   		}
   		
   		$this->db->select('id');
        $this->db->select('type_id');
        $this->db->select('parent_category_id');
        $this->db->select('tree_path');
        $this->db->select('name');
        $this->db->select('seo_name');
        $this->db->select('selected_icons_serialized');
        $this->db->select('meta_title');
        $this->db->select('meta_description');
   		$this->db->from('categories');
   		$this->db->like('tree_path', $category_obj->tree_path . '-', 'after');
   		$this->db->orderby('name');
   		$query = $this->db->get();
   		$result = $query->result_array();
   		
   		$categories = array();
   		foreach ($result AS $row) {
   			$category = new category;
		    $category->setCategoryFromArray($row);
		    $categories[] = $category;
   		}
   		return $categories;
    }
    
	/**
     * Get only direct children of category
     * @param id/obj/seoname $category (if 0 - this is root)
     */
    public function getDirectChildrenOfCategory($category)
    {
    	if (is_numeric($category)) {
    		// By ID
   			$category_id = $category;
   		} elseif (is_object($category))
   			// This is ready object
   			$category_id = $category->id;
   		else {
   			// By seo name
   			$category_id = $this->getCategoryBySeoName($category)->id;
   		}
   		
   		$this->db->select('id');
        $this->db->select('type_id');
        $this->db->select('parent_category_id');
        $this->db->select('tree_path');
        $this->db->select('name');
        $this->db->select('seo_name');
        $this->db->select('selected_icons_serialized');
        $this->db->select('meta_title');
        $this->db->select('meta_description');
   		$this->db->from('categories');
   		$this->db->where('parent_category_id', $category_id);
   		// children of the root require special type_id condition
   		if ($category_id === 0)
   			$this->db->where('type_id', $this->_type_id);
   		$this->db->orderby('name');
   		$query = $this->db->get();
   		$result = $query->result_array();
   		
   		$categories = array();
   		foreach ($result AS $row) {
   			$category = new category;
		    $category->setCategoryFromArray($row);
		    $categories[] = $category;
   		}
   		return $categories;
    }
	
	// --------------------------------------------------------------------------------------------
	// Following methods for management map marker icons
	// --------------------------------------------------------------------------------------------
	/**
	 * Checks:
	 * - is there any custom marker icons available for categories (more than 1)
	 * - is its count=1, if so we will use this 'default icon', without ability to choose another
	 * - is already selected icons available now
	 *
	 */
	public function isIcons($categories_list, $selected_icons_array = array())
	{
		$selected_icons_array = array_filter($selected_icons_array);

		$is_selected_icons = array();
		if ($selected_icons_array) {
			foreach ($selected_icons_array AS $key=>$icon_id) {
				$is_selected_icons[$key] = false;
			}
		}
		$single_icon = false;
		$single_icon_file = '';
    	$categories_icons = array();

    	foreach ($categories_list AS $category_id) {
	    	if ($chain = $this->categories->getCategoryById($category_id)->getChainAsArray())
		    	foreach ($chain AS $category) {
		    		if ($category->selected_icons_serialized) {
		    			if ($selected_icons_array) {
		    				foreach ($selected_icons_array AS $key=>$icon_id) {
		    					if (!$is_selected_icons[$key])
		    						$is_selected_icons[$key] = in_array($icon_id, unserialize($category->selected_icons_serialized));
		    				}
		    			}
		    			$categories_icons = array_unique(array_merge($categories_icons, unserialize($category->selected_icons_serialized)));
		    		}
		    	}
    	}
    	if (count($categories_icons) > 1)
    		$is_icons = true;
    	else {
    		if (count($categories_icons) == 1) {
    			$single_icon = $categories_icons[0];
    			$this->db->select('folder_name');
    			$this->db->select('file_name');
    			$this->db->from('map_marker_icons');
    			$this->db->where('id', $single_icon);
    			$query = $this->db->get();
    			$row = $query->row_array();
    			$single_icon_file = $row['folder_name'] . '/' . $row['file_name'];
    		}
    		$is_icons = false;
    	}
    	return array('is_icons' => $is_icons, 'single_icon' => $single_icon, 'single_icon_file' => $single_icon_file, 'is_selected_icons' => $is_selected_icons);
	}
	
	public function getMapMarkerIconsThemes()
	{
		$this->db->select();
		$this->db->from('map_marker_icons_themes');
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function getMapMarkerIconsFolders()
	{
		$this->db->select('folder_name');
		$this->db->from('map_marker_icons_themes');
		$query = $this->db->get();

		$result = array();
		foreach ($query->result_array() AS $row) {
			$result[] = $row['folder_name'];
		}
		return $result;
	}
	
	public function insertMapMarkerIconsTheme($folder_name)
	{
		$this->db->set('folder_name', $folder_name);
		$this->db->set('name', $folder_name);
		return $this->db->insert('map_marker_icons_themes');
	}
	
	public function deleteMapMarkerIconsTheme($folder_name)
	{
		$this->db->set('lil.map_icon_id', '');
		$this->db->set('lil.map_icon_file', '');
		$this->db->like('lil.map_icon_file', $folder_name . '/', 'AFTER');
		$this->db->update('listings_in_locations AS lil');
		
		$this->db->where('folder_name', $folder_name);
		return $this->db->delete('map_marker_icons_themes');
	}
	
	public function saveMapMarkerIconsThemes($form_result)
	{
		foreach ($form_result AS $folder_name=>$theme_name) {
			$this->db->set('name', $theme_name);
			$this->db->where('folder_name', $folder_name);
			$this->db->update('map_marker_icons_themes');
		}
		return true;
	}
	
	public function getMapMarkerIcons($folder_name)
	{
		$this->db->select();
		$this->db->from('map_marker_icons');
		$this->db->where('folder_name', $folder_name);
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function getMapMarkerIconsFiles($folder_name)
	{
		$this->db->select('file_name');
		$this->db->where('folder_name', $folder_name);
		$this->db->from('map_marker_icons');
		$query = $this->db->get();

		$result = array();
		foreach ($query->result_array() AS $row) {
			$result[] = $row['file_name'];
		}
		return $result;
	}
	
	public function insertMapMarkerIcons($folder_name, $file_name)
	{
		$this->db->set('folder_name', $folder_name);
		$this->db->set('file_name', $file_name);
		$this->db->set('name', $file_name);
		return $this->db->insert('map_marker_icons');
	}
	
	public function deleteMapMarkerIcons($folder_name, $file_name)
	{
		$this->db->set('lil.map_icon_id', '');
		$this->db->set('lil.map_icon_file', '');
		$this->db->where('lil.map_icon_file', $folder_name . '/' . $file_name);
		$this->db->update('listings_in_locations AS lil');

		$this->db->where('folder_name', $folder_name);
		$this->db->where('file_name', $file_name);
		return $this->db->delete('map_marker_icons');
	}
	
	public function saveMapMarkerIcons($folder_name, $form_result)
	{
		foreach ($form_result AS $icon_id=>$icon_name) {
			$this->db->set('name', $icon_name);
			$this->db->where('id', $icon_id);
			$this->db->where('folder_name', $folder_name);
			$this->db->update('map_marker_icons');
		}
		return true;
	}
	
	public function getMapMarkerIconsByName($folder_name)
	{
		$this->db->select();
		$this->db->where('folder_name', $folder_name);
		$this->db->from('map_marker_icons_themes');
		$query = $this->db->get();
		return $query->row_array();
	}
	
	public function updateMapMarkerIcons()
	{
		// Build array of categories by id, those include all map icons they may contain
    	$categories_array = array();
    	$categories_db = $this->selectCategoriesFromDB();
    	foreach ($categories_db AS $key=>$category) {
    		$parents = explode('-', $category['tree_path']);
    		$icons = array();
    		foreach ($categories_db AS $subsearch) {
    			if (in_array($subsearch['id'], $parents)) {
    				if ($tmp_array = unserialize($subsearch['selected_icons_serialized']))
    					$icons = array_unique(array_merge($icons, $tmp_array));
    			}
    		}
    		$categories_array[$category['id']] = $icons;
    	}

   		// Build $listings_in_categories[$listing_id][$category_id] = count_of_categories
   		$this->db->select('lic.listing_id');
    	$this->db->select('lic.category_id');
    	$this->db->select('lil.id AS location_id');
    	$this->db->select('lil.map_icon_id');
    	$this->db->from('listings_in_categories AS lic');
    	$this->db->join('listings_in_locations AS lil', 'lil.listing_id=lic.listing_id');
    	$query = $this->db->get();
    	$listings_in_categories = array();
    	foreach ($query->result_array() AS $row) {
    		$listings_in_categories[$row['listing_id']][$row['location_id']][$row['map_icon_id']][] = $row['category_id'];
    	}

    	// compare all locations' icons with available categories' icons
    	foreach ($listings_in_categories AS $listing_id=>$locations) {
    		foreach ($locations AS $location_id=>$icons) {
    			foreach ($icons AS $icon_id=>$categories) {
    				$icon_exists = false;
    				$unique_icons = array();
    				foreach ($categories AS $category_id) {
    					if (isset($categories_array[$category_id])) {
		    				$unique_icons = array_unique(array_merge($unique_icons, $categories_array[$category_id]));
			    			if (in_array($icon_id, $categories_array[$category_id])) {
			    				$icon_exists = true;
			    			}
    					}
    				}

		    		// if something changed
		    		if (!$icon_exists) {
		    			if (count($unique_icons) == 1) {
		    				// This is 'single icon' - place it in location row
		    				if ($categories_array[$category_id]) {
		    					$new_icon_id = reset($categories_array[$category_id]);
								$this->db->select('folder_name');
								$this->db->select('file_name');
								$this->db->from('map_marker_icons');
								$this->db->where('id', $new_icon_id);
								$query = $this->db->get();
								$row = $query->row_array();
								$single_icon_file = $row['folder_name'] . '/' . $row['file_name'];
		    				} else {
		    					$new_icon_id = 0;
		    					$single_icon_file = '';
		    				}

	    					$this->db->set('map_icon_id', $new_icon_id);
			    			$this->db->set('map_icon_file', $single_icon_file);
			    			$this->db->where('id', $location_id);
			    			$this->db->update('listings_in_locations');
	    				} else {
	    					// There isn't such icon now - clear it in location row
	    					$this->db->set('map_icon_id', 0);
			    			$this->db->set('map_icon_file', '');
			    			$this->db->where('id', $location_id);
			    			$this->db->update('listings_in_locations');
	    				}
		    		}
    			}
    		}
    	}
	}
}
?>