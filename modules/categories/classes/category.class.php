<?php

class category
{
	public $id;
	public $name;
	public $seo_name;
	public $selected_icons_serialized;
	public $meta_title;
	public $meta_description;
	public $type_id;
	public $parent_category_id;
	public $tree_path;
	public $counter = 0;
	public $children = array();
	
	public function __construct()
	{
		$this->id = 'new';
		$this->name = '';
		$this->seo_name = '';
		$this->selected_icons_serialized = '';
		$this->meta_title = '';
		$this->meta_description = '';
		$this->parent_category_id = 0;
	}
	
	public function setCategoryFromArray($array)
	{
		if (isset($array['id']))
			$this->id = $array['id'];
		$this->name = $array['name'];
		$this->seo_name = $array['seo_name'];
		$this->selected_icons_serialized = $array['selected_icons_serialized'];
		$this->meta_title = $array['meta_title'];
		$this->meta_description = $array['meta_description'];
		if (isset($array['type_id']))
			$this->type_id = $array['type_id'];
		if (isset($array['parent_category_id']))
			$this->parent_category_id = $array['parent_category_id'];
		if (isset($array['tree_path']))
			$this->tree_path = $array['tree_path'];
	}
	
	public function getChainAsArray()
	{
		$CI = &get_instance();
    	$CI->load->model('categories', 'categories');

		$chain_ids = explode('-', $this->tree_path);
		array_shift($chain_ids);
		
		$chain = array();
		foreach ($chain_ids AS $category_id) {
			if ($category_id) {
				$category = $CI->categories->getCategoryById($category_id);
				$chain[] = $category;
			}
		}
		return $chain;
	}
	
	public function getChainAsString($glue = ' » ')
	{
		$chain = array();
		$array = $this->getChainAsArray();
		foreach ($array AS $category) {
			$chain[] = $category->name;
		}
		return implode($glue, $chain);
	}
	
	public function getChainAsUrl()
	{
		$chain = array();
		$array = $this->getChainAsArray();
		foreach ($array AS $category) {
			$chain[] = $category->seo_name;
		}

		// If this is local category
		if ($this->type_id) {
			$CI = &get_instance();
			$CI->load->model('types', 'types_levels');
			$type = $CI->types->getTypeById($this->type_id);
			
			$url = 'type/' . $type->seo_name . '/';
		} else {
			$url = '';
		}

		$url .= 'categories/' . implode('/', $chain) . '/';
		return $url;
	}
	
	public function getChainAsLinks($glue = ' » ')
	{
		$chain = array();
		$array = $this->getChainAsArray();
		foreach ($array AS $category) {
			$chain[] = '<a href="' . site_url($category->getUrl()) . '" >' . $category->name . '</a>';
		}
		return implode($glue, $chain);
	}
	
	public function getUrl()
	{
		$CI = &get_instance();
		if ($current_location = $CI->session->userdata('current_location_seo_name')) {
			return 'location/' . $current_location . '/' . $this->getChainAsUrl();
		} else {
			return $this->getChainAsUrl();
		}
	}
	
	public function buildChildren($max_depth = 'max')
	{
		if (!$this->children) {
			$CI = &get_instance();
			$CI->load->model('categories', 'categories');
			$this_children = $CI->categories->getDirectChildrenOfCategory($this->id);

			foreach ($this_children AS $child) {
				if ($max_depth == 'max' || count(array_filter(explode('-', $child->tree_path))) <= $max_depth)
					$this->children[] = $child;
			}
		}
		return $this->children;
	}
	
