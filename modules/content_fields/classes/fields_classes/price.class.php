<?php
class priceClass extends processFieldValue
{
	public $name = 'Price';
	public $configuration = true;
	public $search_configuration = true;
	public $order_option = true;

	/**
     * Overload of processFieldValue::select($custom_group_name, $object_id)
     *
     */
	public function select($custom_group_name, $object_id)
    {
    	$CI = &get_instance();
    	$CI->load->model('content_fields', 'content_fields');

    	$CI->content_fields->db->select();
    	$CI->content_fields->db->from('content_fields_type_' . $this->field->type . '_data');
    	$CI->content_fields->db->where('field_id', $this->field->id);
    	$CI->content_fields->db->where('custom_name', $custom_group_name);
    	$CI->content_fields->db->where('object_id', $object_id);
    	$query = $CI->content_fields->db->get();

		if ($query->num_rows()) {
			$row = $query->row_array();
			$this->field->value = array('field_currency' => $row['field_currency'], 'field_value' => $row['field_value']);
			$this->field->field_value_id = $row['id'];
		} else {
			$this->field->value = array('field_currency' => '', 'field_value' => '');
		}
	}
    
    /**
     * Overload of processFieldValue::save($custom_group_name, $object_id, $form)
     *
     */
    public function save($custom_group_name, $object_id, $form_result)
    {
    	$CI = &get_instance();
    	$CI->load->model('content_fields', 'content_fields');

    	if (isset($form_result['field_currency_' . $this->field->seo_name]) && isset($form_result['field_value_' . $this->field->seo_name])) {
	    	$CI->content_fields->db->set('field_id', $this->field->id);
	    	$CI->content_fields->db->set('custom_name', $custom_group_name);
	    	$CI->content_fields->db->set('object_id', $object_id);
	    	$CI->content_fields->db->set('field_currency', $form_result['field_currency_' . $this->field->seo_name]);
	    	$CI->content_fields->db->set('field_value', $form_result['field_value_' . $this->field->seo_name]);
	    	return $CI->content_fields->db->insert('content_fields_type_' . $this->field->type . '_data');
	    } else {
	    	return true;
	    }
    }
    
    /**
     * Overload of processFieldValue::update($custom_group_name, $object_id, $form)
     *
     */
    public function update($custom_group_name, $object_id, $form_result)
    {
    	if ($this->field->field_value_id == 'new') {
    		return $this->save($custom_group_name, $object_id, $form_result);
    	} else {
	    	if (isset($form_result['field_currency_' . $this->field->seo_name]) && isset($form_result['field_value_' . $this->field->seo_name])) {
	    		$CI = &get_instance();
	    		$CI->load->model('content_fields', 'content_fields');

		    	$CI->content_fields->db->set('field_currency', $form_result['field_currency_' . $this->field->seo_name]);
	    		$CI->content_fields->db->set('field_value', $form_result['field_value_' . $this->field->seo_name]);
		    	$CI->content_fields->db->where('field_id', $this->field->id);
		    	$CI->content_fields->db->where('custom_name', $custom_group_name);
		    	$CI->content_fields->db->where('object_id', $object_id);
		    	return $CI->content_fields->db->update('content_fields_type_' . $this->field->type . '_data');
	    	} else {
	    		return true;
	    	}
    	}
    }
    
    /**
     * Overload of processFieldValue::getValueFromForm($form_result)
     * 
     * Return field's value after the form was not valid
     *
     * @param object $form
     */
    public function getValueFromForm($form_result)
    {
    	$seo_name = $this->field->seo_name;
	    $this->field->value = array('field_currency' => $form_result['field_currency_' . $seo_name], 
	    							'field_value' => $form_result['field_value_' . $seo_name]);
    }
	
    public function renderDataEntry()
    {
    	$CI = &get_instance();
    	$CI->load->model('content_fields', 'content_fields');

        $view =$CI->load->view();
        $view->assign('field', $this->field);
        $view->assign('options', $this->field->options);
        $view->assign('field_currency', $this->field->value['field_currency']);
        $view->assign('field_value', $this->field->value['field_value']);
        
        if ($view->template_exists('content_fields/fields/' . $this->field->type . '/field_input-' . $this->field->seo_name . '.tpl')) {
        	$template = 'content_fields/fields/' . $this->field->type . '/field_input-' . $this->field->seo_name . '.tpl';
        } else {
        	$template = 'content_fields/fields/' . $this->field->type . '/field_input.tpl';
        }
        return $view->fetch($template);
    }
    
