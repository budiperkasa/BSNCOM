<?php
include_once(MODULES_PATH . 'google_maps/classes/location_geoname.class.php');
$CI = &get_instance();

// Switch on lang areas
$CI->languages->langAreasSwitchOn();

// --------------------------------------------------------------------------------------------
// Update categories tree paths
// --------------------------------------------------------------------------------------------
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

$types_with_local_cats = $CI->categories->selectLocalCategoriesTypes();
foreach ($types_with_local_cats AS $type) {
	$type_id = $type['id'];
	$CI->categories->setTypeId($type_id);
	$categories_array = $CI->categories->selectCategoriesFromDB();
	foreach ($categories_array AS $category_row) {
		$path = array();
		$path[] = $category_row['id'];
		$CI->categories->getCategoriesTreePathRecursively($category_row['parent_category_id'], $categories_array, $path);
		$path[] = 0;
		$path = array_reverse($path);
		$path_string = '-'.implode('-', $path);
	
		$CI->db->set('tree_path', $path_string);
		$CI->db->where('id', $category_row['id']);
		$CI->db->where('type_id', $type_id);
		$CI->db->update('categories_by_type');
	}
}

// --------------------------------------------------------------------------------------------
// Add lang columns
// --------------------------------------------------------------------------------------------
if ($CI->load->is_module_loaded('i18n')) {
	$CI->load->model('languages', 'i18n');
	$languages = $CI->languages->getLanguages();
	foreach ($languages AS $lang) {
		$CI->db->query("ALTER TABLE `map_marker_icons_themes` ADD `" . $lang['code'] . "_name` varchar(255) DEFAULT 'untranslated' NOT NULL");
		$CI->db->query("ALTER TABLE `map_marker_icons` ADD `" . $lang['code'] . "_name` varchar(255) DEFAULT 'untranslated' NOT NULL");
		$CI->db->query("ALTER TABLE `listings_in_locations` ADD `" . $lang['code'] . "_location` varchar(255) DEFAULT 'untranslated' NOT NULL");
		$CI->db->query("ALTER TABLE `listings_in_locations` ADD `" . $lang['code'] . "_address_line_1` varchar(255) DEFAULT 'untranslated' NOT NULL");
		$CI->db->query("ALTER TABLE `listings_in_locations` ADD `" . $lang['code'] . "_address_line_2` varchar(255) DEFAULT 'untranslated' NOT NULL");
	}
}

// --------------------------------------------------------------------------------------------
// Process listings locations
// --------------------------------------------------------------------------------------------
/*
 * This function builds the chain of selected locations by levels begining from the lowest $location_id
 * 
 * returns $selected_locations_chain[level][id]
 * returns $selected_locations_chain[level][parent_id]
 *         $selected_locations_chain[level][name]
 */
function getLocationsChainFromId($locations_db, $parent_id, &$selected_locations_chain)
{
	foreach ($locations_db as $location) {
		if ($parent_id == $location['id']) {
			$selected_locations_chain[] = getLocationById($location['id']);
			getLocationsChainFromId($locations_db, $location['parent_id'], $selected_locations_chain);
		}
	}
}
function getLocationById($location_id)
{
	$CI = &get_instance();
   	$CI->db->select();
   	$CI->db->from('locations');
   	$CI->db->where('id', $location_id);
   	$query = $CI->db->get();
   	return $query->row_array();
}

$CI->load->model('users_locations', 'locations');
$geoname_id = 0;
$location_name = '';
$query = $CI->db->get('locations');
$locations_db = $query->result_array();

$CI->db->select('lis.*');
$CI->db->select('loc.parent_id AS location_parent_id');
$CI->db->from('listings AS lis');
$CI->db->join('locations AS loc', 'lis.location_id=loc.id', 'left');
$query = $CI->db->get();
foreach ($query->result_array() AS $row) {
	if ($location_id = $row['location_id']) {
		$selected_locations_chain = array();
		$selected_locations_chain[] = getLocationById($row['location_id']);
		getLocationsChainFromId($locations_db, $row['location_parent_id'], $selected_locations_chain);
		$locations_chain = array();
		foreach ($selected_locations_chain AS $location) {
			$locations_chain[] = $location['name'];
		}
		$location_name = implode(', ', $locations_chain);

		$geoname = new locationGeoname($location_name);
		$geocoded_name = $geoname->geonames_request();
		$CI->db->set('geocoded_name', $geocoded_name);
		$CI->db->set('geocoded_country', $geoname->country);
		$CI->db->set('geocoded_state', $geoname->state);
		$CI->db->set('geocoded_city', $geoname->city);
	}
	$CI->db->set('listing_id', $row['id']);
	$CI->db->set('address_line_1', $row['address_line_1']);
	$CI->db->set('address_line_2', $row['address_line_2']);
	$CI->db->set('zip_or_postal_index', $row['zip_or_postal_index']);
	$CI->db->set('manual_coords', 1);
	$CI->db->set('map_coords_1', $row['map_coords_1']);
	$CI->db->set('map_coords_2', $row['map_coords_2']);
	$CI->db->set('map_zoom', $row['map_zoom']);
	$CI->db->set('location', $location_name);
	$CI->db->insert('listings_in_locations');
}

// --------------------------------------------------------------------------------------------
// Unset locations module
// --------------------------------------------------------------------------------------------
$CI->db->query('DELETE FROM `modules` WHERE `dir`="locations"');
$CI->db->query('DELETE FROM `language_files` WHERE `module`="Locations"');
// --------------------------------------------------------------------------------------------

// --------------------------------------------------------------------------------------------
// Drop unnecessary tables and columns
// --------------------------------------------------------------------------------------------
$CI->db->query('DROP TABLE `locations`, `locations_levels`');
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