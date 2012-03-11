<?php
include_once(BASEPATH . 'richtext_editor/richtextEditor.php');

class richtextClass extends processFieldValue
{
	public $name = 'Rich text editor';
	public $configuration = true;
	public $search_configuration = true;
	public $order_option = false;

    public function renderDataEntry()
    {
    	$CI = &get_instance();
    	$CI->load->model('content_fields', 'content_fields');

        $Editor = new richtextEditor('field_' . $this->field->seo_name, $this->field->value);
        foreach ($this->field->options AS $option) {
        	switch (strtolower($option['option_name'])) {
        		case 'width':
        			if (strpos($option['value'], 'px') === true) {
        				$option['value'] = str_replace('px', '', $option['value']);
        			}
        			$Editor->Width = $option['value'] + 30;
        			break;
        		case 'height':
        			$Editor->Height = $option['value'];
        			break;
        		default:
        			$Editor->Config[$option['option_name']] = $option['value'];
        	}
        }

        $view = $CI->load->view();
        $view->assign('field', $this->field);
        $view->assign('value', $Editor->CreateHtml());
        
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

        $width = '100%';
        foreach ($this->field->options AS $option) {
        	switch (strtolower($option['option_name'])) {
        		case 'width':
        			$width = $option['value'];
        			break;
        	}
        }
        
        $view = $CI->load->view();
        $view->assign('width', $width);
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

    public function validate($form_validate)
    {
    	$CI = &get_instance();
    	$CI->load->model('content_fields', 'content_fields');

    	$required = ($this->field->required) ? '|required' : '';
    	
    	$form_validate->set_rules('field_' . $this->field->seo_name, $this->field->name, 'max_length[100000]' . $required);
    }
    
    public function configurationPage()
    {
    	$CI = &get_instance();
    	$CI->load->model('content_fields', 'content_fields');

    	if ($CI->uri->segment(5) == 'add_option') {
    		// Create new option
    		if ($CI->input->post('submit')) {
	        	$CI->form_validation->set_rules('id', LANG_FIELD_ID, 'required|integer');
	        	$CI->form_validation->set_rules('name', LANG_OPTION_NAME_TH, 'required');
	        	$CI->form_validation->set_rules('value', LANG_OPTION_VALUE_TH, 'required');
	        	
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
	        	$CI->form_validation->set_rules('name', LANG_OPTION_NAME_TH, 'required');
	        	$CI->form_validation->set_rules('value', LANG_OPTION_VALUE_TH, 'required');
	        	
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
    					$value = $item['value'];
    				}
    			}
    		}
    		
    		registry::add('breadcrumbs', array(
    			LANG_EDIT_FIELD_OPTION_TITLE)
    		);

    		$view  = $CI->load->view();
	        $view->assign('field', $this->field);
	        $view->assign('name', $name);
	        $view->assign('value', $value);
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
    		// View options table

	        $view  = $CI->load->view();
	        $view->assign('field', $this->field);
	        $view->assign('options', $this->field->options);
	        $view->display('content_fields/fields/' . $this->field->type . '/field_configure.tpl');
    	}
	}
	
	public function createOption()
	{
		$CI = &get_instance();
		$CI->load->model('content_fields', 'content_fields');

		$name = $CI->form_validation->set_value('name');
		$value = $CI->form_validation->set_value('value');
		$order_num = count($this->field->options) + 1;

    	$CI->content_fields->db->set('field_id', $this->field->id);
    	$CI->content_fields->db->set('option_name', $name);
    	$CI->content_fields->db->set('value', $value);
    	return $CI->content_fields->db->insert('content_fields_type_' . $this->field->type);
    }
    
    public function saveOptionById($option_id)
	{
		$CI = &get_instance();
		$CI->load->model('content_fields', 'content_fields');

		$name = $CI->form_validation->set_value('name');
		$value = $CI->form_validation->set_value('value');

    	$CI->content_fields->db->set('option_name', $name);
    	$CI->content_fields->db->set('value', $value);
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

		if ($CI->input->post('submit')) {
			$CI->form_validation->set_rules('id', LANG_FIELD_ID, 'required');
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

	public function getDefaultSearchOptions()
	{
		$search_options[0]['search_type'] = 'connect_what_field';  // self_search
		return $search_options;
	}

	public function renderSearch($args)
	{
		$CI = &get_instance();
		$CI->load->model('content_fields', 'content_fields');

		if ($this->field->search_options[0]['search_type'] == 'self_search') {
			$view = $CI->load->view();
			$view->assign('field', $this->field);
			$view->assign('field_index', "search_" . $this->field->seo_name);
			$view->assign('args', $args);
			$view->display('content_fields/search/' . $this->field->type . '/search_string.tpl');
		}
	}

	public function validateSearch($fields_group_name, $args, &$search_url_rebuild)
	{
		$CI = &get_instance();
		$CI->load->model('content_fields', 'content_fields');

		if ($this->field->search_options[0]['search_type'] == 'self_search') {
			if (isset($args['search_' . $this->field->seo_name])) {
				$value = $args['search_' . $this->field->seo_name];
			} else {
				$value = '';
			}
			if ($value != '') {
				$CI->db->select('cftd.object_id');
				$CI->db->from('content_fields_type_richtext_data AS cftd');
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
				$CI->db->from('content_fields_type_richtext_data AS cftd');
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
?>