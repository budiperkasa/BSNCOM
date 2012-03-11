<?php
include_once(MODULES_PATH . 'i18n/classes/translations.class.php');
include_once(MODULES_PATH . 'i18n/classes/translation_db_static_vars.class.php');

function loadI18nItemsList($CI)
{
	$modules_array = registry::get('modules_array');
	foreach ($modules_array AS $module_dir=>$module_title) {
		if (is_file(MODULES_PATH . $module_dir . '/i18n_items_list.php')) {
			require(MODULES_PATH . $module_dir . '/i18n_items_list.php');
		}
	}

	$CI->load->model('languages', 'i18n');
	$current_language = $CI->languages->getLanguageByCode(registry::get('current_language'));
	// compatibility with old version
	if (isset($current_language->db_code) && $current_language->db_code) {
		$db_code = $current_language->db_code;
	} else {
		$db_code = $current_language->code;
	}
	registry::set('current_language_db_code', $db_code);
	registry::set('current_language_db_obj', $current_language);
	
	registry::set('i18n_fields', $i18n_fields);

	translation_db_static_vars::$i18n_fields = $i18n_fields;
}

function initI18nModule($CI)
{
	//$url_additional_segments_matches = registry::get('url_additional_segments_matches');    // Not needed, already processed in Components_loader.php
	
	$system_settings = registry::get('system_settings');
	
	$current_language_db_obj = registry::get('current_language_db_obj');
	$CI->config->set_item('date_format', $current_language_db_obj->date_format);
	$CI->config->set_item('time_format', $current_language_db_obj->time_format);

	if ($system_settings['language_areas_enabled']) {
		$view = $CI->load->view();
		$view->addJsFile('jquery.jqURL.js');
		$view->addJsFile('translate_dialog.js');
	}
}

function buildLanguagesPanels($CI)
{
	$system_settings = registry::get('system_settings');
	// If multilanguage interface enabled
	if ($system_settings['multilanguage_enabled']) {
		$CI->load->model('languages', 'i18n');
		$view = $CI->load->view();

    	// Value from components loader
    	$current_language = registry::get('current_language');

	    $languages = $CI->languages->getLanguages();
	    /*foreach ($languages AS $language) {
	    	if ($language['code'] == $current_language) {
	    		$view->assign('language_name', $language['name']);
	    		$view->assign('language_flag', $language['flag']);
	    		break;
	    	}
	    }*/
	    
	    $uri_string = implode('/', $CI->uri->segments);
	    if ($uri_string)
	    	$uri_string .= '/';
	    
		$view->assign('languages', $languages);
		$view->assign('current_language', $current_language);
		$view->assign('uri_string',  $uri_string);
    	$view->assign('multilanguage_enabled', $system_settings['multilanguage_enabled']);
    	return $view->fetch('i18n/languages_panels.tpl');
	}
}

function setTableI18nConditions($CI, $args)
{
	$system_settings = registry::get('system_settings');
	if ($system_settings['language_areas_enabled'] || $CI->config->item('default_language') != 'en' || $CI->config->item('english_db_code') !== '') {
		$table     = $args[0];
		$query_id  = $args[1];
		translation_db_static_vars::$tables[$query_id][] = $table;
	}
}
	
function setSelectI18nConditions($CI, $args)
{
	$system_settings = registry::get('system_settings');
	if ($system_settings['language_areas_enabled'] || $CI->config->item('default_language') != 'en' || $CI->config->item('english_db_code') !== '') {
		$select    = $args[0];
		$query_id  = $args[1];
		translation_db_static_vars::$selects[$query_id][] = $select;
	}
}
	
function setWhereI18nConditions($CI, $args)
{
	$system_settings = registry::get('system_settings');
	if ($system_settings['language_areas_enabled'] || $CI->config->item('default_language') != 'en' || $CI->config->item('english_db_code') !== '') {
		$where           = $args[0];
		$where_clause    = $args[3];
		$query_id        = $args[4];
		translation_db_static_vars::$wheres[$query_id][] = array($where, $where_clause);
	}
}
	
