<?php

class calendarModel extends Model 
{
	public function getSettings()
	{
		$this->db->select();
		$this->db->from('calendar_settings');
		$this->db->where('id', 1);
		$query = $this->db->get();
		$results = $query->result_array();
		return $results[0];
	}

	public function getSearchFieldsByTypeId($connected_type_id)
	{
		$this->db->select('sftg.field_id');
		$this->db->select('cf.name');
		$this->db->from('search_fields_to_groups AS sftg');
		$this->db->join('search_fields_groups AS sfg', 'sftg.search_group_id=sfg.id', 'left');
		$this->db->join('content_fields AS cf', 'cf.id=sftg.field_id', 'left');
		$this->db->where('sfg.custom_id', $connected_type_id);
		$this->db->where('(cf.type="datetime" OR cf.type="datetimerange")');
		$this->db->orderby('sftg.order_num');
		$query = $this->db->get();

		return  $query->result_array();
	}
	
	public function saveSettings($form_result)
	{
		$this->db->set('name', $form_result['name']);
		$this->db->set('connected_type_id', $form_result['connected_type_id']);
		$this->db->set('connected_field', $form_result['connected_field']);
		$this->db->set('visibility_on_index', $form_result['visibility_on_index']);
		$this->db->set('visibility_for_all_types', $form_result['visibility_for_all_types']);
		$this->db->where('id', 1);
		return $this->db->update('calendar_settings');
	}
}
?>