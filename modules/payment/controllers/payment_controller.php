<?php
include_once(MODULES_PATH . 'acl/classes/content_acl.class.php');

class paymentController extends controller
{
    public function search_invoices($argsString = '')
    {
    	$args = parseUrlArgs($argsString);

		// Search url needs for 'asc_desc_insert.base_url' argument of smarty function
		$clean_url = site_url('admin/payment/invoices/search/');
		$base_url = $clean_url;
		$search_url = $base_url;
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
		$this->load->model('payment');
		$this->payment->setPaginator($paginator);
		$invoices = $this->payment->selectInvoices($orderby, $direction, $args);
		$gateways = $this->payment->getAllPaymentGateways();

		$view = $this->load->view();
		$view->assign('invoices', $invoices);
		$view->assign('invoices_count', $paginator->count());
		$view->assign('paginator', $paginator->placeLinksToHtml());

		$view->assign('args', $args);
		$view->assign('orderby', $orderby);
		$view->assign('direction', $direction);
		$view->assign('base_url', $base_url);
		$view->assign('search_url', $search_url);

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
		$view->assign('gateways', $gateways);
		
		$view->addJsFile('jquery.jqURL.js');

		$this->session->set_userdata('back_page', uri_string());
		$view->display('payment/admin_search_invoices.tpl');
    }
    
    public function my_invoices($argsString = '')
    {
    	$args = parseUrlArgs($argsString);

		// Search url needs for 'asc_desc_insert.base_url' argument of smarty function
		$clean_url = site_url('admin/payment/invoices/my/');
		$base_url = $clean_url;
		$search_url = $base_url;

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
		$this->load->model('payment');
		$this->payment->setPaginator($paginator);
		$invoices = $this->payment->selectMyInvoices($orderby, $direction, $args);
		$gateways = $this->payment->getAllPaymentGateways();

		$view = $this->load->view();
		$view->assign('invoices', $invoices);
		$view->assign('invoices_count', $paginator->count());
		$view->assign('paginator', $paginator->placeLinksToHtml());

		$view->assign('args', $args);
		$view->assign('orderby', $orderby);
		$view->assign('direction', $direction);
		$view->assign('search_url', $search_url);
		$view->assign('gateways', $gateways);
		
		$view->addJsFile('jquery.jqURL.js');

		$this->session->set_userdata('back_page', uri_string());
		$view->display('payment/admin_my_invoices.tpl');
    }
    
