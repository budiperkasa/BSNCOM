<?php
include_once(MODULES_PATH . 'payment/classes/invoice.class.php');
include_once(MODULES_PATH . 'payment/classes/transaction.class.php');
include_once(MODULES_PATH . 'users/classes/users_group.class.php');
include_once(MODULES_PATH . 'payment/classes/paymentGateways/PaymentGateway.php');
include_once(MODULES_PATH . 'notifications/classes/notification_sender.class.php');

class paymentModel extends model
{
    public function createInvoice($goods_category, $goods_id, $goods_title, $owner_id, $price_currency, $price_value, $fixed_price = 0)
    {
    	$this->db->set('goods_category', $goods_category);
    	$this->db->set('goods_id', $goods_id);
    	$this->db->set('owner_id', $owner_id);
    	$this->db->set('currency', $price_currency);
    	$this->db->set('value', $price_value);
    	$this->db->set('fixed_price', $fixed_price);
    	// status: 1 - not paid
    	// status: 2 - paid
    	$this->db->set('status', 1);
    	$this->db->set('creation_date', date("Y-m-d H:i:s"));
    	if ($this->db->insert('invoices')) {
    		$invoice_id = $this->db->insert_id();
   			$users_model = $this->load->model('users', 'users');
   			$user = $users_model->getUserById($owner_id);
   			
   			$event_params = array(
				'ITEM_ID' => $goods_id,
				'ITEM_TITLE' => $goods_title,
				'PRICE_CURRENCY' => $price_currency,
				'PRICE_VALUE' => $price_value,
				'RECIPIENT_NAME' => $user->login,
				'RECIPIENT_EMAIL' => $user->email
			);
   			$notification = new notificationSender('Invoice creation');
			$notification->send($event_params);
			events::callEvent('Invoice creation', $event_params);
    	
    		return $invoice_id;
    	} else 
    		return false;
    }
    
    /**
     * Select all invoices table using paginator,
     * this executes optimized method with 3 queries
     *
     * @return array
     */
    public function selectInvoices($orderby = 'id', $direction = 'desc', $args = array())
    {
    	// Number of rows needs
    	$this->db->select('count(*) as count_rows');
    	$this->db->from('invoices as i');
    	$this->db->join('users as u', 'i.owner_id=u.id', 'left');
    	if (isset($args['search_owner'])) {
    		$this->db->like('u.login', urldecode(html_entity_decode($args['search_owner'])));
    	}
    	if (isset($args['search_status'])) {
    		$this->db->where('i.status', urldecode($args['search_status']));
    	}
    	if (isset($args['search_creation_date'])) {
    		$this->db->where('TO_DAYS(i.creation_date) = ', 'TO_DAYS("' . date("Y-m-d", $args['search_creation_date']) . '")', false);
    	}
    	if (isset($args['search_from_creation_date'])) {
    		$this->db->where('TO_DAYS(i.creation_date) >= ', 'TO_DAYS("' . date("Y-m-d", $args['search_from_creation_date']) . '")', false);
    	}
    	if (isset($args['search_to_creation_date'])) {
    		$this->db->where('TO_DAYS(i.creation_date) <= ', 'TO_DAYS("' . date("Y-m-d", $args['search_to_creation_date']) . '")', false);
    	}
    	$query = $this->db->get();
    	$row = $query->row_array('count_rows');
    	$this->paginator->setCount($row['count_rows']);

    	// Select id of listings that will be shown
    	$this->db->select('i.id');
    	$this->db->from('invoices AS i');
    	$this->db->join('users as u', 'i.owner_id=u.id', 'left');
    	if ($orderby)
    		$this->db->order_by('i.' . $orderby, $direction);
    	if (isset($args['search_owner'])) {
    		$this->db->like('u.login', urldecode(html_entity_decode($args['search_owner'])));
    	}
    	if (isset($args['search_status'])) {
    		$this->db->where('i.status', urldecode($args['search_status']));
    	}
    	if (isset($args['search_creation_date'])) {
    		$this->db->where('TO_DAYS(i.creation_date) = ', 'TO_DAYS("' . date("Y-m-d", $args['search_creation_date']) . '")', false);
    	}
    	if (isset($args['search_from_creation_date'])) {
    		$this->db->where('TO_DAYS(i.creation_date) >= ', 'TO_DAYS("' . date("Y-m-d", $args['search_from_creation_date']) . '")', false);
    	}
    	if (isset($args['search_to_creation_date'])) {
    		$this->db->where('TO_DAYS(i.creation_date) <= ', 'TO_DAYS("' . date("Y-m-d", $args['search_to_creation_date']) . '")', false);
    	}
    	$query = $this->db->get();
    	$ids = $this->paginator->getResultIds($query->result_array());

    	$invoices_array = array();
    	if (!empty($ids)) {
	    	$this->db->select('i.*');
	    	$this->db->from('invoices AS i');
	    	if ($orderby)
    			$this->db->order_by('i.' . $orderby, $direction);
	    	$_array = array();
	    	foreach ($ids AS $id) {
	    		$_array[] = $id['id'];
	    	}
	    	$this->db->where_in('i.id', $_array);
	    	if ($orderby)
    			$this->db->order_by('i.' . $orderby, $direction);
    		$query = $this->db->get();
    		
    		foreach ($query->result_array() AS $row) {
    			$invoice = new invoice($row);
    			$invoice->setItemAttrs();
    			$invoices_array[] = $invoice;
    		}
    	}
    	return $invoices_array;
    }
    
