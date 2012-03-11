<?php

class i18nController extends controller
{
	public function index()
    {
		$this->load->model('languages');
		$languages = $this->languages->getLanguages();
		
		if ($this->input->post('submit')) {
            $this->form_validation->set_rules('serialized_order', LANG_SERIALIZED_ORDER_VALUE);
            if ($this->form_validation->run() !== FALSE) {
            	$this->languages->setLanguagesOrder($this->form_validation->set_value('serialized_order'));
            	$this->setSuccess(LANG_LANG_ORDER_SAVE_SUCCESS);
            	redirect('admin/languages/');
            }
        }

		$view = $this->load->view(); 
		$view->addJsFile('jquery.tablednd_0_5.js');
		$view->assign('languages', $languages);
		$view->display('i18n/manage_languages.tpl');
    }
    
    /**
     * form validation function
     *
     * @param string $name
     * @return bool
     */
    public function is_unique_name($name)
    {
    	$this->load->model('languages');
		
		if ($this->languages->is_name($name)) {
			$this->form_validation->set_message('name');
			return FALSE;
		} else {
			return TRUE;
		}
    }
    
    /**
     * form validation function
     *
     * @param string $code
     * @return bool
     */
    public function is_unique_code($code)
    {
    	$this->load->model('languages');
		
		if ($this->languages->is_code($code)) {
			$this->form_validation->set_message('code');
			return FALSE;
		} else {
			return TRUE;
		}
    }
    
    /**
     * form validation function
     *
     * @param string $code
     * @return bool
     */
    public function is_unique_db_code($db_code)
    {
    	$this->load->model('languages');
		
		if ($this->languages->is_db_code($db_code)) {
			$this->form_validation->set_message('db_code');
			return FALSE;
		} else {
			return TRUE;
		}
    }
    
    public function language_create()
    {
		$this->load->model('languages');
		$this->load->model('settings', 'settings');
		$themes_list = $this->settings->getListOfThemes();

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('name', LANG_LANG_NAME, 'required|max_length[25]|callback_is_unique_name');
            $this->form_validation->set_rules('code', LANG_LANG_CODE, 'required|max_length[5]|callback_is_unique_code');
            $this->form_validation->set_rules('db_code', LANG_LANG_DB_CODE, 'required|alpha|max_length[2]|callback_is_unique_db_code');
            $this->form_validation->set_rules('flag', LANG_LANG_FLAG, 'selected');
            $this->form_validation->set_rules('custom_theme', LANG_LANG_CUSTOM_THEME, 'selected');
            $this->form_validation->set_rules('decimals_separator', LANG_LANG_DECIMALS_SEPARATOR, 'selected');
            $this->form_validation->set_rules('thousands_separator', LANG_LANG_THOUSANDS_SEPARATOR, 'selected');
            $this->form_validation->set_rules('date_format', LANG_LANG_DATE_FORMAT, 'required|max_length[20]');
            $this->form_validation->set_rules('time_format', LANG_LANG_TIME_FORMAT, 'required|max_length[20]');

            if ($this->form_validation->run() !== FALSE) {
            	if ($this->languages->createLanguageDir($this->form_validation->set_value('code'))) {
            		if ($this->languages->saveLanguage($this->form_validation->result_array())) {
            			$this->setSuccess(LANG_LANG_CREATE_SUCCESS);
						redirect('admin/languages/');
            		}
            	} else {
            		$this->setError(LANG_LANG_DIR_CREATION_ERR);
            	}
			}
            $language = $this->languages->getLanguageFromForm($this->form_validation->result_array());
        } else {
            $language = $this->languages->getNewLanguage();
        }
        $flags_images = $this->languages->getAllFlags();

        registry::set('breadcrumbs', array(
    		'admin/languages/' => LANG_MANAGE_LANGS,
    		LANG_CREATE_LANG,
    	));