function setLikeI18nConditions($CI, $args)
{
	$system_settings = registry::get('system_settings');
	if ($system_settings['language_areas_enabled'] || $CI->config->item('default_language') != 'en' || $CI->config->item('english_db_code') !== '') {
		$like           = $args[0];
		$like_clause    = $args[2];
		$query_id        = $args[3];
		translation_db_static_vars::$likes[$query_id][] = array($like, $like_clause);
	}
}

/**
 * Collects sets of 'insert' and 'update' queries
 * in order to replace original fields of i18n Items List to its 
 * language areas sinonims
 * 
 * example: 
 * language code == 'ru'
 * then query
 * INSERT INTO content_pages ('title') VALUES 'xxxxx' WHERE id='yyyy'
 * will be modified to
 * INSERT INTO content_pages ('ru_title') VALUES 'xxxxx' WHERE id='yyyy'
 *
 * @param array $args
 */
function setQuerySetI18n($CI, $args)
{
	$system_settings = registry::get('system_settings');
	if ($system_settings['language_areas_enabled'] || $CI->config->item('default_language') != 'en' || $CI->config->item('english_db_code') !== '') {
		$set      = $args[0];
		$query_id = $args[1];
		
		$lang_code = registry::get('current_language');

		//if ($lang_code != $CI->config->item('default_language')) {
			translation_db_static_vars::$sets[$query_id][] = $set;
		//}
	}
}
	
/**
 * Collects all 'insert', 'update' and 'delete' tables of sql queries
 * in order to replace original fields of i18n Items List to its 
 * language areas sinonims
 *
 * @param array $args - 'update' or 'delete' item
 */
function setQueryInsertUpdateI18n($CI, $args)
{
	$system_settings = registry::get('system_settings');
	if ($system_settings['language_areas_enabled'] || $CI->config->item('default_language') != 'en' || $CI->config->item('english_db_code') !== '') {
		$item = $args[0];
		$query_id  = $args[1];

		translation_db_static_vars::$insert_tables[$query_id][] = strtolower($item);
	}
}

/**
 * resets all query's attributes for translation modification 
 * @param array $args
 */
function resetQuery($CI, $args)
{
	$query_id   = $args[0];

	translation_db_static_vars::$selects[$query_id] = array();
	translation_db_static_vars::$tables[$query_id] = array();
	translation_db_static_vars::$wheres[$query_id] = array();
	translation_db_static_vars::$likes[$query_id] = array();
	translation_db_static_vars::$sets[$query_id] = array();
	translation_db_static_vars::$insert_tables[$query_id] = array();
}

/**
 * Modifies sql query and substitutes original fields to 
 * selected language area code fields
 *
 * @param array $args - query and query type
 */
