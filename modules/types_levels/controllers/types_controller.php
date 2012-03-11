<?php

class typesController extends Controller
{
    public function index()
    {
    	$this->load->model('types');

    	if ($this->input->post('submit')) {
            $this->form_validation->set_rules('serialized_order', LANG_SERIALIZED_ORDER_VALUE);
            if ($this->form_validation->run() !== FALSE) {
            	$this->types->setTypesOrder($this->form_validation->set_value('serialized_order'));
            	// Clean cache
				$this->cache->clean(Zend_Cache::CLEANING_MODE_ALL);

            	$this->setSuccess(LANG_TYPES_ORDER_SAVE_SUCCESS);
            	redirect('admin/types/');
            }
        }
    	
    	$types = $this->types->getTypesLevels();

		$view = $this->load->view();
		$view->addJsFile('jquery.tablednd_0_5.js');
		$view->assign('types', $types);
        $view->display('types_levels/admin_types.tpl');
    }

    /**
     * form validation function
     *
     * @param string $name
     * @return bool
     */
    public function is_unique_type_name($name)
    {
    	$this->load->model('types');
		
		if ($this->types->is_type_name($name)) {
			$this->form_validation->set_message('name');
			return FALSE;
		} else {
			return TRUE;
		}
    }
    
    /**
     * form validation function
     *
     * @param string $seoname
     * @return bool
     */
    public function is_unique_type_seoname($seoname)
    {
    	$this->load->model('types');
		
		if ($this->types->is_type_seoname($seoname)) {
			$this->form_validation->set_message('seoname');
			return FALSE;
		} else {
			return TRUE;
		}
    }

    public function create()
    {
    	$system_settings = registry::get('system_settings');
    	$single_type_structure = $system_settings['single_type_structure'];
    	
    	$this->load->model('types');
    	$types = $this->types->getTypesLevels();
    	
    	if ($single_type_structure && count($types) >= 1) {
    		$this->setError(LANG_TYPES_SINGLE_MODE_ERROR . ' ' . LANG_TYPES_SINGLE_MODE_ERROR_HELP);
    		redirect('admin/types');
    	}

		if ($this->input->post('submit')) {
			$this->form_validation->set_rules('name', LANG_TYPE_NAME, 'required|max_length[35]|callback_is_unique_type_name');
			$this->form_validation->set_rules('seo_name', LANG_TYPE_SEO_NAME, 'required|alpha_dash|max_length[255]|callback_is_unique_type_seoname');
			$this->form_validation->set_rules('locations_enabled', LANG_TYPE_LOCATIONS, 'is_checked');
			$this->form_validation->set_rules('zip_enabled', LANG_TYPE_ZIP_PARAM, 'is_checked');
			if (!$single_type_structure) {
				$this->form_validation->set_rules('search_type', LANG_TYPE_SEARCH_TYPE, 'required');
				$this->form_validation->set_rules('what_search', LANG_TYPE_WHAT_SEARCH, 'integer');
				$this->form_validation->set_rules('where_search', LANG_TYPE_WHERE_SEARCH, 'integer');
				$this->form_validation->set_rules('categories_search', LANG_TYPE_CATEGORIES_SEARCH, 'integer');
				$this->form_validation->set_rules('categories_type', LANG_TYPE_CATEGORIES_TYPE, 'required');
			}
			$this->form_validation->set_rules('meta_title', LANG_META_TITLE, 'max_length[255]');
			$this->form_validation->set_rules('meta_description', LANG_META_DESCRIPTION, 'max_length[255]');

			if ($this->form_validation->run() !== FALSE) {
				$form_result = $this->form_validation->result_array();
				if ($this->types->saveType($form_result)) {
					// Clean cache
					$this->cache->clean(Zend_Cache::CLEANING_MODE_ALL);

					$this->setSuccess(LANG_NEW_LISTING_TYPE_CREATE_SUCCESS_1 . ' "' . $form_result['name'] . '" ' . LANG_NEW_LISTING_TYPE_CREATE_SUCCESS_2);
					redirect('admin/types/');
				}
			} else {
				$type = $this->types->getTypeFromForm($this->form_validation->result_array());
			}
		} else {
			$type = $this->types->getNewType();
		}

        registry::set('breadcrumbs', array(
    		'admin/types/' => LANG_LISTINGS_TYPES,
    		LANG_CREATE_TYPE,
    	));

        $view  = $this->load->view();
        $view->assign('type', $type);
        $view->assign('types', $types);
        $view->display('types_levels/admin_type_settings.tpl');
    }

