<?php
include_once(MODULES_PATH . 'acl/classes/content_acl.class.php');
include_once(MODULES_PATH . 'sitemap/classes/google_sitemap.class.php');

class sitemapController extends controller
{
	/**
	 * Additional info pages required to be indexed
	 *
	 * @var array
	 */
	private	$info_pages = array(
				array('url' => 'advertise', 'title' => LANG_TOP_MENU_ADS),
    		);
	
    public function index()
    {
    	$this->load->model('types', 'types_levels');
    	$this->load->model('listings', 'listings');
    	$this->load->model('content_pages', 'content_pages');

		// --------------------------------------------------------------------------------------------
		// Select types and levels in hierarchical array
		// --------------------------------------------------------------------------------------------
		$types = $this->types->getTypesLevels();
    	// --------------------------------------------------------------------------------------------

    	// --------------------------------------------------------------------------------------------
    	// Select content of the  directory
    	// --------------------------------------------------------------------------------------------
    	$listings = array();
    	foreach ($types AS $type) {
    		$listings[$type->id] = $this->listings->selectListings(array('search_type' => $type, 'search_status' => 1, 'search_users_status' => 2), 'l.creation_date', 'desc', array(), array(), false);
    	}
    	//$listings = $this->sitemap->getListingsByTypes($types);
    	$content_pages = $this->content_pages->selectNodes();
    	//$content_pages = $this->sitemap->getContentPages();
    	// --------------------------------------------------------------------------------------------

		$view = $this->load->view();
		$view->assign('types', $types);
		$view->assign('listings', $listings);
		$view->assign('content_pages', $content_pages);
		$view->assign('info_pages', $this->info_pages);
    	$view->display('sitemap/sitemap_html.tpl');
    }
    
    public function sitemap_xml()
    {
    	$this->load->model('types', 'types_levels');
    	$this->load->model('sitemap');

		// --------------------------------------------------------------------------------------------
		// Select types and levels in hierarchical array
		// --------------------------------------------------------------------------------------------
		$types = $this->types->getTypesLevels();
    	// --------------------------------------------------------------------------------------------

    	// Select content of the directory
    	$listings = $this->sitemap->getListingsByTypes($types);
    	$content_pages = $this->sitemap->getContentPages();
    	
    	// SiteMap object
    	$site_map_container = new google_sitemap();

    	// Home page
    	$site_map_item = new google_sitemap_item(site_url(), "", 'daily');
    	$site_map_container->add_item($site_map_item);

    	// Types pages
    	foreach ($types AS $type) {
    		$site_map_item = new google_sitemap_item(site_url($type->getUrl()), "", 'weekly');
    		$site_map_container->add_item($site_map_item);
    	}
    	// Listings pages
    	foreach ($types AS $type) {
	    	foreach ($listings[$type->id] AS $listing) {
	    		$site_map_item = new google_sitemap_item(site_url("listings/" . $listing['url']), substr($listing['last_modified_date'], 0, 10), 'weekly');
	    		$site_map_container->add_item($site_map_item);
	    	}
    	}
    	// Content pages
    	foreach ($content_pages AS $page) {
    		$site_map_item = new google_sitemap_item(site_url("node/" . $page['url']), "", 'monthly');
    		$site_map_container->add_item($site_map_item);
    	}
    	// Info pages
    	foreach ($this->info_pages AS $page) {
    		$site_map_item = new google_sitemap_item(site_url($page['url']), "", 'monthly');
    		$site_map_container->add_item($site_map_item);
    	}

		header( "Content-type: application/xml; charset=\"". $this->config->item('charset') . "\"", true );
		header( 'Pragma: no-cache' );
	
		print $site_map_container->build();
    }
}
?>