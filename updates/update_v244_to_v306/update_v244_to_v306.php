<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

set_time_limit(0);

$rename_permissions = array(
'Manage banners'               =>'Manage all banners',
'View all listings'            =>'Manage all listings',
'Banners blocks settings'      =>'Manage banners blocks',
'View self listings'           =>'Manage self listings',
'Create new listings'          =>'Create listings',
'Edit ratings'                 =>'Manage all ratings',
'Edit reviews'                 =>'Manage all reviews',
'Edit locations'               =>'Manage predefined locations',
'Payment settings edit'        =>'Manage payment settings',
'System settings edit'         =>'Edit system settings',
'Web services settings edit'   =>'Edit web services settings',
'View types'                   =>'Manage types and levels',
'View my profile'              =>'Edit self profile',
);

$rename_events = array(
'onListingCreation'             =>'Listing creation',
'onListingCreation_na'          =>'Listing creation Admin notification',
'onAccountCreation_na'          =>'Account creation Admin notification',
'onUserBlocking'                =>'User blocking',
'onListingExpiration'           =>'Listing expiration',
'onListingProlong'              =>'Listing prolonging',
'onListingBlocking'             =>'Listing blocking',
'onListingApproval'             =>'Listing approval',
'onInvoiceCreation'             =>'Invoice creation',
'onAccountCreationStep1'        =>'Account creation step 1',
'onPasswordRecovery'            =>'Password recovery',
'onAccountCreationStep2'        =>'Account creation step 2',
'onBannerBlocking'              =>'Banner blocking',
'onBannerProlong'               =>'Banner prolonging',
'onBannerExpiration'            =>'Banner expiration',
'onBannerCreation'              =>'Banner creation',
'onReviewCreationForListing'    =>'Review creation for listing',
'onFacebookAccountCreation'     =>'Facebook account creation',
);

$CI = &get_instance();

// --------------------------------------------------------------------------------------------
// Rename permissions and events
// --------------------------------------------------------------------------------------------
foreach ($rename_permissions AS $renamefrom=>$renameto) {
	$CI->db->set('function_access', $renameto);
	$CI->db->where('function_access', $renamefrom);
	$CI->db->update('users_groups_permissions');
}

foreach ($rename_events AS $renamefrom=>$renameto) {
	$CI->db->set('event', $renameto);
	$CI->db->where('event', $renamefrom);
	$CI->db->update('email_notification_templates');
}
// --------------------------------------------------------------------------------------------


// --------------------------------------------------------------------------------------------
// Only for payment module add new 'fixed_price' column
if ($CI->load->is_module_loaded('payment')) {
	$CI->db->query("ALTER TABLE `invoices` ADD `fixed_price` BOOLEAN NOT NULL DEFAULT '0' AFTER `value`");
	$CI->db->query("CREATE TABLE IF NOT EXISTS `listings_payment_upgrades` (`id` int(11) NOT NULL auto_increment, `listing_id` int(11) NOT NULL, `old_level_id` int(11) NOT NULL, `new_level_id` int(11) NOT NULL, PRIMARY KEY  (`id`),KEY `listing_id` (`listing_id`)) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1");
}
// --------------------------------------------------------------------------------------------


// --------------------------------------------------------------------------------------------
// Only for facebook module add new 'use_facebook_logo' and 'facebook_logo_file' columns
if ($CI->load->is_module_loaded('facebook')) {
	$CI->db->query("INSERT INTO `system_settings` (`name`, `value`) VALUES ('facebook_app_id', '')");

	$CI->db->query("ALTER TABLE `users` ADD `use_facebook_logo` tinyint(1) NOT NULL default '1'");
	$CI->db->query("ALTER TABLE `users` ADD `facebook_logo_file` varchar(255) NOT NULL");
	
	$CI->db->query("UPDATE `users` SET `facebook_logo_file`=`user_logo_image`");
	$CI->db->query("UPDATE `users` SET `user_logo_image`=''");
}
// --------------------------------------------------------------------------------------------

