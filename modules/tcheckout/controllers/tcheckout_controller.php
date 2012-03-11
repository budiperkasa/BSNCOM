<?php
include_once(MODULES_PATH . 'payment/classes/paymentGateways/PaymentGateway.php');
include_once(MODULES_PATH . 'tcheckout/classes/tcheckout.class.php');
include_once(MODULES_PATH . 'payment/classes/invoice.class.php');

class tcheckoutController extends controller
{
    public function index($invoice_id, $quantity)
    {
    	$system_settings = registry::get('system_settings');
    	if (!isset($system_settings['2checkout_sid']) || !$system_settings['2checkout_sid']) {
    		$this->setError(LANG_PAYMENT_GATEWAY_ERROR);
    		redirect('admin/payment/invoices/pay/' . $invoice_id . '/');
    	}

    	$this->load->model('payment', 'payment');
    	$invoice = $this->payment->getInvoiceById($invoice_id, true);
    	
    	$twoco = new tcheckout;
    	$twoco->addField('sid', $system_settings['2checkout_sid']);
    	$twoco->addField('c_prod_1', $invoice->goods_id.','.$quantity);
		$twoco->addField('c_name_1', "Listing - '" . $invoice->goods_title . "'");
		$twoco->addField('c_price_1', $invoice->value);
		$twoco->addField('currency_code', $invoice->currency);
		$twoco->addField('total', $invoice->value*$quantity);
		$twoco->addField('cart_order_id', $invoice_id);
		//$twoco->addField('quantity', $quantity);
		$twoco->addField('x_Receipt_Link_URL', site_url('tcheckout/ipn/'));

		$view = $this->load->view();
		$view->assign('twoco', $twoco);
		$view->addJsFile('jquery.timers-1.1.2.js');
		$view->display('tcheckout/2checkout_pay_redirect.tpl');
    }

    public function ipn()
    {
    	$system_settings = registry::get('system_settings');
    	
    	$this->load->model('payment', 'payment');
    	$twoco = new tcheckout;
    	$twoco->setSecret($system_settings['2checkout_secret_word']);

    	if ($twoco->validateIpn()) {
	    	$invoice_id = $twoco->ipnData['cart_order_id'];
	    	
	    	$invoice = $this->payment->getInvoiceById($invoice_id, true);
		    $quantity = $twoco->ipnData['total']/$invoice->value;
		    if ($this->payment->createTransaction(
		    	'2Checkout',
		    	$invoice_id,
		    	'Completed',
		    	$twoco->ipnData['order_number'],
		    	$twoco->ipnData['total'],
		    	0,
		    	$twoco->ipnData['currency_code'],
		    	$quantity,
		    	$twoco->ipnData
		    )) {
		    	// update goods content status and expiration date
		    	$invoice->goods_content->completePayment($quantity);

		    	// Invoice was payed
		    	$this->payment->saveInvoiceStatus($invoice_id, 2);
		    }
    	}
    }
    
    public function settings()
    {
    	$system_settings = registry::get('system_settings');

		$this->load->model('settings', 'settings');

	    if ($this->input->post('submit')) {
			$this->form_validation->set_rules('2checkout_sid', LANG_2CHECKOUT_SID, 'required|integer');
			$this->form_validation->set_rules('2checkout_secret_word', LANG_2CHECKOUT_SWORD, 'required|max_length[255]');

			if ($this->form_validation->run() !== FALSE) {
				if ($this->settings->saveSystemSettings($this->form_validation->result_array())) {
					$this->setSuccess(LANG_SYSTEM_SETTING_SAVE_SUCCESS);
					redirect('admin/payment/settings/2checkout/');
				}
			}
			$tcheckout_sid = $this->form_validation->set_value('2checkout_sid');
			$tcheckout_secret_word = $this->form_validation->set_value('2checkout_secret_word');
	    } else {
	    	$tcheckout_sid = $system_settings['2checkout_sid'];
	    	$tcheckout_secret_word = $system_settings['2checkout_secret_word'];
	    }

	    $view = $this->load->view();
		$view->assign('2checkout_sid', $tcheckout_sid);
		$view->assign('2checkout_secret_word', $tcheckout_secret_word);
		$view->display('tcheckout/2checkout_settings.tpl');
    }
}
?>