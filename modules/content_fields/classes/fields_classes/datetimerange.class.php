<?php
class datetimerangeClass extends processFieldValue
{
	public $name = 'Date Time range';
	public $configuration = true;
	public $search_configuration = false;
	public $order_option = true;

	/**
     * Overload of processFieldValue::select($custom_group_name, $object_id)
     *
     */
	public function select($custom_group_name, $object_id)
    {
    	$CI = &get_instance();
    	$CI->load->model('content_fields', 'content_fields');
    	
    	$this->field->value = array();

	    $CI->content_fields->db->select();
	    $CI->content_fields->db->from('content_fields_type_datetimerange_data');
	    $CI->content_fields->db->where('field_id', $this->field->id);
	    $CI->content_fields->db->where('custom_name', $custom_group_name);
	    $CI->content_fields->db->where('object_id', $object_id);
	    $query = $CI->content_fields->db->get();

    	if ($query->num_rows()) {
    		$row = $query->row_array();
    		$this->field->value = array('from_field_value' => $row['from_field_value'], 'to_field_value' => $row['to_field_value']);
    		$this->field->field_value_id = $row['id'];
    	}
    	$CI->content_fields->db->select();
    	// Special table for multiple dates
	   	$CI->content_fields->db->from('content_fields_type_datesmultiple_data');
	   	$CI->content_fields->db->where('field_id', $this->field->id);
	   	$CI->content_fields->db->where('custom_name', $custom_group_name);
	   	$CI->content_fields->db->where('object_id', $object_id);
	   	$CI->content_fields->db->orderby('field_value');
	   	$query = $CI->content_fields->db->get();

	   	if ($query->num_rows()) {
    		$result = $query->result_array();
    		$dates_array = array();
    		$cycle_days_monday = 0;
    		$cycle_days_tuesday = 0;
    		$cycle_days_wednesday = 0;
    		$cycle_days_thursday = 0;
    		$cycle_days_friday = 0;
    		$cycle_days_saturday = 0;
    		$cycle_days_sunday = 0;
    		foreach ($result AS $row) {
    			if ($row['field_value'] && $row['field_value'] != '0000-00-00')
    				$dates_array[] = $row['field_value'];
    			if ($row['cycle_days_monday'])
    				$cycle_days_monday = $row['cycle_days_monday'];
    			if ($row['cycle_days_tuesday'])
    				$cycle_days_tuesday = $row['cycle_days_tuesday'];
    			if ($row['cycle_days_wednesday'])
    				$cycle_days_wednesday = $row['cycle_days_wednesday'];
    			if ($row['cycle_days_thursday'])
    				$cycle_days_thursday = $row['cycle_days_thursday'];
    			if ($row['cycle_days_friday'])
    				$cycle_days_friday = $row['cycle_days_friday'];
    			if ($row['cycle_days_saturday'])
    				$cycle_days_saturday = $row['cycle_days_saturday'];
    			if ($row['cycle_days_sunday'])
    				$cycle_days_sunday = $row['cycle_days_sunday'];
    		}
	    	$this->field->value = array_merge($this->field->value, array(
	    		'dates_array' => $dates_array,
	    		'cycle_days_monday' => $cycle_days_monday,
	    		'cycle_days_tuesday' => $cycle_days_tuesday,
	    		'cycle_days_wednesday' => $cycle_days_wednesday,
	    		'cycle_days_thursday' => $cycle_days_thursday,
	    		'cycle_days_friday' => $cycle_days_friday,
	    		'cycle_days_saturday' => $cycle_days_saturday,
	    		'cycle_days_sunday' => $cycle_days_sunday
    		));
    		$this->field->field_value_id = 1;
    	}
    }
    
    /**
     * Overload of processFieldValue::save($custom_group_name, $object_id, $form_result)
     *
     */
    public function save($custom_group_name, $object_id, $form_result)
    {
    	$CI = &get_instance();
    	$CI->load->model('content_fields', 'content_fields');

    	$from_field_name = 'from_' . $this->field->seo_name;
    	$from_tmsmp_field_name = 'from_tmstmp_' . $this->field->seo_name;

    	$to_field_name = 'to_' . $this->field->seo_name;
    	$to_tmsmp_field_name = 'to_tmstmp_' . $this->field->seo_name;

    	// Handle hours, minutes, seconds
    	$from_time_h = $form_result['from_h_' . $this->field->seo_name];
    	$from_time_m = $form_result['from_m_' . $this->field->seo_name];
    	$from_time_s = $form_result['from_s_' . $this->field->seo_name];
    	$from_time_h = ($from_time_h == '') ? '00' : $from_time_h;
    	$from_time_m = ($from_time_m == '') ? '00' : $from_time_m;
    	$from_time_s = ($from_time_s == '') ? '00' : $from_time_s;

    	$to_time_h = $form_result['to_h_' . $this->field->seo_name];
    	$to_time_m = $form_result['to_m_' . $this->field->seo_name];
    	$to_time_s = $form_result['to_s_' . $this->field->seo_name];
    	$to_time_h = ($to_time_h == '') ? '00' : $to_time_h;
    	$to_time_m = ($to_time_m == '') ? '00' : $to_time_m;
    	$to_time_s = ($to_time_s == '') ? '00' : $to_time_s;

    	if (!empty($form_result[$from_field_name]) || !empty($form_result[$to_field_name])) {
    		if (!empty($form_result[$from_field_name])) {
    			$from_date_time = date('Y-m-d', $form_result[$from_tmsmp_field_name]) . ' ' . $from_time_h . ':' . $from_time_m . ':' . $from_time_s;
	    	} else {
	    		$from_date_time = '0000-00-00';
	    	}

	    	if (!empty($form_result[$to_field_name])) {
	    		$to_date_time = date('Y-m-d', $form_result[$to_tmsmp_field_name]) . ' ' . $to_time_h . ':' . $to_time_m . ':' . $to_time_s;
	    	} else {
	    		$to_date_time = '9999-12-31';
	    	}

    		$CI->content_fields->db->set('from_field_value', $from_date_time);
	    	$CI->content_fields->db->set('to_field_value', $to_date_time);
	    	$CI->content_fields->db->set('field_id', $this->field->id);
	    	$CI->content_fields->db->set('custom_name', $custom_group_name);
	    	$CI->content_fields->db->set('object_id', $object_id);
	    	return $CI->content_fields->db->insert('content_fields_type_' . $this->field->type . '_data');
    	} else {
    		return true;
    	}

   		$selected_dates = explode(',', $form_result['selected_dates_' . $this->field->seo_name]);
   		foreach ($selected_dates AS $key=>$date) {
   			if ($date == 'false') unset($selected_dates[$key]);
   		}
   		$cycle_days_monday = $form_result['cycle_days_monday_' . $this->field->seo_name];
   		$cycle_days_tuesday = $form_result['cycle_days_tuesday_' . $this->field->seo_name];
   		$cycle_days_wednesday = $form_result['cycle_days_wednesday_' . $this->field->seo_name];
   		$cycle_days_thursday = $form_result['cycle_days_thursday_' . $this->field->seo_name];
   		$cycle_days_friday = $form_result['cycle_days_friday_' . $this->field->seo_name];
   		$cycle_days_saturday = $form_result['cycle_days_saturday_' . $this->field->seo_name];
   		$cycle_days_sunday = $form_result['cycle_days_sunday_' . $this->field->seo_name];
   		if ($selected_dates) {
    		foreach ($selected_dates AS $date) {
    			if (($timestamp = strtotime($date)) !== false) {
	    			$CI->content_fields->db->set('field_value', date('Y-m-d', $timestamp));
			    	$CI->content_fields->db->set('field_id', $this->field->id);
			    	$CI->content_fields->db->set('custom_name', $custom_group_name);
			    	$CI->content_fields->db->set('object_id', $object_id);
			    	$CI->content_fields->db->set('cycle_days_monday', $cycle_days_monday);
			    	$CI->content_fields->db->set('cycle_days_tuesday', $cycle_days_tuesday);
			    	$CI->content_fields->db->set('cycle_days_wednesday', $cycle_days_wednesday);
			    	$CI->content_fields->db->set('cycle_days_thursday', $cycle_days_thursday);
			    	$CI->content_fields->db->set('cycle_days_friday', $cycle_days_friday);
			    	$CI->content_fields->db->set('cycle_days_saturday', $cycle_days_saturday);
			    	$CI->content_fields->db->set('cycle_days_sunday', $cycle_days_sunday);
			    	$CI->content_fields->db->insert('content_fields_type_datesmultiple_data');
    			}
    		}
    		return true;
   		} elseif ($cycle_days_monday || $cycle_days_tuesday || $cycle_days_wednesday || $cycle_days_thursday || $cycle_days_friday || $cycle_days_saturday || $cycle_days_sunday) {
    		$CI->content_fields->db->set('field_value', '');
    		$CI->content_fields->db->set('field_id', $this->field->id);
			$CI->content_fields->db->set('custom_name', $custom_group_name);
			$CI->content_fields->db->set('object_id', $object_id);
			$CI->content_fields->db->set('cycle_days_monday', $cycle_days_monday);
			$CI->content_fields->db->set('cycle_days_tuesday', $cycle_days_tuesday);
			$CI->content_fields->db->set('cycle_days_wednesday', $cycle_days_wednesday);
			$CI->content_fields->db->set('cycle_days_thursday', $cycle_days_thursday);
			$CI->content_fields->db->set('cycle_days_friday', $cycle_days_friday);
			$CI->content_fields->db->set('cycle_days_saturday', $cycle_days_saturday);
			$CI->content_fields->db->set('cycle_days_sunday', $cycle_days_sunday);
			return $CI->content_fields->db->insert('content_fields_type_datesmultiple_data');
    	} else 
    		return true;
    }
    
