<?php

class installController extends Controller
{
    public function step1()
    {
    	// Prevent reinstallation
    	$system_settings = registry::get('system_settings');
    	if (isset($system_settings['completed_installation']) && $system_settings['completed_installation']) {
    		redirect('');
    	}
    	
    	$this->load->model('install');
    	if ($this->input->post('submit')) {
    		$this->form_validation->set_rules('user_login', LANG_INSTALL_USER_LOGIN, 'required');
			$this->form_validation->set_rules('user_email', LANG_INSTALL_USER_EMAIL, 'required|valid_email');
			$this->form_validation->set_rules('user_password', LANG_INSTALL_USER_PASSWORD, 'required|matches[user_password_repeat]');

			if ($this->form_validation->run() !== FALSE) {
				if ($this->install->createRootUser($this->form_validation->result_array())) {
					redirect('install/step2/');
				}
			}
    	}

	    $view = $this->load->view();
	    $view->assign('visible_login_block', true);
		$view->display('install/step1.tpl');
    }
    
    public function step2()
    {
    	// Prevent reinstallation
    	$system_settings = registry::get('system_settings');
    	if (isset($system_settings['completed_installation']) && $system_settings['completed_installation']) {
    		redirect('');
    	}

    	$this->load->model('install');
    	if ($this->input->post('submit')) {
    		$this->form_validation->set_rules('website_title', LANG_INSTALL_WEBSITE_TITLE, 'required');
			$this->form_validation->set_rules('website_email', LANG_INSTALL_WEBSITE_EMAIL, 'required|valid_email');

			if ($this->form_validation->run() !== FALSE) {
				if ($this->install->setSettings($this->form_validation->result_array())) {
					redirect('install/step3/');
				}
			}
    	}

	    $view = $this->load->view();
	    $view->assign('visible_login_block', true);
		$view->display('install/step2.tpl');
    }
    
     public function step3()
    {
    	// Prevent reinstallation
    	$system_settings = registry::get('system_settings');
    	if (isset($system_settings['completed_installation']) && $system_settings['completed_installation']) {
    		redirect('');
    	}

    	$this->load->model('install');
    	if ($this->input->post('submit')) {
    		if ($this->install->completeInstallation())
    			redirect('login/');
    	}

	    $view = $this->load->view();
	    $view->assign('visible_login_block', true);
		$view->display('install/step3.tpl');
    }
}
?>