<?php

class predefinedLocation
{
	public $id;
	public $parent_id;
	public $name;
	public $seo_name;
	public $tree_path;
	public $use_as_label;
	public $geocoded_name;
	public $selected;
	public $counter = 0;
	public $children = array();
	
	public function __construct()
	{
		$this->id = 'new';
		$this->parent_id = 'new';
		$this->name = '';
		$this->seo_name = '';
		$this->tree_path = '';
		$this->use_as_label = false;
		$this->geocoded_name = '';
		$this->selected = false;
	}
	
	public function setLocationFromArray($array)
	{
		if (isset($array['id']))
			$this->id = $array['id'];
		if (isset($array['parent_id']))
			$this->parent_id = $array['parent_id'];
		$this->name = $array['name'];
		$this->seo_name = $array['seo_name'];
		if (isset($array['tree_path']))
			$this->tree_path = $array['tree_path'];
		if (isset($array['use_as_label']))
			$this->use_as_label = $array['use_as_label'];
		if (isset($array['geocoded_name']))
			$this->geocoded_name = $array['geocoded_name'];
	}
	
	public function setSelected()
	{
		$this->selected = true;
	}
	
	public function getChainAsArray()
	{
		$CI = &get_instance();
    	$CI->load->model('locations', 'locations_predefined');

		$chain_ids = explode('-', $this->tree_path);
		array_shift($chain_ids);
		
		$chain = array();
		foreach ($chain_ids AS $location_id) {
			if ($location_id) {
				$location = $CI->locations->getLocationById($location_id);
				$chain[] = $location;
			}
		}
		return $chain;
	}
	
	public function getChainAsString($glue = ', ', $reverse = true)
	{
		$chain = $this->getChainAsArray();
		$locations = array();
		foreach ($chain AS $location)
			$locations[] = $location->name;
		if ($reverse)
			return implode($glue, array_reverse($locations));
		else
			return implode($glue, $locations);
	}
	
	/**
	 * retrieve all children of  this location using provided $locations_array
	 * @param array $locations_array
	 * @param int/string $max_depth
	 */
	public function buildChildren($max_depth = 'max', $is_only_labeled = false)
	{
		if (!$this->children) {
			$CI = &get_instance();
			$CI->load->model('locations', 'locations_predefined');
			$this_children = $CI->locations->getDirectChildrenOfLocation($this->id, $is_only_labeled);
			
			foreach ($this_children AS $child) {
				if ($max_depth == 'max' || count(array_filter(explode('-', $child->tree_path))) <= $max_depth)
					$this->children[] = $child;
			}
		}
		return $this->children;
	}
	
	public function countListings()
	{
		if (!$this->counter) {
			$CI = &get_instance();
			$CI->load->model('locations', 'locations_predefined');
			// Get absolutely ALL children of this location
	    	$locations_children_objs = $CI->locations->getAllChildrenOfLocation($this);
	    	$locations_children_objs[] = $this;
	    	$search_locations_ids = array();
	    	foreach ($locations_children_objs AS $location_obj) {
	    		$search_locations_ids[] = $location_obj->id;
	    	}

			$CI->db->distinct();
			$CI->db->select('lil.listing_id');
			$CI->db->from('listings_in_locations AS lil');
			$CI->db->join('listings AS l', 'l.id=lil.listing_id', 'left');
			$CI->db->join('locations AS loc', 'loc.id=lil.predefined_location_id', 'left');
			$CI->db->join('levels as lev', 'lev.id=l.level_id', 'left');
    		$CI->db->join('types as t', 't.id=lev.type_id', 'left');
			$CI->db->join('users AS u', 'u.id=l.owner_id', 'left');
			$where_sql = '';
			if ($this->geocoded_name)
	    		$where_sql = '(lil.geocoded_name LIKE "%' . $this->geocoded_name . '") OR ';
	    	$CI->db->where('(' . $where_sql . 'lil.predefined_location_id IN (' . implode(',', $search_locations_ids) . '))', null, false);

			$CI->db->where('l.status', 1);
			$CI->db->where('u.status', 2);
			$CI->db->where('t.locations_enabled', 1);
			$CI->db->where('lev.locations_number > ', 0);
			if ($current_category = registry::get('current_category')) {
				$CI->db->join('listings_in_categories AS lic', 'l.id=lic.listing_id', 'left');
				$CI->db->join('categories AS c', 'lic.category_id=c.id', 'left');
				$CI->db->where('(c.tree_path="' . $current_category->tree_path . '" OR c.tree_path LIKE "' . $current_category->tree_path . '-%")', null, false);
			}
			if ($current_type = registry::get('current_type')) {
				$CI->db->where('t.id', $current_type->id);
			}
			$array = $CI->db->get()->result_array();
			foreach ($array AS $row) {
				$this->counter ++;
			}
		}
		return $this->counter;
	}
	
