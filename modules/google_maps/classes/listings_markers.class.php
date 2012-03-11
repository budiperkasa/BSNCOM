<?php
class listingsMarkers
{
	public function buildSummaryMapMarkers($listings_array, $show_anchors = true, $show_links = true, $markers_of_search_location = false)
	{
		$system_settings = registry::get('system_settings');
		
		$current_location = registry::get('current_location');

		$listings_markers_options = array();
		foreach ($listings_array AS $listing) {
			if ($listing->type->locations_enabled && $listing->level->maps_enabled) {
				foreach ($listing->locations_array() AS $location) {
					if ($markers_of_search_location && $current_location) {
						// we will not take markers from other locations
						if (strpos($location->geocoded_name, $current_location->geocoded_name) === FALSE)
							continue;
					}

					$listing_url = '';
					if ($show_links)
						$listing_url = site_url('listings/' . $listing->getUniqueId() . '/');
					$listing_seo_title = '';
					if ($show_anchors)
						$listing_seo_title = $listing->getUniqueId();

					if ($location->map_coords_1 != 0 && $location->map_coords_2 != 0) {
						$listings_markers_options[] = array(
						$location->map_coords_1,
						$location->map_coords_2,
						_utf8_encode($location->location()),
						_utf8_encode($location->address_line_1),
						_utf8_encode($location->address_line_2),
						_utf8_encode($location->zip_or_postal_index),
						$location->map_icon_file,
						$location->map_zoom,
						_utf8_encode($listing->title()),
						$listing->logo_file,
						$listing_url,
						$listing_seo_title,
						);
					}
				}
			}
		}

		return $listings_markers_options;
	}
}
?>