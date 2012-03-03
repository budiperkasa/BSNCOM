<?php

class listingLocation
{
	public $id;
	public $listing_id;
	public $geocoded_name;
	public $location;
	public $predefined_location_id;
	public $predefined_location;
	public $use_predefined_locations;
	public $address_line_1;
	public $address_line_2;
	public $zip_or_postal_index;
	public $manual_coords;
	public $map_coords_1;
	public $map_coords_2;
	public $map_zoom;
	public $map_icon_id;
	public $map_icon_file;
	// Need for location, address_line_1, address_line_2 translations during new location creation
	public $virtual_id;
	
	public function __construct()
	{
		$this->id = 'new';
		$this->listing_id = '';
		$this->geocoded_name = '';
		$this->location = '';
		$this->predefined_location_id = null;
		$this->predefined_location = null;
		$system_settings = registry::get('system_settings');
		if ($system_settings['predefined_locations_mode'] == 'prefered' || $system_settings['predefined_locations_mode'] == 'only')
			$this->use_predefined_locations = true;
		else
			$this->use_predefined_locations = false;
		$this->address_line_1 = '';
		$this->address_line_2 = '';
		$this->zip_or_postal_index = '';
		$this->manual_coords = 0;
		$this->map_coords_1 = 0;
		$this->map_coords_2 = 0;
		$this->map_zoom = '';
		$this->map_icon_id = '';
		$this->map_icon_file = '';
		$this->virtual_id = mktime();
	}
	
	public function setLocationFromArray($array)
	{
		if (isset($array['id']))
			$this->id = $array['id'];
		if (isset($array['listing_id']))
			$this->listing_id = $array['listing_id'];
		if (isset($array['geocoded_name']))
			$this->geocoded_name = $array['geocoded_name'];
		$this->location = $array['location'];
		$this->predefined_location_id = $array['predefined_location_id'];

		$system_settings = registry::get('system_settings');
		if ($system_settings['predefined_locations_mode'] == 'disabled')
			$this->use_predefined_locations = false;
		else 
			$this->use_predefined_locations = $array['use_predefined_locations'];

		$this->address_line_1 = $array['address_line_1'];
		$this->address_line_2 = $array['address_line_2'];
		if (isset($array['zip_or_postal_index']))
			$this->zip_or_postal_index = $array['zip_or_postal_index'];
		if (isset($array['manual_coords']))
			$this->manual_coords = $array['manual_coords'];
		if (isset($array['map_coords_1']))
			$this->map_coords_1 = $array['map_coords_1'];
		if (isset($array['map_coords_2']))
			$this->map_coords_2 = $array['map_coords_2'];
		if (isset($array['map_zoom']))
			$this->map_zoom = $array['map_zoom'];
		if (isset($array['map_icon_id']))
			$this->map_icon_id = $array['map_icon_id'];
		if (isset($array['map_icon_file']))
			$this->map_icon_file = $array['map_icon_file'];
		if (isset($array['virtual_id']))
			$this->virtual_id = $array['virtual_id'];
		
		if ($this->predefined_location_id) {
			$CI = &get_instance();
			$CI->load->model('locations', 'locations_predefined');
			$this->predefined_location = $CI->locations->getLocationById($this->predefined_location_id);
		}
	}
	
	public function renderDropBoxes()
	{
		$CI = &get_instance();
		$CI->load->model('locations', 'locations_predefined');
		$location_levels = $CI->locations->selectAllLevels();

		if (!$this->predefined_location) {
			$locations_by_level[1] = $CI->locations->selectAllSisters(0);
		} else {
			$chain = $this->predefined_location->getChainAsArray();
			end($chain);
			$i = 1;
			$locations_by_level[1] = $CI->locations->selectAllSisters(0, $chain[0]->id);
			foreach ($chain AS $key=>$chain_location) {
				$i++;
				$locations_by_level[$i] = $CI->locations->selectAllSisters($chain_location->id, $chain[$key+1]->id);
			}
		}
		
		if ($this->id != 'new')
			$virtual_id = $this->id;
		else 
			$virtual_id = $this->virtual_id;

		$view = $CI->load->view();
		$view->assign('locations_by_level', $locations_by_level);
		$view->assign('location_levels', $location_levels);
		$view->assign('virtual_id', $virtual_id);
		return $view->fetch('locations/drop_boxes.tpl');
	}
	
	public function location()
	{
		if ($this->use_predefined_locations && $this->predefined_location) {
			return $this->predefined_location->getChainAsString();
		} else {
			return $this->location;
		}
	}
	
	public function compileAddress()
	{
		$address = '';
		if ($this->address_line_1)
    		$address .= $this->address_line_1;
    	if ($this->address_line_2)
    		$address .= ", " . $this->address_line_2;
    	if ($location = $this->location()) {
    		if ($this->address_line_1 || $this->address_line_2)
    			$address .= " ";
    		$address .= $location;
    	}
    	if ($this->zip_or_postal_index)
    		$address .= " " . $this->zip_or_postal_index;
    	return $address;
    }
    
    public function calcDistanceFromCenter()
    {
    	if ($radius_search_args = registry::get('radius_search_args')) {
    		$system_settings = registry::get('system_settings');
    		$x = $radius_search_args['map_coord_1'];
	    	$y = $radius_search_args['map_coord_2'];
	    	//$distance = round((sqrt(pow(($this->map_coords_1-$x), 2)+pow(($this->map_coords_2-$y), 2))/$measure), 1);
	    	
	    	if ($system_settings['search_in_raduis_measure'] == 'miles') {
	    		$R = COORDS_MILES_MULTIPLIER; // earth's mean radius in miles
	    		$measure_text = LANG_SEARCH_IN_RADIUS_MILES;
	    	} else {
	    		$R = COORDS_KILOMETERS_MULTIPLIER; // earth's mean radius in km
	    		$measure_text = LANG_SEARCH_IN_RADIUS_KILOMETRES;
	    	}
			$dLat  = ($this->map_coords_1 - $x) * pi() / 180;
			$dLong = ($this->map_coords_2 - $y) * pi() / 180;
			$a = sin($dLat/2) * sin($dLat/2) + cos($x*pi()/180) * cos($this->map_coords_1*pi()/180) * sin($dLong/2) * sin($dLong/2);
			$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
			$distance = round($R * $c, 1);

	    	if ($distance >= 0.5)
	    		return $distance . ' ' . $measure_text;
    	}
    }
}
?>