<?php

class payment_listingsModel extends model
{
    public function getListingsPrice($types, $users_groups)
    {
    	$this->db->select();
    	$this->db->from('listings_price');
    	$query = $this->db->get();
    	
    	$listings_price = $query->result_array();
    	
    	$prices = array();
    	foreach ($types AS $type) {
	    	foreach ($type->levels AS $level) {
	    		foreach ($users_groups AS $group) {
	    			foreach ($listings_price AS $price) {
	    				if ($price['level_id'] == $level->id && $price['group_id'] == $group->id) {
	    					$prices[$level->id][$group->id] = array('currency' => $price['currency'], 'value' => $price['value']);
	    				}
	    			}
	    		}
	    	}
    	}
    	return $prices;
    }

    public function getListingsPriceByLevelIdAndGroupId($level_id, $group_id)
    {
    	$this->db->select();
    	$this->db->from('listings_price');
    	$this->db->where('level_id', $level_id);
    	$this->db->where('group_id', $group_id);
    	$query = $this->db->get();

    	return $query->row_array();
    }

    public function savePrice($group_id, $level_id, $form)
    {
		$this->db->set('currency', $form['currency']);
    	$this->db->set('value', $form['value']);
    	$this->db->set('level_id', $level_id);
    	$this->db->set('group_id', $group_id);
		return $this->db->on_duplicate_insert('listings_price');
    }
    
    public function getPriceSettingsFromForm($form)
    {
    	$price_row['currency'] = $form['currency'];
    	$price_row['value'] = $form['value'];
    	
    	return $price_row;
    }
    
    public function selectListingsPricesByGroupId()
    {
    	$this->db->select();
    	$this->db->from('listings_price');
    	$this->db->where('group_id', $this->session->userdata('user_group_id'));
    	$query = $this->db->get();
    	
    	$prices = array();
    	foreach ($query->result_array() AS $row) {
    		$prices[$row['level_id']] = $row;
    	}

    	return $prices;
    }
    
    public function selectListingsPricesByDefaultGroup()
    {
    	$this->db->select('lp.*');
    	$this->db->from('listings_price AS lp');
    	$this->db->join('users_groups AS ug', 'ug.id=lp.group_id');
    	$this->db->where('ug.default_group', 1);
    	$query = $this->db->get();
    	$prices = $query->result_array();
    	
    	$result_prices = array();
    	foreach ($prices AS $price_row) {
    		$result_prices[$price_row['level_id']] = $price_row;
    	}
    	return $result_prices;
    }
    
    public function calculatePriceDifferences($listing, $levels, $listings_prices, &$listings_prices_working)
    {
    	// --------------------------------------------------------------------------------------------
		// Calculate 'difference for payment'
		// --------------------------------------------------------------------------------------------
		$time_difference = strtotime($listing->expiration_date) - mktime();
		if (!$listing->level->eternal_active_period && $time_difference > 0) {
			// Listing hasn't expired yet and it hasn't eternal active period
			// Get difference between current time and time to expire, then devide it to current level active period
			$current_active_period = ($listing->level->active_days +
								($listing->level->active_months*30) +
								($listing->level->active_years*365))*86400;
			$periods_difference = floor($time_difference/$current_active_period);
		} elseif ($listing->level->eternal_active_period) {
			// Listing with eternal active period
			$periods_difference = 1;
		} else {
			// Listing already expired
			$periods_difference = 0;
		}

		// Get price and currency of current level
		foreach ($listings_prices AS $price_row) {
			if ($price_row['level_id'] == $listing->level->id) {
				$current_level_price = $price_row['value'];
				$current_level_price_currency = $price_row['currency'];
			}
		}

		// If current level has no price records
		if (!isset($current_level_price))
			$current_level_price = 0;

		foreach ($levels AS $level_key=>$level) {
			foreach ($listings_prices AS $price_key=>$price_row) {
				if ($level->id != $listing->level->id) {
					if ($price_row['level_id'] == $level->id) {
						// Only if currency of the current level is the same as upgradable level
						if (isset($current_level_price_currency) && $price_row['currency'] == $current_level_price_currency) {
							// Calculate how much to pay according to how long it will stay active and how much it was already payed
							$listings_prices_working[$price_key]['value'] = $price_row['value']-($periods_difference*$current_level_price);
						} elseif (!isset($current_level_price_currency)) {
							$listings_prices_working[$price_key]['value'] = $price_row['value'];
						} else {
							// If level has another currency - show error and block this level
							$listings_prices_working[$price_key]['value'] = 'currency mismatch';
						}
					}
				}
				$listings_prices_working[$price_key]['currency'] = $price_row['currency'];
			}
		}
		// --------------------------------------------------------------------------------------------
    }
    
    public function createListingsUpgradeRecord($listing_id, $old_level_id, $new_level_id)
    {
    	$this->db->set('listing_id', $listing_id);
    	$this->db->set('old_level_id', $old_level_id);
    	$this->db->set('new_level_id', $new_level_id);
		$this->db->insert('listings_payment_upgrades');
		
		return $this->db->insert_id();
    }
}
?>