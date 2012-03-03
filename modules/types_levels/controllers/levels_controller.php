<?php
define('MAX_LOGO_WIDTH', 308);
define('MAX_LOGO_HEIGHT', 400);

class levelsController extends Controller
{
	public function levels_of_type($type_id)
    {
		$this->load->model('types');
		$this->load->model('levels');

		$this->types->setTypeId($type_id);
		$this->levels->setTypeId($type_id);
		$type = $this->types->getTypeById();
		
		if ($this->input->post('submit')) {
            $this->form_validation->set_rules('serialized_order', LANG_SERIALIZED_ORDER_VALUE);
            if ($this->form_validation->run() !== FALSE) {
            	$this->load->model('levels');
            	if ($this->levels->setLevelsOrder($this->form_validation->set_value('serialized_order'))) {
            		// Clean cache
					$this->cache->clean(Zend_Cache::CLEANING_MODE_ALL);

            		$this->setSuccess(LANG_LEVELS_ORDER_SAVE_SUCCESS);
            		redirect('admin/levels/type_id/' . $type_id .'/');
            	}
            }
        }

		$levels = $this->levels->getLevelsOfType();

		registry::set('breadcrumbs', array(
    		'admin/types/' => LANG_LISTINGS_TYPES,
    		LANG_LEVELS_OF_TYPE . ' "' . $type->name . '"',
    	));

		$view = $this->load->view();
		$view->addJsFile('jquery.tablednd_0_5.js');
		$view->assign('levels', $levels);
		$view->assign('type_id', $type_id);
		$view->assign('type', $type);
        $view->display('types_levels/admin_levels.tpl');
    }
    
    /**
     * form validation function
     *
     * @param string $name
     * @return bool
     */
    public function is_unique_level_name($name)
    {
    	$this->load->model('levels');
		
		if ($this->levels->is_level_name($name)) {
			$this->form_validation->set_message('name');
			return FALSE;
		} else {
			return TRUE;
		}
    }

