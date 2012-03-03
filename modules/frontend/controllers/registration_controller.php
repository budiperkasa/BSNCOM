<?php
include_once(MODULES_PATH . 'notifications/classes/notification_sender.class.php');
include_once(MODULES_PATH . 'content_fields/classes/field.class.php');
include_once(MODULES_PATH . 'content_fields/classes/content_fields.class.php');
include_once(MODULES_PATH . 'content_fields/classes/search_content_fields.class.php');

class registrationController extends Controller
{
	/**
	 * validation function
	 *
	 * @param string $captcha
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

	/**
	 * validation function, during registration checks the unique of the user email
	 *
	 * @param string $email
	 * @return bool
	 */
	public function check_unique_email($email)
	{
		$this->load->model('users', 'users');

		if ($this->users->is_email($email)) {
			$this->form_validation->set_message('email');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	/**
	 * validation function, checks the unique of the user login name
	 *
	 * @param string $login
	 * @return bool
	 */
	public function check_unique_login($login)
	{
		$this->load->model('users', 'users');

		if ($this->users->is_login($login)) {
			$this->form_validation->set_message('login');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	public function register($argsString = '')
	{
		$args = parseUrlArgs($argsString);
		$registation_url = site_url('register/');
		
		$this->load->model('types', 'types_levels');
		$this->load->model('users_groups', 'users');
		$view = $this->load->view();
		
		$system_settings = registry::get('system_settings');

		// --------------------------------------------------------------------------------------------
		// Select types and levels in hierarchical array
		// --------------------------------------------------------------------------------------------
		$types = $this->types->getTypesLevels();
		$view->assign('types', $types);
		// --------------------------------------------------------------------------------------------

		if (!isset($args['group_id'])) {
			$registration_user_group = $this->users_groups->getDefaultUsersGroup();
		} else {
			$registration_user_group = $this->users_groups->getUsersGroupById($args['group_id']);
		}
		$all_users_groups = $this->users_groups->getUsersGroups();
		
		// Check if registration in this group was allowed
		$users_groups_allowed = array();
		$groups_ids = array();
		foreach ($all_users_groups AS $group_item) {
			if ($group_item->may_register) {
				$groups_ids[$group_item->id] = $group_item->id;
				$users_groups_allowed[$group_item->id] = $group_item;
			}
		}
		ksort($users_groups_allowed);
		if (!in_array($registration_user_group->id, $groups_ids)) {
			$this->setError(LANG_REGISTRATION_ACCOUNT_TYPE_ERROR);
			redirect($registation_url);
		}

		$user = new user($registration_user_group->id);

		if ($this->input->post('submit')) {
			$this->form_validation->set_rules('captcha', LANG_CAPTCHA, 'callback_check_captcha');
			$this->form_validation->set_rules('login', LANG_LOGIN, 'required|min_length[4]|max_length[60]|callback_check_unique_login');
			$this->form_validation->set_rules('email', LANG_EMAIL, 'required|valid_email|callback_check_unique_email');
			$this->form_validation->set_rules('password', LANG_PASSWORD, 'required|max_length[20]|min_length[5]|matches[repeat_password]');
			$this->form_validation->set_rules('repeat_password', LANG_PASSWORD_REPEAT, 'required');
			if ($system_settings['path_to_terms_and_conditions'])
				$this->form_validation->set_rules('terms_agreement', LANG_TERMS_CONDITIONS_AGREEMENT, 'required');

			if ($this->form_validation->run() !== FALSE) {
				$this->load->model('users', 'users');
				$hash = md5(time());
				$activation_link = site_url('register/activate/' . $hash . '/');
				if ($user_id = $this->users->createUser($registration_user_group->id, $user, $hash, $this->form_validation->result_array())) {
					$form =  $this->form_validation->result_array();

					$event_params = array(
						'RECIPIENT_NAME' => $form['login'],
						'RECIPIENT_EMAIL' => $form['email'],
						'ACTIVATION_LINK' => $activation_link,
						'USER_ID' => $user_id
					);
					$notification = new notificationSender('Account creation step 1');
					$notification->send($event_params);
					events::callEvent('Account creation step 1', $event_params);

					$this->setSuccess(LANG_ACCOUNT_CREATE_SUCCESS_1 . ' "' . $form['login'] . '" ' . LANG_ACCOUNT_CREATE_SUCCESS_2);
					redirect('login');
				}
			} else {
				$user->setUserFromArray($this->form_validation->result_array());
			}
			$this->session->unset_userdata('captcha_word');
		}
		
		// --------------------------------------------------------------------------------------------
		// Search block settings
		// --------------------------------------------------------------------------------------------
		$base_url = site_url('search/');
		$search_fields = new searchContentFields(GLOBAL_SEARCH_GROUP_CUSTOM_NAME, 0);
		$advanced_search_fields = new searchContentFields(GLOBAL_SEARCH_GROUP_CUSTOM_NAME, 0, 'advanced');

		$view->assign('base_url', $base_url);
		$view->assign('search_fields', $search_fields);
		$view->assign('advanced_search_fields', $advanced_search_fields);
		// --------------------------------------------------------------------------------------------

		$this->load->plugin('captcha');
		$captcha = create_captcha($this);

		$view->assign('user', $user);
		$view->assign('users_groups_allowed', $users_groups_allowed);
		$view->assign('registation_url', $registation_url);
		$view->assign('registration_user_group', $registration_user_group);
		$view->assign('captcha', $captcha);
		$view->display('frontend/register.tpl');
	}

	public function activate($hash)
	{
		$this->load->model('users', 'users');
		$this->load->model('authorization', 'authorization');

		if ($user = $this->users->activateUser($hash)) {
			$this->authorization->setAuthorization($user);
			$event_params = array(
				'RECIPIENT_NAME' => $user['login'],
				'RECIPIENT_EMAIL' => $user['email'],
				'USER' => $user
			);
			$notification = new notificationSender('Account creation step 2');
			$notification->send($event_params);
			events::callEvent('Account creation step 2', $event_params);

			$this->setSuccess(LANG_ACTIVATION_SUCCESS);
			redirect(HOME_PAGE);
		} else {
			$this->setError(LANG_ACTIVATION_ERROR);
			redirect('login');
		}
	}

	/**
	 * validation function, during password recovery checks if entered user email is existed
	 *
	 * @param string $email
	 * @return bool
	 */
	public function check_is_email($email)
	{
		$this->load->model('users', 'users');

		if (!$this->users->is_email($email)) {
			$this->form_validation->set_message('email');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	public function pass_recovery_step1()
	{
		$this->load->model('types', 'types_levels');
		$view = $this->load->view();

		// --------------------------------------------------------------------------------------------
		// Select types and levels in hierarchical array
		// --------------------------------------------------------------------------------------------
		$types = $this->types->getTypesLevels();
		$view->assign('types', $types);
		// --------------------------------------------------------------------------------------------

		if ($this->input->post('submit')) {
			$this->form_validation->set_rules('captcha', LANG_CAPTCHA, 'callback_check_captcha');
			$this->form_validation->set_rules('email', LANG_USER_EMAIL, 'required|valid_email|callback_check_is_email');

			if ($this->form_validation->run() !== FALSE) {
				$hash = md5(time());
				$activation_link = site_url('pass_recovery_step2/' . $hash . '/');
				$this->load->model('users', 'users');
				// set new hash for user
				if ($user_array = $this->users->saveUserHash($hash, $this->form_validation->set_value('email'))) {
					$event_params = array(
						'RECIPIENT_NAME' => $user_array['login'],
						'RECIPIENT_EMAIL' => $user_array['email'],
						'ACTIVATION_LINK' => $activation_link,
						'USER_ARRAY' => $user_array
					);
					$notification = new notificationSender('Password recovery');
					$notification->send($event_params);
					events::callEvent('Password recovery', $event_params);

					$this->setSuccess(LANG_EMAIL_SEND_SUCCESS);
					redirect('login/');
				}
			} else {
				$view->assign('email', $this->form_validation->set_value('email'));
			}
		}
		
		// --------------------------------------------------------------------------------------------
		// Search block settings
		// --------------------------------------------------------------------------------------------
		$base_url = site_url('search/');
		$search_fields = new searchContentFields(GLOBAL_SEARCH_GROUP_CUSTOM_NAME, 0);
		$advanced_search_fields = new searchContentFields(GLOBAL_SEARCH_GROUP_CUSTOM_NAME, 0, 'advanced');

		$view->assign('base_url', $base_url);
		$view->assign('search_fields', $search_fields);
		$view->assign('advanced_search_fields', $advanced_search_fields);
		// --------------------------------------------------------------------------------------------

		$this->load->plugin('captcha');
		$captcha = create_captcha($this);

		$view->assign('captcha', $captcha);
		$view->display('frontend/pass_recovery_step1.tpl');
	}

	public function pass_recovery_step2($hash)
	{
		$this->load->model('types', 'types_levels');
		$this->load->model('users', 'users');
		$view = $this->load->view();

		// --------------------------------------------------------------------------------------------
		// Select types and levels in hierarchical array
		// --------------------------------------------------------------------------------------------
		$types = $this->types->getTypesLevels();
		$view->assign('types', $types);
		// --------------------------------------------------------------------------------------------
		
		if (!$user = $this->users->isHash($hash)) {
			exit(0);
		}

		if ($this->input->post('submit')) {
			$this->form_validation->set_rules('password', LANG_PASSWORD, 'max_length[20]|min_length[5]|matches[repeat_password]');
			$this->form_validation->set_rules('repeat_password', LANG_PASSWORD_REPEAT);

			if ($this->form_validation->run() !== FALSE) {
				if ($this->users->saveNewPassword($hash, $this->form_validation->set_value('password'))) {
					$this->setSuccess(LANG_PASS_RECOVERY_SUCCESS);
					redirect('login/');
				}
			}
		}
		
		// --------------------------------------------------------------------------------------------
		// Search block settings
		// --------------------------------------------------------------------------------------------
		$base_url = site_url('search/');
		$search_fields = new searchContentFields(GLOBAL_SEARCH_GROUP_CUSTOM_NAME, 0);
		$advanced_search_fields = new searchContentFields(GLOBAL_SEARCH_GROUP_CUSTOM_NAME, 0, 'advanced');

		$view->assign('base_url', $base_url);
		$view->assign('search_fields', $search_fields);
		$view->assign('advanced_search_fields', $advanced_search_fields);
		// --------------------------------------------------------------------------------------------

		$view->display('frontend/pass_recovery_step2.tpl');
	}
}
?>