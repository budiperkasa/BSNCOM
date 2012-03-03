<?php
class selectClass extends processFieldValue
{
	public $name = 'Select box';
	public $configuration = true;
	public $search_configuration = false;
	public $order_option = false;

    public function renderDataEntry()
    {
    	$CI = &get_instance();
    	$CI->load->model('content_fields', 'content_fields');

        $view = $CI->load->view();
        $view->assign('field', $this->field);
        $view->assign('options', $this->field->options);
        
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

		$value = $this->field->value;
		foreach ($this->field->options AS $option) {
			if ($option['id'] == $this->field->value) {
				$value = $option['option_name'];
			}
		}
    	
        $view = $CI->load->view();
        $view->assign('field', $this->field);
        $view->assign('option_id', $this->field->value);
        $view->assign('value', $value);
        
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
    	if ($this->field->value) {
			$value = $this->field->value;
			foreach ($this->field->options AS $option) {
				if ($option['id'] == $this->field->value) {
					$value = $option['option_name'];
				}
			}
			return $value;
    	}
    }

    public function validate($form_validation)
    {
    	$CI = &get_instance();
    	$CI->load->model('content_fields', 'content_fields');

        $required = ($this->field->required) ? 'selected' : '';

		$form_validation->set_rules('field_' . $this->field->seo_name, $this->field->name, $required);
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
    			LANG_CREATE_FIELD_OPTION_TITLE
    		));

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
    			LANG_EDIT_FIELD_OPTION_TITLE,
    			)
    		);

    		$view  = $CI->load->view();
	        $view->assign('field', $this->field);
	        $view->assign('option_id', $option_id);
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
    			LANG_DELETE_FIELD_OPTION_TITLE
    		));

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
	public function renderSearch($args)
	{
		$CI = &get_instance();
		$CI->load->model('content_fields', 'content_fields');

		if (count($this->field->options)) {
			$view = $CI->load->view();
			$view->assign('field', $this->field);
			$view->assign('field_index', "search_" . $this->field->seo_name);
			$view->assign('field_mode', "mode_" . $this->field->seo_name);
			if (isset($args["search_" . $this->field->seo_name])) {
				if ($args["search_" . $this->field->seo_name] != 'any') {
					$values = array_filter(explode('-', $args["search_" . $this->field->seo_name]));
					foreach ($this->field->options AS $key=>$option) {
						if (array_search($option['id'], $values) !== FALSE) {
							$this->field->options[$key]['checked'] = 'checked';
						}
					}
				} else {
					$view->assign('check_all', true);
				}
			}
			$view->assign('options', $this->field->options);
			$view->assign('args', $args);
			$view->display('content_fields/search/' . $this->field->type . '/search_input.tpl');
		}
	}
	
	public function validateSearch($fields_group_name, $args, &$search_url_rebuild)
	{
		$CI = &get_instance();
		$CI->load->model('content_fields', 'content_fields');

		if (isset($args["search_" . $this->field->seo_name]) && $args["search_" . $this->field->seo_name]) {
			if ($args["search_" . $this->field->seo_name] != 'any') {
				$values = array_filter(explode('-', $args["search_" . $this->field->seo_name]));

				$CI->db->select('cftd.object_id');
				$CI->db->from('content_fields_type_select_data AS cftd');
				$CI->db->where('cftd.field_id', $this->field->id);
				$CI->db->where('cftd.custom_name', $fields_group_name);
				$where = array();
				foreach ($values AS $value) {
					$where[] = 'cftd.field_value=' . $value;
				}
				$CI->db->where('(' . implode(' OR ', $where) . ')', null, false);

				$search_url_rebuild .= "search_" . $this->field->seo_name . '/' . implode('-', $values) . '/';
				$sql = $CI->db->_compile_select();
				$CI->db->_reset_select();
				return array('AND' => str_replace("\n"," ",$sql));
			} else {
				$CI->db->distinct();
				$CI->db->select('cftd.object_id');
				$CI->db->from('content_fields_type_select_data AS cftd');
				$CI->db->where('cftd.field_id', $this->field->id);
				$CI->db->where('cftd.custom_name', $fields_group_name);

				$search_url_rebuild .= "search_" . $this->field->seo_name . '/any/';
				$sql = $CI->db->_compile_select();
				$CI->db->_reset_select();
				return array('AND' => str_replace("\n"," ",$sql));
			}
		}
	}
}
?>