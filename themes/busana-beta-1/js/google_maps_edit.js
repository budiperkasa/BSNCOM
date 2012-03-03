	var map = null;
    var geocoder = null;
    var infoWindow = null;
    var markersArray = [];
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
    var glocation = (function(index, point, location, address_line_1, address_line_2, zip_or_postal_index, map_icon_file) {
    	this.index = index;
    	this.point = point;
    	this.location = location;
    	this.address_line_1 = address_line_1;
    	this.address_line_2 = address_line_2;
    	this.zip_or_postal_index = zip_or_postal_index;
    	this.map_icon_file = map_icon_file;
    	this.placeMarker = function() {
    		return placeMarker(this);
    	};
    	this.compileAddress = function() {
    		address = this.address_line_1;
    		if (this.address_line_2)
    			address += ", "+this.address_line_2;
    		if (this.location) {
    			if (this.address_line_1 || this.address_line_2)
    				address += " ";
    			address += this.location;
    		}
    		if (this.zip_or_postal_index)
    			address += " "+this.zip_or_postal_index;
    		return address;
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
    	this.setPoint = function(point) {
    		this.point = point;
    	};
    });

    $(document).ready(function() 
	{
		if (document.getElementById("maps_canvas")) {
		    var mapOptions = {
				zoom: 1,
				scrollwheel: false,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};
		    map = new google.maps.Map(document.getElementById("maps_canvas"), mapOptions);

		    geocoder = new google.maps.Geocoder();
			    
		    var coords_array_1 = new Array();
   			var coords_array_2 = new Array();

		    if ($(".map_coords_1[value!=''][value!='0.000000'][value!='0']").length != 0 || $(".map_coords_2[value!=''][value!='0.000000'][value!='0']").length != 0) {
		    	ajax_loader_show("Targeting...");
		    	$.each($(".location_block"), function(i, val) {
		    		if ($(".location_drop_boxes:eq("+i+")").length && (!$(".use_predefined_locations:eq("+i+")").length || $(".use_predefined_locations:eq("+i+")").is(":checked"))) {
						var locations_drop_boxes = [];
						$($(".location_drop_boxes:eq("+i+")").find(".location_dropdown_list").get().reverse()).each(function() {
							if ($(this).val())
								locations_drop_boxes.push($(this).children(":selected").text());
						});
						var location_string = locations_drop_boxes.join(', ');
					} else {
						var location_string = $(".location:eq("+i+")").val();
					}
		    		if (($(".map_coords_1:eq("+i+")").val()!='' && $(".map_coords_1:eq("+i+")").val()!=0) ||
		    		($(".map_coords_2:eq("+i+")").val()!='' && $(".map_coords_2:eq("+i+")").val()!=0)) {
			    		map_coords_1 = $(".map_coords_1:eq("+i+")").val();
			    		map_coords_2 = $(".map_coords_2:eq("+i+")").val();
			    		point = new google.maps.LatLng(map_coords_1, map_coords_2);
			    		coords_array_1.push(map_coords_1);
						coords_array_2.push(map_coords_2);

			    		var location_obj = new glocation(i, point, 
				    		location_string,
				    		$(".address_line_1:eq("+i+")").val(),
				    		$(".address_line_2:eq("+i+")").val(),
				  			$(".zip_or_postal_index:eq("+i+")").val(),
				   			$(".map_icon_file:eq("+i+")").val()
				   		);
			    		location_obj.placeMarker();
		    		}
		    	});

		    	setMapCenter(coords_array_1, coords_array_2);
		    } else {
		    	map.setCenter(new google.maps.LatLng(34, 0));
		    }

			google.maps.event.addListener(map, 'zoom_changed', function() {
				$(".map_zoom").val(scale.convertScale(map.getZoom()));
                		map_zoom = scale.convertScale(map.getZoom());
			});
		}
	});
	
	function setMapCenter(coords_array_1, coords_array_2) {
		var count = 0;
		var bounds = new google.maps.LatLngBounds();
		for (count == 0; count<coords_array_1.length; count++)  {
			bounds.extend(new google.maps.LatLng(coords_array_1[count], coords_array_2[count]));
		}
		if (count == 1) {
			if ($(".map_zoom").val() == '' || $(".map_zoom").val() == 0)
				var zoom_level = 10;
			else
				var zoom_level = scale.backConvertScale(parseInt($(".map_zoom").val()));
		} else {
			map.fitBounds(bounds);
			var zoom_level = map.getZoom();
		}
		map.setCenter(bounds.getCenter());
		map.setZoom(zoom_level);

		ajax_loader_hide();
	}

	function generateMap() {
		ajax_loader_show("Targeting...");
		var coords_array_1 = new Array();
    	var coords_array_2 = new Array();
		clearOverlays();
		$.each($(".location_block"), function(i, val) {
			if ($(".location_drop_boxes:eq("+i+")").length && (!$(".use_predefined_locations:eq("+i+")").length || $(".use_predefined_locations:eq("+i+")").is(":checked"))) {
				var locations_drop_boxes = [];
				$($(".location_drop_boxes:eq("+i+")").find(".location_dropdown_list").get().reverse()).each(function() {
					if ($(this).val())
						locations_drop_boxes.push($(this).children(":selected").text());
				});
				var location_string = locations_drop_boxes.join(', ');
			} else {
				var location_string = $(".location:eq("+i+")").val();
			}

			if ($(".manual_coords:eq("+i+")").is(":checked") && $(".map_coords_1:eq("+i+")").val()!='' && $(".map_coords_2:eq("+i+")").val()!='' && ($(".map_coords_1:eq("+i+")").val()!=0 || $(".map_coords_2:eq("+i+")").val()!=0)) {
				map_coords_1 = $(".map_coords_1:eq("+i+")").val();
				map_coords_2 = $(".map_coords_2:eq("+i+")").val();
				point = new google.maps.LatLng(map_coords_1, map_coords_2);
				coords_array_1.push(map_coords_1);
				coords_array_2.push(map_coords_2);

				var location_obj = new glocation(i, point, 
					location_string,
					$(".address_line_1:eq("+i+")").val(),
					$(".address_line_2:eq("+i+")").val(),
					$(".zip_or_postal_index:eq("+i+")").val(),
					$(".map_icon_file:eq("+i+")").val()
				);
				location_obj.placeMarker();
				setMapCenter(coords_array_1, coords_array_2);
			} else if(location_string) {
				var location_obj = new glocation(i, null, 
					location_string,
					$(".address_line_1:eq("+i+")").val(),
					$(".address_line_2:eq("+i+")").val(),
					$(".zip_or_postal_index:eq("+i+")").val(),
					$(".map_icon_file:eq("+i+")").val()
				);

				// Geocode by address
				geocoder.geocode( { 'address': location_obj.compileAddress()}, function(results, status) {
					if (status != google.maps.GeocoderStatus.OK) {
						alert("Sorry, we were unable to geocode that address (address #"+(i+1)+")");
						ajax_loader_hide();
					} else {
						point = results[0].geometry.location;
						$(".map_coords_1:eq("+i+")").val(point.lat());
						$(".map_coords_2:eq("+i+")").val(point.lng());
						map_coords_1 = point.lat();
						map_coords_2 = point.lng();
						coords_array_1.push(map_coords_1);
						coords_array_2.push(map_coords_2);
						location_obj.setPoint(point);
						location_obj.placeMarker();
						setMapCenter(coords_array_1, coords_array_2);
					}
				});
			} else {
				ajax_loader_hide();
			}
		});
	}

	function placeMarker(glocation) {
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
			map: map,
			icon: customIcon,
			shadow: customShadow,
			draggable: true
		});

		markersArray.push(marker);
		google.maps.event.addListener(marker, 'click', function() {
			showInfoWindow(glocation, marker);
		});
		
		google.maps.event.addListener(marker, 'dragend', function(event) {
			var point = marker.getPosition();
			if (point !== undefined) {
				var selected_location_num = glocation.index;
				$('#locations_accordion').accordion("activate", selected_location_num);
				$(".manual_coords:eq("+selected_location_num+")").attr("checked", true);
				$(".manual_coords:eq("+selected_location_num+")").parent().parent().find(".manual_coords_block").show(200);

				$(".map_coords_1:eq("+selected_location_num+")").val(point.lat());
				$(".map_coords_2:eq("+selected_location_num+")").val(point.lng());
				generateMap();
			}
		});
	}
	
	// This function builds info Window and shows it hiding another
	function showInfoWindow(glocation, marker) {
		address = glocation.compileHtmlAddress();
		index = glocation.index;
		$('#locations_accordion').accordion("activate", index);
		var windowHtml = '<div style="width:300px">';
		if (global_logo != '')
			windowHtml += '<img width="70px" style="float: left; padding-right: 10px;" src="' + global_server_path + '/users_images/logos/' + global_logo + '" />';
		windowHtml += '<b>' + global_title + '</b><br />' + address + '<div class="clear_float"></div>';
		windowHtml += '</div>';

		// we use global infoWindow, not to close/open it - just to set new content (in order to prevent blinking)
		if (!infoWindow)
			infoWindow = new google.maps.InfoWindow();

		infoWindow.setContent(windowHtml);
		infoWindow.open(map, marker);
	}
	
	function clearOverlays() {
		if (markersArray) {
			for(var i = 0; i<markersArray.length; i++){
				markersArray[i].setMap(null);
			}
			//map.setCenter(new google.maps.LatLng(34, 0));
			//map.setZoom(1);
		}
	}