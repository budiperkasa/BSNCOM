<?php
include_once(MODULES_PATH . 'google_maps/classes/location_geoname.class.php');
$CI = &get_instance();

$CI->db->select('lil.*');
$CI->db->from('listings_in_locations AS lil');
$query = $this->db->get();

foreach ($query->result_array() AS $row) {
	$geoname = new locationGeoname($row['location']);
	$geocoded_name = $geoname->geonames_request();
	$CI->db->set('geocoded_name', $geocoded_name);
	$CI->db->set('geocoded_country', $geoname->country);
	$CI->db->set('geocoded_state', $geoname->state);
	$CI->db->set('geocoded_city', $geoname->city);
	$CI->db->where('id', $row['id']);
	$CI->db->update('listings_in_locations');
}
?>