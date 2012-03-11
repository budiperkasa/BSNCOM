<?php
include_once(MODULES_PATH . 'discount_coupons/classes/coupon.class.php');
include_once(MODULES_PATH . 'discount_coupons/classes/coupons_usage.class.php');

class discount_couponsModel extends model
{
	private $_coupon_id;
	
    public function setCouponId($coupon_id)
    {
    	$this->_coupon_id = $coupon_id;
    }

    public function getAllCoupons()
    {
    	$this->db->select('*');
    	$this->db->from('discount_coupons');
		$query = $this->db->get();
		
		$coupons = array();
		foreach ($query->result_array() AS $row) {
			$coupon = new couponClass;
			$coupon->setCouponFromArray($row);

			$coupons[] = $coupon;
		}
		return $coupons;
    }

    public function getNewCoupon()
    {
		$coupon = new couponClass();
        return $coupon;
    }
    
    public function getCouponFromForm($form_result)
    {
		$coupon = new couponClass;
		$coupon->setCouponFromForm($form_result);

        return $coupon;
    }
    
    /**
     * is there coupon with such code in the DB?
     *
     * @param string $code
     */
    public function is_coupon_code($code)
    {
    	$this->db->select();
		$this->db->from('discount_coupons');
		$this->db->where('code', $code);
		if (!is_null($this->_coupon_id)) {
			$this->db->where('id !=', $this->_coupon_id);
		}
		$query = $this->db->get();

		return $query->num_rows();
    }
    
    public function saveCoupon($form_result)
    {
        $this->db->set('code', $form_result['code']);
        $this->db->set('description', $form_result['description']);
        if ($form_result['allowed_goods[]']) {
        	$this->db->set('allowed_goods_serialized', serialize($form_result['allowed_goods[]']));
        } else {
        	$this->db->set('allowed_goods_serialized', '');
        }
        $this->db->set('discount_type', $form_result['discount_type']);
        if ($form_result['discount_type'] == 0) {
        	$this->db->set('currency', '');
        	$this->db->set('value', $form_result['percents_value']);
        } else {
        	$this->db->set('currency', $form_result['exact_value_currency']);
        	$this->db->set('value', $form_result['exact_value']);
        }
        if ($form_result['effective_date_tmstmp'])
        	$this->db->set('effective_date', date("Y-m-d", $form_result['effective_date_tmstmp']));
        else
        	$this->db->set('effective_date', "0000-00-00");
        if ($form_result['expiration_date_tmstmp'])
        	$this->db->set('expiration_date', date("Y-m-d", $form_result['expiration_date_tmstmp']));
        else
        	$this->db->set('expiration_date', "0000-00-00");
        $this->db->set('usage_count_limit_all', $form_result['usage_count_limit_all']);
        $this->db->set('usage_count_limit_user', $form_result['usage_count_limit_user']);
        $this->db->set('use_if_assigned', $form_result['use_if_assigned']);
        if ($form_result['assign_events[]']) {
        	$this->db->set('assign_events_serialized', serialize($form_result['assign_events[]']));
        } else {
        	$this->db->set('assign_events_serialized', '');
        }

        if ($this->db->insert('discount_coupons')) {
        	$coupon_id = $this->db->insert_id();

        	$system_settings = registry::get('system_settings');
        	if (@$system_settings['language_areas_enabled']) {
        		translations::saveTranslations(array('discount_coupons', 'description', $coupon_id));
        	}
        	return true;
        }
    }
    
    public function getCouponById($coupon_id = null)
    {
    	if (is_null($coupon_id))
    		$coupon_id = $this->_coupon_id;

    	$this->db->select('*');
    	$this->db->from('discount_coupons AS dc');
    	$this->db->where('id', $coupon_id);
		$query = $this->db->get();

		if ($query->num_rows()) {
			$coupon = new couponClass;
			$coupon->setCouponFromArray($query->row_array());

			return $coupon;
		} else {
			return false;
		}
    }
    
