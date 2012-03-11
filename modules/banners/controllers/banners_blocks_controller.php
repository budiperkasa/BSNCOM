<?php

class banners_blocksController extends controller
{
	public function index()
    {
    	$this->load->model('banners_blocks');
    	
    	$banners_blocks = $this->banners_blocks->getBannersBlocks();

		$view = $this->load->view();
		$view->assign('banners_blocks', $banners_blocks);
        $view->display('banners/admin_banners_blocks.tpl');
    }
    
    public function create()
    {
        $this->load->model('banners_blocks');

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('name', LANG_BANNERS_BLOCK_NAME_TH, 'required|max_length[255]');
            $this->form_validation->set_rules('mode', LANG_BANNERS_BLOCK_MODE_TH, 'required|max_length[255]');
            $this->form_validation->set_rules('selector', LANG_BANNERS_BLOCK_SELECTOR_TH, 'required|max_length[255]');
            $this->form_validation->set_rules('banner_width', LANG_WIDTH, 'required|integer');
            $this->form_validation->set_rules('banner_height', LANG_HEIGHT, 'required|integer');
            $this->form_validation->set_rules('active_years', LANG_YEARS, 'integer');
			$this->form_validation->set_rules('active_months', LANG_MONTHS, 'integer');
			$this->form_validation->set_rules('active_days', LANG_DAYS, 'integer');
			$this->form_validation->set_rules('clicks_limit', LANG_BANNERS_BLOCK_CLICKS_LIMIT_TH, 'integer');
			$this->form_validation->set_rules('limit_mode', LANG_BANNERS_BLOCK_LIMITATION_MODE_TH, 'required|max_length[255]');
			$this->form_validation->set_rules('allow_remote_banners', LANG_BANNERS_ALLOW_REMOTE_BANNERS, 'is_checked');

			if ($this->form_validation->run() !== FALSE) {
				$form_result = $this->form_validation->result_array();
				if ($this->banners_blocks->saveBannersBlock($form_result)) {
					$this->setSuccess(LANG_NEW_BANNERS_BLOCK_CREATE_SUCCESS_1 . ' "' . $form_result['name'] . '" ' . LANG_NEW_BANNERS_BLOCK_CREATE_SUCCESS_2);
					redirect('admin/banners_blocks/');
				}
			} else {
            	$banners_block = $this->banners_blocks->getBlockFromForm($this->form_validation->result_array());
			}
        } else {
            $banners_block = $this->banners_blocks->getNewBlock();
        }
        
        registry::set('breadcrumbs', array(
    		'admin/banners_blocks/' => LANG_BANNERS_MANAGE_BLOCKS_TITLE,
    		LANG_BANNERS_BLOCKS_CREATE_TITLE,
    	));

        $view  = $this->load->view();
        $view->assign('banners_block', $banners_block);
        $view->display('banners/admin_banners_block_settings.tpl');
    }
    
    public function edit($block_id)
    {
        $this->load->model('banners_blocks');

        $this->banners_blocks->setBlockId($block_id);

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('name', LANG_BANNERS_BLOCK_NAME_TH, 'required|max_length[255]');
            $this->form_validation->set_rules('mode', LANG_BANNERS_BLOCK_MODE_TH, 'required|max_length[255]');
            $this->form_validation->set_rules('selector', LANG_BANNERS_BLOCK_SELECTOR_TH, 'required|max_length[255]');
            $this->form_validation->set_rules('banner_width', LANG_WIDTH, 'required|integer');
            $this->form_validation->set_rules('banner_height', LANG_HEIGHT, 'required|integer');
            $this->form_validation->set_rules('active_years', LANG_YEARS, 'integer');
			$this->form_validation->set_rules('active_months', LANG_MONTHS, 'integer');
			$this->form_validation->set_rules('active_days', LANG_DAYS, 'integer');
			$this->form_validation->set_rules('clicks_limit', LANG_BANNERS_BLOCK_CLICKS_LIMIT_TH, 'integer');
			$this->form_validation->set_rules('limit_mode', LANG_BANNERS_BLOCK_LIMITATION_MODE_TH, 'required|max_length[255]');
			$this->form_validation->set_rules('allow_remote_banners', LANG_BANNERS_ALLOW_REMOTE_BANNERS, 'is_checked');

			if ($this->form_validation->run() !== FALSE) {
				$form_result = $this->form_validation->result_array();
				if ($this->banners_blocks->saveBannersBlockById($form_result)) {
					$this->setSuccess(LANG_BANNERS_BLOCK_SAVE_SUCCESS_1 . ' "' . $form_result['name'] . '" ' . LANG_BANNERS_BLOCK_SAVE_SUCCESS_2);
					redirect('admin/banners_blocks/');
				}
			} else {
            	$banners_block = $this->banners_blocks->getBlockFromForm($this->form_validation->result_array());
			}
        } else {
            $banners_block = $this->banners_blocks->getBannersBlockById();
        }

        registry::set('breadcrumbs', array(
    		'admin/banners_blocks/' => LANG_BANNERS_MANAGE_BLOCKS_TITLE,
    		LANG_BANNERS_BLOCKS_EDIT_TITLE,
    	));

        $view  = $this->load->view();
        $view->assign('banners_block', $banners_block);
        $view->display('banners/admin_banners_block_settings.tpl');
    }
    
    public function delete($block_id)
    {
        $this->load->model('banners_blocks');

        $this->banners_blocks->setBlockId($block_id);

        if ($this->input->post('yes')) {
            if ($this->banners_blocks->deleteBannersBlockById()) {
            	$this->setSuccess(LANG_BANNERS_BLOCK_DELETE_SUCCESS);
                redirect('admin/banners_blocks/');
            }
        }

        if ($this->input->post('no')) {
            redirect('admin/banners_blocks/');
        }

        if ( !$banners_block = $this->banners_blocks->getBannersBlockById()) {
            redirect('admin/banners_blocks/');
        }
        
        registry::set('breadcrumbs', array(
    		'admin/banners_blocks/' => LANG_BANNERS_MANAGE_BLOCKS_TITLE,
    		LANG_BANNERS_BLOCKS_DELETE_TITLE,
    	));

		$view  = $this->load->view();
		$view->assign('options', array($block_id => $banners_block->name));
        $view->assign('heading', LANG_DELETE_BANNERS_BLOCK);
        $view->assign('question', LANG_DELETE_BANNERS_BLOCK_QUEST);
        $view->display('backend/delete_common_item.tpl');
    }
}
?>