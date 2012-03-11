<?php
include_once(MODULES_PATH . 'google_maps/classes/listings_markers.class.php');

class map_and_markersBlockClass extends blockClass
{
	/**
	 * when items array doesn't passed into the block - it must be extracted here
	 *
	 */
	protected function getItems()
	{
		/* Params:
		
		existed_listings - put markers of existed listings (when we already have an array of existed listings)
		show_anchors - info windows will have "Listings summary" anchors
		show_links - info windows will have "View listing" links
		markers_of_search_location - bool [false] - will show markers only of search_location, even if listings have other locations
		map_width
		map_height [300]
		clasterization
		
		THIS ALL FOR THE SEARCH OF LISTINGS (when we don't have existed listings):
		what_search - string,
		what_match - any/exact,
		where_search - string,
		where_radius - int,
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
		only_with_logos - boolean
		fully_load - boolean
		limit [10] - int
		*/
		
		$CI = &get_instance();
		
		if (!isset($this->params['map_height']))
			$this->params['map_height'] = 300;

		if (!isset($this->params['markers_of_search_location']))
			$markers_of_search_location = false;
		else 
			$markers_of_search_location = $this->params['markers_of_search_location'];
		
		$CI->load->model('listings', 'listings');
		$CI->listings->setPaginator(null);
		$listings = null;
		if (isset($this->params['existed_listings'])) {
			// show only current listings' markers
			$listings_ids = array();
			if (is_array($this->params['existed_listings'])) {
				foreach ($this->params['existed_listings'] AS $listing)
					if (is_numeric($listing))
						// if array of Ids
						$listings_ids[] = $listing;
					elseif (is_object($listing))
						// if array of objects
						$listings[] = $listing;
			} elseif (is_object($this->params['existed_listings']))
				// if single object
				$listings_ids[] = $this->params['existed_listings']->id;
			elseif (is_numeric($this->params['existed_listings']))
				// if single ID
				$listings_ids[] = $this->params['existed_listings'];
			elseif (is_string($this->params['existed_listings']))
				// if string of IDs connected with ','
				$listings_ids = array_filter(explode(',', $this->params['existed_listings']));
			if (!$listings && $listings_ids) {
				$listings = $CI->listings->selectListings(array('search_by_ids' => $listings_ids));
			}
		} else {
			// show all (according to params)
	    	$listings = $CI->listings->selectListings(array_merge(array('search_status' => 1, 'search_users_status' => 2), $this->params));
		}

		$show_anchors = true;
		if (isset($this->params['show_anchors'])) {
			$show_anchors = (bool)$this->params['show_anchors'];
		}
		$show_links = true;
		if (isset($this->params['show_links'])) {
			$show_links = (bool)$this->params['show_links'];
		}

		// All locations in this array
		$this->params['common_locations_array'] = array();
		$this->params['common_listings_array'] = array();
		if ($listings) {
			foreach ($listings AS $listing) {
				$this->params['common_locations_array'] = array_merge($this->params['common_locations_array'], $listing->locations_array());
				$this->params['common_listings_array'][] = $listing;
			}

			$listings_markers = new listingsMarkers;
			if (!($listings_markers_options = $listings_markers->buildSummaryMapMarkers($listings, $show_anchors, $show_links, $markers_of_search_location)))
				return false;
			else 
				return json_encode($listings_markers_options);
		} else {
			return false;
		}
	}
	
	/**
	 * we overwrite parent's render() method in order to work with this specific map and markers block cache
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
		$block->assign('unique_map_id', time());

		// We can draw a circle on map when radius_search_args parameter is set OR during the search by radius
		if (isset($this->params['radius_search_args']))
			$block->assign('radius_search_args', $this->params['radius_search_args']);
		elseif (registry::get('radius_search_args'))
			$block->assign('radius_search_args', registry::get('radius_search_args'));

		$cache_id = $this->compileCacheId();
		$cache_id = 'map_block_' . md5(serialize($cache_id));
		// Do not cache when no_cache=true or during search by radius
		if ((isset($this->params['no_cache']) && $this->params['no_cache'] == "true") || registry::get('radius_search_args') || !$cache = $CI->cache->load($cache_id)) {
			$html = $block->fetch($block_template);
			
			$cache_tags = array('listings', 'locations', 'categories');
			foreach ($this->params['common_listings_array'] AS $listing) {
				$cache_tags[] = 'listings_' . $listing->id;
				$cache_tags[] = 'users_' . $listing->user->id;
			}
			if (!(isset($this->params['no_cache']) && $this->params['no_cache'] == "true") && !registry::get('radius_search_args'))
				$CI->cache->save($html, $cache_id, $cache_tags);
		} else {
	    	$html = $cache;
	    }
		
		return $html;
	}
}
?>