<?php
include_once(MODULES_PATH . 'listings/classes/listing.class.php');
include_once(MODULES_PATH . 'acl/classes/content_acl.class.php');
include_once(MODULES_PATH . 'notifications/classes/notification_sender.class.php');
include_once(MODULES_PATH . 'ajax_files_upload/classes/files_upload.class.php');

include_once(MODULES_PATH . 'content_fields/classes/field.class.php');
include_once(MODULES_PATH . 'content_fields/classes/content_fields.class.php');
include_once(MODULES_PATH . 'content_fields/classes/search_content_fields.class.php');
include_once(MODULES_PATH . 'ratings_reviews/classes/reviews_block.class.php');
include_once(MODULES_PATH . 'google_maps/classes/listings_markers.class.php');

class listingsController extends controller
{
	public function search($argsString = '')
	{
		//$this->output->enable_profiler(TRUE);
		
		$args = parseUrlArgs($argsString);

		// Search url needs for 'asc_desc_insert.base_url' argument of smarty function
		$clean_url = site_url('admin/listings/search/');
		$base_url = $clean_url;
		$search_url = $base_url;
		if (isset($args['what_search'])) {
			$what_search = $args['what_search'];
			$search_url .= 'what_search/' . $what_search . '/';
		}
		if (isset($args['what_match'])) {
			$what_match = $args['what_match'];
			$search_url .= 'what_match/' . $what_match . '/';
		}
		if (isset($args['where_search'])) {
			$where_search = $args['where_search'];
			$search_url .= 'where_search/' . $where_search . '/';
		}
		if (isset($args['where_radius'])) {
			//$geocoder = new geocodeWhereSearch($args);
			
			$where_radius = $args['where_radius'];
			$search_url .= 'where_radius/' . $where_radius . '/';
		}
		if (isset($args['search_claimed_listings'])) {
			$search_claimed_listings = $args['search_claimed_listings'];
			$search_url .= 'search_claimed_listings/' . $search_claimed_listings . '/';
		}
		
		if (isset($args['search_type_id'])) {
			$search_type_id = $args['search_type_id'];
			$search_url .= 'search_type_id/' . $search_type_id . '/';
			$base_url .= 'search_type_id/' . $search_type_id . '/';
			
			$this->load->model('types', 'types_levels');
			if (!($current_type = $this->types->getTypeById($search_type_id))) {
				return ;
			}
			if ($current_type->search_type == 'global')
				$search_custom_group_id = 0;
			else
				$search_custom_group_id = $search_type_id;
		} else {
			$search_custom_group_id = 0;
			$current_type = null;
		}
		
		if (isset($args['use_advanced'])) {
			$use_advanced = true;
			$search_url .= 'use_advanced/true/';
		} else {
			$use_advanced = false;
		}

		if (isset($args['search_category'])) {
			$search_category = $args['search_category'];
			$search_url .= 'search_category/' . $search_category . '/';
			
			$categories_ids = array_filter(explode(',', urldecode(html_entity_decode($args['search_category']))));
			array_walk($categories_ids, "trim");
		}
		if (isset($args['search_owner'])) {
			$search_owner = $args['search_owner'];
			$search_url .= 'search_owner/' . $search_owner . '/';
		}
		if (isset($args['search_status'])) {
			$search_status = $args['search_status'];
			$search_url .= 'search_status/' . $search_status . '/';
		}
		if (isset($args["search_creation_date"])) {
			$search_creation_date = $args['search_creation_date'];
			$search_url .= 'search_creation_date/' . $search_creation_date . '/';
		}
		if (isset($args['search_from_creation_date'])) {
			$search_from_creation_date = $args['search_from_creation_date'];
			$search_url .= 'search_from_creation_date/' . $search_from_creation_date . '/';
		}
		if (isset($args['search_to_creation_date'])) {
			$search_to_creation_date = $args['search_to_creation_date'];
			$search_url .= 'search_to_creation_date/' . $search_to_creation_date . '/';
		}

		// Build search content fields block
		if ($search_custom_group_id == 0) {
			$group_name = GLOBAL_SEARCH_GROUP_CUSTOM_NAME;
		} else {
			$group_name = LOCAL_SEARCH_GROUP_CUSTOM_NAME;
		}
		$search_fields = new searchContentFields($group_name, $search_custom_group_id);
		// Advanced fields object needs in order to decide - render 'Advanced search' button or not
		$advanced_search_fields = new searchContentFields($group_name, $search_custom_group_id, 'advanced');
		$search_sql_array = $search_fields->validateSearch(LISTINGS_LEVEL_GROUP_CUSTOM_NAME, $args, $use_advanced, $search_url_rebuild);
		$search_url .= $search_url_rebuild;

		// Paginator url needs for '.../page/x/' url modification
		$paginator_url = $search_url;
		if (isset($args['orderby'])) {
			$orderby = $args['orderby'];
			$paginator_url .= 'orderby/' . $args['orderby'] . '/';
		} else {
			$orderby = 'id';
		}
		if (isset($args['direction'])) {
			$direction = $args['direction'];
			$paginator_url .= 'direction/' . $args['direction'] . '/';
		} else {
			$direction = 'desc';
		}
		$paginator = new pagination(array('args' => $args, 'url' => $paginator_url, 'num_per_page' => 10));
		$this->load->model('listings');
		$this->listings->setPaginator($paginator);
		$listings = $this->listings->selectListings(array_merge($args, array('search_type' => $current_type, 'page' => 'admin')), $orderby, $direction, $search_sql_array/*, $categories_array*/);

		$view = $this->load->view();
		$view->addJsFile('jquery.jstree.js');
		$view->assign('listings_count', $paginator->count());
		$view->assign('paginator', $paginator->placeLinksToHtml());

		$this->load->model('types', 'types_levels');
		$types = $this->types->getTypesLevels();

		$view->assign('listings', $listings);

		$view->assign('args', $args);
		if (isset($categories_ids))
			$view->assign('search_categories_array', $categories_ids);
		$view->assign('orderby', $orderby);
		$view->assign('direction', $direction);
		$view->assign('clean_url', $clean_url);
		$view->assign('base_url', $base_url);
		$view->assign('search_url', $search_url);
		$view->assign('types', $types);
		if (isset($current_type))
			$view->assign('current_type', $current_type);
		$view->assign('search_fields', $search_fields);
		$view->assign('advanced_search_fields', $advanced_search_fields);

		$this->session->set_userdata('back_page', uri_string());
		$view->display('listings/admin_search_listings.tpl');
	}
	