    public function renderDataOutput($view_type)
    {
    	$CI = &get_instance();
    	$CI->load->model('content_fields', 'content_fields');

		$field_value = $this->field->value['field_value'];

		foreach ($this->field->options AS $option) {
			if ($option['id'] == $this->field->value['field_currency']) {
				$field_currency = $option['option_name'];
			}
		}
    	
        $view = $CI->load->view();
        $view->assign('field', $this->field);
        $view->assign('field_currency', $field_currency);
        $view->assign('field_value', $field_value);

        // Find the most relevant template, according to view type and field seo name
        if ($view->template_exists('content_fields/fields/' . $this->field->type . '/field_output-' . $view_type . '-' . $this->field->seo_name . '.tpl')) {
        	$template = 'content_fields/fields/' . $this->field->type . '/field_output-' . $view_type . '-' . $this->field->seo_name . '.tpl';
        } elseif ($view->template_exists('content_fields/fields/' . $this->field->type . '/field_output-' . $this->field->seo_name . '.tpl')) {
        	$template = 'content_fields/fields/' . $this->field->type . '/field_output-' . $this->field->seo_name . '.tpl';
        } elseif ($view->template_exists('content_fields/fields/' . $this->field->type . '/field_output-' . $view_type . '.tpl')) {
        	$template = 'content_fields/fields/' . $this->field->type . '/field_output-' . $view_type . '.tpl';
        } else {
        	$template = 'content_fields/fields/' . $this->field->type . '/field_output.tpl';
        }
        return $view->fetch($template);
    }
    
    public function getValueAsString()
    {
    	if (isset($this->field->value['field_value']) && isset($this->field->value['field_currency']) && $this->field->value['field_currency'] != -1) {
	    	$field_value = $this->field->value['field_value'];
			foreach ($this->field->options AS $option) {
				if ($option['id'] == $this->field->value['field_currency']) {
					$field_currency = $option['option_name'];
				}
			}
			return $field_currency . ' ' . $field_value;
    	}
    }

    public function validate($form_validation)
    {
    	$selected = ($this->field->required) ? '|selected' : '';
    	$required = ($this->field->required) ? '|required' : '';

		$form_validation->set_rules('field_currency_' . $this->field->seo_name, $this->field->name . ' ' . LANG_CURRENCY_TH, $selected);
		$form_validation->set_rules('field_value_' . $this->field->seo_name, $this->field->name, 'numeric' . $required);
    }

    public function configurationPage()
    {
    	$CI = &get_instance();
    	$CI->load->model('content_fields', 'content_fields');

    	if ($CI->uri->segment(5) == 'add_option') {
    		// Create new option
    		if ($CI->input->post('submit')) {
	        	$CI->form_validation->set_rules('id', LANG_FIELD_ID, 'required|integer');
	        	$CI->form_validation->set_rules('name', LANG_FIELD_ID, 'required');
	        	
	        	if ($CI->form_validation->run() !== FALSE) {
					if ($this->createOption()) {
						// Clean cache
						$CI->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('content_fields'));

						$CI->setSuccess(LANG_FIELD_CONFIG_SAVE_SUCCESS);
						redirect('admin/fields/configure/' . $this->field->id);
					}
				} else {
					$name = $CI->form_validation->set_value('name');
				}
    		} else {
    			// default option name
    			$name = '';
    		}
    		
    		registry::add('breadcrumbs', array(
    			LANG_CREATE_FIELD_OPTION_TITLE)
    		);

    		$view  = $CI->load->view();
	        $view->assign('field', $this->field);
	        $view->assign('option_id', 'new');
	        $view->assign('name', $name);
	        $view->display('content_fields/fields/' . $this->field->type . '/field_option_settings.tpl');
    	} elseif ($CI->uri->segment(5) == 'edit_option') {
    		// Edit option
    		$option_id = $CI->uri->segment(6);
    		if ($CI->input->post('submit')) {
	        	$CI->form_validation->set_rules('id', LANG_FIELD_ID, 'required|integer');
	        	$CI->form_validation->set_rules('name', LANG_FIELD_ID, 'required');
	        	
	        	if ($CI->form_validation->run() !== FALSE) {
					if ($this->saveOptionById($option_id)) {
						// Clean cache
						$CI->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('content_fields'));

						$CI->setSuccess(LANG_FIELD_CONFIG_SAVE_SUCCESS);
						redirect('admin/fields/configure/' . $this->field->id);
					}
				} else {
					$name = $CI->form_validation->set_value('name');
				}
    		} else {
    			foreach ($this->field->options AS $key=>$item) {
    				if ($item['id'] == $option_id) {
    					$name = $item['option_name'];
    				}
    			}
    		}
    		
