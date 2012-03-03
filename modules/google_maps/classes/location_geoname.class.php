<?php

class locationGeoname
{
	public function geonames_request($query, $return = 'geoname')
	{
		//$system_settings = registry::get('system_settings');
        //if (@$maps_key = $system_settings['maps_key']) {
		//$fullUrl = sprintf("http://maps.google.com/maps/geo?q=%s&key=%s&hl=en&output=json", urlencode($query), $maps_key);

		$system_settings = registry::get('system_settings');
		$use_districts = $system_settings['geocoded_locations_mode_districts'];
		$use_provinces = $system_settings['geocoded_locations_mode_provinces'];
		
		$fullUrl = sprintf("http://maps.googleapis.com/maps/api/geocode/json?address=%s&language=en&sensor=false", urlencode($query));
		$ch = curl_init($fullUrl);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
		$response = curl_exec($ch);
		$ret = json_decode( $response, true );
		curl_close($ch);
		if($ret && $ret["status"] == "OK") {
			if ($return == 'coordinates') {
				return array($ret["results"][0]["geometry"]["location"]["lng"], $ret["results"][0]["geometry"]["location"]["lat"]);
			} elseif ($return == 'geoname') {
				$geocoded_name = array();
				foreach ($ret["results"][0]["address_components"] AS $component) {
					if ($component["types"][0] == "sublocality") {
						$town = $component["long_name"];
						$geocoded_name[] = $town;
					}
					if ($component["types"][0] == "locality") {
						$city = $component["long_name"];
						$geocoded_name[] = $city;
					}
					if ($use_districts)
						if ($component["types"][0] == "administrative_area_level_3") {
							$district = $component["long_name"];
							$geocoded_name[] = $district;
						}
					if ($use_provinces)
						if ($component["types"][0] == "administrative_area_level_2") {
							$province = $component["long_name"];
							$geocoded_name[] = $province;
						}
					if ($component["types"][0] == "administrative_area_level_1") {
						$state = $component["long_name"];
						$geocoded_name[] = $state;
					}
					if ($component["types"][0] == "country") {
						$country = $component["long_name"];
						$geocoded_name[] = $country;
					}
				}
				return implode(', ', $geocoded_name);
			} elseif ($return == 'address') {
				return $ret["results"][0]["formatted_address"];
			}
		} else {
			return '';
		}
			
			
			/*if($ret && $ret["Status"]["code"] == 200) {
				if ($return == 'coordinates') {
					return $ret["Placemark"][0]["Point"]["coordinates"];
				} elseif ($return == 'geoname') {
					$geocoded_name = '';
					if (isset($ret["Placemark"][0]["AddressDetails"]["Country"]) && isset($ret["Placemark"][0]["AddressDetails"]["Country"]["CountryName"])) {
						// Country
						$geocoded_name = $ret["Placemark"][0]["AddressDetails"]["Country"]["CountryName"];
						if (isset($ret["Placemark"][0]["AddressDetails"]["Country"]["AdministrativeArea"])) {
							// State/Region
							$geocoded_name = $ret["Placemark"][0]["AddressDetails"]["Country"]["AdministrativeArea"]["AdministrativeAreaName"] . ', ' . $geocoded_name;
							if (isset($ret["Placemark"][0]["AddressDetails"]["Country"]["AdministrativeArea"]["Locality"])) {
								// City
								$geocoded_name = $ret["Placemark"][0]["AddressDetails"]["Country"]["AdministrativeArea"]["Locality"]["LocalityName"] . ', ' . $geocoded_name;
							} elseif (isset($ret["Placemark"][0]["AddressDetails"]["Country"]["AdministrativeArea"]["SubAdministrativeArea"])) {
								if (isset($ret["Placemark"][0]["AddressDetails"]["Country"]["AdministrativeArea"]["SubAdministrativeArea"]["SubAdministrativeAreaName"])) {
									// Province
									$geocoded_name = $ret["Placemark"][0]["AddressDetails"]["Country"]["AdministrativeArea"]["SubAdministrativeArea"]["SubAdministrativeAreaName"] . ', ' . $geocoded_name;
								}
								if (isset($ret["Placemark"][0]["AddressDetails"]["Country"]["AdministrativeArea"]["SubAdministrativeArea"]["Locality"])) {
									// City/town
									$geocoded_name = $ret["Placemark"][0]["AddressDetails"]["Country"]["AdministrativeArea"]["SubAdministrativeArea"]["Locality"]["LocalityName"] . ', ' . $geocoded_name;
									if (isset($ret["Placemark"][0]["AddressDetails"]["Country"]["AdministrativeArea"]["SubAdministrativeArea"]["Locality"]["DependentLocality"])) {
										// City/town district
										$geocoded_name = $ret["Placemark"][0]["AddressDetails"]["Country"]["AdministrativeArea"]["SubAdministrativeArea"]["Locality"]["DependentLocality"]["DependentLocalityName"] . ', ' . $geocoded_name;
									}
								}
							}
						} elseif (isset($ret["Placemark"][0]["AddressDetails"]["Country"]["SubAdministrativeArea"])) {
							if (isset($ret["Placemark"][0]["AddressDetails"]["Country"]["SubAdministrativeArea"]["SubAdministrativeAreaName"])) {
								// Province
								$geocoded_name = $ret["Placemark"][0]["AddressDetails"]["Country"]["SubAdministrativeArea"]["SubAdministrativeAreaName"] . ', ' . $geocoded_name;
							}
							if (isset($ret["Placemark"][0]["AddressDetails"]["Country"]["SubAdministrativeArea"]["Locality"])) {
								// City/town
								$geocoded_name = $ret["Placemark"][0]["AddressDetails"]["Country"]["SubAdministrativeArea"]["Locality"]["LocalityName"] . ', ' . $geocoded_name;
							}
							if (isset($ret["Placemark"][0]["AddressDetails"]["Country"]["SubAdministrativeArea"]["Locality"]["DependentLocality"])) {
								// City/town district
								$geocoded_name = $ret["Placemark"][0]["AddressDetails"]["Country"]["SubAdministrativeArea"]["Locality"]["DependentLocality"]["DependentLocalityName"] . ', ' . $geocoded_name;
							}
						}
					}
					return $geocoded_name;
				} elseif ($return == 'address') {
					return $ret["Placemark"][0]["address"];
				}
			} else {
				return '';
			}*/
        /*} else {
        	return '';
        }*/
	}
}
?>