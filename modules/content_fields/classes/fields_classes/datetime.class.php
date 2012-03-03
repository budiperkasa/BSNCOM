<?php
class datetimeClass extends processFieldValue
{
	public $name = 'Date Time';
	public $configuration = true;
	public $search_configuration = false;
	public $order_option = true;

	/**
     * Return field's value after the form was not valid
     *
     * @param object $form
     */
    public function getValueFromForm($form_result)
    {
    	$seo_name = $this->field->seo_name;
    	if (!empty($form_result['tmstmp_' . $seo_name])) {
	    	$this->field->value = date("Y-m-j", $form_result['tmstmp_' . $seo_name]) . 
	    										' ' . $form_result['h_' . $seo_name] . 
	    										':' . $form_result['m_' . $seo_name] . 
	    										':' . $form_result['s_' . $seo_name];
    	}
    }

    public function renderDataEntry()
    {
    	$CI = &get_instance();
    	$CI->load->model('content_fields', 'content_fields');

        $view = new view;
        $view->assign('field', $this->field);
        $view->assign('date', $this->field->value);
        $view->assign('date_tmstmp', strtotime($this->field->value));

        if (!empty($this->field->options)) {
        	$enable_time = $this->field->options[0]['enable_time'];
        } else {
        	$enable_time = 1;
        }
        $view->assign('enable_time', $enable_time);

        $a = explode(' ', $this->field->value);
        $b = explode(':', $a[1]);
        $time_h = ($b[0] == '') ? '00' : $b[0];
        $view->assign('time_h', $time_h);
        $time_m = ($b[1] == '') ? '00' : $b[1];
        $view->assign('time_m', $time_m);
        $time_s = ($b[2] == '') ? '00' : $b[2];
        $view->assign('time_s', $time_s);
        
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

    	if ($this->field->value != '' && $this->field->value != '0000-00-00 00:00:00' && $this->field->value != '1970-01-01 00:00:00') {
	        $format = $this->field->options[0]['format'];
	        $enable_time = $this->field->options[0]['enable_time'];

	        $value = date($format, strtotime($this->field->value));
	        if (!$enable_time) {
	        	$value = str_replace(' 00:00:00', '', $value);
	        }
	
	        $view = $CI->load->view();
	        $view->assign('field', $this->field);
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
    }
    
    public function getValueAsString()
    {
    	$format = $this->field->options[0]['format'];
    	$enable_time = $this->field->options[0]['enable_time'];
		
    	if ($this->field->value) {
	    	$value = date($format, strtotime($this->field->value));
	    	if (!$enable_time) {
	    		$value = str_replace(' 00:00:00', '', $value);
	    	}
	    	return $value;
    	}
    }

    public function validate($form_validation)
    {
    	$CI = &get_instance();
    	$CI->load->model('content_fields', 'content_fields');

    	$field_name = 'field_' . $this->field->seo_name;
    	$tmsmp_field_name = 'tmstmp_' . $this->field->seo_name;

    	$required = ($this->field->required) ? '|required' : '';

    	$form_validation->set_rules($field_name, $this->field->name, 'max_length[255]' . $required);
    	$form_validation->set_rules($tmsmp_field_name, $this->field->name);

    	$form_validation->set_rules('h_' . $this->field->seo_name, $this->field->name, 'integer');
    	$form_validation->set_rules('m_' . $this->field->seo_name, $this->field->name, 'integer');
    	$form_validation->set_rules('s_' . $this->field->seo_name, $this->field->name, 'integer');
    }
    
    public function save($custom_group_name, $object_id, $form_result)
    {
    	$CI = &get_instance();
    	$CI->load->model('content_fields', 'content_fields');

    	$field_name = 'field_' . $this->field->seo_name;
    	$tmsmp_field_name = 'tmstmp_' . $this->field->seo_name;
    	
    	// Handle hours, minutes, seconds
    	$time_h = $form_result['h_' . $this->field->seo_name];
    	$time_m = $form_result['m_' . $this->field->seo_name];
    	$time_s = $form_result['s_' . $this->field->seo_name];

    	$time_h = ($time_h == '') ? '00' : $time_h;
    	$time_m = ($time_m == '') ? '00' : $time_m;
    	$time_s = ($time_s == '') ? '00' : $time_s;

    	if (!empty($form_result[$field_name])) {
    		$date_time = date('Y-m-d', $form_result[$tmsmp_field_name]) . ' ' . $time_h . ':' . $time_m . ':' . $time_s;
    		$CI->content_fields->db->set('field_value', $date_time);
	    	$CI->content_fields->db->set('field_id', $this->field->id);
	    	$CI->content_fields->db->set('custom_name', $custom_group_name);
	    	$CI->content_fields->db->set('object_id', $object_id);
	    	return $CI->content_fields->db->insert('content_fields_type_' . $this->field->type . '_data');
    	} else {
    		return true;
    	}
    }
    
    public function update($custom_group_name, $object_id, $form_result)
    {
    	$CI = &get_instance();
    	$CI->load->model('content_fields', 'content_fields');

    	if ($this->field->field_value_id == 'new') {
    		return $this->save($custom_group_name, $object_id, $form_result);
    	} else {
	    	$field_name = 'field_' . $this->field->seo_name;
	    	$tmsmp_field_name = 'tmstmp_' . $this->field->seo_name;
	    	
	    	// Handle hours, minutes, seconds
	    	$time_h = $form_result['h_' . $this->field->seo_name];
	    	$time_m = $form_result['m_' . $this->field->seo_name];
	    	$time_s = $form_result['s_' . $this->field->seo_name];
	
	    	$time_h = ($time_h == '') ? '00' : $time_h;
	    	$time_m = ($time_m == '') ? '00' : $time_m;
	    	$time_s = ($time_s == '') ? '00' : $time_s;
	
	    	if (!is_null($form_result[$field_name])) {
	    		$date_time = date('Y-m-d', $form_result[$tmsmp_field_name]) . ' ' . $time_h . ':' . $time_m . ':' . $time_s;
		    	$CI->content_fields->db->set('field_value', $date_time);
		    	$CI->content_fields->db->where('field_id', $this->field->id);
		    	$CI->content_fields->db->where('custom_name', $custom_group_name);
		    	$CI->content_fields->db->where('object_id', $object_id);
		    	return $CI->content_fields->db->update('content_fields_type_' . $this->field->type . '_data');
	    	} else {
	    		return true;
	    	}
    	}
    }

    public function configurationPage()
    {
    	$CI = &get_instance();
    	$CI->load->model('content_fields', 'content_fields');

        if ($CI->input->post('submit')) {
            $CI->form_validation->set_rules('id', LANG_FIELD_ID, 'required');
            $CI->form_validation->set_rules('format', LANG_DATETIME_FORMAT, 'required');
            $CI->form_validation->set_rules('enable_time', LANG_ENABLE_TIME, 'is_checked');

            if ($CI->form_validation->run() !== FALSE) {
				if ($this->saveOptionsByFieldId($CI->form_validation->result_array())) {
					// Clean cache
					$CI->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('content_fields'));

					$CI->setSuccess(LANG_FIELD_CONFIG_SAVE_SUCCESS);
					redirect('admin/fields/');
				}
			} else {
	            $options[0]['format'] = $CI->form_validation->set_value('format');
				$options[0]['enable_time'] = $CI->form_validation->set_value('enable_time');
			}
        } else {
        	$options = $this->field->options;
        }

        $view  = new view;
        $view->assign('field', $this->field);
        $view->assign('format', $options[0]['format']);
        $view->assign('enable_time', $options[0]['enable_time']);
        $view->assign('last_page', $CI->session->userdata('last_page'));
        $view->display('content_fields/fields/' . $this->field->type . '/field_configure.tpl');
	}

