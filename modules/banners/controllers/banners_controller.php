<?php
include_once(MODULES_PATH . 'ajax_files_upload/classes/files_upload.class.php');
include_once(MODULES_PATH . 'banners/classes/banners_acl.class.php');
include_once(MODULES_PATH . 'notifications/classes/notification_sender.class.php');

class bannersController extends controller
{
	public function index($argsString = '')
    {
		$args = parseUrlArgs($argsString);

		// Search url needs for 'asc_desc_insert.base_url' argument of smarty function
		$clean_url = site_url('admin/banners/search/');
		$base_url = $clean_url;
		$search_url = $base_url;
		if (isset($args['search_block_id'])) {
			$search_block_id = $args['search_block_id'];
			$search_url .= 'search_block_id/' . $search_block_id . '/';
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
		$this->load->model('banners');
		$this->banners->setPaginator($paginator);
		$banners = $this->banners->selectBanners($orderby, $direction, $args);
		
		$this->load->model('banners_blocks');
		$banners_blocks = $this->banners_blocks->getBannersBlocks();

		$view = $this->load->view();
		$view->assign('banners', $banners);
		$view->assign('banners_count', $paginator->count());
		$view->assign('paginator', $paginator->placeLinksToHtml());

		$view->assign('args', $args);
		$view->assign('orderby', $orderby);
		$view->assign('direction', $direction);
		$view->assign('clean_url', $clean_url);
		$view->assign('base_url', $base_url);
		$view->assign('search_url', $search_url);

		if (isset($args["search_creation_date"])) {
			$view->assign('creation_date', date("y-m-d", $args["search_creation_date"]));
			$view->assign('creation_date_tmstmp', $args["search_creation_date"]);
		}
		$mode = 'single';
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

		$view->assign('banners_blocks', $banners_blocks);
		$this->session->set_userdata('back_page', uri_string());
        $view->display('banners/admin_search_banners.tpl');
    }
    
    public function my($argsString = '')
    {
		$args = parseUrlArgs($argsString);

		// Search url needs for 'asc_desc_insert.base_url' argument of smarty function
		$search_url = site_url('admin/banners/my/');

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
		$this->load->model('banners');
		$this->banners->setPaginator($paginator);
		$banners = $this->banners->selectMyBanners($orderby, $direction);

		$view = $this->load->view();
		$view->assign('banners', $banners);
		$view->assign('banners_count', $paginator->count());
		$view->assign('paginator', $paginator->placeLinksToHtml());

		$view->assign('args', $args);
		$view->assign('orderby', $orderby);
		$view->assign('direction', $direction);
		$view->assign('search_url', $search_url);

		$this->session->set_userdata('back_page', uri_string());
        $view->display('banners/admin_my_banners.tpl');
    }
    
	public function explode_checked_list($list)
	{
		return explode('*', $list);
	}
    
    public function create($block_id = null)
    {
        if (is_null($block_id)) {
        	// Step 1
        	// Select banners block
        	$this->load->model('banners_blocks');
        	$banners_blocks = $this->banners_blocks->getBannersBlocks();

        	$prices_array = array();
        	if ($banners_prices = registry::get('banners_prices')) {
				foreach ($banners_blocks AS $block_key=>$block) {
					foreach ($banners_prices AS $price) {
						if ($block->id == $price['block_id'] && $this->session->userdata('user_group_id') == $price['group_id']) {
							$prices_array[$block->id]['price_currency'] = $price['currency'];
							$prices_array[$block->id]['price_value'] = $price['value'];
						}
					}
				}
			}
        	
        	$view  = $this->load->view();
	        $view->assign('banners_blocks', $banners_blocks);
	        $view->assign('banners_prices', $prices_array);
	        $view->display('banners/admin_add_banner_select_block.tpl');
        } else {
        	// Step 2
        	$this->load->model('banners');
        	$this->load->model('banners_blocks');
	        $this->banners_blocks->setBlockId($block_id);
	        $banners_block = $this->banners_blocks->getBannersBlockById();

        	if ($this->input->post('submit')) {
        		$this->form_validation->set_rules('url', LANG_BANNER_LINK_URL, 'required|max_length[255]');
            	$this->form_validation->set_rules('banner_file', LANG_BANNER_IMAGE, 'max_length[255]');
            	$this->form_validation->set_rules('is_uploaded_flash', LANG_IS_BANNER_FLASH);
            	$this->form_validation->set_rules('locations_checked_list', LANG_LOCATIONS_LIST, 'callback_explode_checked_list');
            	$this->form_validation->set_rules('use_all_locations', LANG_BANNERS_IN_ALL_LOCATIONS, 'is_checked');
            	$this->form_validation->set_rules('categories_checked_list', LANG_CATEGORIES_LIST, 'callback_explode_checked_list');
           		$this->form_validation->set_rules('use_all_categories', LANG_BANNERS_IN_ALL_CATEGORIES, 'is_checked');
            	if ($banners_block->allow_remote_banners) {
            		$this->form_validation->set_rules('use_remote_image', LANG_USE_REMOTE_BANNER, 'is_checked');
            		$this->form_validation->set_rules('remote_image_url', LANG_BANNER_REMOTE_IMAGE, 'max_length[255]');
            		$this->form_validation->set_rules('is_loaded_flash', LANG_IS_BANNER_FLASH);
            	}
            	
            	if ($this->form_validation->run() !== FALSE) {
            		$form_result = $this->form_validation->result_array();
            		
            		// Check if any banner file uploaded or remote image url entered;
            		if ((!isset($form_result['banner_file']) || !$form_result['banner_file']) && (!isset($form_result['remote_image_url']) || !$form_result['remote_image_url'] || (isset($form_result['remote_image_url']) && $form_result['remote_image_url'] && !$this->checkRemoteImage($block_id, $form_result['remote_image_url'], $error, $flash)))) {
            			$this->setError('Any banner file must be uploaded or remote image url entered!');
            			$banner = $this->banners->getBannerFromForm($block_id, $this->form_validation->result_array());
            		} else {
	            		if ($banner_id = $this->banners->saveBanner($block_id, $form_result, $banners_block)) {
							$this->setSuccess(LANG_BANNERS_CREATE_SUCCESS);
							// raise Banner creation event
							$event_params = array(
								'BANNER_ID' => $banner_id,
								'BANNER_URL' => $this->form_validation->set_value('url'), 
								'BANNER_BLOCK_ID' => $block_id, 
								'BANNER_BLOCK_NAME' => $banners_block->name, 
								'RECIPIENT_NAME' => $this->session->userdata('user_login'),
								'RECIPIENT_EMAIL' => $this->session->userdata('user_email')
							);
							$notification = new notificationSender('Banner creation');
							$notification->send($event_params);
							events::callEvent('Banner creation', $event_params);
	
							redirect('admin/banners/my/');
						}
            		}
            	} else {
            		$banner = $this->banners->getBannerFromForm($block_id, $this->form_validation->result_array());
            	}
        	} else {
        		$banner = $this->banners->getNewBanner($block_id);
        	}

			$file_to_upload = new filesUpload;
			$file_to_upload->title = LANG_BANNER_IMAGE;
			$file_to_upload->upload_id = 'banner_file';
			$file_to_upload->after_upload_url = site_url('ajax/banners/get_image/' . $block_id);
			$file_to_upload->attrs['banner_obj'] = $banner;

			if (strpos($this->session->userdata('back_page'), '/admin/banners/search/') !== FALSE) {
		    	registry::set('breadcrumbs', array(
		    		$this->session->userdata('back_page') => LANG_BANNERS_SEARCH_TITLE,
		    		LANG_BANNERS_ADD_BANNER_TITLE,
		    	));
	    	} elseif (strpos($this->session->userdata('back_page'), '/admin/banners/my/') !== FALSE) {
	    		registry::set('breadcrumbs', array(
		    		$this->session->userdata('back_page') => LANG_BANNERS_MY_TITLE,
		    		LANG_BANNERS_ADD_BANNER_TITLE,
		    	));
	    	}

			$view = $this->load->view();
			$view->addJsFile('ajaxfileupload.js');
			$view->addJsFile('jquery.jstree.js');
			$view->assign('banner_upload_block', $file_to_upload);

	        //$view->assign('banners_block', $banners_block);
	        $view->assign('banner', $banner);
	        if ($banner->block->allow_remote_banners) {
	        	$view->assign('error_url_enter', 'Select file to load first!');

	        	$users_content_array = $this->config->item('users_content');
	        	$view->assign('allowed_types', $users_content_array['banner_file']['allowed_types']);
	        }
	        $view->display('banners/admin_banner_settings.tpl');
        }
    }
    
    /**
	 * Handle uploaded banner file and process it through image resize functions
	 */
	public function get_image($block_id)
    {
    	if ($this->input->post('uploaded_file')) {
    		$uploaded_file = $this->input->post('uploaded_file');
			$users_content_server_path = $this->config->item('users_content_server_path');
			$users_content_array = $this->config->item('users_content');
			$banner_image_path_to_upload = $users_content_array['banner_file']['upload_to'];

			$this->load->model('banners_blocks');
			$this->banners_blocks->setBlockId($block_id);
        	$banners_block = $this->banners_blocks->getBannersBlockById();

        	$banner_file_info = getimagesize($uploaded_file);
			$mime_type = $banner_file_info['mime'];
        	
			if ($mime_type == 'image/jpeg' || $mime_type == 'image/gif' || $mime_type == 'image/png') {
				$flash = false;
				$this->load->library('image_lib');
				$destImageSize[] = $banners_block->block_size;
				$destImageFolder[] = $users_content_server_path . $banner_image_path_to_upload;

				if ($this->image_lib->process_images('resize', $uploaded_file, $destImageFolder, $destImageSize)) {
					$file = $uploaded_file;
					$error = '';
				} else {
					$error = $this->image_lib->display_errors();
					$file = '';
				}
			} elseif ($mime_type == 'application/x-shockwave-flash') {
				// Flash banner
				$flash = true;
				$file = $uploaded_file;
				$error = '';
			} else {
				$flash = false;
				$error = "Banner has unsupported format!";
			}

			// JQuery .post needs JSON response
			echo json_encode(array('error_msg' => _utf8_encode($error), 'file_name' => basename($file), 'flash' => $flash));
		}
	}
	
	public function get_image_by_url($block_id)
	{
		if ($image_url = $this->input->post('image_url')) {
			$this->checkRemoteImage($block_id, $image_url, $error, $flash);

			// JQuery .post needs JSON response
			echo json_encode(array('error_msg' => _utf8_encode($error), 'file_name' => $image_url, 'flash' => $flash));
		}
	}
	
	public function checkRemoteImage($block_id, $image_url, &$error, &$flash)
	{
		$users_content_server_path = $this->config->item('users_content_server_path');
		$users_content_array = $this->config->item('users_content');
		$banner_image_path_to_upload = $users_content_array['banner_file']['upload_to'];
		$file = $users_content_server_path . $banner_image_path_to_upload . md5(time());
		if ($ch = curl_init($image_url)) {
			$fp = fopen($file, "wb");

			$options = array(CURLOPT_FILE => $fp,
			                 CURLOPT_HEADER => 0,
			                 CURLOPT_FOLLOWLOCATION => 1,
			                 CURLOPT_TIMEOUT => 10);
			curl_setopt_array($ch, $options);
			curl_exec($ch);
			curl_close($ch);
			fclose($fp);

			$banner_file_info = getimagesize($file);
			$mime_type = $banner_file_info['mime'];

			if ($mime_type == 'image/jpeg' || $mime_type == 'image/gif' || $mime_type == 'image/png') {
				$flash = false;
				$this->load->library('image_lib');
				if ($image_props = $this->image_lib->get_image_properties($file, true)) {
					$this->load->model('banners_blocks');
					$this->banners_blocks->setBlockId($block_id);
			       	$banners_block = $this->banners_blocks->getBannersBlockById();
			       	if ($image_props['width'] > $banners_block->explodeSize('block_size', 0) || $image_props['height'] > $banners_block->explodeSize('block_size', 1)) {
			       		$error = "Banner provided with this url has greater size than allowed!";
			       	} else {
			       		$error = '';
			       	}
				} else {
					$error = "Banner not found or has unsupported format!";
				}
			} elseif ($mime_type == 'application/x-shockwave-flash') {
				// Flash banner
				$flash = true;
				$error = '';
			} else {
				$flash = false;
				$error = "Banner not found or has unsupported format!";
			}
		} else {
			$error = "URL isn't existed!";
			$flash = false;
		}
		if (!$error)
			return true;
	}

    public function edit($banner_id)
    {
        $this->load->model('banners');
        $this->banners->setBannerId($banner_id);

        $banners_access = bannersAcl::getInstance();
		$banners_access->checkBannerAccess($banner_id, 'Manage all banners');
		
		$banner = $this->banners->getBannerById($banner_id);

       	if ($this->input->post('submit')) {
       		$this->form_validation->set_rules('url', LANG_BANNER_LINK_URL, 'required|max_length[255]');
           	$this->form_validation->set_rules('banner_file', LANG_BANNER_IMAGE, 'max_length[255]');
           	$this->form_validation->set_rules('is_uploaded_flash', LANG_IS_BANNER_FLASH);
           	$this->form_validation->set_rules('locations_checked_list', LANG_LOCATIONS_LIST, 'callback_explode_checked_list');
            $this->form_validation->set_rules('use_all_locations', LANG_BANNERS_IN_ALL_LOCATIONS, 'is_checked');
            $this->form_validation->set_rules('categories_checked_list', LANG_CATEGORIES_LIST, 'callback_explode_checked_list');
           	$this->form_validation->set_rules('use_all_categories', LANG_BANNERS_IN_ALL_CATEGORIES, 'is_checked');
           	if ($banner->block->allow_remote_banners) {
            	$this->form_validation->set_rules('use_remote_image', LANG_USE_REMOTE_BANNER, 'is_checked');
            	$this->form_validation->set_rules('remote_image_url', LANG_BANNER_REMOTE_IMAGE, 'max_length[255]');
            	$this->form_validation->set_rules('is_loaded_flash', LANG_IS_BANNER_FLASH);
            }

           	if ($this->form_validation->run() !== FALSE) {
           		$form_result = $this->form_validation->result_array();
           		// Check if any banner file uploaded or remote image url entered;
            	if ((!isset($form_result['banner_file']) || !$form_result['banner_file']) && (!isset($form_result['remote_image_url']) || !$form_result['remote_image_url'])) {
            		$this->setError('Any banner file must be uploaded or remote image url entered!');
            		$banner = $this->banners->getBannerFromForm($block_id, $this->form_validation->result_array());
            	} else {
	           		if ($this->banners->saveBannerById($form_result)) {
	           			// Clean cache
						$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('banners'));
	
						$this->setSuccess(LANG_BANNERS_SAVE_SUCCESS);
						redirect($this->session->userdata('back_page'));
					}
            	}
           	} else {
           		$banner = $this->banners->getBannerFromForm($banner->block_id, $this->form_validation->result_array());
           	}
       	}

		$file_to_upload = new filesUpload;
		$file_to_upload->title = LANG_BANNER_IMAGE;
		$file_to_upload->upload_id = 'banner_file';
		$file_to_upload->after_upload_url = site_url('ajax/banners/get_image/' . $banner->block_id);
		$file_to_upload->attrs['banner_obj'] = $banner;

    	if (strpos($this->session->userdata('back_page'), '/admin/banners/search/') !== FALSE) {
	    	registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_BANNERS_SEARCH_TITLE,
	    		LANG_BANNERS_EDIT_BANNER_TITLE,
	    	));
    	} elseif (strpos($this->session->userdata('back_page'), '/admin/banners/my/') !== FALSE) {
    		registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_BANNERS_MY_TITLE,
	    		LANG_BANNERS_EDIT_BANNER_TITLE,
	    	));
    	}

		$view = $this->load->view();
		$view->addJsFile('ajaxfileupload.js');
		$view->addJsFile('jquery.jstree.js');
		$view->assign('banner_upload_block', $file_to_upload);
        $view->assign('banner', $banner);
        if ($banner->block->allow_remote_banners) {
	       	$view->assign('error_url_enter', 'Select file to load first!');

	       	$users_content_array = $this->config->item('users_content');
	       	$view->assign('allowed_types', $users_content_array['banner_file']['allowed_types']);
		}
        $view->display('banners/admin_banner_settings.tpl');
    }
    
    public function view($banner_id)
    {
		$this->load->model('banners');

        $banners_access = bannersAcl::getInstance();
		$banners_access->checkBannerAccess($banner_id, 'Manage all banners');

       	$banner = $this->banners->getBannerById($banner_id);

    	if (strpos($this->session->userdata('back_page'), '/admin/banners/search/') !== FALSE) {
	    	registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_BANNERS_SEARCH_TITLE,
	    		LANG_BANNERS_VIEW_BANNER_TITLE,
	    	));
    	} elseif (strpos($this->session->userdata('back_page'), '/admin/banners/my/') !== FALSE) {
    		registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_BANNERS_MY_TITLE,
	    		LANG_BANNERS_VIEW_BANNER_TITLE,
	    	));
    	}

		$view = $this->load->view();
        $view->assign('banner', $banner);
        $view->display('banners/admin_view_banner.tpl');
    }
    
    /**
	 * Delete just one banner
	 * 
	 */
	public function delete($banner_id)
    {
        $this->load->model('banners');
        $this->banners->setBannerId($banner_id);

        $banners_access = bannersAcl::getInstance();
		$banners_access->checkBannerAccess($banner_id, 'Manage all banners');

		if ($this->input->post('yes')) {
            if ($this->banners->deleteBannerById()) {
            	// Clean cache
				$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('banners'));

            	$this->setSuccess(LANG_BANNERS_DELETE_SUCCESS);
                redirect($this->session->userdata('back_page'));
            }
        }

        if ($this->input->post('no')) {
            redirect($this->session->userdata('back_page'));
        }

        if ( !$banner = $this->banners->getBannerById($banner_id)) {
            redirect($this->session->userdata('back_page'));
        }
        
        if (strpos($this->session->userdata('back_page'), '/admin/banners/search/') !== FALSE) {
	    	registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_BANNERS_SEARCH_TITLE,
	    		LANG_BANNERS_DELETE_BANNER_TITLE,
	    	));
    	} elseif (strpos($this->session->userdata('back_page'), '/admin/banners/my/') !== FALSE) {
    		registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_BANNERS_MY_TITLE,
	    		LANG_BANNERS_DELETE_BANNER_TITLE,
	    	));
    	}

		$view  = $this->load->view();
		$view->assign('options', array($banner_id));
        $view->assign('heading', LANG_DELETE_BANNERS);
        $view->assign('question', LANG_DELETE_BANNERS_QUEST);
        $view->display('backend/delete_common_item.tpl');
    }
	
	/**
	 * Delete group of banners
	 * 
	 */
	public function massDelete()
    {
    	$this->load->model('banners');

		$banners_ids = $this->input->searchPostItems('cb_');
		if (empty($banners_ids)) {
			$banners_ids = $this->input->post('options');
		}
		$banners_array = array();
		$banners_access = bannersAcl::getInstance();
		if (!empty($banners_ids)) {
			foreach ($banners_ids AS $id) {
				$banners_access->checkBannerAccess($id, 'Manage all banners');

				$this->banners->setBannerId($id);
				$banner = $this->banners->getBannerById();
				$banners_array[$id] = $banner->url;
			}
		} else {
			$this->setError(LANG_BANNERS_SELECT_ERROR);
			redirect($this->session->userdata('back_page'));
		}

        if ($this->input->post('yes')) {
            if ($this->banners->deleteBanners($banners_array)) {
            	// Clean cache
				$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('banners'));

                $this->setSuccess(LANG_BANNERS_DELETE_SUCCESS);
                redirect($this->session->userdata('back_page'));
            }
        }

        if ($this->input->post('no')) {
            redirect($this->session->userdata('back_page'));
        }
        
        if (strpos($this->session->userdata('back_page'), '/admin/banners/search/') !== FALSE) {
	    	registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_BANNERS_SEARCH_TITLE,
	    		LANG_BANNERS_DELETE_BANNER_TITLE,
	    	));
    	} elseif (strpos($this->session->userdata('back_page'), '/admin/banners/my/') !== FALSE) {
    		registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_BANNERS_MY_TITLE,
	    		LANG_BANNERS_DELETE_BANNER_TITLE,
	    	));
    	}

        $view  = $this->load->view();
		$view->assign('options', $banners_array);
		$view->assign('heading', LANG_DELETE_BANNERS);
        $view->assign('question', LANG_DELETE_BANNERS_QUEST);
        $view->display('backend/delete_common_item.tpl');
    }
    
    /**
	 * Block checked banners
	 * 
	 */
	public function block()
    {
    	$this->load->model('banners');

		$banners_ids = $this->input->searchPostItems('cb_');
		$banners_array = array();
		$banners_access = bannersAcl::getInstance();
		if (!empty($banners_ids)) {
			foreach ($banners_ids AS $id) {
				$banners_access->checkBannerAccess($id, 'Manage all banners');
				$this->banners->setBannerId($id);
				$banner = $this->banners->getBannerById();
				$banners_array[$id] = $banner;
			}
		} else {
			$this->setError(LANG_BANNERS_SELECT_ERROR);
			redirect('admin/banners/search/');
		}
		
		if ($this->banners->blockBanners($banners_ids)) {
			$this->setSuccess(LANG_BANNERS_BLOCK_SUCCESS);

			foreach ($banners_array AS $banner) {
				if ($banner->status != 2) {
					// raise Banner blocking event
					$event_params = array(
						'BANNER_ID' => $banner->id, 
						'BANNER_URL' => $banner->url, 
						'RECIPIENT_NAME' => $banner->user->login,
						'RECIPIENT_EMAIL' => $banner->user->email
					);
					$notification = new notificationSender('Banner blocking');
					$notification->send($event_params);
					events::callEvent('Banner blocking', $event_params);
				}
			}
			redirect('admin/banners/search/');
		}
    }
    
    /**
	 * Acivate checked banners
	 * 
	 */
	public function activate()
    {
		$this->load->model('banners');

		$banners_ids = $this->input->searchPostItems('cb_');
		$banners_array = array();
		$banners_access = bannersAcl::getInstance();
		if (!empty($banners_ids)) {
			foreach ($banners_ids AS $id) {
				$banners_access->checkBannerAccess($id, 'Manage all banners');
				$this->banners->setBannerId($id);
				$banner = $this->banners->getBannerById();
				$banners_array[$id] = $banner;
			}
		} else {
			$this->setError(LANG_BANNERS_SELECT_ERROR);
			redirect('admin/banners/search/');
		}
		
		if ($this->banners->activateBanners($banners_ids)) {
			$this->setSuccess(LANG_BANNERS_ACTIVATE_SUCCESS);

			redirect('admin/banners/search/');
		}
    }
    
    /**
	 * change banner status page for the admin account
	 *
	 */
	public function change_status($banner_id)
	{
		$this->load->model('banners');
		$this->banners->setBannerId($banner_id);

        $banners_access = bannersAcl::getInstance();
		$banners_access->checkBannerAccess($banner_id, 'Manage all banners');

       	$banner = $this->banners->getBannerById($banner_id);

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('status', LANG_BANNER_STATUS, 'required');

			if ($this->form_validation->run() !== FALSE) {
				if ($this->banners->saveBannerStatus($this->form_validation->set_value('status'))) {
					$this->setSuccess(LANG_BANNER_STATUS_SAVE_SUCCESS);

					if ($banner->status != 2 && $this->form_validation->set_value('status') == 2) {
						// raise Banner blocking event
						$event_params = array(
							'BANNER_ID' => $banner_id, 
							'BANNER_URL' => $banner->url, 
							'RECIPIENT_NAME' => $banner->user->login,
							'RECIPIENT_EMAIL' => $banner->user->email
						);
						$notification = new notificationSender('Banner blocking');
						$notification->send($event_params);
						events::callEvent('Banner blocking', $event_params);
					}
					redirect($this->session->userdata('back_page'));
				}
			}
        }
        
        if (strpos($this->session->userdata('back_page'), '/admin/banners/search/') !== FALSE) {
	    	registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_BANNERS_SEARCH_TITLE,
	    		LANG_BANNERS_CHANGE_STATUS_BANNER_TITLE,
	    	));
    	} elseif (strpos($this->session->userdata('back_page'), '/admin/banners/my/') !== FALSE) {
    		registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_BANNERS_MY_TITLE,
	    		LANG_BANNERS_CHANGE_STATUS_BANNER_TITLE,
	    	));
    	}

		$view = $this->load->view();
		$view->assign('banner', $banner);
		$view->assign('banner_id', $banner_id);
		$view->display('banners/admin_banner_status.tpl');
	}

	public function prolong($banner_id)
	{
		$this->load->model('banners');
		$this->banners->setBannerId($banner_id);

        $banners_access = bannersAcl::getInstance();
		$banners_access->checkBannerAccess($banner_id, 'Manage all banners');

       	$banner = $this->banners->getBannerById($banner_id);

		if ($this->banners->prolongBanner($banner)) {
			$this->setSuccess(LANG_BANNER_PROLONG_SUCCESS);

			// raise Banner prolong event
			$event_params = array(
				'BANNER_ID' => $banner_id, 
				'BANNER_URL' => $banner->url, 
				'RECIPIENT_NAME' => $banner->user->login,
				'RECIPIENT_EMAIL' => $banner->user->email
			);
			$notification = new notificationSender('Banner prolonging');
			$notification->send($event_params);
			events::callEvent('Banner prolonging', $event_params);
		}
		redirect($this->session->userdata('back_page'));
	}
	
	public function click_tracing($banner_id)
	{
		$this->load->model('banners');
		$this->banners->setBannerId($banner_id);
		$this->banners->incrementBannerClick();
	}
}
?>