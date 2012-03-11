<?php

class couponsUsageClass
{
	public $id;
	public $coupon_id;
	public $coupon;
	public $user_id;
	public $user;
	public $invoice_id;
	public $invoice;
	public $provided_discount;
	public $usage_date;

	public function setUsageFromArray($row)
	{
		$this->id = $row['id'];
		$this->coupon_id = $row['coupon_id'];
		$this->user_id = $row['user_id'];
		$this->invoice_id = $row['invoice_id'];
		$this->usage_date = $row['usage_date'];
		
		$CI = &get_instance();
		$CI->load->model('discount_coupons', 'discount_coupons');
		$CI->load->model('users', 'users');
		$CI->load->model('payment', 'payment');
		
		$this->coupon = $CI->discount_coupons->getCouponById($this->coupon_id);
		$this->user = $CI->users->getUserById($this->user_id);
		$this->invoice = $CI->payment->getInvoiceById($this->invoice_id);
		
		$init_value = $this->invoice->value;
		$this->invoice->calculateValueWithDiscount();
		$this->provided_discount = $init_value - $this->invoice->value;
	}
}
?>