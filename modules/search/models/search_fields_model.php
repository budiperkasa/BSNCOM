<?php
class search_fieldsModel extends model
{
	private $_group_id;

	public function setGroupId($id)
	{
		$this->_group_id = $id;
	}
	
	/**
	 * Get fields of the group
	 *
	 * @param $custom_group
	 * @return array
	 */
	public function getFieldsByGroupName($custom_group, $custom_id, $mode)
	{
		$this->db->select('sfg.name AS group_name');
		$this->db->select('sftg.id AS sftg_id');
		$this->db->select('cf.*');
		$this->db->from('search_fields_to_groups AS sftg');
		$this->db->join('content_fields AS cf', 'cf.id=sftg.field_id');
		$this->db->join('search_fields_groups AS sfg', 'sfg.id=sftg.search_group_id');
		$this->db->where('sfg.custom_name', $custom_group);
		$this->db->where('sfg.custom_id', $custom_id);
		$this->db->where('sfg.mode', $mode);
		$this->db->orderBy('sftg.order_num');
		$query = $this->db->get();

		return $query->result_array();
	}

	public function selectFieldsNotInGroup($fields_of_group)
	{
		$ids = array();
		foreach ($fields_of_group AS $field) {
			$ids[] = $field['id'];
		}
		
		// Select levels which contained in search fields group of current type
		// for global search group - select all levels

		if ($this->_group_id > 2) {
			// this is local levels search group
			$this->db->select('l.id', false);
			$this->db->from('levels AS l');
			$this->db->join('search_fields_groups AS sfg', 'l.type_id=sfg.custom_id', 'left');
			$this->db->where('sfg.id', $this->_group_id);
			$level_ids_sql = $this->db->_compile_select();
			$this->db->_reset_select();
			$level_ids_sql = str_replace("\n", " ", $level_ids_sql);
		} else {
			// this is global search group
			$this->db->distinct();
			$this->db->select('id');
			$this->db->from('levels');
			$level_ids_sql = $this->db->_compile_select();
			$this->db->_reset_select();
			$level_ids_sql = str_replace("\n"," ",$level_ids_sql);
		}

		// Select content fields connected with selected levels
		$this->db->distinct();
		$this->db->select('cf.*');
		$this->db->from('content_fields AS cf');
		$this->db->join('content_fields_to_groups AS cftg', 'cftg.field_id=cf.id', 'left');
		$this->db->join('content_fields_groups AS cfg', 'cftg.group_id=cfg.id', 'left');
		$this->db->where('cfg.custom_name', LISTINGS_LEVEL_GROUP_CUSTOM_NAME);
		$this->db->where_in('cfg.custom_id', $level_ids_sql, false);
		if (!empty($ids)) {
			$this->db->where_not_in('cf.id', $ids);
		}
		$query = $this->db->get();
		
		return $query->result_array();
	}
	
	/**
	 * select fields of the group by ID
	 *
	 * @return array
	 */
	public function getFieldsOfGroupById()
	{
		$this->db->select('cf.*');
		$this->db->from('content_fields AS cf');
		$this->db->join('search_fields_to_groups AS sftg', 'cf.id=sftg.field_id');
		$this->db->where('sftg.search_group_id', $this->_group_id);
		$this->db->order_by('sftg.order_num');
		$query = $this->db->get();
		
		return $query->result_array();
	}
	
	/**
	 * select fields group row by ID
	 *
	 * @return array
	 */
	public function getFieldsGroupById()
	{
		$this->db->select();
		$this->db->from('search_fields_groups');
		$this->db->where('id', $this->_group_id);
		$query = $this->db->get();
		
		return $query->row_array();
	}
	
	/**
	 * extracts fields ids from serialized list and saves it in DB
	 *
	 * @param string $serialized_list
	 */
	public function saveFieldsListOfGroup($serialized_list)
	{
		$ids = array();
		$fields = explode('&', $serialized_list);
		if ($serialized_list != '') {
			foreach ($fields AS $field) {
				$x = explode('=', $field);
				$ids[] = $x[1];
			}
			$ids = array_unique($ids);
		}
		
		$order_num = 1;
		foreach ($ids AS $id) {
			$this->db->set('field_id', $id);
			$this->db->set('search_group_id', $this->_group_id);
			$this->db->set('order_num', $order_num++);
			$this->db->on_duplicate_insert('search_fields_to_groups');
		}

		// Delete unnecessary field rows
		if (count($ids)) {
			$fields_of_group = $this->getFieldsOfGroupById();
			foreach ($fields_of_group AS $field_in_group) {
				if (!in_array($field_in_group['id'], $ids)) {
					$this->db->delete('search_fields_to_groups', array('field_id' => $field_in_group['id'], 'search_group_id' => $this->_group_id));
				}
			}
		} else {
			// There are no one field in list - clear all
			$this->db->delete('search_fields_to_groups', array('search_group_id' => $this->_group_id));
		}
	}
	
	/**
	 * select all search groups except global search group from DB
	 *
	 * @return array
	 */
	public function selectLocalSearchGroups()
	{
		$this->db->select();
		$this->db->where('custom_name !=', GLOBAL_SEARCH_GROUP_CUSTOM_NAME);
		$this->db->order_by('id', 'asc');
		$query = $this->db->get('search_fields_groups');

		return $query->result_array();
	}
}
?>