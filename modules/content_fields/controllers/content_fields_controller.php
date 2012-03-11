<?php

class content_fieldsController extends controller
{
	public function index()
	{
    	$this->load->model('content_fields');
    	$fields = $this->content_fields->selectAllFields();

    	$view = $this->load->view();
    	$view->assign('fields', $fields);
        $view->display('content_fields/admin_manage_fields.tpl');
	}
	
	/**
	 * form validation function
	 *
	 * @param str $field_name
	 * @return bool
	 */
	public function check_field_name($field_name)
	{
		$this->load->model('content_fields');
		
		if ($this->content_fields->is_field_name($field_name)) {
			$this->form_validation->set_message('name');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	public function check_seo_field_name($field_name)
	{
		$this->load->model('content_fields');
		
		if ($this->content_fields->is_seo_field_name($field_name)) {
			$this->form_validation->set_message('seo_name');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	public function create()
	{
        $this->load->model('content_fields');
        
        // Let collect all avaliable types of field
        $field_types = $this->content_fields->getAllFieldTypes();

        if ($this->input->post('submit')) {
        	$this->form_validation->set_rules('name', LANG_FIELD_NAME, 'required|max_length[255]|callback_check_field_name');
        	$this->form_validation->set_rules('frontend_name', LANG_FIELD_FRONTEND_NAME, 'max_length[255]');
        	$this->form_validation->set_rules('seo_name', LANG_FIELD_SEO_NAME, 'required|alpha_dash|max_length[255]|callback_check_seo_field_name');
        	$this->form_validation->set_rules('type', LANG_FIELD_TYPE, 'required');
        	$this->form_validation->set_rules('description', LANG_FIELD_DESCRIPTION, 'max_length[1000]');
        	$this->form_validation->set_rules('required', LANG_FIELD_REQUIRED, 'is_checked');
        	$this->form_validation->set_rules('v_index_page', LANG_FIELD_VISIBILITY_INDEX, 'is_checked');
        	$this->form_validation->set_rules('v_types_page', LANG_FIELD_VISIBILITY_TYPES, 'is_checked');
        	$this->form_validation->set_rules('v_categories_page', LANG_FIELD_VISIBILITY_CATEGORIES, 'is_checked');
        	$this->form_validation->set_rules('v_search_page', LANG_FIELD_VISIBILITY_SEARCH, 'is_checked');

        	if ($this->form_validation->run() !== FALSE) {
        		// Complete field object and save it into DB
        		$field = $this->content_fields->buildFieldObj($this->form_validation->result_array());
        		if ($this->content_fields->saveField($field)) {
					$this->setSuccess(LANG_FIELD_CREATE_SUCCESS);
					redirect('admin/fields/');
				}
        	} else {
        		$field = $this->content_fields->buildFieldObj($this->form_validation->result_array());
        	}
        } else {
            $field = $this->content_fields->getNewField();
        }
        
        registry::set('breadcrumbs', array(
    		'admin/fields/' => LANG_MANAGE_CONTENT_FIELDS,
    		LANG_CREATE_FIELD,
    	));

        $view = $this->load->view();
        $view->assign('field', $field);
        $view->assign('field_types', $field_types);
        $view->display('content_fields/admin_field_settings.tpl');
	}
	
	public function edit($field_id)
    {
		$this->load->model('content_fields');

		$this->content_fields->setFieldId($field_id);

        $field_types = $this->content_fields->getAllFieldTypes();

        if ($this->input->post('submit')) {
        	$this->form_validation->set_rules('name', LANG_FIELD_NAME, 'required|max_length[255]|callback_check_field_name');
        	$this->form_validation->set_rules('frontend_name', LANG_FIELD_FRONTEND_NAME, 'max_length[255]');
        	$this->form_validation->set_rules('seo_name', LANG_FIELD_SEO_NAME, 'required|alpha_dash|max_length[255]|callback_check_seo_field_name');
        	$this->form_validation->set_rules('description', LANG_FIELD_DESCRIPTION, 'max_length[1000]');
        	$this->form_validation->set_rules('required', LANG_FIELD_REQUIRED, 'is_checked');
        	$this->form_validation->set_rules('v_index_page', LANG_FIELD_VISIBILITY_INDEX, 'is_checked');
        	$this->form_validation->set_rules('v_types_page', LANG_FIELD_VISIBILITY_TYPES, 'is_checked');
        	$this->form_validation->set_rules('v_categories_page', LANG_FIELD_VISIBILITY_CATEGORIES, 'is_checked');
        	$this->form_validation->set_rules('v_search_page', LANG_FIELD_VISIBILITY_SEARCH, 'is_checked');
        	
        	if ($this->form_validation->run() !== FALSE) {
        		// Complete field object and save it into DB
        		$field = $this->content_fields->buildFieldObj($this->form_validation->result_array());
        		if ($this->content_fields->saveFieldById($field)) {
        			// Clean cache
					$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('content_fields'));

					$this->setSuccess(LANG_FIELD_SAVE_SUCCESS);
					redirect('admin/fields/');
				}
        	} else {
        		$field = $this->content_fields->buildFieldObj($this->form_validation->result_array());
        	}
        } else {
            $field = $this->content_fields->getFieldById();
        }
        
        registry::set('breadcrumbs', array(
    		'admin/fields/' => LANG_MANAGE_CONTENT_FIELDS,
    		LANG_EDIT_FIELD . ' "' . $field->name . '"',
    	));

        $view  = $this->load->view();
        $view->assign('field', $field);
        $view->assign('field_types', $field_types);
        $view->display('content_fields/admin_field_settings.tpl');
    }
    
    public function delete($field_id)
    {
        $this->load->model('content_fields');

        $this->content_fields->setFieldId($field_id);

        if ($this->input->post('yes')) {
            if ($this->content_fields->deleteFieldById()) {
            	// Clean cache
				$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('content_fields'));

            	$this->setSuccess(LANG_FIELD_DELETE_SUCCESS);
                redirect('admin/fields/');
            }
        }

        if ($this->input->post('no')) {
            redirect('admin/fields/');
        }

        if ( !$field = $this->content_fields->getFieldById()) {
            redirect('admin/fields/');
        }
        
        registry::set('breadcrumbs', array(
    		'admin/fields/' => LANG_MANAGE_CONTENT_FIELDS,
    		LANG_DELETE_FIELD . ' "' . $field->name . '"',
    	));

		$view  = $this->load->view();
		$view->assign('options', array($field_id => $field->name));
        $view->assign('heading', LANG_DELETE_FIELD);
        $view->assign('question', LANG_DELETE_FIELD_QUEST);
        $view->display('backend/delete_common_item.tpl');
    }
    
    public function configure($field_id)
    {
    	$this->load->model('content_fields');

		$this->content_fields->setFieldId($field_id);

		// Call configuration view directly from content field type class
        $this->content_fields->configureField();
    }
    
    public function copy($field_id)
    {
    	$this->load->model('content_fields');

		$this->content_fields->setFieldId($field_id);

        if ($this->content_fields->copyField()) {
        	$this->setSuccess(LANG_FIELD_COPY_SUCCESS);
        	redirect('admin/fields/');
        }
    }
    
    public function configure_search($field_id)
    {
    	$this->load->model('content_fields');
    	$this->content_fields->setFieldId($field_id);
    	
    	// Call configuration view directly from content field type class
        $this->content_fields->configureFieldSearch();
    }
    
    public function website_redirect($field_value_id)
    {
    	$this->load->model('content_fields');
    	$url = $this->content_fields->getWebsiteUrlById($field_value_id);
    	
    	// Some hostings return 302 HTTP error with 'Status: 200' header while redirect,
    	// so we have to get rid of it.
    	//header("Status: 200");

    	$url = trim($url, '/');

    	header('Location: ' . prep_url($url));
    }
}
?>