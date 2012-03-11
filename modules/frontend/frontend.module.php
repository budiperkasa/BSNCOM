<?php
class frontendModule
{
	public $title = "Frontend";
	public $version = "0.1";
	public $description = "Frontend part of the system.";
	public $type = "core";

	public $lang_files = array("frontend.php", "contactus.php");
	
	public function routes()
	{
		// --------------------------------------------------------------------------------------------
		$route['()'] = array(
			//'title' => LANG_FRONTEND_HOME_TITLE,
		);
		// Is there any predefined location?
		$route['location/:any/'] = array(
			//'title' => LANG_FRONTEND_HOME_TITLE,
		);
		// --------------------------------------------------------------------------------------------

		$route['listings/:any/'] = array(
			'action' => 'listings',
		);

		$route['download/:num/'] = array(
			'action' => 'download',
		);
		
		$route['print_listing/:any/'] = array(
			'action' => 'print_listing',
		);
		
		
		$route['users/:any/'] = array(
			'action' => 'users',
		);
		
		$route['print_user/:any/'] = array(
			'action' => 'print_user',
		);
		
		// --------------------------------------------------------------------------------------------
		$route['()types/:any/(.*)'] = array(
			'action' => 'types',
		);
		// Is there any predefined location?
		$route['location/:any/types/:any/(.*)'] = array(
			'action' => 'types',
		);
		// --------------------------------------------------------------------------------------------
		
		// --------------------------------------------------------------------------------------------
		$route['()()categories/(.*)'] = array(
			'action' => 'categories',
		);
		// Is there any predefined location?
		$route['location/:any/()categories/(.*)'] = array(
			'action' => 'categories',
		);
		$route['()type/:any/categories/(.*)'] = array(
			'action' => 'categories',
		);
		// Is there any predefined location?
		$route['location/:any/type/:any/categories/(.*)'] = array(
			'action' => 'categories',
		);
		// --------------------------------------------------------------------------------------------

		// --------------------------------------------------------------------------------------------
		$route['location/:any/search/(.*)'] = array(
			'title' => LANG_FRONTEND_SEARCH_TITLE,
			'action' => 'search',
		);
		// Is there any predefined location?
		$route['()search/(.*)'] = array(
			'title' => LANG_FRONTEND_SEARCH_TITLE,
			'action' => 'search',
		);
		// --------------------------------------------------------------------------------------------
		
		/**
	     * Content pages action
	     *
	     */
		$route['node/:any/'] = array(
			'action' => 'node',
		);
		
		$route['quick_list/(.*)'] = array(
			'title' => LANG_QUICK_LIST_TITLE,
			'action' => 'quick_list',
		);
		
		$route['advertise/'] = array(
			'title' => LANG_ADVERTISE_TITLE,
			'action' => 'advertise',
		);
		
		// --------------------------------------------------------------------------------------------
		// Registration routes
		// --------------------------------------------------------------------------------------------
		$route['register/activate/:any'] = array(
			'controller' => 'registration',
			'action' => 'activate',
		);
		
		$route['register/(.*)'] = array(
			'title' => LANG_CREATE_ACCOUNT_TITLE,
			'controller' => 'registration',
			'action' => 'register',
		);

		$route['pass_recovery_step1/'] = array(
			'title' => LANG_PASSWORD_RECOVERY1_TITLE,
			'controller' => 'registration',
			'action' => 'pass_recovery_step1',
		);
		
		$route['pass_recovery_step2/:any'] = array(
			'title' => LANG_PASSWORD_RECOVERY2_TITLE,
			'controller' => 'registration',
			'action' => 'pass_recovery_step2',
		);
		
		// --------------------------------------------------------------------------------------------
		// 'Contact us' page
		// --------------------------------------------------------------------------------------------
		$route['contactus/'] = array(
			'controller' => 'contactus',
			'title' => LANG_CONTACT_US_TITLE,
		);
		// --------------------------------------------------------------------------------------------

		return $route;
	}
	
	public function hooks()
	{
		$hook['includeJQueryAndStyles'] = array(
			'weight' => 1.2,
			'exclusions' => array(
				'login/(.*)',
				'admin/:any',
				'install/:any',
				'locations/ajax_autocomplete_request/',
				'refresh_captcha/',
				'locations/get_locations_path_by_id/',
			),
		);
		
		$hook['getCurrentType'] = array(
			'weight' => 0,
			'inclusions' => array(
				'()types/:any/(.*)',
				'location/:any/types/:any/(.*)'
			),
		);
		
		$hook['getCurrentCategory'] = array(
			'weight' => 0,
			'inclusions' => array(
				'()()categories/(.*)',
				'location/:any/()categories/(.*)',
				'()type/:any/categories/(.*)',
				'location/:any/type/:any/categories/(.*)'
			),
		);
		
		$hook['getCurrentListing'] = array(
			'weight' => 0,
			'inclusions' => array(
				'listings/:any/',
				'print_listing/:any/'
			),
		);
		
		$hook['getCurrentUser'] = array(
			'weight' => 0,
			'inclusions' => array(
				'users/:any/',
				'print_user/:any/'
			),
		);
		
		$hook['getCurrentSearchParams'] = array(
			'weight' => 0,
			'inclusions' => array(
				'location/:any/search/(.*)',
				'()search/(.*)'
			),
		);
		
		/*$hook['setMobileTheme'] = array(
			'weight' => 3,
		);*/

		return $hook;
	}
}
?>