    /**
     * Overload of processFieldValue::update($custom_group_name, $object_id, $form)
     *
     */
    public function update($custom_group_name, $object_id, $form_result)
    {
    	$CI = &get_instance();
    	$CI->load->model('content_fields', 'content_fields');

    	if ($this->field->field_value_id == 'new') {
    		return $this->save($custom_group_name, $object_id, $form_result);
    	} else {
	    	$from_field_name = 'from_' . $this->field->seo_name;
		    $from_tmsmp_field_name = 'from_tmstmp_' . $this->field->seo_name;

	    	$to_field_name = 'to_' . $this->field->seo_name;
	    	$to_tmsmp_field_name = 'to_tmstmp_' . $this->field->seo_name;

	    	// Handle hours, minutes, seconds
	    	$from_time_h = $form_result['from_h_' . $this->field->seo_name];
	    	$from_time_m = $form_result['from_m_' . $this->field->seo_name];
	    	$from_time_s = $form_result['from_s_' . $this->field->seo_name];
	    	$from_time_h = ($from_time_h == '') ? '00' : $from_time_h;
	    	$from_time_m = ($from_time_m == '') ? '00' : $from_time_m;
	    	$from_time_s = ($from_time_s == '') ? '00' : $from_time_s;

	    	$to_time_h = $form_result['to_h_' . $this->field->seo_name];
	    	$to_time_m = $form_result['to_m_' . $this->field->seo_name];
	    	$to_time_s = $form_result['to_s_' . $this->field->seo_name];
	    	$to_time_h = ($to_time_h == '') ? '00' : $to_time_h;
	    	$to_time_m = ($to_time_m == '') ? '00' : $to_time_m;
	    	$to_time_s = ($to_time_s == '') ? '00' : $to_time_s;

    		if (!empty($form_result[$from_field_name])) {
    			$from_date_time = date('Y-m-d', $form_result[$from_tmsmp_field_name]) . ' ' . $from_time_h . ':' . $from_time_m . ':' . $from_time_s;
    		} else {
    			$from_date_time = '0000-00-00';
    		}

    		if (!empty($form_result[$to_field_name])) {
    			$to_date_time = date('Y-m-d', $form_result[$to_tmsmp_field_name]) . ' ' . $to_time_h . ':' . $to_time_m . ':' . $to_time_s;
    		} else {
    			$to_date_time = '9999-12-31';
    		}

    		$CI->content_fields->db->select('id');
    		$CI->content_fields->db->from('content_fields_type_datetimerange_data');
    		$CI->content_fields->db->where('field_id', $this->field->id);
	    	$CI->content_fields->db->where('custom_name', $custom_group_name);
	    	$CI->content_fields->db->where('object_id', $object_id);
	    	if ($CI->content_fields->db->get()->num_rows()) {
	    		$CI->content_fields->db->set('from_field_value', $from_date_time);
		    	$CI->content_fields->db->set('to_field_value', $to_date_time);
		    	$CI->content_fields->db->where('field_id', $this->field->id);
		    	$CI->content_fields->db->where('custom_name', $custom_group_name);
		    	$CI->content_fields->db->where('object_id', $object_id);
		    	$CI->content_fields->db->update('content_fields_type_datetimerange_data');
	    	} else {
	    		$CI->content_fields->db->set('from_field_value', $from_date_time);
		    	$CI->content_fields->db->set('to_field_value', $to_date_time);
		    	$CI->content_fields->db->set('field_id', $this->field->id);
		    	$CI->content_fields->db->set('custom_name', $custom_group_name);
		    	$CI->content_fields->db->set('object_id', $object_id);
		    	$CI->content_fields->db->insert('content_fields_type_datetimerange_data');
	    	}

			$selected_dates = explode(',', $form_result['selected_dates_' . $this->field->seo_name]);
			foreach ($selected_dates AS $key=>$date) {
    			if ($date == 'false') unset($selected_dates[$key]);
    		}
    		$cycle_days_monday = $form_result['cycle_days_monday_' . $this->field->seo_name];
    		$cycle_days_tuesday = $form_result['cycle_days_tuesday_' . $this->field->seo_name];
    		$cycle_days_wednesday = $form_result['cycle_days_wednesday_' . $this->field->seo_name];
    		$cycle_days_thursday = $form_result['cycle_days_thursday_' . $this->field->seo_name];
    		$cycle_days_friday = $form_result['cycle_days_friday_' . $this->field->seo_name];
    		$cycle_days_saturday = $form_result['cycle_days_saturday_' . $this->field->seo_name];
    		$cycle_days_sunday = $form_result['cycle_days_sunday_' . $this->field->seo_name];
    		if ($selected_dates) {
    			$this->select($custom_group_name, $object_id);
    			$already_saved_dates = array();
	    		foreach ($selected_dates AS $date) {
	    			if (($timestamp = strtotime($date)) !== false) {
	    				$selected_date = date('Y-m-d', $timestamp);
	    				if (in_array($selected_date, $this->field->value['dates_array'])) {
					    	$CI->content_fields->db->set('cycle_days_monday', $cycle_days_monday);
					    	$CI->content_fields->db->set('cycle_days_tuesday', $cycle_days_tuesday);
					    	$CI->content_fields->db->set('cycle_days_wednesday', $cycle_days_wednesday);
					    	$CI->content_fields->db->set('cycle_days_thursday', $cycle_days_thursday);
					    	$CI->content_fields->db->set('cycle_days_friday', $cycle_days_friday);
					    	$CI->content_fields->db->set('cycle_days_saturday', $cycle_days_saturday);
					    	$CI->content_fields->db->set('cycle_days_sunday', $cycle_days_sunday);
					    	$CI->content_fields->db->where('field_id', $this->field->id);
			    			$CI->content_fields->db->where('custom_name', $custom_group_name);
			    			$CI->content_fields->db->where('object_id', $object_id);
					    	$CI->content_fields->db->update('content_fields_type_datesmultiple_data');
	    				} else {
	    					$CI->content_fields->db->set('field_value', $selected_date);
					    	$CI->content_fields->db->set('cycle_days_monday', $cycle_days_monday);
					    	$CI->content_fields->db->set('cycle_days_tuesday', $cycle_days_tuesday);
					    	$CI->content_fields->db->set('cycle_days_wednesday', $cycle_days_wednesday);
					    	$CI->content_fields->db->set('cycle_days_thursday', $cycle_days_thursday);
					    	$CI->content_fields->db->set('cycle_days_friday', $cycle_days_friday);
					    	$CI->content_fields->db->set('cycle_days_saturday', $cycle_days_saturday);
					    	$CI->content_fields->db->set('cycle_days_sunday', $cycle_days_sunday);
					    	$CI->content_fields->db->set('field_id', $this->field->id);
			    			$CI->content_fields->db->set('custom_name', $custom_group_name);
			    			$CI->content_fields->db->set('object_id', $object_id);
					    	$CI->content_fields->db->insert('content_fields_type_datesmultiple_data');
	    				}
	    				$already_saved_dates[] = $selected_date;
	    			}
	    		}
	    		// Now remove unselected dates
	    		foreach ($this->field->value['dates_array'] AS $date) {
	    			if (!in_array($date, $already_saved_dates)) {
		    			$CI->content_fields->db->where('field_value', $date);
		    			$CI->content_fields->db->where('field_id', $this->field->id);
				    	$CI->content_fields->db->where('custom_name', $custom_group_name);
				    	$CI->content_fields->db->where('object_id', $object_id);
					    $CI->content_fields->db->delete('content_fields_type_datesmultiple_data');
	    			}
	    		}
	    		return true;
    		} elseif ($cycle_days_monday || $cycle_days_tuesday || $cycle_days_wednesday || $cycle_days_thursday || $cycle_days_friday || $cycle_days_saturday || $cycle_days_sunday) {
    			$CI->content_fields->db->where('field_id', $this->field->id);
			    $CI->content_fields->db->where('custom_name', $custom_group_name);
			    $CI->content_fields->db->where('object_id', $object_id);
				$CI->content_fields->db->delete('content_fields_type_datesmultiple_data');

	    		$CI->content_fields->db->set('field_value', '');
				$CI->content_fields->db->set('cycle_days_monday', $cycle_days_monday);
				$CI->content_fields->db->set('cycle_days_tuesday', $cycle_days_tuesday);
				$CI->content_fields->db->set('cycle_days_wednesday', $cycle_days_wednesday);
				$CI->content_fields->db->set('cycle_days_thursday', $cycle_days_thursday);
				$CI->content_fields->db->set('cycle_days_friday', $cycle_days_friday);
				$CI->content_fields->db->set('cycle_days_saturday', $cycle_days_saturday);
				$CI->content_fields->db->set('cycle_days_sunday', $cycle_days_sunday);
				$CI->content_fields->db->set('field_id', $this->field->id);
		    	$CI->content_fields->db->set('custom_name', $custom_group_name);
		    	$CI->content_fields->db->set('object_id', $object_id);
				return $CI->content_fields->db->insert('content_fields_type_datesmultiple_data');
	    	} else {
	    		$CI->content_fields->db->where('field_id', $this->field->id);
			    $CI->content_fields->db->where('custom_name', $custom_group_name);
			    $CI->content_fields->db->where('object_id', $object_id);
				$CI->content_fields->db->delete('content_fields_type_datesmultiple_data');
	    		return true;
			}
    	}
    }
    
