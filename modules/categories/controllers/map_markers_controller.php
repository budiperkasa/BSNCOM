<?php

class map_markersController extends controller
{
    /**
     * select marker icons for category
     *
     * @param int $category_id
     */
    public function select_icons_for_categories($category_id)
    {
    	$this->load->model('categories', 'categories');

    	// --------------------------------------------------------------------------------------------
    	// get all icons 
    	// --------------------------------------------------------------------------------------------
    	$themes = $this->categories->getMapMarkerIconsThemes();
    	foreach ($themes AS $key=>$theme) {
    		$themes[$key]['icons'] = $this->categories->getMapMarkerIcons($theme['folder_name']);
    	}
    	// --------------------------------------------------------------------------------------------

    	$view  = $this->load->view();
    	$view->addCssFile('map_icons.css');
    	$view->addJsFile('map_icons_for_categories.js');
    	$view->assign('themes', $themes);
    	$view->assign('multiple_select', true);
        $view->display('categories/admin_select_icons.tpl');
    }
    
    /**
     * select marker icon for listing's map
     *
     * @param int $categories_list
     * @param int $type_id
     */
    public function select_icons_for_listings($categories_list, $type_id = null)
    {
    	$this->load->model('categories');

    	// --------------------------------------------------------------------------------------------
    	// Extract all icons attached to categories and their parents
    	// --------------------------------------------------------------------------------------------
    	$categories_icons = array();
    	$categories_list = array_filter(explode(',', trim($categories_list, '/')));
    	foreach ($categories_list AS $category_id) {
	    	if ($chain = $this->categories->getCategoryById($category_id)->getChainAsArray())
		    	foreach ($chain AS $category) {
		    		if ($category->selected_icons_serialized)
		    			$categories_icons = array_merge($categories_icons, unserialize($category->selected_icons_serialized));
		    	}
    	}
    	// --------------------------------------------------------------------------------------------

    	// --------------------------------------------------------------------------------------------
    	// build themes->icons tree
    	// --------------------------------------------------------------------------------------------
    	$themes = $this->categories->getMapMarkerIconsThemes();
    	foreach ($themes AS $key=>$theme) {
    		$themes[$key]['icons'] = $this->categories->getMapMarkerIcons($theme['folder_name']);
    		foreach ($themes[$key]['icons'] AS $icon_key=>$icon) {
    			if (!in_array($icon['id'], $categories_icons)) {
    				unset($themes[$key]['icons'][$icon_key]);
    			}
    		}
    	}
    	// --------------------------------------------------------------------------------------------

    	$view  = $this->load->view();
    	$view->addCssFile('map_icons.css');
    	$view->addJsFile('map_icons_for_listings.js');
    	$view->assign('themes', $themes);
    	$view->assign('multiple_select', false);
        $view->display('categories/admin_select_icons.tpl');
    }

    public function is_icons($type_id = null)
    {
    	$categories_list = array_filter(unserialize($this->input->post('categories_list')));
    	$selected_icons = unserialize($this->input->post('selected_icons'));
    	if (!is_array($selected_icons))
    		$selected_icons = array($selected_icons);

    	$this->load->model('categories', 'categories');
    	$result = $this->categories->isIcons($categories_list, $selected_icons);

    	$json = array(
    		"is_icons" => $result['is_icons'],
    		"single_icon" => $result['single_icon'],
    		"single_icon_file" => $result['single_icon_file'],
    		"is_selected_icons" => serialize($result['is_selected_icons'])
		);
		echo json_encode($json);
    }
    