	public function build_advanced_search()
	{
		if ($this->input->post('type_id') !== FALSE) {
			$type_id = $this->input->post('type_id');
			$this->load->model('types', 'types_levels');
			$this->types->setTypeId($type_id);
			$type = $this->types->getTypeById();

			if ($args_object = json_decode($this->input->post('args')))
				$args = get_object_vars($args_object);
			else 
				$args = array();

			// Build search content fields block
			if ($type_id == 0 || $type->search_type == 'global') {
				$search_type_id = 0;
				$group_name = GLOBAL_SEARCH_GROUP_CUSTOM_NAME;
			} else {
				$search_type_id = $type_id;
				$group_name = LOCAL_SEARCH_GROUP_CUSTOM_NAME;
			}
			$search_fields = new searchContentFields($group_name, $search_type_id, 'advanced');
			$search_sql_array = $search_fields->validateSearch(LISTINGS_LEVEL_GROUP_CUSTOM_NAME, $args, false, $search_url_rebuild);

			$view = $this->load->view();
			$view->assign('args', $args);
			$view->assign('search_fields', $search_fields);
			$mode = 'single';

			if (isset($args["search_creation_date"])) {
				$view->assign('creation_date', date("y-m-d", $args["search_creation_date"]));
				$view->assign('creation_date_tmstmp', $args["search_creation_date"]);
			}
			if (isset($args["search_from_creation_date"])) {
				$view->assign('from_creation_date', date("y-m-d", $args["search_from_creation_date"]));
				$view->assign('from_creation_date_tmstmp', $args["search_from_creation_date"]);
				$mode = 'range';
			}
			if (isset($args["search_to_creation_date"])) {
				$view->assign('to_creation_date', date("y-m-d", $args["search_to_creation_date"]));
				$view->assign('to_creation_date_tmstmp', $args["search_to_creation_date"]);
				$mode = 'range';
			}
			$view->assign('mode', $mode);
			echo $view->fetch('listings/admin_advanced_search.tpl');
		}
	}
	
	// **************************************************************************************************************
	// NEED TO FINISH THIS METHOD to be on a separate page
	// **************************************************************************************************************
	public function advanced_search($type_id = 0)
	{
		$this->load->model('types', 'types_levels');
		$this->types->setTypeId($type_id);
		$type = $this->types->getTypeById();

		if ($args_object = json_decode($this->input->post('args')))
			$args = get_object_vars($args_object);
		else 
			$args = array();

			// Build search content fields block
			if ($type_id == 0 || $type->search_type == 'global') {
				$search_type_id = 0;
				$group_name = GLOBAL_SEARCH_GROUP_CUSTOM_NAME;
			} else {
				$search_type_id = $type_id;
				$group_name = LOCAL_SEARCH_GROUP_CUSTOM_NAME;
			}
			$search_fields = new searchContentFields($group_name, $search_type_id, 'advanced');
			$search_sql_array = $search_fields->validateSearch(LISTINGS_LEVEL_GROUP_CUSTOM_NAME, $args, false, $search_url_rebuild);

			$view = $this->load->view();
			$view->assign('args', $args);
			$view->assign('search_fields', $search_fields);
			$mode = 'single';

			if (isset($args["search_creation_date"])) {
				$view->assign('creation_date', date("y-m-d", $args["search_creation_date"]));
				$view->assign('creation_date_tmstmp', $args["search_creation_date"]);
			}
			if (isset($args["search_from_creation_date"])) {
				$view->assign('from_creation_date', date("y-m-d", $args["search_from_creation_date"]));
				$view->assign('from_creation_date_tmstmp', $args["search_from_creation_date"]);
				$mode = 'range';
			}
			if (isset($args["search_to_creation_date"])) {
				$view->assign('to_creation_date', date("y-m-d", $args["search_to_creation_date"]));
				$view->assign('to_creation_date_tmstmp', $args["search_to_creation_date"]);
				$mode = 'range';
			}
			$view->assign('mode', $mode);
			echo $view->fetch('listings/admin_advanced_search.tpl');
	}

	public function my($argsString = '')
	{
		//$this->output->enable_profiler(TRUE);
		
		$args = parseUrlArgs($argsString);

		// Search url needs for 'asc_desc_insert.base_url' argument of smarty function
		$search_url = site_url('admin/listings/my');

		// Paginator url needs for '.../page/x/' url modification
		$paginator_url = $search_url;
		if (isset($args['orderby'])) {
			$orderby = $args['orderby'];
			$paginator_url .= 'orderby/' . $args['orderby'] . '/';
		} else {
			$orderby = 'id';
		}
		if (isset($args['direction'])) {
			$direction = $args['direction'];
			$paginator_url .= 'direction/' . $args['direction'] . '/';
		} else {
			$direction = 'desc';
		}

		$paginator = new pagination(array('args' => $args, 'url' => $paginator_url, 'num_per_page' => 10));
		$this->load->model('listings');
		$this->listings->setPaginator($paginator);
		$listings = $this->listings->selectMyListings($orderby, $direction);

		$view = $this->load->view();
		$view->assign('listings_count', $paginator->count());
		$view->assign('paginator', $paginator->placeLinksToHtml());
		$view->assign('listings', $listings);

		$view->assign('orderby', $orderby);
		$view->assign('direction', $direction);
		$view->assign('search_url', $search_url);

		$this->session->set_userdata('back_page', uri_string());
		$view->display('listings/admin_my_listings.tpl');
	}

    /**
     * form validation function
     *
     * @param string $seoname
     * @return bool
     */
    public function is_unique_listing_seo_name($seoname)
    {
    	$this->load->model('listings');
		
    	if ($seoname)
			if ($this->listings->is_listing_seo_name($seoname) || is_numeric($seoname)) {
				$this->form_validation->set_message('seoname');
				return FALSE;
			} else {
				return TRUE;
			}
		else 
			return TRUE;
    }

