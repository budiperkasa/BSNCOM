<?php

class payment_packagesController extends controller
{
    public function prices()
    {
		$this->load->model('packages', 'packages');
		$packages = $this->packages->getAllPackages();

    	$this->load->model('users_groups', 'users');
    	$users_groups = $this->users_groups->getUsersGroups();

    	$this->load->model('payment_packages');
    	$prices = $this->payment_packages->getPackagesPrice($packages, $users_groups);

        $view  = $this->load->view();
        $view->assign('packages', $packages);
        $view->assign('users_groups', $users_groups);
        $view->assign('prices', $prices);
        $view->display('payment/packages/admin_payment_packages_manage.tpl');
    }
    
    public function payment_settings($group_id, $package_id)
    {
    	$this->load->model('payment_packages');

    	if ($this->input->post('submit')) {
    		$this->form_validation->set_rules('currency', LANG_LISTINGS_PRICE_CURRENCY, 'required');
    		$this->form_validation->set_rules('value', LANG_LISTINGS_PRICE, 'numeric|required');
    		
    		if ($this->form_validation->run() !== FALSE) {
    			if ($this->payment_packages->savePrice($group_id, $package_id, $this->form_validation->result_array())) {
    				$this->setSuccess(LANG_PRICE_SAVED_SUCCESS);
    				redirect('admin/packages/payment/');
    			}
    		} else {
    			$price_row = $this->payment_packages->getPriceSettingsFromForm($this->form_validation->result_array());
    		}
    	} else {
    		$price_row = $this->payment_packages->getPackagesPriceByPackageIdAndGroupId($package_id, $group_id);
    	}

    	$this->load->model('users_groups', 'users');
    	$this->users_groups->setUsersGroupId($group_id);
    	$user_group = $this->users_groups->getUsersGroupById();
    	$user_group_name = $user_group->name;

    	$this->load->model('packages', 'packages');
    	$package = $this->packages->getPackageById($package_id);
    	
    	registry::set('breadcrumbs', array(
    		'admin/packages/payment/' => LANG_VIEW_PAYMENT_SETTINGS_TITLE,
    		LANG_PACKAGES_PRICE_OPTION_1 . ' "' . $package->name . '" ' . LANG_PACKAGES_PRICE_OPTION_2 . ' "' . $user_group_name . '"',
    	));
    	
    	$view = $this->load->view();
    	$view->assign('price_row', $price_row);
    	$view->assign('user_group_name', $user_group_name);
    	$view->assign('package_name', $package->name);
    	$view->assign('currencies', $this->config->item('currency_codes'));
    	$view->display('payment/packages/admin_payment_packages_settings.tpl');
    }
}
?>