    /**
     * Select listings owner invoices table using paginator,
     * this executes optimized method with 3 queries
     *
     * @return array
     */
    public function selectMyInvoices($orderby = 'id', $direction = 'desc', $args = array())
    {
    	// Number of rows needs
    	$this->db->select('count(*) as count_rows');
    	$this->db->from('invoices as i');
    	$this->db->where('i.owner_id', $this->session->userdata('user_id'));
    	$query = $this->db->get();
    	$row = $query->row_array('count_rows');
    	$this->paginator->setCount($row['count_rows']);

    	// Select id of listings that will be shown
    	$this->db->select('i.id');
    	$this->db->from('invoices AS i');
    	$this->db->where('i.owner_id', $this->session->userdata('user_id'));
    	if ($orderby)
    		$this->db->order_by('i.' . $orderby, $direction);
    	$query = $this->db->get();
    	$ids = $this->paginator->getResultIds($query->result_array());

    	$invoices_array = array();
    	if (!empty($ids)) {
	    	$this->db->select('i.*');
	    	$this->db->from('invoices AS i');
	    	$this->db->where('i.owner_id', $this->session->userdata('user_id'));
	    	$_array = array();
	    	foreach ($ids AS $id) {
	    		$_array[] = $id['id'];
	    	}
	    	$this->db->where_in('i.id', $_array);
	    	if ($orderby)
    			$this->db->order_by('i.' . $orderby, $direction);
    		$query = $this->db->get();
    		
    		foreach ($query->result_array() AS $row) {
    			$invoice = new invoice($row);
    			$invoice->setItemAttrs();
    			$invoices_array[] = $invoice;
    		}
    	}
    	return $invoices_array;
    }
    
    public function getInvoiceById($invoice_id, $discount = false)
    {
    	$this->db->select('i.*');
	    $this->db->from('invoices AS i');
    	$this->db->where('i.id', $invoice_id);
    	$query = $this->db->get();
    	
    	if ($query->num_rows()) {
	    	$invoice = new invoice($query->row_array());
	    	$invoice->setItemAttrs();
	    	if ($discount) {
	    		$content_access_obj = contentAcl::getInstance();
				$CI = &get_instance();
				if ($CI->load->is_module_loaded('discount_coupons') && $content_access_obj->isPermission('Use coupons'))
	    			$invoice->calculateValueWithDiscount();
	    	}
	    	return $invoice;
    	} else {
    		return false;
    	}
    }

    /**
     * select all payment gateways of the system
     *
     * @return array
     */
    public function getPaymentGateways($currency_code)
    {
    	$query = $this->db->get('payment_gateways');
    	$available_gateways = array();
    	foreach ($query->result_array() AS $gateway) {
			include_once(MODULES_PATH . $gateway['module'] . '/classes/' . $gateway['module'] . '.class.php');
			$gateway_instance = new $gateway['module'];
			if (array_search($currency_code, $gateway_instance->available_currencies) !== FALSE) {
				$available_gateways[] = $gateway;
			}
    	}
    	return $available_gateways;
    }
    
    public function getAllPaymentGateways()
    {
    	$query = $this->db->get('payment_gateways');
    	return $query->result_array();
    }

    /**
     * is tranaction wasn't processed yet
     *
     * @param int $txn_id
     * @return bool
     */
    public function isUniqueTransaction($txn_id)
    {
    	$this->db->select('id');
    	$this->db->from('transactions');
    	$this->db->where('txn_id', $txn_id);
    	$query = $this->db->get();
    	
    	return !($query->num_rows());
    }
    