	public function getUrl()
	{
		return 'location/' . $this->seo_name . '/' . getBaseUrl();
	}
	
	public function render()
	{
		$func_args = func_get_args();
		$args['template'] = $func_args[0];
		$args['is_counter'] = $func_args[1];
		$args['max_depth'] = $func_args[2];
		$args['selected_locations'] = $func_args[3];
		$args['highlight_element'] = $func_args[4];
		$args['is_children_label'] = $func_args[5];
		$args['is_only_labeled'] = $func_args[6];

		if (!$args['is_only_labeled'] || ($args['is_only_labeled'] && $this->use_as_label)) {
			$tokens = array(
				'ID',
				'SEONAME',
				'URL',
				'NAME',
				'NAME_WITHOUT_QUOTES',
				'CHILDREN',
				'HIGHLIGHT',
				'COUNTER',
				'OBRACKET',
				'CBRACKET',
				'ISCHILDRENLABEL',
			);
	
			$_template = $args['template'];
			foreach ($tokens AS $token) {
				$function_name = '_get' . ucfirst(strtolower($token));
			   	$_template = str_ireplace('%'.$token.'%', $this->$function_name($args), $_template);
			}
			return $_template;
		} else 
			return '';
	}
	
	public function _getObracket()
	{
		return '{';
	}
	public function _getCbracket()
	{
		return '}';
	}
	
	public function _getId()
	{
		return $this->id;
	}
	
	public function _getSeoname()
	{
		return $this->seo_name;
	}
	
	public function _getUrl()
	{
		return site_url($this->getUrl());
	}

	public function _getName()
	{
		return $this->name;
	}
	
	public function _getName_without_quotes()
	{
		//return addslashes($this->name);
		return quotes_to_entities($this->name);
	}
	
	public function _getChildren($args)
	{
		$this->buildChildren($args['max_depth'], $args['is_only_labeled']);
		$children_output = '';
		if ($this->children) {
			foreach ($this->children AS $child) {
				$children_output .= call_user_func_array(array($child, 'render'), $args);
			}
		}
		return $children_output;
	}
	
	public function _getHighlight($args)
	{
		$selected_locations = $args['selected_locations'];
		if (is_array($selected_locations)) {
			foreach ($selected_locations AS $location) {
				if (is_object($location))
					$selected_locations[] = $location->id;
			}
		}

		$highlight_element = $args['highlight_element'];
		if (is_array($selected_locations)) {
			if ((!in_array($this->id, $selected_locations)) && (!in_array($this->seo_name, $selected_locations)) && (!in_array($this, $selected_locations)))
				$highlight_element = '';
		} elseif ($selected_locations != $this->id && $selected_locations != $this->seo_name && $selected_locations != $this)
				$highlight_element = '';
		return $highlight_element;
	}
	
	public function _getCounter($args)
	{
		$is_counter = $args['is_counter'];
		if ($is_counter) {
			return '&nbsp;(' . $this->countListings() . ')';
		} else
			return '';
	}
	
	public function _getIschildrenlabel($args)
	{
		$this->buildChildren($args['max_depth'], $args['is_only_labeled']);
		$is_children_label = $args['is_children_label'];
		if ($this->children) {
			return $is_children_label;
		}
	}
}
?>