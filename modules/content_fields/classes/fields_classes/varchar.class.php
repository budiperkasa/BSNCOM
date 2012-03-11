<?php
class varcharClass extends processFieldValue
{
	public $name = 'String';
	public $configuration = true;
	public $search_configuration = true;
	public $order_option = true;

    public function renderDataEntry()
    {
    	$CI = &get_instance();
    	$CI->load->model('content_fields', 'content_fields');

        $max_length = $this->field->options[0]['max_length'];
        $regex = $this->field->options[0]['regex'];
        $is_numeric = $this->field->options[0]['is_numeric'];

        if ($max_length > 60) {
        	$field_length = 60;
        } else {
        	$field_length = $max_length;
        }
     
        $view = $CI->load->view();   
        $view->assign('field', $this->field);
        $view->assign('value', $this->field->value);
        $view->assign('max_length', $max_length);
        $view->assign('is_numeric', $is_numeric);
        $view->assign('regex', $regex);
        $view->assign('field_length', $field_length);
        
        if ($view->template_exists('content_fields/fields/' . $this->field->type . '/field_input-' . $this->field->seo_name . '.tpl')) {
        	$template = 'content_fields/fields/' . $this->field->type . '/field_input-' . $this->field->seo_name . '.tpl';
        } else {
        	$template = 'content_fields/fields/' . $this->field->type . '/field_input.tpl';
        }
        return $view->fetch($template);
    }
    
    public function renderDataOutput($view_type)
    {
        $view = new view;
        $view->assign('field', $this->field);

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
    	if (isset($this->field->value))
			return $this->field->value;
    }

    public function validate($form_validation)
    {
        $max_length = $this->field->options[0]['max_length'];
        $regex = $this->field->options[0]['regex'];
        $is_numeric = $this->field->options[0]['is_numeric'];

    	$required = ($this->field->required) ? '|required' : '';
    	$numeric = ($is_numeric) ? '|numeric' : '';
    	$form_validation->set_rules('field_' . $this->field->seo_name, $this->field->name, 'max_length[' . $max_length . ']' . $required . $numeric);
    	
    	// Add regex validation rule
    	if ($_POST['field_' . $this->field->seo_name] && $regex) {
    		if (!preg_match('/^' . $regex . '$/', $_POST['field_' . $this->field->seo_name])) {
    			$form_validation->_error_array['field_' . $this->field->seo_name] = "Field " . $this->field->name . " doesn't match template!";
    		}
    	}
    }

    public function configurationPage()
    {
    	$CI = &get_instance();
    	$CI->load->model('content_fields', 'content_fields');

        if ($CI->input->post('submit')) {
            $CI->form_validation->set_rules('id', LANG_FIELD_ID, 'required');
            $CI->form_validation->set_rules('max_length', LANG_MAX_LENGTH_TEXT, 'integer|required');
            $CI->form_validation->set_rules('regex', LANG_REGEX_TEMPLATE, 'max_length[255]');
            $CI->form_validation->set_rules('is_numeric', LANG_IS_NUMERIC, 'is_checked');

            if ($CI->form_validation->run() !== FALSE) {
				if ($this->saveOptionsByFieldId($CI->form_validation->result_array())) {
					// Clean cache
					$CI->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('content_fields'));

					$CI->setSuccess(LANG_FIELD_CONFIG_SAVE_SUCCESS);
					redirect('admin/fields/');
				}
			} else {
				$options = $this->getOptionsFromForm();
			}
        } else {
        	$options = $this->field->options;
        }