    /**
     * Overload of processFieldValue::getValueFromForm($form)
     * 
     * Return field's value after the form was not valid
     *
     * @param object $form
     */
    public function getValueFromForm($form_result)
    {
    	$CI = &get_instance();
    	$CI->load->model('content_fields', 'content_fields');
    	
    	$this->field->value = array();

    	$seo_name = $this->field->seo_name;

	    // is 'from' input was filled?
	    if (!empty($form_result['from_tmstmp_' . $seo_name]) AND $form_result['from_tmstmp_' . $seo_name] != 'NaN') {
	    	$from_tmstmp = date("Y-m-j", $form_result['from_tmstmp_' . $seo_name]) . 
		   													' ' . $form_result['from_h_' . $seo_name] . 
		   													':' . $form_result['from_m_' . $seo_name] . 
		   													':' . $form_result['from_s_' . $seo_name];
	    } else {
	    	$from_tmstmp = '';
	    }
	    // is 'to' input was filled?
	    if (!empty($form_result['to_tmstmp_' . $seo_name]) AND $form_result['to_tmstmp_' . $seo_name] != 'NaN') {
	    	$to_tmstmp = date("Y-m-j", $form_result['to_tmstmp_' . $seo_name]) . 
		   													' ' . $form_result['to_h_' . $seo_name] . 
		   													':' . $form_result['to_m_' . $seo_name] . 
		   													':' . $form_result['to_s_' . $seo_name];
	    } else {
	    	$to_tmstmp = '';
	    }

	    $this->field->value = array('from_field_value' => $from_tmstmp, 
	    							'to_field_value' => $to_tmstmp);

		$selected_dates = explode(',', $form_result['selected_dates_' . $seo_name]);
		$dates_array = array();
		foreach ($selected_dates AS $date) {
			if (($timestamp = strtotime($date)) !== false) {
				$dates_array[] = date("Y-m-j", $timestamp);
			}
		}
		$this->field->value = array_merge($this->field->value, array(
			'dates_array' => $dates_array,
			'cycle_days_monday' => $form_result['cycle_days_monday_' . $seo_name],
			'cycle_days_tuesday' => $form_result['cycle_days_tuesday_' . $seo_name],
			'cycle_days_wednesday' => $form_result['cycle_days_wednesday_' . $seo_name],
			'cycle_days_thursday' => $form_result['cycle_days_thursday_' . $seo_name],
			'cycle_days_friday' => $form_result['cycle_days_friday_' . $seo_name],
			'cycle_days_saturday' => $form_result['cycle_days_saturday_' . $seo_name],
			'cycle_days_sunday' => $form_result['cycle_days_sunday_' . $seo_name]
		));
    }

