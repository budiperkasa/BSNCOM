<?php
include_once(MODULES_PATH . 'facebook/classes/facebook.php');

function fcb_login($CI)
{
	$CI->load->model('authorization', 'authorization');
	if ($CI->authorization->checkAuthorization() === FALSE && !$CI->input->post('submit')) {
		$user_info = fcb_getUserInfo();

		if ($user_info && isset($user_info['email']) && $user_info['email']) {
			
			$CI->load->model('facebook', 'facebook');
			if (!$user_array = $CI->facebook->getFcbUser($user_info['uid'])) {
				$CI->facebook->insertFcbUser($user_info);
			} else {
				$CI->facebook->updateFcbUser($user_info);
			}
			$user = $CI->authorization->setAuthorization($CI->facebook->getFcbUser($user_info['uid']));

			//redirect(HOME_PAGE);
			header("Cache-Control: no-cache");
		}
	}
}

function fcb_getUserInfo($uid = null)
{
	$system_settings = registry::get('system_settings');

	$facebook = new Facebook(array(
		'appId'  => $system_settings['facebook_app_id'],
		'secret' => $system_settings['facebook_app_secret'],
		'cookie' => true
	));

	if (is_null($uid)) {
		$user = $facebook->getUser();
		if ($user)
			$uid = $facebook->getUser();
		else 
			return false;
	} else {
		$facebook->getUser();
	}

	$user_info = $facebook->api('/me?fields=id,name,picture,email');
	$user_info['uid'] = $uid;

	return $user_info;
}

function fcb_logout($CI)
{
	$system_settings = registry::get('system_settings');

	$facebook = new Facebook(array(
		'appId'  => $system_settings['facebook_app_id'],
		'secret' => $system_settings['facebook_app_secret'],
	));
	$user = $facebook->getUser();
	$user_dat = $facebook->getAccessToken();

	if ($user) {
		$logoutUrl = $facebook->getLogoutUrl(array('next' => site_url('logout')));
		$facebook->destroySession();
		redirect($logoutUrl);
	}
}

function fcb_login_btn($CI)
{
	$system_settings = registry::get('system_settings');
	if ($system_settings['facebook_app_id'] && $system_settings['facebook_app_secret']) {
		$facebook = new Facebook(array(
			'appId'  => $system_settings['facebook_app_id'],
			'secret' => $system_settings['facebook_app_secret'],
			'cookie' => true
		));
		$view = $CI->load->view();
		$view->assign('login_url', $facebook->getLoginUrl(array('scope'=> 'email', 'redirect_uri' => site_url('login'))));
		return $view->fetch('facebook/fcb_login_btn.tpl');
	}
}

function fcb_systemSettingsPage($CI)
{
	$system_settings = registry::get('system_settings');

	$view = $CI->load->view();
	$view->assign('system_settings', $system_settings);
	return $view->fetch('facebook/fcb_settings.tpl');
}
function fcb_handleSystemSettings($CI)
{
	if ($CI->input->post('submit')) {
		$CI->form_validation->set_rules('facebook_api_key', LANG_FACEBOOK_API_KEY, 'max_length[255]');
		$CI->form_validation->set_rules('facebook_app_id', LANG_FACEBOOK_APP_ID, 'max_length[255]');
		$CI->form_validation->set_rules('facebook_app_secret', LANG_FACEBOOK_APP_SECRET, 'max_length[255]');
	}
}
?>