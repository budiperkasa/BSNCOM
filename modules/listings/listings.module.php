<?php
class listingsModule
{
	public $title = "Listings";
	public $version = "0.1";
	public $description = "Manages listings and its connected objects - images, videos, files, ratings, reviews, statistics.";
	public $type = "core";
	public $permissions = array(
		'Manage all listings',
		'Manage self listings',
		'Create listings',
		'Edit listings expiration date',
		'View all statistics',
		'View self statistics',
		'Change listing level',
		'Manage ability to claim',
		'Claim on listings');

	public $lang_files = "listings.php";
	
	public function routes()
	{
		$route['admin/listings/search/(.*)'] = array(
			'title' => LANG_SEARCH_LISTINGS_TITLE,
			'action' => 'search',
			'access' => 'Manage all listings',
		);

		$route['admin/listings/my/(.*)'] = array(
			'title' => LANG_VIEW_MY_LISTINGS_TITLE,
			'action' => 'my',
			'access' => 'Manage self listings',
		);

		// advanced search block called from ajax
		$route['ajax/listings/build_advanced_search/'] = array(
			'action' => 'build_advanced_search',
		);
		// a route of separate advanced search page (usually for "light" theme)
		$route['advanced_search/:num'] = array(
			'action' => 'advanced_search',
		);
		
		// Create listing step 1
		$route['admin/listings/create/'] = array(
			'title' => LANG_CREATE_LISTING_TITLE,
			'action' => 'create',
			'access' => 'Create listings',
		);

		// Create listing step 2
		$route['admin/listings/create/level_id/:num'] = array(
			'title' => LANG_CREATE_LISTING_TITLE,
			'action' => 'create',
			'access' => 'Create listings',
		);
		$route['admin/listings/create/level_id/:num/user_package_id/:num'] = array(
			'title' => LANG_CREATE_LISTING_TITLE,
			'action' => 'create',
			'access' => 'Create listings',
		);
		
		$route['admin/listings/edit/:num/'] = array(
			'title' => LANG_EDIT_LISTING_TITLE,
			'action' => 'edit',
			'access' => array('Manage all listings', 'Manage self listings'),
		);
		
		$route['admin/listings/prolong/:num/'] = array(
			'action' => 'prolong',
			'access' => array('Manage all listings', 'Manage self listings'),
		);
		
		$route['admin/listings/delete/:num/'] = array(
			'title' => LANG_DELETE_LISTING_TITLE,
			'action' => 'delete',
			'access' => array('Manage all listings', 'Manage self listings'),
		);

		$route['admin/listings/delete/'] = array(
			'title' => LANG_DELETE_LISTINGS_TITLE,
			'action' => 'massDelete',
			'access' => array('Manage all listings', 'Manage self listings'),
		);
		
		$route['admin/listings/block/'] = array(
			'action' => 'block',
			'access' => 'Manage all listings',
		);
		
		$route['admin/listings/activate/'] = array(
			'action' => 'activate',
			'access' => 'Manage all listings',
		);
		
		$route['admin/listings/view/:num/'] = array(
			'title' => LANG_VIEW_LISTING_TITLE,
			'action' => 'view',
			'access' => array('Manage all listings', 'Manage self listings'),
		);
		
		$route['admin/listings/change_level/:num/'] = array(
			'title' => LANG_CHANGE_LISTING_LEVEL_TITLE,
			'action' => 'change_level',
			'access' => 'Change listing level',
		);
		
		$route['admin/listings/change_status/:num/'] = array(
			'title' => LANG_CHANGE_LISTING_STATUS_TITLE,
			'action' => 'change_status',
			'access' => array('Manage all listings', 'Manage self listings'),
		);

		$route['admin/listings/get_logo/:num'] = array(
			'action' => 'get_logo',
			'access' => array('Manage all listings', 'Manage self listings'),
		);
		
		// --------------------------------------------------------------------------------------------
		// Images
		// --------------------------------------------------------------------------------------------
		$route['admin/listings/images/:num/'] = array(
			'controller' => 'images',
			'title' => LANG_MANAGE_LISTING_IMAGES_TITLE,
			'action' => 'images',
			'access' => array('Manage all listings', 'Manage self listings'),
		);
		
		$route['admin/listings/images/delete/'] = array(
			'controller' => 'images',
			'title' => LANG_DELETE_IMAGES,
			'action' => 'massImagesDelete',
			'access' => array('Manage all listings', 'Manage self listings'),
		);
		
		$route['admin/listings/images/delete/:num'] = array(
			'controller' => 'images',
			'title' => LANG_DELETE_IMAGES,
			'action' => 'deleteImage',
			'access' => array('Manage all listings', 'Manage self listings'),
		);
		
		$route['admin/listings/images/edit/:num/:num/'] = array(
			'controller' => 'images',
			'title' => LANG_MANAGE_IMAGE_TITLE,
			'action' => 'imageEdit',
			'access' => array('Manage all listings', 'Manage self listings'),
		);
		
		$route['ajax/listings/:num/get_image/:num/'] = array(
			'controller' => 'images',
			'action' => 'get_image',
			'access' => array('Manage all listings', 'Manage self listings'),
		);
		
		// --------------------------------------------------------------------------------------------
		// Files
		// --------------------------------------------------------------------------------------------
		$route['admin/listings/files/:num/'] = array(
			'controller' => 'files',
			'title' => LANG_MANAGE_FILES_TITLE,
			'action' => 'files',
			'access' => array('Manage all listings', 'Manage self listings'),
		);
		
		$route['ajax/listings/:num/get_file/:num/'] = array(
			'controller' => 'files',
			'action' => 'get_file',
			'access' => array('Manage all listings', 'Manage self listings'),
		);
		
		$route['admin/listings/files/edit/:num/:num/'] = array(
			'controller' => 'files',
			'title' => LANG_EDIT_FILES_TITLE,
			'action' => 'file_edit',
			'access' => array('Manage all listings', 'Manage self listings'),
		);
		
		$route['admin/listings/files/delete/'] = array(
			'controller' => 'files',
			'title' => LANG_DELETE_FILES,
			'action' => 'massFilesDelete',
			'access' => array('Manage all listings', 'Manage self listings'),
		);
		
		$route['admin/listings/files/delete/:num'] = array(
			'controller' => 'files',
			'title' => LANG_DELETE_FILES,
			'action' => 'deleteFile',
			'access' => array('Manage all listings', 'Manage self listings'),
		);

		// --------------------------------------------------------------------------------------------
		// Videos
		// --------------------------------------------------------------------------------------------
		$route['admin/listings/videos/:num/'] = array(
			'controller' => 'videos',
			'title' => LANG_MANAGE_VIDEOS_TITLE,
			'action' => 'videos',
			'access' => array('Manage all listings', 'Manage self listings'),
		);
		
		$route['admin/listings/videos/attach/:num/'] = array(
			'controller' => 'videos',
			'title' => LANG_ATTACH_VIDEO_TITLE,
			'action' => 'videos_attach',
			'access' => array('Manage all listings', 'Manage self listings'),
		);
		
		$route['admin/listings/videos/upload/:num/'] = array(
			'controller' => 'videos',
			'title' => LANG_UPLOAD_VIDEO_TITLE,
			'action' => 'videos_upload',
			'access' => array('Manage all listings', 'Manage self listings'),
		);
		
		$route['admin/listings/videos/edit/:num/:num/'] = array(
			'controller' => 'videos',
			'title' => LANG_EDIT_VIDEO_TITLE,
			'action' => 'videos_edit',
			'access' => array('Manage all listings', 'Manage self listings'),
		);
		
		$route['admin/listings/videos/delete/'] = array(
			'controller' => 'videos',
			'title' => LANG_DELETE_FILES,
			'action' => 'massVideosDelete',
			'access' => array('Manage all listings', 'Manage self listings'),
		);
		
		$route['admin/listings/videos/delete/:num'] = array(
			'controller' => 'videos',
			'title' => LANG_DELETE_FILES,
			'action' => 'deleteVideo',
			'access' => array('Manage all listings', 'Manage self listings'),
		);
		
		// controller for youtube file upload response
		// Example: admin/listings/3/get_video/3/?status=200&id=znoaQCISmok
		$route['admin/listings/:num/get_video/:num/(.*)'] = array(
			'controller' => 'videos',
			'action' => 'get_video',
			'access' => array('Manage all listings', 'Manage self listings'),
		);
		
		// --------------------------------------------------------------------------------------------
		// Statistics
		// --------------------------------------------------------------------------------------------
		$route['admin/listings/statistics/:num/(.*)'] = array(
			'controller' => 'statistics',
			'title' => LANG_LISTINGS_STATISTICS_TITLE,
			'action' => 'statistics',
			'access' => array('View all statistics', 'View self statistics'),
		);

		// --------------------------------------------------------------------------------------------
		// Ratings and review module
		// --------------------------------------------------------------------------------------------
		$route['admin/ratings/listings/:num'] = array(
			'controller' => 'ratings_reviews',
			'title' => LANG_ADMIN_RATINGS_TITLE,
			'action' => 'admin_ratings',
			'access' => array('Manage all ratings', 'Manage self ratings'),
		);

		$route['admin/reviews/listings/:num'] = array(
			'controller' => 'ratings_reviews',
			'title' => LANG_ADMIN_REVIEWS_TITLE,
			'action' => 'admin_reviews',
			'access' => array('Manage all reviews', 'Manage self reviews'),
		);

		// --------------------------------------------------------------------------------------------
		// Claim routes
		// --------------------------------------------------------------------------------------------
		$route['listings/claim/:any'] = array(
			'action' => 'set_claim',
			'access' => 'Claim on listings',
		);
		
		$route['admin/listings/approve_claim/:num'] = array(
			'action' => 'approve_claim',
			'access' => 'Manage ability to claim',
		);
		
		$route['admin/listings/decline_claim/:num'] = array(
			'action' => 'decline_claim',
			'access' => 'Manage ability to claim',
		);

		return $route;
	}