    public function level_create($type_id)
    {
    	$this->load->model('levels');
    	$this->load->model('types');

		$this->levels->setTypeId($type_id);
		$this->types->setTypeId($type_id);
		$type = $this->types->getTypeById();

        if ($this->input->post('submit')) {
			$this->form_validation->set_rules('name', LANG_LEVEL_NAME, 'required|max_length[60]|callback_is_unique_level_name');

			$this->form_validation->set_rules('active_years', LANG_YEARS, 'integer');
			$this->form_validation->set_rules('active_months', LANG_MONTHS, 'integer');
			$this->form_validation->set_rules('active_days', LANG_DAYS, 'integer');
			$this->form_validation->set_rules('eternal_active_period', LANG_ETERNAL, 'is_checked');
			$this->form_validation->set_rules('allow_to_edit_active_period', LANG_ALLOW_TO_EDIT_ACTIVE_PERIOD_OF_LISTING, 'is_checked');

            $this->form_validation->set_rules('featured', LANG_LEVEL_FEATURED, 'is_checked');
            
            $this->form_validation->set_rules('title_enabled', LANG_LEVEL_TITLE_ENABLED, 'is_checked');
            $this->form_validation->set_rules('seo_title_enabled', LANG_LEVEL_SEO_TITLE_ENABLED, 'is_checked');
            $this->form_validation->set_rules('meta_enabled', LANG_LEVEL_META_ENABLED, 'is_checked');
            
            $this->form_validation->set_rules('description_mode', LANG_LEVEL_LISTING_DESCRIPTION, 'required');
            $required = ($_POST['description_mode'] == 'enabled') ? '|required' : '';
            $this->form_validation->set_rules('description_length', LANG_LEVEL_LISTING_DESCRIPTION_LENGTH, 'is_natural_no_zero' . $required);
            
            if ($type->categories_type != 'disabled')
				$this->form_validation->set_rules('categories_number', LANG_LEVEL_CATEGORIES_NUMBER, 'is_natural_no_zero');
			if ($type->locations_enabled)
				$this->form_validation->set_rules('locations_number', LANG_LEVEL_LOCATIONS_NUMBER, 'is_natural_no_zero');

			$this->form_validation->set_rules('preapproved_mode', LANG_TYPE_MODERATION, 'is_checked');

			$this->form_validation->set_rules('logo_enabled', LANG_LEVEL_ENABLE_LOGO, 'is_checked');
			$required = (isset($_POST['logo_enabled'])) ? 'is_natural_no_zero|required' : '';
			$this->form_validation->set_rules('logo_width', LANG_LOGO_WIDTH, 'max_integer[' . MAX_LOGO_WIDTH . ']' . $required);
			$this->form_validation->set_rules('logo_height', LANG_LOGO_HEIGHT, 'max_integer[' . MAX_LOGO_HEIGHT . ']' . $required);

			$this->form_validation->set_rules('images_count', LANG_LEVEL_IMAGES_COUNT, 'integer');
			$this->form_validation->set_rules('images_width', LANG_IMAGES_WIDTH, 'integer');
			$this->form_validation->set_rules('images_height', LANG_IMAGES_HEIGHT, 'integer');
			$this->form_validation->set_rules('images_thumbnail_width', LANG_THMBS_WIDTH, 'integer');
			$this->form_validation->set_rules('images_thumbnail_height', LANG_THMBS_HEIGHT, 'integer');

			$this->form_validation->set_rules('video_count', LANG_LEVEL_VIDEOS_COUNT, 'integer');
			$required = (isset($_POST['video_count']) && $_POST['video_count']) ? 'integer|required' : '';
			$this->form_validation->set_rules('video_width', LANG_VIDEOS_WIDTH, $required);
			$this->form_validation->set_rules('video_height', LANG_VIDEOS_HEIGHT, $required);

			$this->form_validation->set_rules('files_count', LANG_LEVEL_FILES_COUNT, 'integer');

			if ($type->locations_enabled) {
				$this->form_validation->set_rules('maps_enabled', LANG_LEVEL_MAPS, 'is_checked');
				$required = (isset($_POST['maps_enabled'])) ? 'is_natural_no_zero|required' : '';
				$this->form_validation->set_rules('maps_width', LANG_MAPS_WIDTH, $required);
				$this->form_validation->set_rules('maps_height', LANG_MAPS_HEIGHT, $required);
			}

			$this->form_validation->set_rules('option_print', LANG_LEVEL_PRINT_OPTION, 'is_checked');
			$this->form_validation->set_rules('option_pdf', LANG_LEVEL_PDF_OPTION, 'is_checked');
			$this->form_validation->set_rules('option_quick_list', LANG_LEVEL_QUICKLIST_OPTION, 'is_checked');
			$this->form_validation->set_rules('option_email_friend', LANG_LEVEL_EMAILFRIEND_OPTION, 'is_checked');
			$this->form_validation->set_rules('option_email_owner', LANG_LEVEL_EMAILOWNER_OPTION, 'is_checked');
			$this->form_validation->set_rules('option_report', LANG_LEVEL_REPORT_OPTION, 'is_checked');

			$this->form_validation->set_rules('social_bookmarks_enabled', LANG_LEVEL_SOCIAL, 'is_checked');

			$this->form_validation->set_rules('ratings_enabled', LANG_RATINGS_ENABLED, 'is_checked');
			$this->form_validation->set_rules('reviews_mode', LANG_REVIEWS_MODE, 'required');
			$is_checked = ($_POST['reviews_mode'] != 'disabled') ? 'is_checked' : '';
			$this->form_validation->set_rules('reviews_richtext_enabled', LANG_REVIEWS_RICHTEXT_ENABLED, $is_checked);

            if ($this->form_validation->run() !== FALSE) {
            	$form_result = $this->form_validation->result_array();
				if ($this->levels->saveLevel($type, $form_result)) {
					// Clean cache
					$this->cache->clean(Zend_Cache::CLEANING_MODE_ALL);

					$this->setSuccess(LANG_LEVEL_CREATE_SUCCESS_1 . ' "' . $form_result['name'] . '" ' . LANG_LEVEL_CREATE_SUCCESS_2);
					redirect('admin/levels/type_id/' . $type_id . '/');
				}
            } else {
            	$level = $this->levels->getLevelFromForm($this->form_validation->result_array());
            }
        } else {
            $level = $this->levels->getNewLevel();
        }

        registry::set('breadcrumbs', array(
    		'admin/types/' => LANG_LISTINGS_TYPES,
    		'admin/levels/type_id/' . $type_id => LANG_LEVELS_OF_TYPE . ' "' . $type->name . '"',
    		LANG_CREATE_LEVEL . ' "' . $type->name . '"',
    	));
        
        $view  = $this->load->view();
        $view->assign('level', $level);
        $view->assign('type_id', $type_id);
        $view->assign('type', $type);
        $view->display('types_levels/admin_level_settings.tpl');
    }
    