    public function renderDataEntry()
    {
    	$CI = &get_instance();
    	$CI->load->model('content_fields', 'content_fields');

        $view = $CI->load->view();
        $view->assign('field', $this->field);

        $enable_time = $this->field->options[0]['enable_time'];
        $view->assign('enable_time', $enable_time);

   		$view->assign('from_date', $this->field->value['from_field_value']);
        $view->assign('to_date', $this->field->value['to_field_value']);
        $view->assign('from_date_tmstmp', strtotime($this->field->value['from_field_value']));
        $view->assign('to_date_tmstmp', strtotime($this->field->value['to_field_value']));

        $a = explode(' ', $this->field->value['from_field_value']);
        $b = explode(':', $a[1]);
        $time_h = ($b[0] == '') ? '00' : $b[0];
        $view->assign('from_time_h', $time_h);
        $time_m = ($b[1] == '') ? '00' : $b[1];
        $view->assign('from_time_m', $time_m);
        $time_s = ($b[2] == '') ? '00' : $b[2];
        $view->assign('from_time_s', $time_s);
        
        $a = explode(' ', $this->field->value['to_field_value']);
        $b = explode(':', $a[1]);
        $time_h = ($b[0] == '') ? '00' : $b[0];
        $view->assign('to_time_h', $time_h);
        $time_m = ($b[1] == '') ? '00' : $b[1];
        $view->assign('to_time_m', $time_m);
        $time_s = ($b[2] == '') ? '00' : $b[2];
        $view->assign('to_time_s', $time_s);

		$view->assign('dates_array', $this->field->value['dates_array']);
		$view->assign('cycle_days_monday', $this->field->value['cycle_days_monday']);
		$view->assign('cycle_days_tuesday', $this->field->value['cycle_days_tuesday']);
		$view->assign('cycle_days_wednesday', $this->field->value['cycle_days_wednesday']);
		$view->assign('cycle_days_thursday', $this->field->value['cycle_days_thursday']);
		$view->assign('cycle_days_friday', $this->field->value['cycle_days_friday']);
		$view->assign('cycle_days_saturday', $this->field->value['cycle_days_saturday']);
		$view->assign('cycle_days_sunday', $this->field->value['cycle_days_sunday']);

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

        if (!empty($this->field->options)) {
        	$format = $this->field->options[0]['format'];
        } else {
        	if ($this->field->options[0]['enable_time'])
        		$format = 'd.m.Y H:i:s';
        	else
        		$format = 'd.m.Y';
    	}
		$view = $CI->load->view();
		$view->assign('field', $this->field);
		$view->assign('format', $format);
		if (!empty($this->field->value['from_field_value']) || !empty($this->field->value['to_field_value'])) {
			if ($this->field->value['from_field_value'] != '0000-00-00 00:00:00') {
		       	$from_value = date($format, strtotime($this->field->value['from_field_value']));
			} else {
				$from_value = '';
			}
			if ($this->field->value['to_field_value'] != '9999-12-31 00:00:00') {
		       	$to_value = date($format, strtotime($this->field->value['to_field_value']));
			} else {
				$to_value = '';
			}

	        if (!$enable_time) {
	        	$from_value = str_replace(' 00:00:00', '', $from_value);
	        	$to_value = str_replace(' 00:00:00', '', $to_value);
	        }
		} else {
			$from_value = '';
			$to_value = '';
		}
        $view->assign('from_value', $from_value);
        $view->assign('to_value', $to_value);

		$view->assign('dates_array', $this->field->value['dates_array']);
		$view->assign('cycle_days_monday', $this->field->value['cycle_days_monday']);
		$view->assign('cycle_days_tuesday', $this->field->value['cycle_days_tuesday']);
		$view->assign('cycle_days_wednesday', $this->field->value['cycle_days_wednesday']);
		$view->assign('cycle_days_thursday', $this->field->value['cycle_days_thursday']);
		$view->assign('cycle_days_friday', $this->field->value['cycle_days_friday']);
		$view->assign('cycle_days_saturday', $this->field->value['cycle_days_saturday']);
		$view->assign('cycle_days_sunday', $this->field->value['cycle_days_sunday']);

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
    
    public function getValueAsString($glue = ' - ')
    {
    	if (!empty($this->field->options)) {
        	$format = $this->field->options[0]['format'];
        } else {
        	if ($this->field->options[0]['enable_time'])
        		$format = 'd.m.Y H:i:s';
        	else
        		$format = 'd.m.Y';
    	}
    	$output = '';
		if (!empty($this->field->value['from_field_value']) || !empty($this->field->value['to_field_value'])) {
			if ($this->field->value['from_field_value'] != '0000-00-00 00:00:00') {
		       	$from_value = LANG_FROM_DATERANGE . ' ' . date($format, strtotime($this->field->value['from_field_value']));
			} else {
				$from_value = '';
			}
			if ($this->field->value['to_field_value'] != '9999-12-31 00:00:00') {
				if ($from_value)
					$output .= $glue;
		       	$to_value = LANG_TO_DATERANGE . ' ' . date($format, strtotime($this->field->value['to_field_value']));
		       } else {
				$to_value = '';
			}

	        if (!$enable_time) {
	        	$from_value = str_replace(' 00:00:00', '', $from_value);
	        	$to_value = str_replace(' 00:00:00', '', $to_value);
	        }
	        $output = $from_value . $to_value . $glue;
		}

		$dates_array = $this->field->value['dates_array'];
		$cycle_days_monday = $this->field->value['cycle_days_monday'];
		$cycle_days_tuesday = $this->field->value['cycle_days_tuesday'];
		$cycle_days_wednesday = $this->field->value['cycle_days_wednesday'];
		$cycle_days_thursday = $this->field->value['cycle_days_thursday'];
		$cycle_days_friday = $this->field->value['cycle_days_friday'];
		$cycle_days_saturday = $this->field->value['cycle_days_saturday'];
		$cycle_days_sunday = $this->field->value['cycle_days_sunday'];
		if ($cycle_days_monday && $cycle_days_tuesday && $cycle_days_wednesday && $cycle_days_friday && $cycle_days_saturday && $cycle_days_sunday)
			$output .= LANG_EVERY_DAY . $glue;
		else {
			if ($cycle_days_monday) $output .= LANG_EVERY_MONDAY . $glue;
			if ($cycle_days_tuesday) $output .= LANG_EVERY_TUESDAY . $glue;
			if ($cycle_days_wednesday) $output .= LANG_EVERY_WEDNESDAY . $glue;
			if ($cycle_days_thursday) $output .= LANG_EVERY_THURSDAY . $glue;
			if ($cycle_days_friday) $output .= LANG_EVERY_FRIDAY . $glue;
			if ($cycle_days_saturday) $output .= LANG_EVERY_SATURDAY . $glue;
			if ($cycle_days_sunday) $output .= LANG_EVERY_SUNDAY . $glue;
   		}
   		if (count($dates_array))
	   		foreach($dates_array AS $date) {
				$output .= date($format, strtotime($date)) .$glue;
			}
		$output = trim($output, $glue);
   		return $output;
    }

    public function validate($form_validation)
    {
    	$CI = &get_instance();
    	$CI->load->model('content_fields', 'content_fields');

	    $form_validation->set_rules('from_' . $this->field->seo_name, LANG_FROM_DATERANGE . " " . $this->field->name, 'max_length[255]');
	    $form_validation->set_rules('from_tmstmp_' . $this->field->seo_name, LANG_FROM_DATERANGE . " " . $this->field->name);
	    $form_validation->set_rules('from_h_' . $this->field->seo_name, $this->field->name, 'integer');
	    $form_validation->set_rules('from_m_' . $this->field->seo_name, $this->field->name, 'integer');
	    $form_validation->set_rules('from_s_' . $this->field->seo_name, $this->field->name, 'integer');

    	$form_validation->set_rules('to_' . $this->field->seo_name, LANG_TO_DATERANGE . " " . $this->field->name, 'max_length[255]');
    	$form_validation->set_rules('to_tmstmp_' . $this->field->seo_name, LANG_TO_DATERANGE . " " . $this->field->name);
    	$form_validation->set_rules('to_h_' . $this->field->seo_name, $this->field->name, 'integer');
    	$form_validation->set_rules('to_m_' . $this->field->seo_name, $this->field->name, 'integer');
    	$form_validation->set_rules('to_s_' . $this->field->seo_name, $this->field->name, 'integer');

    	// Nothing was filled, but required
    	if ($this->field->required && (!$_POST['from_' . $this->field->seo_name] && !$_POST['to_' . $this->field->seo_name])) {
    		$form_validation->_error_array['from_' . $this->field->seo_name] = "Fields '" . LANG_FROM_DATERANGE . " " . $this->field->name . "' or '" . LANG_TO_DATERANGE . " " . $this->field->name . "' must be filled in!";
    	}

   		$form_validation->set_rules('selected_dates_' . $this->field->seo_name, LANG_SELECTED_EXACT_DATES . " " . $this->field->name);
   		$form_validation->set_rules('cycle_days_monday_' . $this->field->seo_name, LANG_EVERY_MONDAY . " " . $this->field->name, 'is_checked');
   		$form_validation->set_rules('cycle_days_tuesday_' . $this->field->seo_name, LANG_EVERY_TUESDAY . " " . $this->field->name, 'is_checked');
   		$form_validation->set_rules('cycle_days_wednesday_' . $this->field->seo_name, LANG_EVERY_WEDNESDAY . " " . $this->field->name, 'is_checked');
   		$form_validation->set_rules('cycle_days_thursday_' . $this->field->seo_name, LANG_EVERY_THURSDAY . " " . $this->field->name, 'is_checked');
   		$form_validation->set_rules('cycle_days_friday_' . $this->field->seo_name, LANG_EVERY_FRIDAY . " " . $this->field->name, 'is_checked');
   		$form_validation->set_rules('cycle_days_saturday_' . $this->field->seo_name, LANG_EVERY_SATURDAY . " " . $this->field->name, 'is_checked');
   		$form_validation->set_rules('cycle_days_sunday_' . $this->field->seo_name, LANG_EVERY_SUNDAY . " " . $this->field->name, 'is_checked');
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
		return $CI->content_fields->db->on_duplicate_insert('content_fields_type_datetimerange');
	}
	
	public function getDefaultOptions()
	{
		$options[0]['format'] = 'd.m.Y H:i:s';
		$options[0]['enable_time'] = 0;
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

	    	$search_url_rebuild .= "search_" . $this->field->seo_name . '/' . $args["search_" . $this->field->seo_name] . '/';
	    	
	    	/*$sql = '(
	    		SELECT
	    			cftd.object_id
	    		FROM
	    			content_fields_type_datetimerange_data AS cftd
	    		WHERE
	    			cftd.field_id = ' . $this->field->id . ' AND
	    			cftd.custom_name = "' . $fields_group_name . '" AND
	    			TO_DAYS(cftd.from_field_value) <= TO_DAYS("' . date("Y-m-d", $tmstmp) . '") AND
	    			TO_DAYS(cftd.to_field_value) >= TO_DAYS("' . date("Y-m-d", $tmstmp) . '")
	    	) UNION (
	    		SELECT
	    			cftd.object_id
	    		FROM
	    			content_fields_type_datesmultiple_data AS cftd
	    		WHERE (
	    			(TO_DAYS(cftd.field_value) = TO_DAYS("' . date("Y-m-d", $tmstmp) . '"))
	    			OR
	    			((WEEKDAY("' . date("Y-m-d", $tmstmp) . '") = 0 AND cftd.cycle_days_monday = 1) OR
	    			(WEEKDAY("' . date("Y-m-d", $tmstmp) . '") = 1 AND cftd.cycle_days_tuesday = 1) OR
	    			(WEEKDAY("' . date("Y-m-d", $tmstmp) . '") = 2 AND cftd.cycle_days_wednesday = 1) OR
	    			(WEEKDAY("' . date("Y-m-d", $tmstmp) . '") = 3 AND cftd.cycle_days_thursday = 1) OR
	    			(WEEKDAY("' . date("Y-m-d", $tmstmp) . '") = 4 AND cftd.cycle_days_friday = 1) OR
	    			(WEEKDAY("' . date("Y-m-d", $tmstmp) . '") = 5 AND cftd.cycle_days_saturday = 1) OR
	    			(WEEKDAY("' . date("Y-m-d", $tmstmp) . '") = 6 AND cftd.cycle_days_sunday = 1))
	    			) AND (
	    			cftd.field_id = ' . $this->field->id . ' AND
	    			cftd.custom_name = "' . $fields_group_name . '"
	    			)
	    	)';*/
	    	
	    	$search_date = date("Y-m-d", $tmstmp);

	    	$sql = '
	    		SELECT
	    			cftd.object_id
	    		FROM
	    			content_fields_type_datetimerange_data AS cftdtr, content_fields_type_datesmultiple_data AS cftd
	    		WHERE
			    		(
			    				TO_DAYS(cftd.field_value) = TO_DAYS("' . $search_date . '")
			    			OR
					    		(
					    				(WEEKDAY("' . $search_date . '") = 0 AND cftd.cycle_days_monday = 1) OR
						    			(WEEKDAY("' . $search_date . '") = 1 AND cftd.cycle_days_tuesday = 1) OR
						    			(WEEKDAY("' . $search_date . '") = 2 AND cftd.cycle_days_wednesday = 1) OR
						    			(WEEKDAY("' . $search_date . '") = 3 AND cftd.cycle_days_thursday = 1) OR
						    			(WEEKDAY("' . $search_date . '") = 4 AND cftd.cycle_days_friday = 1) OR
						    			(WEEKDAY("' . $search_date . '") = 5 AND cftd.cycle_days_saturday = 1) OR
						    			(WEEKDAY("' . $search_date . '") = 6 AND cftd.cycle_days_sunday = 1)
					    			AND
						    			(cftdtr.from_field_value <= "' . $search_date . '" AND
						    			cftdtr.to_field_value >= "' . $search_date . '")
						    	)
			    		)
	    			AND 
		    			(cftdtr.field_id = ' . $this->field->id . ' OR cftd.field_id = ' . $this->field->id . ') AND
		    			(cftdtr.custom_name = "' . $fields_group_name . '" OR cftd.custom_name = "' . $fields_group_name . '")
	    	';
	    	return array('AND' => str_replace("\n"," ",$sql));
		}

		if (isset($args["from_tmstmp_" . $this->field->seo_name]) || isset($args["to_tmstmp_" . $this->field->seo_name])) {
			if (isset($args["from_tmstmp_" . $this->field->seo_name])) {
				// Receives date in unix timestamp and also in 'Y-m-d' formats
		    	if (is_numeric($args["from_tmstmp_" . $this->field->seo_name]) && (strtotime(date("Y-m-d", $args["from_tmstmp_" . $this->field->seo_name]))) !== FALSE)
		    		$from_tmstmp = $args["from_tmstmp_" . $this->field->seo_name];
		    	elseif ((strtotime($args["from_tmstmp_" . $this->field->seo_name])) !== FALSE && strtotime($args["from_tmstmp_" . $this->field->seo_name]) != -1)
					$from_tmstmp = strtotime($args["from_tmstmp_" . $this->field->seo_name]);
			} else {
				$from_tmstmp = false;
			}
			if (isset($args["to_tmstmp_" . $this->field->seo_name])) {
				// Receives date in unix timestamp and also in 'Y-m-d' formats
				if (is_numeric($args["to_tmstmp_" . $this->field->seo_name]) && (strtotime(date("Y-m-d", $args["to_tmstmp_" . $this->field->seo_name]))) !== FALSE)
		    		$to_tmstmp = $args["to_tmstmp_" . $this->field->seo_name];
		    	elseif ((strtotime($args["to_tmstmp_" . $this->field->seo_name])) !== FALSE && strtotime($args["to_tmstmp_" . $this->field->seo_name]) != -1)
					$to_tmstmp = strtotime($args["to_tmstmp_" . $this->field->seo_name]);
			} else {
				$to_tmstmp = false;
			}

			$days_of_week_search_sql = array();
			if ($from_tmstmp && $to_tmstmp) {
				$sStartDate = gmdate("Y-m-d", $from_tmstmp);  
				$sEndDate = gmdate("Y-m-d", $to_tmstmp);  
				$aDays[] = date('w', $from_tmstmp);   
				$sCurrentDate = $sStartDate;  
				while($sCurrentDate < $sEndDate){  
					$sCurrentDate = gmdate("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));  
					$aDays[] = date('w', strtotime($sCurrentDate));  
				}
				$aDays = array_unique($aDays);
			} else {
				$aDays = array(0, 1, 2, 3, 4, 5, 6);
			}
			foreach ($aDays AS $day) {
				if ($day == 1) $days_of_week_search_sql[] = 'cftd.cycle_days_monday = 1';
				if ($day == 2) $days_of_week_search_sql[] = 'cftd.cycle_days_tuesday = 1';
				if ($day == 3) $days_of_week_search_sql[] = 'cftd.cycle_days_wednesday = 1';
				if ($day == 4) $days_of_week_search_sql[] = 'cftd.cycle_days_thursday = 1';
				if ($day == 5) $days_of_week_search_sql[] = 'cftd.cycle_days_friday = 1';
				if ($day == 6) $days_of_week_search_sql[] = 'cftd.cycle_days_saturday = 1';
				if ($day == 0) $days_of_week_search_sql[] = 'cftd.cycle_days_sunday = 1';
			}
			
			$sql = '
				(
					SELECT
		    			cftd.object_id
		    		FROM
		    			content_fields_type_datetimerange_data AS cftdtr
		    			JOIN content_fields_type_datesmultiple_data AS cftd ON cftdtr.object_id=cftd.object_id 
		    		WHERE
		    				(
					    		' . ($days_of_week_search_sql ? '(' . implode(' OR ', $days_of_week_search_sql) . ') OR ' : '') . '
					    		' . (($from_tmstmp && $to_tmstmp) ? '
					    		(cftd.field_value >= "' . date("Y-m-d", $from_tmstmp) . '" AND
					    		cftd.field_value <= "' . date("Y-m-d", $to_tmstmp) . '" AND
					    		cftd.field_value != "0000-00-00") ' : '') . '

								' . (($from_tmstmp && !$to_tmstmp) ? '(cftd.field_value >= "' . date("Y-m-d", $from_tmstmp) . '" AND cftd.field_value != "0000-00-00")' : '') . '

								' . (($to_tmstmp && !$from_tmstmp) ? '(cftd.field_value <= "' . date("Y-m-d", $to_tmstmp) . '" AND cftd.field_value != "0000-00-00")' : '') . '
							)
						AND
							(
								' . (($from_tmstmp && $to_tmstmp) ? '
								(cftdtr.to_field_value >= "' . date("Y-m-d", $from_tmstmp) . '" OR cftdtr.to_field_value = "9999-12-31 00:00:00") AND
								(cftdtr.from_field_value <= "' . date("Y-m-d", $to_tmstmp) . '" OR cftdtr.from_field_value = "0000-00-00 00:00:00") ' : '') . '

								' . (($from_tmstmp && !$to_tmstmp) ? '(cftdtr.to_field_value >= "' . date("Y-m-d", $from_tmstmp) . '" OR cftdtr.to_field_value = "9999-12-31 00:00:00")' : '') . '

								' . (($to_tmstmp && !$from_tmstmp) ? '(cftdtr.from_field_value <= "' . date("Y-m-d", $to_tmstmp) . '" OR cftdtr.from_field_value = "0000-00-00 00:00:00")' : '') . '
							)
						AND
							(cftdtr.field_id = ' . $this->field->id . ' OR cftd.field_id = ' . $this->field->id . ') AND
			    			(cftdtr.custom_name = "' . $fields_group_name . '" OR cftd.custom_name = "' . $fields_group_name . '")
				)
				UNION
				(
					SELECT
		    			cftd.object_id
		    		FROM
		    			content_fields_type_datetimerange_data AS cftdtr
		    			RIGHT JOIN content_fields_type_datesmultiple_data AS cftd ON cftdtr.object_id=cftd.object_id 
		    		WHERE
		    				(
					    		' . ($days_of_week_search_sql ? '(' . implode(' OR ', $days_of_week_search_sql) . ') OR ' : '') . '
					    		' . (($from_tmstmp && $to_tmstmp) ? '
					    		(cftd.field_value >= "' . date("Y-m-d", $from_tmstmp) . '" AND
					    		cftd.field_value <= "' . date("Y-m-d", $to_tmstmp) . '" AND
					    		cftd.field_value != "0000-00-00") ' : '') . '

								' . (($from_tmstmp && !$to_tmstmp) ? '(cftd.field_value >= "' . date("Y-m-d", $from_tmstmp) . '" AND cftd.field_value != "0000-00-00")' : '') . '

								' . (($to_tmstmp && !$from_tmstmp) ? '(cftd.field_value <= "' . date("Y-m-d", $to_tmstmp) . '" AND cftd.field_value != "0000-00-00")' : '') . '
							)
						AND
							cftdtr.object_id IS NULL
						AND
			    			(cftd.field_id = ' . $this->field->id . ') AND
			    			(cftd.custom_name = "' . $fields_group_name . '")
				)
				UNION
				(
					SELECT
		    			cftd.object_id
		    		FROM
		    			content_fields_type_datetimerange_data AS cftdtr
		    			LEFT JOIN content_fields_type_datesmultiple_data AS cftd ON cftdtr.object_id=cftd.object_id 
		    		WHERE
		    				cftd.object_id IS NULL
						AND
							(
								' . (($from_tmstmp && $to_tmstmp) ? '
								(cftdtr.to_field_value >= "' . date("Y-m-d", $from_tmstmp) . '" OR cftdtr.to_field_value = "9999-12-31 00:00:00") AND
								(cftdtr.from_field_value <= "' . date("Y-m-d", $to_tmstmp) . '" OR cftdtr.from_field_value = "0000-00-00 00:00:00") ' : '') . '

								' . (($from_tmstmp && !$to_tmstmp) ? '(cftdtr.to_field_value >= "' . date("Y-m-d", $from_tmstmp) . '" OR cftdtr.to_field_value = "9999-12-31 00:00:00")' : '') . '

								' . (($to_tmstmp && !$from_tmstmp) ? '(cftdtr.from_field_value <= "' . date("Y-m-d", $to_tmstmp) . '" OR cftdtr.from_field_value = "0000-00-00 00:00:00")' : '') . '
							)
						AND
			    			(cftdtr.field_id = ' . $this->field->id . ') AND
			    			(cftdtr.custom_name = "' . $fields_group_name . '")
				)
	    	';

			/*$sql = '(
	    		SELECT
	    			cftd.object_id
	    		FROM
	    			content_fields_type_datetimerange_data AS cftd
	    		WHERE
	    			' . ($from_tmstmp ? 'TO_DAYS(cftd.to_field_value) >= TO_DAYS("' . date("Y-m-d", $from_tmstmp) . '") AND' : '') . '
	    			' . ($to_tmstmp ? 'TO_DAYS(cftd.from_field_value) >= TO_DAYS("' . date("Y-m-d", $to_tmstmp) . '") AND' : '') . '
	    			cftd.field_id = ' . $this->field->id . ' AND
	    			cftd.custom_name = "' . $fields_group_name . '"
	    	) UNION (
	    		SELECT
	    			cftd.object_id
	    		FROM
	    			content_fields_type_datesmultiple_data AS cftd
	    		WHERE (
	    			' . ($days_of_week_search_sql ? '(' . implode(' OR ', $days_of_week_search_sql) . ') OR ' : '') . '
	    			' . (($from_tmstmp && $to_tmstmp) ? '
	    			(TO_DAYS(cftd.field_value) >= TO_DAYS("' . date("Y-m-d", $from_tmstmp) . '") AND
	    			TO_DAYS(cftd.field_value) <= TO_DAYS("' . date("Y-m-d", $to_tmstmp) . '") AND
	    			cftd.field_value != "0000-00-00") ' : '') . '

					' . (($from_tmstmp && !$to_tmstmp) ? '(TO_DAYS(cftd.field_value) >= TO_DAYS("' . date("Y-m-d", $from_tmstmp) . '") AND cftd.field_value != "0000-00-00")' : '') . '
					
					' . (($to_tmstmp && !$from_tmstmp) ? '(TO_DAYS(cftd.field_value) <= TO_DAYS("' . date("Y-m-d", $to_tmstmp) . '") AND cftd.field_value != "0000-00-00")' : '') . '

	    			) AND (
	    			cftd.field_id = ' . $this->field->id . ' AND
	    			cftd.custom_name = "' . $fields_group_name . '"
	    			)
	    	)';*/

			if ($from_tmstmp)
				$search_url_rebuild .= "from_tmstmp_" . $this->field->seo_name . '/' . $args["from_tmstmp_" . $this->field->seo_name] . '/';
			if ($to_tmstmp)
				$search_url_rebuild .= "to_tmstmp_" . $this->field->seo_name . '/' . $args["to_tmstmp_" . $this->field->seo_name] . '/';

	    	return array('AND' => str_replace("\n"," ",$sql));
		}
	}

	/**
	 * Special order by the nearest to NOW date
	 * @param string $direction - 
	 * 		when desc: order by the nearest to NOW date
	 * 		when asc: order by the farthest from NOW date
	 */
	public function orderby($object_ids, $group_custom_name, $content_field_id, $direction = 'desc')
	{
		$CI = &get_instance();
		$CI->load->model('content_fields', 'content_fields');

		// Get all available records with dates
		$sql = '
				SELECT
		    		cftd.object_id AS cftd_object_id, cftdtr.object_id AS cftdtr_object_id,
		    		cftd.field_value, cftd.cycle_days_monday, cftd.cycle_days_tuesday, cftd.cycle_days_wednesday, cftd.cycle_days_thursday, cftd.cycle_days_friday, cftd.cycle_days_saturday, cftd.cycle_days_sunday,
		    		cftdtr.from_field_value, cftdtr.to_field_value
		    	FROM
		    		content_fields_type_datetimerange_data AS cftdtr, content_fields_type_datesmultiple_data AS cftd 
		    	WHERE
						(cftdtr.field_id = ' . $content_field_id . ' OR cftd.field_id = ' . $content_field_id . ') AND
		    			(cftdtr.custom_name = "' . $group_custom_name . '" OR cftd.custom_name = "' . $group_custom_name . '") AND
		    			(cftdtr.object_id IN (' . implode(', ', $object_ids) . ') OR cftd.object_id IN (' . implode(', ', $object_ids) . '))';

		
				    /*(
					    		(cftd.field_value >= "' . date("Y-m-d") . '" AND
					    		cftd.field_value != "0000-00-00")
				    		OR
				    			cftd.field_value = "0000-00-00"
				    		OR
				    			(cftd.cycle_days_monday=1 OR cftd.cycle_days_tuesday=1 OR cftd.cycle_days_wednesday=1 OR cftd.cycle_days_thursday=1 OR cftd.cycle_days_friday=1 OR cftd.cycle_days_saturday=1 OR cftd.cycle_days_sunday=1)
						)
					AND
						(
							(cftdtr.from_field_value <= "' . date("Y-m-d") . '" OR cftdtr.from_field_value = "0000-00-00 00:00:00")
							AND
							(cftdtr.to_field_value >= "' . date("Y-m-d") . '" OR cftdtr.to_field_value = "9999-12-31 00:00:00")
						)
					AND*/
		
		$result_query = $CI->content_fields->db->query(str_replace("\n"," ",$sql));
		
		// Now find available ranges in datetimeranges table
		$result_dates = array();
		$result = $result_query->result_array();
		$cftdtr_dates = array();
		foreach ($result AS $cftdtr_row) {
			if (!isset($cftdtr_dates[$cftdtr_row['cftdtr_object_id']])) {
				if ($cftdtr_row['from_field_value'] != "0000-00-00 00:00:00" && strtotime($cftdtr_row['from_field_value']) > strtotime(date('Y-m-d', time()))) {
					$start_date = strtotime($cftdtr_row['from_field_value']);
				} else {
					$start_date = strtotime(date('Y-m-d'));
				}
				if ($cftdtr_row['to_field_value'] != "9999-12-31 00:00:00") {
					$end_date = strtotime($cftdtr_row['to_field_value']);
				} else {
					$end_date = null;
				}
				$cftdtr_dates[$cftdtr_row['cftdtr_object_id']]['start_date'] = $start_date;
				$cftdtr_dates[$cftdtr_row['cftdtr_object_id']]['end_date'] = $end_date;
				
				if (($cftdtr_row['from_field_value'] == "0000-00-00 00:00:00" || strtotime($cftdtr_row['from_field_value']) <= strtotime(date('Y-m-d')))
				 && ($cftdtr_row['to_field_value'] == "9999-12-31 00:00:00" || strtotime($cftdtr_row['to_field_value']) >= strtotime(date('Y-m-d'))))
					$is_in_range = true;
				else
					$is_in_range = false;

				$cftdtr_dates[$cftdtr_row['cftdtr_object_id']]['is_in_range'] = $is_in_range;
			}
		}

		// Find nearest dates in multidates table
		foreach ($result AS $cftd_row) {
			if (!isset($result_dates[$cftd_row['cftd_object_id']])) {
				if (isset($nearest_date))
					unset($nearest_date);
				if (isset($cftdtr_dates[$cftd_row['cftd_object_id']])) {
					$start_date = $cftdtr_dates[$cftd_row['cftd_object_id']]['start_date'];
					$end_date = $cftdtr_dates[$cftd_row['cftd_object_id']]['end_date'];
					$is_in_range = $cftdtr_dates[$cftd_row['cftd_object_id']]['is_in_range'];
				} else {
					$start_date = strtotime(date('Y-m-d'));
					$end_date = null;
					$is_in_range = true;
				}
	
				if ($is_in_range) {
					if ($cftd_row['cycle_days_monday'] && $cftd_row['cycle_days_tuesday'] && $cftd_row['cycle_days_wednesday'] && $cftd_row['cycle_days_thursday'] && $cftd_row['cycle_days_friday'] && $cftd_row['cycle_days_saturday'] && $cftd_row['cycle_days_sunday'])
						$nearest_date = $start_date;
					else {
						if ($cftd_row['cycle_days_monday']) { $date = strtotime('next Monday', $start_date-1); if (!isset($nearest_date) || $nearest_date > $date) $nearest_date = $date; }
						if ($cftd_row['cycle_days_tuesday']) { $date = strtotime('next Tuesday', $start_date-1); if (!isset($nearest_date) || $nearest_date > $date) $nearest_date = $date; }
						if ($cftd_row['cycle_days_wednesday']) { $date = strtotime('next Wednesday', $start_date-1); if (!isset($nearest_date) || $nearest_date > $date) $nearest_date = $date; }
						if ($cftd_row['cycle_days_thursday']) { $date = strtotime('next Thursday', $start_date-1); if (!isset($nearest_date) || $nearest_date > $date) $nearest_date = $date; }
						if ($cftd_row['cycle_days_friday']) { $date = strtotime('next Friday', $start_date-1); if (!isset($nearest_date) || $nearest_date > $date) $nearest_date = $date; }
						if ($cftd_row['cycle_days_saturday']) { $date = strtotime('next Saturday', $start_date-1); if (!isset($nearest_date) || $nearest_date > $date) $nearest_date = $date; }
						if ($cftd_row['cycle_days_sunday']) { $date = strtotime('next Sunday', $start_date-1); if (!isset($nearest_date) || $nearest_date > $date) $nearest_date = $date; }
					}
				}
	
				foreach ($result AS $alone_dates_row) {
					if ($cftd_row['cftd_object_id'] == $alone_dates_row['cftd_object_id']) {
						if ($alone_dates_row['field_value'] != '0000-00-00') {
							$alone_date = strtotime($alone_dates_row['field_value']);
							if (isset($nearest_date)) {
								if ($alone_date < $nearest_date && $alone_date >= strtotime(date('Y-m-d'))) {
									$nearest_date = $alone_date;
								}
							} elseif ($alone_date >= strtotime(date('Y-m-d')))
								$nearest_date = $alone_date;
						}
						if (!isset($nearest_date))
							$is_in_range = false;
					}
				}

				if (isset($nearest_date) && (is_null($end_date) || $nearest_date <= $end_date)) {
					$result_dates[$cftd_row['cftd_object_id']] = date('Y-m-d', $nearest_date);
				}
				if (!isset($nearest_date) && $is_in_range)
					$result_dates[$cftd_row['cftd_object_id']] = date('Y-m-d');
				if (!isset($nearest_date) && !$is_in_range)
					$result_dates[$cftd_row['cftd_object_id']] = '9999-12-31';
			}
		}
		
		foreach ($cftdtr_dates AS $listing_id=>$date_array) {
			if (!isset($result_dates[$listing_id])) {
				if ($date_array['is_in_range'])
					$result_dates[$listing_id] = date('Y-m-d', $date_array['start_date']);
				else 
					$result_dates[$listing_id] = '9999-12-31';
			}
		}
		
		// Sort dates 
		asort($result_dates);
		//var_dump($result_dates);
		return array_keys($result_dates);
	}

	public function getSomeNearestDates($object_id, $group_custom_name, $how_many_dates = 20)
	{
		$content_field_id = $this->field->id;

		$CI = &get_instance();
		$CI->load->model('content_fields', 'content_fields');

		// Get all available records with dates
		$sql = '
				SELECT
		    		cftd.object_id AS cftd_object_id, cftdtr.object_id AS cftdtr_object_id,
		    		cftd.field_value, cftd.cycle_days_monday, cftd.cycle_days_tuesday, cftd.cycle_days_wednesday, cftd.cycle_days_thursday, cftd.cycle_days_friday, cftd.cycle_days_saturday, cftd.cycle_days_sunday,
		    		cftdtr.from_field_value, cftdtr.to_field_value
		    	FROM
		    		content_fields_type_datetimerange_data AS cftdtr, content_fields_type_datesmultiple_data AS cftd 
		    	WHERE
						(cftdtr.field_id = ' . $content_field_id . ' OR cftd.field_id = ' . $content_field_id . ') AND
		    			(cftdtr.custom_name = "' . $group_custom_name . '" OR cftd.custom_name = "' . $group_custom_name . '") AND
		    			(cftdtr.object_id = ' . $object_id . ' OR cftd.object_id = ' . $object_id . ')';
		$result_query = $CI->content_fields->db->query(str_replace("\n"," ",$sql));
		
		// Now find available ranges in datetimeranges table
		$result_dates = array();
		$result = $result_query->result_array();
		$is_range = false;
		foreach ($result AS $cftdtr_row) {
			if ($cftdtr_row['cftdtr_object_id'] == $object_id) {
				if ($cftdtr_row['from_field_value'] != "0000-00-00 00:00:00" && strtotime($cftdtr_row['from_field_value']) > strtotime(date('Y-m-d', time()))) {
					$start_date = strtotime($cftdtr_row['from_field_value']);
				} else {
					$start_date = strtotime(date('Y-m-d', time()));
				}
				if ($cftdtr_row['to_field_value'] != "9999-12-31 00:00:00") {
					$end_date = strtotime($cftdtr_row['to_field_value']);
				} else {
					$end_date = strtotime(date('Y-m-d', time()+63072000));
				}
				$cftdtr_dates['start_date'] = $start_date;
				$cftdtr_dates['end_date'] = $end_date;
				
				if ($cftdtr_row['from_field_value'] != "0000-00-00 00:00:00" && $cftdtr_row['to_field_value'] != "9999-12-31 00:00:00")
					$is_range = true;
			}
		}

		// Find nearest dates in multidates table
		foreach ($result AS $cftd_row) {
			if ($cftd_row['cftd_object_id'] == $object_id) {
				if (isset($cftdtr_dates)) {
					$start_date = $cftdtr_dates['start_date'];
					$end_date = $cftdtr_dates['end_date'];
				} else {
					$start_date = strtotime(date('Y-m-d', time()));
					$end_date = strtotime(date('Y-m-d', time()+63072000));
				}
				
				if (($cftd_row['cycle_days_monday'] && $cftd_row['cycle_days_tuesday'] && $cftd_row['cycle_days_wednesday'] && $cftd_row['cycle_days_thursday'] && $cftd_row['cycle_days_friday'] && $cftd_row['cycle_days_saturday'] && $cftd_row['cycle_days_sunday'])
				|| ($is_range && !$cftd_row['cycle_days_monday'] && !$cftd_row['cycle_days_tuesday'] && !$cftd_row['cycle_days_wednesday'] && !$cftd_row['cycle_days_thursday'] && !$cftd_row['cycle_days_friday'] && !$cftd_row['cycle_days_saturday'] && !$cftd_row['cycle_days_sunday'])) {
					for ($i = 0; $i < $how_many_dates; $i++) {
						$date = strtotime('+'.$i.' day', $start_date-1);
						if ($date < $end_date)
							if (!in_array($date, $result_dates))
								$result_dates[] = $date;
					}
				} else {
					if ($cftd_row['cycle_days_monday']) {
						for ($i = 0; $i < $how_many_dates; $i++) {
							$date = strtotime('+'.$i.' week Monday', $start_date-1);
							if ($date < $end_date)
								if (!in_array($date, $result_dates))
									$result_dates[] = $date;
						}
					}
					if ($cftd_row['cycle_days_tuesday']) {
						for ($i = 0; $i < $how_many_dates; $i++) {
							$date = strtotime('+'.$i.' week Tuesday', $start_date-1);
							if ($date < $end_date)
								if (!in_array($date, $result_dates))
									$result_dates[] = $date;
						}
					}
					if ($cftd_row['cycle_days_wednesday']) {
						for ($i = 0; $i < $how_many_dates; $i++) {
							$date = strtotime('+'.$i.' week Wednesday', $start_date-1);
							if ($date < $end_date)
								if (!in_array($date, $result_dates))
									$result_dates[] = $date;
						}
					}
					if ($cftd_row['cycle_days_thursday']) {
						for ($i = 0; $i < $how_many_dates; $i++) {
							$date = strtotime('+'.$i.' week Thursday', $start_date-1);
							if ($date < $end_date)
								if (!in_array($date, $result_dates))
									$result_dates[] = $date;
						}
					}
					if ($cftd_row['cycle_days_friday']) {
						for ($i = 0; $i < $how_many_dates; $i++) {
							$date = strtotime('+'.$i.' week Friday', $start_date-1);
							if ($date < $end_date)
								if (!in_array($date, $result_dates))
									$result_dates[] = $date;
						}
					}
					if ($cftd_row['cycle_days_saturday']) {
						for ($i = 0; $i < $how_many_dates; $i++) {
							$date = strtotime('+'.$i.' week Saturday', $start_date-1);
							if ($date < $end_date)
								if (!in_array($date, $result_dates))
									$result_dates[] = $date;
						}
					}
					if ($cftd_row['cycle_days_sunday']) {
						for ($i = 0; $i < $how_many_dates; $i++) {
							$date = strtotime('+'.$i.' week Sunday', $start_date-1);
							if ($date < $end_date)
								if (!in_array($date, $result_dates))
									$result_dates[] = $date;
						}
					}
				}

				foreach ($result AS $alone_dates_row) {
					if ($cftd_row['cftd_object_id'] == $alone_dates_row['cftd_object_id']) {
						if ($alone_dates_row['field_value'] != '0000-00-00') {
							$alone_date = strtotime($alone_dates_row['field_value']);
							if (/*$start_date <= $alone_date && */$end_date >= $alone_date)
								if (!in_array($alone_date, $result_dates))
									$result_dates[] = $alone_date;
						}
					}
				}
			}
		}
		if (empty($result_dates) && isset($start_date) && isset($end_date)) {
			for ($i = 1; $i <= $how_many_dates; $i++) {
				$date = strtotime('+'.$i.' day', $start_date-1);
				if ($date < $end_date)
					if (!in_array($date, $result_dates))
						$result_dates[] = $date;
			}
		}
		// Sort dates 
		asort($result_dates);

		$result_dates = array_slice($result_dates, 0, $how_many_dates);
		foreach ($result_dates AS $key=>$date)
			$result_dates[$key] = date('Y-m-d', $date);

		//var_dump($result_dates);
		return $result_dates;
	}
}
?>