	public function menu()
	{
		$menu[LANG_CREATE_LISTING_MENU] = array(
			'weight' => 3,
			'url' => 'admin/listings/create/',
			'access' => 'Create listings',
			'sinonims' => array(array('admin', 'listings', 'create', 'level_id', '%')),
			'icon' => 'new_listing',
		);
		
		$menu[LANG_VIEW_MY_LISTING_MENU] = array(
			'weight' => 4,
			'url' => 'admin/listings/my/',
			'access' => 'Manage self listings',
			'sinonims' => array(
				array('admin', 'listings', 'my', '%+'),
				array('admin', 'listings', 'change_status', '%+'), 
			),
			'icon' => 'listings',
		);
		
		$menu[LANG_LISTINGS_MENU] = array(
			'weight' => 51,
			'children' => array(
				LANG_SEARCH_LISTINGS_MENU => array(
					'weight' => 2,
					'url' => 'admin/listings/search/',
					'access' => 'Manage all listings',
					'sinonims' => array(
						array('admin', 'listings', 'search', '%+'),
						array('admin', 'listings', 'images', '%+'), 
						array('admin', 'listings', 'image_details', '%+'), 
						array('admin', 'listings', 'view', '%+'), 
						array('admin', 'listings', 'edit', '%+'), 
						array('admin', 'listings', 'change_level', '%+'), 
						array('admin', 'listings', 'change_status', '%+'), 
						array('admin', 'listings', 'delete', '%+'), 
						array('admin', 'listings', 'videos', '%+'), 
						array('admin', 'listings', 'files', '%+'), 
						array('admin', 'listings', 'statistics', '%+'),
						array('admin', 'ratings', 'listings', '%+'),
						array('admin', 'reviews', 'listings', '%+'),
					),
				),
				LANG_SEARCH_REVIEWS_MENU => array(
					'weight' => 3,
					'url' => 'admin/reviews/listings/search/',
					'access' => 'Manage all reviews',
					'sinonims' => array(
						array('admin', 'reviews', 'listings', 'search', '%+'),
					),
				),
			),
		);

		return $menu;
	}
	
	public function hooks()
	{
		$hook['buildMyListingsMenuItem'] = array(
			'weight' => 2,
		);
		
		$hook['listings_runAutoBlocker'] = array(
			'events' => array('Auto blocker run')
		);
		
		$hook['sendReviewNotification'] = array(
			'events' => array('Review creation')
		);

		return $hook;
	}
}
?>