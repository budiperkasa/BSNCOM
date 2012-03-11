<?php

class calendarController extends controller
{
	public function calendar_settings()
    {
    	$this->load->model('calendar');
    	
    	$this->load->model('types', 'types_levels');
    	$types = $this->types->getTypesLevels();

    	if ($this->input->post('submit')) {
    		$this->form_validation->set_rules('name', LANG_CALENDAR_NAME, 'required|max_length[255]');
            $this->form_validation->set_rules('connected_type_id', LANG_CALENDAR_TYPE, 'required|integer');
            $this->form_validation->set_rules('connected_field', LANG_CALENDAR_FIELD, 'required');
            $this->form_validation->set_rules('visibility_on_index', LANG_CALENDAR_VISIBILITY_INDEX, 'required');
            $this->form_validation->set_rules('visibility_for_all_types', LANG_CALENDAR_VISIBILITY_FOR_TYPES, 'required');

			if ($this->form_validation->run() !== FALSE) {
				$form_result = $this->form_validation->result_array();
				if ($this->calendar->saveSettings($form_result)) {
					$this->setSuccess(LANG_CALENDAR_SAVE_SUCCESS);
					redirect('admin/calendar_settings/');
				}
			}
			$settings = $this->form_validation->result_array();
    	} else {
    		$settings = $this->calendar->getSettings();
    	}

    	if ($this->input->post('connected_type_select') !== FALSE) {
    		$connected_type_id = $this->input->post('connected_type_select');
    	} else {
    		$connected_type_id = $settings['connected_type_id'];
    	}
    	
    	$search_fields = $this->calendar->getSearchFieldsByTypeId($connected_type_id);

    	$view = $this->load->view();
		$view->assign('settings', $settings);
		$view->assign('types', $types);
		$view->assign('connected_type_id', $connected_type_id);
		$view->assign('search_fields', $search_fields);
        $view->display('calendar/admin_calendar_settings.tpl');
    }
}
?>