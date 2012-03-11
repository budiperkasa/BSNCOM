<?php

class discount_couponsController extends controller
{
	public function my()
	{
		$this->load->model('discount_coupons');
		$coupons = $this->discount_coupons->getUserCoupons();
		$coupons_usage = $this->discount_coupons->getCouponsUsage();

		$this->session->set_userdata('back_page', uri_string());
		
		foreach ($coupons AS $key=>$coupon) {
			$coupons[$key]->setActive($this->discount_coupons->isCouponActive($coupon));
		}

		$view = $this->load->view();
		$view->assign('coupons', $coupons);
		$view->assign('coupons_usage', $coupons_usage);
		$view->display('coupons/admin_my_coupons.tpl');
	}

	// --------------------------------------------------------------------------------------------
	// Manage coupons
	// --------------------------------------------------------------------------------------------
	public function index()
	{
		$this->load->model('discount_coupons');

		$coupons = $this->discount_coupons->getAllCoupons();

		$view = $this->load->view();
		$view->assign('coupons', $coupons);
		$view->display('coupons/admin_coupons.tpl');
	}

	public function search($argsString = '')
	{
		$args = parseUrlArgs($argsString);

		// Search url needs for 'asc_desc_insert.base_url' argument of smarty function
		$clean_url = site_url('admin/coupons/search/');
		$base_url = $clean_url;
		$search_url = $base_url;
		if (isset($args['search_login'])) {
			$search_login = urldecode($args['search_login']);
			$search_url .= 'search_login/' . $search_login . '/';
		}
		if (isset($args['search_coupon'])) {
			$search_coupon = $args['search_coupon'];
			$search_url .= 'search_coupon/' . $search_coupon . '/';
		}
		if (isset($args['search_usage_date'])) {
			$search_usage_date = $args['search_usage_date'];
			$search_url .= 'search_usage_date/' . $search_usage_date . '/';
		}
		if (isset($args['search_from_usage_date'])) {
			$search_from_usage_date = $args['search_from_usage_date'];
			$search_url .= 'search_from_usage_date/' . $search_from_usage_date . '/';
		}
		if (isset($args['search_to_usage_date'])) {
			$search_to_usage_date = $args['search_to_usage_date'];
			$search_url .= 'search_to_usage_date/' . $search_to_usage_date . '/';
		}
		
		// Paginator url needs for '.../page/x/' url modification
		$paginator_url = $search_url;
		if (isset($args['orderby'])) {
			$orderby = $args['orderby'];
			$paginator_url .= 'orderby/' . $args['orderby'] . '/';
		} else {
			$orderby = 'dcu.usage_date';
		}
		if (isset($args['direction'])) {
			$direction = $args['direction'];
			$paginator_url .= 'direction/' . $args['direction'] . '/';
		} else {
			$direction = 'asc';
		}

		$paginator = new pagination(array('args' => $args, 'url' => $paginator_url, 'num_per_page' => 10));
		$this->load->model('discount_coupons');
		$this->discount_coupons->setPaginator($paginator);
		$coupons_usage = $this->discount_coupons->selectCouponsUsage($orderby, $direction, $args);
		
		$view = $this->load->view();

		if (isset($search_login)) {
			$this->load->model('users', 'users');
			$user = $this->users->getUserByLogin($search_login);
			$coupons_by_user = $this->discount_coupons->selectCouponsByUser($user->id);
			$view->assign('user', $user);
			$view->assign('coupons_by_user', $coupons_by_user);
		}
		
		$all_coupons = $this->discount_coupons->getAllCoupons();

		$view->assign('coupons_usage', $coupons_usage);
		$view->assign('all_coupons', $all_coupons);
		$view->assign('usages_count', $paginator->count());
		$view->assign('paginator', $paginator->placeLinksToHtml());

		$view->assign('args', $args);
		$view->assign('orderby', $orderby);
		$view->assign('direction', $direction);
		$view->assign('base_url', $base_url);
		$view->assign('search_url', $search_url);
		
		$mode = 'single';
		if (isset($args["search_usage_date"])) {
			$view->assign('usage_date', date("y-m-d", $args["search_usage_date"]));
			$view->assign('usage_date_tmstmp', $args["search_usage_date"]);
		}
		if (isset($args["search_from_usage_date"])) {
			$view->assign('from_usage_date', date("y-m-d", $args["search_from_usage_date"]));
			$view->assign('from_usage_date_tmstmp', $args["search_from_usage_date"]);
			$mode = 'range';
		}
		if (isset($args["search_to_usage_date"])) {
			$view->assign('to_usage_date', date("y-m-d", $args["search_to_usage_date"]));
			$view->assign('to_usage_date_tmstmp', $args["search_to_usage_date"]);
			$mode = 'range';
		}
		$view->assign('mode', $mode);

		// Add js library for Send Message feature
		$view->addJsFile('jquery.jqURL.js');

		$view->display('coupons/admin_search_coupons.tpl');
	}
	
