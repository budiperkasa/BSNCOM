<?php
include_once(MODULES_PATH . 'i18n/classes/language.class.php');

class languagesModel extends Model 
{
	private $_language_id;

	public function getLanguages()
	{
		$this->db->select();
		$this->db->from('languages');
		$this->db->orderBy('order_num');
		$query = $this->db->get();
		return $query->result_array();
	}
	
	public function setLanguageId($language_id)
	{
		$this->_language_id = $language_id;
	}

	public function setLanguagesOrder($serialized_order)
    {
    	$a = explode("=", $serialized_order);
    	$i = 1;
    	foreach ($a AS $row) {
    		$b = explode("&", $row);
    		foreach ($b AS $id) {
    			$id = trim($id, "_id");
    			if (is_numeric($id)) {
    				$this->db->set('order_num', $i++);
    				$this->db->where('id', $id);
    				$this->db->update('languages');
    			}
    		}
    	}
    }
    
    /**
     * is there language with such name in the DB?
     *
     * @param string $name
     */
    public function is_name($name)
    {
    	$this->db->select();
		$this->db->from('languages');
		$this->db->where('name', $name);
		if (!is_null($this->_language_id)) {
			$this->db->where('id !=', $this->_language_id);
		}
		$query = $this->db->get();

		return $query->num_rows();
    }
    
    /**
     * is there language with such code in the DB?
     *
     * @param string $name
     */
    public function is_code($code)
    {
    	$this->db->select();
		$this->db->from('languages');
		$this->db->where('code', $code);
		if (!is_null($this->_language_id)) {
			$this->db->where('id !=', $this->_language_id);
		}
		$query = $this->db->get();

		return $query->num_rows();
    }
    
    /**
     * is there language with such db code?
     *
     * @param string $name
     */
    public function is_db_code($db_code)
    {
    	$this->db->select();
		$this->db->from('languages');
		$this->db->where('db_code', $db_code);
		if (!is_null($this->_language_id)) {
			$this->db->where('id !=', $this->_language_id);
		}
		$query = $this->db->get();

		return $query->num_rows();
    }
    
    public function saveLanguage($form)
	{
		$this->db->select_max('order_num');
    	$query = $this->db->get('languages');
    	if ($row = $query->row())
    		$order_num = $row->order_num + 1;
    	else 
    		$order_num = 1;

    	$this->db->set('active', 1);
		$this->db->set('name', $form['name']);
		$this->db->set('code', $form['code']);
		$this->db->set('db_code', $form['db_code']);
		$this->db->set('flag', $form['flag']);
		$this->db->set('custom_theme', $form['custom_theme']);
		$this->db->set('decimals_separator', $form['decimals_separator']);
		$this->db->set('thousands_separator', $form['thousands_separator']);
		$this->db->set('date_format', $form['date_format']);
		$this->db->set('time_format', $form['time_format']);
		$this->db->set('order_num', $order_num);
		if ($this->db->insert('languages')) {
			$this->addLangColumns($form['db_code']);
			$this->addLangIndexes($form['db_code']);
			return true;
		}
	}
	
	public function getLanguageFromForm($form)
	{
		$language = new language;
        $language->setLanguageFromArray($form);
        return $language;
	}
	
	public function getNewLanguage()
	{
		$language = new language;
        return $language;
	}
	
	public function getAllFlags()
	{
		$system_settings = registry::get('system_settings');
		$flags = array();
		$path = 'themes' . DIRECTORY_SEPARATOR . $system_settings['design_theme'] . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'flags' .DIRECTORY_SEPARATOR;

		$this->load->helper('directory');
		$map = directory_map($path);
		if (is_array($map))
			foreach ($map as $file) {
				$flags[] = $file;
			}
		sort($flags);
		return $flags;
	}
	
