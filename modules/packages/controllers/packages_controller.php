<?php

class packagesController extends controller
{
	// --------------------------------------------------------------------------------------------
	// Use packages
	// --------------------------------------------------------------------------------------------
	public function add($package_id = null)
	{
		$this->load->model('packages');
		$this->packages->setPackageId($package_id);

		$packages = $this->packages->getAllPackages();
		
		$packages_prices = registry::get('packages_prices');
		
		if (!is_null($package_id) && $package = $this->packages->getPackageById()) {
			if ($user_package_id = $this->packages->addPackageToUser()) {
				$this->setSuccess(LANG_PACKAGE_ADD_SUCCESS);
				
				$my_packages_obj = $this->packages->getUserPackages();

				// raise Package addition event
				$event_params = array(
					'PACKAGE_ID' => $package_id,
					'USER_PACKAGE_ID' => $user_package_id,
					'PACKAGE_NAME' => $package->name, 
					'RECIPIENT_NAME' => $my_packages_obj->user->login,
					'RECIPIENT_EMAIL' => $my_packages_obj->user->email
				);
				$notification = new notificationSender('Package addition');
				$notification->send($event_params);
				events::callEvent('Package addition', $event_params);
				redirect('admin/packages/my/');
			}
		}

		$view = $this->load->view();
		$view->assign('packages', $packages);
		$view->assign('packages_prices', $packages_prices);
		$view->display('packages/admin_add_package.tpl');
	}
	
	public function my()
	{
		$this->load->model('packages');
		$my_packages_obj = $this->packages->getUserPackages();

		$this->session->set_userdata('back_page', uri_string());

		$view = $this->load->view();
		$view->assign('my_packages_obj', $my_packages_obj);
		$view->display('packages/admin_my_packages.tpl');
	}
	
	public function search($argsString = '')
	{
		$args = parseUrlArgs($argsString);

		// Search url needs for 'asc_desc_insert.base_url' argument of smarty function
		$clean_url = site_url('admin/packages/search/');
		$base_url = $clean_url;
		$search_url = $base_url;
		if (isset($args['search_login'])) {
			$search_login = $args['search_login'];
			$search_url .= 'search_login/' . $search_login . '/';
		}
		if (isset($args['search_status'])) {
			$search_status = $args['search_status'];
			$search_url .= 'search_status/' . $search_status . '/';
		}
		if (isset($args['search_addition_date'])) {
			$search_addition_date = $args['search_addition_date'];
			$search_url .= 'search_addition_date/' . $search_addition_date . '/';
		}
		if (isset($args['search_from_addition_date'])) {
			$search_from_addition_date = $args['search_from_addition_date'];
			$search_url .= 'search_from_addition_date/' . $search_from_addition_date . '/';
		}
		if (isset($args['search_to_addition_date'])) {
			$search_to_addition_date = $args['search_to_addition_date'];
			$search_url .= 'search_to_addition_date/' . $search_to_addition_date . '/';
		}
		
		// Paginator url needs for '.../page/x/' url modification
		$paginator_url = $search_url;
		if (isset($args['orderby'])) {
			$orderby = $args['orderby'];
			$paginator_url .= 'orderby/' . $args['orderby'] . '/';
		} else {
			$orderby = 'pu.creation_date';
		}
		if (isset($args['direction'])) {
			$direction = $args['direction'];
			$paginator_url .= 'direction/' . $args['direction'] . '/';
		} else {
			$direction = 'asc';
		}

		$paginator = new pagination(array('args' => $args, 'url' => $paginator_url, 'num_per_page' => 10));
		$this->load->model('packages');
		$this->packages->setPaginator($paginator);
		$users_packages = $this->packages->selectPackages($orderby, $direction, $args);
		
		$all_packages = $this->packages->getAllPackages();

		$view = $this->load->view();
		$view->assign('users_packages', $users_packages);
		$view->assign('all_packages', $all_packages);
		$view->assign('packages_count', $paginator->count());
		$view->assign('paginator', $paginator->placeLinksToHtml());

		$view->assign('args', $args);
		$view->assign('orderby', $orderby);
		$view->assign('direction', $direction);
		$view->assign('base_url', $base_url);
		$view->assign('search_url', $search_url);
		
		$mode = 'single';
		if (isset($args["search_addition_date"])) {
			$view->assign('addition_date', date("y-m-d", $args["search_addition_date"]));
			$view->assign('addition_date_tmstmp', $args["search_addition_date"]);
		}
		if (isset($args["search_from_addition_date"])) {
			$view->assign('from_addition_date', date("y-m-d", $args["search_from_addition_date"]));
			$view->assign('from_addition_date_tmstmp', $args["search_from_addition_date"]);
			$mode = 'range';
		}
		if (isset($args["search_to_addition_date"])) {
			$view->assign('to_addition_date', date("y-m-d", $args["search_to_addition_date"]));
			$view->assign('to_addition_date_tmstmp', $args["search_to_addition_date"]);
			$mode = 'range';
		}
		$view->assign('mode', $mode);

		// Add js library for Send Message feature
		$view->addJsFile('jquery.jqURL.js');

		$this->session->set_userdata('back_page', uri_string());
		$view->display('packages/admin_search_packages.tpl');
	}
	
