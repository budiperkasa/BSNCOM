<?php

class categoriesController extends controller
{
    public function categories()
    {
		$this->load->model('categories');
		
		if ($this->input->post('submit')) {
            $this->form_validation->set_rules('list', LANG_CATEGORIES_SER_LIST);

            if ($this->form_validation->run() !== FALSE) {
                if ($this->categories->saveCategories($this->form_validation->set_value('list'))) {
                	// Clean cache
					$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('categories'));

                	$this->setSuccess(LANG_ORDER_CATEGORY_SUCCESS);
                }
            }
			redirect('admin/categories/');
        }

        $view  = $this->load->view();
        $view->addJsFile('jquery.jstree.js');
        $view->display('categories/admin_categories.tpl');
    }

    /**
     * Ajax request through categories levels browsing
     */
    public function ajax_categories_request($template_name)
    {
    	if ($this->input->post('id') !== false) {
    		$parent_id = $this->input->post('id');
	    	$is_counter = $this->input->post('is_counter');
	    	if ($this->input->post('max_depth'))
	    		$max_depth = $this->input->post('max_depth');
	    	else
	    		$max_depth = 'max';
	    	if ($this->input->post('selected_categories'))
	    		$selected_categories = json_decode($this->input->post('selected_categories'));
	    	else 
	    		$selected_categories = null;
	    	$highlight_element = $this->input->post('highlight_element');
	    	$is_children_label = $this->input->post('is_children_label');
	    	if ($this->input->post('type_id'))
	    		$type_id = json_decode($this->input->post('type_id'));
	    	else 
	    		$type_id = 0;

	    	$this->load->model('categories');
	    	if ($parent_id != 0) {
		    	$parent_category = $this->categories->getCategoryById($parent_id);
		    	$parent_category->buildChildren($max_depth);
		    	$items = $parent_category->children;
		    	$type_id = $parent_category->type_id;
	    	} else {
	    		// We may extract items of the tree's root
	    		$this->categories->setTypeId($type_id);
	    		$items = $this->categories->getDirectChildrenOfCategory(0);
	    	}
	    	$categories_json = array();
	    	$view = $this->load->view();
	    	$view->assign('type_id', $type_id);
	    	$rendered_template = $view->fetch(trim($template_name, '/'));
	    	foreach ($items AS $category) {
				$categories_json[] = $category->render($rendered_template, $is_counter, $max_depth, $selected_categories, $highlight_element, $is_children_label);
	    	}
	    	// also trim last ',' with line-break symbol 
        	echo '[' . trim(implode(' ', $categories_json), ',') . ']';
	    }
    }
    
    public function is_unique_category_seoname($seo_name)
    {
    	$this->load->model('categories');
		
		if ($this->categories->is_category_seoname($seo_name)) {
			$this->form_validation->set_message('seoname');
			return FALSE;
		} else {
			return TRUE;
		}
    }
    
    public function create()
    {
    	$this->load->model('categories');
    	
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
					redirect('admin/categories/');
				}
			}
            $category = $this->categories->getCategoryFromForm($this->form_validation->result_array());
        } else {
            $category = $this->categories->getNewCategory();
        }
        
        registry::set('breadcrumbs', array(
    		'admin/categories/' => LANG_EDIT_CATEGORIES,
    		LANG_CREATE_CATEGORY,
    	));

        $view  = $this->load->view();
        $view->assign('category', $category);
        $view->display('categories/admin_category_settings.tpl');
    }
    
    public function create_child($parent_id)
    {
    	$this->load->model('categories');
    	
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
					redirect('admin/categories/');
				}
			}
            $category = $this->categories->getCategoryFromForm($this->form_validation->result_array());
        } else {
            $category = $this->categories->getNewCategory();
        }
        
        registry::set('breadcrumbs', array(
    		'admin/categories/' => LANG_EDIT_CATEGORIES,
    		LANG_CREATE_CHILD_CATEGORY,
    	));

        $view  = $this->load->view();
        $view->assign('category', $category);
        $view->display('categories/admin_category_settings.tpl');
    }
    
    public function edit($category_id)
    {
    	$this->load->model('categories');
    	
    	$this->categories->setCategoryId($category_id);

    	if ($this->input->post('submit')) {
            $this->form_validation->set_rules('name', LANG_CATEGORY_NAME, 'required|max_length[45]');
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
					redirect('admin/categories/');
				}
			}
            $category = $this->categories->getCategoryFromForm($this->form_validation->result_array());
        } else {
            $category = $this->categories->getCategoryById();
        }
        
        registry::set('breadcrumbs', array(
    		'admin/categories/' => LANG_EDIT_CATEGORIES,
    		LANG_EDIT_CATEGORY . ' "' . $category->name . '"',
    	));

        $view  = $this->load->view();
        $view->assign('category', $category);
        $view->display('categories/admin_category_settings.tpl');
    }
    
    public function delete($category_id)
    {
        $this->load->model('categories');

        $this->categories->setCategoryId($category_id);

        if ($this->input->post('yes')) {
            if ($this->categories->deleteCategoryById()) {
            	// Clean cache
				$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('categories'));

            	$this->setSuccess(LANG_DELETE_CATEGORY_SUCCESS);
                redirect('admin/categories/');
            }
        }

        if ($this->input->post('no')) {
            redirect('admin/categories/');
        }

        if ( !$category = $this->categories->getCategoryById()) {
            redirect('admin/categories/');
        }
        
        registry::set('breadcrumbs', array(
    		'admin/categories/' => LANG_EDIT_CATEGORIES,
    		LANG_DELETE_CATEGORY . ' "' . $category->name . '"',
    	));

		$view  = $this->load->view();
		$view->assign('options', array($category_id => $category->name));
        $view->assign('heading', LANG_DELETE_CATEGORY);
        $view->assign('question', LANG_DELETE_CATEGORY_QUEST);
        $view->display('backend/delete_common_item.tpl');
    }

    public function get_categories_path($type_id = null)
    {
    	$category_id = $this->input->post('category_id');
    	$categories_list = array_filter(unserialize($this->input->post('categories_list')));
    	$categories_list[] = $category_id;
    	// --------------------------------------------------------------------------------------------
    	// Build categories path from category_id to the root of categories tree
    	// --------------------------------------------------------------------------------------------
    	$this->load->model('categories');
    	$category = $this->categories->getCategoryById($category_id);
    	$category_path = $category->getChainAsString();
    	
    	$result = $this->categories->isIcons($categories_list);

    	$is_icons = $result['is_icons'];
    	$single_icon = $result['single_icon'];
    	$single_icon_file = $result['single_icon_file'];
    	// --------------------------------------------------------------------------------------------

    	$json = array(
    		"selected_cat_name" => _utf8_encode($category_path),
    		"selected_cat_id" => $category_id,
    		"is_icons" => $is_icons,
    		"single_icon" => $single_icon,
    		"single_icon_file" => $single_icon_file,
    		"is_selected_icons" => serialize(array())
		);
		echo json_encode($json);
    }
    
    /**
	 * validation function
	 *
	 * @param string $email
	 * @return bool
	 */
	public function check_captcha($captcha)
	{
		if ($this->session->userdata('captcha_word') != $captcha) {
			$this->form_validation->set_message('captcha');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
    public function send_suggestion()
    {
    	if ($this->input->post('submit')) {
    		$this->form_validation->set_rules('suggested_category', LANG_SUGGEST_CATEGORY, 'required|max_length[255]');
    		$this->form_validation->set_rules('captcha', LANG_CAPTCHA, 'callback_check_captcha');
    		
    		if ($this->form_validation->run() !== FALSE) {
    			$suggested_category = $this->form_validation->set_value('suggested_category');
    			$system_settings = registry::get('system_settings');
				$event_params = array(
					'SUGGESTED_CATEGORY' => $suggested_category, 
					'SENDER_NAME' => $this->session->userdata('user_login'),
					'SENDER_EMAIL' => $this->session->userdata('user_email'),
					'RECIPIENT_NAME' => '',
					'RECIPIENT_EMAIL' => $system_settings['website_email'],
				);
				$notification = new notificationSender('Category suggestion');
				if ($notification->send($event_params))
					$this->setSuccess(LANG_SUGGEST_CATEGORY_SUCCESS);
    		}
    		$suggested_category = $this->form_validation->set_value('suggested_category');
    	} else {
    		$suggested_category = '';
    	}

    	$this->load->plugin('captcha');
		$captcha = create_captcha($this);

    	$view = $this->load->view();
	    $view->assign('suggested_category', $suggested_category);
	    $view->assign('captcha', $captcha);
	    $view->assign('sender_url', site_url("ajax/categories/send_suggestion/"));
    	$view->display('categories/suggest_category.tpl');
    }
}
?>