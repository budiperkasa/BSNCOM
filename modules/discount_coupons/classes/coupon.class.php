<?php
include_once(MODULES_PATH . 'notifications/classes/notification_sender.class.php');

class couponClass
{
	public $id;
	public $code;
	public $description;
	public $allowed_goods_serialized;
	public $allowed_goods;
	public $assign_events_serialized;
	public $assign_events;
	public $usage_count_limit_all;
	public $usage_count_limit_user;
	public $value;
	public $currency;
	public $use_if_assigned;
	// 0 - percents
	// 1 - exact value
	public $discount_type;
	public $effective_date;
	public $effective_date_tmstmp;
	public $expiration_date;
	public $expiration_date_tmstmp;
	public $active;
	
	public function __construct()
	{
		$this->id = 'new';
		$this->code = '';
		$this->description = '';
		$this->allowed_goods_serialized = '';
		$this->allowed_goods = array('listings');
		$this->assign_events_serialized = '';
		$this->assign_events = array('custom_users');
		$this->usage_count_limit_all = 0;
		$this->usage_count_limit_user = 0;
		$this->value = 0;
		$this->currency = 'USD';
		$this->use_if_assigned = 1;
		// 0 - percents
		// 1 - exact value
		$this->discount_type = 0;
		$this->effective_date = null;
		$this->effective_date_tmstmp = null;
		$this->expiration_date = null;
		$this->expiration_date_tmstmp = null;
		$this->active = true;
	}
	
	public function setCouponFromArray($row)
	{
		if (isset($row['id']))
			$this->id = $row['id'];
		$this->code = $row['code'];
		$this->description = $row['description'];
		$this->allowed_goods_serialized = $row['allowed_goods_serialized'];
		$this->allowed_goods = unserialize($row['allowed_goods_serialized']);
		$this->assign_events_serialized = $row['assign_events_serialized'];
		$this->assign_events = unserialize($row['assign_events_serialized']);
		$this->usage_count_limit_all = $row['usage_count_limit_all'];
		$this->usage_count_limit_user = $row['usage_count_limit_user'];
		$this->value = $row['value'];
		$this->currency = $row['currency'];
		$this->use_if_assigned = $row['use_if_assigned'];
		$this->discount_type = $row['discount_type'];
		if ($row['effective_date'] != '0000-00-00') {
			$this->effective_date = $row['effective_date'];
			$this->effective_date_tmstmp = strtotime($row['effective_date']);
		}
		if ($row['expiration_date'] != '0000-00-00') {
			$this->expiration_date = $row['expiration_date'];
			$this->expiration_date_tmstmp = strtotime($row['expiration_date']);
		}
	}
	
	public function setCouponFromForm($form_result)
	{
		if (isset($row['id']))
			$this->id = $row['id'];
		$this->code = $form_result['code'];
        $this->description = $form_result['description'];
        $this->allowed_goods_serialized = serialize($form_result['allowed_goods[]']);
        if ($form_result['allowed_goods[]'])
        	$this->allowed_goods = $form_result['allowed_goods[]'];
        else 
        	$this->allowed_goods = array();
        $this->discount_type = $form_result['discount_type'];
        if ($form_result['discount_type'] == 0)
        	$this->value = $form_result['percents_value'];
        else {
        	$this->currency = $form_result['exact_value_currency'];
        	$this->value = $form_result['exact_value'];
        }
        if ($form_result['effective_date_tmstmp']) {
        	$this->effective_date = date("Y-m-d", $form_result['effective_date_tmstmp']);
        	$this->effective_date_tmstmp = $form_result['effective_date_tmstmp'];
        }
        if ($form_result['expiration_date_tmstmp']) {
	        $this->expiration_date = date("Y-m-d", $form_result['expiration_date_tmstmp']);
	        $this->expiration_date_tmstmp = $form_result['expiration_date_tmstmp'];
        }
        $this->usage_count_limit_all = $form_result['usage_count_limit_all'];
        $this->usage_count_limit_user = $form_result['usage_count_limit_user'];
        $this->use_if_assigned = $form_result['use_if_assigned'];
        $this->assign_events_serialized = serialize($form_result['assign_events[]']);
        if ($form_result['assign_events[]'])
        	$this->assign_events = $form_result['assign_events[]'];
        else 
        	$this->assign_events = array();
	}
	
	public function assignToUser($user_id)
	{
		$CI = &get_instance();
		$CI->load->model('users', 'users');
		$CI->load->model('discount_coupons', 'discount_coupons');
		$CI->discount_coupons->setCouponId($this->id);
		$coupon = $CI->discount_coupons->getCouponById();

		if (($user = $CI->users->getUserById($user_id)) && $CI->discount_coupons->assignCouponToUser($user_id)) {
			if (!$coupon->usage_count_limit_user)
				$usage_limit = LANG_UNLIMITED;
			else 
				$usage_limit = $coupon->usage_count_limit_user;
			$event_params = array(
				'COUPON_CODE' => $coupon->code,
				'COUPON_USAGE_COUNT_LIMIT' => $usage_limit,
				'RECIPIENT_NAME' => $user->login,
				'RECIPIENT_EMAIL' => $user->email
			);
   			$notification = new notificationSender('Discount coupon assigning');
			$notification->send($event_params);

			return true;
		} else 
			return false;
	}
	
	public function setActive($status)
	{
		$this->active = $status;
	}
}
?>