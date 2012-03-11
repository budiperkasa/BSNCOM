<?php

class banners_paymentController extends Controller
{
	public function prices()
    {
    	$this->load->model('banners_payment');

    	$this->load->model('banners_blocks');
    	$banners_blocks = $this->banners_blocks->getBannersBlocks();

    	$this->load->model('users_groups', 'users');
    	$users_groups = $this->users_groups->getUsersGroups();

    	$prices = $this->banners_payment->getBannersPrice($banners_blocks, $users_groups);

        $view  = $this->load->view();
        $view->assign('banners_blocks', $banners_blocks);
        $view->assign('users_groups', $users_groups);
        $view->assign('prices', $prices);
        $view->display('banners/admin_payment_banners_manage.tpl');
    }
    
    public function payment_settings($group_id, $block_id)
    {
    	$this->load->model('banners_payment');

    	if ($this->input->post('submit')) {
    		$this->form_validation->set_rules('currency', LANG_BANNERS_PRICE_CURRENCY, 'required');
    		$this->form_validation->set_rules('value', LANG_BANNERS_PRICE, 'numeric|required');
    		
    		if ($this->form_validation->run() !== FALSE) {
    			if ($this->banners_payment->savePrice($group_id, $block_id, $this->form_validation->result_array())) {
    				$this->setSuccess(LANG_PRICE_SAVED_SUCCESS);
    				redirect('admin/banners/payment/');
    			}
    		} else {
    			$price_row = $this->banners_payment->getPriceSettingsFromForm($this->form_validation->result_array());
    		}
    	} else {
    		$price_row = $this->banners_payment->getBannersPriceByBlockIdAndGroupId($block_id, $group_id);
    	}

    	$this->load->model('users_groups', 'users');
    	$this->users_groups->setUsersGroupId($group_id);
    	$user_group = $this->users_groups->getUsersGroupById();
    	$user_group_name = $user_group->name;

    	$this->load->model('banners_blocks');
    	$this->banners_blocks->setBlockId($block_id);
    	$banners_block = $this->banners_blocks->getBannersBlockById();
    	$block_name = $banners_block->name;
    	
    	registry::set('breadcrumbs', array(
    		'admin/banners/payment/' => LANG_VIEW_PAYMENT_SETTINGS_TITLE,
    		LANG_BLOCK_PRICE_1 . ' "' . $block_name . '" ' . LANG_BLOCK_PRICE_2 . ' "' . $user_group_name . '"',
    	));
    	
    	$view = $this->load->view();
    	$view->assign('price_row', $price_row);
    	$view->assign('user_group_name', $user_group_name);
    	$view->assign('block_name', $block_name);
    	$view->assign('currencies', $this->config->item('currency_codes'));
    	$view->display('banners/admin_payment_banners_settings.tpl');
    }
}
?>