    		registry::add('breadcrumbs', array(
    			LANG_EDIT_FIELD_OPTION_TITLE)
    		);

    		$view  = $CI->load->view();
	        $view->assign('field', $this->field);
	        $view->assign('name', $name);
	        $view->display('content_fields/fields/' . $this->field->type . '/field_option_settings.tpl');
    	} elseif ($CI->uri->segment(5) == 'delete_option') {
    		// Delete option
    		$option_id = $CI->uri->segment(6);
    		
    		if ($CI->input->post('yes')) {
    			$CI->form_validation->set_rules('options', LANG_FIELD_OPTION_NAME, 'required');
    			
    			if ($CI->form_validation->run() !== FALSE) {
		            if ($this->deleteOptionById()) {
		            	// Clean cache
						$CI->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('content_fields'));

		            	$CI->setSuccess(LANG_FIELD_CONFIG_SAVE_SUCCESS);
		                redirect('admin/fields/configure/' . $this->field->id);
		            }
    			}
	        }
	
	        if ($CI->input->post('no')) {
	            redirect('admin/fields/configure/' . $this->field->id);
	        }

    		foreach ($this->field->options AS $key=>$item) {
    			if ($item['id'] == $option_id) {
    				$name = $item['option_name'];
    			}
    		}
    		
    		registry::add('breadcrumbs', array(
    			LANG_DELETE_FIELD_OPTION_TITLE)
    		);

    		// Use common delete view
    		$view  = $CI->load->view();
	        $view->assign('options', array($option_id => $name));
	        $view->assign('heading', LANG_DELETE_FIELD_OPTION_TITLE);
	        $view->assign('question', LANG_DELETE_OPTION_QUESTION);
	        $view->display('backend/delete_common_item.tpl');
    	} else {
    		// Manage options table
    		if ($CI->input->post('submit')) {
	        	$CI->form_validation->set_rules('id', LANG_FIELD_ID, 'required|integer');
	        	$CI->form_validation->set_rules('serialized_order', LANG_SERIALIZED_ORDER_VALUE);

				if ($CI->form_validation->run() !== FALSE) {
					if ($this->saveOptionsOrder()) {
						// Clean cache
						$CI->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('content_fields'));

						$CI->setSuccess(LANG_FIELD_CONFIG_SAVE_SUCCESS);
						redirect(current_url());
					}
				}
	        }

	        $view  = $CI->load->view();
	        $view->assign('field', $this->field);
	        $view->assign('options', $this->field->options);
	        $view->addJsFile('jquery.tablednd_0_5.js');
	        $view->display('content_fields/fields/' . $this->field->type . '/field_configure.tpl');
    	}
	}
	
	public function createOption()
	{
		$CI = &get_instance();
		$CI->load->model('content_fields', 'content_fields');

		$name = $CI->form_validation->set_value('name');
		$order_num = count($this->field->options) + 1;

    	$CI->content_fields->db->set('field_id', $this->field->id);
    	$CI->content_fields->db->set('option_name', $name);
    	$CI->content_fields->db->set('order_num', $order_num);
    	return $CI->content_fields->db->insert('content_fields_type_' . $this->field->type);
    }
    
    public function saveOptionById($option_id)
	{
		$CI = &get_instance();
		$CI->load->model('content_fields', 'content_fields');

		$name = $CI->form_validation->set_value('name');

    	$CI->content_fields->db->set('option_name', $name);
    	$CI->content_fields->db->where('id', $option_id);
    	return $CI->content_fields->db->update('content_fields_type_' . $this->field->type);
    }
    
    public function deleteOptionById()
    {
    	$CI = &get_instance();
    	$CI->load->model('content_fields', 'content_fields');

    	$options = $CI->form_validation->set_value('options');
    	
    	return $CI->content_fields->db->delete('content_fields_type_' . $this->field->type, array('id' => $options[0]));
    }

	public function saveOptionsOrder()
	{
		$CI = &get_instance();
		$CI->load->model('content_fields', 'content_fields');

		$serialized_order = $CI->form_validation->set_value('serialized_order');

		if (!empty($serialized_order)) {
			$a = explode("=", $serialized_order);
		   	$start = 0;
		   	foreach ($a AS $row) {
		   		$b = explode("&", $row);
		   		foreach ($b AS $_id) {
		   			$_id = trim($_id, "_id");
		   			if (is_numeric($_id)) {
		   				$start++;
		   				$CI->content_fields->db->set('order_num', $start);
		   				$CI->content_fields->db->where('id', $_id);
		   				$CI->content_fields->db->update('content_fields_type_' . $this->field->type);
		   			}
		   		}
		   	}
		}
		return true;
	}

	public function getDefaultOptions()
	{
		return array();
	}
	
	
	// --------------------------------------------------------------------------------------------
	// Search methods
	// --------------------------------------------------------------------------------------------
	public function searchConfigurationPage()
	{
		$CI = &get_instance();
		$CI->load->model('content_fields', 'content_fields');

		if ($CI->uri->segment(5) == 'manage_options') {
			// Manage options table
    		if ($CI->input->post('submit')) {
	        	//$CI->form_validation->set_rules('id', LANG_FIELD_ID, 'required|integer');
	        	$CI->form_validation->set_rules('serialized_order', LANG_SERIALIZED_ORDER_VALUE);

				if ($CI->form_validation->run() !== FALSE) {
					if ($this->saveMinMaxOptionsOrder()) {
						$CI->setSuccess(LANG_FIELD_CONFIG_SAVE_SUCCESS);
						redirect(current_url());
					}
				}
	        } else {
	        	$min_max_options = $this->selectMinMaxOptions($this->field->type);
	        }
	        
	        registry::add('breadcrumbs', array(
    			LANG_MANAGE_FIELD_SEARCH_OPTIONS
    		));

	        $view  = $CI->load->view();
	        $view->assign('field', $this->field);
	        $view->assign('min_max_options', $min_max_options);
	        $view->addJsFile('jquery.tablednd_0_5.js');
	        $view->display('content_fields/search/' . $this->field->type . '/search_manage_options.tpl');
		} elseif ($CI->uri->segment(5) == 'add_option') {
			// Create new option
    		if ($CI->input->post('submit')) {
	        	//$CI->form_validation->set_rules('id', LANG_FIELD_ID, 'required|integer');
	        	$CI->form_validation->set_rules('name', LANG_FIELD_OPTION_NAME, 'required|numeric');
	        	
	        	if ($CI->form_validation->run() !== FALSE) {
					if ($this->createMinMaxOption()) {
						$CI->setSuccess(LANG_FIELD_CONFIG_SAVE_SUCCESS);
						redirect('admin/fields/configure_search/' . $this->field->id . '/manage_options/');
					}
				} else {
					$name = $CI->form_validation->set_value('name');
				}
    		} else {
    			// default option name
    			$name = '';
    		}
    		
    		registry::add('breadcrumbs', array(
    			'admin/fields/configure_search/' . $this->field->id . '/manage_options/' => LANG_MANAGE_FIELD_SEARCH_OPTIONS,
    			LANG_FIELD_SEARCH_ADD_OPTION
    		));

    		$view  = $CI->load->view();
	        $view->assign('field', $this->field);
	        $view->assign('option_id', 'new');
	        $view->assign('name', $name);
	        $view->display('content_fields/search/' . $this->field->type . '/search_minmax_option_settings.tpl');
		} elseif ($CI->uri->segment(5) == 'edit_option') {
			// Edit option
    		$option_id = $CI->uri->segment(6);
    		if ($CI->input->post('submit')) {
	        	//$CI->form_validation->set_rules('id', LANG_FIELD_ID, 'required|integer');
	        	$CI->form_validation->set_rules('name', LANG_FIELD_OPTION_NAME, 'required|numeric');
	        	
	        	if ($CI->form_validation->run() !== FALSE) {
					if ($this->saveMinMaxOptionById($option_id)) {
						$CI->setSuccess(LANG_FIELD_CONFIG_SAVE_SUCCESS);
						redirect('admin/fields/configure_search/' . $this->field->id . '/manage_options/');
					}
				} else {
					$name = $CI->form_validation->set_value('name');
				}
    		} else {
    			$options = $this->selectMinMaxOptions($this->field->type);
    			foreach ($options AS $key=>$item) {
    				if ($item['id'] == $option_id) {
    					$name = $item['option_name'];
    				}
    			}
    		}
    		
    		registry::add('breadcrumbs', array(
    			'admin/fields/configure_search/' . $this->field->id . '/manage_options/' => LANG_MANAGE_FIELD_SEARCH_OPTIONS,
    			LANG_FIELD_SEARCH_EDIT_OPTION
    		));

    		$view  = $CI->load->view();
	        $view->assign('field', $this->field);
	        $view->assign('name', $name);
	        $view->display('content_fields/search/' . $this->field->type . '/search_minmax_option_settings.tpl');
	    } elseif ($CI->uri->segment(5) == 'delete_option') {
	    	// Delete option
    		$option_id = $CI->uri->segment(6);
    		
    		if ($CI->input->post('yes')) {
    			$CI->form_validation->set_rules('options', LANG_FIELD_OPTION_NAME, 'required');
    			
    			if ($CI->form_validation->run() !== FALSE) {
		            if ($this->deleteMinMaxOptionById()) {
		            	$CI->setSuccess(LANG_FIELD_CONFIG_SAVE_SUCCESS);
		                redirect('admin/fields/configure_search/' . $this->field->id . '/manage_options/');
		            }
    			}
	        }
	
	        if ($CI->input->post('no')) {
	            redirect('admin/fields/configure_search/' . $this->field->id . '/manage_options/');
	        }
    		
    		$options = $this->selectMinMaxOptions($this->field->type);
    		foreach ($options AS $key=>$item) {
    			if ($item['id'] == $option_id) {
    				$name = $item['option_name'];
    			}
    		}
    		
    		registry::add('breadcrumbs', array(
    			'admin/fields/configure_search/' . $this->field->id . '/manage_options/' => LANG_MANAGE_FIELD_SEARCH_OPTIONS,
    			LANG_FIELD_SEARCH_DELETE_OPTION
    		));

    		// Use common delete view
    		$view  = $CI->load->view();
	        $view->assign('options', array($option_id => $name));
	        $view->assign('heading', LANG_DELETE_FIELD_OPTION_TITLE);
	        $view->assign('question', LANG_DELETE_OPTION_QUESTION);
	        $view->display('backend/delete_common_item.tpl');
		} else {
			// Search configuration field page
			if ($CI->input->post('submit')) {
				$CI->form_validation->set_rules('search_type', LANG_MAX_LENGTH_TEXT, 'required');

				if ($CI->form_validation->run() !== FALSE) {
					if ($this->saveSearchOptionsByFieldId($CI->form_validation->result_array())) {
						$CI->setSuccess(LANG_FIELD_CONFIG_SAVE_SUCCESS);
						redirect(current_url());
					}
				} else {
					$search_options = $this->getSearchOptionsFromForm();
				}
			} else {
				$search_options = $CI->content_fields->selectSearchFieldConfiguration($this->field->type);
			}

			$view  = $CI->load->view();
			$view->assign('field', $this->field);
			$view->assign('search_type', $search_options[0]['search_type']);
			$view->display('content_fields/search/' . $this->field->type . '/search_configure.tpl');
		}
	}
	
	public function saveMinMaxOptionsOrder()
	{
		$CI = &get_instance();
		$CI->load->model('content_fields', 'content_fields');

		$serialized_order = $CI->form_validation->set_value('serialized_order');

		if (!empty($serialized_order)) {
			$a = explode("=", $serialized_order);
		   	$start = 0;
		   	foreach ($a AS $row) {
		   		$b = explode("&", $row);
		   		foreach ($b AS $_id) {
		   			$_id = trim($_id, "_id");
		   			if (is_numeric($_id)) {
		   				$start++;
		   				$CI->content_fields->db->set('order_num', $start);
		   				$CI->content_fields->db->where('id', $_id);
		   				$CI->content_fields->db->update('search_fields_type_' . $this->field->type . '_options');
		   			}
		   		}
		   	}
		}
		return true;
	}
	
	public function saveSearchOptionsByFieldId($form_result)
	{
		$CI = &get_instance();
		$CI->load->model('content_fields', 'content_fields');

		$CI->content_fields->db->set('field_id', $this->field->id);
		$CI->content_fields->db->set('search_type', $form_result['search_type']);
		return $CI->content_fields->db->on_duplicate_insert('search_fields_type_' . $this->field->type);
	}
	
	public function getSearchOptionsFromForm()
	{
		$CI = &get_instance();
		$CI->load->model('content_fields', 'content_fields');

		$search_options[0]['search_type'] = $CI->form_validation->set_value('search_type');
		return $search_options;
	}
	
	public function selectMinMaxOptions($field_type)
	{
		$CI = &get_instance();
		$CI->load->model('content_fields', 'content_fields');

		$CI->content_fields->db->select();
		$CI->content_fields->db->from('search_fields_type_' . $field_type . '_options');
		$CI->content_fields->db->where('field_id', $this->field->id);
		$CI->content_fields->db->order_by('order_num', 'asc');
		$query = $CI->content_fields->db->get();
		
		return $query->result_array();
	}
	
	public function createMinMaxOption()
	{
		$CI = &get_instance();
		$CI->load->model('content_fields', 'content_fields');

		$name = $CI->form_validation->set_value('name');
		$options = $this->selectMinMaxOptions($this->field->type);
		$order_num = count($options) + 1;

    	$CI->content_fields->db->set('field_id', $this->field->id);
    	$CI->content_fields->db->set('option_name', $name);
    	$CI->content_fields->db->set('order_num', $order_num);
    	return $CI->content_fields->db->insert('search_fields_type_' . $this->field->type . '_options');
    }
    
    public function saveMinMaxOptionById($option_id)
	{
		$CI = &get_instance();
		$CI->load->model('content_fields', 'content_fields');

		$name = $CI->form_validation->set_value('name');

    	$CI->content_fields->db->set('option_name', $name);
    	$CI->content_fields->db->where('id', $option_id);
    	return $CI->content_fields->db->update('search_fields_type_' . $this->field->type . '_options');
    }
    
    public function deleteMinMaxOptionById()
    {
    	$CI = &get_instance();
    	$CI->load->model('content_fields', 'content_fields');

    	$options = $CI->form_validation->set_value('options');
    	
    	return $CI->content_fields->db->delete('search_fields_type_' . $this->field->type . '_options', array('id' => $options[0]));
    }
	
	public function getDefaultSearchOptions()
	{
		$search_options[0]['search_type'] = 'exact_match';  // min_max
		return $search_options;
	}
	
	public function renderSearch($args)
	{
		$CI = &get_instance();
		$CI->load->model('content_fields', 'content_fields');

		if ($this->field->search_options[0]['search_type'] == 'min_max') {
			$min_max_options = $this->selectMinMaxOptions($this->field->type);

			$view = $CI->load->view();
			if (count($min_max_options)) {
				$view->assign('field', $this->field);
				$view->assign('min_max_options', $min_max_options);
				$view->assign('options', $this->field->options);
				$view->assign('currency_index', "search_currency_" . $this->field->seo_name);
				$view->assign('from_index', "search_from_" . $this->field->seo_name);
				$view->assign('to_index', "search_to_" . $this->field->seo_name);
				$view->assign('args', $args);
				$view->display('content_fields/search/' . $this->field->type . '/search_minmax_select.tpl');
			} else {
				$view->assign('field', $this->field);
				$view->assign('currency_index', "search_currency_" . $this->field->seo_name);
				$view->assign('options', $this->field->options);
				$view->assign('from_index', "search_from_" . $this->field->seo_name);
				$view->assign('to_index', "search_to_" . $this->field->seo_name);
				$view->assign('args', $args);
				$view->display('content_fields/search/' . $this->field->type . '/search_minmax.tpl');
			}
		} else {
			// exact_match
			$view = $CI->load->view();
			$view->assign('field', $this->field);
			$view->assign('max_length', $this->field->options[0]['max_length']);
			$view->assign('field_index', "search_" . $this->field->seo_name);
			$view->assign('args', $args);
			$view->display('content_fields/search/' . $this->field->type . '/search_match.tpl');
		}
	}
	
	public function validateSearch($fields_group_name, $args, &$search_url_rebuild)
	{
		$CI = &get_instance();
		$CI->load->model('content_fields', 'content_fields');

		if (isset($args["search_currency_" . $this->field->seo_name])) {
			$currency = $args["search_currency_" . $this->field->seo_name];

			if ($this->field->search_options[0]['search_type'] == 'min_max') {
				$min_max_options = $this->selectMinMaxOptions($this->field->type);
	
				if (isset($args['search_from_' . $this->field->seo_name]) && is_numeric($args['search_from_' . $this->field->seo_name]/*) || $args['search_from_' . $this->field->seo_name] == 'min'*/)) {
					$from_value = $args['search_from_' . $this->field->seo_name];
				} else {
					$from_value = '';
				}
				if (isset($args['search_to_' . $this->field->seo_name]) && is_numeric($args['search_to_' . $this->field->seo_name]/*) || $args['search_to_' . $this->field->seo_name] == 'max'*/)) {
					$to_value = $args['search_to_' . $this->field->seo_name];
				} else {
					$to_value = '';
				}
	
				$CI->db->select('cftd.object_id');
				$CI->db->from('content_fields_type_price_data AS cftd');
				$CI->db->join('content_fields_type_price AS cft', 'cftd.field_currency=cft.id');
				$CI->db->where('cftd.field_id', $this->field->id);
				$CI->db->where('cftd.custom_name', $fields_group_name);
				$CI->db->where('cft.option_name', $currency);
				if ($from_value != '') {
					$CI->db->where('cftd.field_value >=', $from_value, false);
					$search_url_rebuild .= 'search_from_' . $this->field->seo_name . '/' . $from_value . '/';
				}
				if ($to_value != '') {
					$CI->db->where('cftd.field_value <=', $to_value, false);
					$search_url_rebuild .= 'search_to_' . $this->field->seo_name . '/' . $to_value . '/';
				}
				$search_url_rebuild .= 'search_currency_' . $this->field->seo_name . '/' . $currency . '/';
				$sql = $CI->db->_compile_select();
				$CI->db->_reset_select();
				return array('AND' => str_replace("\n"," ",$sql));
			} else {
				// exact_match
				if (isset($args['search_' . $this->field->seo_name]) && is_numeric($args['search_' . $this->field->seo_name])) {
					$value = $args['search_' . $this->field->seo_name];
				} else {
					$value = '';
				}
	
				if ($value != '') {
					$CI->db->select('cftvd.object_id');
					$CI->db->from('content_fields_type_varchar_data AS cftvd');
					$CI->db->where('cftvd.field_id', $this->field->id);
					$CI->db->where('cftvd.custom_name', $fields_group_name);
					$CI->db->where('cftvd.field_value =', $value, false);
					$search_url_rebuild .= 'search_' . $this->field->seo_name . '/' . $value . '/';
	
					$sql = $CI->db->_compile_select();
					$CI->db->_reset_select();
					return array('AND' => str_replace("\n"," ",$sql));
				}
			}
		}
	}
}
?>