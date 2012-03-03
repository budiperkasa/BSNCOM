<?php
include_once(MODULES_PATH . 'content_fields/classes/search_content_fields.class.php');

class listingsBlockClass extends blockClass
{
	/**
	 * when items array doesn't passed into the block - it must be extracted here
	 *
	 */
	protected function getItems()
	{
		/* Params:
		
		what_search - string,
		what_match - 'any'/'exact',
		where_search - string,
		where_radius - int (miles or kilometers),
		search_type - ID/obj,
		search_category - id1,id2,id3,id4,.../array/ID/obj/seo name
		search_location - ID/obj/seo name
		search_owner - login/ID
		search_status - 1: active, 2: blocked, 3: suspended, 4: unapproved, 5: not paid
		search_users_status - 1: unverified, 2: active, 3: blocked
		search_creation_date - timestamp/'Y-m-d'
		search_from_creation_date - timestamp/'Y-m-d'
		search_to_creation_date - timestamp/'Y-m-d'
		view_name - full/semitable/short
		view_format - int/'int*int'
		orderby [l.id]
		direction [desc]
		only_with_logos=boolean
		fully_load - boolean

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
		$paginator->setNumPerPage($paginator_attrs['num_per_page']);
		$CI->listings->setPaginator($paginator);

	    $listings = $CI->listings->selectListings($this->params, $orderby, $direction, $search_sql_array, $categories_array, $fully_load);

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
		
		$current_location_id = 0;
		if ($current_location = registry::get('current_location')) {
			$current_location_id = $current_location->id;
		}
		
		// --------------------------------------------------------------------------------------------
		// Cache tags will be special for each listing in the block
		// Ordering of listings is also important
		$cache_tags = array('listings', 'listings_counts', 'locations', 'categories', 'content_fields');
		foreach ($this->params['items_array'] AS $listing) {
			$cache_tags[] = 'listings_' . $listing->id;
			$cache_tags[] = 'users_' . $listing->user->id;
		}
		$cache_id = array_merge($this->compileCacheId(), $cache_tags);
		$cache_id = 'listings_block_' . $current_location_id . '_' . md5(serialize($cache_id));
		// Do not cache when random listings or during search by radius
		if ((isset($this->params['orderby']) && $this->params['orderby'] == 'random') || registry::get('radius_search_args') || !$cache = $CI->cache->load($cache_id)) {
			$html = $block->fetch($block_template);
			if (!(isset($this->params['orderby']) && $this->params['orderby'] == 'random') && !registry::get('radius_search_args'))
				$CI->cache->save($html, $cache_id, $cache_tags);
			// --------------------------------------------------------------------------------------------
		} else {
	    	$html = $cache;
	    }
		
		return $html;
	}
}
?>