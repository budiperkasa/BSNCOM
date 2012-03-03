<?php
class defaultTheme
{
	public $title = "Default theme (do not remove)";
	public $version = "1";
	public $description = "";

	//public $lang_files = "default.php";

	/*public function routes()
	{
		$route[''] = array(
			'action' => 'index',
		);

		return $route;
	}*/

	
	
	public function hooks()
	{
		$hook['default_addJsFiles'] = array(
			'type' => 'when_active',
			'file' => 'when_active_hooks.php',
			'exclusions' => array(
				'install/:any',
				'locations/ajax_autocomplete_request/',
				'refresh_captcha/',
				'locations/get_locations_path_by_id/',
			),
		);
		
		return $hook;
	}
}
?>