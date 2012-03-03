<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class authorizationController extends Controller
{
	public function index()
	{
		$this->load->model('authorization');

		if ($this->authorization->checkAuthorization() === FALSE) {
			if ($this->input->post('submit')) {
				$this->form_validation->set_rules('email', LANG_LOGIN_EMAIL, 'required|valid_email');
				$this->form_validation->set_rules('password', LANG_LOGIN_PASSWORD, 'required');
				$this->form_validation->set_rules('remember_me', LANG_REMEMBER_ME, 'is_checked');
	
				if ($this->form_validation->run() !== FALSE) {
					// Check if user and password math existed user in DB
					// Also select user row from DB
					$user_array = $this->authorization->checkLogin($this->form_validation->set_value('email'), $this->form_validation->set_value('password'));
					if (!empty($user_array)) {
						// Check if existed user has active status
						if ($user_array['status'] != 3) {
							$user = $this->authorization->setAuthorization($user_array, $this->form_validation->set_value('remember_me'));

							//header("Cache-Control: no-cache");
							redirect(HOME_PAGE);
						} else {
							// User blocked
							$this->setError(LANG_USER_BLOCKED);
						}
					} else {
						$this->setError(LANG_USER_LOGIN_ERROR);
					}
				}
	        }
	
			$view = $this->load->view();
			$view->assign('visible_login_block', true);
			$view->display('authorization/admin_login.tpl');
		} else {
			// do not edit this line
			redirect(HOME_PAGE);
		}
	}

	public function logout()
	{
		$this->load->model('authorization');

		if ($this->authorization->checkAuthorization() !== FALSE) {
			$this->authorization->unsetAuthorization();
		}
		redirect('login');
	}
}
?>