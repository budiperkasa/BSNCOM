<?php

class categories_by_typeController extends controller
{
	public function choose_type()
	{
		$this->load->model('types', 'types_levels');
    	$types = $this->types->selectLocalCategoriesTypes();

    	$view = $this->load->view();
    	$view->assign('types', $types);
        $view->display('categories/local_categories_types.tpl');
	}
	
    public function categories_by_type($type_id)
    {
		$this->load->model('categories');
		$this->load->model('types', 'types_levels');
		
		$this->categories->setTypeId($type_id);
		$this->types->setTypeId($type_id);
		$type = $this->types->getTypeById();
		
		if ($this->input->post('submit')) {
            $this->form_validation->set_rules('list', LANG_CATEGORIES_SER_LIST);

            if ($this->form_validation->run() !== FALSE) {
                if ($this->categories->saveCategories($this->form_validation->set_value('list'))) {
                	// Clean cache
					$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('categories'));

                	$this->setSuccess(LANG_ORDER_CATEGORY_SUCCESS);
                }
            }
			redirect('admin/categories/by_type/' . $type_id . '/');
        }

        registry::set('breadcrumbs', array(
    		'admin/categories/by_type/' => LANG_CHOOSE_TYPE_OF_CATEGORIES_TITLE,
    		LANG_MANAGE_CATEGORIES_BY_TYPE . ' "' . $type->name . '"',
    	));

        $view  = $this->load->view();
        $view->addJsFile('jquery.jstree.js');