    public function saveCouponById($form_result)
    {
        $this->db->set('code', $form_result['code']);
        $this->db->set('description', $form_result['description']);
        if ($form_result['allowed_goods[]'])
        	$this->db->set('allowed_goods_serialized', serialize($form_result['allowed_goods[]']));
        $this->db->set('discount_type', $form_result['discount_type']);
        if ($form_result['discount_type'] == 0)
        	$this->db->set('value', $form_result['percents_value']);
        else {
        	$this->db->set('currency', $form_result['exact_value_currency']);
        	$this->db->set('value', $form_result['exact_value']);
        }
        if ($form_result['effective_date_tmstmp'])
        	$this->db->set('effective_date', date("Y-m-d", $form_result['effective_date_tmstmp']));
        else
        	$this->db->set('effective_date', "0000-00-00");
        if ($form_result['expiration_date_tmstmp'])
        	$this->db->set('expiration_date', date("Y-m-d", $form_result['expiration_date_tmstmp']));
        else
        	$this->db->set('expiration_date', "0000-00-00");
        $this->db->set('usage_count_limit_all', $form_result['usage_count_limit_all']);
        $this->db->set('usage_count_limit_user', $form_result['usage_count_limit_user']);
        $this->db->set('use_if_assigned', $form_result['use_if_assigned']);
        if ($form_result['assign_events[]'])
        	$this->db->set('assign_events_serialized', serialize($form_result['assign_events[]']));
        $this->db->where('id', $this->_coupon_id);
        return $this->db->update('discount_coupons');
    }
    
    public function deleteCouponById()
    {
    	$this->db->where('id', $this->_coupon_id);
    	$this->db->delete('discount_coupons');
    	
    	$this->db->where('coupon_id', $this->_coupon_id);
    	$this->db->delete('discount_coupons_users');
    	
    	$this->db->where('coupon_id', $this->_coupon_id);
    	$this->db->delete('discount_coupons_usage');

    	return true;
    }

    // --------------------------------------------------------------------------------------------
    // Use coupons functions
    // --------------------------------------------------------------------------------------------
    /**
     * Coupon assigns to user
     *
     */
    public function assignCouponToUser($user_id = null)
    {
    	if (is_null($user_id))
    		$user_id = $this->session->userdata('user_id');

    	$this->db->set('coupon_id', $this->_coupon_id);
    	$this->db->set('user_id', $user_id);
    	if ($this->db->insert('discount_coupons_users')) {
    		return $this->db->insert_id();
    	} else {
    		return false;
    	}
    }
    
    public function getMyCouponsCount()
    {
    	$this->db->select('count(*) AS coupons_count');
    	$this->db->where('user_id', $this->session->userdata('user_id'));
    	$this->db->from('discount_coupons_users');
    	$query = $this->db->get();
    	$row = $query->row_array();
    	
    	return $row['coupons_count'];
    }
    
    public function getUserCoupons($user_id = null)
    {
    	if (is_null($user_id))
    		$user_id = $this->session->userdata('user_id');

    	$this->db->select('dc.*');
    	$this->db->from('discount_coupons AS dc');
    	$this->db->join('discount_coupons_users AS dcu', 'dcu.coupon_id=dc.id', 'left');
    	$this->db->where('dcu.user_id', $user_id);
		$query = $this->db->get();
		
		$coupons = array();
		foreach ($query->result_array() AS $row) {
			$coupon = new couponClass;
			$coupon->setCouponFromArray($row);

			$coupons[] = $coupon;
		}
		return $coupons;
    }
    
    public function getCouponByCode($coupon_code)
    {
    	$this->db->select('*');
    	$this->db->from('discount_coupons AS dc');
    	$this->db->where('code', $coupon_code);
		$query = $this->db->get();

		if ($query->num_rows()) {
			$coupon = new couponClass;
			$coupon->setCouponFromArray($query->row_array());

			return $coupon;
		} else {
			return false;
		}
    }
    
    /**
     * extract coupon, that used for invoice 
     * @param int $invoice_id
     */
    public function getCouponByUsageInvoiceId($invoice_id)
    {
    	$this->db->select('dc.*');
    	$this->db->from('discount_coupons AS dc');
    	$this->db->join('discount_coupons_usage AS dcu', 'dc.id=dcu.coupon_id');
    	$this->db->where('dcu.invoice_id', $invoice_id);
    	$query = $this->db->get();
    	
    	if ($query->num_rows()) {
			$coupon = new couponClass;
			$coupon->setCouponFromArray($query->row_array());

			return $coupon;
		} else {
			return false;
		}
    }
    