		$view = $this->load->view();
		$view->assign('language', $language);
		$view->assign('flags_images', $flags_images);
		$view->assign('themes_list', $themes_list);
        $view->display('i18n/language_settings.tpl');
    }
    
    public function language_edit($language_id)
    {
		$this->load->model('languages');
		$this->load->model('settings', 'settings');
		$themes_list = $this->settings->getListOfThemes();
		$this->languages->setLanguageId($language_id);
		$language = $this->languages->getLanguageById();

        if ($this->input->post('submit')) {
        	$this->form_validation->set_rules('active', LANG_LANG_ACTIVE, 'integer|is_checked');
            $this->form_validation->set_rules('name', LANG_LANG_NAME, 'required|max_length[25]|callback_is_unique_name');
            if ($language->code != 'en') {
            	$this->form_validation->set_rules('code', LANG_LANG_CODE, 'required|max_length[5]|callback_is_unique_code');
            	$this->form_validation->set_rules('db_code', LANG_LANG_DB_CODE, 'required|alpha|max_length[2]|callback_is_unique_db_code');
            }
            $this->form_validation->set_rules('flag', LANG_LANG_FLAG, 'selected');
            $this->form_validation->set_rules('custom_theme', LANG_LANG_CUSTOM_THEME, 'selected');
            $this->form_validation->set_rules('decimals_separator', LANG_LANG_DECIMALS_SEPARATOR, 'selected');
            $this->form_validation->set_rules('thousands_separator', LANG_LANG_THOUSANDS_SEPARATOR, 'selected');
            $this->form_validation->set_rules('date_format', LANG_LANG_DATE_FORMAT, 'required|max_length[20]');
            $this->form_validation->set_rules('time_format', LANG_LANG_TIME_FORMAT, 'required|max_length[20]');

            if ($this->form_validation->run() !== FALSE) {
            	if ($language->code == 'en' || $this->languages->saveLanguageDir($this->form_validation->set_value('code'))) {
            		if ($this->languages->saveLanguageById($this->form_validation->result_array(), $language)) {
            			$this->setSuccess(LANG_LANG_SAVE_SUCCESS);
						redirect('admin/languages/');
            		}
            	} else {
            		$this->setError(LANG_LANG_DIR_CREATION_ERR);
            	}
			}
            $language = $this->languages->getLanguageFromForm($this->form_validation->result_array());
        }

        $flags_images = $this->languages->getAllFlags();

        registry::set('breadcrumbs', array(
    		'admin/languages/' => LANG_MANAGE_LANGS,
    		LANG_EDIT_LANG . ' "' . $language->name . '"',
    	));

		$view = $this->load->view();
		$view->assign('language', $language);
		$view->assign('flags_images', $flags_images);
		$view->assign('themes_list', $themes_list);
        $view->display('i18n/language_settings.tpl');
    }

    public function language_delete($language_id)
    {
    	$this->load->model('languages');

        $this->languages->setLanguageId($language_id);

        if ($this->input->post('yes')) {
        	if ($this->languages->deleteLanguageDir()) {
            	if ($this->languages->deleteLanguageById()) {
	            	$this->setSuccess(LANG_LANG_DELETE_SUCCESS);

	            	// Necessarily redirect to default language
	                $system_settings = registry::get('system_settings');
	                $default_language = $system_settings['default_language'];
	                redirect(base_url() . $this->config->slash_item('index_page') . 'lang/' . $default_language . '/admin/languages/');
            	}
        	}
        }

        if ($this->input->post('no')) {
            redirect('admin/languages/');
        }

        if ( !$language = $this->languages->getLanguageById()) {
            redirect('admin/languages/');
        }
        
        registry::set('breadcrumbs', array(
    		'admin/languages/' => LANG_MANAGE_LANGS,
    		LANG_DELETE_LANG . ' "' . $language->name . '"',
    	));

		$view  = $this->load->view();
		$view->assign('options', array($language_id => $language->name));
        $view->assign('heading', LANG_DELETE_LANG);
        $view->assign('question', LANG_DELETE_LANG_QUEST);
        $view->display('backend/delete_common_item.tpl');
    }

	public function languages_translate($language_id)
	{
		$this->load->model('languages');

        $this->languages->setLanguageId($language_id);

        $language = $this->languages->getLanguageById();
        $files_list = $this->languages->getLanguageFilesList();

        registry::set('breadcrumbs', array(
    		'admin/languages/' => LANG_MANAGE_LANGS,
    		LANG_TRANSLATE_INTERFACE . ' "' . $language->name . '"',
    	));
        
        $view = $this->load->view();
        $view->assign('files_list', $files_list);
        $view->assign('language_id', $language_id);
        $view->assign('language', $language);
        $view->display('i18n/language_translate.tpl');
	}
	
	public function languages_translate_search_text($language_id)
	{
	 	if ($this->input->post('submit')) {
	 		$this->load->model('languages');
	 		$this->languages->setLanguageId($language_id);
        	$language = $this->languages->getLanguageById();

        	$system_settings = registry::get('system_settings');
	        $default_language_code = $system_settings['default_language'];

        	$view = $this->load->view();
	 		
        	$this->form_validation->set_rules('search_text', LANG_LANG_SEARCH_TEXT, 'required');
        	if ($this->form_validation->run() !== FALSE) {
        		$search_text = $this->form_validation->set_value('search_text');

        		$files_list = $this->languages->getLanguageFilesList();
        		
        		$language_strings = array();
        		foreach ($files_list AS $file) {
        			if ($file_strings = $this->languages->includeLanguageFile($language->code, $file['file']))
        				$language_strings = array_merge($language_strings, $file_strings);
        		}
        		foreach ($language_strings AS $key=>$string) {
        			if (mb_stripos($string, $search_text) === FALSE && $key != $search_text)
        				unset($language_strings[$key]);
        		}
        		$view->assign('language_strings', $language_strings);
        	} else {
        		$search_text = $this->form_validation->set_value('search_text');
        	}
        	registry::set('breadcrumbs', array(
	    		'admin/languages/' => LANG_MANAGE_LANGS,
	    		'admin/languages/translate/' . $language_id => LANG_TRANSLATE_INTERFACE . ' "' . $language->name . '"',
	    		LANG_LANG_TRANSLATE_CONSTANTS
	    	));
	    	
	    	if ($language->code != $default_language_code) {
	    		$default_language_strings = array();
	    		foreach ($files_list AS $file) {
        			if ($file_strings = $this->languages->includeLanguageFile($default_language_code, $file['file']))
        				$default_language_strings = array_merge($default_language_strings, $file_strings);
        		}
	    		foreach ($default_language_strings AS $key=>$string) {
	    			if (!isset($language_strings[$key]))
	    				unset($default_language_strings[$key]);
	    		}
	    		$view->assign('default_language_code', $default_language_code);
	        	$view->assign('default_language_strings', $default_language_strings);
	    	}

        	$view->assign('language', $language);
        	$view->assign('language_id', $language_id);
        	$view->assign('language_code', $language->code);
        	$view->assign('search_text', $search_text);
	        $view->display('i18n/language_search_text.tpl');
        } else 
        	redirect('admin/languages/translate/' . $language_id);
	}
	
	public function languages_translate_file($language_id, $file_id = null)
	{
		$this->load->model('languages');

        $this->languages->setLanguageId($language_id);

        if ($file_id)
        	$file_names = $this->languages->getFileNameById($file_id);
        else 
        	$file_names = $this->languages->getLanguageFilesList();
        $language = $this->languages->getLanguageById();
        
        if ($this->input->post('submit')) {
        	if ($this->languages->saveLanguageConstantsIntoFile($language->code, $file_names, $_POST))
        		$this->setSuccess('Constants were translated successfully!');
        	redirect('admin/languages/translate/' . $language_id);
        }
        
        if (is_string($file_names)) {
	        $system_settings = registry::get('system_settings');
	        $default_language_code = $system_settings['default_language'];
	        
	        $language_strings = $this->languages->includeLanguageFile($language->code, $file_names);
	
	        registry::set('breadcrumbs', array(
	    		'admin/languages/' => LANG_MANAGE_LANGS,
	    		'admin/languages/translate/' . $language_id => LANG_TRANSLATE_INTERFACE . ' "' . $language->name . '"',
	    		LANG_LANG_TRANSLATE_CONSTANTS
	    	));
	        
	        $view = $this->load->view();
	        if ($language->code != $default_language_code) {
				$default_language_strings = $this->languages->includeLanguageFile($default_language_code, $file_names);
				$view->assign('default_language_strings', $default_language_strings);
			}
	        $view->assign('file', $file_names);
	        $view->assign('language_strings', $language_strings);
	        $view->assign('language_code', $language->code);
	        $view->assign('default_language_code', $default_language_code);
	        $view->assign('language', $language);
	        $view->display('i18n/language_translate_file.tpl');
		} elseif (is_array($file_names))
        	redirect('admin/languages/translate/' . $language_id);
	}
	
	public function translate_block($table, $field, $row_id, $hash, $field_type = null, $virtual_id = 0)
    {
    	// prevent hack access to translatable content
    	if ($hash != md5($table.$field.$row_id.$field_type.registry::get('secret'))) {
			show_error('401 Access denied!', '');
		}

		if (is_null($field_type) || $field_type == '') {
			$field_type = 'string';
		}

		$system_settings = registry::get('system_settings');
		$default_language_code = $system_settings['default_language'];

		$this->load->model('languages');
		$languages = $this->languages->getLanguages();

		$view = $this->load->view();
		$view->assign('current_language', registry::get('current_language'));
		$view->assign('languages', $languages);
		$view->assign('table', $table);
		$view->assign('field', $field);
		$view->assign('row_id', $row_id);
		$view->assign('field_type', $field_type);
		$view->assign('virtual_id', $virtual_id);
		$view->assign('hash', md5($table.$field.$row_id.$field_type.registry::get('secret')));
		$view->display('i18n/choose_translate_dialog.tpl');
    }
    
    public function translate_window($table, $field, $row_id, $hash, $field_type, $virtual_id, $db_lang_code)
    {
    	// prevent hack access to translatable content
    	if ($hash != md5($table.$field.$row_id.$field_type.registry::get('secret'))) {
			show_error('401 Access denied!', '');
		}

		$this->load->model('languages');
		if ($value = $this->input->post('translated_input')) {
			if ($field_type == 'keywords')
    			$value = str_replace("\n", ", ", $value);

			if ($this->languages->setLangAreasField($db_lang_code, $value, $table, $field, $row_id, $virtual_id)) {
				$translated = true;
			} else {
				$translated = false;
			}
		} else {
			$translated = false;
		}

		$view = $this->load->view();
		$language = $this->languages->getLanguageByDBCode($db_lang_code);
		if (($lang_area_value = $this->languages->getLangAreaField($db_lang_code, $table, $field, $row_id, $virtual_id)) !== false) {
			if ($field_type == 'keywords')
    			$lang_area_value = str_replace(", ", "\n", $lang_area_value);

			$view->assign('language', $language);
			$view->assign('field_type', $field_type);
			$view->assign('row_id', $row_id);
			$view->assign('translated', $translated);
			$view->assign('lang_area_value', $lang_area_value);
			if ($field_type == 'richtext') {
				include_once(BASEPATH . 'richtext_editor/richtextEditor.php');
				$richtext = new richtextEditor('translated_input' , $lang_area_value);
				$view->assign('richtext', $richtext->createHtml());
			}
			$view->removeJsFile('admin_menu.js');
			$view->addJsFile('jquery.timers-1.1.2.js');
			$view->display('i18n/translate_window.tpl');
		} else {
			echo LANG_CONTENT_TRANSLATION_ERROR;
		}
	}
	
	public function ajax_get_translation_link($table, $field, $row_id, $field_type, $virtual_id)
	{
		echo translations::getTranslationLink($table, $field, $row_id, $field_type, $virtual_id);
	}

	public function i18n_settings()
	{
		$system_settings = registry::get('system_settings');
		
		$this->load->model('settings', 'settings');
		$this->load->model('languages', 'i18n');
		$languages_list = $this->languages->getLanguages();
		
		if ($this->input->post('submit')) {
			$this->form_validation->set_rules('default_language', LANG_DEFUALT_LANGUAGE, 'selected');
			$this->form_validation->set_rules('multilanguage_enabled', LANG_ENABLE_MULTILANGUAGE, 'is_checked');
			$this->form_validation->set_rules('language_areas_enabled', LANG_ENABLE_LANG_AREAS, 'is_checked');
			
			if ($this->form_validation->run() !== FALSE) {
				if ($this->settings->saveSystemSettings($this->form_validation->result_array())) {
					$this->setSuccess(LANG_SYSTEM_SETTING_SAVE_SUCCESS);
					redirect('admin/i18n_settings/');
				}
			}
			$default_language = $this->form_validation->set_value('default_language');
			$multilanguage_enabled = $this->form_validation->set_value('multilanguage_enabled');
			$language_areas_enabled = $this->form_validation->set_value('language_areas_enabled');
		} else {
			$default_language = $system_settings['default_language'];
			$multilanguage_enabled = $system_settings['multilanguage_enabled'];
			$language_areas_enabled = $system_settings['language_areas_enabled'];
		}
	
		$view = $this->load->view();
		$view->assign('languages_list', $languages_list);
		$view->assign('default_language', $default_language);
		$view->assign('multilanguage_enabled', $multilanguage_enabled);
		$view->assign('language_areas_enabled', $language_areas_enabled);
		$view->display('i18n/admin_i18n_settings.tpl');
	}
}
?>