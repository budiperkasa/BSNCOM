	// there may be some maps on one page
	var maps = {};
    var infoWindow = null;
    var directions;
    var address = '';
    var scale = 
    {
    	min: 1, 
    	max: 20,
    	convertScale: function(value) {
    		return Math.round((value/((this.max-this.min)+1))*100);
    	},
    	backConvertScale: function(value) {
    		return Math.round((value*((this.max-this.min)+1))/100);
    	}
    };
    var glocation = (function(index, point, location, address_line_1, address_line_2, zip_or_postal_index, map_icon_file, listing_title, listing_logo, listing_url, listing_seo_title, unique_map_id) {
    	this.index = index;
    	this.point = point;
    	this.location = location;
    	this.address_line_1 = address_line_1;
    	this.address_line_2 = address_line_2;
    	this.zip_or_postal_index = zip_or_postal_index;
    	this.map_icon_file = map_icon_file;
    	this.listing_title = listing_title;
    	this.listing_logo = listing_logo;
    	this.listing_url = listing_url;
    	this.listing_seo_title = listing_seo_title;
    	this.placeMarker = function() {
    		return placeMarker(this, unique_map_id);
    	};
    	this.compileHtmlAddress = function() {
    		address = this.address_line_1;
    		if (this.address_line_2)
    			address += ", "+this.address_line_2;
    		if (this.location) {
    			if (this.address_line_1 || this.address_line_2)
    				address += "<br />";
    			address += this.location;
    		}
    		if (this.zip_or_postal_index)
    			address += " "+this.zip_or_postal_index;
    		return address;
    	};
    	this.compileAddress = function() {
    		address = this.address_line_1;
    		if (this.address_line_2)
    			address += ", "+this.address_line_2;
    		if (this.location) {
    			address += " "+this.location;
    		}
    		if (this.zip_or_postal_index)
    			address += " "+this.zip_or_postal_index;
    		return address;
    	};
    });
    var map_markers_attrs_array = new Array();
    var map_markers_attrs = (function(map_id, markers_array) {
    		this.map_id = map_id;
    		this.markers_array = markers_array;
    });

    $(document).ready(function() 
	{
		// are there any markers?
		if (map_markers_attrs_array.length) {
			var markers = [];

			for (var i=0; i<map_markers_attrs_array.length; i++) {
				var unique_map_id = map_markers_attrs_array[i].map_id;
				var markers_array = map_markers_attrs_array[i].markers_array;
				if (document.getElementById("maps_canvas_"+unique_map_id)) {
					var mapOptions = {
						zoom: 1,
						scrollwheel: false,
						mapTypeId: google.maps.MapTypeId.ROADMAP
					};
				    maps[unique_map_id] = new google.maps.Map(document.getElementById("maps_canvas_"+unique_map_id), mapOptions);

					var bounds = new google.maps.LatLngBounds();
				    if (markers_array.length) {
				    	for (var j=0; j<markers_array.length; j++) {
			    			map_coords_1 = markers_array[j][0];
					    	map_coords_2 = markers_array[j][1];
			    			point = new google.maps.LatLng(map_coords_1, map_coords_2);
			    			bounds.extend(point);
	
			    			var location_obj = new glocation(i, point, 
			    				markers_array[j][2],
			    				markers_array[j][3],
			    				markers_array[j][4],
			    				markers_array[j][5],
			    				markers_array[j][6],
			    				markers_array[j][8],
			    				markers_array[j][9],
			    				markers_array[j][10],
			    				markers_array[j][11],
			    				unique_map_id
				    		);
				    		var marker = location_obj.placeMarker();
				    		markers.push(marker);
			    		}
						if (markers_array.length == 1) {
							var zoom_level = scale.backConvertScale(markers_array[0][7]);
						} else {
							maps[unique_map_id].fitBounds(bounds);
							var zoom_level = maps[unique_map_id].getZoom();
						}
						var map_center = bounds.getCenter();
				    } else {
				    	var zoom_level = 1;
				    	var map_center = new google.maps.LatLng(34, 0);
				    }
				    
				    maps[unique_map_id].setCenter(map_center);
				    maps[unique_map_id].setZoom(zoom_level);
				}
			}

			if(typeof MarkerClusterer == 'function')
				var markerCluster = new MarkerClusterer(maps[unique_map_id], markers, {'gridSize':20});

			//if(typeof map_radius == 'number') {
			if ($("#map_radius_"+unique_map_id).length) {
				map_radius = parseFloat($("#map_radius_"+unique_map_id).val());
				if ($("#map_in_miles_"+unique_map_id).val() == 'true')
					map_radius *= 1.609344;
				map_coord_1 = $("#map_coord_1_"+unique_map_id).val();
				map_coord_2 = $("#map_coord_2_"+unique_map_id).val();

				map_radius *= 1000; // we need radius exactly in meters
			    if (draw_circle != null) {
			    	draw_circle.setMap(null);
			    }
			    var draw_circle = new google.maps.Circle({
			    	center: new google.maps.LatLng(map_coord_1, map_coord_2),
			        radius: map_radius,
			        strokeColor: "#FF0000",
			        strokeOpacity: 0.25,
			        strokeWeight: 1,
			        fillColor: "#FF0000",
			        fillOpacity: 0.1,
			        map:  maps[unique_map_id]
			    });
			}
		}

		if ($(".direction_button").length) {
			unique_map_id = $(".direction_button").attr("id").replace("get_direction_button_", "");

			var directionsService = new google.maps.DirectionsService();
		      var directionsDisplay = new google.maps.DirectionsRenderer({map: maps[unique_map_id]})
		      directionsDisplay.setPanel(document.getElementById("route_"+unique_map_id));
		}
		
		$(".direction_button").click(function() {
			unique_map_id = $(".direction_button").attr("id").replace("get_direction_button_", "");
			// Retrieve the start and end locations and create
			// a DirectionsRequest using DRIVING directions.
			var start = $("#from_direction_"+unique_map_id).val();
			var end = $(".select_direction_"+unique_map_id+":checked").val();
			var request = {
				origin: start,
				destination: end,
				travelMode: google.maps.DirectionsTravelMode.DRIVING
			};

			directionsService.route(request, function(response, status) {
				if (status == google.maps.DirectionsStatus.OK) {
					directionsDisplay.setDirections(response);
				} else {
					handleDirectionsErrors(status);
				}
			});
		});
	});

	function placeMarker(glocation, unique_map_id) {
		if (glocation.map_icon_file) {
			var customIcon = new google.maps.MarkerImage(
				global_map_icons_path+'icons/'+glocation.map_icon_file,
				new google.maps.Size(32, 37),
				new google.maps.Point(0, 0),
				new google.maps.Point(16,37));
			var customShadow = new google.maps.MarkerImage(
				global_map_icons_path+'shadow-playground.png',
				new google.maps.Size(51, 37));
		} else {
			var customIcon = new google.maps.MarkerImage(
				global_map_icons_path+"blank.png",
				new google.maps.Size(27, 27),
				new google.maps.Point(0, 0),
				new google.maps.Point(14,27));
			var customShadow = new google.maps.MarkerImage(
				global_map_icons_path+'shadow-playground.png',
				new google.maps.Size(41, 27));
		}
		var marker = new google.maps.Marker({
			position: glocation.point,
			map: maps[unique_map_id],
			icon: customIcon,
			shadow: customShadow
		});

		google.maps.event.addListener(marker, 'click', function() {
			showInfoWindow(glocation, marker, maps[unique_map_id]);
		});

		$("#open_iw_" + glocation.listing_seo_title).click(function() {
			showInfoWindow(glocation, marker, maps[unique_map_id]);
			$(this).attr("href", "#maps_canvas_"+unique_map_id);
			moveSlowly($(this));
			return false;
		});
		
		return marker;
	}
	
	// This function builds info Window and shows it hiding another
	function showInfoWindow(glocation, marker, map) {
		address = glocation.compileHtmlAddress();
		var windowHtml = '<div>';
		if (glocation.listing_logo) {
			if (glocation.listing_seo_title)
				windowHtml += '<a href="#listing_id-' + glocation.listing_seo_title + '">';
			windowHtml += '<img width="70px" style="float: left; padding-right: 10px;" src="' + global_server_path + '/users_images/logos/' + glocation.listing_logo + '" />';
			if (glocation.listing_seo_title)
				windowHtml += '</a>';
		}
		if (glocation.listing_seo_title)
			windowHtml += '<a href="#listing_id-' + glocation.listing_seo_title + '">';
		windowHtml += '<b>' + glocation.listing_title + '</b><br />' + address + '<div class="clear_float"></div>';
		if (glocation.listing_seo_title)
			windowHtml += '</a>';
		windowHtml += '</div>';
		
		if (glocation.listing_url || glocation.listing_seo_title)
			windowHtml += '<div><br />';
		if (glocation.listing_seo_title)
			windowHtml += '<b><a href="#listing_id-' + glocation.listing_seo_title + '">' + view_summary_label + '</a></b>&nbsp;&nbsp;&nbsp;';
		if (glocation.listing_url)
			windowHtml += '<b><a href="' + glocation.listing_url + '">' + view_listing_label + '</a></b>';
		if (glocation.listing_url || glocation.listing_seo_title)
			windowHtml += '<br/><br/></div>';

		// we use global infoWindow, not to close/open it - just to set new content (in order to prevent blinking)
		if (!infoWindow)
			infoWindow = new google.maps.InfoWindow({maxWidth:450});

		infoWindow.setContent(windowHtml);
		infoWindow.open(map, marker);
	}
	
	function handleDirectionsErrors(status){
	   if (status == google.maps.DirectionsStatus.NOT_FOUND)
	     alert("No corresponding geographic location could be found for one of the specified addresses. This may be due to the fact that the address is relatively new, or it may be incorrect.");
	   else if (status == google.maps.DirectionsStatus.ZERO_RESULTS)
	     alert("No route could be found between the origin and destination.");
	   else if (status == google.maps.DirectionsStatus.UNKNOWN_ERROR)
	     alert("A directions request could not be processed due to a server error. The request may succeed if you try again.");
	   else if (status == google.maps.DirectionsStatus.REQUEST_DENIED)
	     alert("The webpage is not allowed to use the directions service.");
	   else if (status == google.maps.DirectionsStatus.INVALID_REQUEST)
	     alert("The provided DirectionsRequest was invalid.");
	   else if (status == google.maps.DirectionsStatus.OVER_QUERY_LIMIT)
	     alert("The webpage has sent too many requests within the allowed time period.");
	   else alert("An unknown error occurred.");
	}