    /**
     * needed to checks if transaction payment and invoice value are equal
     *
     * @param int $invoice_id
     * @param string $transaction_currency
     * @return bool || float
     */
    public function getInvoiceValue($invoice_id, $transaction_currency)
    {
    	if ($invoice = $this->getInvoiceById($invoice_id, true)) {
    		if ($invoice->currency == $transaction_currency) {
    			return $invoice->value;
    		} else {
    			return false;
    		}
    	} else {
    		return false;
    	}
    }

    /**
     * complete transaction
     *
     * @param int $item_number
     * @param int $invoice_id
     * @param string $payment_status
     * @param string $txn_id
     * @param float $mc_gross
     * @param float $mc_fee
     * @param string $mc_currency
     * @param int $quantity
     * @param string $additional_fields
     * @return bool
     */
    public function createTransaction($payment_gateway, $invoice_id, $payment_status, $txn_id, $mc_gross, $mc_fee, $mc_currency, $quantity, $additional_fields)
    {
    	$this->db->set('payment_gateway', $payment_gateway);
    	$this->db->set('invoice_id', $invoice_id);
    	$this->db->set('payment_status', $payment_status);
    	$this->db->set('txn_id', $txn_id);
    	$this->db->set('mc_gross', $mc_gross);
    	$this->db->set('mc_fee', $mc_fee);
    	$this->db->set('mc_currency', $mc_currency);
    	$this->db->set('quantity', $quantity);
    	$this->db->set('payment_date', date("Y-m-d H:i:s"));
    	$txn_fields = '';
    	foreach ($additional_fields AS $key=>$field) {
    		$txn_fields .= $key . '=' . $field . '; ';
    	}
    	$this->db->set('txn_fields', $txn_fields);
    	if ($this->db->insert('transactions')) {
    		$event_params = array(
				'INVOICE_ID' => $invoice_id,
				'PAYMENT_GATEWAY' => $payment_gateway,
				'TOTAL_SUM' => $mc_gross,
				'CURRENCY' => $mc_currency,
				'QUANTITY' => $quantity,
			);
			events::callEvent('Transaction completion', $event_params);
			return true;
    	} else 
    	return false;
    }

    /**
     * Saves invoice status
     *
     * @param int $status
     * @return bool
     */
    public function saveInvoiceStatus($invoice_id, $status)
    {
    	$this->db->set('status', $status);
    	$this->db->where('id', $invoice_id);
    	return $this->db->update('invoices');
    }

