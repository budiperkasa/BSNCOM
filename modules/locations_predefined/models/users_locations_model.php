<?php
include_once(MODULES_PATH . 'locations_predefined/classes/predefined_location.class.php');

class users_locationsModel extends model
{
    public function buildDropBoxByParentId($parent_id, $for_level)
    {
        $this->db->select('id');
        $this->db->select('name');
        $this->db->from('locations');
        $this->db->where('parent_id', $parent_id);
        $this->db->order_by('name');
        $result = $this->db->get()->result_array();

        $drop_box_content = '<option value="" selected>- - - ' . LANG_LOCATION_SELECT . ' ' . $for_level . ' - - -</option>';
        foreach ($result as $v) {
            $drop_box_content .= '<option value="' . $v['id'] . '">' . $v['name'] . '</option>';
        }

        return $drop_box_content;
    }
    
    public function getSuggestions($query)
    {
    	$this->db->select();
    	$this->db->from('locations');
    	$this->db->like('name', $query, 'after');
    	$result = $this->db->get()->result_array();

    	$locations = array();
    	foreach ($result AS $row) {
	    	$location = new predefinedLocation;
	    	$location->setLocationFromArray($row);
	    	$locations[] = $location;
    	}
    	return $locations;
    }
}
?>