	public function createLanguageDir($code)
	{
		$this->load->helper('directory');
		$system_settings = registry::get('system_settings');
        $default_language_code = 'en';

        $default_dir = LANGPATH . $default_language_code;
		$new_dir = LANGPATH . $code;

		@mkdir($new_dir);
		$map = directory_map($default_dir);
		foreach ($map as $file) {
			$this->copyLanguageFile($default_language_code, $code, $file);
		}

		// + we need to copy i18n files from all installed modules and themes
		$modules_array = registry::get('modules_array');
		foreach ($modules_array AS $dir=>$name) {
			$this->copyI18nFiles(MODULES_PATH . $dir . DIRECTORY_SEPARATOR . 'i18n' . DIRECTORY_SEPARATOR);
		}
		
		//$language_files_array = registry::get('language_files_array');
		/*foreach ($language_files_array['modules'] AS $module_name=>$module_files_array) {
			foreach ($modules_array AS $dir=>$name) {
				if ($name == $module_name) {
					$module_dir = MODULES_PATH . $dir . DIRECTORY_SEPARATOR;
					break;
				}
			}
			if (isset($module_dir)) { 
				foreach ($module_files_array AS $file_name) {
					$this->copyLanguageFileFromModuleTheme($default_language_code, $code, $module_dir . $file_name);
				}
				unset($module_dir);
			}
		}*/

		return true;
	}

	/**
	 * Find all language files in the source folder and copy them to 'languages' folder,
	 * used for modules and themes installation,
	 * source folder must have such format:
	 * - en:
	 * - - en_lang_file_1.php
	 * - - en_lang_file_2.php
	 * - es: 
	 * - - es_lang_file_1.php
	 * - - es_lang_file_2.php
	 * 
	 * @param string $source_folder
	 */
	public function copyI18nFiles($source_folder, $rewrite_if_exists = false)
	{
		if (is_dir($source_folder)) {
			$i18n_dir_map = directory_map($source_folder);
			$languages_folders = directory_map(LANGPATH);
			$languages_codes = array_merge(array_keys($languages_folders), explode('|', DEFAULT_LANGS));

			foreach ($languages_codes AS $lang_code) {
		    	// If site has special languages, those are not in updates folder - place updates contstants from english
				if (array_key_exists($lang_code, $i18n_dir_map))
					$update_lang_code = $lang_code;
				else
					$update_lang_code = 'en';
				$update_folder = $source_folder . $update_lang_code . DIRECTORY_SEPARATOR;
				foreach ($i18n_dir_map[$update_lang_code] as $file) {
					$update_file = $update_folder . $file;
					$dest_file = LANGPATH . $lang_code . DIRECTORY_SEPARATOR . $file;
					if ($rewrite_if_exists || !is_file($dest_file)) {
						@copy($update_file, $dest_file);
						@chmod($dest_file, 0777);
					}
				}
			}
			// --------------------------------------------------------------------------------------------
	    	// In order to avoid 'MYSQL server has gone away' problem
	    	// --------------------------------------------------------------------------------------------
	    	$this->db->reconnect();
	    	// --------------------------------------------------------------------------------------------
		}
	}
    
	/**
	 * copy language file from default language code 'languages' folder to destination 'languages' folder (if there isn't such file yet)
	 * 
	 * @param string $default_language_code
	 * @param string $language_code
	 * @param string $file_name
	 */
    public function copyLanguageFile($default_language_code, $language_code, $file_name)
    {
    	$dest_file = LANGPATH . $language_code . DIRECTORY_SEPARATOR . $file_name;
    	if (is_file($dest_file) || (!is_file($dest_file) && @copy(LANGPATH . $default_language_code . DIRECTORY_SEPARATOR . $file_name, $dest_file) && @chmod($dest_file, 0777)))
    		return true;
    	else 
    		return false;
    }

    public function getLanguageById()
	{
		$this->db->select();
		$this->db->from('languages');
		$this->db->where('id', $this->_language_id);
		$query = $this->db->get();
		
		$language = new language;
		$language->setLanguageFromArray($query->row_array());
        return $language;
	}
	
	public function saveLanguageById($form, $current_language)
	{
		$this->db->select('db_code');
		$this->db->from('languages');
		$this->db->where('id', $this->_language_id);
		$query = $this->db->get();
		$row = $query->row_array();
		$old_code = $row['db_code'];
		
    	$this->db->set('active', $form['active']);
		$this->db->set('name', $form['name']);
		if ($current_language->code != 'en') {
			$this->db->set('code', $form['code']);
			$this->db->set('db_code', $form['db_code']);
		}
		$this->db->set('flag', $form['flag']);
		$this->db->set('custom_theme', $form['custom_theme']);
		$this->db->set('decimals_separator', $form['decimals_separator']);
		$this->db->set('thousands_separator', $form['thousands_separator']);
		$this->db->set('date_format', $form['date_format']);
		$this->db->set('time_format', $form['time_format']);
		$this->db->where('id', $this->_language_id);
		if ($this->db->update('languages')) {
			if ($current_language->code != 'en')
				return $this->renameLangColumns($old_code, $form['db_code']);
			return true;
		}
	}
	
