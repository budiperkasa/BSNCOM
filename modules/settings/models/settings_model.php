<?php
include_once(MODULES_PATH . 'settings/classes/listings_views_set.class.php');

class settingsModel extends Model
{
	private $_level_id;
	private $_cached_titles_templates;
	
	/**
	 * Explores through the themes directory and find all themes
	 *
	 * @return array
	 */
	public function getListOfThemes()
	{
		$this->load->helper('directory');
		
		$themes_map = directory_map(THEMES_PATH);
		
		$themes = array();
		foreach ($themes_map AS $theme_dir=>$val) {
			if (is_dir(THEMES_PATH . $theme_dir))
				$themes[] = $theme_dir;
		}

		return $themes;
	}

	/**
	 * update system settings records
	 *
	 * @param array $keys
	 * @return bool
	 */
	public function saveSystemSettings($results)	
    {
		foreach ($results AS $key=>$value) {
			if ($value !== '') {
				$this->db->set('value', $value);
				$this->db->where('name', $key);
				$this->db->update('system_settings');
    		}
		}
		
		// --------------------------------------------------------------------------------------------
		// Translate language files of new theme
		// --------------------------------------------------------------------------------------------
		$CI = &get_instance();
		if (isset($results['design_theme'])) {
			$theme_name = $results['design_theme'];
			$theme_lang_dir = ROOT . 'themes' . DIRECTORY_SEPARATOR . $theme_name . DIRECTORY_SEPARATOR . 'i18n' . DIRECTORY_SEPARATOR;
			if ($this->load->is_module_loaded('i18n') && is_dir($theme_lang_dir)) {
				$CI->load->model('languages', 'i18n');
				$CI->languages->copyI18nFiles($theme_lang_dir);
				
				$language_files_map = directory_map($theme_lang_dir);
				foreach ($language_files_map AS $file) {
					$this->db->select();
					$this->db->where('theme', $theme_name);
					$this->db->where('file', $file);
					$this->db->from('language_files');
					$query = $this->db->get();
					if (!$query->num_rows()) {
						$this->db->set('theme', $theme_name);
						$this->db->set('file', $file);
						$this->db->insert('language_files');
					}
				}
			}
		}
		// --------------------------------------------------------------------------------------------
		
		// Clean cache
		$CI->cache->clean(Zend_Cache::CLEANING_MODE_ALL);
		
		return true;
	}
	
	/**
	 * update site settings records
	 *
	 * @param object $form
	 * @return bool
	 */
	public function saveSiteSettings($results)	
    {
		foreach ($results AS $key=>$value) {
			if ($key != 'keywords')
				$this->db->set('value', $value);
			else
				$this->db->set('value', str_replace("\n", ", ", $value));
			$this->db->where('name', $key);
			$this->db->update('site_settings');
		}
		
		// Clean cache
		$CI = &get_instance();
		$CI->cache->clean(Zend_Cache::CLEANING_MODE_ALL);

		return true;
	}
	
	public function getSettingsFromForm($form)
	{
		return $form->result;
	}
	
	public function pointDefaultLangFile($selected_language)
	{
		$load_language = new load_language;
		return $load_language->pointDefaultLangFile($selected_language);
	}

    public function getLevelVisibilityFromForm($form)
    {
		$level_visibility = new levelVisibilityClass;

        foreach ($form->result as $key => $value) {
            $level_visibility->$key = $value;
        }

        return $level_visibility;
    }

    public function selectViewsByTypes($types)
    {
    	$this->db->select();
    	$this->db->from('listings_fields_visibility');
    	$query = $this->db->get();
    	$result = $query->result_array();
    	$views = new listingsViewsSet($types, $result);
    	return $views;
    }
    
    public function getViewSettingsByTypeAndPage($type_id, $page_name)
    {
    	$this->db->select();
    	$this->db->from('listings_fields_visibility');
    	$this->db->where('type_id', $type_id);
    	$this->db->where('page_name', $page_name);
    	$query = $this->db->get();
    	if (!$query->row_array()) {
    		if ($page_name == 'index') {
    			return array('view' => 'semitable', 'format' => '3*1');
    		} else {
    			return array('view' => 'full', 'format' => '10');
    		}
    	}

    	return $query->row_array();
    }
    
