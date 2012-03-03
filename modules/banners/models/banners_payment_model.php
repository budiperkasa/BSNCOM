<?php

class banners_paymentModel extends Model
{
	public function selectBannersPricesByGroupId()
    {
    	$this->db->select();
    	$this->db->from('banners_price');
    	$this->db->where('group_id', $this->session->userdata('user_group_id'));
    	$query = $this->db->get();
    	
    	$prices = array();
    	foreach ($query->result_array() AS $row) {
    		$prices[$row['block_id']] = $row;
    	}

    	return $prices;
    }
    
    public function selectBannersPricesByDefaultGroup()
    {
    	$this->db->select('bp.*');
    	$this->db->from('banners_price AS bp');
    	$this->db->join('users_groups AS ug', 'ug.id=bp.group_id');
    	$this->db->where('ug.default_group', 1);
    	$query = $this->db->get();
    	$prices = $query->result_array();
    	
    	$result_prices = array();
    	foreach ($prices AS $price_row) {
    		$result_prices[$price_row['block_id']] = $price_row;
    	}
    	return $result_prices;
    }
    
    public function getBannersPrice($banners_blocks, $users_groups)
    {
    	$this->db->select();
    	$this->db->from('banners_price');
    	$query = $this->db->get();
    	
    	$banners_price = $query->result_array();
    	
    	$prices = array();
    	foreach ($banners_blocks AS $block) {
	    	foreach ($users_groups AS $group) {
	    		foreach ($banners_price AS $price) {
	    			if ($price['block_id'] == $block->id && $price['group_id'] == $group->id) {
	    				$prices[$block->id][$group->id] = array('currency' => $price['currency'], 'value' => $price['value']);
	    			}
	    		}
	    	}
    	}
    	return $prices;
    }
    
    public function getBannersPriceByBlockIdAndGroupId($block_id, $group_id)
    {
    	$this->db->select();
    	$this->db->from('banners_price');
    	$this->db->where('block_id', $block_id);
    	$this->db->where('group_id', $group_id);
    	$query = $this->db->get();

    	return $query->row_array();
    }
    
    public function getPriceSettingsFromForm($form)
    {
    	$price_row['currency'] = $form['currency'];
    	$price_row['value'] = $form['value'];
    	
    	return $price_row;
    }
    
    public function savePrice($group_id, $block_id, $form)
    {
		$this->db->set('currency', $form['currency']);
    	$this->db->set('value', $form['value']);
    	$this->db->set('block_id', $block_id);
    	$this->db->set('group_id', $group_id);
		return $this->db->on_duplicate_insert('banners_price');
    }
}
?>