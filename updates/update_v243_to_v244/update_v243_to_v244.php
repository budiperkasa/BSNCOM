<?php

$CI = &get_instance();

// Add lang columns
if ($CI->load->is_module_loaded('i18n')) {
	$CI->load->model('languages', 'i18n');
	$languages = $CI->languages->getLanguages();
	foreach ($languages AS $lang) {
		$CI->db->query("ALTER TABLE `categories` ADD `" . $lang['code'] . "_meta_title` varchar(255) DEFAULT 'untranslated' NOT NULL");
		$CI->db->query("ALTER TABLE `categories` ADD `" . $lang['code'] . "_meta_description` varchar(255) DEFAULT 'untranslated' NOT NULL");
		
		$CI->db->query("ALTER TABLE `categories_by_type` ADD `" . $lang['code'] . "_meta_title` varchar(255) DEFAULT 'untranslated' NOT NULL");
		$CI->db->query("ALTER TABLE `categories_by_type` ADD `" . $lang['code'] . "_meta_description` varchar(255) DEFAULT 'untranslated' NOT NULL");
		
		$CI->db->query("ALTER TABLE `types` ADD `" . $lang['code'] . "_meta_title` varchar(255) DEFAULT 'untranslated' NOT NULL");
		$CI->db->query("ALTER TABLE `types` ADD `" . $lang['code'] . "_meta_description` varchar(255) DEFAULT 'untranslated' NOT NULL");
		
		$CI->db->query("ALTER TABLE `content_pages` ADD `" . $lang['code'] . "_meta_title` varchar(255) DEFAULT 'untranslated' NOT NULL");
		$CI->db->query("ALTER TABLE `content_pages` ADD `" . $lang['code'] . "_meta_description` varchar(255) DEFAULT 'untranslated' NOT NULL");
	}
}
?>