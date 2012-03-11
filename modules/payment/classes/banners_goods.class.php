<?php
include_once(MODULES_PATH . 'payment/classes/goods_content.class.php');

class bannersGoods extends goodsContentClass
{
	/**
	 * the real name of goods
	 *
	 * @var string
	 */
	public $name = LANG_BANNERS_GOODS;

	/**
	 * alias name of goods in the DB
	 *
	 * @var string
	 */
	public $category = 'banners';

	/**
	 * select goods object ID and title from DB
	 *
	 */
	public function setItemAttrs($invoice_id)
	{
		$CI = &get_instance();
		$CI->db->select('b.id as goods_id');
	    $CI->db->select('b.url as goods_title');
	    $CI->db->from('banners as b');
	    $CI->db->join('invoices as i', 'i.goods_id=b.id', 'left');
	    $CI->db->where('i.id', $invoice_id);
	    $query = $CI->db->get();
	    if ($row = $query->row_array()) {
		    $this->goods_id = $row['goods_id'];
		    $this->goods_title = LANG_BANNER_INVOICE_TITLE . ": '" . $row['goods_title'] . "'";
	    }
	}

	/**
	 * select goods options from DB
	 *
	 * @return array
	 */
	public function getGoodsOptions()
	{
		$CI = &get_instance();
		$CI->db->select('bb.active_days');
    	$CI->db->select('bb.active_months');
    	$CI->db->select('bb.active_years');
    	$CI->db->select('bb.clicks_limit');
    	$CI->db->select('bb.limit_mode');
    	$CI->db->from('banners_blocks AS bb');
    	$CI->db->join('banners AS b', 'b.block_id=bb.id', 'left');
    	$CI->db->where('b.id', $this->goods_id);

    	$query = $CI->db->get();
    	return $query->row_array();
	}

	/**
	 * render goods options on the invoice details page
	 *
	 * @return html
	 */
	public function showOptions()
	{
		$options = $this->getGoodsOptions();

		$html = '<tr><td class="table_left_side">' . LANG_INVOICE_ACTIVE_PERIOD . '</td>';
		$html .= '<td class="table_right_side">';
		
		if ($options['limit_mode'] == 'active_period' || $options['limit_mode'] == 'both') {
				$html .= LANG_INVOICE_ACTIVE_DAYS . ': ' . $options['active_days'] . '&nbsp;' .
                LANG_INVOICE_ACTIVE_MONTHS . ': ' . $options['active_months'] . '&nbsp;' .
                LANG_INVOICE_ACTIVE_YEARS . ': ' . $options['active_years'] . '<br />';
		}
		if ($options['limit_mode'] == 'clicks' || $options['limit_mode'] == 'both') {
			$html .= LANG_BANNERS_BLOCK_CLICKS_LIMIT_TH . ': ' . $options['clicks_limit'] . '&nbsp;';
		}
		$html .= '</td></tr>';
		return $html;
	}
	
	/**
	 * complete transaction and invoice of the goods item
	 *
	 * @param int $quantity
	 * @return bool
	 */
	public function completePayment($quantity)
	{
		$options = $this->getGoodsOptions();
		$banner_id = $this->goods_id;

		$CI = &get_instance();
		$CI->load->model('banners', 'banners');
		$CI->banners->setBannerId($banner_id);
		$banner = $CI->banners->getBannerById();

		// update banner status - active
		$CI->banners->saveBannerStatus(1);

		// update banner expiration date and clicks expiration count
		$CI->db->set('expiration_date', date("Y-m-d H:i:s", (mktime() + (
    									($options['active_days']) +
    									($options['active_months']*30) +
    									($options['active_years']*365)
    									)*86400*$quantity)));
    	$CI->db->set('clicks_expiration_count', 'clicks_expiration_count+' . ($options['clicks_limit']*$quantity), false);
    	$CI->db->where('id', $this->goods_id);
    	return $CI->db->update('banners');
	}
}
?>