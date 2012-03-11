<?php
class backendModule
{
	public $title = "Admin backend panel";
	public $version = "0.2";
	public $description = "Users with 'admin backend' permissions redirects to this page after success login.";
	public $type = "core";
	//public $permissions = 'Admin backend';
	
	public $lang_files = array("backend.php", "common.php");
	
	public function routes()
	{
		$route['admin/'] = array(
			'title' => LANG_HOME_MENU,
		);
		
		// Captcha refreshing route
		$route['refresh_captcha/'] = array(
			'action' => 'refresh_captcha',
		);
		
		/**
		 * Updater
		 * 
		 * @param updater string
		 */
		$route['update/:any'] = array(
			'action' => 'update',
		);
		$route['update_langs/:any'] = array(
			'action' => 'update_langs',
		);

		return $route;
	}
	
	public function menu()
	{
		$menu[LANG_HOME_MENU] = array(
			'weight' => 1,
			'url' => 'admin/',
			'icon' => 'home',
		);

		$menu[LANG_LOGOUT_MENU] = array(
			'weight' => 49,
			'url' => 'logout/',
			'icon' => 'logout',
		);

		return $menu;
	}
	
	public function hooks()
	{
		$hook['includeJQuery'] = array(
			'weight' => 1.1,
			'inclusions' => array(
				'login/(.*)',
				'admin/:any',
				'install/:any',
			),
			'exclusions' => array(
				'ajax/(.*)'
			),
		);
		
		$hook['includeJsCssFiles'] = array(
			'weight' => 1.2,
			'inclusions' => array(
				'login/(.*)',
				'admin/:any',
				'install/:any',
			),
			'exclusions' => array(
				'ajax/(.*)'
			),
		);

		$hook['buildMessagesBlock'] = array(
			'viewTrigger' => array(array('pre', '.content'), array('pre', '.email_form'), array('replace', '#messages')),
		);

		return $hook;
	}
}
?>