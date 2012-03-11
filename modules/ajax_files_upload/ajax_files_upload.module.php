<?php
class ajax_files_uploadModule
{
	public $title = "AJAX Files Upload";
	public $version = "0.1";
	public $description = "Module uploads and handles files of various types.";
	public $type = "core";

	public $lang_files = "upload.php";
	
	public function routes()
	{
		$route['ajax/files_upload/:any'] = array(
		);

		return $route;
	}
}
?>