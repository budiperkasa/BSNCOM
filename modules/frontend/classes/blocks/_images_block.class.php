<?php

class imagesBlockClass extends blockClass
{
	/**
	 * when items array doesn't passed into the block - it must be extracted here
	 *
	 */
	protected function getItems()
	{
		/* Params:

		only_logos [false] - bool
		orderby - string,
		limit [10] - int
		*/
		
		$CI = &get_instance();

		if (isset($this->params['search_type_id'])) {
			$search_type_id = $this->params['search_type_id'];

			$CI->load->model('types', 'types_levels');
			$current_type = $CI->types->getTypeById($search_type_id);
			if ($current_type->search_type == 'global')
				$search_custom_group_id = 0;
			else
				$search_custom_group_id = $search_type_id;
		} else {
			$search_custom_group_id = 0;
			$current_type = null;
		}
		
		if (isset($this->params['fully_load'])) {
			$fully_load = $this->params['fully_load'];
		} else {
			$fully_load = true;
		}

		if (isset($this->params['orderby'])) {
			$orderby = $this->params['orderby'];
		} else {
			$orderby = 'l.id';
		}
		if (isset($this->params['direction'])) {
			$direction = $this->params['direction'];
		} else {
			$direction = 'desc';
		}

		// --------------------------------------------------------------------------------------------
		$CI->load->model('categories', 'categories');
		if (isset($search_type_id) && $current_type->categories_type == 'local') {
			// Local categories
			$CI->categories->setTypeId($search_type_id);
		}
		$categories_array = $CI->categories->selectCategoriesFromDB();
		// --------------------------------------------------------------------------------------------
		
		// --------------------------------------------------------------------------------------------
		// Build search content fields block
		if ($search_custom_group_id == 0) {
			$group_name = GLOBAL_SEARCH_GROUP_CUSTOM_NAME;
		} else {
			$group_name = LOCAL_SEARCH_GROUP_CUSTOM_NAME;
		}
		$search_fields = new searchContentFields($group_name, $search_custom_group_id);
		$search_sql_array = $search_fields->validateSearch(LISTINGS_LEVEL_GROUP_CUSTOM_NAME, $this->params, 'true', $search_url_rebuild);
		// --------------------------------------------------------------------------------------------
		
		$paginator_attrs['args'] = $this->params;
		if (isset($this->params['limit']))
			$paginator_attrs['num_per_page'] = $this->params['limit'];
		else 
			$paginator_attrs['num_per_page'] = 10;
		
		$paginator = new pagination($paginator_attrs);
		$CI->load->model('listings', 'listings');
		$CI->pagination->setNumPerPage($paginator_attrs['num_per_page']);
		$CI->listings->setPaginator($paginator);
		
		//$cache_id = md5(serialize($this->params));
		//if ((isset($this->params['orderby']) && $this->params['orderby'] == 'random') || !$cache = $CI->cache->load($cache_id)) {
	    	$listings = $CI->listings->selectListings($this->params, $orderby, $direction, $search_sql_array, $categories_array, $fully_load);
			//$CI->cache->save(array($listings), $cache_id, array('listings'));
	    /*} else {
	    	list($listings) = $cache;
	    }*/
		
		return $listings;
	}
	
	/**
	 * we overwrite parent's render() method in order to work with this specific listings block cache
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

		$cache_id = 'listings_block_' . md5(serialize($this->params));
		if (!$cache = $CI->cache->load($cache_id)) {
			$html = $block->fetch($block_template);
			$CI->cache->save($html, $cache_id, array('listings'));
		} else {
	    	$html = $cache;
	    }
		
		return $html;
	}
}
?>