    public function saveListingsView($type_id, $page_name, $form)
    {
    	$this->db->set('type_id', $type_id);
    	$this->db->set('page_name', $page_name);
    	$this->db->set('view', $form['view']);
    	$this->db->set('format', $form['format']);
    	$this->db->set('order_by', $form['order_by']);
    	$this->db->set('order_direction', $form['order_direction']);
    	if ($type_id)
    		$this->db->set('levels_visible', $form['serialised_levels']);
    	return $this->db->on_duplicate_insert('listings_fields_visibility');
    }
    
    public function setAutoBlockerTimer($block_set_time)
    {
    	$this->db->set('value', mktime() + ($block_set_time));
    	$this->db->where('name', 'auto_blocker_timestamp');
    	return $this->db->update('system_settings');
    }

    public function setTitlesByLevels($titles_by_levels)
    {
    	foreach ($titles_by_levels AS $level_id=>$titles_template) {
    		$this->db->set('titles_template', $titles_template);
    		$this->db->where('id', $level_id);
    		$this->db->update('levels');
    	}
    	return true;
    }
    
    public function synchronizeUsersContent()
    {
    	set_time_limit(0);

    	$CI = &get_instance();
		$CI->load->model('levels', 'types_levels');
		$CI->load->model('users_groups', 'users');
		$this->load->helper('directory');
		$this->load->library('image_lib');
		$system_settings = registry::get('system_settings');

    	$users_content_server_path = $this->config->item('users_content_server_path');
		$users_content_settings = $this->config->item('users_content');

		// --------------------------------------------------------------------------------------------
		// Check all users content folders
		foreach ($users_content_settings AS $content_option) {
			if (isset($content_option['upload_to'])) {
				if (!is_dir($users_content_server_path . $content_option['upload_to']))
					@mkdir($users_content_server_path . $content_option['upload_to']);
			}
			if (isset($content_option['thumbnails'])) {
				foreach ($content_option['thumbnails'] AS $content_option_thumbnails) {
					if (!is_dir($users_content_server_path . $content_option_thumbnails['upload_to']))
						@mkdir($users_content_server_path . $content_option_thumbnails['upload_to']);
				}
			}
		}
		// --------------------------------------------------------------------------------------------

		// --------------------------------------------------------------------------------------------
		$this->db->select('level_id');
		$this->db->select('logo_file');
		$this->db->from('listings');
		$array = $this->db->get()->result_array();
		$logo_files = array();
		foreach ($array AS $listing_row) {
			$logo_file = $listing_row['logo_file'];
			$level = $CI->levels->getLevelById($listing_row['level_id']);
			if ($level && $level->logo_enabled && $logo_file) {
				$destImageSize = array();
				$destImageFolder = array();
				$destImageCrop = array();
				// --------------------------------------------------------------------------------------------
				// This need to re-populate logos folder with new sizes (if needed, of course)
				$users_content_settings['listing_logo_image']['size'] = $level->logo_size;
				$users_content_settings['listing_image']['thumbnails'][] = $users_content_settings['listing_logo_image'];
				
				// --------------------------------------------------------------------------------------------
				foreach ($users_content_settings['listing_image']['thumbnails'] AS $thmb) {
					if (!is_file($users_content_server_path . $thmb['upload_to'] . $logo_file)) {
						if (isset($thmb['size'])) {
							$destImageSize[] = $thmb['size'];
							$destImageFolder[] = $users_content_server_path . $thmb['upload_to'];
							if (isset($thmb['crop']))
								$destImageCrop[] = $thmb['crop'];
							else 
								$destImageCrop[] = false;
						} else {
							$destImageSize[] = $level->images_thumbnail_size;
							$destImageFolder[] = $users_content_server_path . $thmb['upload_to'];
							$destImageCrop[] = false;
						}
					}
				}
				$this->image_lib->process_images('resize', $users_content_server_path . $users_content_settings['listing_image']['upload_to'] . $logo_file, $destImageFolder, $destImageSize, $destImageCrop);
				$logo_files[] = $logo_file;
			}
		}
		
		// Delete unnecessary files from logos folder
		$directory_map = directory_map($users_content_server_path . $users_content_settings['listing_logo_image']['upload_to']);
		$files_to_delete = array_diff($directory_map, $logo_files);
		foreach ($files_to_delete AS $file)
			unlink($users_content_server_path . $users_content_settings['listing_logo_image']['upload_to'] . $file);

		// --------------------------------------------------------------------------------------------
    	// In order to avoid 'MYSQL server has gone away' problem
    	// --------------------------------------------------------------------------------------------
    	$this->db->reconnect();
    	// --------------------------------------------------------------------------------------------
		
		// --------------------------------------------------------------------------------------------
		$this->db->select('file');
		$this->db->from('images');
		$array = $this->db->get()->result_array();
		$image_files = array();
		foreach ($array AS $images_row) {
			$image_file = $images_row['file'];
			$destImageSize = array();
			$destImageFolder = array();
			$destImageCrop = array();
			foreach ($users_content_settings['listing_image']['thumbnails'] AS $thmb) {
				if (!is_file($users_content_server_path . $thmb['upload_to'] . $image_file)) {
					if (isset($thmb['size'])) {
						$destImageSize[] = $thmb['size'];
						$destImageFolder[] = $users_content_server_path . $thmb['upload_to'];
						if (isset($thmb['crop']))
							$destImageCrop[] = $thmb['crop'];
						else 
							$destImageCrop[] = false;
					}
				}
			}
			$this->image_lib->process_images('resize', $users_content_server_path . $users_content_settings['listing_image']['upload_to'] . $image_file, $destImageFolder, $destImageSize, $destImageCrop);
			$image_files[] = $image_file;
		}
		$logo_files = array_merge($logo_files, $image_files);
		// --------------------------------------------------------------------------------------------
		
		// Delete unnecessary files from images folder
		$directory_map = directory_map($users_content_server_path . $users_content_settings['listing_image']['upload_to']);
		$files_to_delete = array_diff($directory_map, $logo_files);
		foreach ($files_to_delete AS $file)
			unlink($users_content_server_path . $users_content_settings['listing_image']['upload_to'] . $file);
		
		// Delete unnecessary files from all images thumbnails folder
		foreach ($users_content_settings['listing_image']['thumbnails'] AS $thmb) {
			$directory_map = directory_map($users_content_server_path . $thmb['upload_to']);
			$files_to_delete = array_diff($directory_map, $logo_files);
			foreach ($files_to_delete AS $file)
				unlink($users_content_server_path . $thmb['upload_to'] . $file);
		}
		// --------------------------------------------------------------------------------------------
		
		// --------------------------------------------------------------------------------------------
		// Delete unnecessary site logos
		$directory_map = directory_map($users_content_server_path . $users_content_settings['site_logo_file']['upload_to']);
		$files_to_delete = array_diff($directory_map, array($system_settings['site_logo_file']));
		foreach ($files_to_delete AS $file)
			unlink($users_content_server_path . $users_content_settings['site_logo_file']['upload_to'] . $file);
		// --------------------------------------------------------------------------------------------

		// --------------------------------------------------------------------------------------------
    	// In order to avoid 'MYSQL server has gone away' problem
    	// --------------------------------------------------------------------------------------------
    	$this->db->reconnect();
    	// --------------------------------------------------------------------------------------------

		// --------------------------------------------------------------------------------------------
		$this->db->select('group_id');
		$this->db->select('user_logo_image');
		$this->db->from('users');
		$array = $this->db->get()->result_array();
		$users_logo_files = array();
		foreach ($array AS $user_row) {
			$logo_file = $user_row['user_logo_image'];
			$users_group = $CI->users_groups->getUsersGroupById($user_row['group_id']);
			if ($users_group->logo_enabled && $logo_file) {
				$destImageSize = array();
				$destImageFolder = array();
				$destImageCrop = array();
				foreach ($users_content_settings['user_logo_image']['thumbnails'] AS $thmb) {
					if (!is_file($users_content_server_path . $thmb['upload_to'] . $logo_file)) {
						if (isset($thmb['size'])) {
							$destImageSize[] = $thmb['size'];
							$destImageFolder[] = $users_content_server_path . $thmb['upload_to'];
							if (isset($thmb['crop']))
								$destImageCrop[] = $thmb['crop'];
							else 
								$destImageCrop[] = false;
						}
					}
				}
				$this->image_lib->process_images('resize', $users_content_server_path . $users_content_settings['user_logo_image']['upload_to'] . $logo_file, $destImageFolder, $destImageSize, $destImageCrop);
				$users_logo_files[] = $logo_file;
			}
		}
		
		// Delete unnecessary files from users logos folder
		$directory_map = directory_map($users_content_server_path . $users_content_settings['user_logo_image']['upload_to']);
		$files_to_delete = array_diff($directory_map, $users_logo_files);
		foreach ($files_to_delete AS $file)
			unlink($users_content_server_path . $users_content_settings['user_logo_image']['upload_to'] . $file);
		
		// Delete unnecessary files from all users logos thumbnails folder
		foreach ($users_content_settings['user_logo_image']['thumbnails'] AS $thmb) {
			$directory_map = directory_map($users_content_server_path . $thmb['upload_to']);
			$files_to_delete = array_diff($directory_map, $users_logo_files);
			foreach ($files_to_delete AS $file)
				unlink($users_content_server_path . $thmb['upload_to'] . $file);
		}
		// --------------------------------------------------------------------------------------------

		// --------------------------------------------------------------------------------------------
    	// In order to avoid 'MYSQL server has gone away' problem
    	// --------------------------------------------------------------------------------------------
    	$this->db->reconnect();
    	// --------------------------------------------------------------------------------------------

		// --------------------------------------------------------------------------------------------
		$this->db->select('file');
		$this->db->from('files');
		$array = $this->db->get()->result_array();
		$files = array();
		foreach ($array AS $file_row) {
			$files[] = basename($file_row['file']);
		}
		$directory_map = directory_map($users_content_server_path . $users_content_settings['listing_file']['upload_to']);
		$files_to_delete = array_diff($directory_map, $files);
		foreach ($files_to_delete AS $file)
			unlink($users_content_server_path . $users_content_settings['listing_file']['upload_to'] . $file);
		// --------------------------------------------------------------------------------------------
		
		// --------------------------------------------------------------------------------------------
		if ($this->load->is_module_loaded('banners')) {
			$this->db->select('banner_file');
			$this->db->from('banners');
			$array = $this->db->get()->result_array();
			$files = array();
			foreach ($array AS $file_row) {
				$files[] = $file_row['banner_file'];
			}
			$directory_map = directory_map($users_content_server_path . $users_content_settings['banner_file']['upload_to']);
			$files_to_delete = array_diff($directory_map, $files);
			foreach ($files_to_delete AS $file)
				unlink($users_content_server_path . $users_content_settings['banner_file']['upload_to'] . $file);
		}
		// --------------------------------------------------------------------------------------------
		
		// --------------------------------------------------------------------------------------------
		$directory_map = directory_map($users_content_server_path . 'tmp/');
		foreach ($directory_map AS $file)
			unlink($users_content_server_path . 'tmp/' . $file);
		// --------------------------------------------------------------------------------------------


		// --------------------------------------------------------------------------------------------
    	// In order to avoid 'MYSQL server has gone away' problem
    	// --------------------------------------------------------------------------------------------
    	$this->db->reconnect();
    	// --------------------------------------------------------------------------------------------
    }
}
?>