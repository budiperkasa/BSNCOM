<?php

class payment_listingsController extends controller
{
    public function prices()
    {
    	$this->load->model('payment_listings');
    	
    	$this->load->model('types', 'types_levels');
    	$types = $this->types->getTypesLevels();

    	$this->load->model('users_groups', 'users');
    	$users_groups = $this->users_groups->getUsersGroups();

    	$prices = $this->payment_listings->getListingsPrice($types, $users_groups);

        $view  = $this->load->view();
        $view->assign('types', $types);
        $view->assign('users_groups', $users_groups);
        $view->assign('prices', $prices);
        $view->display('payment/listings/admin_payment_listings_manage.tpl');
    }
    
    public function payment_settings($group_id, $level_id)
    {
    	$this->load->model('payment_listings');

    	if ($this->input->post('submit')) {
    		$this->form_validation->set_rules('currency', LANG_LISTINGS_PRICE_CURRENCY, 'required');
    		$this->form_validation->set_rules('value', LANG_LISTINGS_PRICE, 'numeric|required');
    		
    		if ($this->form_validation->run() !== FALSE) {
    			if ($this->payment_listings->savePrice($group_id, $level_id, $this->form_validation->result_array())) {
    				$this->setSuccess(LANG_PRICE_SAVED_SUCCESS);
    				redirect('admin/listings/payment/');
    			}
    		} else {
    			$price_row = $this->payment_listings->getPriceSettingsFromForm($this->form_validation->result_array());
    		}
    	} else {
    		$price_row = $this->payment_listings->getListingsPriceByLevelIdAndGroupId($level_id, $group_id);
    	}

    	$this->load->model('users_groups', 'users');
    	$this->users_groups->setUsersGroupId($group_id);
    	$user_group = $this->users_groups->getUsersGroupById();
    	$user_group_name = $user_group->name;

    	$this->load->model('levels', 'types_levels');
    	$level = $this->levels->getLevelById($level_id);
    	
    	registry::set('breadcrumbs', array(
    		'admin/listings/payment/' => LANG_VIEW_PAYMENT_SETTINGS_TITLE,
    		LANG_LEVEL_PRICE_1 . ' "' . $level->name . '" ' . LANG_LEVEL_PRICE_2 . ' "' . $user_group_name . '"',
    	));
    	
    	$view = $this->load->view();
    	$view->assign('price_row', $price_row);
    	$view->assign('user_group_name', $user_group_name);
    	$view->assign('level_name', $level->name);
    	$view->assign('currencies', $this->config->item('currency_codes'));
    	$view->display('payment/listings/admin_payment_listings_settings.tpl');
    }
}
?>