    public function pay($invoice_id)
    {
    	$this->load->model('payment');
    	if (!$invoice = $this->payment->getInvoiceById($invoice_id))
    		show_404('Invoice not available!');
    	$gateways = $this->payment->getPaymentGateways($invoice->currency);
    	
    	$content_access_obj = contentAcl::getInstance();
    	
    	if ($this->input->post('submit')) {
    		if (!$invoice->fixed_price)
	    		$required = "required";
	    	else 
	    		$required = '';
	    	$this->form_validation->set_rules('quantity', LANG_ITEM_QUANTITY, 'is_natural|'.$required);
    		$this->form_validation->set_rules('gateway', LANG_PAYMENT_METHOD, 'required');

    		if ($this->load->is_module_loaded('discount_coupons') && $content_access_obj->isPermission('Use coupons')) {
    			$this->form_validation->set_rules('coupon_code', LANG_COUPON_CODE);
    			$this->form_validation->set_rules('selected_coupon_code', LANG_COUPON_CODE);
    		}

    		if ($this->form_validation->run() !== FALSE) {
    			$gateway = $this->form_validation->set_value('gateway');
    			if (!$invoice->fixed_price)
    				$quantity = $this->form_validation->set_value('quantity');
    			else 
    				$quantity = 1;
    				
    			if ($this->load->is_module_loaded('discount_coupons') && $content_access_obj->isPermission('Use coupons')) {
    				if (($coupon_code = $this->form_validation->set_value('coupon_code')) || ($this->form_validation->set_value('selected_coupon_code') && $this->form_validation->set_value('selected_coupon_code') != -1)) {
	    				if (!$coupon_code)
	    					$coupon_code = $this->form_validation->set_value('selected_coupon_code');
	
	    				$this->load->model('discount_coupons', 'discount_coupons');
	    				if ($coupon = $this->discount_coupons->checkCoupon($coupon_code, $invoice_id)) {
	    					$this->discount_coupons->setCouponUsage($coupon->id, $invoice_id);
	    				} else {
	    					$this->setError(LANG_COUPON_USAGE_ERROR);
	    					redirect('admin/payment/invoices/pay/' . $invoice_id);
	    				}
	    				
	    				$invoice = $this->payment->getInvoiceById($invoice_id, true);
    				} else {
    					$this->discount_coupons->resetCouponUsage($invoice_id);
    				}
    			}

    			if ($invoice->value > 0)
    				redirect('admin/payment/gateway/' . $gateway . '/invoice_id/' . $invoice_id . '/quantity/' . $quantity . '/');
    			else {
    				// --------------------------------------------------------------------------------------------
    				// If total value after discount becomes 0 - just complete the payment
    				// --------------------------------------------------------------------------------------------
		    		$invoice->goods_content->completePayment(1);
		    		$this->payment->saveInvoiceStatus($invoice_id, 2);

		    		$this->setSuccess(LANG_COUPON_ZERO_VALUE);
		    		redirect('admin/payment/invoices/my/');
		    		// --------------------------------------------------------------------------------------------
    			}
    		} else {
    			$quantity = $this->form_validation->set_value('quantity');
    		}
    	} else {
    		$quantity = 1;
    	}

    	if (strpos($this->session->userdata('back_page'), '/admin/payment/invoices/my/') !== FALSE) {
	    	registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_VIEW_MY_INVOICES_TITLE,
	    		LANG_PAY_INVOICE,
	    	));
    	} elseif (strpos($this->session->userdata('back_page'), '/admin/payment/invoices/search/') !== FALSE) {
    		registry::set('breadcrumbs', array(
	    		$this->session->userdata('back_page') => LANG_SEARCH_INVOICES_TITLE,
	    		LANG_PAY_INVOICE,
	    	));
    	}
    	
    	$view = $this->load->view();
    	if ($this->load->is_module_loaded('discount_coupons') && $content_access_obj->isPermission('Use coupons')) {
    		$this->load->model('discount_coupons', 'discount_coupons');
    		$my_coupons = $this->discount_coupons->getUserCoupons();
    		$view->assign('my_coupons', $my_coupons);
    	}
    	$view->assign('gateways', $gateways);
    	$view->assign('invoice', $invoice);
    	$view->assign('quantity', $quantity);
    	$view->display('payment/admin_invoice.tpl');
    }
    
    public function print_invoice($invoice_id)
    {
    	$this->load->model('payment');
    	$invoice = $this->payment->getInvoiceById($invoice_id);
    	$site_settings = registry::get('site_settings');

    	$quantity = 1;

    	$view = $this->load->view();
    	$view->assign('invoice', $invoice);
    	$view->assign('quantity', $quantity);
    	$view->assign('company_details', $site_settings['company_details']);
    	$view->display('payment/admin_invoice_print.tpl');
    }
    
    public function success($invoice_id)
    {
    	/*$this->load->model('payment', 'payment');
    	if ($transaction = $this->payment->getTransactionByInvoiceId($invoice_id)) {
	    	$view = $this->load->view();
	    	$view->assign('invoice_id', $invoice_id);
	    	$view->assign('total_value', $transaction->value);
	    	$view->addJsFile('jquery.timers-1.1.2.js');
	    	$view->display('payment/admin_invoice_success.tpl');
    	} else {
    		exit('No such invoice was successfully processed! Seems it has failed!');
    	}*/
    	redirect('admin/payment/invoices/my/');
    }

    public function search_transactions($argsString = '')
    {
    	$args = parseUrlArgs($argsString);
    	
    	// Search url needs for 'asc_desc_insert.base_url' argument of smarty function
		$clean_url = site_url('admin/payment/transactions/search/');
		$base_url = $clean_url;
		$search_url = $base_url;
		if (isset($args['search_owner'])) {
			$search_owner = $args['search_owner'];
			$search_url .= 'search_owner/' . $search_owner . '/';
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
		$this->load->model('payment');
		$this->payment->setPaginator($paginator);
		$transactions = $this->payment->selectTransactions($orderby, $direction, $args);

		$view = $this->load->view();
		$view->assign('transactions', $transactions);
		$view->assign('transactions_count', $paginator->count());
		$view->assign('paginator', $paginator->placeLinksToHtml());

		$view->assign('args', $args);
		$view->assign('orderby', $orderby);
		$view->assign('direction', $direction);
		$view->assign('base_url', $base_url);
		$view->assign('search_url', $search_url);

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

		$this->session->set_userdata('back_page', uri_string());
		$view->display('payment/admin_search_transactions.tpl');
    }
    
    public function my_transactions($argsString = '')
    {
    	$args = parseUrlArgs($argsString);

		// Search url needs for 'asc_desc_insert.base_url' argument of smarty function
		$clean_url = site_url('admin/payment/transactions/my/');
		$base_url = $clean_url;
		$search_url = $base_url;

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
		$this->load->model('payment');
		$this->payment->setPaginator($paginator);
		$transactions = $this->payment->selectMyTransactions($orderby, $direction, $args);

		$view = $this->load->view();
		$view->assign('transactions', $transactions);
		$view->assign('transactions_count', $paginator->count());
		$view->assign('paginator', $paginator->placeLinksToHtml());

		$view->assign('args', $args);
		$view->assign('orderby', $orderby);
		$view->assign('direction', $direction);
		$view->assign('search_url', $search_url);

		$this->session->set_userdata('back_page', uri_string());
		$view->display('payment/admin_my_transactions.tpl');
    }
    
    public function create_transaction($invoice_id = null)
    {
    	if (is_null($invoice_id)) {
    		// Step 1
	    	if ($this->input->post('submit')) {
	    		$this->form_validation->set_rules('invoice_id', LANG_PAYMENT_INVOICE_ID, 'integer|required');
	
	    		if ($this->form_validation->run() !== FALSE) {
	    			$invoice_id = $this->form_validation->set_value('invoice_id');
	
	    			redirect('admin/payment/transactions/create/' . $invoice_id . '/');
	    		} else {
	    			$invoice_id = $this->form_validation->set_value('invoice_id');
	    		}
	    	} else {
	    		$invoice_id = '';
	    	}
	
	    	$view = $this->load->view();
	    	$view->assign('invoice_id', $invoice_id);
	    	$view->display('payment/admin_create_transaction_step1.tpl');
    	} else {
    		// Step 2
    		$this->load->model('payment');
	    	if ($invoice = $this->payment->getInvoiceById($invoice_id)) {
		    	if ($this->input->post('submit')) {
		    		if (!$invoice->fixed_price)
		    			$required = "required";
		    		else 
		    			$required = '';
		    		$this->form_validation->set_rules('quantity', LANG_ITEM_QUANTITY, 'is_natural|'.$required);
		
		    		if ($this->form_validation->run() !== FALSE) {
		    			if (!$invoice->fixed_price)
		    				$quantity = $this->form_validation->set_value('quantity');
		    			else 
		    				$quantity = 1;
	
		    			if ($this->payment->completeTransaction($invoice, $quantity)) {
		    				$this->setSuccess(LANG_CREATE_TRANSACTION_SUCCESS);
		    			}
		    			redirect($this->session->userdata('back_page'));
		    		} else {
		    			$quantity = $this->form_validation->set_value('quantity');
		    		}
		    	} else {
		    		$quantity = 1;
		    	}
		    	
		    	if (strpos($this->session->userdata('back_page'), '/admin/payment/invoices/my/') !== FALSE) {
			    	registry::set('breadcrumbs', array(
			    		$this->session->userdata('back_page') => LANG_VIEW_MY_INVOICES_TITLE,
			    		LANG_CREATE_TRANSACTIONS_TITLE,
			    	));
		    	} elseif (strpos($this->session->userdata('back_page'), '/admin/payment/invoices/search/') !== FALSE) {
		    		registry::set('breadcrumbs', array(
			    		$this->session->userdata('back_page') => LANG_SEARCH_INVOICES_TITLE,
			    		LANG_CREATE_TRANSACTIONS_TITLE,
			    	));
		    	}
	
		    	$view = $this->load->view();
		    	$view->assign('invoice', $invoice);
		    	$view->assign('quantity', $quantity);
		    	$view->display('payment/admin_create_transaction_step2.tpl');
	    	} else {
	    		$this->setError('Wrong invoice ID!');
	    		redirect('admin/payment/transactions/create/');
	    	}
    	}
    }
}


/*New version of Web 2.0 Directory script was just released.

New functionality and features:

    * Membership packages module. Now site admin may concatenate listings levels in packages, this allows users to pay onetime only for package which contains different numbers of listings or include unlimited number of listings. Also packages settings allow to choose2 modes of packages functionality: 1. only membership packages; 2. both methods of listings creation - under membership packages and standalone/independent listings.
    * Discount coupons module. Using it admin opens giant marketing resources of website based on Web 2.0 Directory script. Discount coupons configuration includes following settings: goods types (listings, banners, packages) which allowed to recalculate price according to discount; percents or exact value type of discount; active period of discount; total usage count limit and per user usage count limit (be carefull with these options when you assign coupon to user - total usage count limi must be greater than per user count or unlimited); you may allow users to use coupons only when they assigned with these coupons or set to use without assigning (for example you may public coupons somewhere outside the site); also set assigning events (discount coupons send to users when these events raise + any coupon may be assigned manually by admin).
    * Predefined locations.

*/

?>