// --------------------------------------------------------------------------------------------
// Add new columns to banners tables
if ($CI->load->is_module_loaded('banners')) {
	$CI->db->query("ALTER TABLE `banners` ADD `remote_image_url` VARCHAR( 255 ) NOT NULL AFTER `banner_file`, ADD `use_remote_image` BOOLEAN NOT NULL AFTER `remote_image_url`");
	$CI->db->query("ALTER TABLE `banners` ADD `is_uploaded_flash` BOOLEAN NOT NULL AFTER `use_remote_image`, ADD `is_loaded_flash` BOOLEAN NOT NULL AFTER `is_uploaded_flash`");
	$CI->db->query("ALTER TABLE `banners_blocks` ADD `allow_remote_banners` BOOLEAN NOT NULL AFTER `block_size`");
}
// --------------------------------------------------------------------------------------------


// --------------------------------------------------------------------------------------------
// Add Content permissions options
// --------------------------------------------------------------------------------------------
$CI->db->select('*');
$CI->db->from('levels');
$query = $CI->db->get();
$levels_array = $query->result_array();

$CI->db->select('*');
$CI->db->from('users_groups');
$query = $CI->db->get();
$users_groups_array = $query->result_array();

foreach ($levels_array AS $level) {
	foreach ($users_groups_array AS $users_group) {
		$CI->db->set('objects_name', 'levels');
		$CI->db->set('object_id', $level['id']);
		$CI->db->set('group_id', $users_group['id']);
		$CI->db->insert('users_groups_content_permissions');
	}
}
// --------------------------------------------------------------------------------------------


// --------------------------------------------------------------------------------------------
// Move 'company_details' from system settings to site settings
// --------------------------------------------------------------------------------------------
$CI->db->select('value');
$CI->db->from('system_settings');
$CI->db->where('name', 'company_details');
$query = $CI->db->get();
$row = $query->row_array();
$company_details = $row['value'];

$CI->db->set('name', 'company_details');
$CI->db->set('value', $company_details);
$CI->db->insert('site_settings');

$CI->db->where('name', 'company_details');
$CI->db->delete('system_settings');
// --------------------------------------------------------------------------------------------


// --------------------------------------------------------------------------------------------
// Move categories by type rows into 'categories' table and drop 'categories_by_type' table
// --------------------------------------------------------------------------------------------
$CI->db->select();
$CI->db->from('categories_by_type');
$CI->db->order_by('name');
$categories_array = $CI->db->get()->result_array();
$i = 0;
foreach ($categories_array AS $row_key=>$row) {
	$CI->db->select('id');
	$CI->db->where('seo_name', $row['seo_name']);
	$CI->db->from('categories');
	if ($CI->db->get()->num_rows()) {
		// Same seo names will be rewriten as '....-1'
		$categories_array[$row_key]['seo_name'] = $row['seo_name'] . '-1';
	}
	
	// Update order of global categories
	$i++;
	$CI->db->set('order_num', $i);
	
	foreach ($row AS $key=>$value) {
		if ($key != 'id')
			$CI->db->set($key, $value);
	}
	$CI->db->insert('categories');
	$categories_array[$row_key]['new_id'] = $CI->db->insert_id();
	
	// Update 'listings_in_categories' table
	$CI->db->set('category_id', $categories_array[$row_key]['new_id']);
	$CI->db->where('category_id', $categories_array[$row_key]['id']);
	$CI->db->where('type_id', $categories_array[$row_key]['type_id']);
	$CI->db->update('listings_in_categories');
}

// Update 'parent_category_id' field with new values
foreach ($categories_array AS $row_key=>$row) {
	$CI->db->set('parent_category_id', $row['new_id']);
	$CI->db->where('parent_category_id', $row['id']);
	$CI->db->where('type_id', $row['type_id']);
	$CI->db->update('categories');
}

// Remove 'categories_by_type' table
$CI->load->dbforge();
$CI->dbforge->drop_table('categories_by_type');

// Remove type_id column from 'listings_in_categories' table
$CI->db->query('ALTER TABLE `listings_in_categories` DROP `type_id`');

// Update categories tree paths
$CI->load->model('categories', 'categories');
$categories_array = $CI->db->get('categories')->result_array();
foreach ($categories_array AS $category_row) {
	$path = array();
	$path[] = $category_row['id'];
	$CI->categories->getCategoriesTreePathRecursively($category_row['parent_category_id'], $categories_array, $path);
	$path[] = 0;
	$path = array_reverse($path);
	$path_string = '-'.implode('-', $path);

	$CI->db->set('tree_path', $path_string);
	$CI->db->where('id', $category_row['id']);
	$CI->db->update('categories');
}