    public function manage_map_icons_themes()
    {
    	$this->load->model('categories', 'categories');

    	$themes = $this->categories->getMapMarkerIconsThemes();

    	if ($this->input->post('submit')) {
    		foreach ($themes AS $theme_item) {
            	$this->form_validation->set_rules($theme_item['folder_name'], LANG_MARKER_ICONS_THEME_NAME, 'required|max_length[255]');
    		}

			if ($this->form_validation->run() !== FALSE) {
				if ($this->categories->saveMapMarkerIconsThemes($this->form_validation->result_array())) {
					$themes = $this->categories->getMapMarkerIconsThemes();
					$this->setSuccess(LANG_MARKER_SAVE_SUCCESS);
				}
			} else {
				foreach ($themes AS $key=>$theme_item) {
					$themes[$key]['name'] = $this->form_validation->set_value($theme_item['folder_name']);
				}
			}
    	} else {
    		$themes_db = $this->categories->getMapMarkerIconsFolders();

	    	$system_settings = registry::get('system_settings');
	    	$theme = $system_settings['design_theme'];
	    	$directory_map = directory_map(ROOT . 'themes/' . $theme . '/map_icons/icons/');

	    	foreach ($directory_map AS $theme_name=>$theme_folder) {
	    		if (in_array($theme_name, $themes_db)) {
	    			unset($themes_db[array_search($theme_name, $themes_db)]);
	    		} else {
	    			$this->categories->insertMapMarkerIconsTheme($theme_name);
	    		}
	    	}
	    	foreach ($themes_db AS $folder_name) {
	    		$this->categories->deleteMapMarkerIconsTheme($folder_name);
	    	}
	    	$themes = $this->categories->getMapMarkerIconsThemes();
    	}
    	
    	$view  = $this->load->view();
    	$view->assign('themes', $themes);
    	$view->display('categories/admin_manage_map_icons_themes.tpl');
    }
    
    public function manage_map_icons($folder_name)
    {
    	$this->load->model('categories', 'categories');

    	$icons = $this->categories->getMapMarkerIcons($folder_name);

    	if ($this->input->post('submit')) {
    		foreach ($icons AS $icon_item) {
            	$this->form_validation->set_rules($icon_item['id'], LANG_MARKER_ICON_NAME, 'required|max_length[255]');
    		}

			if ($this->form_validation->run() !== FALSE) {
				if ($this->categories->saveMapMarkerIcons($folder_name, $this->form_validation->result_array())) {
					$icons = $this->categories->getMapMarkerIcons($folder_name);
					$this->setSuccess(LANG_MARKER_SAVE_SUCCESS);
				}
			} else {
				foreach ($icons AS $key=>$icon_item) {
					$icons[$key]['name'] = $this->form_validation->set_value($icon_item['id']);
				}
			}
    	} else {
    		$icons_db = $this->categories->getMapMarkerIconsFiles($folder_name);

	    	$system_settings = registry::get('system_settings');
	    	$theme = $system_settings['design_theme'];
	    	$directory_map = directory_map(ROOT . 'themes/' . $theme . '/map_icons/icons/' . $folder_name . '/');

	    	foreach ($directory_map AS $file_name) {
	    		if (in_array($file_name, $icons_db)) {
	    			unset($icons_db[array_search($file_name, $icons_db)]);
	    		} else {
	    			$this->categories->insertMapMarkerIcons($folder_name, $file_name);
	    		}
	    	}
	    	foreach ($icons_db AS $file_name) {
	    		$this->categories->deleteMapMarkerIcons($folder_name, $file_name);
	    	}
	    	$icons = $this->categories->getMapMarkerIcons($folder_name);
    	}
    	
    	$theme = $this->categories->getMapMarkerIconsByName($folder_name);
    	
    	registry::set('breadcrumbs', array(
	    	'admin/manage_map_icons_themes/' => LANG_MANAGE_MARKER_ICONS_THEMES_TITLE,
	    	LANG_MANAGE_MARKER_ICONS_TITLE . ' "' . $theme['name'] . '"'
	    ));
    	
    	$view  = $this->load->view();
    	$view->assign('icons', $icons);
    	$view->assign('theme', $theme);
    	$view->display('categories/admin_manage_map_icons.tpl');
    }
}
?>