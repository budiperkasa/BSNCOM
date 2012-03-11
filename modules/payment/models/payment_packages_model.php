<?php

class payment_packagesModel extends model
{
    public function getPackagesPrice($packages, $users_groups)
    {
    	$this->db->select();
    	$this->db->from('packages_price');
    	$query = $this->db->get();
    	
    	$packages_price = $query->result_array();
    	
    	$prices = array();
    	foreach ($packages AS $package) {
	    	foreach ($users_groups AS $group) {
	    		foreach ($packages_price AS $price) {
	    			if ($price['package_id'] == $package->id && $price['group_id'] == $group->id) {
	    				$prices[$package->id][$group->id] = array('currency' => $price['currency'], 'value' => $price['value']);
	    			}
	    		}
    		}
    	}
    	return $prices;
    }

    public function getPackagesPriceByPackageIdAndGroupId($package_id, $group_id)
    {
    	$this->db->select();
    	$this->db->from('packages_price');
    	$this->db->where('package_id', $package_id);
    	$this->db->where('group_id', $group_id);
    	$query = $this->db->get();

    	return $query->row_array();
    }

    public function savePrice($group_id, $package_id, $form)
    {
		$this->db->set('currency', $form['currency']);
    	$this->db->set('value', $form['value']);
    	$this->db->set('package_id', $package_id);
    	$this->db->set('group_id', $group_id);
		return $this->db->on_duplicate_insert('packages_price');
    }
    
    public function getPriceSettingsFromForm($form)
    {
    	$price_row['currency'] = $form['currency'];
    	$price_row['value'] = $form['value'];
    	
    	return $price_row;
    }
    
    public function selectPackagesPricesByGroupId()
    {
    	$this->db->select();
    	$this->db->from('packages_price');
    	$this->db->where('group_id', $this->session->userdata('user_group_id'));
    	$query = $this->db->get();
    	
    	$prices = array();
    	foreach ($query->result_array() AS $row) {
    		$prices[$row['package_id']] = $row;
    	}

    	return $prices;
    }
    
    public function selectPackagesPricesByDefaultGroup()
    {
    	$this->db->select('pp.*');
    	$this->db->from('packages_price AS pp');
    	$this->db->join('users_groups AS ug', 'ug.id=pp.group_id');
    	$this->db->where('ug.default_group', 1);
    	$query = $this->db->get();
    	$prices = $query->result_array();
    	
    	$result_prices = array();
    	foreach ($prices AS $price_row) {
    		$result_prices[$price_row['package_id']] = $price_row;
    	}
    	return $result_prices;
    }
}
?>