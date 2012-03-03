<?php

class sitemapModule
{
	public $title = "SiteMap";
	public $version = "0.1";
	public $description = "SiteMap module";
	
	public $lang_files = "sitemap.php";
	
	public function routes()
	{
		$route['sitemap/'] = array(
			'title' => LANG_SITEMAP_TITLE,
		);
		
		$route['sitemap.xml'] = array(
			'action' => 'sitemap_xml',
		);

		return $route;
	}
}
?>