<?php

$modules_array = registry::get('modules_array');
foreach ($modules_array AS $module_dir=>$module_title) {
	if (is_file(MODULES_PATH . $module_dir . '/i18n_items_list.php')) {
		require(MODULES_PATH . $module_dir . '/i18n_items_list.php');
	}
}

$CI = &get_instance();
$CI->load->model('languages', 'i18n');
$CI->languages->addLangColumns('en', $i18n_fields);
$CI->languages->addLangIndexes('en', $i18n_fields);
?>