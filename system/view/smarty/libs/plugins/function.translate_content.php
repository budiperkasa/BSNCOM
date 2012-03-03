<?php
include_once(MODULES_PATH . 'i18n/classes/translations.class.php');

/**
 * Web 2.0 Classifieds script smarty function
 * 
 * inserts content translation button
 *
 * @param array $params
 * table - db table name
 * field - db table column
 * row_id - ID of the db table row
 * [field_type] - string || text || richtext
 * 
 * @param object $smarty
 * @return string
 */
function smarty_function_translate_content($params, $smarty)
{
	$system_settings = registry::get('system_settings');
	// If multilanguage interface enabled
	if (isset($system_settings['multilanguage_enabled']) && $system_settings['multilanguage_enabled']) {
		$table = $params['table'];
		$field = $params['field'];
		$row_id = $params['row_id'];
		if (isset($params['virtual_id']))
			$virtual_id = $params['virtual_id'];
		else 
			$virtual_id = '';
		
		if (isset($params['field_type'])) {
			$field_type = $params['field_type'];
		} else {
			$field_type = 'string';
		}
		
		return translations::getTranslationLink($table, $field, $row_id, $field_type, $virtual_id);
	}
}
?>