	public function saveLanguageDir($new_code)
	{
		$this->db->select('code');
		$this->db->from('languages');
		$this->db->where('id', $this->_language_id);
		$query = $this->db->get();
		
		$row = $query->row_array();
		$old_code = $row['code'];
		
		return rename(LANGPATH . $old_code, LANGPATH . $new_code);
	}
	
	public function deleteLanguageDir()
	{
		$this->db->select('code');
		$this->db->from('languages');
		$this->db->where('id', $this->_language_id);
		$query = $this->db->get();
		
		if ($row = $query->row_array()) {
			$code = $row['code'];
			// we will not delete default languages folders
			if (!array_key_exists($code, explode('|', DEFAULT_LANGS))) {
				$this->load->helper('directory');
				if ($map = directory_map(LANGPATH . $code)) {
					foreach ($map as $file) {
						unlink(LANGPATH . $code . '/' . $file);
					}
					return rmdir(LANGPATH . $code);
				} else {
					return true;
				}
			}
			return true;
		}
		return false;
	}
	
	public function deleteLanguageById()
    {
    	$this->db->select('code');
		$this->db->from('languages');
		$this->db->where('id', $this->_language_id);
		$query = $this->db->get();
		if ($row = $query->row_array()) {
			$old_code = $row['code'];
	    	
	        $this->db->where('id', $this->_language_id);
	        if ($this->db->delete('languages')) {
	        	return $this->deleteLangColumns($old_code);
	        }
		}
    }
    
    public function getLanguageFilesList()
    {
    	$this->db->select();
		$this->db->from('language_files');
		$this->db->orderby('module');
		$this->db->orderby('theme');
		$query = $this->db->get();
		
		$lang_files = array();
		foreach ($query->result_array() AS $row) {
			$lang_files[] = $row;
		}
		return $lang_files;
    }
    
    public function getFileNameById($file_id)
    {
    	$this->db->select();
		$this->db->from('language_files');
		$this->db->where('id', $file_id);
		$query = $this->db->get();
		
		$row = $query->row_array();
		return $row['file'];
    }
    
    public function includeLanguageFile($language_code, $file_name)
    {
		/*include(LANGPATH . $language_code . DIRECTORY_SEPARATOR . $file_name);
		return $language;*/
    	return $this->get_include_contents(LANGPATH . $language_code . DIRECTORY_SEPARATOR . $file_name);
    }
    
	function get_include_contents($filename) {
		if (is_file($filename)) {
			ob_start();
			include $filename;
			if (isset($language))
				return $language;
			else 
				return false;
			ob_get_clean();
		}
		return false;
	}
    
    public function saveLanguageConstantsIntoFile($language_code, $files_list, $post_values)
    {
    	if (is_string($files_list)) {
    		$file_path = LANGPATH . $language_code . '/' . $files_list;
    		$translation_constants = file_get_contents($file_path);
	    	foreach ($post_values AS $const_index=>$translated_const) {
	    		// replace " -  symbol from constants
	    		$translated_const = str_replace('"', "'", $translated_const);
	    		// empty constant must contain space
	    		if (empty($translated_const))
	    			$translated_const = ' ';
	    		$translation_constants = preg_replace("/language\['$const_index'\] = \"(.*)\";/", "language['$const_index'] = \"$translated_const\";", $translation_constants);
	    	}
	    	return file_put_contents($file_path, $translation_constants);
    	} elseif (is_array($files_list)) {
    		$language_strings = array();
        	foreach ($files_list AS $file) {
        		$file_name = $file['file'];
        		$file_path = LANGPATH . $language_code . '/' . $file_name;

	    		$translation_constants = file_get_contents($file_path);
		    	foreach ($post_values AS $const_index=>$translated_const) {
		    		// replace " -  symbol from constants
		    		$translated_const = str_replace('"', "'", $translated_const);
		    		// empty constant must contain space
		    		if (empty($translated_const))
		    			$translated_const = ' ';
		    		$translation_constants = preg_replace("/language\['$const_index'\] = \"(.*)\";/", "language['$const_index'] = \"$translated_const\";", $translation_constants);
		    	}
		    	file_put_contents($file_path, $translation_constants);
        	}
        	return true;
    	}
    }
    
    public function getLanguageByCode($code)
	{
		$this->db->select();
		$this->db->from('languages');
		$this->db->where('code', $code);
		$query = $this->db->get();
		
		$language = new language;
		$language->setLanguageFromArray($query->row_array());
        return $language;
	}
	