    public function level_edit($level_id)
    {
        $this->load->model('levels');
        $this->load->model('types');

		$this->levels->setLevelId($level_id);
		$level = $this->levels->getLevelById();

		$this->types->setTypeId($level->type_id);
		$type = $this->types->getTypeById();

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('name', LANG_LEVEL_NAME, 'required|max_length[60]|callback_is_unique_level_name');

			$this->form_validation->set_rules('active_years', LANG_YEARS, 'integer');
			$this->form_validation->set_rules('active_months', LANG_MONTHS, 'integer');
			$this->form_validation->set_rules('active_days', LANG_DAYS, 'integer');
			$this->form_validation->set_rules('eternal_active_period', LANG_ETERNAL, 'is_checked');
			$this->form_validation->set_rules('allow_to_edit_active_period', LANG_ALLOW_TO_EDIT_ACTIVE_PERIOD_OF_LISTING, 'is_checked');

            $this->form_validation->set_rules('featured', LANG_LEVEL_FEATURED, 'is_checked');

            $this->form_validation->set_rules('title_enabled', LANG_LEVEL_TITLE_ENABLED, 'is_checked');
            $this->form_validation->set_rules('seo_title_enabled', LANG_LEVEL_SEO_TITLE_ENABLED, 'is_checked');
            $this->form_validation->set_rules('meta_enabled', LANG_LEVEL_META_ENABLED, 'is_checked');

            $this->form_validation->set_rules('description_mode', LANG_LEVEL_LISTING_DESCRIPTION, 'required');
            $required = ($_POST['description_mode'] == 'enabled') ? '|required' : '';
            $this->form_validation->set_rules('description_length', LANG_LEVEL_LISTING_DESCRIPTION_LENGTH, 'is_natural_no_zero' . $required);
            
            if ($type->categories_type != 'disabled')
				$this->form_validation->set_rules('categories_number', LANG_LEVEL_CATEGORIES_NUMBER, 'is_natural_no_zero');
			if ($type->locations_enabled)
				$this->form_validation->set_rules('locations_number', LANG_LEVEL_LOCATIONS_NUMBER, 'is_natural_no_zero');

			$this->form_validation->set_rules('preapproved_mode', LANG_TYPE_MODERATION, 'is_checked');

			$this->form_validation->set_rules('logo_enabled', LANG_LEVEL_ENABLE_LOGO, 'is_checked');
			$required = (isset($_POST['logo_enabled'])) ? 'is_natural_no_zero|required' : '';
			$this->form_validation->set_rules('logo_width', LANG_LOGO_WIDTH, 'max_integer[' . MAX_LOGO_WIDTH . ']' . $required);
			$this->form_validation->set_rules('logo_height', LANG_LOGO_HEIGHT, 'max_integer[' . MAX_LOGO_HEIGHT . ']' . $required);

			$this->form_validation->set_rules('images_count', LANG_LEVEL_IMAGES_COUNT, 'integer');
			$this->form_validation->set_rules('images_width', LANG_IMAGES_WIDTH, 'integer');
			$this->form_validation->set_rules('images_height', LANG_IMAGES_HEIGHT, 'integer');
			$this->form_validation->set_rules('images_thumbnail_width', LANG_THMBS_WIDTH, 'integer');
			$this->form_validation->set_rules('images_thumbnail_height', LANG_THMBS_HEIGHT, 'integer');

			$this->form_validation->set_rules('video_count', LANG_LEVEL_VIDEOS_COUNT, 'integer');
			$required = (isset($_POST['video_count']) && $_POST['video_count']) ? 'integer|required' : '';
			$this->form_validation->set_rules('video_width', LANG_VIDEOS_WIDTH, $required);
			$this->form_validation->set_rules('video_height', LANG_VIDEOS_HEIGHT, $required);

			$this->form_validation->set_rules('files_count', LANG_LEVEL_FILES_COUNT, 'integer');

			if ($type->locations_enabled) {
				$this->form_validation->set_rules('maps_enabled', LANG_LEVEL_MAPS, 'is_checked');
				$required = (isset($_POST['maps_enabled'])) ? 'is_natural_no_zero|required' : '';
				$this->form_validation->set_rules('maps_width', LANG_MAPS_WIDTH, $required);
				$this->form_validation->set_rules('maps_height', LANG_MAPS_HEIGHT, $required);
			}

			$this->form_validation->set_rules('option_print', LANG_LEVEL_PRINT_OPTION, 'is_checked');
			$this->form_validation->set_rules('option_pdf', LANG_LEVEL_PDF_OPTION, 'is_checked');
			$this->form_validation->set_rules('option_quick_list', LANG_LEVEL_QUICKLIST_OPTION, 'is_checked');
			$this->form_validation->set_rules('option_email_friend', LANG_LEVEL_EMAILFRIEND_OPTION, 'is_checked');
			$this->form_validation->set_rules('option_email_owner', LANG_LEVEL_EMAILOWNER_OPTION, 'is_checked');
			$this->form_validation->set_rules('option_report', LANG_LEVEL_REPORT_OPTION, 'is_checked');

			$this->form_validation->set_rules('social_bookmarks_enabled', LANG_LEVEL_SOCIAL, 'is_checked');
			
			$this->form_validation->set_rules('ratings_enabled', LANG_RATINGS_ENABLED, 'is_checked');
			$this->form_validation->set_rules('reviews_mode', LANG_REVIEWS_MODE, 'required');
			$is_checked = ($_POST['reviews_mode'] != 'disabled') ? 'is_checked' : '';
			$this->form_validation->set_rules('reviews_richtext_enabled', LANG_REVIEWS_RICHTEXT_ENABLED, $is_checked);

            if ($this->form_validation->run() !== FALSE) {
            	$form_result = $this->form_validation->result_array();
				if ($this->levels->saveLevelById($type, $form_result)) {
					// Clean cache
					$this->cache->clean(Zend_Cache::CLEANING_MODE_ALL);

					$this->setSuccess(LANG_LEVEL_SAVE_SUCCESS_1 . ' "' . $form_result['name'] . '" ' . LANG_LEVEL_SAVE_SUCCESS_2);
					redirect('admin/levels/type_id/' . $type->id . '/');
				}
            } else {
            	$level = $this->levels->getLevelFromForm($this->form_validation->result_array());
            }
        }