	/**
	 * Counts all listings inside this category and all its children
	 *
	 * @return int
	 */
	public function countListings()
	{
		if (!$this->counter) {
			$CI = &get_instance();
			
			// Search all childs of current location + this location
			// and show counts only for them
			if ($current_location = registry::get('current_location')) {
				$CI->load->model('locations', 'locations_predefined');
	    		$locations_children_objs = $CI->locations->getAllChildrenOfLocation($current_location);
	    		$locations_children_objs[] = $current_location;
	    		$search_locations_ids = array();
	    		foreach ($locations_children_objs AS $location_obj) {
	    			$search_locations_ids[] = $location_obj->id;
	    		}
			}
			
			$CI->db->distinct();
			$CI->db->select('lic.listing_id');
			$CI->db->from('listings_in_categories AS lic');
			$CI->db->join('listings AS l', 'l.id=lic.listing_id', 'left');
			$CI->db->join('categories AS c', 'c.id=lic.category_id', 'left');
			$CI->db->join('levels as lev', 'lev.id=l.level_id', 'left');
    		$CI->db->join('types as t', 't.id=lev.type_id', 'left');
			$CI->db->join('users AS u', 'u.id=l.owner_id', 'left');
			if ($current_location) {
				$CI->db->join('listings_in_locations AS lil', 'lil.listing_id=l.id', 'left');
	    		$where_sql = '';
	    		if ($current_location->geocoded_name)
	    			//$where_sql = '(lil.geocoded_name LIKE "%' . $current_location->geocoded_name . '" AND t.locations_enabled=1 AND lev.locations_number>0) OR ';
	    			$where_sql = '(lil.geocoded_name LIKE "%' . $current_location->geocoded_name . '") OR ';
	    		$CI->db->where('(' . $where_sql . 'lil.predefined_location_id IN (' . implode(',', $search_locations_ids) . '))', null, false);
	    		$CI->db->where('t.locations_enabled', 1);
				$CI->db->where('lev.locations_number > ', 0);
			}
			$CI->db->where('(c.tree_path LIKE "%-'.$this->id.'-%" OR c.tree_path LIKE "%-'.$this->id.'")', null, false);
			$CI->db->where('l.status', 1);
			$CI->db->where('u.status', 2);
			$array = $CI->db->get()->result_array();
			foreach ($array AS $row) {
				$this->counter ++;
			}
		}
		return $this->counter;
	}
	
	public function render($template, $is_counter = false, $max_depth = 'max', $selected_categories = null, $highlight_element = '', $is_children_label = '')
	{
		$func_args = func_get_args();
		$args['template'] = $func_args[0];
		$args['is_counter'] = $func_args[1];
		$args['max_depth'] = $func_args[2];
		$args['selected_categories'] = $func_args[3];
		$args['highlight_element'] = $func_args[4];
		$args['is_children_label'] = $func_args[5];

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

		$_template = $template;
		foreach ($tokens AS $token) {
			$function_name = '_get' . ucfirst(strtolower($token));
		   	$_template = str_ireplace('%'.$token.'%', $this->$function_name($args), $_template);
		}

		return $_template;
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
		$this->buildChildren($args['max_depth']);
		$children_output = '';
		if ($this->children) {
			foreach ($this->children AS $child) {
				//$children_output .= $child->render($template, $is_counter, $max_depth, $selected_categories, $highlight_element);
				$children_output .= call_user_func_array(array($child, 'render'), $args);
			}
		}
		return $children_output;
	}
	
	public function _getHighlight($args)
	{
		$selected_categories = $args['selected_categories'];
		if (is_array($selected_categories)) {
			foreach ($selected_categories AS $category) {
				if (is_object($category))
					$selected_categories[] = $category->id;
			}
		}

		$highlight_element = $args['highlight_element'];
		if (is_array($selected_categories)) {
			if ((!in_array($this->id, $selected_categories)) && (!in_array($this->seo_name, $selected_categories)) && (!in_array($this, $selected_categories)))
				$highlight_element = '';
		} elseif ($selected_categories != $this->id && $selected_categories != $this->seo_name && $selected_categories != $this)
				$highlight_element = '';
		return $highlight_element;
	}
	
	public function _getCounter($args)
	{
		if ($args['is_counter']) {
			return '&nbsp;(' . $this->countListings() . ')';
		} else
			return '';
	}
	
	public function _getIschildrenlabel($args)
	{
		$this->buildChildren($args['max_depth']);
		if ($this->children) {
			return $args['is_children_label'];
		} else {
			return '';
		}
	}

	/**
	 * returns category with the same seo name, but of another local type
	 *
	 * @param int/string $type
	 */
	public function getSimilarCategoryOfAnotherType($type)
	{
		$CI = &get_instance();
		$CI->load->model('types', 'types_levels');
		$CI->load->model('categories', 'categories');
		
		if (is_numeric($type)) {
			$type = $CI->types->getTypeById($type);
		} elseif (is_string($type)) {
			$type = $CI->types->getTypeBySeoName($type);
		} else {
			return ;
		}
		
		$CI->categories->setTypeId($type->id);
		return $CI->categories->getCategoryBySeoName($this->seo_name);
	}
}
?>