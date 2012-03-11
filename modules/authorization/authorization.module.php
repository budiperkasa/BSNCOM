<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('HOME_PAGE', 'admin/');

class authorizationModule
{
	public $title = "Authorization";
	public $version = "0.1";
	public $description = "Module provides login authorization, login and password reminder pages";
	public $type = "core";

	public $lang_files = "login.php";
	
	public function routes()
	{
		$route['login/(.*)'] = array(
			'title' => LANG_AUTHORIZATION_MENU,
		);

		$route['logout/'] = array(
			'action' => 'logout',
		);

		return $route;
	}
}
?>