    public function isCouponActive($coupon)
    {
    	// Check all usage count
    	$this->db->select('dcu.id');
    	$this->db->from('discount_coupons_usage AS dcu');
    	$this->db->join('invoices AS i', 'i.id=dcu.invoice_id', 'left');
    	$this->db->where('i.status', 2);
    	$query = $this->db->get();
    	$usage_count = $query->num_rows();
    	if ($coupon->usage_count_limit_all > 0 && $coupon->usage_count_limit_all <= $usage_count) {
    		return false;
    	}

    	// Check user usage count
    	$this->db->select('dcu.id');
    	$this->db->from('discount_coupons_usage AS dcu');
    	$this->db->join('invoices AS i', 'i.id=dcu.invoice_id', 'left');
    	$this->db->where('i.owner_id', $this->session->userdata('user_id'));
    	$this->db->where('i.status', 2);
    	$query = $this->db->get();
    	$usage_count = $query->num_rows();
    	if ($coupon->usage_count_limit_user > 0 && $coupon->usage_count_limit_user <= $usage_count) {
    		return false;
    	}

    	// Check effective date and expiration date
    	if ($coupon->effective_date_tmstmp)
    		if ($coupon->effective_date_tmstmp > time())
    			return false;
    	if ($coupon->expiration_date_tmstmp)
    		if ($coupon->expiration_date_tmstmp < time())
    			return false;
    	
    	return true;
    }
    
    /**
     * Checks if user may use this coupon
     *
     * @param string $coupon_code
     * @param int $invoice_id
     * @return bool
     */
    public function checkCoupon($coupon_code, $invoice_id)
    {
    	if (!$coupon = $this->getCouponByCode($coupon_code))
    		return false;
    	
    	if ($coupon->use_if_assigned) {
    		// Check if coupon was assigned with this user
    		$user_coupons = $this->getUserCoupons();
    		$may_use = false;
    		foreach ($user_coupons AS $coupon_obj) {
    			if ($coupon_obj->code == $coupon_code)
    				$may_use = true;
    		}
    		if (!$may_use)
    			return false;
    	}
    	
    	if (!$this->isCouponActive($coupon))
    		return false;

    	$CI = &get_instance();
    	$CI->load->model('payment', 'payment');
    	$invoice = $CI->payment->getInvoiceById($invoice_id);
    	// Check if coupon allowed for this goods from invoice
    	if (!in_array($invoice->goods_category, $coupon->allowed_goods))
    		return false;
    		
    	return $coupon;
    }
    
    /**
     * We have to store in 'discount_coupons_usage' table that we use a coupon with invoice,
     * overwrite this each time
     * 
     * @param int $coupon_id
     * @param int $invoice_id
     */
    public function setCouponUsage($coupon_id, $invoice_id)
    {
    	if ($coupon = $this->getCouponByUsageInvoiceId($invoice_id)) {
    		/*if ($coupon->id == $coupon_id)
    			return true;
    		else {*/
    			$this->db->set('coupon_id', $coupon_id);
    			$this->db->set('usage_date', date("Y-m-d H:i:s"));
    			$this->db->where('invoice_id', $invoice_id);
    			return $this->db->update('discount_coupons_usage');
    		//}
    	} else {
    		$this->db->set('coupon_id', $coupon_id);
    		$this->db->set('invoice_id', $invoice_id);
    		$this->db->set('usage_date', date("Y-m-d H:i:s"));
    		return $this->db->insert('discount_coupons_usage');
    	}
    }
    
	/**
     * need to delete any usage of this invoice, because each invoice may ever use only 1 coupon
     * 
     * @param int $invoice_id
     */
    public function resetCouponUsage($invoice_id)
    {
    	$this->db->where('invoice_id', $invoice_id);
    	return $this->db->delete('discount_coupons_usage');
    }

    public function getCouponsUsage($user_id = null)
    {
    	if (is_null($user_id))
    		$user_id = $this->session->userdata('user_id');

    	$this->db->select('dcu.*');
    	$this->db->select('i.owner_id AS user_id');
    	$this->db->from('discount_coupons_usage AS dcu');
    	$this->db->join('invoices AS i', 'i.id=dcu.invoice_id', 'left');
    	$this->db->where('i.owner_id', $user_id);
    	$query = $this->db->get();

    	$usages = array();
    	if ($query->num_rows()) {
			$coupons_usage = new couponsUsageClass;
			$coupons_usage->setUsageFromArray($query->row_array());
			$usages[] = $coupons_usage;

			return $usages;
		} else {
			return false;
		}
    }
    
