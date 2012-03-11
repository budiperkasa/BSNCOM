<?php

class categoriesBlockClass extends blockClass
{
	/**
	 * when items array doesn't passed into the block - it must be extracted here
	 *
	 */
	public function getItems()
	{
		/* Params:
		
		type [0 - global] - object/numeric,
		from_category_id - parent category or root if doesn't set,
		max_depth [max] - if doesn't set = 'max'
		is_counter - bool
		*/

		$CI = &get_instance();
		$CI->load->model('categories', 'categories');

		if (isset($this->params['type'])) {
			$type = $this->params['type'];
			if (is_object($type))
				$type_id = $type->id;
			elseif (is_numeric($type)) {
				$type_id = $type;
				$CI->load->model('types', 'types_levels');
				$type = $CI->types->getTypeById($type_id);
				if ($type->categories_type == 'global')
					$local_categories_type_id = 0;
				else 
					$local_categories_type_id = $type->id;
			} else 
				$local_categories_type_id = 0;
		} else {
			$local_categories_type_id = 0;
		}
		if (isset($this->params['from_category_id'])) {
			$from_category_id = $this->params['from_category_id'];
		} else {
			$from_category_id = 0;
		}
		if (isset($this->params['max_depth'])) {
			$max_depth = $this->params['max_depth'];
		} else {
			$max_depth = 'max';
		}

		$this->params['type_id'] = $local_categories_type_id;

		$CI->categories->setTypeId($local_categories_type_id);
		$categories_tree = $CI->categories->getDirectChildrenOfCategory($from_category_id);
	    $this->params['categories_tree'] = $categories_tree;

		if (!$type || $type->categories_type == 'global') {
			$local_type_seo_name = null;
		} else {
			$local_type_seo_name = $type->seo_name;
		}
		$this->params['local_type_seo_name'] = $local_type_seo_name;
	}
	
	/**
	 * we overwrite parent's render() method in order to work with this specific categories cache
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

		$cache_id = $this->compileCacheId();
		$cache_id = 'categories_block_' . $current_location_id . '_' . md5(serialize($cache_id));
		if ((isset($this->params['no_cache']) && $this->params['no_cache'] == "true") || !$cache = $CI->cache->load($cache_id)) {
			$html = $block->fetch($block_template);

			$cache_tags = array('categories');
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