    /**
     * Select all transactions table using paginator,
     * this executes optimized method with 3 queries
     *
     * @return array
     */
    public function selectTransactions($orderby = 'id', $direction = 'desc', $args = array())
    {
    	// Number of rows needs
    	$this->db->select('count(*) as count_rows');
    	$this->db->from('transactions as t');
    	$this->db->join('invoices as i', 'i.id=t.invoice_id', 'left');
    	$this->db->join('users as u', 'i.owner_id=u.id', 'left');
    	if (isset($args['search_owner'])) {
    		$this->db->like('u.login', urldecode(html_entity_decode($args['search_owner'])));
    	}
    	if (isset($args['search_creation_date'])) {
    		$this->db->where('TO_DAYS(t.payment_date) = ', 'TO_DAYS("' . date("Y-m-d", $args['search_creation_date']) . '")', false);
    	}
    	if (isset($args['search_from_creation_date'])) {
    		$this->db->where('TO_DAYS(t.payment_date) >= ', 'TO_DAYS("' . date("Y-m-d", $args['search_from_creation_date']) . '")', false);
    	}
    	if (isset($args['search_to_creation_date'])) {
    		$this->db->where('TO_DAYS(t.payment_date) <= ', 'TO_DAYS("' . date("Y-m-d", $args['search_to_creation_date']) . '")', false);
    	}
    	$query = $this->db->get();
    	$row = $query->row_array('count_rows');
    	$this->paginator->setCount($row['count_rows']);

    	// Select id of listings that will be shown
    	$this->db->select('t.id');
    	$this->db->from('transactions as t');
    	$this->db->join('invoices as i', 'i.id=t.invoice_id', 'left');
    	$this->db->join('users as u', 'i.owner_id=u.id', 'left');
    	if ($orderby)
    		$this->db->order_by('t.' . $orderby, $direction);
    	if (isset($args['search_owner'])) {
    		$this->db->like('u.login', urldecode(html_entity_decode($args['search_owner'])));
    	}
    	if (isset($args['search_creation_date'])) {
    		$this->db->where('TO_DAYS(t.payment_date) = ', 'TO_DAYS("' . date("Y-m-d", $args['search_creation_date']) . '")', false);
    	}
    	if (isset($args['search_from_creation_date'])) {
    		$this->db->where('TO_DAYS(t.payment_date) >= ', 'TO_DAYS("' . date("Y-m-d", $args['search_from_creation_date']) . '")', false);
    	}
    	if (isset($args['search_to_creation_date'])) {
    		$this->db->where('TO_DAYS(t.payment_date) <= ', 'TO_DAYS("' . date("Y-m-d", $args['search_to_creation_date']) . '")', false);
    	}
    	$query = $this->db->get();
    	$ids = $this->paginator->getResultIds($query->result_array());

    	$transactions_array = array();
    	if (!empty($ids)) {
	    	$this->db->select('t.id AS t_id');
	    	$this->db->select('t.invoice_id AS t_invoice_id');
	    	$this->db->select('t.payment_gateway AS t_payment_gateway');
	    	$this->db->select('t.payment_status AS t_payment_status');
	    	$this->db->select('t.mc_currency AS t_mc_currency');
	    	$this->db->select('t.quantity AS t_quantity');
	    	$this->db->select('t.mc_gross AS t_value');
	    	$this->db->select('t.mc_fee AS t_fee');
	    	$this->db->select('t.payment_date AS t_payment_date');
	    	$this->db->select('i.*');
	    	$this->db->from('transactions as t');
    		$this->db->join('invoices as i', 'i.id=t.invoice_id', 'left');
	    	$_array = array();
	    	foreach ($ids AS $id) {
	    		$_array[] = $id['id'];
	    	}
	    	$this->db->where_in('t.id', $_array);
	    	if ($orderby)
    			$this->db->order_by('t.' . $orderby, $direction);
    		$query = $this->db->get();

    		foreach ($query->result_array() AS $row) {
    			$transaction = new transaction($row);
    			$transactions_array[] = $transaction;
    		}
    	}
    	return $transactions_array;
    }
    
    /**
     * Select listings owner transactions table using paginator,
     * this executes optimized method with 3 queries
     *
     * @return array
     */
    public function selectMyTransactions($orderby = 'id', $direction = 'desc', $args = array())
    {
    	// Number of rows needs
    	$this->db->select('count(*) as count_rows');
    	$this->db->from('transactions as t');
    	$this->db->join('invoices as i', 'i.id=t.invoice_id', 'left');
    	$this->db->where('i.owner_id', $this->session->userdata('user_id'));
    	$query = $this->db->get();
    	$row = $query->row_array('count_rows');
    	$this->paginator->setCount($row['count_rows']);

    	$this->db->select('t.id');
    	$this->db->from('transactions as t');
    	$this->db->join('invoices as i', 'i.id=t.invoice_id', 'left');
    	$this->db->where('i.owner_id', $this->session->userdata('user_id'));
    	if ($orderby)
    		$this->db->order_by('t.' . $orderby, $direction);
    	$query = $this->db->get();
    	$ids = $this->paginator->getResultIds($query->result_array());

    	$transactions_array = array();
    	if (!empty($ids)) {
	    	$this->db->select('t.id AS t_id');
	    	$this->db->select('t.invoice_id AS t_invoice_id');
	    	$this->db->select('t.payment_gateway AS t_payment_gateway');
	    	$this->db->select('t.payment_status AS t_payment_status');
	    	$this->db->select('t.mc_currency AS t_mc_currency');
	    	$this->db->select('t.quantity AS t_quantity');
	    	$this->db->select('t.mc_gross AS t_value');
	    	$this->db->select('t.mc_fee AS t_fee');
	    	$this->db->select('t.payment_date AS t_payment_date');
	    	$this->db->select('i.*');
	    	$this->db->from('transactions as t');
    		$this->db->join('invoices as i', 'i.id=t.invoice_id', 'left');
	    	$_array = array();
	    	foreach ($ids AS $id) {
	    		$_array[] = $id['id'];
	    	}
	    	$this->db->where_in('t.id', $_array);
	    	if ($orderby)
    			$this->db->order_by('t.' . $orderby, $direction);
    		$query = $this->db->get();

    		foreach ($query->result_array() AS $row) {
    			$transaction = new transaction($row);
    			$transactions_array[] = $transaction;
    		}
    	}
    	return $transactions_array;
    }
    
