<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

set_time_limit(0);

$CI = &get_instance();

// --------------------------------------------------------------------------------------------
// Add lang index to DB
// --------------------------------------------------------------------------------------------
if ($CI->load->is_module_loaded('i18n')) {
	$CI->load->model('languages', 'i18n');
	$languages = $CI->languages->getLanguages();
	foreach ($languages AS $lang) {
		$CI->languages->addLangIndexes($lang['db_code']);
	}
}
// --------------------------------------------------------------------------------------------

// --------------------------------------------------------------------------------------------
// Only for banners module add new columns
if ($CI->load->is_module_loaded('banners')) {
	$CI->db->query("ALTER TABLE `banners` ADD `checked_categories` TEXT  NOT NULL AFTER `clicks_expiration_count`");
	$CI->db->query("ALTER TABLE `banners` ADD `checked_locations` TEXT NOT NULL AFTER `clicks_expiration_count`");
}
// --------------------------------------------------------------------------------------------

?>