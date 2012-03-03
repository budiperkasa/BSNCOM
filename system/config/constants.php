<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ', 							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 					'ab');
define('FOPEN_READ_WRITE_CREATE', 				'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 			'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');





//////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////
/*
|--------------------------------------------------------------------------
| During creation of new content fields groups - these prefixes need
|--------------------------------------------------------------------------
|
|
*/
define('USERS_PROFILE_GROUP_CUSTOM_NAME', 'users_profile');
define('LISTINGS_LEVEL_GROUP_CUSTOM_NAME', 'listing_level');
define('CONTENT_PAGES_GROUP_CUSTOM_NAME', 'content_pages');
define('CONTACT_US_PAGE_GROUP_CUSTOM_NAME', 'contact_us_page');

define('GLOBAL_SEARCH_GROUP_CUSTOM_NAME', 'global_search');
define('LOCAL_SEARCH_GROUP_CUSTOM_NAME', 'local_search');

/*
|--------------------------------------------------------------------------
| Miscellaneous constants
|--------------------------------------------------------------------------
|
|
*/
define('SITE_LOGO_WIDTH', 300);
define('SITE_LOGO_HEIGHT', 65);


define('DEFAULT_LANGUAGE_CODE', 'en');
define('LISTING_TITLE_LENGTH', 55);
define('REVIEW_MAX_LENGTH', 500);

// if null - cache will be active forever
// 604800 - it is one week
define('CACHE_LIFETIME', 604800);

// Google maps coordinates to kilometers/miles miltipliers
define('COORDS_MILES_MULTIPLIER', 3959);
define('COORDS_KILOMETERS_MULTIPLIER', 6371);

// Languages folders set by default 
define('DEFAULT_LANGS', 'en|de|es|fr|it|ru|pt-PT|tr');

/*
|--------------------------------------------------------------------------
| Frontend view constants
|--------------------------------------------------------------------------
|
|
*/
define('LISTINGS_PER_PAGE_ON_QUICKLIST_PAGE', 10);


/* End of file constants.php */
/* Location: ./system/application/config/constants.php */