// Update order of global categories
$CI->db->select();
$CI->db->from('categories');
$CI->db->where('type_id', 0);
$CI->db->order_by('name');
$categories_db = $CI->db->get()->result_array();
$i = 0;
foreach ($categories_db AS $category_row) {
	$i++;
	$CI->db->set('order_num', $i);
	$CI->db->where('id', $category_row['id']);
	$CI->db->update('categories');
}
// --------------------------------------------------------------------------------------------


// --------------------------------------------------------------------------------------------
$users_content_server_path = $CI->config->item('users_content_server_path');
$users_content_settings = $CI->config->item('users_content');
// Check all users content folders
foreach ($users_content_settings AS $content_option) {
	if (isset($content_option['upload_to'])) {
		if (!is_dir($users_content_server_path . $content_option['upload_to']))
			@mkdir($users_content_server_path . $content_option['upload_to']);
	}
	if (isset($content_option['thumbnails'])) {
		foreach ($content_option['thumbnails'] AS $content_option_thumbnails) {
			if (!is_dir($users_content_server_path . $content_option_thumbnails['upload_to']))
				@mkdir($users_content_server_path . $content_option_thumbnails['upload_to']);
		}
	}
}
// Create tmp subfolder in 'users_content' folder
@mkdir($users_content_server_path . 'tmp');
// --------------------------------------------------------------------------------------------



// --------------------------------------------------------------------------------------------
// Check listings seo names, now it can't be numeric
// --------------------------------------------------------------------------------------------
$listings_array = $CI->db->get('listings')->result_array();
foreach ($listings_array AS $listing_row) {
	if (is_numeric($listing_row['seo_title'])) {
		$CI->db->set('seo_title', $listing_row['seo_title'].'o');
		$CI->db->where('id', $listing_row['id']);
		$CI->db->update('listings');
	}
}
// --------------------------------------------------------------------------------------------


// --------------------------------------------------------------------------------------------
// Move default listings 'order by' and 'order direction' from `system_settings` table to
// `listings_fields_visibility` table
// --------------------------------------------------------------------------------------------
$CI->db->select('value');
$CI->db->from('system_settings');
$CI->db->or_where('name', 'def_listings_order');
$row = $CI->db->get()->row_array();
$def_listings_order = $row['value'];

$CI->db->select('value');
$CI->db->from('system_settings');
$CI->db->or_where('name', 'def_listings_order_direction');
$row = $CI->db->get()->row_array();
$def_listings_order_direction = $row['value'];

$CI->db->set('order_by', $def_listings_order);
$CI->db->set('order_direction', $def_listings_order_direction);
$CI->db->update('listings_fields_visibility');

$CI->db->or_where('name', 'def_listings_order');
$CI->db->or_where('name', 'def_listings_order_direction');
$CI->db->delete('system_settings');
// --------------------------------------------------------------------------------------------

// --------------------------------------------------------------------------------------------
// Modify 'languages' table
// --------------------------------------------------------------------------------------------
if ($CI->load->is_module_loaded('i18n')) {
	$CI->db->query("ALTER TABLE `languages` CHANGE `code` `code` VARCHAR( 5 )");
	$CI->db->query("ALTER TABLE `languages` ADD `db_code` VARCHAR( 2 ) NOT NULL");
	$CI->db->query("ALTER TABLE `languages` ADD `custom_theme` VARCHAR( 255 ) NOT NULL DEFAULT 'default'");
	$CI->db->query("ALTER TABLE `languages` ADD `decimals_separator` VARCHAR( 1 ) NOT NULL DEFAULT '.'");
	$CI->db->query("ALTER TABLE `languages` ADD `thousands_separator` VARCHAR( 1 ) NOT NULL DEFAULT ''");
	$CI->db->query("ALTER TABLE `languages` ADD `date_format` VARCHAR( 20 ) NOT NULL DEFAULT '%m/%d/%y'");
	$CI->db->query("ALTER TABLE `languages` ADD `time_format` VARCHAR( 20 ) NOT NULL DEFAULT '%H:%M:%S'");

	$CI->db->query("UPDATE `languages` SET `db_code`=`code`");
}
// --------------------------------------------------------------------------------------------

