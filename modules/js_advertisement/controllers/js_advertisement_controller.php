<?php
include_once(MODULES_PATH . 'js_advertisement/classes/js_advertisement_block.class.php');

class js_advertisementController extends controller
{
	public function __construct($components)
	{
		// Disable global XSS filtering for Ads javascript
		$this->config = &load_class('Config');
		$this->config->set_item('global_xss_filtering', FALSE);

		parent::Controller($components);
	}
	
	public function index()
    {
    	$this->load->model('js_advertisement');
    	
    	$js_advertisement_blocks = $this->js_advertisement->getJsAdvertisementArray();

		$view = $this->load->view();
		$view->assign('js_advertisement_blocks', $js_advertisement_blocks);
        $view->display('js_advertisement/admin_js_advertisement.tpl');
    }
    
    public function create()
    {
        $this->load->model('js_advertisement');

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('name', LANG_JSADVERTISEMENT_BLOCK_NAME_TH, 'required|max_length[255]');
            $this->form_validation->set_rules('mode', LANG_JSADVERTISEMENT_BLOCK_MODE_TH, 'required|max_length[255]');
            $this->form_validation->set_rules('selector', LANG_JSADVERTISEMENT_BLOCK_SELECTOR_TH, 'required|max_length[255]');
            $this->form_validation->set_rules('code', LANG_JSADVERTISEMENT_CODE, 'required|max_length[2000]');

			if ($this->form_validation->run() !== FALSE) {
				$form_result = $this->form_validation->result_array();
				if ($this->js_advertisement->saveJsAdvertisementBlock($form_result)) {
					$this->setSuccess(LANG_NEW_JSADVERTISEMENT_CREATE_SUCCESS_1 . ' "' . $form_result['name'] . '" ' . LANG_NEW_JSADVERTISEMENT_CREATE_SUCCESS_2);
					redirect('admin/js_advertisement/');
				}
			} else {
            	$block = $this->js_advertisement->getBlockFromForm($this->form_validation->result_array());
			}
        } else {
            $block = $this->js_advertisement->getNewBlock();
        }
        
        registry::set('breadcrumbs', array(
    		'admin/js_advertisement/' => LANG_JSADVERTISEMENT_MANAGE_BLOCKS_TITLE,
    		LANG_JSADVERTISEMENT_CREATE_TITLE,
    	));

        $view  = $this->load->view();
        $view->assign('js_advertisement', $block);
        $view->display('js_advertisement/admin_js_advertisement_settings.tpl');
    }
    
    public function edit($block_id)
    {
        $this->load->model('js_advertisement');

        $this->js_advertisement->setBlockId($block_id);

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('name', LANG_JSADVERTISEMENT_BLOCK_NAME_TH, 'required|max_length[255]');
            $this->form_validation->set_rules('mode', LANG_JSADVERTISEMENT_BLOCK_MODE_TH, 'required|max_length[255]');
            $this->form_validation->set_rules('selector', LANG_JSADVERTISEMENT_BLOCK_SELECTOR_TH, 'required|max_length[255]');
            $this->form_validation->set_rules('code', LANG_JSADVERTISEMENT_CODE, 'required|max_length[2000]');

			if ($this->form_validation->run() !== FALSE) {
				$form_result = $this->form_validation->result_array();
				if ($this->js_advertisement->saveJsAdvertisementBlockById($form_result)) {
					$this->setSuccess(LANG_JSADVERTISEMENT_SAVE_SUCCESS_1 . ' "' . $form_result['name'] . '" ' . LANG_JSADVERTISEMENT_SAVE_SUCCESS_2);
					redirect('admin/js_advertisement/');
				}
			} else {
            	$block = $this->js_advertisement->getBlockFromForm($this->form_validation->result_array());
			}
        } else {
            $block = $this->js_advertisement->getJsAdvertisementBlockById();
        }

        registry::set('breadcrumbs', array(
    		'admin/js_advertisement/' => LANG_JSADVERTISEMENT_MANAGE_BLOCKS_TITLE,
    		LANG_JSADVERTISEMENT_EDIT_TITLE,
    	));

        $view  = $this->load->view();
        $view->assign('js_advertisement', $block);
        $view->assign('js_advertisement_name', $block->name);
        $view->display('js_advertisement/admin_js_advertisement_settings.tpl');
    }
    
    public function delete($block_id)
    {
        $this->load->model('js_advertisement');

        $this->js_advertisement->setBlockId($block_id);

        if ($this->input->post('yes')) {
            if ($this->js_advertisement->deleteJsAdvertisementById()) {
            	$this->setSuccess(LANG_JSADVERTISEMENT_DELETE_SUCCESS);
                redirect('admin/js_advertisement/');
            }
        }

        if ($this->input->post('no')) {
            redirect('admin/js_advertisement/');
        }

        if ( !$block = $this->js_advertisement->getJsAdvertisementBlockById()) {
            redirect('admin/js_advertisement/');
        }
        
        registry::set('breadcrumbs', array(
    		'admin/js_advertisement/' => LANG_JSADVERTISEMENT_MANAGE_BLOCKS_TITLE,
    		LANG_JSADVERTISEMENT_DELETE_TITLE,
    	));

		$view  = $this->load->view();
		$view->assign('options', array($block_id => $block->name));
        $view->assign('heading', LANG_DELETE_JSADVERTISEMENT);
        $view->assign('question', LANG_DELETE_JSADVERTISEMENT_QUEST);
        $view->display('backend/delete_common_item.tpl');
    }
}
?>