<?php
include_once(MODULES_PATH . 'locations_predefined/classes/location_suggestion.class.php');

class users_locationsController extends controller
{
	/**
	 * build drop box of location level ("for_level") starting from id ("parent_id")
	 *
	 */
	public function build_drop_box()
	{
		$this->load->model('users_locations');
		$drop_box_content = $this->users_locations->buildDropBoxByParentId($this->input->post('parent_id'), $this->input->post('for_level'));
		echo $drop_box_content;
	}
	
	public function autocomplete_request()
	{
		if ($this->input->post('query')) {
			$query = $this->input->post('query');
			$this->load->model('users_locations');
			$suggested_locations = $this->users_locations->getSuggestions($query);

			$suggestions = array();
			foreach ($suggested_locations AS $location) {
				$suggestions[] = new locationSuggestion($location->getChainAsString(), $location->id);
			}
			echo json_encode($suggestions);
		}
	}
	
	/**
	 * build locations path array starting from id "location_id"
	 *
	 */
	public function get_locations_path_by_id()
	{
		//if($this->_input->server('HTTP_X_REQUESTED_WITH') == 'XMLHttpRequest') {
			//$locationsModel = new locationsModel;
			$this->load->model('locations');
			
			$selected_locations_chain = array();
			$selected_locations_chain = $this->locations->getLocationsChainFromId($this->input->post('location_id'));
			$selected_locations_chain = array_reverse($selected_locations_chain);

			/*$location_levels = $this->locations->selectAllLevels();
			
			$locations_string_array = array();
			foreach ($location_levels AS $loc_levels_item) {
				if (isset($locations_array[$loc_levels_item['order_num'] - 1]))
					foreach ($locations_array[$loc_levels_item['order_num'] - 1] AS $loc_item) {
						if ($loc_item['selected']) {
							$locations_string_array[] = "'" . $loc_item['name'] . "'";
						}
					}
			}
			$locations_string = implode(', ', $locations_string_array);*/
			
			echo json_encode($selected_locations_chain);
	}
	
	/*public function ajax_autocomplete_request()
	{
		if ($this->input->post('query')) {
			$query = $this->input->post('query');
			$this->load->model('users_locations');
			$suggestions_ids = $this->users_locations->getSuggestions($query);

			$suggestions = array();
			$data = array();
			if (count($suggestions_ids)) {
				$this->load->model('locations');
				foreach ($suggestions_ids AS $row) {
					$selected_locations_chain = array();
					$selected_locations_chain = $this->locations->getLocationsChainFromId($row['id']);
					$tmp_array = array();
					foreach ($selected_locations_chain AS $location) {
						$tmp_array[] = _utf8_encode($location->name);
					}
					$suggestions[] = implode(', ', $tmp_array);
					$data[] = $row['id'];
				}
			}
			$suggestion_obj = new locationSuggestions($query, $suggestions, $data);
			echo json_encode($suggestion_obj);
		}
	}*/
}
?>