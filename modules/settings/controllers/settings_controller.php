<?php
include_once(MODULES_PATH . 'ajax_files_upload/classes/files_upload.class.php');

class settingsController extends Controller
{
	public function __construct($components)
	{
		// Disable global XSS filtering for titles templates management function
		$this->config = &load_class('Config');
		$this->config->set_item('global_xss_filtering', FALSE);

		parent::Controller($components);
	}
	
	/**
	 * Check is there not more that one type in the system
	 *
	 * @param boolean $mode
	 * @return boolean
	 */
	public function check_types_single_mode($mode)
	{
		if ($mode === '1') {
			$this->load->model('types', 'types_levels');
			$types = $this->types->getTypesLevels();
			if (count($types) > 1) {
				$this->form_validation->set_message('check_types_single_mode', LANG_TYPES_SINGLE_MODE_ERROR);
				return false;
			} elseif (count($types) == 1) {
				$one_type = array_shift($types);
				if ($one_type->search_type == 'local' || $one_type->categories_type == 'local') {
					$this->form_validation->set_message('check_types_single_mode', LANG_TYPES_SINGLE_MODE_ERROR);
					return false;
				}
			}
		}
		return true;
	}
	
    public function index()
    {
	    $this->load->model('settings');
	    $themes_list = $this->settings->getListOfThemes();

	    $system_settings = registry::get('system_settings');
	    
	    $view = $this->load->view();
	    
	    $file_to_upload = new filesUpload;
	    $file_to_upload->title = LANG_WEBSITE_LOGO;
	    $file_to_upload->upload_id = 'site_logo_file';
	    if ($system_settings['site_logo_file'])
	    	$file_to_upload->current_file = $system_settings['site_logo_file'];
	    $file_to_upload->after_upload_url = site_url('admin/settings/get_site_logo');
	    $file_to_upload->attrs['width'] = SITE_LOGO_WIDTH;
	    $file_to_upload->attrs['height'] = SITE_LOGO_HEIGHT;

	    if ($this->input->post('submit')) {
			$this->form_validation->set_rules('site_logo_file', LANG_WEBSITE_LOGO);
			$this->form_validation->set_rules('website_email', LANG_WEBSITE_EMAIL, 'required|valid_email');
			$this->form_validation->set_rules('design_theme', LANG_SELECT_THEME, 'selected');
			$this->form_validation->set_rules('global_what_search', LANG_ENABLE_WHAT_SEARCH, 'is_checked');
			$this->form_validation->set_rules('global_where_search', LANG_ENABLE_WHERE_SEARCH, 'is_checked');
			$this->form_validation->set_rules('global_categories_search', LANG_ENABLE_CATEGORIES_SEARCH, 'is_checked');
			$this->form_validation->set_rules('search_in_raduis_measure', LANG_DEFAULT_SEARCH_IN_RADIUS_MEASURE, 'required');
			$this->form_validation->set_rules('anonym_rates_reviews', LANG_ENABLE_ANONYM_RATINGS_REVIEWS, 'is_checked');
			$this->form_validation->set_rules('categories_block_type', LANG_CATEGORIES_BLOCK_VIEW, 'required');
			//$this->form_validation->set_rules('def_listings_order', LANG_DEFAULT_LISTINGS_ORDER, 'required');
			//$this->form_validation->set_rules('def_listings_order_direction', LANG_DEFAULT_LISTINGS_ORDER_DIRECTION, 'required');
			$this->form_validation->set_rules('single_type_structure', LANG_SETTINGS_TYPE_STRUCTURE, 'required|callback_check_types_single_mode');
			$this->form_validation->set_rules('enable_contactus_page', LANG_ENABLE_CONTACTUS_PAGE, 'is_checked');
			$this->form_validation->set_rules('path_to_terms_and_conditions', LANG_PATH_TO_TERMS_CONDITIONS, 'max_length[255]');

			if ($this->form_validation->run() !== FALSE) {
				if ($this->settings->saveSystemSettings($this->form_validation->result_array())) {
					if ($this->form_validation->set_value('single_type_structure')) {
						$this->load->model('types', 'types_levels');
						if ($types = $this->types->getTypesLevels())
							$this->types->saveTypeByIdWhenSingleStructure(array_pop($types));
					}

					$this->setSuccess(LANG_SYSTEM_SETTING_SAVE_SUCCESS);
					redirect('admin/settings/');
				}
			} else {
				$file_to_upload->current_file = $this->input->post('site_logo_file');
				$view->assign('system_settings', $this->form_validation->result_array());
			}
	    } elseif ($this->input->post('clear_cache')) {
	    	$this->load->helper('file');
	    	// Delete all files in smarty compiled templates folder and cache folder
	    	delete_files(BASEPATH . 'view/smarty/templates_c/', true);
	    	delete_files(BASEPATH . 'cache/');
	    	$this->setSuccess(LANG_SETTINGS_CACHED_CLEARED_SUCCESS);
	    	redirect('admin/settings/');
	    } elseif ($this->input->post('synchronize_users_content')) {
	    	$this->settings->synchronizeUsersContent();
			
	    	$this->setSuccess(LANG_SYNCHRONIZE_USERS_CONTENT_SUCCESS);
	    	redirect('admin/settings/');
	    }
	    $view->assign('system_settings', $system_settings);
	    
	    $upload_block = $file_to_upload->setUploadBlock('files_upload/site_logo_upload_block.tpl');

	    $view->addJsFile('ajaxfileupload.js');
	    $view->assign('image_upload_block', $upload_block);
	    $view->assign('themes_list', $themes_list);
		$view->display('settings/admin_system_settings.tpl');
    }
    
