<?php

class admin_locationsController extends controller
{
    public function levels()
    {
		$this->load->model('locations');
	
		if ($this->input->post('submit')) {
			$this->form_validation->set_rules('serialized_order', LANG_SERIALIZED_ORDER_VALUE);
            if ($this->form_validation->run() !== FALSE) {
            	$this->locations->setLocLevelsOrder($this->form_validation->set_value('serialized_order'));
            	// Clean cache
				$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('locations'));

            	$this->setSuccess(LANG_LOC_LEVELS_ORDER_SAVE_SUCCESS);
            	redirect('admin/locations/levels/');
            }
		}
		$levels = $this->locations->selectAllLevels();
    
		$view = $this->load->view();

		$view->assign('levels', $levels);
		$view->addJsFile('jquery.tablednd_0_5.js');
        $view->display('locations/admin_locations_levels.tpl');
    }
    
    public function is_unique_loc_level_name($name)
    {
    	$this->load->model('locations');
		
		if ($this->locations->is_loc_level_name($name)) {
			$this->form_validation->set_message('name');
			return FALSE;
		} else {
			return TRUE;
		}
    }
    
    public function levels_create()
    {
    	$this->load->model('locations');

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('name', LANG_LOC_LEVEL_NAME, 'required|max_length[35]|callback_is_unique_loc_level_name');

			if ($this->form_validation->run() !== FALSE) {
				$form_result = $this->form_validation->result_array();
				if ($this->locations->saveLocationsLevel($form_result)) {
					// Clean cache
					$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('locations'));

					$this->setSuccess(LANG_NEW_LOC_LEVEL_CREATE_SUCCESS);
					redirect('admin/locations/levels/');
				}
			} else {
            	$loc_level = $this->locations->getLocationsLevelFromForm($this->form_validation->result_array());
			}
        } else {
            $loc_level = $this->locations->getNewLocationsLevel();
        }
        
        registry::set('breadcrumbs', array(
    		'admin/locations/levels/' => LANG_MANAGE_LOC_LEVELS,
    		LANG_CREATE_LOCATIONS_LEVEL_TITLE,
    	));

        $view  = $this->load->view();
        $view->assign('loc_level', $loc_level);
        $view->display('locations/admin_locations_level_settings.tpl');
    }
    
    public function levels_edit($loc_level_id)
    {
        $this->load->model('locations');

        $this->locations->setLocationsLevelId($loc_level_id);

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('name', LANG_LOC_LEVEL_NAME, 'required|max_length[35]|callback_is_unique_loc_level_name');

			if ($this->form_validation->run() !== FALSE) {
				$form_result = $this->form_validation->result_array();
				if ($this->locations->saveLocationsLevelById($form_result)) {
					$this->setSuccess(LANG_LOC_LEVEL_SAVE_SUCCESS);
					redirect('admin/locations/levels/');
				}
			} else {
            	$loc_level = $this->locations->getLocationsLevelFromForm($this->form_validation->result_array());
			}
        } else {
            $loc_level = $this->locations->getLocationsLevelById();
        }

        registry::set('breadcrumbs', array(
    		'admin/locations/levels/' => LANG_MANAGE_LOC_LEVELS,
    		LANG_EDIT_LOCATIONS_LEVEL_TITLE . ' "' . $loc_level->name . '"',
    	));

        $view  = $this->load->view();
        $view->assign('loc_level', $loc_level);
        $view->display('locations/admin_locations_level_settings.tpl');
    }
    
    public function levels_delete($loc_level_id)
    {
        $this->load->model('locations');

        $this->locations->setLocationsLevelId($loc_level_id);

        if ($this->input->post('yes')) {
            if ($this->locations->deleteLocationsLevelById()) {
            	// Clean cache
				$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('locations'));

            	$this->setSuccess(LANG_LOC_LEVEL_DELETE_SUCCESS);
                redirect('admin/locations/levels/');
            }
        }

        if ($this->input->post('no')) {
            redirect('admin/locations/levels/');
        }

        if ( !$loc_level = $this->locations->getLocationsLevelById()) {
            redirect('admin/locations/levels/');
        }
        
        registry::set('breadcrumbs', array(
    		'admin/locations/levels/' => LANG_MANAGE_LOC_LEVELS,
    		LANG_DELETE_LOCATIONS_LEVEL_TITLE . ' "' . $loc_level->name . '"',
    	));

		$view  = $this->load->view();
		$view->assign('options', array($loc_level_id => $loc_level->name));
        $view->assign('heading', LANG_DELETE_LOC_LEVEL);
        $view->assign('question', LANG_DELETE_LOC_LEVEL_QUEST);
        $view->display('backend/delete_common_item.tpl');
    }
    
    // --------------------------------------------------------------------------------------------
    // Admin Locations functions
    // --------------------------------------------------------------------------------------------

    public function index()
    {
    	$this->load->model('locations');
        $loc_levels_count = $this->locations->getLocationsLevelsCount();

        $view  = $this->load->view();
        $view->addJsFile('jquery.jstree.js');
        $view->assign('loc_levels_count', $loc_levels_count);
        $view->display('locations/admin_locations.tpl');
    }
    
    public function synchronize()
    {
    	$this->load->model('locations');
    	if ($this->locations->synchronize_locations())
    		// Clean cache
			$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('locations'));

        	$this->setSuccess(LANG_SYNCHRONIZE_LOCATIONS_SUCCESS);
        redirect('admin/locations/');
    }
    
	public function regeocode()
    {
    	$this->load->model('locations');
    	if ($this->locations->reGeoCodeAllLocations())
	    	// Clean cache
			$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('locations'));

        	$this->setSuccess(LANG_REGEOCODE_LOCATIONS_SUCCESS);
        redirect('admin/locations/');
    }
    
    public function save()
    {
    	$this->load->model('locations');
    	$this->form_validation->set_rules('list', LANG_LOCATIONS_SER_LIST);
		if ($this->form_validation->run() !== FALSE) {
			if ($this->locations->saveLocations($this->form_validation->set_value('list'))) {
				// Clean cache
				$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('locations'));

				$this->setSuccess(LANG_ORDER_LOCATIONS_SUCCESS);
			}
		}
		redirect('admin/locations/');
    }
    
    public function delete()
    {
    	$this->load->model('locations');
    	$this->form_validation->set_rules('checked_list', LANG_LOCATIONS_CHECKED_LIST);
		if ($this->form_validation->run() !== FALSE) {
			if ($this->locations->deleteLocations($this->form_validation->set_value('checked_list'))) {
				// Clean cache
				$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('locations'));

				$this->setSuccess(LANG_DELETE_LOCATIONS_SUCCESS);
			}
		}
		redirect('admin/locations/');
    }
    
	public function label($location_id = null)
    {
    	$this->load->model('locations');
    	if ($location_id) {
    		// Clean cache
			$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('locations'));
    		if ($this->locations->labelLocationById($location_id)) {
				$this->setSuccess(LANG_LABELED_LOCATIONS_SUCCESS);
	   		} else {
				$this->setError(LANG_LABELED_LOCATIONS_ERROR);
			}
    	} else {
	    	$this->form_validation->set_rules('checked_list', LANG_LOCATIONS_CHECKED_LIST);
			if ($this->form_validation->run() !== FALSE) {
				// Clean cache
				$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('locations'));
				if ($this->locations->labelLocations($this->form_validation->set_value('checked_list'))) {
					$this->setSuccess(LANG_LABELED_LOCATIONS_SUCCESS);
				} else {
					$this->setError(LANG_LABELED_LOCATIONS_ERROR);
				}
			}
    	}
		redirect('admin/locations/');
    }

    /**
     * Ajax request through locations levels browsing
     *
     */
    public function ajax_locations_request($template_name)
    {
	    if ($this->input->post('id') !== false) {
    		$parent_id = $this->input->post('id');
	    	$is_counter = $this->input->post('is_counter');
	    	if ($this->input->post('max_depth'))
	    		$max_depth = $this->input->post('max_depth');
	    	else
	    		$max_depth = 'max';
	    	if ($this->input->post('selected_locations'))
	    		$selected_locations = json_decode($this->input->post('selected_locations'));
	    	else 
	    		$selected_locations = null;
	    	$highlight_element = $this->input->post('highlight_element');
	    	$is_children_label = $this->input->post('is_children_label');
	    	$is_only_labeled = $this->input->post('is_only_labeled');

	    	$this->load->model('locations');
	    	$parent_location = $this->locations->getLocationById($parent_id);

			$loc_levels_count = $this->locations->getLocationsLevelsCount();
			if ($parent_id != 0)
	    		$locations_chain_count = count($this->locations->getLocationsChainFromId($parent_id));
	    	else 
	    		$locations_chain_count = 0;
    		$curr_levels_count = $locations_chain_count + 1;

	    	$parent_location->buildChildren(/*$locations_array, */$max_depth, $is_only_labeled);
	    	$locations_json = array();
	    	$view = $this->load->view();
	    	$view->assign('loc_levels_count', $loc_levels_count);
	    	$view->assign('curr_levels_count', $curr_levels_count);
	    	$rendered_template = $view->fetch(trim($template_name, '/'));
	    	foreach ($parent_location->children AS $location) {
	    		if (!$is_only_labeled || ($is_only_labeled && $location->use_as_label))
					$locations_json[] = $location->render($rendered_template, $is_counter, $max_depth, $selected_locations, $highlight_element, $is_children_label, $is_only_labeled);
	    	}
	    	// also trim last ',' with line-break symbol
        	echo '[' . trim(implode(' ', $locations_json), ',') . ']';
	    }
    }
    
    public function is_unique_location_seoname($seo_name)
    {
    	$this->load->model('locations');
		
		if ($this->locations->is_location_seoname($seo_name)) {
			$this->form_validation->set_message('seoname');
			return FALSE;
		} else {
			return TRUE;
		}
    }
    
    public function location_create()
    {
    	$this->load->model('locations');
    	
    	if ($this->input->post('submit')) {
            $this->form_validation->set_rules('name', LANG_LOCATIONS_NAME, 'required|max_length[35]');
            $this->form_validation->set_rules('seo_name', LANG_LOCATIONS_SEO_NAME, 'required|alpha_dash|max_length[255]|callback_is_unique_location_seoname');
            $this->form_validation->set_rules('geocoded_name', LANG_LOCATIONS_GEO_NAME, 'max_length[255]');

			if ($this->form_validation->run() !== FALSE) {
				$form_result = $this->form_validation->result_array();
				if ($this->locations->saveLocation($form_result)) {
					// Clean cache
					$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('locations'));

					$this->setSuccess(LANG_CREATE_LOCATION_SUCCESS);
					redirect('admin/locations/');
				}
			}
            $location = $this->locations->getLocationFromForm($this->form_validation->result_array());
        } else {
            $location = $this->locations->getNewlocation();
        }
        
        registry::set('breadcrumbs', array(
    		'admin/locations/' => LANG_EDIT_LOCATIONS,
    		LANG_CREATE_LOCATION,
    	));

        $view  = $this->load->view();
        $view->assign('location', $location);
        $view->display('locations/admin_location_settings.tpl');
    }
    
    public function location_create_child($parent_id)
    {
    	$this->load->model('locations');
    	
    	if ($this->input->post('submit')) {
            $this->form_validation->set_rules('name', LANG_LOCATIONS_NAME, 'required|max_length[35]');
            $this->form_validation->set_rules('seo_name', LANG_LOCATIONS_SEO_NAME, 'required|alpha_dash|max_length[255]|callback_is_unique_location_seoname');
            $this->form_validation->set_rules('geocoded_name', LANG_LOCATIONS_GEO_NAME, 'max_length[255]');

			if ($this->form_validation->run() !== FALSE) {
				$form_result = $this->form_validation->result_array();
				if ($this->locations->saveLocation($form_result, $parent_id)) {
					// Clean cache
					$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('locations'));

					$this->setSuccess(LANG_CREATE_CHILD_LOCATION_SUCCESS);
					redirect('admin/locations/');
				}
			}
            $location = $this->locations->getLocationFromForm($this->form_validation->result_array());
        } else {
            $location = $this->locations->getNewlocation();
        }

        registry::set('breadcrumbs', array(
    		'admin/locations/' => LANG_EDIT_LOCATIONS,
    		LANG_CREATE_CHILD_LOCATION,
    	));

        $view  = $this->load->view();
        $view->assign('location', $location);
        $view->assign('parent_location', $this->locations->getLocationById($parent_id));
        $view->display('locations/admin_location_settings.tpl');
    }
    
    public function location_edit($location_id)
    {
    	$this->load->model('locations');
    	
    	$this->locations->setLocationId($location_id);
    	
    	if ($this->input->post('submit')) {
            $this->form_validation->set_rules('name', LANG_LOCATIONS_NAME, 'required|max_length[35]');
            $this->form_validation->set_rules('seo_name', LANG_LOCATIONS_SEO_NAME, 'required|alpha_dash|max_length[255]|callback_is_unique_location_seoname');
            $this->form_validation->set_rules('geocoded_name', LANG_LOCATIONS_GEO_NAME, 'max_length[255]');

			if ($this->form_validation->run() !== FALSE) {
				$form_result = $this->form_validation->result_array();
				if ($this->locations->saveLocationById($form_result)) {
					// Clean cache
					$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('locations'));

					$this->setSuccess(LANG_SAVE_LOCATION_SUCCESS);
					redirect('admin/locations/');
				}
			}
            $location = $this->locations->getLocationFromForm($this->form_validation->result_array());
        } else {
            $location = $this->locations->getLocationById();
        }
        
        registry::set('breadcrumbs', array(
    		'admin/locations/' => LANG_EDIT_LOCATIONS,
    		LANG_EDIT_LOCATION . ' "' . $location->name . '"',
    	));

        $view  = $this->load->view();
        $view->assign('location', $location);
        $view->assign('parent_location', $this->locations->getLocationById($this->locations->getLocationById()->parent_id));
        $view->display('locations/admin_location_settings.tpl');
    }
    
    public function location_delete($location_id)
    {
        $this->load->model('locations');

        $this->locations->setLocationId($location_id);

        if ($this->input->post('yes')) {
            if ($this->locations->deleteLocationById()) {
            	// Clean cache
				$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('locations'));

            	$this->setSuccess(LANG_DELETE_LOCATION_SUCCESS);
                redirect('admin/locations/');
            }
        }

        if ($this->input->post('no')) {
            redirect('admin/locations/');
        }

        if ( !$location = $this->locations->getLocationById()) {
            redirect('admin/locations/');
        }
        
        registry::set('breadcrumbs', array(
    		'admin/locations/' => LANG_EDIT_LOCATIONS,
    		LANG_DELETE_LOCATION . ' "' . $location->name . '"',
    	));

		$view  = $this->load->view();
		$view->assign('options', array($location_id => $location->name));
        $view->assign('heading', LANG_DELETE_LOCATION);
        $view->assign('question', LANG_DELETE_LOCATION_QUEST);
        $view->display('backend/delete_common_item.tpl');
    }
    
    public function locations_settings()
    {
    	$this->load->model('settings', 'settings');
    	$this->load->model('locations');

    	if ($this->input->post('submit')) {
    		$this->form_validation->set_rules('predefined_locations_mode', LANG_PREDEFINED_LOCATIONS_MODE, 'required');
    		$this->form_validation->set_rules('geocoded_locations_mode_districts', LANG_PREDEFINED_LOCATIONS_MODE_UDE_DISTRICTS, 'is_checked');
    		$this->form_validation->set_rules('geocoded_locations_mode_provinces', LANG_PREDEFINED_LOCATIONS_MODE_UDE_PROVINCES, 'is_checked');

			if ($this->form_validation->run() !== FALSE) {
				if ($this->settings->saveSystemSettings($this->form_validation->result_array())) {
					// we will re-GeoCode locations if districts or provinces modes were changed
					/*$old_settings = registry::get('system_settings');
					$new_settings = $this->form_validation->result_array();
					if ($old_settings['geocoded_locations_mode_districts'] != $new_settings['geocoded_locations_mode_districts']
					|| $old_settings['geocoded_locations_mode_provinces'] != $new_settings['geocoded_locations_mode_provinces']) {
						$old_settings['geocoded_locations_mode_districts'] = $new_settings['geocoded_locations_mode_districts'];
						$old_settings['geocoded_locations_mode_provinces'] = $new_settings['geocoded_locations_mode_provinces'];
						registry::set('system_settings', $old_settings);
						
						$this->locations->reGeoCodeAllLocations();
					}*/
					
					// Set predefined locations mode for whole listings_in_locations table
					$new_settings = $this->form_validation->result_array();
					if ($new_settings['predefined_locations_mode'] == 'only') {
						$this->locations->setPredefinedLocationsModeForListings(1);
					}
					if ($new_settings['predefined_locations_mode'] == 'disabled') {
						$this->locations->setPredefinedLocationsModeForListings(0);
					}

					$this->setSuccess(LANG_LOCATIONS_SETTINGS_SAVE_SUCCESS);
					redirect('admin/locations/settings/');
				}
			}
			$settings = $this->form_validation->result_array();
    	} else {
    		$settings = registry::get('system_settings');
    	}

    	$view = $this->load->view();
		$view->assign('settings', $settings);
        $view->display('locations/admin_locations_system_settings.tpl');
    }
}
?>