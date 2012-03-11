<?php
include_once(MODULES_PATH . 'content_pages/classes/content_page.class.php');

class content_pagesController extends controller
{
	public function index()
    {
    	$this->load->model('content_pages');
    	
    	if ($this->input->post('submit')) {
            $this->form_validation->set_rules('serialized_order', LANG_SERIALIZED_ORDER_VALUE);
            if ($this->form_validation->run() !== FALSE) {
            	$this->content_pages->setPagesOrder($this->form_validation->set_value('serialized_order'));
            	// Clean cache
				$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('content_pages'));

            	$this->setSuccess(LANG_PAGES_ORDER_SAVE_SUCCESS);
            	redirect('admin/pages/manage/');
            }
        }
		$nodes = $this->content_pages->selectNodes();

		$view = $this->load->view();
		$view->addJsFile('jquery.tablednd_0_5.js');
		$view->assign('nodes', $nodes);
		$view->display('content_pages/admin_content_pages.tpl');
    }

    /**
     * Action of 'admin/preview/%' route
     *
     */
    public function preview($node_id)
    {
    	$this->load->model('content_pages');
    	$this->content_pages->setNodeId($node_id);

    	$node = new content_page($node_id);
    	$node->setPageFromArray($this->content_pages->getNodeById());
    	
    	registry::set('breadcrumbs', array(
	    	'admin/pages/manage/' => LANG_MANAGE_CONTENT_PAGES,
	    	LANG_VIEW_CONTENT_PAGE . " '" . $node->title() . "'",
	    ));

		$view = $this->load->view();
		$view->assign('node', $node);
		$view->assign('title', 'Preview: ' . $node->title());
        $view->display('content_pages/admin_node_preview.tpl');
    }
    
    /**
     * form validation function
     *
     * @param string $seoname
     * @return bool
     */
    public function is_unique_node($node_url)
    {
    	$this->load->model('content_pages');
		
		if ($this->content_pages->is_unique_node($node_url)) {
			$this->form_validation->set_message('node_url');
			return FALSE;
		} else {
			return TRUE;
		}
    }
    
    public function create()
    {
    	$this->load->model('content_pages');

        $node = new content_page;
        if ($this->input->post('submit')) {
        	$this->form_validation->set_rules('url', LANG_PAGE_URL, 'required|max_length[255]|alpha_dash|callback_is_unique_node');
        	$this->form_validation->set_rules('title', LANG_PAGE_TITLE, 'required|max_length[255]');
        	$this->form_validation->set_rules('in_menu', LANG_ENABLE_LINK_IN_MENU, 'is_checked');
        	$this->form_validation->set_rules('meta_title', LANG_META_TITLE, 'max_length[255]');
            $this->form_validation->set_rules('meta_description', LANG_META_DESCRIPTION, 'max_length[255]');
        	$node->validateFields($this->form_validation);
        	
        	if ($this->form_validation->run() !== FALSE) {
        		$form = $this->form_validation->result_array();
        		if ($page_id = $this->content_pages->savePage($form)) {
					if ($node->saveFields($page_id, $form)) {
						// Clean cache
						$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('content_pages'));

						$this->setSuccess(LANG_CONTENT_PAGE_CREATE_SUCCESS_1 . ' "' . $form['title'] . '" ' . LANG_CONTENT_PAGE_CREATE_SUCCESS_2);
						redirect('admin/pages/manage/');
					}
				}
        	} else {
        		$node->getPageFromForm($this->form_validation->result_array());
        	}
        }
        
        registry::set('breadcrumbs', array(
	    	'admin/pages/manage/' => LANG_MANAGE_CONTENT_PAGES,
	    	LANG_CREATE_CONTENT_PAGE,
	    ));

		$view = $this->load->view();
		$view->assign('node', $node);
		$view->display('content_pages/admin_content_pages_settings.tpl');
    }
    
    public function edit($node_id)
    {
    	$this->load->model('content_pages');
    	$this->content_pages->setNodeId($node_id);

    	$node = new content_page($node_id);
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('url', LANG_PAGE_URL, 'required|max_length[255]|alpha_dash|callback_is_unique_node');
        	$this->form_validation->set_rules('title', LANG_PAGE_TITLE, 'required|max_length[255]');
        	$this->form_validation->set_rules('in_menu', LANG_ENABLE_LINK_IN_MENU, 'is_checked');
        	$this->form_validation->set_rules('meta_title', LANG_META_TITLE, 'max_length[255]');
            $this->form_validation->set_rules('meta_description', LANG_META_DESCRIPTION, 'max_length[255]');
            $node->validateFields($this->form_validation);
            
            if ($this->form_validation->run() !== FALSE) {
        		$form = $this->form_validation->result_array();
        		if ($this->content_pages->savePageById($form)) {
        			if ($node->updateFields($form)) {
        				// Clean cache
						$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('content_pages'));

        				$this->setSuccess(LANG_CONTENT_PAGE_SAVE_SUCCESS_1 . ' "' . $form['title'] . '" ' . LANG_CONTENT_PAGE_SAVE_SUCCESS_2);
						redirect('admin/pages/manage/');
        			}
        		}
            } else {
            	$node->getPageFromForm($this->form_validation->result_array());
				$node->content_fields->getValuesFromForm($this->form_validation->result_array());
            }
        } else {
			$node->setPageFromArray($this->content_pages->getNodeById());
        }
        
        registry::set('breadcrumbs', array(
	    	'admin/pages/manage/' => LANG_MANAGE_CONTENT_PAGES,
	    	LANG_EDIT_CONTENT_PAGE . " '" . $node->title() . "'",
	    ));

        $view = $this->load->view();
        $view->assign('node', $node);
		$view->display('content_pages/admin_content_pages_settings.tpl');
    }
    
    public function delete($node_id)
    {
    	$this->load->model('content_pages');
		$this->content_pages->setNodeId($node_id);

		if ($this->input->post('yes')) {
            if ($this->content_pages->deletePageById()) {
            	// Clean cache
				$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('content_pages'));

            	$this->setSuccess(LANG_CONTENT_PAGE_DELETE_SUCCESS);
                redirect('admin/pages/manage/');
            }
        }

        if ($this->input->post('no')) {
            redirect('admin/pages/manage/');
        }

        if ( !$node_array = $this->content_pages->getNodeById()) {
            redirect('admin/pages/manage/');
        }
        
	    registry::set('breadcrumbs', array(
	    	'admin/pages/manage/' => LANG_MANAGE_CONTENT_PAGES,
	    	LANG_DELETE_CONTENT_PAGE . " '" . $node_array['title'] . "'",
	    ));

		$view  = $this->load->view();
		$view->assign('options', array($node_id => $node_array['title']));
        $view->assign('heading', LANG_DELETE_CONTENT_PAGE);
        $view->assign('question', LANG_DELETE_CONTENT_PAGE_QUESTION);
        $view->display('backend/delete_common_item.tpl');
    }
}
?>