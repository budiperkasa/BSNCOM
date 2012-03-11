<?php
class textClass extends processFieldValue
{
	public $name = 'Text area';
	public $configuration = true;
	public $search_configuration = true;
	public $order_option = false;

	public static $instance_count = 0;
	
	public function __construct($field_array = null)
	{
		parent::__construct($field_array);
		self::$instance_count++;
		
		$CI = &get_instance();
		$CI->load->model('content_fields', 'content_fields');
		
		if (self::$instance_count == 1) {
        	$view = new view;
        	$view->addJsFile('content_fields/text.js');
        }
	}
	
    public function renderDataEntry()
    {
    	$CI = &get_instance();
    	$CI->load->model('content_fields', 'content_fields');

        $max_length = $this->field->options[0]['max_length'];
    	
        $view = $CI->load->view();
        $view->assign('field', $this->field);
        $view->assign('max_length', $max_length);
        $view->assign('count_chars', ($max_length - mb_strlen($this->field->value)));
        
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
        	
        $required = ($this->field->required) ? '|required' : '';

    	$form_validation->set_rules('field_' . $this->field->seo_name, $this->field->name, 'max_length[' . $max_length . ']' . $required);
    }

    public function configurationPage()
    {
    	$CI = &get_instance();
    	$CI->load->model('content_fields', 'content_fields');

        if ($CI->input->post('submit')) {
            $CI->form_validation->set_rules('id', LANG_FIELD_ID, 'required');
            $CI->form_validation->set_rules('max_length', LANG_MAX_LENGTH_TEXT, 'integer|required');

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
        $max_length = $options[0]['max_length'];

        $view  = $CI->load->view();
        $view->assign('field', $this->field);
        $view->assign('max_length', $max_length);
        $view->display('content_fields/fields/' . $this->field->type . '/field_configure.tpl');
	}

	public function getOptionsFromForm($form)
	{
		$CI = &get_instance();
		$CI->load->model('content_fields', 'content_fields');

		$options[0]['max_length'] = $CI->form_validation->set_value('max_length');
		return $options;
	}
	
	public function saveOptionsByFieldId($form_result)
	{
		$CI = &get_instance();
		$CI->load->model('content_fields', 'content_fields');

		$CI->content_fields->db->set('field_id', $this->field->id);
		$CI->content_fields->db->set('max_length', $form_result['max_length']);
		return $CI->content_fields->db->on_duplicate_insert('content_fields_type_' . $this->field->type);
	}
	
	public function getDefaultOptions()
	{
		$options[0]['max_length'] = 2000;
		return $options;
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
				$CI->db->from('content_fields_type_text_data AS cftd');
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
				$CI->db->from('content_fields_type_text_data AS cftd');
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