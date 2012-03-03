<?php
include_once(MODULES_PATH . 'users/classes/user.class.php');
include_once(MODULES_PATH . 'users/classes/user_suggestions.class.php');
include_once(MODULES_PATH . 'notifications/classes/notification_sender.class.php');
include_once(MODULES_PATH . 'ajax_files_upload/classes/files_upload.class.php');

class usersController extends controller
{
	public function search($argsString = '')
	{
		$args = parseUrlArgs($argsString);

		// Search url needs for 'asc_desc_insert.base_url' argument of smarty function
		$clean_url = site_url('admin/users/search/');
		$base_url = $clean_url;
		$search_url = $base_url;
		if (isset($args['search_login'])) {
			$search_login = $args['search_login'];
			$search_url .= 'search_login/' . $search_login . '/';
		}
		if (isset($args['search_email'])) {
			$search_email = $args['search_email'];
			$search_url .= 'search_email/' . $search_email . '/';
		}
		if (isset($args['search_group'])) {
			$search_group = $args['search_group'];
			$search_url .= 'search_group/' . $search_group . '/';
		}
		if (isset($args['search_status'])) {
			$search_status = $args['search_status'];
			$search_url .= 'search_status/' . $search_status . '/';
		}
		if (isset($args['search_last_visit_date'])) {
			$search_last_visit_date = $args['search_last_visit_date'];
			$search_url .= 'search_last_visit_date/' . $search_last_visit_date . '/';
		}
		if (isset($args['search_from_last_visit_date'])) {
			$search_from_last_visit_date = $args['search_from_last_visit_date'];
			$search_url .= 'search_from_last_visit_date/' . $search_from_last_visit_date . '/';
		}
		if (isset($args['search_to_last_visit_date'])) {
			$search_to_last_visit_date = $args['search_to_last_visit_date'];
			$search_url .= 'search_to_last_visit_date/' . $search_to_last_visit_date . '/';
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
		$this->load->model('users');
		$this->users->setPaginator($paginator);
		$users = $this->users->selectUsers($orderby, $direction, $args);
		
		$this->load->model('users_groups');
		$users_groups = $this->users_groups->getUsersGroups();

		$view = $this->load->view();
		$view->assign('users', $users);
		$view->assign('groups_list', $users_groups);
		$view->assign('users_count', $paginator->count());
		$view->assign('paginator', $paginator->placeLinksToHtml());

		$view->assign('args', $args);
		$view->assign('orderby', $orderby);
		$view->assign('direction', $direction);
		$view->assign('base_url', $base_url);
		$view->assign('search_url', $search_url);
		
		$mode = 'single';
		if (isset($args["search_last_visit_date"])) {
			$view->assign('last_visit_date', date("y-m-d", $args["search_last_visit_date"]));
			$view->assign('last_visit_date_tmstmp', $args["search_last_visit_date"]);
		}
		if (isset($args["search_from_last_visit_date"])) {
			$view->assign('from_last_visit_date', date("y-m-d", $args["search_from_last_visit_date"]));
			$view->assign('from_last_visit_date_tmstmp', $args["search_from_last_visit_date"]);
			$mode = 'range';
		}
		if (isset($args["search_to_last_visit_date"])) {
			$view->assign('to_last_visit_date', date("y-m-d", $args["search_to_last_visit_date"]));
			$view->assign('to_last_visit_date_tmstmp', $args["search_to_last_visit_date"]);
			$mode = 'range';
		}
		$view->assign('mode', $mode);

		// Add js library for Send Message feature
		$view->addJsFile('jquery.jqURL.js');

		$this->session->set_userdata('back_page', uri_string());
		$view->display('users/admin_search_users.tpl');
	}
	
	public function change_group($user_id)
	{
		$this->load->model('users');
		$this->users->setUserId($user_id);
		$user = $this->users->getUserById();

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('group_id', LANG_USERS_GROUP_NAME_TH, 'required|integer');

			if ($this->form_validation->run() !== FALSE) {
				$form = $this->form_validation->result_array();
				if ($this->users->saveUserGroup($form)) {
					// Clean cache
					$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('users_' . $user_id));

					$this->setSuccess(LANG_USER_SAVE_SUCCESS_1 . ' "' . $user->login . '" ' . LANG_USER_SAVE_SUCCESS_2);
					redirect('admin/users/search/');
				}
			}
        }

