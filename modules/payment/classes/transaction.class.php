<?php

class transaction
{
	public $id;
	public $invoide_id;
	public $payment_gateway;
	public $payment_status;
	public $currency;
	public $quantity;
	public $value;
	public $fee;
	public $payment_date;
	public $invoice;

	public function __construct($array)
	{
		$this->id = $array['t_id'];
		$this->invoice_id = $array['t_invoice_id'];
		$this->payment_gateway = $array['t_payment_gateway'];
		$this->payment_status = $array['t_payment_status'];
		$this->currency = $array['t_mc_currency'];
		$this->quantity = $array['t_quantity'];
		$this->value = $array['t_value'];
		$this->fee = $array['t_fee'];
		$this->payment_date = $array['t_payment_date'];

		$this->invoice = new invoice($array);
		$this->invoice->setItemAttrs();
	}
}
?>