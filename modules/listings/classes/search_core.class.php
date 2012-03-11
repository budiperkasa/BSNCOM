<?php
include_once(MODULES_PATH . 'google_maps/classes/location_geoname.class.php');

class searchCore
{
	private $args;

	public function setArgs($args)
	{
		$this->args = $args;
	}
	
	public function processWhat()
	{
		if (isset($this->args['what_search'])) {
			$CI = &get_instance();
			$search_string = mb_ereg_replace("/[^" . $CI->config->item('permitted_uri_chars') . "]/", " ", $this->args['what_search']);
			
			if (isset($this->args['what_match']) && $this->args['what_match'] == 'exact') {
				$search_string = '\'"' . $search_string . '"\' IN BOOLEAN MODE';
			} else {
				$search_array = array_filter(explode(' ', $search_string));
				$search_array2 = array();
				foreach ($search_array AS $key=>$word) {
					$search_array[$key] = '>' . addslashes($word);
					$search_array2[] = addslashes($word) . '*';
				}
				$search_string = '\'' . implode(' ', array_merge($search_array, $search_array2)) . '\' IN BOOLEAN MODE';
			}

			if (function_exists('getLangDBCode') && $code = getLangDBCode())
				$lang_db_code = $code . '_';
			else
				$lang_db_code = '';

			// --------------------------------------------------------------------------------------------
			// Search from listings table
			$query = $CI->db->query("
			SELECT
				l.id,
				MATCH(l." . $lang_db_code . "title) AGAINST(" . $search_string . ") AS listings_title_score,
				MATCH(l." . $lang_db_code . "listing_description) AGAINST(" . $search_string . ") AS listings_description_score,
				MATCH(l." . $lang_db_code . "listing_meta_description) AGAINST(" .$search_string . ") AS listings_meta_description_score,
				MATCH(l." . $lang_db_code . "listing_keywords) AGAINST(" . $search_string . ") AS listings_keywords_score
			FROM listings AS l
			LEFT JOIN levels AS lev ON lev.id=l.level_id
			WHERE
				(MATCH(l." . $lang_db_code . "title) AGAINST(" . $search_string . ") AND lev.title_enabled=1) OR
				(MATCH(l." . $lang_db_code . "listing_description) AGAINST(" . $search_string . ") AND lev.description_mode != 'disabled') OR
				(MATCH(l." . $lang_db_code . "listing_meta_description) AGAINST(" . $search_string . ") AND lev.meta_enabled = 1) OR
				(MATCH(l." . $lang_db_code . "listing_keywords) AGAINST(" . $search_string . ") AND lev.meta_enabled = 1)
			ORDER BY
				(listings_title_score*1.25+listings_description_score+listings_meta_description_score+listings_keywords_score*1.2) desc");
			$listings = $query->result_array();
			$listings_ids = array();
			foreach ($listings AS $row) {
				$listings_ids[$row['id']] = $row['listings_title_score']*1.25+$row['listings_description_score']+$row['listings_meta_description_score']+$row['listings_keywords_score']*1.2;
			}
			// --------------------------------------------------------------------------------------------

			// --------------------------------------------------------------------------------------------
			// Search from categories table
			$query = $CI->db->query("
			SELECT
				c.id,
				MATCH(c." . $lang_db_code . "name) AGAINST(" . $search_string . ") AS categories_name_score,
				MATCH(c." . $lang_db_code . "meta_title) AGAINST(" . $search_string . ") AS categories_meta_title_score,
				MATCH(c." . $lang_db_code . "meta_description) AGAINST(" . $search_string . ") AS categories_meta_description_score
			FROM (categories AS c)
			WHERE MATCH(c." . $lang_db_code . "name, c." . $lang_db_code . "meta_title, c." . $lang_db_code . "meta_description) AGAINST(" . $search_string . ")
			ORDER BY (categories_name_score*1.25+categories_meta_title_score+categories_meta_description_score*1.20) desc");
			$categories = $query->result_array();

			$CI->load->model('categories', 'categories');
			$categories_of_search = array();
			foreach ($categories AS $row) {
				$category = $CI->categories->getCategoryById($row['id']);
				$score = $row['categories_name_score']*1.25+$row['categories_meta_title_score']+$row['categories_meta_description_score']*1.20;
				$categories_of_search[] = array($score, $category->id);
				foreach ($CI->categories->getAllChildrenOfCategory($category) AS $category)
					$categories_of_search[] = array($score, $category->id);
			}

			$listings_ids_in_categories = array();
			if ($categories_of_search) {
				foreach ($categories_of_search AS $category_array) {
					$score = $category_array[0];
					$category_id = $category_array[1];
					$query = $CI->db->query("
					SELECT
						lic.listing_id AS id
					FROM (listings_in_categories AS lic)
					WHERE lic.category_id=" . $category_id);
					$listings_in_category = $query->result_array();
					foreach($listings_in_category AS $row) {
						if (array_key_exists($row['id'], $listings_ids)) {
							// sum scores of listings table + score of categories table
							$listings_ids[$row['id']] = $listings_ids[$row['id']]+$score;
						} else {
							// listings those were found just by categories - will be merged to the end
							$listings_ids_in_categories[] = $row['id'];
						}
					}
				}
			}
			// --------------------------------------------------------------------------------------------
			
			// Sort listings IDs by their score
			arsort($listings_ids);
			return array_merge(array_keys($listings_ids), $listings_ids_in_categories);
		} else {
			return array();
		}
	}
	
	public function processWhere()
	{
		if (isset($this->args['where_search'])) {
			$CI = &get_instance();
			
			$search_string = mb_str_replace(',', '', mb_ereg_replace("/[^" . $CI->config->item('permitted_uri_chars') . "]/", " ", $this->args['where_search']));

			/*$search_array = array_filter(explode(' ', $search_string));
			$search_array2 = array();
			foreach ($search_array AS $key=>$word) {
				$search_array[$key] = '>' . addslashes($word);
				$search_array2[] = addslashes($word) . '*';
			}
			$search_string = '\'' . implode(' ', array_merge($search_array, $search_array2)) . '\' IN BOOLEAN MODE';*/
			
			if (function_exists('getLangDBCode') && $code = getLangDBCode())
				$lang_db_code = $code . '_';
			else
				$lang_db_code = '';

			$geocoder = new locationGeoname;
			$geocoded_location = $geocoder->geonames_request($this->args['where_search']);
			
			$search_array = array_filter(explode(' ', $geocoded_location));
			foreach ($search_array AS $key=>$word) {
				$search_array[$key] = '+' . addslashes($word);
			}
			$geo_search_string = '\'' . implode(' ', $search_array) . '\' IN BOOLEAN MODE';

			$search_array2 = array();
			$search_array3 = array();
			foreach ($search_array AS $key=>$word) {
				$search_array2[] = '>' . addslashes($word);
				$search_array3[] = addslashes($word) . '*';
			}
			$location_search_string = '\'' . implode(' ', array_merge($search_array2, $search_array3)) . '\' IN BOOLEAN MODE';

			$query = $CI->db->query("
			SELECT
				lil.listing_id AS id,
				MATCH(lil.geocoded_name) AGAINST(" . $geo_search_string . ") AS listings_geocoded_name_score,"
				. ($geocoded_location ? "MATCH(lil.geocoded_name) AGAINST('\"" . addslashes($geocoded_location) . "\"' IN BOOLEAN MODE) AS listings_geocoded_location_score," : "") .
				"MATCH(lil." . $lang_db_code . "location) AGAINST(" . $location_search_string . ") AS listings_location_score,
				MATCH(lil." . $lang_db_code . "address_line_1) AGAINST(" .$location_search_string . ") AS listings_address_line_1_score,
				MATCH(lil." . $lang_db_code . "address_line_2) AGAINST(" . $location_search_string . ") AS listings_address_line_2_score,
				MATCH(lil.zip_or_postal_index) AGAINST(" . $location_search_string . ") AS listings_zip_or_postal_index_score
			FROM (listings_in_locations AS lil)
			WHERE 
				MATCH(lil.geocoded_name) AGAINST(" . $geo_search_string . ") OR
				MATCH(lil." . $lang_db_code . "location) AGAINST(" .$location_search_string . ") OR
				MATCH(lil." . $lang_db_code . "address_line_1) AGAINST(" .$location_search_string . ") OR
				MATCH(lil." . $lang_db_code . "address_line_2) AGAINST(" .$location_search_string . ") OR
				MATCH(lil.zip_or_postal_index) AGAINST(" .$location_search_string . ")"
			. ($geocoded_location ? " OR MATCH(lil.geocoded_name) AGAINST('\"" . addslashes($geocoded_location) . "\"' IN BOOLEAN MODE)" : "") . 
			"ORDER BY (
				listings_geocoded_name_score*1.25+"
				. ($geocoded_location ? "listings_geocoded_location_score*2+" : "") .
				"listings_location_score+
				listings_address_line_1_score*1.25+
				listings_address_line_2_score+
				listings_zip_or_postal_index_score*2) desc");
			$listings = $query->result_array();
			$listings_ids = array();
			foreach ($listings AS $row) {
				$listings_ids[] = $row['id'];
			}
			
			return $listings_ids;
		}
	}
}

// str_replace function with mbString functionality
function mb_str_replace($needle, $replacement, $haystack) {
	if (!is_array($needle))
		$needle = array($needle);
	foreach ($needle AS $needle_item) {
		$needle_len = mb_strlen($needle_item);
		$pos = mb_strpos($haystack, $needle_item);
		while (!($pos === false)) {
			$front = mb_substr($haystack, 0, $pos );
			$back  = mb_substr($haystack, $pos + $needle_len);
			$haystack = $front . $replacement . $back;
			$pos = mb_strpos($haystack, $needle_item);
		}
	}
	return $haystack;
}
?>