        $this->load->model('users_groups');
		$users_groups = $this->users_groups->getUsersGroups();
		
		if (strpos($this->session->userdata('back_page'), '/admin/users/search/') !== FALSE)
			$back_page = $this->session->userdata('back_page');
		else 
			$back_page = site_url('admin/users/search/');
		registry::set('breadcrumbs', array(
	    	$back_page => LANG_SEARCH_USERS_TITLE,
	    	'admin/users/view/' . $user_id => LANG_VIEW_USER_PROFILE . ' "' . $user->login . '"',
	    	LANG_CHANGE_USERS_GROUP . ' "' . $user->login . '"',
	    ));
        
		$view = new view;
		$view->assign('user', $user);
		$view->assign('users_groups', $users_groups);
		$view->display('users/change_user_group.tpl');
	}
	
	public function change_status($user_id)
	{
		$this->load->model('users');
		$this->users->setUserId($user_id);
		$user = $this->users->getUserById();

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('status', LANG_USERS_STATUS_TH, 'required|integer');

            if ($this->form_validation->run() !== FALSE) {
				$form = $this->form_validation->result_array();
				if ($this->users->saveUserStatus($form)) {
					// Clean cache
					$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('users_' . $user_id));

					$this->setSuccess(LANG_USER_SAVE_STATUS_SUCCESS_1 . ' "' . $user->login . '" ' . LANG_USER_SAVE_STATUS_SUCCESS_2);
					if ($user->status == 2 && $form['status'] == 3) {
						// Raise event User blocking
						$event_params = array(
							'RECIPIENT_NAME' => $user->login,
							'RECIPIENT_EMAIL' => $user->email,
							'USER' => $user
						);
						$notification = new notificationSender('User blocking');
						$notification->send($event_params);
						events::callEvent('User blocking', $event_params);
					}
					redirect('admin/users/search/');
				}
			}
        }
        
        if (strpos($this->session->userdata('back_page'), '/admin/users/search/') !== FALSE)
			$back_page = $this->session->userdata('back_page');
		else 
			$back_page = site_url('admin/users/search/');
        registry::set('breadcrumbs', array(
	    	$back_page => LANG_SEARCH_USERS_TITLE,
	    	'admin/users/view/' . $user_id => LANG_VIEW_USER_PROFILE . ' "' . $user->login . '"',
	    	LANG_CHANGE_USERS_STATUS . ' "' . $user->login . '"',
	    ));

		$view = $this->load->view();
		$view->assign('user', $user);
		$view->display('users/change_user_status.tpl');
	}
	
	public function create($argsString = '')
	{
		$args = parseUrlArgs($argsString);
		
		$this->load->model('users_groups');
		$users_groups = $this->users_groups->getUsersGroups();
		
		if (!isset($args['group_id'])) {
			$users_group_id = $this->users_groups->getDefaultUsersGroup()->id;
		} else {
			$users_group_id = $args['group_id'];
		}

		$user = new user($users_group_id);

        if ($this->input->post('submit')) {
			$this->form_validation->set_rules('password', LANG_USER_PROFILE_PASSWORD, 'max_length[20]|min_length[5]|matches[repeat_password]');
			$this->form_validation->set_rules('repeat_password', LANG_USER_PROFILE_PASSWORD_REPEAT);
            $this->form_validation->set_rules('email', LANG_USER_EMAIL, 'required|valid_email|callback_check_unique_email');
            $this->form_validation->set_rules('login', LANG_USER_LOGIN, 'required|min_length[4]|max_length[60]|callback_check_unique_login');
            if ($user->users_group->is_own_page) {
	            if ($user->users_group->use_seo_name)
	            	$this->form_validation->set_rules('seo_login', LANG_USER_SEO_LOGIN, 'callback_check_unique_seo_login');
	            if ($user->users_group->meta_enabled) {
	            	$this->form_validation->set_rules('meta_description', LANG_USER_META_DESCRIPTION);
	            	$this->form_validation->set_rules('meta_keywords', LANG_USER_KEYWORDS);
	            }
            }
			if ($user->users_group->logo_enabled)
	           	$this->form_validation->set_rules('user_logo_image', LANG_CUSTOM_USER_LOGO_IMAGE, 'max_length[255]');
			$user->validateFields($this->form_validation);

			if ($this->form_validation->run() !== FALSE) {
				$form = $this->form_validation->result_array();
				$hash = md5(time());
				if ($this->users->createUser($users_group_id, $user, $hash, $form, 2)) {
					if ($user->updateFields($form)) {
						$this->setSuccess(LANG_USER_SAVE_PROFILE_1 . ' "' . $form['login'] . '" ' . LANG_USER_SAVE_PROFILE_2);
						redirect('admin/users/search/');
					}
				}
			} else {
	            $user->setUserFromArray($this->form_validation->result_array());
	            $user->content_fields->getValuesFromForm($this->form_validation->result_array());
			}
		}

		$view = $this->load->view();
		// --------------------------------------------------------------------------------------------
		// Set up upload logo block
		if ($user->users_group->logo_enabled) {
			$file_to_upload = new filesUpload;
			$file_to_upload->title = LANG_CUSTOM_USER_LOGO_IMAGE;
			$file_to_upload->upload_id = 'user_logo_image';
			$file_to_upload->current_file = $user->user_logo_image;
			$file_to_upload->after_upload_url = site_url('admin/users/get_logo/' . $user->users_group->id);
			$file_to_upload->attrs['width'] = $user->users_group->explodeSize('logo_size', 'width');
			$file_to_upload->attrs['height'] = $user->users_group->explodeSize('logo_size', 'height');

			$view->addJsFile('ajaxfileupload.js');
			$view->assign('image_upload_block', $file_to_upload);
		}
		// --------------------------------------------------------------------------------------------

		$view->assign('selected_users_group_id', $users_group_id);
		$view->assign('user', $user);
		$view->assign('users_groups', $users_groups);
		$view->assign('url', site_url('admin/users/create/'));
		$view->display('users/admin_create_user.tpl');
	}
	
	public function profile($user_id)
	{
		// Protect first user account, editable only through 'edit my profile' menu item
		if ($user_id != 1) {
			$this->load->model('users');
			$this->users->setUserId($user_id);
			$user_array = $this->users->getUserArrayById($user_id);
			
			$user = new user($user_array['group_id'], $user_id);
			$user->setUserFromArray($user_array);

	        if ($this->input->post('submit')) {
				if ($this->input->post('password')) {
					$this->form_validation->set_rules('password', LANG_USER_PROFILE_PASSWORD, 'max_length[20]|min_length[5]|matches[repeat_password]');
					$this->form_validation->set_rules('repeat_password', LANG_USER_PROFILE_PASSWORD_REPEAT);
	        	}
	            $this->form_validation->set_rules('email', LANG_USER_EMAIL, 'required|valid_email|callback_check_unique_email');
	            $this->form_validation->set_rules('login', LANG_USER_LOGIN, 'required|min_length[4]|max_length[60]|callback_check_unique_login');
	            if ($user->users_group->is_own_page) {
		            if ($user->users_group->use_seo_name)
		            	$this->form_validation->set_rules('seo_login', LANG_USER_SEO_LOGIN, 'callback_check_unique_seo_login');
		            if ($user->users_group->meta_enabled) {
		            	$this->form_validation->set_rules('meta_description', LANG_USER_META_DESCRIPTION);
		            	$this->form_validation->set_rules('meta_keywords', LANG_USER_KEYWORDS);
		            }
	            }
	            $this->form_validation->set_rules('status', LANG_USER_STATUS, 'required');
	            if ($user->users_group->logo_enabled && $user->facebook_uid) {
	            	$this->form_validation->set_rules('use_facebook_logo', LANG_USE_FACEBOOK_LOGO_IMAGE, 'is_checked');
	            	$this->form_validation->set_rules('facebook_logo_file', LANG_USE_FACEBOOK_LOGO_IMAGE, 'max_length[255]');
	            }
	            if ($user->users_group->logo_enabled)
	            	$this->form_validation->set_rules('user_logo_image', LANG_CUSTOM_USER_LOGO_IMAGE, 'max_length[255]');
	            $user->validateFields($this->form_validation);
	            
	            if ($this->form_validation->run() !== FALSE) {
	            	$form = $this->form_validation->result_array();
	            	if ($this->users->saveUser($form, $user)) {
	            		// Clean cache
						$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('users_' . $user_id));
						
						if ($user->status == 2 && $form['status'] == 3) {
							// Raise event User blocking
							$event_params = array(
								'RECIPIENT_NAME' => $user->login,
								'RECIPIENT_EMAIL' => $user->email,
								'USER' => $user
							);
							$notification = new notificationSender('User blocking');
							$notification->send($event_params);
							events::callEvent('User blocking', $event_params);
						}

						if ($user->updateFields($form)) {
							$this->setSuccess(LANG_USER_SAVE_PROFILE_1 . ' "' . $form['login'] . '" ' . LANG_USER_SAVE_PROFILE_2);
							redirect('admin/users/search/');
						}
					}
	            } else {
	            	$user->setUserFromArray($this->form_validation->result_array());
	            	$user->content_fields->getValuesFromForm($this->form_validation->result_array());
	            }
	        }

	        if (strpos($this->session->userdata('back_page'), '/admin/users/search/') !== FALSE)
				$back_page = $this->session->userdata('back_page');
			else 
				$back_page = site_url('admin/users/search/');
		    registry::set('breadcrumbs', array(
		    	$back_page => LANG_SEARCH_USERS_TITLE,
		    	'admin/users/view/' . $user_id => LANG_VIEW_USER_PROFILE . ' "' . $user->login . '"',
		    	LANG_USER_PROFILE . ' "' . $user->login . '"',
		    ));

	        $view = $this->load->view();
	        // --------------------------------------------------------------------------------------------
	        // Set up upload logo block
	        if ($user->users_group->logo_enabled) {
				$file_to_upload = new filesUpload;
				$file_to_upload->title = LANG_CUSTOM_USER_LOGO_IMAGE;
				$file_to_upload->upload_id = 'user_logo_image';
				$file_to_upload->current_file = $user->user_logo_image;
				$file_to_upload->after_upload_url = site_url('admin/users/get_logo/' . $user->users_group->id);
				$file_to_upload->attrs['width'] = $user->users_group->explodeSize('logo_size', 'width');
				$file_to_upload->attrs['height'] = $user->users_group->explodeSize('logo_size', 'height');

				$view->addJsFile('ajaxfileupload.js');
				$view->assign('image_upload_block', $file_to_upload);

				if ($this->load->is_module_loaded('facebook') && $user->facebook_uid) {
					// --------------------------------------------------------------------------------------------
					// Work with facebook logo
					// --------------------------------------------------------------------------------------------
					if ($user_info = fcb_getUserInfo($user->facebook_uid))
						$view->assign('facebook_logo_file', $user_info['picture']);
					// --------------------------------------------------------------------------------------------
				}
			}
			// --------------------------------------------------------------------------------------------

	        $view->assign('user', $user);
			$view->display('users/admin_users_profiles.tpl');
		}
	}
	
	/**
	 * Handle uploaded logo file and process it through image resize functions
	 */
	public function get_logo($users_group_id)
    {
    	if ($this->input->post('uploaded_file')) {
    		$uploaded_file = $this->input->post('uploaded_file');
    		$crop = $this->input->post('crop');
			$users_content_server_path = $this->config->item('users_content_server_path');
			$users_content_settings = $this->config->item('users_content');

			$this->load->model('users_groups', 'users');
			$users_group = $this->users_groups->getUsersGroupById($users_group_id);

			if ($users_group->logo_enabled) {
				$this->load->library('image_lib');
				
				// Process all available thumbnails
				foreach ($users_content_settings['user_logo_image']['thumbnails'] AS $thmb) {
					if (isset($thmb['size']))
						$destImageSize[] = $thmb['size'];
					else
						$destImageSize[] = $users_group->logo_thumbnail_size;
					$destImageFolder[] = $users_content_server_path . $thmb['upload_to'];
					if (isset($thmb['crop']))
						$destImageCrop[] = $thmb['crop'];
					else 
						$destImageCrop[] = false;
				}

				$destImageSize[] = $users_group->logo_size;
				$destImageFolder[] = $users_content_server_path . $users_content_settings['user_logo_image']['upload_to'];
				if ($crop == 'true')
					$destImageCrop[] = true;
				else
					$destImageCrop[] = false;

				if ($this->image_lib->process_images('resize', $uploaded_file, $destImageFolder, $destImageSize, $destImageCrop)) {
					/*$image_size = getimagesize($uploaded_file);
					$logo_size = explode('*', $users_group->logo_size);
					// Logo cropping
					if ($crop == 'true') {
						$config['image_library'] = 'gd2';
						$config['source_image'] = $uploaded_file;
						$config['maintain_ratio'] = FALSE;
	
						if ($image_size[0]/$image_size[1] > $logo_size[0]/$logo_size[1]) {
							$crop_width = ($image_size[1]*$logo_size[0])/$logo_size[1];
							$config['x_axis'] = ($image_size[0]-$crop_width)/2;
							$config['y_axis'] = 0;
							$config['width'] = $crop_width;
							$config['height'] = $image_size[1];
						} else {
							$crop_height = ($logo_size[1]*$image_size[0])/$logo_size[0];
							$config['y_axis'] = ($image_size[1]-$crop_height)/2;
							$config['x_axis'] = 0;
							$config['width'] = $image_size[0];
							$config['height'] = $crop_height;
						}
						$this->image_lib->initialize($config);
						$this->image_lib->crop();
						$this->image_lib->clear();
						// --------------------------------------------------------------------------------------------
					}
					
					// --------------------------------------------------------------------------------------------
					$config['image_library'] = 'gd2';
					$config['source_image'] = $uploaded_file;
					$config['new_image'] = $users_content_server_path . $users_content_settings['user_logo_image']['upload_to'];
					$config['create_thumb'] = TRUE;
					$config['thumb_marker'] = '';
					$config['maintain_ratio'] = TRUE;
					$config['width'] = $logo_size[0];
					$config['height'] = $logo_size[1];
					$this->image_lib->initialize($config);
					$this->image_lib->resize();
					$this->image_lib->clear();*/
					// --------------------------------------------------------------------------------------------
					
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
	
	public function delete($user_id)
	{
		$this->load->model('users');
		$this->users->setUserId($user_id);
		$user = $this->users->getUserById();

		if ($this->input->post('yes')) {
            if ($this->users->deleteUserById()) {
            	// Clean cache
				$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('users_' . $user_id));

            	$this->setSuccess(LANG_USER_DELETE_SUCCESS);
                redirect('admin/users/search/');
            }
        }

        if ($this->input->post('no')) {
            redirect('admin/users/search/');
        }

        if (!$user) {
            redirect('admin/users/search/');
        }

        if (strpos($this->session->userdata('back_page'), '/admin/users/search/') !== FALSE)
			$back_page = $this->session->userdata('back_page');
		else 
			$back_page = site_url('admin/users/search/');
		registry::set('breadcrumbs', array(
		   	$back_page => LANG_SEARCH_USERS_TITLE,
		   	'admin/users/view/' . $user_id => LANG_VIEW_USER_PROFILE . ' "' . $user->login . '"',
		   	LANG_DELETE_USER_PROFILE . ' "' . $user->login . '"',
		));

		$view  = $this->load->view();
		$view->assign('options', array($user_id => $user->login));
        $view->assign('heading', LANG_DELETE_USER_PROFILE . ' "' . $user->login . '"');
        $view->assign('question', LANG_DELETE_USER_PROFILE_QUEST);
        $view->display('backend/delete_common_item.tpl');
	}
	
	/**
	 * validation function, checks the unique of the user email
	 *
	 * @param string $email
	 * @return bool
	 */
	public function check_unique_email($email)
	{
		$this->load->model('users');
		
		if ($this->users->is_email($email)) {
			$this->form_validation->set_message('email');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	/**
	 * validation function, checks the unique of the user login name
	 *
	 * @param string $login
	 * @return bool
	 */
	public function check_unique_login($login)
	{
		$this->load->model('users');
		
		if ($this->users->is_login($login)) {
			$this->form_validation->set_message('login');
			return FALSE;
		} else {
			return TRUE;
		}
	}
	
	/**
	 * validation function, checks the unique of the user seo login name
	 *
	 * @param string $seo_login
	 * @return bool
	 */
	public function check_unique_seo_login($seo_login)
	{
		$this->load->model('users');
		
		if ($seo_login)
			if ($this->users->is_seo_login($seo_login) || is_numeric($seo_login)) {
				$this->form_validation->set_message('seo_login');
				return FALSE;
			} else {
				return TRUE;
			}
		else 
			return TRUE; 
	}
	
	public function my_profile()
	{
		$this->load->model('users');
		$user_id = $this->session->userdata('user_id');
		$user_group_id = $this->session->userdata('user_group_id');

		$this->users->setUserId($user_id);
		
		$user = new user($user_group_id, $user_id);
		$user->setUserFromArray($this->users->getUserArrayById());

        if ($this->input->post('submit')) {
			if ($this->input->post('password')) {
				$this->form_validation->set_rules('password', LANG_USER_PROFILE_PASSWORD, 'max_length[20]|min_length[5]|matches[repeat_password]');
				$this->form_validation->set_rules('repeat_password', LANG_USER_PROFILE_PASSWORD_REPEAT);
        	}
            $this->form_validation->set_rules('email', LANG_USER_EMAIL, 'required|valid_email|callback_check_unique_email');
            $this->form_validation->set_rules('login', LANG_USER_LOGIN, 'required|min_length[4]|max_length[60]|callback_check_unique_login');
            if ($user->users_group->is_own_page) {
            	if ($user->users_group->use_seo_name)
            		$this->form_validation->set_rules('seo_login', LANG_USER_SEO_LOGIN, 'callback_check_unique_seo_login');
            	if ($user->users_group->meta_enabled) {
            		$this->form_validation->set_rules('meta_description', LANG_USER_META_DESCRIPTION);
            		$this->form_validation->set_rules('meta_keywords', LANG_USER_KEYWORDS);
            	}
			}
            if ($user->users_group->logo_enabled && $user->facebook_uid) {
	           	$this->form_validation->set_rules('use_facebook_logo', LANG_USE_FACEBOOK_LOGO_IMAGE, 'is_checked');
	           	$this->form_validation->set_rules('facebook_logo_file', LANG_USE_FACEBOOK_LOGO_IMAGE, 'max_length[255]');
			}
			if ($user->users_group->logo_enabled)
	            $this->form_validation->set_rules('user_logo_image', LANG_CUSTOM_USER_LOGO_IMAGE, 'max_length[255]');
            $user->validateFields($this->form_validation);
            
            if ($this->form_validation->run() !== FALSE) {
            	if ($this->users->saveUser($this->form_validation->result_array(), $user, true)) {
            		// Clean cache
					$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('users_' . $user_id));

					if ($user->updateFields($this->form_validation->result_array())) {
						$this->setSuccess(LANG_MY_PROFILE_SAVE_SUCCESS);
						redirect('admin/users/my_profile/');
					}
				}
            } else {
            	$user->setUserFromArray($this->form_validation->result_array());
            	$user->content_fields->getValuesFromForm($this->form_validation->result_array());
            }
        }/* else {
        	$user->setUserFromArray($this->users->getUserArrayById());
        }*/
        
        $view = $this->load->view();
        // --------------------------------------------------------------------------------------------
        // Set up upload logo block
        if ($user->users_group->logo_enabled) {
			$file_to_upload = new filesUpload;
			$file_to_upload->title = LANG_CUSTOM_USER_LOGO_IMAGE;
			$file_to_upload->upload_id = 'user_logo_image';
			$file_to_upload->current_file = $user->user_logo_image;
			$file_to_upload->after_upload_url = site_url('admin/users/get_logo/' . $user->users_group->id);
			$file_to_upload->attrs['width'] = $user->users_group->explodeSize('logo_size', 'width');
			$file_to_upload->attrs['height'] = $user->users_group->explodeSize('logo_size', 'height');

			$view->addJsFile('ajaxfileupload.js');
			$view->assign('image_upload_block', $file_to_upload);

			if ($this->load->is_module_loaded('facebook') && $user->facebook_uid) {
				// --------------------------------------------------------------------------------------------
				// Work with facebook logo
				// --------------------------------------------------------------------------------------------
				if ($user_info = fcb_getUserInfo($user->facebook_uid))
					$view->assign('facebook_logo_file', $user_info['picture']);
				// --------------------------------------------------------------------------------------------
			}
		}
		// --------------------------------------------------------------------------------------------

        $view->assign('user', $user);
		$view->display('users/my_profile.tpl');
	}

	public function view($user_id)
	{
		$this->load->model('users');
		$this->users->setUserId($user_id);
		$user = $this->users->getUserById();

		if (strpos($this->session->userdata('back_page'), '/admin/users/search/') !== FALSE)
			$back_page = $this->session->userdata('back_page');
		else 
			$back_page = site_url('admin/users/search/');
		registry::set('breadcrumbs', array(
	    	$back_page => LANG_SEARCH_USERS_TITLE,
	    	LANG_VIEW_USER_PROFILE . ' "' . $user->login . '"',
	    ));

        $view = $this->load->view();
		$view->assign('user', $user);
		$view->display('users/admin_view_user.tpl');
	}

    /**
	 * Block checked users
	 * 
	 */
	public function block()
    {
		$users_ids = $this->input->searchPostItems('cb_');
		if (!empty($users_ids)) {
			$this->load->model('users');
			if ($this->users->blockUsers($users_ids)) {
				$this->setSuccess(LANG_USERS_BLOCK_SUCCESS);
				redirect('admin/users/search/');
			}
		} else {
			$this->setError(LANG_USERS_SELECT_ERROR);
			redirect('admin/users/search/');
		}
    }
    
    public function ajax_autocomplete_request()
    {
    	if ($this->input->post('query')) {
			$query = $this->input->post('query');
			$this->load->model('users');
			$suggestions_array = $this->users->getSuggestions($query);

			$suggestions = array();
			if (count($suggestions_array)) {
				foreach ($suggestions_array AS $row) {
					$suggestions[] = _utf8_encode($row['login']);
				}
			}

			$suggestion_obj = new userSuggestions($query, $suggestions);
			echo json_encode($suggestion_obj);
		}
    }
}
?>