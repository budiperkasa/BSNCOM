<?php

class locationsBlockClass extends blockClass
{
	public function getItems()
	{
		/* Params:

		max_depth - int ['max']
		is_counter - bool [false]
		is_only_labeled - bool [false]
		from_location_id - ID [0]
		*/

		$CI = &get_instance();
		$CI->load->model('locations', 'locations_predefined');

		if (isset($this->params['from_location_id'])) {
			$from_location_id = $this->params['from_location_id'];
		} else {
			$from_location_id = 0;
		}
		if (isset($this->params['max_depth'])) {
			$max_depth = $this->params['max_depth'];
		} else {
			$max_depth = 'max';
			$this->params['max_depth'] = $max_depth;
		}
		if (!isset($this->params['is_only_labeled'])) {
			$this->params['is_only_labeled'] = false;
		}
		if (!isset($this->params['is_counter'])) {
			$this->params['is_counter'] = false;
		}

		// We will select only root locations at first, other children will be collected inside each root's location
		$locations_tree = $CI->locations->getDirectChildrenOfLocation($from_location_id, $this->params['is_only_labeled']);

	    $this->params['locations_tree'] = $locations_tree;
	}
	
	/**
	 * we overwrite parent's render() method in order to work with this specific locations cache
	 *
	 * @return html
	 */
	public function render()
	{
		$CI = &get_instance();
		$block = $CI->load->view();

		// --------------------------------------------------------------------------------------------
		// Render special template or default
		// --------------------------------------------------------------------------------------------
		if (isset($this->params['block_template']) && $block->template_exists($this->params['block_template'])) {
        	$block_template = $this->params['block_template'];
        } else {
        	$block_template = 'frontend/blocks/default.tpl';
        }
        // --------------------------------------------------------------------------------------------

		if ($this->wrapper_object)
			$block->assign('wrapper_object', $this->wrapper_object);
		$block->assign($this->params);
		
		$current_location_id = 0;
		if ($current_location = registry::get('current_location')) {
			$current_location_id = $current_location->id;
		}
		$current_category_id = 0;
		if ($current_category = registry::get('current_category')) {
			$current_category_id = $current_category->id;
		}
		$current_type_id = 0;
		if ($current_type = registry::get('current_type')) {
			$current_type_id = $current_type->id;
		}

		$cache_id = $this->compileCacheId();
		$cache_id = 'locations_block_' . $current_location_id . '_' . $current_category_id . '_' . $current_type_id . '_' . md5(serialize($cache_id));
		if ((isset($this->params['no_cache']) && $this->params['no_cache'] == "true") || !$cache = $CI->cache->load($cache_id)) {
			$html = $block->fetch($block_template);

			$cache_tags = array('locations');
			if ($this->params['is_counter'])
				$cache_tags[] = 'listings_counts';
			$CI->cache->save($html, $cache_id, $cache_tags);
		} else {
	    	$html = $cache;
	    }
		
		return $html;
	}
}
?>