    public function site()
    {
    	$this->load->model('settings');
    	$site_settings = registry::get('site_settings');
    	$view = $this->load->view();

	    if ($this->input->post('submit')) {
			$this->form_validation->set_rules('website_title', LANG_WEBSITE_TITLE, 'required|max_length[255]');
			$this->form_validation->set_rules('description', LANG_WEBSITE_DESCRIPTION, 'max_length[1000]');
			$this->form_validation->set_rules('keywords', LANG_WEBSITE_KEYWORDS, 'max_length[1000]');
			$this->form_validation->set_rules('signature_in_emails', LANG_EMAILS_SIGNATURE);
			if ($this->load->is_module_loaded('payment'))
				$this->form_validation->set_rules('company_details', LANG_COMPANY_DETAILS, 'max_length[1000]');

			if ($this->form_validation->run() !== FALSE) {
				if ($this->settings->saveSiteSettings($this->form_validation->result_array())) {
					$this->setSuccess(LANG_SITE_SETTINGS_SAVE_SUCCESS);
					redirect('admin/settings/site/');
				}
			} else {
				$view->assign('site_settings', $this->form_validation->result_array());
				$view->assign('keywords', $this->input->post('keywords'));
			}
	    } else {
	    	$view->assign('site_settings', $site_settings);
	    	$view->assign('keywords', str_replace(", ", "\n", $site_settings['keywords']));
	    }

		$view->display('settings/admin_site_settings.tpl');
    }
    
    public function get_site_logo()
    {
    	if ($this->input->post('uploaded_file')) {
    		$uploaded_file = $this->input->post('uploaded_file');
    		$users_content_server_path = $this->config->item('users_content_server_path');
    		$users_content_array = $this->config->item('users_content');
    		$site_logo_path_to_upload = $users_content_array['site_logo_file']['upload_to'];

			$this->load->library('image_lib');
			$destImageSize[] = SITE_LOGO_WIDTH . '*' . SITE_LOGO_HEIGHT;
			$destImageFolder[] = $users_content_server_path . $site_logo_path_to_upload;

			if ($this->image_lib->process_images('resize', $uploaded_file, $destImageFolder, $destImageSize)) {
				$file = $uploaded_file;
				$error = '';
			} else {
				$error = $this->image_lib->display_errors();
				$file = '';
			}

			// JQuery .post needs JSON response
			echo json_encode(array('error_msg' => _utf8_encode($error), 'file_name' => basename($file)));
		}
	}

	public function services()
    {
    	if ($this->input->post('submit')) {
			$this->form_validation->set_rules('youtube_key', LANG_YOUTUBE_KEY, 'max_length[255]');
			$this->form_validation->set_rules('youtube_username', LANG_YOUTUBE_USERNAME, 'max_length[255]');
			$this->form_validation->set_rules('youtube_password', LANG_YOUTUBE_PASSWORD, 'max_length[255]');
			$this->form_validation->set_rules('youtube_product_name', LANG_YOUTUBE_PRODUCT, 'max_length[255]');
			$this->form_validation->set_rules('google_analytics_account_id', LANG_ANALYTICS_ACCOUNT_ID, 'max_length[15]');
			$this->form_validation->set_rules('google_analytics_profile_id', LANG_ANALYTICS_PROFILE_ID, 'integer');
			$this->form_validation->set_rules('google_analytics_email', LANG_ANALYTICS_EMAIL, 'valid_email');
			$this->form_validation->set_rules('google_analytics_password', LANG_ANALYTICS_PASSWORD, 'max_length[255]');
			$this->form_validation->set_rules('mollom_public_key', LANG_MOLLOM_PUBLIC_KEY, 'max_length[255]');
			$this->form_validation->set_rules('mollom_private_key', LANG_MOLLOM_PRIVATE_KEY, 'max_length[255]');

			if ($this->form_validation->run() !== FALSE) {
				$this->load->model('settings');
				if ($this->settings->saveSystemSettings($this->form_validation->result_array())) {
					$this->setSuccess(LANG_WEBSERVICES_SAVE_SUCCESS);
					redirect('admin/settings/services/');
				}
			} else {
				$system_settings = $this->form_validation->result_array();
			}
    	} else {
    		$system_settings = registry::get('system_settings');
    	}
    	$view = new view;
	    
	    $view->assign('system_settings', $system_settings);
		$view->display('settings/admin_webservices_settings.tpl');
    }
    
