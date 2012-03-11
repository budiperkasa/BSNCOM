<?php
include_once(MODULES_PATH . 'payment/classes/paymentGateways/PaymentGateway.php');
include_once(MODULES_PATH . 'payment/classes/invoice.class.php');
include_once(MODULES_PATH . 'alertpay/classes/alertpay.class.php');

class alertpayController extends controller
{
    public function index($invoice_id, $quantity)
    {
    	$system_settings = registry::get('system_settings');
    	if (!isset($system_settings['alertpay_email']) || !$system_settings['alertpay_email']) {
    		$this->setError(LANG_PAYMENT_GATEWAY_ERROR);
    		redirect('admin/payment/invoices/pay/' . $invoice_id . '/');
    	}

    	$this->load->model('payment', 'payment');
    	$invoice = $this->payment->getInvoiceById($invoice_id, true);

    	$alertpay = new Alertpay;
    	// May be we need to process transaction in test mode 
    	//$alertpay->enableTestMode();

    	// Payment gateway merchant's email 
		$alertpay->addField('ap_merchant', $system_settings['alertpay_email']);
		// We will return here after all steps of payment
		$alertpay->addField('ap_returnurl', site_url('admin/payment/invoices/success/' . $invoice_id));
		// We will return here when payment will be canceled on some step
		$alertpay->addField('ap_cancelurl', site_url('admin/payment/invoices/my/'));
		// specific field for only AlertPay
		$alertpay->addField('ap_purchasetype', 'item-goods');
		// The name of purchase item
		$alertpay->addField('ap_itemname', $invoice->goods_title);
		// ID of purchase item
		$alertpay->addField('ap_itemcode', $invoice_id);
		// Currency code (Alertpay::available_currencies - this list of available currencies is in alertpay class)
		$alertpay->addField('ap_currency', $invoice->currency);
		// Quantity
		$alertpay->addField('ap_quantity', $quantity);
		// Money amount for each individual purchase item
		$alertpay->addField('ap_amount', $invoice->value);

		$view = $this->load->view();
		$view->assign('alertpay', $alertpay);

		$view->addJsFile('jquery.timers-1.1.2.js');
		$view->display('alertpay/alertpay_pay_redirect.tpl');
    }

    public function ipn()
    {
    	$system_settings = registry::get('system_settings');
    	
    	$this->load->model('payment', 'payment');
    	$alertpay = new Alertpay;
    	// May be we need to process transaction in test mode 
    	//$alertpay->enableTestMode();

    	if ($alertpay->validateIpn()) {
    		// Validate IPN response
    		// Does merchant email from request match with email in settings?
    		// and sucurity codes?
    		if ($alertpay->ipnData['ap_merchant'] == $system_settings['alertpay_email']
    			// IPN Simulator doesn't send security code - so we commented this 
    			//&& $alertpay->ipnData['ap_securitycode'] == $system_settings['alertpay_securitycode']
    			) {
    			// Is transaction unique?
	    		if ($this->payment->isUniqueTransaction($alertpay->ipnData['ap_referencenumber'])) {
	    			$invoice_id = $alertpay->ipnData['ap_itemcode'];
	    			$invoice_value = $this->payment->getInvoiceValue($invoice_id, $alertpay->ipnData['ap_currency']);
	    			// Check currency and total paid amount
	    			if ($invoice_value && floatval($alertpay->ipnData['ap_totalamount']) == floatval($alertpay->ipnData['ap_quantity']*($invoice_value))) {
	    				// Create transaction record in the DB
		    			if ($this->payment->createTransaction(
		    				'AlertPay',
		    				$alertpay->ipnData['ap_itemcode'],
		    				$alertpay->ipnData['ap_status'],
		    				$alertpay->ipnData['ap_referencenumber'],
		    				$alertpay->ipnData['ap_totalamount'],
		    				$alertpay->ipnData['ap_feeamount'],
		    				$alertpay->ipnData['ap_currency'],
		    				$alertpay->ipnData['ap_quantity'],
		    				$alertpay->ipnData
		    			)) {
		    				// If transaction was successfull - set invoice to "paid" status and complete the payment
		    				if ($alertpay->ipnData['ap_status'] == 'Success') {
		    					$invoice = $this->payment->getInvoiceById($invoice_id);
		    					// update goods content status and expiration date
		    					$invoice->goods_content->completePayment($alertpay->ipnData['ap_quantity']);

		    					// Invoice was payed
		    					$this->payment->saveInvoiceStatus($invoice_id, 2);
		    				}
		    			}
	    			}
	    		}
    		}
    	}
    }
    
    public function settings()
    {
	    $system_settings = registry::get('system_settings');
	    $this->load->model('settings', 'settings');

	    if ($this->input->post('submit')) {
			$this->form_validation->set_rules('alertpay_email', LANG_ALERTPAY_EMAIL, 'required|valid_email');
			$this->form_validation->set_rules('alertpay_securitycode', LANG_ALERTPAY_SECURITYCODE, 'required');

			if ($this->form_validation->run() !== FALSE) {
				if ($this->settings->saveSystemSettings($this->form_validation->result_array())) {
					$this->setSuccess(LANG_SYSTEM_SETTING_SAVE_SUCCESS);
					redirect('admin/payment/settings/alertpay/');
				}
			}
			$alertpay_email = $this->form_validation->set_value('alertpay_email');
			$alertpay_securitycode = $this->form_validation->set_value('alertpay_securitycode');
	    } else {
	    	$alertpay_email = $system_settings['alertpay_email'];
	    	$alertpay_securitycode = $system_settings['alertpay_securitycode'];
	    }

	    $view = $this->load->view();
	    $view->assign('alertpay_email', $alertpay_email);
	    $view->assign('alertpay_securitycode', $alertpay_securitycode);
		$view->display('alertpay/alertpay_settings.tpl');
    }
}
?>