	public function getLanguageByDBCode($db_code)
	{
		$this->db->select();
		$this->db->from('languages');
		$this->db->where('db_code', $db_code);
		$query = $this->db->get();
		
		$language = new language;
		$language->setLanguageFromArray($query->row_array());
        return $language;
	}
	
	public function langAreasSwitchOn()
    {
    	$system_settings = registry::get('system_settings');
	    $system_settings['language_areas_enabled'] = 1;
	    registry::set('system_settings', $system_settings);
    }
    
    public function langAreasSwitchOff()
    {
    	$system_settings = registry::get('system_settings');
	    $system_settings['language_areas_enabled'] = 0;
	    registry::set('system_settings', $system_settings);
    }
	
	public function getLangAreaField($lang_code, $table, $field, $row_id, $virtual_id)
    {
    	/*$this->db->execSql('SHOW COLUMNS FROM `' . $table . '` WHERE field = "' . $field . '"');
		$field_props = $this->db->rowAsArray();
    	if (strpos($field_props['Type'], 'varchar') !== false)
    		$field_type = 'varchar';
    	if (strpos($field_props['Type'], 'textarea') !== false)
    		$field_type = 'textarea';*/

		if ($row_id == 'new') {
			if ($this->session->userdata('translation_array')) {
				$translation_array = unserialize(base64_decode($this->session->userdata('translation_array')));
				if (isset($translation_array[$table][$field][$virtual_id][$lang_code]))
					return $translation_array[$table][$field][$virtual_id][$lang_code];
				else
					return 'untranslated';
			}
			return 'untranslated';
		} else {
			$this->langAreasSwitchOff();

			if ($lang_code == 'en') {
				if (!$this->config->item('english_db_code') || $this->config->item('english_db_code') === "") {
					$this->db->select($field . ' AS lang_value');
				} else {
					$this->db->select($field . ' AS original_value');
					$this->db->select($this->config->item('english_db_code') . '_' . $field . ' AS lang_value');
				}
			} else {
				$this->db->select($field . ' AS original_value');
		    	$this->db->select($lang_code . '_' . $field . ' AS lang_value');
			}
			
		    /*if ($lang_code != $this->config->item('default_language')) {
		    	$this->db->select($field . ' AS original_value');
		    	$this->db->select($lang_code . '_' . $field . ' AS lang_value');
		    } else {
		    	$this->db->select($field . ' AS lang_value');
		    }*/
		    $this->db->from($table);
		    $this->db->where('id', $row_id);
		    $query = $this->db->get();

		    $this->langAreasSwitchOn();

		    if ($query->num_rows()) {
				$lang_areas_fields = $query->row_array();
				return $lang_areas_fields['lang_value'];
			} else {
				return false;
			}
		}
    }
    
    public function setLangAreasField($lang_code, $value, $table, $field, $row_id, $virtual_id = 0)
    {
    	if ($row_id == 'new') {
    		// Save translated value in session
    		if ($this->session->userdata('translation_array')) {
				$translation_array = unserialize(base64_decode($this->session->userdata('translation_array')));
    		}
			$translation_array[$table][$field][$virtual_id][$lang_code] = $value;
			$this->session->set_userdata(array('translation_array' => base64_encode(serialize($translation_array))));
			return true;
    	} else {
    		// Or in database
    		$this->langAreasSwitchOff();
    		
    		if ($lang_code == 'en') {
				if (!$this->config->item('english_db_code') || $this->config->item('english_db_code') === "") {
					//$this->db->select($field . ' AS lang_value');
					$this->db->set($field, $value);
				} else {
					//$this->db->select($field . ' AS original_value');
					//$this->db->select($this->config->item('english_db_code') . '_' . $field . ' AS lang_value');
					$this->db->set($this->config->item('english_db_code') . '_' . $field, $value);
				}
			} else {
				//$this->db->select($field . ' AS original_value');
		    	//$this->db->select($lang_code . '_' . $field . ' AS lang_value');
		    	$this->db->set($lang_code . '_' . $field, $value);
			}
    		
    		/*if ($lang_code != $this->config->item('default_language')) {
	    		$lang_field = $lang_code . '_' . $field;
    		} else {
    			$lang_field = $field;
    		}*/

			//$this->db->set($field, $value);
	    	$this->db->where('id', $row_id);
	    	$result = $this->db->update($table);

	    	$this->langAreasSwitchOn();

	    	return $result;
    	}
    }
    
