<?php
include_once(MODULES_PATH . 'payment/classes/paymentGateways/PaymentGateway.php');
include_once(MODULES_PATH . 'paypal/classes/paypal.class.php');
include_once(MODULES_PATH . 'payment/classes/invoice.class.php');

class paypalController extends controller
{
    public function index($invoice_id, $quantity)
    {
    	$system_settings = registry::get('system_settings');
    	if (!isset($system_settings['paypal_email']) || !$system_settings['paypal_email']) {
    		$this->setError(LANG_PAYMENT_GATEWAY_ERROR);
    		redirect('admin/payment/invoices/pay/' . $invoice_id . '/');
    	}

    	$this->load->model('payment', 'payment');
    	$invoice = $this->payment->getInvoiceById($invoice_id, true);

    	$paypal = new Paypal;
    	//$paypal->enableTestMode();
		$paypal->addField('business', $system_settings['paypal_email']);
		$paypal->addField('return', site_url('admin/payment/invoices/success/' . $invoice_id));
		$paypal->addField('cancel_return', site_url('admin/payment/invoices/my/'));
		$paypal->addField('notify_url', site_url('paypal/ipn'));
		$paypal->addField('item_name', $invoice->goods_title);
		$paypal->addField('item_number', $invoice_id);
		$paypal->addField('currency_code', $invoice->currency);
		$paypal->addField('quantity', $quantity);
		$paypal->addField('amount', $invoice->value);

		$view = $this->load->view();
		$view->assign('paypal', $paypal);

		$view->addJsFile('jquery.timers-1.1.2.js');
		$view->display('paypal/paypal_pay_redirect.tpl');
    }

    public function ipn()
    {
    	$system_settings = registry::get('system_settings');
    	
    	$this->load->model('payment', 'payment');
    	$paypal = new Paypal;
    	//$paypal->enableTestMode();

    	if ($paypal->validateIpn()) {
    		if ($paypal->ipnData['business'] == $system_settings['paypal_email']) {
	    		if ($this->payment->isUniqueTransaction($paypal->ipnData['txn_id'])) {
	    			$invoice_id = $paypal->ipnData['item_number'];
	    			$invoice_value = $this->payment->getInvoiceValue($invoice_id, $paypal->ipnData['mc_currency']);
	    			if ($invoice_value && floatval($paypal->ipnData['mc_gross']) == floatval($paypal->ipnData['quantity']*($invoice_value))) {
		    			if ($this->payment->createTransaction(
		    				'PayPal',
		    				$paypal->ipnData['item_number'],
		    				$paypal->ipnData['payment_status'],
		    				$paypal->ipnData['txn_id'],
		    				$paypal->ipnData['mc_gross'],
		    				$paypal->ipnData['mc_fee'],
		    				$paypal->ipnData['mc_currency'],
		    				$paypal->ipnData['quantity'],
		    				$paypal->ipnData
		    			)) {
		    				if ($paypal->ipnData['payment_status'] == 'Completed') {
		    					$invoice = $this->payment->getInvoiceById($invoice_id);
		    					// update goods content status and expiration date
		    					$invoice->goods_content->completePayment($paypal->ipnData['quantity']);

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
			$this->form_validation->set_rules('paypal_email', LANG_PAYPAL_EMAIL, 'required|valid_email');

			if ($this->form_validation->run() !== FALSE) {
				if ($this->settings->saveSystemSettings($this->form_validation->result_array())) {
					$this->setSuccess(LANG_SYSTEM_SETTING_SAVE_SUCCESS);
					redirect('admin/payment/settings/paypal/');
				}
			}
			$paypal_email = $this->form_validation->set_value('paypal_email');
	    } else {
	    	$paypal_email = $system_settings['paypal_email'];
	    }

	    $view = $this->load->view();
	    $view->assign('paypal_email', $paypal_email);
		$view->display('paypal/paypal_settings.tpl');
    }
}
?>