    public function frontend()
    {
    	$this->load->model('settings');
    	$this->load->model('types', 'types_levels');
    	$types = $this->types->getTypesLevels();
    	
    	$views = $this->settings->selectViewsByTypes($types);

        $view  = $this->load->view();
        $view->assign('types', $types);
        $view->assign('views', $views);
        $view->display('settings/admin_frontend_settings.tpl');
    }
    
    public function frontend_configure($page_key, $type_id = 0)
    {
    	$this->load->model('settings');
    	$this->load->model('types', 'types_levels');
    	$types = $this->types->getTypesLevels();
    	$type = $this->types->getTypeById($type_id);
    	$views = $this->settings->selectViewsByTypes($types);
    	$listings_view = $views->getViewByTypeIdAndPage($type_id, $page_key);

    	if ($this->input->post('submit')) {
    		$this->form_validation->set_rules('view', LANG_LISTING_VIEW_TH, 'required');
    		$this->form_validation->set_rules('format', LANG_LISTING_FORMAT, 'required');
    		$this->form_validation->set_rules('order_by', LANG_DEFAULT_LISTINGS_ORDER, 'required');
    		$this->form_validation->set_rules('order_direction', LANG_DEFAULT_LISTINGS_ORDER_DIRECTION, 'required');
    		if ($type_id)
    			$this->form_validation->set_rules('serialised_levels', LANG_LISTING_LEVELS_VISIBLE);

    		if ($this->form_validation->run() !== FALSE) {
    			if ($this->settings->saveListingsView($type_id, $page_key, $this->form_validation->result_array())) {
    				// Clean cache
					$this->cache->clean(Zend_Cache::CLEANING_MODE_ALL);

    				$this->setSuccess(LANG_FRONTEND_SETTINGS_SAVE_SUCCESS);
    				redirect('admin/settings/frontend/');
    			}
    		}
    	}

    	$view  = $this->load->view();
    	if ($type) {
    		$view->assign('type_name', $type->name);
	        registry::set('breadcrumbs', array(
		    	'admin/settings/frontend/' => LANG_FRONTEND_SETTINGS_TITLE,
		    	LANG_EDIT_LISTINGS_VIEW_1 . ' "' . $type->name . '" ' . LANG_EDIT_LISTINGS_VIEW_2 . ' "' . $listings_view->page_name . '"',
		    ));
    	} else {
    		registry::set('breadcrumbs', array(
		    	'admin/settings/frontend/' => LANG_FRONTEND_SETTINGS_TITLE,
		    	LANG_FRONTEND_SETTINGS_PAGE . ' "' . $listings_view->page_name . '"',
		    ));
    	}

        $view->assign('type', $type);
        $view->assign('listings_view', $listings_view);
        $view->display('settings/admin_frontend_configure.tpl');
    }

    public function titles()
    {
    	$this->load->model('settings');
    	$this->load->model('types', 'types_levels');
    	$types = $this->types->getTypesLevels();

    	if ($this->input->post('submit')) {
    		$this->form_validation->set_rules('titles[]', LANG_TEMPLATES, 'required|max_length[255]');
    		
    		if ($this->form_validation->run() !== FALSE) {
    			$titles_by_levels = $this->form_validation->set_value('titles[]');
    			if ($this->settings->setTitlesByLevels($titles_by_levels)) {
    				// Clean cache
					$this->cache->clean(Zend_Cache::CLEANING_MODE_ALL);

    				$this->setSuccess(LANG_TITLES_TEMPLATE_SAVE_SUCCESS);
    				redirect('admin/settings/titles/');
    			}
    		}
    	}
    	
    	$view  = $this->load->view();
        $view->assign('types', $types);
        $view->display('settings/admin_titles_templates.tpl');
    }
}
?>