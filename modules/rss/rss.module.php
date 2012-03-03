<?php
define('RSS_LISTINGS_COUNT', 20);

class rssModule
{
	public $title = "RSS";
	public $version = "0.1";
	public $description = "RSS Webcast Channel module";
	
	public $lang_files = "rss.php";
	
	public function routes()
	{
		$route['rss/:any/(.*)'] = array();

		return $route;
	}
}
?>