// --------------------------------------------------------------------------------------------
// Convert predefined locations table
// --------------------------------------------------------------------------------------------
include_once(MODULES_PATH . 'google_maps/classes/location_geoname.class.php');
$geocoder = new locationGeoname;

$CI->load->model('locations', 'locations_predefined');
//$locations_db = $CI->locations->selectLocationsFromDB();*/

$CI->db->select('lis.*');
$CI->db->select('loc.parent_id AS location_parent_id');
$CI->db->from('listings AS lis');
$CI->db->join('locations AS loc', 'lis.location_id=loc.id', 'left');
$query = $CI->db->get();
foreach ($query->result_array() AS $row) {
	if ($location_id = $row['location_id']) {
		/*$path = array();
		$path[] = $location_id;
		$CI->locations->getLocationsTreePathRecursively($row['location_parent_id'], $locations_db, $path);
		$path[] = 0;
		$path = array_reverse($path);
		$path_string = '-'.implode('-', $path);
		
		$CI->db->set('tree_path', $path_string);
		$CI->db->where('id', $location_id);
		$CI->db->update('locations');
		
		$loc_names = array();
		foreach ($path AS $loc_id) {
			if ($loc_id) {
				$location = $CI->locations->getLocationById($loc_id);
				$loc_names[] = $location->name;
			}
		}
		$location_name = implode(', ', $loc_names);

	    if ($geocoded_name = $geocoder->geonames_request($location_name)) {
	    	$CI->db->set('geocoded_name', $geocoded_name);
	    }*/
	
		$CI->db->set('listing_id', $row['id']);
		$CI->db->set('address_line_1', $row['address_line_1']);
		$CI->db->set('address_line_2', $row['address_line_2']);
		$CI->db->set('zip_or_postal_index', $row['zip_or_postal_index']);
		$CI->db->set('manual_coords', 1);
		$CI->db->set('map_coords_1', $row['map_coords_1']);
		$CI->db->set('map_coords_2', $row['map_coords_2']);
		$CI->db->set('map_zoom', $row['map_zoom']);
		//$CI->db->set('location', $location_name);
		$CI->db->set('predefined_location_id', $location_id);
		$CI->db->set('use_predefined_locations', 1);
		$CI->db->insert('listings_in_locations');
	}
}

$CI->db->select('id');
$CI->db->select('name');
$CI->db->select('parent_id');
$CI->db->from('locations');
$locations_db = $CI->db->get()->result_array();

foreach ($locations_db AS $loc_row) {
	$path = array();
	$path[] = $loc_row['id'];
	$CI->locations->getLocationsTreePathRecursively($loc_row['parent_id'], $locations_db, $path);
	$path[] = 0;
	$path = array_reverse($path);
	$path_string = '-'.implode('-', $path);
	
	// --------------------------------------------------------------------------------------------
	// Geocode and resave listings_in_locations
	// --------------------------------------------------------------------------------------------
	$locations = array();
	foreach ($path AS $id) {
		foreach ($locations_db AS $location_row) {
			if ($location_row['id'] == $id) {
				$locations[] = $location_row['name'];
			}
		}
	}
	$location_name = implode(', ', $locations);
	if ($geocoded_name = $geocoder->geonames_request($location_name)) {
		$this->db->set('geocoded_name', $geocoded_name);
	}
	$CI->db->set('tree_path', $path_string);
	$CI->db->where('id', $loc_row['id']);
	$CI->db->update('locations');
	
	if ($geocoded_name) {
		$CI->db->set('geocoded_name', $geocoded_name);
	}
	$CI->db->set('location', $location_name);
	$CI->db->where('predefined_location_id', $loc_row['id']);
	$CI->db->update('listings_in_locations');
	// --------------------------------------------------------------------------------------------
}

// --------------------------------------------------------------------------------------------

// --------------------------------------------------------------------------------------------
// Convert locations module
// --------------------------------------------------------------------------------------------
$CI->db->query('UPDATE `modules` SET `dir`="locations_predefined", `name`="Predefined Locations" WHERE `dir`="locations"');
$CI->db->query('UPDATE `language_files` SET `file`="locations_predefined.php", `module`="Predefined Locations" WHERE `module`="Locations"');
// --------------------------------------------------------------------------------------------


