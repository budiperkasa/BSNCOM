<?php
include_once(MODULES_PATH . 'payment/classes/goods_content.class.php');

class listingsGoods extends goodsContentClass
{
	/**
	 * the real name of goods
	 *
	 * @var string
	 */
	public $name = LANG_LISTINGS_GOODS;

	/**
	 * alias name of goods in the DB
	 *
	 * @var string
	 */
	public $category = 'listings';

	/**
	 * select goods object ID and title from DB
	 *
	 */
	public function setItemAttrs($invoice_id)
	{
		$CI = &get_instance();
		$CI->db->select('l.id as goods_id');
	    $CI->db->select('l.title as goods_title');
	    $CI->db->from('listings as l');
	    $CI->db->join('invoices as i', 'i.goods_id=l.id', 'left');
	    $CI->db->where('i.id', $invoice_id);
	    $query = $CI->db->get();
	    if ($row = $query->row_array()) {
		    $this->goods_id = $row['goods_id'];
		    $this->goods_title = LANG_LISTING_INVOICE_TITLE . ": '" . $row['goods_title'] . "'";
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
		$CI->db->select('active_days');
    	$CI->db->select('active_months');
    	$CI->db->select('active_years');
    	$CI->db->from('levels AS lev');
    	$CI->db->join('listings AS l', 'l.level_id=lev.id', 'left');
    	$CI->db->where('l.id', $this->goods_id);

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
		
		if (!$options['active_days'] && !$options['active_months'] && !$options['active_years'])
			return '<tr><td class="table_left_side">' . LANG_INVOICE_ACTIVE_PERIOD . '</td>
					<td class="table_right_side">' . 
					'<span class="green">' . LANG_ETERNAL . '</span>' .
					'</td></tr>';
		else
			return '<tr><td class="table_left_side">' . LANG_INVOICE_ACTIVE_PERIOD . '</td>
					<td class="table_right_side">' . 
					LANG_INVOICE_ACTIVE_DAYS . ': ' . $options['active_days'] . '&nbsp;' .
	                LANG_INVOICE_ACTIVE_MONTHS . ': ' . $options['active_months'] . '&nbsp;' .
	                LANG_INVOICE_ACTIVE_YEARS . ': ' . $options['active_years'] .
					'</td></tr>';
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
		$listing_id = $this->goods_id;

		$CI = &get_instance();
		$CI->load->model('listings', 'listings');
		$CI->listings->setListingId($listing_id);
		$level_id = $CI->listings->getLevelIdByListingId();
		$listing = new listing($level_id, $listing_id);
		$listing->setListingFromArray($CI->listings->getListingRowById(), $CI->listings->getListingCategories(), $CI->listings->getListingLocations());

		$CI->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array('listings_counts'));

		// Only new listings will run through approval (not prolonged)
		if ($listing->level->preapproved_mode && $listing->was_prolonged_times == 0) {
			// status: unapproved
			$CI->listings->saveListingStatus(4);
		} else {
			// status: active
			$CI->listings->saveListingStatus(1);
		}

		// update listing expiration date
		$CI->db->set('expiration_date', date("Y-m-d H:i:s", (mktime() + (
    									($options['active_days']) +
    									($options['active_months']*30) +
    									($options['active_years']*365)
    									)*86400*$quantity)));
    	$CI->db->where('id', $this->goods_id);
    	return $CI->db->update('listings');
	}
}
?>