    public function selectCouponsUsage($orderby = 'dcu.usage_date', $direction = 'desc', $args = array())
    {
    	// -----------------------------------------------------------------------------------
    	// Number of rows needs
    	//
    	$this->db->select('count(*) as count_rows');
    	$this->db->from('discount_coupons_usage AS dcu');
    	$this->db->join('invoices AS i', 'i.id=dcu.invoice_id', 'left');
    	if (isset($args['search_login'])) {
    		$this->db->join('users AS u', 'u.id=i.owner_id', 'left');
    		$this->db->like('u.login', urldecode(html_entity_decode($args['search_login'])));
    	}
    	if (isset($args['search_coupon'])) {
    		$this->db->like('dcu.coupon_id', urldecode(html_entity_decode($args['search_coupon'])));
    	}
    	if (isset($args['search_usage_date'])) {
    		$this->db->where('TO_DAYS(dcu.usage_date) = ', 'TO_DAYS("' . date("Y-m-d", $args['search_usage_date']) . '")', false);
    	}
    	if (isset($args['search_from_usage_date'])) {
    		$this->db->where('TO_DAYS(dcu.usage_date) >= ', 'TO_DAYS("' . date("Y-m-d", $args['search_from_usage_date']) . '")', false);
    	}
    	if (isset($args['search_to_usage_date'])) {
    		$this->db->where('TO_DAYS(dcu.usage_date) <= ', 'TO_DAYS("' . date("Y-m-d", $args['search_to_usage_date']) . '")', false);
    	}
    	$query = $this->db->get();
    	$row = $query->row_array('count_rows');
    	$this->paginator->setCount($row['count_rows']);

    	// -----------------------------------------------------------------------------------
    	// Select id of packages that will be shown
    	//
    	$this->db->select('dcu.id');
    	$this->db->from('discount_coupons_usage AS dcu');
    	$this->db->join('invoices AS i', 'i.id=dcu.invoice_id', 'left');
    	if ($orderby)
    		$this->db->order_by($orderby, $direction);
    	if (isset($args['search_login'])) {
    		$this->db->join('users AS u', 'u.id=i.owner_id', 'left');
    		$this->db->like('u.login', urldecode(html_entity_decode($args['search_login'])));
    	}
    	if (isset($args['search_coupon'])) {
    		$this->db->like('dcu.coupon_id', urldecode(html_entity_decode($args['search_coupon'])));
    	}
    	if (isset($args['search_usage_date'])) {
    		$this->db->where('TO_DAYS(dcu.usage_date) = ', 'TO_DAYS("' . date("Y-m-d", $args['search_usage_date']) . '")', false);
    	}
    	if (isset($args['search_from_usage_date'])) {
    		$this->db->where('TO_DAYS(dcu.usage_date) >= ', 'TO_DAYS("' . date("Y-m-d", $args['search_from_usage_date']) . '")', false);
    	}
    	if (isset($args['search_to_usage_date'])) {
    		$this->db->where('TO_DAYS(dcu.usage_date) <= ', 'TO_DAYS("' . date("Y-m-d", $args['search_to_usage_date']) . '")', false);
    	}
    	if ($orderby)
	    	$this->db->order_by($orderby, $direction);
    	$query = $this->db->get();
    	$ids = $this->paginator->getResultIds($query->result_array());

    	if (!empty($ids)) {
	    	$this->db->select('dcu.*');
    		$this->db->select('i.owner_id AS user_id');
	    	$this->db->from('discount_coupons_usage AS dcu');
    		$this->db->join('invoices AS i', 'i.id=dcu.invoice_id', 'left');
	    	$_array = array();
	    	foreach ($ids AS $id) {
	    		$_array[] = $id['id'];
	    	}
	    	$this->db->where_in('dcu.id', $_array);
	    	if ($orderby)
	    		$this->db->order_by($orderby, $direction);
	    	$query = $this->db->get();
	    	$usages = array();
	    	foreach ($query->result_array() AS $row) {
				$coupons_usage = new couponsUsageClass;
				$coupons_usage->setUsageFromArray($query->row_array());
				$usages[] = $coupons_usage;
    		}
    		return $usages;
    	} else {
    		return array();
    	}
    }
    // --------------------------------------------------------------------------------------------
    
    public function selectCouponsByUser($user_id)
    {
    	$this->db->select('coupon_id');
    	$this->db->from('discount_coupons_users');
    	$this->db->where('user_id', $user_id);
    	$query = $this->db->get();
    	
    	$coupons = array();
    	foreach ($query->result_array() AS $row) {
    		$coupon = $this->getCouponById($row['coupon_id']);
    		$coupons[] = $coupon;
    	}
    	return $coupons;
    }
}
?>