// --------------------------------------------------------------------------------------------
// Make 'Contact us' core module
// --------------------------------------------------------------------------------------------
if ($CI->load->is_module_loaded('contactus')) {
	$CI->db->set('module', 'Frontend');
	$CI->db->where('file', 'contactus.php');
	$CI->db->update('language_files');
	
	$CI->db->where('dir', 'contactus');
	$CI->db->delete('modules');
} else {
	$CI->db->set('module', 'Frontend');
	$CI->db->set('file', 'contactus.php');
	$CI->db->insert('language_files');
}
// --------------------------------------------------------------------------------------------

// --------------------------------------------------------------------------------------------
// Add google maps to list of core modules (install it if it wasn't installed yet)
// --------------------------------------------------------------------------------------------
/*$CI->db->select();
$CI->db->from('modules');
$CI->db->where('dir', 'google_maps');*/
//if (!$CI->db->get()->num_rows()) {
if (!$CI->load->is_module_loaded('google_maps')) {
	$CI->modules_control->installModule('google_maps');
}
// --------------------------------------------------------------------------------------------

// --------------------------------------------------------------------------------------------
// Make 'Contact us' core module
// --------------------------------------------------------------------------------------------
/*$CI->db->select();
$CI->db->from('language_files');
$CI->db->where('file', 'contactus.php');*/
if ($CI->load->is_module_loaded('contactus')) {
	$CI->db->set('module', 'Frontend');
	$CI->db->where('file', 'contactus.php');
	$CI->db->update('language_files');
	
	$CI->db->where('dir', 'contactus');
	$CI->db->delete('modules');
}
// --------------------------------------------------------------------------------------------

// --------------------------------------------------------------------------------------------
// Add lang columns
// --------------------------------------------------------------------------------------------
if ($CI->load->is_module_loaded('i18n')) {
	$CI->load->model('languages', 'i18n');
	$languages = $CI->languages->getLanguages();
	foreach ($languages AS $lang) {
		$CI->db->query("ALTER TABLE `listings` ADD `" . $lang['code'] . "_listing_meta_description` TEXT NOT NULL");
		$CI->db->query("ALTER TABLE `users` ADD `" . $lang['code'] . "_meta_description` TEXT NOT NULL");
		$CI->db->query("ALTER TABLE `users` ADD `" . $lang['code'] . "_meta_keywords` TEXT NOT NULL");
		$CI->db->query("ALTER TABLE `types` ADD `" . $lang['code'] . "_meta_title` varchar(255) DEFAULT 'untranslated' NOT NULL");
		$CI->db->query("ALTER TABLE `types` ADD `" . $lang['code'] . "_meta_description` TEXT NOT NULL");

		$CI->db->query("ALTER TABLE `map_marker_icons_themes` ADD `" . $lang['code'] . "_name` varchar(255) DEFAULT 'untranslated' NOT NULL");
		$CI->db->query("ALTER TABLE `map_marker_icons` ADD `" . $lang['code'] . "_name` varchar(255) DEFAULT 'untranslated' NOT NULL");
		$CI->db->query("ALTER TABLE `listings_in_locations` ADD `" . $lang['code'] . "_location` varchar(255) DEFAULT 'untranslated' NOT NULL");
		$CI->db->query("ALTER TABLE `listings_in_locations` ADD `" . $lang['code'] . "_address_line_1` varchar(255) DEFAULT 'untranslated' NOT NULL");
		$CI->db->query("ALTER TABLE `listings_in_locations` ADD `" . $lang['code'] . "_address_line_2` varchar(255) DEFAULT 'untranslated' NOT NULL");
	}
}
// --------------------------------------------------------------------------------------------

// --------------------------------------------------------------------------------------------
// Drop unnecessary tables and columns
// --------------------------------------------------------------------------------------------
$CI->db->query('ALTER TABLE `listings` 
DROP COLUMN location_id, 
DROP COLUMN address_line_1, 
DROP COLUMN address_line_2,
DROP COLUMN zip_or_postal_index, 
DROP COLUMN map_coords_1, 
DROP COLUMN map_coords_2, 
DROP COLUMN map_address, 
DROP COLUMN map_zoom');

?>