function modifyQuery($CI, $args)
{
	$system_settings = registry::get('system_settings');

	$query_db   = $args[0];
	$query_id   = $args[1];
	$query_type = $args[2];
	
	if (isset(translation_db_static_vars::$selects[$query_id])) {
		$ar_select = translation_db_static_vars::$selects[$query_id];
		$sql_tables = translation_db_static_vars::$tables[$query_id];
	} else {
		$ar_select = array();
		$sql_tables = array();
	}
	if (isset(translation_db_static_vars::$wheres[$query_id])) {
		$ar_where = translation_db_static_vars::$wheres[$query_id];
	} else {
		$ar_where = array();
	}
	if (isset(translation_db_static_vars::$likes[$query_id])) {
		$ar_like = translation_db_static_vars::$likes[$query_id];
	} else {
		$ar_like = array();
	}
	translation_db_static_vars::$selects[$query_id] = array();
	translation_db_static_vars::$tables[$query_id] = array();
	translation_db_static_vars::$wheres[$query_id] = array();
	translation_db_static_vars::$likes[$query_id] = array();
		
	if (isset(translation_db_static_vars::$sets[$query_id])) {
		$sets = translation_db_static_vars::$sets[$query_id];
		$insert_tables = translation_db_static_vars::$insert_tables[$query_id];
	} else {
		$sets = array();
		$insert_tables = array();
	}
	translation_db_static_vars::$sets[$query_id] = array();
	translation_db_static_vars::$insert_tables[$query_id] = array();

	if (!($lang_db_code = getLangDBCode()))
		return false;

	// Switch off lang areas
	$language_areas_really_enabled = false;
	if ($system_settings['language_areas_enabled'])
		$language_areas_really_enabled = true;
	$CI->languages->langAreasSwitchOff();

		if ($query_type == 'select' /*&& $lang_db_code != $CI->config->item('default_language')*/) {
			foreach (translation_db_static_vars::$i18n_fields AS $table=>$fields) {
				// Loop i18n fields
				foreach ($fields AS $field) {
					// Loop 'from' items
					foreach ($sql_tables AS $from) {
						$_table = '';
						$_tsinonim = '';

						// Parse 'from' item of the sql query
						$a = explode(' as ', strtolower($from));
						if (count($a) == 1) {
							$a = explode(' ', strtolower($from));
							if (count($a) > 1) {
								$_tsinonim = trim($a[1]);
							} else {
								$_tsinonim = '';
							}
						} else {
							$_tsinonim = trim($a[1]);
						}
						$_table = trim($a[0]);

						if ($_table == $CI->db->_protect_identifiers($table, TRUE, NULL, FALSE) || $_table == $table) {
							// Loop 'select' items
							foreach ($ar_select AS $select) {
								$process_field = '';
								$process_table = '';
								$_fsinonim = '';
								$_ftsinonim = '';
								$_field = '';

								// Parse 'select' item of the sql query
								$b = explode(' as ', strtolower($select));
								if (count($b) == 1) {
									$b = explode(' ', strtolower($select));
								}
								if (isset($b[1]))
									$_fsinonim = trim($b[1]);
								else 
									$_fsinonim = '';
								$_field = trim($b[0]);
									
								$c = explode('.', $_field);
								if (count($c) > 1) {
									$_ftsinonim = trim($c[0]);
									$_field = trim($c[1]);
								} else {
									$_ftsinonim = '';
								}

								// Match tables and columns
								if (!empty($_ftsinonim) && !empty($_tsinonim)) {
									if ($_ftsinonim == $_tsinonim)
										if ($_field == $field || $_field == '*') {
											$process_field = $field;
											$process_table = $table;
										}
								} else {
									if ($_field == $CI->db->_protect_identifiers($field, TRUE, NULL, FALSE) || $_field == $field || $_field == '*') {
										$process_field = $field;
										$process_table = $table;
									}
								}
								if (!empty($process_field) && !empty($process_table)) {
									if (!empty($_tsinonim))
										$_sinonim = $_tsinonim . '.';
									else
										$_sinonim = $_tsinonim;
									if (empty($_fsinonim))
										$_fsinonim = $field;

									if ($_field != '*') {
										$query_db->changeSelectAlias($select, 'original_' . $process_field);
									} else {
										// This is in order to use original lang column, if in selected language item wasn't translated
										$query_db->select($_sinonim . $process_field . ' AS original_' . $process_field);
									}
									// Add "original_.$process_field" order by parameter
									if (in_array($CI->db->_protect_identifiers($_sinonim . $process_field).' ASC', $query_db->ar_orderby))
										$query_db->order_by('original_' . $process_field, 'ASC');
									if (in_array($CI->db->_protect_identifiers($_sinonim . $process_field).' DESC', $query_db->ar_orderby))
										$query_db->order_by('original_' . $process_field, 'DESC');
									if (in_array($CI->db->_protect_identifiers($_sinonim . $process_field), $query_db->ar_orderby))
										$query_db->order_by('original_' . $process_field);

									//$query_db->select($_sinonim . $process_field . ' AS original_' . $process_field);
									$query_db->select($_sinonim . $lang_db_code . '_' . $process_field . ' AS ' . $_fsinonim);
								}
							}
							// Loop 'where' clauses
							foreach ($ar_where AS $where_array) {
								// Parse 'where' clauses of the sql query
								list($where, $where_clause) = $where_array;
								$a = explode('.', $where);
								if (count($a) > 1) {
									$_fwsinonim = trim($a[0]);
									$_wfield = trim($a[1]);
								} else {
									$_fwsinonim = '';
									$_wfield = $where;
								}
								// remove operators from where clause
								$_fwsinonim = str_replace(array('`','!','=',' ','>','<','/','is null','is not null'), '', $_fwsinonim);
								$_wfield = str_replace(array('`','!','=',' ','>','<','/','is null','is not null'), '', $_wfield);
								if (($_wfield == $CI->db->_protect_identifiers($field, TRUE, NULL, FALSE) || $_wfield == $field) && (empty($_fwsinonim) || ($_fwsinonim == $_tsinonim || $_fwsinonim == $CI->db->_protect_identifiers($_tsinonim, TRUE, NULL, FALSE)))) {
									$query_db->changeWhereClause($where_clause, $_wfield, $lang_db_code . '_' . $_wfield);
								}
							}
							// Loop 'like' clauses
							foreach ($ar_like AS $like_array) {
								// Parse 'like' clauses of the sql query
								list($like, $like_clause) = $like_array;
								$a = explode('.', $like);
								if (count($a) > 1) {
									$_flsinonim = trim($a[0]);
									$_lfield = trim($a[1]);
								} else {
									$_flsinonim = '';
									$_lfield = $like;
								}
								// remove operators from like clause
								$_flsinonim = str_replace(array('`','!','=',' ','>','<','/','is null','is not null'), '', $_flsinonim);
								$_lfield = str_replace(array('`','!','=',' ','>','<','/','is null','is not null'), '', $_lfield);
								if (($_lfield == $CI->db->_protect_identifiers($field, TRUE, NULL, FALSE) || $_lfield == $field) && (empty($_flsinonim) || ($_flsinonim == $_tsinonim || $_flsinonim == $CI->db->_protect_identifiers($_tsinonim, TRUE, NULL, FALSE)))) {
									$query_db->changeLikeClause($like_clause, $_lfield, $lang_db_code . '_' . $_lfield);
								}
							}
						}
					}
				}
			}
		} else {
			if (($query_type == 'insert' || $query_type == 'update' || $query_type == 'insert_update' 
				 /*|| $query_type == 'insert_update_mass' ||  $query_type == 'insert_mass'*/) 
				 /*&& $lang_db_code != $CI->config->item('default_language')*/) {
				foreach (translation_db_static_vars::$i18n_fields AS $table=>$fields) {
					foreach ($fields AS $field) {
						foreach ($insert_tables AS $insert) {
							if ($insert == $CI->db->_protect_identifiers($table, TRUE, NULL, FALSE)) {
								foreach ($sets AS $set) {
									if ($set == $CI->db->_protect_identifiers($field, TRUE, NULL, FALSE))
										$query_db->changeSetKey($set, $CI->db->_protect_identifiers($lang_db_code . '_' . str_replace('`', '', $set), TRUE, NULL, FALSE));
								}
							}
						}
					}
				}
			}
		}
		
		// Switch on lang areas
		if ($language_areas_really_enabled)
			$CI->languages->langAreasSwitchOn();
	//}
}

function getLangDBCode()
{
	// this is real code (not only for DB)
	$lang_code = registry::get('current_language');

	// This value tells the system from/to which DB column we will read/write values
	$lang_db_code = registry::get('current_language_db_code');
	
	$system_settings = registry::get('system_settings');
	$CI = &get_instance();

	// If language areas disabled we will read/write from/to 'default_language' code column, while it is not English.
	// If it is English, we will read/write from/to 'english_db_code' code column
	if (!$system_settings['language_areas_enabled']) {
		if ($CI->config->item('default_language') == 'en')
			// if 'english_db_code' code column is empty - we will not modify anything
			if (!$CI->config->item('english_db_code') || $CI->config->item('english_db_code') === "")
				return false;
			else
				$lang_db_code = $CI->config->item('english_db_code');
		else
			$lang_db_code = $CI->config->item('default_language');
	} else {
		if ($lang_code == 'en') {
			// if 'english_db_code' code column is empty - we will not modify anything
			if (!$CI->config->item('english_db_code') || $CI->config->item('english_db_code') === "")
				return false;
			else
				$lang_db_code = $CI->config->item('english_db_code');
		}
	}
	return $lang_db_code;
}
?>