	public function saveOptionsByFieldId($result)
	{
		$CI = &get_instance();
		$CI->load->model('content_fields', 'content_fields');

		$CI->content_fields->db->set('field_id', $this->field->id);
		$CI->content_fields->db->set('format', $result['format']);
		$CI->content_fields->db->set('enable_time', $result['enable_time']);
		return $CI->content_fields->db->on_duplicate_insert('content_fields_type_datetime');
	}
	
	public function getDefaultOptions()
	{
		$options[0]['format'] = 'd.m.Y H:i:s';
		$options[0]['enable_time'] = 1;
		return $options;
	}


	// --------------------------------------------------------------------------------------------
	// Search methods
	// --------------------------------------------------------------------------------------------
	public function renderSearch($args)
	{
		$CI = &get_instance();
		$CI->load->model('content_fields', 'content_fields');

		$view = $CI->load->view();
		$view->assign('field', $this->field);
		$view->assign('field_index', "search_" . $this->field->seo_name);
		$view->assign('args', $args);
		$mode = 'single';
		
		if (isset($args["search_" . $this->field->seo_name])) {
			$tmstmp = null;
			// Receives date in unix timestamp and also in 'Y-m-d' formats
			if (is_numeric($args["search_" . $this->field->seo_name]) && (strtotime(date("Y-m-d", $args["search_" . $this->field->seo_name]))) !== FALSE) {
				// Timestamp
	    		$tmstmp = $args["search_" . $this->field->seo_name];
	    	} elseif ((strtotime($args["search_" . $this->field->seo_name])) !== FALSE) {
	    		// 'Y-m-d' format
	    		$tmstmp = strtotime($args["search_" . $this->field->seo_name]);
	    	}
	    	
	    	if ($tmstmp) {
	    		$view->assign('date', date("Y-m-d", $tmstmp));
	    		$view->assign('date_tmstmp', $tmstmp);
	    	}
		}
		if (isset($args["from_tmstmp_" . $this->field->seo_name])) {
			$tmstmp = null;
			// Receives date in unix timestamp and also in 'Y-m-d' formats
			if (is_numeric($args["from_tmstmp_" . $this->field->seo_name]) && (strtotime(date("Y-m-d", $args["from_tmstmp_" . $this->field->seo_name]))) !== FALSE) {
	    		// Timestamp
	    		$tmstmp = $args["from_tmstmp_" . $this->field->seo_name];
	    	} elseif ((strtotime($args["from_tmstmp_" . $this->field->seo_name])) !== FALSE) {
	    		// 'Y-m-d' format
	    		$tmstmp = strtotime($args["from_tmstmp_" . $this->field->seo_name]);
	    	}
	    	
	    	if ($tmstmp) {
	    		$view->assign('from_date', date("Y-m-d", $tmstmp));
	    		$view->assign('from_date_tmstmp', $tmstmp);
	    	}
			
			$mode = 'range';
		}
		if (isset($args["to_tmstmp_" . $this->field->seo_name])) {
			$tmstmp = null;
			// Receives date in unix timestamp and also in 'Y-m-d' formats
			if (is_numeric($args["to_tmstmp_" . $this->field->seo_name]) && (strtotime(date("Y-m-d", $args["to_tmstmp_" . $this->field->seo_name]))) !== FALSE) {
	    		// Timestamp
	    		$tmstmp = $args["to_tmstmp_" . $this->field->seo_name];
	    	} elseif ((strtotime($args["to_tmstmp_" . $this->field->seo_name])) !== FALSE) {
	    		// 'Y-m-d' format
	    		$tmstmp = strtotime($args["to_tmstmp_" . $this->field->seo_name]);
	    	}
	    	
	    	if ($tmstmp) {
	    		$view->assign('to_date', date("Y-m-d", $tmstmp));
	    		$view->assign('to_date_tmstmp', $tmstmp);
	    	}
			
			$mode = 'range';
		}

		$view->assign('mode', $mode);
		$view->display('content_fields/search/' . $this->field->type . '/search_input.tpl');
	}
	
