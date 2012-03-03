<?php

class modules_controlModel extends Model
{
	/**
	 * build array of mudules info's classes instances
	 *
	 * @param array $modules_array - installed modules
	 * @return array
	 */
	public function getModulesInfo($modules_array)
	{
		// --------------------------------------------------------------------------------------------
		// Build modules instances list
		// --------------------------------------------------------------------------------------------
		$modules = array();
		$modules_dir = directory_map(MODULES_PATH);
		foreach ($modules_dir AS $module_dir=>$array) {
			if (is_file(MODULES_PATH . $module_dir . DIRECTORY_SEPARATOR . $module_dir . '.module.php')) {
				include_once(MODULES_PATH . $module_dir . DIRECTORY_SEPARATOR . $module_dir . '.module.php');
				$module_name = $module_dir . 'Module';
				$module_instance = new $module_name;
				$module_instance->active = array_key_exists($module_dir, $modules_array);
				$modules[$module_dir] = $module_instance;
			}
		}
		ksort($modules);
		// --------------------------------------------------------------------------------------------

		// --------------------------------------------------------------------------------------------
		// Look through depending and required modules
		// --------------------------------------------------------------------------------------------
		foreach ($modules AS $module_dir=>$module_instance) {
			if (isset($module_instance->depends_on)) {
				if (!is_array($module_instance->depends_on)) {
					$module_instance->depends_on = array($module_instance->depends_on);
				}
				foreach ($module_instance->depends_on AS $key=>$depends_module_dir) {
					$modules[$depends_module_dir]->required_by[$module_dir] = $module_instance;
					$module_instance->depends_on[$key] = $modules[$depends_module_dir];
				}
			}
		}
		
		return $modules;
	}
	
	public function installModule($module_dir_W2D)
	{
		// --------------------------------------------------------------------------------------------
		// Process installation sql queries and php files
		// --------------------------------------------------------------------------------------------
		$install_path = MODULES_PATH . $module_dir_W2D . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR;
		$install_php_file = $module_dir_W2D . '.install.php';
		$install_sql_file = $module_dir_W2D . '.install.sql';
		if (is_dir($install_path)) {
			if (is_file($install_path . $install_sql_file)) {
				$queries = getQueriesFromFile($install_path . $install_sql_file);
				foreach ($queries AS $query) {
					$this->db->query($query);
				}
			}
			if (is_file($install_path . $install_php_file)) {
				include($install_path . $install_php_file);
			}
		}
		// --------------------------------------------------------------------------------------------

		include_once(MODULES_PATH . $module_dir_W2D . DIRECTORY_SEPARATOR . $module_dir_W2D . '.module.php');
		$module_name = $module_dir_W2D . 'Module';
		$module_instance = new $module_name;
		$module_title_W2D = $module_instance->title;

		// --------------------------------------------------------------------------------------------
		// Insert record into DB
		$this->db->insert('modules', array('dir' => $module_dir_W2D, 'name' => $module_title_W2D));
		registry::add('modules_array', array($module_dir_W2D => $module_title_W2D));
		// --------------------------------------------------------------------------------------------

		// --------------------------------------------------------------------------------------------
		// Work with language files
		// --------------------------------------------------------------------------------------------
		if (key_exists('lang_files', get_object_vars($module_instance))) {
			$lang_files = $module_instance->lang_files;
			if (!is_array($lang_files)) {
				$lang_files = array($lang_files);
			}
			// Add language files records into DB
			foreach ($lang_files AS $file) {
				$this->db->set('module', $module_title_W2D);
				$this->db->set('file', $file);
				$this->db->insert('language_files');
			}
			if ($this->load->is_module_loaded('i18n')) {
				// Provide translation of module's language files
				$CI = &get_instance();
				$CI->load->model('languages', 'i18n');
				$CI->languages->copyI18nFiles(MODULES_PATH . $module_dir_W2D . DIRECTORY_SEPARATOR . 'i18n' . DIRECTORY_SEPARATOR);

				// Add language areas columns into DB structure - get from 'i18n_items_list.php' of new module
				if (is_file(MODULES_PATH . $module_dir_W2D . DIRECTORY_SEPARATOR . 'i18n_items_list.php')) {
					include_once(MODULES_PATH . $module_dir_W2D . DIRECTORY_SEPARATOR . 'i18n_items_list.php');
		
					$languages = $CI->languages->getLanguages();
					foreach ($languages AS $lang) {
						$CI->languages->addLangColumns($lang['db_code'], $i18n_fields);
						$CI->languages->addLangIndexes($lang['db_code'], $i18n_fields);
					}
				}
			}
		}
		// --------------------------------------------------------------------------------------------
	}
	
	public function uninstallModule($module_dir_W2D)
	{
		$install_path = MODULES_PATH . $module_dir_W2D . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR;
		$uninstall_php_file = $module_dir_W2D . '.uninstall.php';
		$uninstall_sql_file = $module_dir_W2D . '.uninstall.sql';
		if (is_dir($install_path)) {
			if (is_file($install_path . $uninstall_php_file)) {
				include($install_path . $uninstall_php_file);
			}
			if (is_file($install_path . $uninstall_sql_file)) {
				$queries = getQueriesFromFile($install_path . $uninstall_sql_file);
				foreach ($queries AS $query) {
					$this->db->query($query);
				}
			}
		}

		// Delete record from DB
		$this->db->delete('modules', array('dir' => $module_dir_W2D));
		
		include_once(MODULES_PATH . $module_dir_W2D . '/' . $module_dir_W2D . '.module.php');
		$module_name = $module_dir_W2D . 'Module';
		$module_instance = new $module_name;
		$module_title_W2D = $module_instance->title;
		
		// Delete module's permissions from users groups permissions list
		if (key_exists('permissions', get_object_vars($module_instance))) {
			if (!is_array($module_instance->permissions))
				$module_instance->permissions = array($module_instance->permissions);
			$this->db->where_in('function_access', $module_instance->permissions);
			$this->db->delete('users_groups_permissions');
		}
		
		// Delete language files records from DB
		if (key_exists('lang_files', get_object_vars($module_instance))) {
			$lang_files = $module_instance->lang_files;
			if (!is_array($lang_files)) {
				$lang_files = array($lang_files);
			}
			foreach ($lang_files AS $file) {
				$this->db->delete('language_files', array('file' => $file));
			}
		}
	}
}
?>