	/**
	 * Create new listing in 3 steps:
	 * 1st step: select listing type and level
	 * 2nd step: fill in listing fields
	 * 3rd step: listing preview and approval
	 *
	 */
	public function create($level_id = null, $user_package_id = null)
	{
		$this->load->model('listings');
		$this->load->model('levels', 'types_levels');
		$this->load->model('types', 'types_levels');
		$system_settings = registry::get('system_settings');

		if (is_null($level_id)) {
			// --------------------------------------------------------------------------------------------
			// 1st step
			// --------------------------------------------------------------------------------------------
			$this->load->model('types', 'types_levels');
	    	$types = $this->types->getTypesLevels();

			if ($listings_prices = registry::get('listings_prices')) {
				foreach ($types AS $type_id=>$type) {
					foreach ($type->levels AS $level_id=>$level) {
						foreach ($listings_prices AS $price) {
							if ($level->id == $price['level_id'] && $this->session->userdata('user_group_id') == $price['group_id']) {
								$types[$type_id]->levels[$level_id]->setPrice($price['value'], $price['currency']);
							}
						}
					}
				}
			}

			$view  = $this->load->view();
			
			if ($this->load->is_module_loaded('packages')) {
				$this->load->model('packages', 'packages');
				$my_packages_obj = $this->packages->getUserPackages();
				$view->assign('my_packages_obj', $my_packages_obj);
			}
			
			$view->assign('types', $types);
			$view->assign('listings_prices', $listings_prices);
			$view->display('listings/admin_create_listing_select_type_level.tpl');
		} else {
			// --------------------------------------------------------------------------------------------
			// 2nd step
			// --------------------------------------------------------------------------------------------
			
			// --------------------------------------------------------------------------------------------
			// Is choosen level available
			// --------------------------------------------------------------------------------------------
			$content_access_obj = contentAcl::getInstance();
			if (!$content_access_obj->isContentPermission('levels', $level_id))
				show_error('401 Access denied!');
			// --------------------------------------------------------------------------------------------

			$listing = new listing($level_id);
			
			// --------------------------------------------------------------------------------------------
			// Check packages - is choosen package available
			// --------------------------------------------------------------------------------------------
			if (!is_null($user_package_id)) {
				$package_available = false;
				if ($this->load->is_module_loaded('packages')) {
					$this->load->model('packages', 'packages');
					$my_packages_obj = $this->packages->getUserPackages();
					
					foreach ($my_packages_obj->packages AS $user_package) {
						if ($user_package->status == 1 && $user_package->id == $user_package_id) {
							foreach ($user_package->listings_left AS $package_level_id=>$listings_count) {
								if ($package_level_id == $level_id)
									if ($listings_count === 'unlimited' || $listings_count > 0) {
										$package_available = true;
										break;
										break;
									}
							}
						}
					}
				}
				if (!$package_available) {
					show_error('401 Access denied!');
				}
			}
			// --------------------------------------------------------------------------------------------

			if ($this->input->post('submit')) {
				if ($content_access_obj->isPermission('Manage ability to claim'))
					$this->form_validation->set_rules('ability_to_claim', LANG_LISTING_ABILITY_TO_CLAIM, 'is_checked');
	            if ($listing->level->title_enabled)
					$this->form_validation->set_rules('name', LANG_LISTING_TITLE, 'required|max_length[' . LISTING_TITLE_LENGTH . ']');
				if ($listing->level->seo_title_enabled)
					$this->form_validation->set_rules('seo_name', LANG_LISTING_SEO_TITLE, 'max_length[255]|alpha_dash|callback_is_unique_listing_seo_name');
				if ($listing->level->description_mode != 'disabled') {
					if ($listing->level->description_mode != 'richtext')
						$condition = 'max_length[' . $listing->level->description_length . ']';
					else 
						$condition = '';						
					$this->form_validation->set_rules('listing_description', LANG_LISTING_DESCRIPTION, $condition);
				}
				if ($listing->level->meta_enabled) {
					$this->form_validation->set_rules('listing_meta_description', LANG_LISTING_META_DESCRIPTION);
					$this->form_validation->set_rules('listing_keywords', LANG_LISTING_KEYWORDS);
				}
	            if ($listing->level->logo_enabled) {
	            	$this->form_validation->set_rules('listing_logo_image', LANG_LISTING_LOGO_IMAGE, 'max_length[255]');
	            }
	            if ($content_access_obj->isPermission('Edit listings expiration date') && (!$listing->level->eternal_active_period || $listing->level->allow_to_edit_active_period)) {
					$this->form_validation->set_rules('expiration_date', LANG_EXPIRATION_DATE, 'required');
				}
	            if ($listing->type->categories_type != 'disabled' && $listing->level->categories_number)
	            	$this->form_validation->set_rules('serialized_categories_list', LANG_LISTING_SELECTED_CATEGORIES, 'required');
				if ($listing->type->locations_enabled && $listing->level->locations_number && $this->input->post('location_id')) {
					$this->form_validation->set_rules('location_id[]', LANG_LISTING_LOCATION_ID, 'required');

					if ($system_settings['predefined_locations_mode'] != 'disabled') {
						$this->form_validation->set_rules('use_predefined_locations', LANG_USE_PREDEFINED_LOCATIONS, 'isset');
						$this->load->model('locations', 'locations_predefined');
						$locations_levels = $this->locations->selectAllLevels();
						foreach ($locations_levels AS $locations_level)
							$this->form_validation->set_rules('loc_level_'.$locations_level->order_num.'[]', LANG_USE_PREDEFINED_LOCATIONS, 'integer');
					}

					if ($system_settings['predefined_locations_mode'] != 'only') {
						$this->form_validation->set_rules('location[]', LANG_LISTING_LOCATION, 'max_length[255]');
						$this->form_validation->set_rules('geocoded_name[]', LANG_LOCATIONS_GEO_NAME, 'max_length[255]');
					}

					$this->form_validation->set_rules('address_line_1[]', LANG_ADDRESS_LINE1_1, 'max_length[255]');
					$this->form_validation->set_rules('address_line_2[]', LANG_ADDRESS_LINE2_1, 'max_length[255]');
					if ($listing->type->zip_enabled) {
						$this->form_validation->set_rules('zip_or_postal_index[]', LANG_LISTING_ZIP, 'max_length[255]');
					}
					if ($listing->level->maps_enabled) {
						$this->form_validation->set_rules('single_icon', LANG_SINGLE_ICON, 'required');
						$this->form_validation->set_rules('manual_coords', LANG_ENTER_LTLG_MANUALLY, 'isset');
						$this->form_validation->set_rules('map_coords_1[]', LANG_MAP_LATITUDE);
						$this->form_validation->set_rules('map_coords_2[]', LANG_MAP_LONGITUDE);
						$this->form_validation->set_rules('map_zoom[]', LANG_MAP_ZOOM_LEVEL, 'integer');
						$this->form_validation->set_rules('map_icon_id[]', LANG_MAP_ICON_ID, 'integer');
						$this->form_validation->set_rules('map_icon_file[]', LANG_MAP_ICON_FILE);
					}
				}
				$listing->validateFields($this->form_validation);

				if ($this->form_validation->run() != FALSE) {
					$icons_test_ok = true;
					if ($listing->type->locations_enabled && $listing->level->maps_enabled && $listing->type->categories_type != 'disabled') {
						$this->load->model('categories', 'categories');
						$icons_test = $this->categories->isIcons(unserialize($this->form_validation->set_value('serialized_categories_list')), $this->form_validation->set_value('map_icon_id[]'));
						foreach ($icons_test['is_selected_icons'] AS $is_icon) {
							if (!$is_icon)
								$icons_test_ok = false;
						}
					}
					if ($icons_test_ok) {
						if ($listing_id = $this->listings->saveListing($this->form_validation->result_array(), $listing->level, $listing->type, $user_package_id)) {
							if ($listing->saveFields($listing_id, $this->form_validation->result_array())) {
								$this->setSuccess(LANG_LISTINGS_CREATE_SUCCESS_1 . ' "' . $this->form_validation->set_value('name') . '" ' . LANG_LISTINGS_CREATE_SUCCESS_2);
								// raise Listing creation event
								$event_params = array(
									'LISTING_ID' => $listing_id, 
									'LISTING_TITLE' => $this->form_validation->set_value('name'), 
									'LISTING_TYPE' => $listing->type->name,
									'LISTING_LEVEL' => $listing->level->name,
									'RECIPIENT_NAME' => $this->session->userdata('user_login'),
									'RECIPIENT_EMAIL' => $this->session->userdata('user_email')
								);
								$notification = new notificationSender('Listing creation');
								$notification->send($event_params);
								events::callEvent('Listing creation', $event_params);

								redirect('admin/listings/view/' . $listing_id . '/');
							}
						}
					} else {
						$this->setError(LANG_ICONS_TEST_ERROR);
					}
				}

				$listing->getListingFromForm($this->form_validation->result_array());
				$listing->content_fields->getValuesFromForm($this->form_validation->result_array());
			}

			$view = $this->load->view();

			if ($listing->level->logo_enabled) {
				$file_to_upload = new filesUpload;
				$file_to_upload->title = LANG_LISTING_LOGO_IMAGE;
				$file_to_upload->upload_id = 'listing_logo_image';
				$file_to_upload->current_file = $listing->logo_file;
				$file_to_upload->after_upload_url = site_url('admin/listings/get_logo/' . $listing->level->id);
				$file_to_upload->attrs['width'] = $listing->level->explodeSize('logo_size', 'width');
				$file_to_upload->attrs['height'] = $listing->level->explodeSize('logo_size', 'height');

				$view->addJsFile('ajaxfileupload.js');
				$view->assign('image_upload_block', $file_to_upload);
			}

			$view->addJsFile('jquery.jstree.js');

			$view->assign('listing', $listing);
			$view->assign('level_name', $listing->level->name);
			$view->assign('type_name', $listing->type->name);

			if ($listing->type->locations_enabled && $listing->level->maps_enabled) {
				$view->addJsFile('google_maps_edit.js');
			}
			$view->display('listings/admin_listing_main_settings.tpl');
		}
	}
	