	public function validateSearch($fields_group_name, $args, &$search_url_rebuild)
	{
		$CI = &get_instance();
		$CI->load->model('content_fields', 'content_fields');

		if (isset($args["search_" . $this->field->seo_name])) {
			// Receives date in unix timestamp and also in 'Y-m-d' formats
			if (is_numeric($args["search_" . $this->field->seo_name]) && (strtotime(date("Y-m-d", $args["search_" . $this->field->seo_name]))) !== FALSE)
				$tmstmp = $args["search_" . $this->field->seo_name];
	    	elseif (($tmstmp = strtotime($args["search_" . $this->field->seo_name])) !== FALSE && strtotime($args["search_" . $this->field->seo_name]) != -1)
	    		$tmstmp = strtotime($args["search_" . $this->field->seo_name]);

			$CI->db->select('cftd.object_id');
			$CI->db->from('content_fields_type_datetime_data AS cftd');
			$CI->db->where('cftd.field_id', $this->field->id);
			$CI->db->where('cftd.custom_name', $fields_group_name);
			$CI->db->where('TO_DAYS(cftd.field_value)', 'TO_DAYS("' . date("Y-m-d", $tmstmp) . '")', false);

			$search_url_rebuild .= "search_" . $this->field->seo_name . '/' . $args["search_" . $this->field->seo_name] . '/';

			$sql = $CI->db->_compile_select();
			$CI->db->_reset_select();
			return array('AND' => str_replace("\n"," ",$sql));
		}

		if (isset($args["from_tmstmp_" . $this->field->seo_name]) || isset($args["to_tmstmp_" . $this->field->seo_name])) {
			if (isset($args["from_tmstmp_" . $this->field->seo_name])) {
				$from_tmstmp = $args["from_tmstmp_" . $this->field->seo_name];
			} else {
				$from_tmstmp = '';
			}
			if (isset($args["to_tmstmp_" . $this->field->seo_name])) {
				$to_tmstmp = $args["to_tmstmp_" . $this->field->seo_name];
			} else {
				$to_tmstmp = '';
			}

			$CI->db->select('cftd.object_id');
			$CI->db->from('content_fields_type_datetime_data AS cftd');
			$CI->db->where('cftd.field_id', $this->field->id);
			$CI->db->where('cftd.custom_name', $fields_group_name);
			if ($from_tmstmp != '') {
				$CI->db->where('TO_DAYS(cftd.field_value) >= ', 'TO_DAYS("' . date("Y-m-d", $from_tmstmp) . '")', false);
				$search_url_rebuild .= "from_tmstmp_" . $this->field->seo_name . '/' . $args["from_tmstmp_" . $this->field->seo_name] . '/';
			}
			if ($to_tmstmp != '') {
				$CI->db->where('TO_DAYS(cftd.field_value) <= ', 'TO_DAYS("' . date("Y-m-d", $to_tmstmp) . '")', false);
				$search_url_rebuild .= "to_tmstmp_" . $this->field->seo_name . '/' . $args["to_tmstmp_" . $this->field->seo_name] . '/';
			}

			$sql = $CI->db->_compile_select();
			$CI->db->_reset_select();
			return array('AND' => str_replace("\n"," ",$sql));
		}
	}
}
?>