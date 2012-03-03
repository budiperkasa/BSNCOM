<?php
class emailClass extends processFieldValue
{
	public $name = 'Email';
	public $configuration = false;
	public $search_configuration = true;
	public $order_option = true;

    public function renderDataEntry()
    {
    	$CI = &get_instance();
    	$CI->load->model('content_fields', 'content_fields');

        $view = $CI->load->view();
        $view->assign('field', $this->field);
        $view->assign('value', $this->field->value);
        
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

        $view = $CI->load->view();
        $view->assign('field', $this->field);
        
        // Antispam email protection
        if ($value = $this->field->value) {
	        $devided = mt_rand(1, strlen($value));
	        $part1 = substr($value, 0, $devided);
	        $part2 = substr($value, $devided, strlen($value));
	        $view->assign('part1', $part1);
	        $view->assign('part2', $part2);
        }
	        
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
    	$CI = &get_instance();
    	$CI->load->model('content_fields', 'content_fields');

    	$required = ($this->field->required) ? 'required|' : '';

    	if ($required || $_POST['field_' . $this->field->seo_name] != '') {
    		$required .= 'valid_email';
    	}

    	$form_validation->set_rules('field_' . $this->field->seo_name, $this->field->name, $required);
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
				$CI->db->from('content_fields_type_email_data AS cftd');
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
			} else {
				$value = '';
			}

			if ($value != '') {
				$CI->db->select('cftd.object_id');
				$CI->db->from('content_fields_type_email_data AS cftd');
				$CI->db->where('cftd.field_id', $this->field->id);
				$CI->db->where('cftd.custom_name', $fields_group_name);
				$CI->db->like('cftd.field_value', $value);

				$sql = $CI->db->_compile_select();
				$CI->db->_reset_select();
				return array('OR' => str_replace("\n"," ",$sql));
			}
		}
	}
}
?>