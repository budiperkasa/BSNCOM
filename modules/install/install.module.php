<?php
class installModule
{
	public $title = "Installation module";
	public $version = "0.1";
	public $description = "";
	public $type = "core";
	
	public $lang_files = "install.php";
	
	public function routes()
	{
		$route['install/'] = array(
			'title' => LANG_INSTALL_TITLE,
			'action' => 'step1'
		);
		
		$route['install/step2/'] = array(
			'title' => LANG_INSTALL_TITLE,
			'action' => 'step2'
		);
		
		$route['install/step3/'] = array(
			'title' => LANG_INSTALL_TITLE,
			'action' => 'step3'
		);

		return $route;
	}
}
?>