        if (empty($options)) {
        	$options = $this->getDefaultOptions();
        }
        $view  = $CI->load->view();
        $view->assign('field', $this->field);
        $view->assign('max_length', $options[0]['max_length']);
        $view->assign('regex', $options[0]['regex']);
        $view->assign('is_numeric', $options[0]['is_numeric']);
        $view->display('content_fields/fields/' . $this->field->type . '/field_configure.tpl');
	}
	
	public function getOptionsFromForm()
	{
		$CI = &get_instance();
		$CI->load->model('content_fields', 'content_fields');

		$options[0]['max_length'] = $CI->form_validation->set_value('max_length');
		$options[0]['regex'] = $CI->form_validation->set_value('regex');
		$options[0]['is_numeric'] = $CI->form_validation->set_value('is_numeric');
		return $options;
	}

	public function saveOptionsByFieldId($form_result)
	{
		$CI = &get_instance();
    	$CI->load->model('content_fields', 'content_fields');

		$CI->content_fields->db->set('field_id', $this->field->id);
		$CI->content_fields->db->set('max_length', $form_result['max_length']);
		$CI->content_fields->db->set('is_numeric', $form_result['is_numeric']);
		$CI->content_fields->db->set('regex', $form_result['regex']);

		if ($CI->content_fields->db->on_duplicate_insert('content_fields_type_' . $this->field->type)) {
			// Is main cofiguration of field was changed - it was string, but become numeric or vice versa
			if ($this->field->options[0]['is_numeric'] != $form_result['is_numeric']) {
				// Clear search configuration
				$CI->db->delete('search_fields_type_' . $this->field->type, array('field_id' => $this->field->id));
			}
			return true;
		}
	}
	
	public function getDefaultOptions()
	{
		$options[0]['max_length'] = 60;
		$options[0]['regex'] = '';
		$options[0]['is_numeric'] = 0;
		return $options;
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
				//$CI->form_validation->set_rules('id', LANG_FIELD_ID, 'required');
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

			if ($this->field->options[0]['is_numeric']) {
		        $view->display('content_fields/search/' . $this->field->type . '/search_configure_numeric.tpl');
			} else {
		        $view->display('content_fields/search/' . $this->field->type . '/search_configure_string.tpl');
			}
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
	
	public function getDefaultSearchOptions($field_type)
	{
		if ($this->field->options[0]['is_numeric']) {
			$search_options[0]['search_type'] = 'exact_match';  // min_max
			return $search_options;
		} else {
			$search_options[0]['search_type'] = 'connect_what_field';  // self_search
			return $search_options;
		}
	}
	
	public function renderSearch($args)
	{
		$CI = &get_instance();
		$CI->load->model('content_fields', 'content_fields');

		if ($this->field->options[0]['is_numeric']) {
			if ($this->field->search_options[0]['search_type'] == 'min_max') {
				$min_max_options = $this->selectMinMaxOptions($this->field->type);

				$view = $CI->load->view();
				if (count($min_max_options)) {
					$view->assign('field', $this->field);
					$view->assign('min_max_options', $min_max_options);
					$view->assign('from_index', "search_from_" . $this->field->seo_name);
					$view->assign('to_index', "search_to_" . $this->field->seo_name);
					$view->assign('args', $args);
					$view->display('content_fields/search/' . $this->field->type . '/search_numeric_minmax_select.tpl');
				} else {
					$view->assign('field', $this->field);
					$view->assign('max_length', $this->field->options[0]['max_length']);
					$view->assign('from_index', "search_from_" . $this->field->seo_name);
					$view->assign('to_index', "search_to_" . $this->field->seo_name);
					$view->assign('args', $args);
					$view->display('content_fields/search/' . $this->field->type . '/search_numeric_minmax.tpl');
				}
			} else {
				// exact_match
				$view = $CI->load->view();
				$view->assign('field', $this->field);
				$view->assign('max_length', $this->field->options[0]['max_length']);
				$view->assign('field_index', "search_" . $this->field->seo_name);
				$view->assign('args', $args);
				$view->display('content_fields/search/' . $this->field->type . '/search_numeric_match.tpl');
			}
		} else {
			if ($this->field->search_options[0]['search_type'] == 'self_search') {
				$view = $CI->load->view();
				$view->assign('field', $this->field);
				$view->assign('max_length', $this->field->options[0]['max_length']);
				$view->assign('field_index', "search_" . $this->field->seo_name);
				$view->assign('args', $args);
				$view->display('content_fields/search/' . $this->field->type . '/search_string.tpl');
			}
		}
	}
	
	public function validateSearch($fields_group_name, $args, &$search_url_rebuild)
	{
		$CI = &get_instance();
		$CI->load->model('content_fields', 'content_fields');

		if ($this->field->options[0]['is_numeric']) {
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

				if ($from_value != '' OR $to_value != '') {
					$CI->db->select('cftd.object_id');
					$CI->db->from('content_fields_type_varchar_data AS cftd');
					$CI->db->where('cftd.field_id', $this->field->id);
					$CI->db->where('cftd.custom_name', $fields_group_name);
					if ($from_value != '') {
						$CI->db->where('cftd.field_value >=', $from_value, false);
						$search_url_rebuild .= 'search_from_' . $this->field->seo_name . '/' . $from_value . '/';
					}
					if ($to_value != '') {
						$CI->db->where('cftd.field_value <=', $to_value, false);
						$search_url_rebuild .= 'search_to_' . $this->field->seo_name . '/' . $to_value . '/';
					}
					$sql = $CI->db->_compile_select();
					$CI->db->_reset_select();
					return array('AND' => str_replace("\n"," ",$sql));
				}
			} else {
				// exact_match
				if (isset($args['search_' . $this->field->seo_name]) && is_numeric($args['search_' . $this->field->seo_name])) {
					$value = $args['search_' . $this->field->seo_name];
				} else {
					$value = '';
				}
				
				if ($value != '') {
					$CI->db->select('cftd.object_id');
					$CI->db->from('content_fields_type_varchar_data AS cftd');
					$CI->db->where('cftd.field_id', $this->field->id);
					$CI->db->where('cftd.custom_name', $fields_group_name);
					$CI->db->where('cftd.field_value =', $value, false);
					$search_url_rebuild .= 'search_' . $this->field->seo_name . '/' . $value . '/';

					$sql = $CI->db->_compile_select();
					$CI->db->_reset_select();
					return array('AND' => str_replace("\n"," ",$sql));
				}
			}
		} else {

			if ($this->field->search_options[0]['search_type'] == 'self_search') {
				if (isset($args['search_' . $this->field->seo_name])) {
					$value = $args['search_' . $this->field->seo_name];
				} else {
					$value = '';
				}

				if ($value != '') {
					$CI->db->select('cftd.object_id');
					$CI->db->from('content_fields_type_varchar_data AS cftd');
					$CI->db->where('cftd.field_id', $this->field->id);
					$CI->db->where('cftd.custom_name', $fields_group_name);
					$CI->db->like('cftd.field_value', $value);
					$search_url_rebuild .= 'search_' . $this->field->seo_name . '/' . $value . '/';
	
					$sql = $CI->db->_compile_select();
					$CI->db->_reset_select();
					return array('AND' => str_replace("\n"," ",$sql));
				}
			} else {
				// connected to 'What' field
				if (isset($args['what_search'])) {
					$value = $args['what_search'];
					if (isset($args['what_match'])) {
		    			$what_match = $args['what_match'];
		    		} else {
		    			$what_match = 'any';
		    		}
				} else {
					$value = '';
				}

				if ($value != '') {
					$CI->db->select('cftd.object_id');
					$CI->db->from('content_fields_type_varchar_data AS cftd');
					$CI->db->where('cftd.field_id', $this->field->id);
					$CI->db->where('cftd.custom_name', $fields_group_name);
					if ($what_match == 'any') {
						$CI->db->like('cftd.field_value', $value);
					} else {
						$CI->db->where('cftd.field_value', $value);
					}

					$sql = $CI->db->_compile_select();
					$CI->db->_reset_select();
					return array('OR' => str_replace("\n"," ",$sql));
				}
			}
		}
	}
}
?>