	public function change_status($user_package_id)
	{
		$this->load->model('packages');
		$my_packages_obj = $this->packages->getUserPackagesByUserPackageId($user_package_id);
		foreach ($my_packages_obj->packages AS $package) {
			if ($package->id == $user_package_id) {
				$current_package = $package;
				break;
			}
		}

		if ($this->input->post('submit')) {
            $this->form_validation->set_rules('status', LANG_PACKAGE_STATUS_TH, 'required');

			if ($this->form_validation->run() !== FALSE) {
				if ($this->packages->savePackageStatus($user_package_id, $this->form_validation->set_value('status'))) {
					$this->setSuccess(LANG_PACKAGES_STATUS_SAVE_SUCCESS);

					if ($current_package->status != 2 && $this->form_validation->set_value('status') == 2) {
						// raise Package blocking event
						$event_params = array(
							'PACKAGE_ID' => $current_package->id,
							'PACKAGE_NAME' => $current_package->package->name, 
							'RECIPIENT_NAME' => $my_packages_obj->user->login,
							'RECIPIENT_EMAIL' => $my_packages_obj->user->email
						);
						$notification = new notificationSender('Package blocking');
						$notification->send($event_params);
						events::callEvent('Package blocking', $event_params);
					}
					redirect($this->session->userdata('back_page'));
				}
			}
        }

	    if (strpos($this->session->userdata('back_page'), '/admin/packages/search/') !== FALSE) {
	    	registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_SEARCH_PACKAGES_TITLE,
	    		LANG_PACKAGE_STATUS_TITLE . ' "' . $current_package->package->name . '"',
	    	));
    	} elseif (strpos($this->session->userdata('back_page'), '/admin/packages/my/') !== FALSE) {
    		registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_VIEW_MY_PACKAGES,
	    		LANG_PACKAGE_STATUS_TITLE . ' "' . $current_package->package->name . '"',
	    	));
    	}

		$view = $this->load->view();
		$view->assign('user_package', $current_package);
		$view->display('packages/admin_package_status.tpl');
	}
	// --------------------------------------------------------------------------------------------
	
	// --------------------------------------------------------------------------------------------
	// Manage packages
	// --------------------------------------------------------------------------------------------
	public function index()
	{
		$this->load->model('packages');

		$packages = $this->packages->getAllPackages();

		if ($this->input->post('submit')) {
            $this->form_validation->set_rules('serialized_order', LANG_SERIALIZED_ORDER_VALUE);
            if ($this->form_validation->run() !== FALSE) {
            	$this->packages->setPackagesOrder($this->form_validation->set_value('serialized_order'));

            	$this->setSuccess(LANG_PACKAGES_ORDER_SAVE_SUCCESS);
            	redirect('admin/packages/');
            }
        }

		$view = $this->load->view();
		$view->addJsFile('jquery.tablednd_0_5.js');
		$view->assign('packages', $packages);
		$view->display('packages/admin_packages.tpl');
	}
	
	/**
     * form validation function
     *
     * @param string $name
     * @return bool
     */
    public function callback_is_unique_package_name($name)
    {
    	$this->load->model('packages');
		
		if ($this->packages->is_package_name($name)) {
			$this->form_validation->set_message('name');
			return FALSE;
		} else {
			return TRUE;
		}
    }
	
	public function create()
	{
		$this->load->model('packages');

		if ($this->input->post('submit')) {
			$this->form_validation->set_rules('name', LANG_PACKAGE_NAME, 'required|max_length[35]|callback_is_unique_package_name');

			if ($this->form_validation->run() !== FALSE) {
				$form_result = $this->form_validation->result_array();
				if ($this->packages->savePackage($form_result)) {
					$this->setSuccess(LANG_NEW_PACKAGE_CREATE_SUCCESS_1 . ' "' . $form_result['name'] . '" ' . LANG_NEW_PACKAGE_CREATE_SUCCESS_2);
					redirect('admin/packages/');
				}
			} else {
	           	$package = $this->packages->getPackageFromForm($this->form_validation->result_array());
			}
    	} else {
    		$package = $this->packages->getNewPackage();
    	}
    	
    	registry::set('breadcrumbs', array(
	    	'admin/packages/' => LANG_MANAGE_PACKAGES_TITLE,
	    	LANG_CREATE_PACKAGE_TITLE,
	    ));

        $view  = $this->load->view();
        $view->assign('package', $package);
        $view->display('packages/admin_package_settings.tpl');
	}
	
	public function edit($package_id)
	{
		$this->load->model('packages');
		$this->packages->setPackageId($package_id);

		if ($this->input->post('submit')) {
			$this->form_validation->set_rules('id', LANG_PACKAGE_ID, 'required|integer');
			$this->form_validation->set_rules('name', LANG_PACKAGE_NAME, 'required|max_length[35]|callback_is_unique_package_name');

			if ($this->form_validation->run() !== FALSE) {
				$form_result = $this->form_validation->result_array();
				if ($this->packages->savePackageById($form_result)) {
					$this->setSuccess(LANG_PACKAGE_SAVE_SUCCESS);
					redirect('admin/packages/');
				}
			} else {
	           	$package = $this->packages->getPackageFromForm($this->form_validation->result_array());
			}
    	} else {
    		$package = $this->packages->getPackageById();
    	}

    	registry::set('breadcrumbs', array(
	    	'admin/packages/' => LANG_MANAGE_PACKAGES_TITLE,
	    	LANG_EDIT_PACKAGE_TITLE . ' "' . $package->name . '"',
	    ));

        $view  = $this->load->view();
        $view->assign('package', $package);
        $view->display('packages/admin_package_settings.tpl');
	}
	
	public function delete($package_id)
    {
        $this->load->model('packages');
		$this->packages->setPackageId($package_id);

        if ($this->input->post('yes')) {
            if ($this->packages->deletePackageById()) {
            	$this->setSuccess(LANG_PACKAGE_DELETE_SUCCESS);
                redirect('admin/packages/');
            }
        }

        if ($this->input->post('no')) {
            redirect('admin/packages/');
        }

        if ( !$package = $this->packages->getPackageById()) {
            redirect('admin/packages/');
        }
        
        registry::set('breadcrumbs', array(
	    	'admin/packages/' => LANG_MANAGE_PACKAGES_TITLE,
	    	LANG_DELETE_PACKAGE_TITLE . ' "' . $package->name . '"',
	    ));

		$view  = $this->load->view();
		$view->assign('options', array($package_id => $package->name));
        $view->assign('heading', LANG_DELETE_PACKAGE_TITLE);
        $view->assign('question', LANG_DELETE_PACKAGE_QUEST);
        $view->display('backend/delete_common_item.tpl');
    }
    
    public function items($package_id)
    {
    	$this->load->model('packages');
		$this->packages->setPackageId($package_id);
		$package = $this->packages->getPackageById();
		
    	$this->load->model('types', 'types_levels');
    	$types = $this->types->getTypesLevels();
		
		if ($this->input->post('submit')) {
			$post_variables = $_POST;
			$limited_level_ids = array();
			$unlimited_level_ids = array();
			foreach ($post_variables AS $key=>$var) {
				if (strpos($key, 'count_') !== FALSE)
					if ($this->input->post($key) >= 0)
						$limited_level_ids[] = str_replace('count_', '', $key);
				if (strpos($key, 'unlimited_') !== FALSE)
					if ($this->input->post($key) >= 0)
						$unlimited_level_ids[] = str_replace('unlimited_', '', $key);
			}

			if ($this->packages->savePackageItems($this->input, $limited_level_ids, $unlimited_level_ids)) {
	           	$this->setSuccess(LANG_PACKAGE_ITEMS_SAVE_SUCCESS);
				redirect('admin/packages/');
			}
		}
		
		registry::set('breadcrumbs', array(
	    	'admin/packages/' => LANG_MANAGE_PACKAGES_TITLE,
	    	LANG_MANAGE_PACKAGE_ITEMS_TITLE . ' "' . $package->name . '"',
	    ));
		
		$view  = $this->load->view();
        $view->assign('types', $types);
        $view->assign('package', $package);
        $view->display('packages/admin_package_items.tpl');
    }
    
    public function packages_settings()
    {
	    $this->load->model('settings', 'settings');

	    $system_settings = registry::get('system_settings');
	    
	    $view = $this->load->view();

	    if ($this->input->post('submit')) {
			$this->form_validation->set_rules('packages_listings_creation_mode', LANG_SETTINGS_LISTINGS_CREATION_MODE, 'required');

			if ($this->form_validation->run() !== FALSE) {
				if ($this->settings->saveSystemSettings($this->form_validation->result_array())) {
					$this->setSuccess(LANG_SYSTEM_SETTING_SAVE_SUCCESS);
					redirect('admin/packages_settings/');
				}
			} else {
				$view->assign('system_settings', $this->form_validation->result_array());
			}
	    } else {
	    	$view->assign('system_settings', $system_settings);
	    }

		$view->display('packages/admin_packages_settings.tpl');
    }
    // --------------------------------------------------------------------------------------------
}
?>