	/**
	 * Handle uploaded logo file and process it through image resize functions
	 */
	public function get_logo($level_id)
    {
    	if ($this->input->post('uploaded_file')) {
    		$uploaded_file = $this->input->post('uploaded_file');
    		$crop = $this->input->post('crop');
    		
			$users_content_server_path = $this->config->item('users_content_server_path');
			$users_content_settings = $this->config->item('users_content');

			$this->load->model('levels', 'types_levels');
			$level = $this->levels->getLevelById($level_id);

			if ($level->logo_enabled) {
				$this->load->library('image_lib');

				// Process all available thumbnails
				foreach ($users_content_settings['listing_image']['thumbnails'] AS $thmb) {
					// When thumbnails size option doesn't set in config file -
					// take it from level's settings
					if (isset($thmb['size']))
						$destImageSize[] = $thmb['size'];
					else
						$destImageSize[] = $level->images_thumbnail_size;
					$destImageFolder[] = $users_content_server_path . $thmb['upload_to'];
					if (isset($thmb['crop']))
						$destImageCrop[] = $thmb['crop'];
					else 
						$destImageCrop[] = false;
				}

				$destImageSize[] = $level->images_size;
				$destImageFolder[] = $users_content_server_path . $users_content_settings['listing_image']['upload_to'];
				if (isset($users_content_settings['listing_image']['crop']))
					$destImageCrop[] = $users_content_settings['listing_image']['crop'];
				else 
					$destImageCrop[] = false;

				$destImageSize[] = $level->logo_size;
				$destImageFolder[] = $users_content_server_path . $users_content_settings['listing_logo_image']['upload_to'];
				if ($crop == 'true')
					$destImageCrop[] = true;
				else
					$destImageCrop[] = false;

				/*$site_settings = registry::get('site_settings');
				$config['source_image'] = $uploaded_file;
				$config['wm_text'] = $site_settings['website_title'];
				$config['wm_type'] = 'text';
				$config['wm_font_path'] = BASEPATH . DIRECTORY_SEPARATOR . 'fonts' . DIRECTORY_SEPARATOR . 'ANTSYPAN.TTF';
				$config['wm_font_size'] = '22';
				$config['wm_font_color'] = 'ffffff';
				$config['wm_vrt_alignment'] = 'middle';
				$config['wm_hor_alignment'] = 'center';
				//$config['wm_padding'] = '50';
				//$config['wm_opacity'] = '20';
				$config['quality'] = '100';
				$this->image_lib->initialize($config);
				$this->image_lib->watermark();*/
				
				if ($this->image_lib->process_images('resize', $uploaded_file, $destImageFolder, $destImageSize, $destImageCrop)) {
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
	}
	
	/**
	 * Edit listing
	 * here will be checked user's permissions to the content - 
	 * this means that only users with "Manage all listings" permissions and listing's author may
	 * get access to this listing
	 *
	 */
	public function edit($listing_id)
	{
		$this->load->model('listings');
		$this->load->model('levels', 'types_levels');
		$this->load->model('types', 'types_levels');
		$this->listings->setListingId($listing_id);
		$level_id = $this->listings->getLevelIdByListingId();

		$system_settings = registry::get('system_settings');

		$content_access_obj = contentAcl::getInstance();
		$content_access_obj->checkListingAccess($listing_id, 'Manage all listings');

		$listing = new listing($level_id, $listing_id);
		$listing->setListingFromArray($this->listings->getListingRowById(), $this->listings->getListingCategories(), $this->listings->getListingLocations());

		if ($this->input->post('submit')) {
			if ($content_access_obj->isPermission('Manage ability to claim'))
				$this->form_validation->set_rules('ability_to_claim', LANG_LISTING_ABILITY_TO_CLAIM, 'is_checked');
			if ($listing->level->title_enabled)
				$this->form_validation->set_rules('name', LANG_LISTING_TITLE, 'required|max_length[' . LISTING_TITLE_LENGTH . ']');
			if ($listing->level->seo_title_enabled)
				$this->form_validation->set_rules('seo_name', LANG_LISTING_SEO_TITLE, 'max_length[255]|alpha_dash|callback_is_unique_listing_seo_name');
			if ($listing->level->description_mode != 'disabled') {
				if ($listing->level->description_mode != 'richtext')
					$condition = 'max_length[' . $listing->level->description_length . ']';
				else 
					$condition = '';						
				$this->form_validation->set_rules('listing_description', LANG_LISTING_DESCRIPTION, $condition);
			}
			if ($listing->level->meta_enabled) {
				$this->form_validation->set_rules('listing_meta_description', LANG_LISTING_META_DESCRIPTION);
				$this->form_validation->set_rules('listing_keywords', LANG_LISTING_KEYWORDS);
			}
			if ($listing->level->logo_enabled) {
				$this->form_validation->set_rules('listing_logo_image', LANG_LISTING_LOGO_IMAGE, 'max_length[255]');
			}
			if ($content_access_obj->isPermission('Edit listings expiration date') && (!$listing->level->eternal_active_period || $listing->level->allow_to_edit_active_period)) {
				$this->form_validation->set_rules('expiration_date', LANG_EXPIRATION_DATE, 'required');
			}
			if ($listing->type->categories_type != 'disabled' && $listing->level->categories_number)
				$this->form_validation->set_rules('serialized_categories_list', LANG_LISTING_SELECTED_CATEGORIES, 'required');
			if ($listing->type->locations_enabled && $listing->level->locations_number && $this->input->post('location_id')) {
				$this->form_validation->set_rules('location_id[]', LANG_LISTING_LOCATION_ID, 'required');
				
				if ($system_settings['predefined_locations_mode'] != 'disabled') {
					$this->form_validation->set_rules('use_predefined_locations', LANG_USE_PREDEFINED_LOCATIONS, 'isset');
					$this->load->model('locations', 'locations_predefined');
					$locations_levels = $this->locations->selectAllLevels();
					foreach ($locations_levels AS $locations_level)
						$this->form_validation->set_rules('loc_level_'.$locations_level->order_num.'[]', LANG_USE_PREDEFINED_LOCATIONS, 'integer');
				}
				
				if ($system_settings['predefined_locations_mode'] != 'only') {
					$this->form_validation->set_rules('location[]', LANG_LISTING_LOCATION, 'max_length[255]');
					$this->form_validation->set_rules('geocoded_name[]', LANG_LOCATIONS_GEO_NAME, 'max_length[255]');
				}
				$this->form_validation->set_rules('address_line_1[]', LANG_ADDRESS_LINE1_1, 'max_length[255]');
				$this->form_validation->set_rules('address_line_2[]', LANG_ADDRESS_LINE2_1, 'max_length[255]');
				if ($listing->type->zip_enabled) {
					$this->form_validation->set_rules('zip_or_postal_index[]', LANG_LISTING_ZIP, 'max_length[255]');
				}
				if ($listing->level->maps_enabled) {
					$this->form_validation->set_rules('single_icon', LANG_SINGLE_ICON, 'required');
					$this->form_validation->set_rules('manual_coords', LANG_ENTER_LTLG_MANUALLY, 'isset');
					$this->form_validation->set_rules('map_coords_1[]', LANG_MAP_LATITUDE);
					$this->form_validation->set_rules('map_coords_2[]', LANG_MAP_LONGITUDE);
					$this->form_validation->set_rules('map_zoom[]', LANG_MAP_ZOOM_LEVEL, 'integer');
					$this->form_validation->set_rules('map_icon_id[]', LANG_MAP_ICON_ID, 'integer');
					$this->form_validation->set_rules('map_icon_file[]', LANG_MAP_ICON_FILE);
				}
			}
			$listing->validateFields($this->form_validation);

			if ($this->form_validation->run() != FALSE) {
				$icons_test_ok = true;
				if ($listing->type->locations_enabled && $listing->level->maps_enabled && $listing->type->categories_type != 'disabled') {
					// Check if listing allowed to be assigned with these icons
					$this->load->model('categories', 'categories');
					$icons_test = $this->categories->isIcons(unserialize($this->form_validation->set_value('serialized_categories_list')), $this->form_validation->set_value('map_icon_id[]'));
					foreach ($icons_test['is_selected_icons'] AS $is_icon) {
						if (!$is_icon)
							$icons_test_ok = false;
					}
				}
				if ($icons_test_ok) {
					if ($this->listings->saveListingById($this->form_validation->result_array(), $listing->level, $listing->type, $listing)) {
						// Clean cache
						$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_'.$listing_id));
						
						if ($listing->updateFields($this->form_validation->result_array())) {
							$this->setSuccess(LANG_LISTING_SAVE_SUCCESS_1 . ' "' . $this->form_validation->set_value('name') . '" ' . LANG_LISTING_SAVE_SUCCESS_2);

							redirect('admin/listings/view/' . $listing_id . '/');
						}
					}
				} else {
					$this->setError(LANG_ICONS_TEST_ERROR);
				}
			}

			$listing->getListingFromForm($this->form_validation->result_array());
			$listing->content_fields->getValuesFromForm($this->form_validation->result_array());
		}

		if (strpos($this->session->userdata('back_page'), '/admin/listings/search/') !== FALSE) {
	    	registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_SEARCH_LISTINGS_TITLE,
	    		LANG_EDIT_LISTING_1 . ' "' . $listing->type->name . '" ' . LANG_EDIT_LISTING_2 . ' "' . $listing->level->name . '"',
	    	));
    	} elseif (strpos($this->session->userdata('back_page'), '/admin/listings/my/') !== FALSE) {
    		registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_VIEW_MY_LISTINGS_TITLE,
	    		LANG_EDIT_LISTING_1 . ' "' . $listing->type->name . '" ' . LANG_EDIT_LISTING_2 . ' "' . $listing->level->name . '"',
	    	));
    	}
    	