    public function getMyInvoicesCount()
    {
    	$this->db->select('count(*) AS invoices_count');
    	$this->db->from('invoices');
    	$this->db->where('owner_id', $this->session->userdata('user_id'));

    	$query = $this->db->get();
    	$row = $query->row_array();

    	return $row['invoices_count'];
    }
    
    public function getMyTransactionsCount()
    {
    	$this->db->select('count(t.id) AS transactions_count');
    	$this->db->from('transactions AS t');
    	$this->db->join('invoices AS i', 'i.id=t.invoice_id', 'left');
    	$this->db->where('i.owner_id', $this->session->userdata('user_id'));
    	$query = $this->db->get();
    	$row = $query->row_array();

    	return $row['transactions_count'];
    }
    
    public function getInvoicesSummary()
	{
		$this->db->select('status');
		$this->db->select('count(if(status=1, 1, null)) AS not_paid_status', false);
		$this->db->select('count(if(status=2, 1, null)) AS paid_status', false);
		$this->db->from('invoices');
		$this->db->group_by('status');
		$query = $this->db->get();
		
		return $query->result_array();
	}
	
	public function getTransactionsCount()
	{
		$this->db->select('count(*) AS transactions_count');
		$this->db->from('transactions');
		$query = $this->db->get();
    	$row = $query->row_array();

    	return $row['transactions_count'];
	}
	
	public function getTransactionsSummary()
	{
		$this->db->select('mc_currency');
		$this->db->select('SUM(mc_gross) AS transactions_amount');
		$this->db->select('SUM(mc_fee) AS transactions_fee_amount');
		$this->db->from('transactions');
		$this->db->group_by('mc_currency');
		$query = $this->db->get();
		
		return $query->result_array();
	}
	
	public function getMyInvoicesSummary()
	{
		$this->db->select('status');
		$this->db->select('count(if(status=1, 1, null)) AS not_paid_status', false);
		$this->db->select('count(if(status=2, 1, null)) AS paid_status', false);
		$this->db->from('invoices');
		$this->db->group_by('status');
		$this->db->where('owner_id', $this->session->userdata('user_id'));
		$query = $this->db->get();
		
		return $query->result_array();
	}

	public function getMyTransactionsSummary()
	{
		$this->db->select('t.mc_currency');
		$this->db->select('SUM(t.mc_gross) AS transactions_amount', false);
		$this->db->select('SUM(t.mc_fee) AS transactions_fee_amount', false);
		$this->db->from('transactions AS t');
		$this->db->join('invoices AS i', 't.invoice_id=i.id', 'left');
		$this->db->where('i.owner_id', $this->session->userdata('user_id'));
		$this->db->group_by('t.mc_currency');
		$query = $this->db->get();
		
		return $query->result_array();
	}
	
	public function completeTransaction($invoice, $quantity)
	{
		if ($this->createTransaction(
		    'Manual',
		    $invoice->id,
		    'Completed',
		    md5(time()),
		    floatval($invoice->value*$quantity),
		    0,
		    $invoice->currency,
		    $quantity,
		    array()
		)) {
			// update goods content status and expiration date
			$invoice->goods_content->completePayment($quantity);

			// Invoice was payed
			$this->saveInvoiceStatus($invoice->id, 2);
		}
	}
	
	public function getTransactionByInvoiceId($invoice_id)
	{
		$this->db->select('t.id AS t_id');
	    $this->db->select('t.invoice_id AS t_invoice_id');
	    $this->db->select('t.payment_gateway AS t_payment_gateway');
	    $this->db->select('t.payment_status AS t_payment_status');
	    $this->db->select('t.mc_currency AS t_mc_currency');
	    $this->db->select('t.quantity AS t_quantity');
	    $this->db->select('t.mc_gross AS t_value');
	    $this->db->select('t.mc_fee AS t_fee');
	    $this->db->select('t.payment_date AS t_payment_date');
	    $this->db->select('i.*');
	    $this->db->from('transactions as t');
    	$this->db->join('invoices as i', 'i.id=t.invoice_id', 'left');
	    $this->db->where('t.invoice_id', $invoice_id);
    	$query = $this->db->get();

    	if ($row = $query->row_array()) {
	    	$transaction = new transaction($row);
	    	return $transaction;
    	} else {
    		return false;
    	}
	}
}
?>