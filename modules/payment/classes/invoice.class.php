<?php
include_once(MODULES_PATH . 'payment/classes/listings_goods.class.php');
include_once(MODULES_PATH . 'payment/classes/listings_upgrade_goods.class.php');
include_once(MODULES_PATH . 'payment/classes/banners_goods.class.php');
include_once(MODULES_PATH . 'payment/classes/packages_goods.class.php');

class invoice
{
	public $id;
	public $goods_category;
	public $goods_content;
	public $goods_id;
	public $goods_title;
	public $owner_id;
	public $status;
	public $currency;
	public $value;
	public $fixed_price;
	public $creation_date;
	public $owner;

	public function __construct($array)
	{
		$this->id = $array['id'];
		$this->goods_category = $array['goods_category'];
		if ($array['goods_category']) {
			$goods_class = $array['goods_category'] . 'Goods';
			$this->goods_content = new $goods_class;
		}
		$this->owner_id = $array['owner_id'];
		$this->status = $array['status'];
		$this->currency = $array['currency'];
		$this->value = $array['value'];
		$this->fixed_price = $array['fixed_price'];
		$this->creation_date = $array['creation_date'];
		
		$CI = &get_instance();
		$CI->load->model('users', 'users');
		$this->owner = $CI->users->getUserById($array['owner_id']);
	}
	
	public function setItemAttrs()
	{
	    $this->goods_content->setItemAttrs($this->id);
	    $this->goods_id = $this->goods_content->goods_id;
	    if (!is_null($this->goods_content->goods_id))
	    	$this->goods_title = $this->goods_content->goods_title;
	    else 
	    	$this->goods_title = 'Item is not available';
	}
	
	public function getViewUrl()
	{
		if ($this->goods_content->showUrl())
			return site_url('admin/' . $this->goods_content->category() . '/view/' . $this->goods_id . '/');
		else 
			return false;
	}
	
	public function calculateValueWithDiscount()
	{
		$CI = &get_instance();
		$CI->load->model('discount_coupons', 'discount_coupons');
		if ($coupon = $CI->discount_coupons->getCouponByUsageInvoiceId($this->id)) {
			if ($coupon->discount_type == 0) {
				// If percents
				$this->value = round($this->value*((100 - $coupon->value)/100), 2);
			} elseif ($coupon->discount_type == 1) {
				// If exact value
				if ($this->currency == $coupon->currency) {
					$this->value = $this->value - $coupon->value;
					if ($this->value < 0)
						$this->value = 0;
				}
			}
		}
	}
}
?>