	/**
     * form validation function
     *
     * @param string $code
     * @return bool
     */
    public function callback_is_unique_coupon_code($code)
    {
    	$this->load->model('discount_coupons');
		
		if ($this->discount_coupons->is_coupon_code($code)) {
			$this->form_validation->set_message('code');
			return FALSE;
		} else {
			return TRUE;
		}
    }
	
	public function create()
	{
		$this->load->model('discount_coupons');

		if ($this->input->post('submit')) {
			$this->form_validation->set_rules('code', LANG_COUPON_CODE, 'required|max_length[30]|callback_is_unique_coupon_code');
			$this->form_validation->set_rules('description', LANG_COUPON_DESCRIPTION);
			$this->form_validation->set_rules('allowed_goods[]', LANG_COUPON_ALLOWED_GOODS, 'required');
			$this->form_validation->set_rules('discount_type', LANG_COUPON_TYPE, 'required');
			if ($this->input->post('discount_type') == 0)
				$this->form_validation->set_rules('percents_value', LANG_COUPON_TYPE_PERCENTS, 'required|numeric|max_integer[100]|min_integer[0]');
			else {
				$this->form_validation->set_rules('exact_value_currency', LANG_COUPON_TYPE_EXACT_VALUE, 'required');
				$this->form_validation->set_rules('exact_value', LANG_COUPON_TYPE_EXACT_VALUE, 'required|numeric|min_integer[0]');
			}
			$this->form_validation->set_rules('effective_date_tmstmp', LANG_COUPON_EFFECTIVE_DATE, 'integer');
			$this->form_validation->set_rules('expiration_date_tmstmp', LANG_COUPON_EXPIRATION_DATE, 'integer');
			$this->form_validation->set_rules('usage_count_limit_all', LANG_COUPON_USAGE_COUNT_LIMIT_ALL, 'required|is_natural');
			$this->form_validation->set_rules('usage_count_limit_user', LANG_COUPON_USAGE_COUNT_LIMIT_USER, 'required|is_natural');
			$this->form_validation->set_rules('use_if_assigned', LANG_COUPON_USE_IF_ASSIGNED, 'required|integer');
			$this->form_validation->set_rules('assign_events[]', LANG_COUPON_ASSIGN_EVENTS, 'required');

			if ($this->form_validation->run() !== FALSE) {
				$form_result = $this->form_validation->result_array();
				if ($this->discount_coupons->saveCoupon($form_result)) {
					$this->setSuccess(LANG_NEW_COUPON_CREATE_SUCCESS_1 . ' "' . $form_result['code'] . '" ' . LANG_NEW_COUPON_CREATE_SUCCESS_2);
					redirect('admin/coupons/');
				}
			} else {
	           	$coupon = $this->discount_coupons->getCouponFromForm($this->form_validation->result_array());
			}
    	} else {
    		$coupon = $this->discount_coupons->getNewCoupon();
    	}
    	
    	registry::set('breadcrumbs', array(
	    	'admin/coupons/' => LANG_MANAGE_COUPONS_TITLE,
	    	LANG_CREATE_COUPON_TITLE,
	    ));

        $view  = $this->load->view();
        $view->assign('coupon', $coupon);
        $view->assign('currencies', $this->config->item('currency_codes'));
        $view->display('coupons/admin_coupon_settings.tpl');
	}
	