        registry::set('breadcrumbs', array(
    		'admin/types/' => LANG_LISTINGS_TYPES,
    		'admin/levels/type_id/' . $level->type_id => LANG_LEVELS_OF_TYPE . ' "' . $type->name . '"',
    		LANG_EDIT_LEVEL_1 .  ' "' . $level->name . '" ' . LANG_EDIT_LEVEL_2 . ' "' . $type->name . '"',
    	));
    	
    	$custom_group_id = $this->levels->getContentGroupIdByLevelId();

        $view  = $this->load->view();
        $view->assign('level', $level);
        $view->assign('type', $type);
        $view->assign('type_id', $type->id);
        $view->assign('custom_group_id', $custom_group_id);
        $view->display('types_levels/admin_level_settings.tpl');
    }

    public function level_delete($level_id)
    {
    	$this->load->model('levels');
    	$this->load->model('types');

        $this->levels->setLevelId($level_id);
        $level = $this->levels->getLevelById();

		$this->types->setTypeId($level->type_id);
		$type = $this->types->getTypeById();

        if ($this->input->post('yes')) {
            if ($this->levels->deleteLevelById()) {
            	// Clean cache
				$this->cache->clean(Zend_Cache::CLEANING_MODE_ALL);

            	$this->setSuccess(LANG_LEVEL_DELETE_SUCCESS);
                redirect('admin/levels/type_id/' . $type->id . '/');
            }
        }

        if ($this->input->post('no')) {
            redirect('admin/levels/type_id/' . $type->id . '/');
        }

        if ( !$level) {
            redirect('admin/levels/type_id/' . $type->id . '/');
        }
        
        registry::set('breadcrumbs', array(
    		'admin/types/' => LANG_LISTINGS_TYPES,
    		'admin/levels/type_id/' . $type->id => LANG_LEVELS_OF_TYPE . ' "' . $type->name . '"',
    		LANG_DELETE_LEVEL . ' "' . $level->name . '"',
    	));

		$view  = $this->load->view();
		$view->assign('options', array($level_id => $level->name));
        $view->assign('heading', LANG_DELETE_LEVEL . ' "' . $level->name . '"');
        $view->assign('question', LANG_DELETE_LEVEL_QUEST);
        $view->display('backend/delete_common_item.tpl');
    }
}
?>