<?php

$CI = &get_instance();

// update content_fields.frontend_name = content_fields.name
$CI->db->set('frontend_name', 'name', FALSE);
$CI->db->update('content_fields');

// Add lang columns into content_fields like 'xx_frontend_name'
if ($CI->load->is_module_loaded('i18n')) {
	$CI->load->model('languages', 'i18n');
	$languages = $CI->languages->getLanguages();
	foreach ($languages AS $lang) {
		$CI->db->query("ALTER TABLE `content_fields` ADD `" . $lang['code'] . "_frontend_name` varchar(255) DEFAULT 'untranslated' NOT NULL");
	}
}

// update listings_in_categories.type_id = types.id
$CI->db->query("UPDATE `listings_in_categories` AS lic, `listings` AS l, `levels` AS lev, `types` AS t
SET lic.type_id=t.id
WHERE lic.listing_id=l.id AND l.level_id=lev.id AND lev.type_id=t.id AND t.categories_type='local'
");
?>