<?php
include_once(MODULES_PATH . 'settings/classes/pagination.php');
include_once(MODULES_PATH . 'settings/classes/spam_protector.php');

function runAutoBlocker($CI)
{
	// Seconds till next auto blocking cycle
	$block_set_time = $CI->config->item('auto_block_timer');

	$system_settings = registry::get('system_settings');

	if (intval($system_settings['auto_blocker_timestamp']) <= mktime()) {
		// Process all functions those affect on auto blocker run
		events::callEvent('Auto blocker run');

		$CI->load->model('settings', 'settings');
		$CI->settings->setAutoBlockerTimer($block_set_time);
	}
}

function selectSiteSettings($CI)
{
	// Select site settings
	$query = $CI->db->get('site_settings');
	$settings = array();
	foreach ($query->result() as $row) {
		$settings[$row->name] = $row->value;
	}
	registry::set('site_settings', $settings);
}
?>