    public function edit($type_id)
    {
    	$system_settings = registry::get('system_settings');
    	$single_type_structure = $system_settings['single_type_structure'];

        $this->load->model('types');

        $this->types->setTypeId($type_id);
        $type = $this->types->getTypeById();

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('id', LANG_TYPE_ID, 'required|integer');
            $this->form_validation->set_rules('name', LANG_TYPE_NAME, 'required|max_length[35]|callback_is_unique_type_name');
            $this->form_validation->set_rules('seo_name', LANG_TYPE_SEO_NAME, 'required|alpha_dash|max_length[255]|callback_is_unique_type_seoname');
            $this->form_validation->set_rules('locations_enabled', LANG_TYPE_LOCATIONS, 'is_checked');
            $this->form_validation->set_rules('zip_enabled', LANG_TYPE_ZIP_PARAM, 'is_checked');
            if (!$single_type_structure) {
	            $this->form_validation->set_rules('search_type', LANG_TYPE_SEARCH_TYPE, 'required');
	            $this->form_validation->set_rules('what_search', LANG_TYPE_WHAT_SEARCH, 'integer');
	            $this->form_validation->set_rules('where_search', LANG_TYPE_WHERE_SEARCH, 'integer');
	            $this->form_validation->set_rules('categories_search', LANG_TYPE_CATEGORIES_SEARCH, 'integer');
	            $this->form_validation->set_rules('categories_type', LANG_TYPE_CATEGORIES_TYPE, 'required');
            }
            $this->form_validation->set_rules('meta_title', LANG_META_TITLE, 'max_length[255]');
            $this->form_validation->set_rules('meta_description', LANG_META_DESCRIPTION, 'max_length[255]');

			if ($this->form_validation->run() !== FALSE) {
				$form_result = $this->form_validation->result_array();
				if ($this->types->saveTypeById($form_result)) {
					// Clean cache
					$this->cache->clean(Zend_Cache::CLEANING_MODE_ALL);

					$this->setSuccess(LANG_NEW_LISTING_TYPE_SAVE_SUCCESS_1 . ' "' . $form_result['name'] . '" ' . LANG_NEW_LISTING_TYPE_SAVE_SUCCESS_2);
					redirect('admin/types/');
				}
			} else {
            	$type = $this->types->getTypeFromForm($this->form_validation->result_array());
			}
        }

        registry::set('breadcrumbs', array(
    		'admin/types/' => LANG_LISTINGS_TYPES,
    		LANG_EDIT_TYPE . ' "' . $type->name . '"',
    	));

        $view  = $this->load->view();
        $view->assign('type', $type);
        $view->assign('type_name', $type->name);
        $view->assign('types', $this->types->getTypesLevels());
        $view->display('types_levels/admin_type_settings.tpl');
    }
    
    public function delete($type_id)
    {
        $this->load->model('types');

        $this->types->setTypeId($type_id);

        if ($this->input->post('yes')) {
            if ($this->types->deleteTypeById()) {
            	// Clean cache
				$this->cache->clean(Zend_Cache::CLEANING_MODE_ALL);

            	$this->setSuccess(LANG_TYPE_DELETE_SUCCESS);
                redirect('admin/types/');
            }
        }

        if ($this->input->post('no')) {
            redirect('admin/types/');
        }

        if ( !$type = $this->types->getTypeById()) {
            redirect('admin/types/');
        }
        
        registry::set('breadcrumbs', array(
    		'admin/types/' => LANG_LISTINGS_TYPES,
    		LANG_DELETE_TYPE . ' "' . $type->name . '"',
    	));

		$view  = $this->load->view();
		$view->assign('options', array($type_id => $type->name));
        $view->assign('heading', LANG_DELETE_TYPE);
        $view->assign('question', LANG_DELETE_TYPE_QUEST);
        $view->display('backend/delete_common_item.tpl');
    }
}
?>