        $view->assign('type', $type);
        $view->assign('type_id', $type_id);
        $view->display('categories/admin_local_categories.tpl');
    }

    public function is_unique_category_seoname($seo_name)
    {
		if ($this->categories->is_category_seoname($seo_name)) {
			$this->form_validation->set_message('seoname');
			return FALSE;
		} else {
			return TRUE;
		}
    }
    
    public function create_by_type($type_id)
    {
    	$this->load->model('categories');
    	$this->load->model('types', 'types_levels');
    	
    	$this->categories->setTypeId($type_id);
		$this->types->setTypeId($type_id);
		$type = $this->types->getTypeById();
    	
    	if ($this->input->post('submit')) {
            $this->form_validation->set_rules('name', LANG_CATEGORY_NAME, 'required|max_length[45]');
            $this->form_validation->set_rules('seo_name', LANG_CATEGORY_SEO_NAME, 'required|alpha_dash|max_length[255]|callback_is_unique_category_seoname');
            $this->form_validation->set_rules('meta_title', LANG_META_TITLE, 'max_length[255]');
            $this->form_validation->set_rules('meta_description', LANG_META_DESCRIPTION, 'max_length[255]');
            $this->form_validation->set_rules('selected_icons_serialized', LANG_CATEGORY_SELECTED_ICONS_SERIALISED_LIST);

			if ($this->form_validation->run() !== FALSE) {
				$form_result = $this->form_validation->result_array();
				if ($this->categories->saveCategory($form_result)) {
					// Clean cache
					$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('categories'));

					$this->setSuccess(LANG_CREATE_CATEGORY_SUCCESS);
					redirect('admin/categories/by_type/' . $type_id . '/');
				}
			}
            $category = $this->categories->getCategoryFromForm($this->form_validation->result_array());
        } else {
            $category = $this->categories->getNewCategory();
        }
        
        registry::set('breadcrumbs', array(
    		'admin/categories/by_type/' => LANG_CHOOSE_TYPE_OF_CATEGORIES_TITLE,
    		'admin/categories/by_type/' . $type_id => LANG_MANAGE_CATEGORIES_BY_TYPE . ' "' . $type->name . '"',
    		LANG_CREATE_CATEGORY_BY_TYPE_TITLE,
    	));

        $view  = $this->load->view();
        $view->assign('type', $type);
        $view->assign('type_id', $type_id);
        $view->assign('category', $category);
        $view->display('categories/admin_local_category_settings.tpl');
    }
    
    public function create_child_by_type($type_id, $parent_id)
    {
    	$this->load->model('categories');
    	$this->load->model('types', 'types_levels');
    	
    	$this->categories->setTypeId($type_id);
		$this->types->setTypeId($type_id);
		$type = $this->types->getTypeById();
    	
    	if ($this->input->post('submit')) {
            $this->form_validation->set_rules('name', LANG_CATEGORY_NAME, 'required|max_length[45]');
            $this->form_validation->set_rules('seo_name', LANG_CATEGORY_SEO_NAME, 'required|alpha_dash|max_length[255]|callback_is_unique_category_seoname');
            $this->form_validation->set_rules('meta_title', LANG_META_TITLE, 'max_length[255]');
            $this->form_validation->set_rules('meta_description', LANG_META_DESCRIPTION, 'max_length[255]');
            $this->form_validation->set_rules('selected_icons_serialized', LANG_CATEGORY_SELECTED_ICONS_SERIALISED_LIST);

			if ($this->form_validation->run() !== FALSE) {
				$form_result = $this->form_validation->result_array();
				if ($this->categories->saveCategoryChild($parent_id, $form_result)) {
					// Clean cache
					$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('categories'));

					$this->setSuccess(LANG_CREATE_CATEGORY_SUCCESS);
					redirect('admin/categories/by_type/' . $type_id . '/');
				}
			}
            $category = $this->categories->getCategoryFromForm($this->form_validation->result_array());
        } else {
            $category = $this->categories->getNewCategory();
        }
        
        registry::set('breadcrumbs', array(
    		'admin/categories/by_type/' => LANG_CHOOSE_TYPE_OF_CATEGORIES_TITLE,
    		'admin/categories/by_type/' . $type_id => LANG_MANAGE_CATEGORIES_BY_TYPE . ' "' . $type->name . '"',
    		LANG_CREATE_CHILD_CATEGORY_BY_TYPE_TITLE,
    	));

        $view  = $this->load->view();
        $view->assign('type', $type);
        $view->assign('type_id', $type_id);
        $view->assign('category', $category);
        $view->display('categories/admin_local_category_settings.tpl');
    }
    
    public function edit_by_type($type_id, $category_id)
    {
    	$this->load->model('categories');
    	$this->load->model('types', 'types_levels');
    	
    	$this->categories->setCategoryId($category_id);
    	$this->categories->setTypeId($type_id);
		$this->types->setTypeId($type_id);
		$type = $this->types->getTypeById();
    	
    	if ($this->input->post('submit')) {
            $this->form_validation->set_rules('name', LANG_CATEGORY_NAME, 'required|max_length[35]');
            $this->form_validation->set_rules('seo_name', LANG_CATEGORY_SEO_NAME, 'required|alpha_dash|max_length[255]|callback_is_unique_category_seoname');
            $this->form_validation->set_rules('meta_title', LANG_META_TITLE, 'max_length[255]');
            $this->form_validation->set_rules('meta_description', LANG_META_DESCRIPTION, 'max_length[255]');
            $this->form_validation->set_rules('selected_icons_serialized', LANG_CATEGORY_SELECTED_ICONS_SERIALISED_LIST);

			if ($this->form_validation->run() !== FALSE) {
				$form_result = $this->form_validation->result_array();
				if ($this->categories->saveCategoryById($form_result)) {
					// Clean cache
					$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('categories'));

					$this->setSuccess(LANG_SAVE_CATEGORY_SUCCESS);
					redirect('admin/categories/by_type/' . $type_id . '/');
				}
			}
            $category = $this->categories->getCategoryFromForm($this->form_validation->result_array());
        } else {
            $category = $this->categories->getCategoryById();
        }
        
        registry::set('breadcrumbs', array(
    		'admin/categories/by_type/' => LANG_CHOOSE_TYPE_OF_CATEGORIES_TITLE,
    		'admin/categories/by_type/' . $type_id => LANG_MANAGE_CATEGORIES_BY_TYPE . ' "' . $type->name . '"',
    		LANG_EDIT_CATEGORY_BY_TYPE_TITLE,
    	));

        $view  = $this->load->view();
        $view->assign('type', $type);
        $view->assign('type_id', $type_id);
        $view->assign('category', $category);
        $view->display('categories/admin_local_category_settings.tpl');
    }
    
    public function delete_by_type($type_id, $category_id)
    {
        $this->load->model('categories');
    	$this->load->model('types', 'types_levels');
    	
    	$this->categories->setCategoryId($category_id);
    	$this->categories->setTypeId($type_id);
		$this->types->setTypeId($type_id);
		$type = $this->types->getTypeById();

        if ($this->input->post('yes')) {
            if ($this->categories->deleteCategoryById()) {
            	// Clean cache
					$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('categories'));

            	$this->setSuccess(LANG_DELETE_CATEGORY_SUCCESS);
                redirect('admin/categories/by_type/' . $type_id . '/');
            }
        }

        if ($this->input->post('no')) {
            redirect('admin/categories/by_type/' . $type_id . '/');
        }

        if ( !$category = $this->categories->getCategoryById()) {
            redirect('admin/categories/by_type/' . $type_id . '/');
        }
        
        registry::set('breadcrumbs', array(
    		'admin/categories/by_type/' => LANG_CHOOSE_TYPE_OF_CATEGORIES_TITLE,
    		'admin/categories/by_type/' . $type_id => LANG_MANAGE_CATEGORIES_BY_TYPE . ' "' . $type->name . '"',
    		LANG_DELETE_CATEGORY_BY_TYPE_TITLE,
    	));

		$view  = $this->load->view();
		$view->assign('options', array($category_id => $category->name));
        $view->assign('heading', LANG_DELETE_CATEGORY);
        $view->assign('question', LANG_DELETE_CATEGORY_QUEST);
        $view->display('backend/delete_common_item.tpl');
    }
}
?>