	public function edit($coupon_id)
	{
		$this->load->model('discount_coupons');
		$this->discount_coupons->setCouponId($coupon_id);

		if ($this->input->post('submit')) {
			$this->form_validation->set_rules('id', LANG_COUPON_ID, 'required');
			$this->form_validation->set_rules('code', LANG_COUPON_CODE, 'required|max_length[30]|callback_is_unique_coupon_code');
			$this->form_validation->set_rules('description', LANG_COUPON_DESCRIPTION);
			$this->form_validation->set_rules('allowed_goods[]', LANG_COUPON_ALLOWED_GOODS, 'required');
			$this->form_validation->set_rules('discount_type', LANG_COUPON_TYPE, 'required');
			if ($this->input->post('discount_type') == 0)
				$this->form_validation->set_rules('percents_value', LANG_COUPON_TYPE_PERCENTS, 'required|numeric|max_integer[100]|min_integer[0]');
			else {
				$this->form_validation->set_rules('exact_value_currency', LANG_COUPON_TYPE_EXACT_VALUE, 'required');
				$this->form_validation->set_rules('exact_value', LANG_COUPON_TYPE_EXACT_VALUE, 'required|numeric|min_integer[0]');
			}
			$this->form_validation->set_rules('effective_date_tmstmp', LANG_COUPON_EFFECTIVE_DATE, 'integer');
			$this->form_validation->set_rules('expiration_date_tmstmp', LANG_COUPON_EXPIRATION_DATE, 'integer');
			$this->form_validation->set_rules('usage_count_limit_all', LANG_COUPON_USAGE_COUNT_LIMIT_ALL, 'required|is_natural');
			$this->form_validation->set_rules('usage_count_limit_user', LANG_COUPON_USAGE_COUNT_LIMIT_USER, 'required|is_natural');
			$this->form_validation->set_rules('use_if_assigned', LANG_COUPON_USE_IF_ASSIGNED, 'required|integer');
			$this->form_validation->set_rules('assign_events[]', LANG_COUPON_ASSIGN_EVENTS, 'required');

			if ($this->form_validation->run() !== FALSE) {
				$form_result = $this->form_validation->result_array();
				if ($this->discount_coupons->saveCouponById($form_result)) {
					$this->setSuccess(LANG_NEW_COUPON_SAVE_SUCCESS_1 . ' "' . $form_result['code'] . '" ' . LANG_NEW_COUPON_SAVE_SUCCESS_2);
					redirect('admin/coupons/');
				}
			} else {
	           	$coupon = $this->discount_coupons->getCouponFromForm($this->form_validation->result_array());
			}
    	} else {
    		$coupon = $this->discount_coupons->getCouponById();
    	}

    	registry::set('breadcrumbs', array(
	    	'admin/coupons/' => LANG_MANAGE_COUPONS_TITLE,
	    	LANG_EDIT_COUPON_TITLE . ' "' . $coupon->code . '"',
	    ));

        $view  = $this->load->view();
        $view->assign('coupon', $coupon);
        $view->assign('currencies', $this->config->item('currency_codes'));
        $view->display('coupons/admin_coupon_settings.tpl');
	}
	
	public function delete($coupon_id)
    {
        $this->load->model('discount_coupons');
		$this->discount_coupons->setCouponId($coupon_id);

        if ($this->input->post('yes')) {
            if ($this->discount_coupons->deleteCouponById()) {
            	$this->setSuccess(LANG_COUPON_DELETE_SUCCESS);
                redirect('admin/coupons/');
            }
        }

        if ($this->input->post('no')) {
            redirect('admin/coupons/');
        }

        if ( !$coupon = $this->discount_coupons->getCouponById()) {
            redirect('admin/coupons/');
        }
        
        registry::set('breadcrumbs', array(
	    	'admin/coupons/' => LANG_MANAGE_COUPONS_TITLE,
	    	LANG_DELETE_COUPON_TITLE . ' "' . $coupon->code . '"',
	    ));

		$view  = $this->load->view();
		$view->assign('options', array($coupon_id => $coupon->code));
        $view->assign('heading', LANG_DELETE_COUPON_TITLE);
        $view->assign('question', LANG_DELETE_COUPON_QUEST);
        $view->display('backend/delete_common_item.tpl');
    }
    
    public function send_coupon($user_id)
    {
    	$this->load->model('discount_coupons');
		$coupons = $this->discount_coupons->getAllCoupons();
		
		foreach ($coupons AS $key=>$coupon) {
			if (!in_array('custom_users', $coupon->assign_events))
				unset($coupons[$key]);
		}

		if (empty($coupons))
			exit('There are not any discount coupons in the system to send!');
		
    	$this->load->model('users', 'users');
    	$this->users->setUserId($user_id);
    	$user = $this->users->getUserById();

    	if ($this->input->post('submit')) {
    		$this->form_validation->set_rules('coupon_code', LANG_COUPON_CODE);

    		if ($this->form_validation->run() !== FALSE) {
    			$coupon = $this->discount_coupons->getCouponByCode($this->form_validation->set_value('coupon_code'));
    			if ($coupon->assignToUser($user_id))
    				$this->setSuccess(LANG_COUPON_ASSIGN_SUCCESS);
    		}
    	}

    	$view = $this->load->view();
	    $view->assign('coupons', $coupons);
	    $view->assign('url', site_url("admin/coupons/send/user_id/" . $user_id));
    	$view->display('coupons/user_send.tpl');
    }
    // --------------------------------------------------------------------------------------------
}
?>