<?php
class content_fields_groupsModel extends model
{
	private $_group_id;

	public function setFieldsGroupId($group_id)
	{
		$this->_group_id = $group_id;
	}
	
	/**
	 * select all groups from DB
	 *
	 * @return array
	 */
	public function selectAllGroups()
	{
		$this->db->select('cfg.*');
		$this->db->select('count(cf.id) AS f_count');
		$this->db->from('content_fields_groups AS cfg');
		$this->db->join('content_fields_to_groups AS cftg', 'cftg.group_id=cfg.id', 'left');
		$this->db->join('content_fields AS cf', 'cftg.field_id=cf.id', 'left');
		$this->db->group_by('cfg.id');
		$this->db->order_by('cfg.id', 'asc');
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
		$this->db->from('content_fields_groups');
		$this->db->where('id', $this->_group_id);
		$query = $this->db->get();
		
		return $query->row_array();

		/*$field = new fieldsGroup;
		$field->getFieldsGroupFromArray($query->row_array());
		return $field;*/
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
		$this->db->join('content_fields_to_groups AS cftg', 'cf.id=cftg.field_id');
		$this->db->where('cftg.group_id', $this->_group_id);
		$this->db->order_by('cftg.order_num');
		$query = $this->db->get();
		
		return $query->result_array();
	}
	
	/**
	 * select fields those are free from this group
	 *
	 * @return array
	 */
	public function selectFieldsNotInGroup($fields_of_group)
	{
		$ids = array();
		foreach ($fields_of_group AS $field) {
			$ids[] = $field['id'];
		}

		$this->db->distinct();
		$this->db->from('content_fields AS cf');
		if (!empty($ids)) {
			$this->db->where_not_in('id', $ids);
		}

		$query = $this->db->get();
		
		return $query->result_array();
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
			$this->db->set('group_id', $this->_group_id);
			$this->db->set('order_num', $order_num++);
			$this->db->on_duplicate_insert('content_fields_to_groups');
		}

		// Delete unnecessary field rows
		if (count($ids)) {
			$fields_of_group = $this->getFieldsOfGroupById();
			foreach ($fields_of_group AS $field_in_group) {
				if (!in_array($field_in_group['id'], $ids)) {
					$this->db->delete('content_fields_to_groups', array('field_id' => $field_in_group['id'], 'group_id' => $this->_group_id));
				}
			}
		} else {
			// There are no one field in list - clear all
			$this->db->delete('content_fields_to_groups', array('group_id' => $this->_group_id));
		}
	}

	/**
	 * Get fields of the group
	 *
	 * @param seo_string $custom_group
	 * @return array
	 */
	public function getFieldsByGroupName($custom_group, $custom_id, $page = null)
	{
		$this->db->select('cfg.custom_name AS custom_name');
		$this->db->select('cfg.custom_id AS custom_id');
		$this->db->select('cfg.name AS group_name');
		$this->db->select('cftg.id AS cftg_id');
		$this->db->select('cf.*');
		$this->db->from('content_fields_to_groups AS cftg');
		$this->db->join('content_fields AS cf', 'cf.id=cftg.field_id');
		$this->db->join('content_fields_groups AS cfg', 'cfg.id=cftg.group_id');
		//$this->db->where('cfg.custom_name', $custom_group);
		//$this->db->where('cfg.custom_id', $custom_id);
		if ($page) {
			$this->db->where('cf.v_'.$page.'_page', 1);
		}
		$this->db->order_by('cftg.order_num');
		$query = $this->db->get();
		$fields_result = $query->result_array();

		$result = array();
		foreach ($fields_result As $field_row) {
			if ($field_row['custom_name'] == $custom_group && $field_row['custom_id'] == $custom_id) {
				$result[] = $field_row;
			}
		}
		return $result;
	}
}
?>