    public function addLangColumns($code, $i18n_fields = null)
	{
		if (is_null($i18n_fields)) {
			$i18n_fields = registry::get('i18n_fields');
		}
		
		$tables = $this->db->list_tables();
		foreach ($i18n_fields AS $table=>$fields) {
			if (!in_array($table, $tables)) {
				unset($i18n_fields[$table]);
			}
		}

		foreach ($i18n_fields AS $table=>$fields) {
			foreach ($fields AS $field) {
				$new_field = $code . '_' . $field;
				$query = $this->db->query('SHOW COLUMNS FROM `' . $table . '` WHERE field = "' . $new_field . '"');
				if (!count($query->row_array())) {
					$query = $this->db->query('SHOW COLUMNS FROM `' . $table . '` WHERE field = "' . $field . '"');
					$field_props = $query->row_array();
					
					if ($field_props['Type'] == 'text') {
						$default = '';
					} else {
						$default = 'DEFAULT "untranslated" NOT NULL';
					}

					$this->db->query('ALTER TABLE `' . $table . '` ADD ' . $new_field . ' ' . $field_props['Type'] . ' ' . $default);
				}
			}
		}
		return true;
	}
	
	public function addLangIndexes($code, $i18n_fields = null)
	{
		if (is_null($i18n_fields)) {
			$i18n_fields = registry::get('i18n_fields');
		}
		
		$tables = $this->db->list_tables();
		foreach ($i18n_fields AS $table=>$fields) {
			if (!in_array($table, $tables)) {
				unset($i18n_fields[$table]);
			}
		}

		foreach ($i18n_fields AS $table=>$fields) {
			$indexes_array = array();
			foreach ($fields AS $field) {
				$new_field = $code . '_' . $field;
				$query = $this->db->query('SHOW INDEXES FROM `' . $table . '` WHERE column_name = "' . $field . '"');
				if (count($query->row_array())) {
					$query = $this->db->query('SHOW INDEXES FROM `' . $table . '` WHERE column_name = "' . $field . '"');
					$result_array = $query->result_array();
					foreach ($result_array AS $row) {
						$indexes_array[$row['Key_name']][$row['Index_type']][] = $row['Column_name'];
					}
				}
			}

			foreach ($indexes_array AS $index=>$column_array) {
				foreach ($column_array AS $index_type=>$columns) {
					$new_columns = array();
					foreach ($columns AS $column)
						$new_columns[] = '`' . $code . '_' . $column . '`';
					if ($row['Index_type'] == 'FULLTEXT')
						$this->db->query('ALTER TABLE `' . $table . '` ADD FULLTEXT (' . implode(',', $new_columns) . ')');
					if ($row['Index_type'] == 'BTREE')
						$this->db->query('ALTER TABLE `' . $table . '` ADD INDEX (' . implode(',', $new_columns) . ')');
					if ($row['Index_type'] == 'UNIQUE')
						$this->db->query('ALTER TABLE `' . $table . '` ADD UNIQUE (' . implode(',', $new_columns) . ')');
				}
			}
		}
		return true;
	}
	
	public function renameLangColumns($old_code, $new_code)
	{
		$i18n_fields = registry::get('i18n_fields');
		
		foreach ($i18n_fields AS $table=>$fields) {
			foreach ($fields AS $field) {
				$query = $this->db->query('SHOW COLUMNS FROM `' . $table . '` WHERE field = "' . $field . '"');
				$field_props = $query->row_array();
				
				if ($field_props['Type'] == 'text') {
					$default = '';
				} else {
					$default = 'DEFAULT "untranslated" NOT NULL';
				}
				
				$old_field = $old_code . '_' . $field;
				$new_field = $new_code . '_' . $field;
				$this->db->query('ALTER TABLE `' . $table . '` CHANGE ' . $old_field . ' ' . $new_field .' ' . $field_props['Type'] . ' ' . $default);
			}
		}
		return true;
	}
	
	public function deleteLangColumns($code)
	{
		$i18n_fields = registry::get('i18n_fields');

		$this->load->dbforge();

		foreach ($i18n_fields AS $table=>$fields) {
			$fields = array_unique($fields);
			foreach ($fields AS $field) {
				$delete_field = $code . '_' . $field;
				if ($this->db->field_exists($delete_field, $table)) {
					$this->dbforge->drop_column($table, $delete_field);
				}
			}
		}
		return true;
	}
}
?>