    	$view = $this->load->view();

		if ($listing->level->logo_enabled) {
			$file_to_upload = new filesUpload;
			$file_to_upload->title = LANG_LISTING_LOGO_IMAGE;
			$file_to_upload->upload_id = 'listing_logo_image';
			$file_to_upload->current_file = $listing->logo_file;
			$file_to_upload->after_upload_url = site_url('admin/listings/get_logo/' . $listing->level->id);
			$file_to_upload->attrs['width'] = $listing->level->explodeSize('logo_size', 'width');
			$file_to_upload->attrs['height'] = $listing->level->explodeSize('logo_size', 'height');

			$view->addJsFile('ajaxfileupload.js');
			$view->assign('image_upload_block', $file_to_upload);
		}

		$view->addJsFile('jquery.jstree.js');

		$view->assign('listing', $listing);
		$view->assign('level_name', $listing->level->name);
		$view->assign('type_name', $listing->type->name);

		if ($listing->type->locations_enabled && $listing->level->maps_enabled) {
			$view->addJsFile('google_maps_edit.js');
		}
		$view->display('listings/admin_listing_main_settings.tpl');
	}
	
	/**
	 * Delete just one listing
	 * 
	 */
	public function delete($listing_id)
    {
        $this->load->model('listings');
		$this->listings->setListingId($listing_id);
		
		$content_access_obj = contentAcl::getInstance();
		$content_access_obj->checkListingAccess($listing_id, 'Manage all listings');

		if ($this->input->post('yes')) {
            if ($this->listings->deleteListingById()) {
            	// Clean cache
				$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_counts', 'listings_'.$listing_id));

            	$this->setSuccess(LANG_LISTINGS_DELETE_SUCCESS);
                redirect($this->session->userdata('back_page'));
            }
        }

        if ($this->input->post('no')) {
            redirect($this->session->userdata('back_page'));
        }

        if ( !$listing_array = $this->listings->getListingRowById()) {
            redirect($this->session->userdata('back_page'));
        }
        
        if (strpos($this->session->userdata('back_page'), '/admin/listings/search/') !== FALSE) {
	    	registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_SEARCH_LISTINGS_TITLE,
	    		LANG_DELETE_LISTINGS,
	    	));
    	} elseif (strpos($this->session->userdata('back_page'), '/admin/listings/my/') !== FALSE) {
    		registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_VIEW_MY_LISTINGS_TITLE,
	    		LANG_DELETE_LISTINGS,
	    	));
    	}

		$view  = $this->load->view();
		$view->assign('options', array($listing_id => $listing_array['title']));
        $view->assign('heading', LANG_DELETE_LISTINGS);
        $view->assign('question', LANG_DELETE_LISTINGS_QUEST);
        $view->display('backend/delete_common_item.tpl');
    }
	
	/**
	 * Delete group of listings
	 * 
	 */
	public function massDelete()
    {
    	$this->load->model('listings');

		$listings_ids = $this->input->searchPostItems('cb_');
		if (empty($listings_ids)) {
			$listings_ids = $this->input->post('options');
		}
		$listings_array = array();
		if (!empty($listings_ids)) {
			$content_access_obj = contentAcl::getInstance();
			foreach ($listings_ids AS $id) {
				$content_access_obj->checkListingAccess($id, 'Manage all listings');
				$listing = $this->listings->getListingRowById($id);
				$listings_array[$id] = $listing['title'];
			}
		} else {
			$this->setError(LANG_LISTINGS_SELECT_ERROR);
			redirect($this->session->userdata('back_page'));
		}

        if ($this->input->post('yes')) {
            if ($this->listings->deleteListings($listings_array)) {
                $this->setSuccess(LANG_LISTINGS_DELETE_SUCCESS);
                redirect($this->session->userdata('back_page'));
            }
        }

        if ($this->input->post('no')) {
            redirect($this->session->userdata('back_page'));
        }
        
        if (strpos($this->session->userdata('back_page'), '/admin/listings/search/') !== FALSE) {
	    	registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_SEARCH_LISTINGS_TITLE,
	    		LANG_DELETE_LISTINGS,
	    	));
    	} elseif (strpos($this->session->userdata('back_page'), '/admin/listings/my/') !== FALSE) {
    		registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_VIEW_MY_LISTINGS_TITLE,
	    		LANG_DELETE_LISTINGS,
	    	));
    	}

        $view  = $this->load->view();
		$view->assign('options', $listings_array);
        $view->assign('heading', LANG_DELETE_LISTINGS);
        $view->assign('question', LANG_DELETE_LISTINGS_QUEST);
        $view->display('backend/delete_common_item.tpl');
    }
    
    /**
	 * Block checked listings
	 * 
	 */
	public function block()
    {
		$listings_ids = $this->input->searchPostItems('cb_');
		if (!empty($listings_ids)) {
			$this->load->model('listings');
			if ($this->listings->blockListings($listings_ids)) {
				$this->setSuccess(LANG_LISTINGS_BLOCK_SUCCESS);
				
				$this->load->model('users', 'users');
				foreach ($listings_ids AS $listing_id) {
					$this->listings->setListingId($listing_id);
					$level_id = $this->listings->getLevelIdByListingId();
					$listing = new listing($level_id, $listing_id);
					$listing->setListingFromArray($this->listings->getListingRowById(), $this->listings->getListingCategories(), $this->listings->getListingLocations());

					$this->users->setUserId($listing->owner_id);
					$user = $this->users->getUserById();

					if ($listing->status != 2) {
						// raise Listing blocking event
						$event_params = array(
							'LISTING_ID' => $listing_id, 
							'LISTING_TITLE' => $listing->title(), 
							'LISTING_TYPE' => $listing->type->name,
							'LISTING_LEVEL' => $listing->level->name,
							'RECIPIENT_NAME' => $user->login,
							'RECIPIENT_EMAIL' => $user->email
						);
						$notification = new notificationSender('Listing blocking');
						$notification->send($event_params);
						events::callEvent('Listing blocking', $event_params);
					}
				}

				//redirect('admin/listings/search/');
			}
		} else {
			$this->setError(LANG_LISTINGS_SELECT_ERROR);
			//redirect('admin/listings/search/');
		}
		redirect($this->session->userdata('back_page'));
    }
    
    /**
	 * Acivate checked listings
	 * 
	 */
	public function activate()
    {
		$listings_ids = $this->input->searchPostItems('cb_');
		if (!empty($listings_ids)) {
			$this->load->model('listings');
			if ($this->listings->activateListings($listings_ids)) {
				$this->setSuccess(LANG_LISTINGS_ACTIVATE_SUCCESS);

				$this->load->model('users', 'users');
				foreach ($listings_ids AS $listing_id) {
					$this->listings->setListingId($listing_id);
					$level_id = $this->listings->getLevelIdByListingId();
					$listing = new listing($level_id, $listing_id);
					$listing->setListingFromArray($this->listings->getListingRowById(), $this->listings->getListingCategories(), $this->listings->getListingLocations());

					$this->users->setUserId($listing->owner_id);
					$user = $this->users->getUserById();

					if ($listing->status != 4) {
						// raise Listing approval event
						$event_params = array(
							'LISTING_ID' => $listing_id, 
							'LISTING_TITLE' => $listing->title(), 
							'LISTING_TYPE' => $listing->type->name,
							'LISTING_LEVEL' => $listing->level->name,
							'RECIPIENT_NAME' => $user->login,
							'RECIPIENT_EMAIL' => $user->email
						);
						$notification = new notificationSender('Listing approval');
						$notification->send($event_params);
						events::callEvent('Listing approval', $event_params);
					}
				}
				
				//redirect('admin/listings/search/');
			}
		} else {
			$this->setError(LANG_LISTINGS_SELECT_ERROR);
			//redirect('admin/listings/search/');
		}
		redirect($this->session->userdata('back_page'));
    }
	
	public function view($listing_id)
	{
		$system_settings = registry::get("system_settings");

		$this->load->model('listings');
		$this->listings->setListingId($listing_id);
		$level_id = $this->listings->getLevelIdByListingId();
		
		$content_access_obj = contentAcl::getInstance();
		$content_access_obj->checkListingAccess($listing_id, 'Manage all listings');

		$listing = new listing($level_id, $listing_id);
		$listing->setListingFromArray($this->listings->getListingRowById(), $this->listings->getListingCategories(), $this->listings->getListingLocations());

		if (strpos($this->session->userdata('back_page'), '/admin/listings/search/') !== FALSE) {
	    	registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_SEARCH_LISTINGS_TITLE,
	    		LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
	    	));
    	} elseif (strpos($this->session->userdata('back_page'), '/admin/listings/my/') !== FALSE) {
    		registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_VIEW_MY_LISTINGS_TITLE,
	    		LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
	    	));
    	}

		$view = $this->load->view();
		$view->addJsFile('google_maps_view.js');
		$view->assign('listing', $listing);
		$view->assign('level_name', $listing->level->name);
		$view->assign('type_name', $listing->type->name);
		$view->display('listings/admin_view_listing.tpl');
	}
	
	/**
	 * change listing level
	 *
	 */
	public function change_level($listing_id)
	{
		$this->load->model('listings');
		$this->load->model('levels', 'types_levels');

		$this->listings->setListingId($listing_id);
		$current_level_id = $this->listings->getLevelIdByListingId();

		$current_level = $this->levels->getLevelById($current_level_id);

		$this->levels->setTypeId($current_level->type_id);
		$levels = $this->levels->getLevelsOfType();

		$listing = new listing($current_level_id, $listing_id);
		$listing->setListingFromArray($this->listings->getListingRowById(), $this->listings->getListingCategories(), $this->listings->getListingLocations());

		// --------------------------------------------------------------------------------------------
		// IF listing is in package - check levels those allowed for this package
		// --------------------------------------------------------------------------------------------
		if ($listing->package) {
			$listings_prices = array();

			$this->load->model('packages', 'packages');
			$my_packages_obj = $this->packages->getUserPackages($listing->owner_id);

			foreach ($my_packages_obj->packages AS $user_package) {
				if ($user_package->id == $listing->package->id) {
					foreach ($levels AS $level_key=>$level) {
						if ($level->id != $current_level_id) {
							if ($user_package->listings_left[$level->id] !== 'unlimited' && $user_package->listings_left[$level->id] <= 0) {
								$level->setPrice('package level not allowed');
							}
						}
					}
				}
			}
		} else 
			// From 'modules/payment/controllers/payment_listings_hook.php'
			if (!($listings_prices = registry::get('listings_prices')))
				$listings_prices = array();
		// --------------------------------------------------------------------------------------------
		
		// --------------------------------------------------------------------------------------------
		// Set difference prices
		// --------------------------------------------------------------------------------------------
		foreach ($levels AS $level_key=>$level) {
			if ($level->id != $current_level_id) {
				foreach ($listings_prices AS $price_key=>$price_row) {
					if ($price_row['level_id'] == $level->id) {
						$level->setPrice($listings_prices[$price_key]['value'], $listings_prices[$price_key]['currency']);
					}
				}
			} else {
				$level->setPrice('current level');
			}
		}
		// --------------------------------------------------------------------------------------------

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('level_id', LANG_LISTING_LEVEL, 'required');

			if ($this->form_validation->run() !== FALSE) {
				$chosen_level_id = $this->form_validation->set_value('level_id');
				
				$content_access_obj = contentAcl::getInstance();

				// If user really changed level
				if ($chosen_level_id != $current_level_id && $content_access_obj->isContentPermission('levels', $chosen_level_id)) {
					// Find choosen level and get new expiration date of listing
					$chosen_level = $this->levels->getLevelById($chosen_level_id);

					if ($this->listings->saveListingLevelAndExpirationDate($chosen_level_id, mktime() + (
	    											($chosen_level->active_days) +
	    											($chosen_level->active_months*30) +
	    											($chosen_level->active_years*365))*86400)) {
						// Clean cache
						$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_'.$listing_id));
						
						// Raise Listing change level event
						$event_params = array(
							'LISTING_ID' => $listing_id, 
							'LISTING_TITLE' => $listing->title(), 
							'LISTING_TYPE' => $listing->type->name,
							'NEW_LISTING_LEVEL' => $chosen_level->name,
							'NEW_LISTING_LEVEL_ID' => $chosen_level->id,
							'OLD_LISTING_LEVEL' => $current_level->name,
							'OLD_LISTING_LEVEL_ID' => $current_level->id,
							'RECIPIENT_NAME' => $listing->user->login,
							'RECIPIENT_EMAIL' => $listing->user->email
						);
						$notification = new notificationSender('Listing change level');
						$notification->send($event_params);
						events::callEvent('Listing change level', $event_params);
	
						$this->setSuccess(LANG_LISTING_LEVEL_SAVE_SUCCESS_1 . ' "' . $listing->title() . '" ' . LANG_LISTING_LEVEL_SAVE_SUCCESS_2);
					}
				}
				redirect($this->session->userdata('back_page'));
			}
        }
        
        if (strpos($this->session->userdata('back_page'), 'admin/listings/search/') !== FALSE) {
	    	registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_SEARCH_LISTINGS_TITLE,
	    		'admin/listings/view/' . $listing->id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
	    		LANG_LISTING_LEVELS_1 . ' "' . $listing->title() . '"',
	    	));
    	} elseif (strpos($this->session->userdata('back_page'), 'admin/listings/my/') !== FALSE) {
    		registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_VIEW_MY_LISTINGS_TITLE,
	    		'admin/listings/view/' . $listing->id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
	    		LANG_LISTING_LEVELS_1 . ' "' . $listing->title() . '"',
	    	));
    	}

		$view = $this->load->view();
		$view->assign('levels', $levels);
		$view->assign('listing', $listing);
		$view->assign('listing_id', $listing_id);
		$view->display('listings/admin_listing_level.tpl');
	}
	
	/**
	 * change listing status page
	 *
	 */
	public function change_status($listing_id)
	{
		$this->load->model('listings');
		$this->listings->setListingId($listing_id);
		$level_id = $this->listings->getLevelIdByListingId();
		
		$content_access_obj = contentAcl::getInstance();
		$content_access_obj->checkListingAccess($listing_id, 'Manage all listings');

		$listing = new listing($level_id, $listing_id);
		$listing->setListingFromArray($this->listings->getListingRowById(), $this->listings->getListingCategories(), $this->listings->getListingLocations());

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('status', LANG_LISTING_STATUS, 'required');

			if ($this->form_validation->run() !== FALSE) {
				
				if ($this->form_validation->set_value('status') != 1 && $this->form_validation->set_value('status') != 2) {
		    		$content_access_obj = contentAcl::getInstance();
			    	if (!$content_access_obj->isPermission('Manage all listings')) {
			    		// User don't allowed to set such status
			    		return false;
			    	}
		    	}
				
				if ($this->listings->saveListingStatus($this->form_validation->set_value('status'))) {
					// Clean cache
					$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_'.$listing_id));

					$this->setSuccess(LANG_LISTING_STATUS_SAVE_SUCCESS_1 . ' "' . $listing->title() . '" ' . LANG_LISTING_STATUS_SAVE_SUCCESS_2);

					if ($listing->status != 2 && $this->form_validation->set_value('status') == 2) {
						// raise Listing blocking event
						$event_params = array(
							'LISTING_ID' => $listing_id, 
							'LISTING_TITLE' => $listing->title(), 
							'LISTING_TYPE' => $listing->type->name,
							'LISTING_LEVEL' => $listing->level->name,
							'RECIPIENT_NAME' => $listing->user->login,
							'RECIPIENT_EMAIL' => $listing->user->email
						);
						$notification = new notificationSender('Listing blocking');
						$notification->send($event_params);
						events::callEvent('Listing blocking', $event_params);
					}
					if ($listing->status == 4 && $this->form_validation->set_value('status') == 1) {
						// raise Listing approval event
						$event_params = array(
							'LISTING_ID' => $listing_id, 
							'LISTING_TITLE' => $listing->title(), 
							'LISTING_TYPE' => $listing->type->name,
							'LISTING_LEVEL' => $listing->level->name,
							'RECIPIENT_NAME' => $listing->user->login,
							'RECIPIENT_EMAIL' => $listing->user->email
						);
						$notification = new notificationSender('Listing approval');
						$notification->send($event_params);
						events::callEvent('Listing approval', $event_params);
					}
					redirect($this->session->userdata('back_page'));
				}
			}
        }

	    if (strpos($this->session->userdata('back_page'), '/admin/listings/search/') !== FALSE) {
	    	registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_SEARCH_LISTINGS_TITLE,
	    		'admin/listings/view/' . $listing->id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
	    		LANG_LISTING_STATUS . ' "' . $listing->title() . '"',
	    	));
    	} elseif (strpos($this->session->userdata('back_page'), '/admin/listings/my/') !== FALSE) {
    		registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_VIEW_MY_LISTINGS_TITLE,
	    		'admin/listings/view/' . $listing->id => LANG_VIEW_LISTING . ' "' . $listing->title() . '"',
	    		LANG_LISTING_STATUS . ' "' . $listing->title() . '"',
	    	));
    	}

		$view = $this->load->view();
		$view->assign('listing', $listing);
		$view->assign('listing_id', $listing_id);
		$view->display('listings/admin_listing_status.tpl');
	}
	
	public function prolong($listing_id)
	{
		$this->load->model('listings');
		$this->listings->setListingId($listing_id);
		$level_id = $this->listings->getLevelIdByListingId();
		
		$content_access_obj = contentAcl::getInstance();
		$content_access_obj->checkListingAccess($listing_id, 'Manage all listings');

		$listing = new listing($level_id, $listing_id);
		$listing->setListingFromArray($this->listings->getListingRowById(), $this->listings->getListingCategories(), $this->listings->getListingLocations());
		
		// --------------------------------------------------------------------------------------------
		// IF listing is in package - check if current level allowed for this package,
		// if not - set error and redirect
		// --------------------------------------------------------------------------------------------
		if ($listing->package) {
			$packages_model = $this->load->model('packages', 'packages');
			$my_packages_obj = $packages_model->getUserPackages($listing->owner_id);

			foreach ($my_packages_obj->packages AS $user_package) {
				if ($user_package->id == $listing->package->id) {
					if ($user_package->listings_left[$listing->level_id] !== 'unlimited' && $user_package->listings_left[$listing->level_id] <= 0) {
						$this->setError(LANG_PACKAGE_LEVEL_NOT_ALLOWED);
						redirect($this->session->userdata('back_page'));
					}
				}
			}
		}
		// --------------------------------------------------------------------------------------------
		
		if ($this->listings->prolongListing($listing)) {
			$this->setSuccess(LANG_LISTING_PROLONG_SUCCESS_1 . ' "' . $listing->title() . '" ' . LANG_LISTING_PROLONG_SUCCESS_2);
			$user_login = $this->session->userdata('user_login');
			$user_email = $this->session->userdata('user_email');
			// raise Listing prolonging event
			$event_params = array(
				'LISTING_ID' => $listing_id, 
				'LISTING_TITLE' => $listing->title(), 
				'LISTING_TYPE' => $listing->type->name,
				'LISTING_LEVEL' => $listing->level->name,
				'RECIPIENT_NAME' => $listing->user->login,
				'RECIPIENT_EMAIL' => $listing->user->email
			);
			$notification = new notificationSender('Listing prolonging');
			$notification->send($event_params);
			events::callEvent('Listing prolonging', $event_params);
		}
		redirect($this->session->userdata('back_page'));
	}
	
	// --------------------------------------------------------------------------------------------
    // Claiming routes
    // --------------------------------------------------------------------------------------------
	public function set_claim($listing_url_part)
	{
		$this->load->model('listings');
		if ($listing_row = $this->listings->getListingRowByUrl($listing_url_part)) {
			if ($this->session->userdata('user_id')) {
				$listing_id = $listing_row['id'];
				$listing = new listing($listing_row['level_id'], $listing_id);
				$listing->setListingFromArray($listing_row, $this->listings->getListingCategories($listing_id), $this->listings->getListingLocations($listing_id));
	
				if ($listing->claim_row['ability_to_claim']) {
					if ($this->listings->setClaim($listing->claim_row['id'])) {
						// Clean cache
						$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_' . $listing_id));

						$system_settings = registry::get('system_settings');
						$event_params = array(
							'LISTING_TITLE' => $listing->title(), 
							'SENDER_NAME' => $this->session->userdata('user_login'),
							'SENDER_EMAIL' => $this->session->userdata('user_email'),
							'RECIPIENT_NAME' => $listing->user->login,
							'RECIPIENT_EMAIL' => $listing->user->email,
						);
						$notification = new notificationSender('Listing claim');
						if ($notification->send($event_params))
							$this->setSuccess(LANG_LISTINGS_SET_CLAIM_SUCCESS);
					} else {
						$this->setError(LANG_LISTINGS_CLAIM_ERROR);
					}
				} else {
					$this->setError(LANG_LISTINGS_CLAIM_ERROR);
				}
			} else {
				$this->setError(LANG_LISTINGS_AUTHORIZATION_CLAIM_ERROR);
			}
		} else {
			$this->setError(LANG_LISTINGS_CLAIM_ERROR);
		}
		$this->load->library('user_agent');
		redirect($this->agent->referrer());
	}
	
	public function approve_claim($listing_id)
	{
		$this->load->model('listings');
		if ($claim_row = $this->listings->getClaimRow($listing_id)) {
			$this->load->model('users', 'users');
			if ($user = $this->users->getUserById($claim_row['to_user_id'])) {
				if ($this->listings->delegateListingToUser($listing_id, $user->id)) {
					// Clean cache
					$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_' . $listing_id));

					$this->listings->approveClaim($listing_id);
					
					$listing = $this->listings->getListingById($listing_id);
					$event_params = array(
						'LISTING_TITLE' => $listing->title(), 
						'RECIPIENT_NAME' => $user->login,
						'RECIPIENT_EMAIL' => $user->email,
					);
					$notification = new notificationSender('Listing claim approved');
					$notification->send($event_params);

					$this->setSuccess(LANG_LISTINGS_APPROVE_CLAIM_SUCCESS . $user->login);
				}
			} else {
				$this->setError(LANG_LISTINGS_CLAIM_ERROR);
			}
		} else {
			$this->setError(LANG_LISTINGS_CLAIM_ERROR);
		}
		redirect('admin/listings/search');
	}

	public function decline_claim($listing_id)
	{
		$this->load->model('listings');
		if ($claim_row = $this->listings->getClaimRow($listing_id)) {
			$this->load->model('users', 'users');
			if ($user = $this->users->getUserById($claim_row['to_user_id'])) {
				if ($claim_row['to_user_id'] && $claim_row['approved']) {
					$this->listings->rollBackListingFromUser($listing_id, $claim_row['from_user_id']);
					// Clean cache
					$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_'.$listing_id));
					
					$this->setSuccess(LANG_LISTINGS_ROLLBACK_CLAIM_SUCCESS);
				}
				if ($this->listings->declineClaim($listing_id)) {
					$listing = $this->listings->getListingById($listing_id);
					$event_params = array(
						'LISTING_TITLE' => $listing->title(), 
						'RECIPIENT_NAME' => $user->login,
						'RECIPIENT_EMAIL' => $user->email,
					);
					$notification = new notificationSender('Listing claim declined');
					$notification->send($event_params);
	
					$this->setSuccess(LANG_LISTINGS_DECLINE_CLAIM_SUCCESS);
				} else {
					$this->setError(LANG_LISTINGS_CLAIM_ERROR);
				}
			} else {
				$this->setError(LANG_LISTINGS_CLAIM_ERROR);
			}
		} else {
			$this->setError(LANG_LISTINGS_CLAIM_ERROR);
		}
		redirect('admin/listings/search');
	}
}
?>