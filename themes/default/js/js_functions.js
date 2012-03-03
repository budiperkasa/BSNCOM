var ajax_fn = '';

$(document).ready(function() 
{
	// Drag table rows and build serialized_order
	if ($("#drag_table").length) {
	    $("#drag_table").tableDnD( {
	    	onDragClass: "drag_class",
	    	onDrop: function(table, row) {
				$("#serialized_order").val($.tableDnD.serialize());
	    	}
	    });
	}
	
	jGrowl_show_msg();

	// Add zebra style to tables
	$(".standardTable tr").live("mouseover",
		function() {
			$(this).addClass("row_highlight");
		}
	)
	$(".standardTable tr").live("mouseout",
		function() {
			$(this).removeClass("row_highlight");
		}
	)

	// Check/uncheck row of a table
	$(".standardTable tr td").live("click", 
		function(event) {
			if ( this == event.target) {
				if ($(this).parent().find(':first-child:first-child input[type=checkbox]').length) {
					chkbx = $(this).parent().find(':first-child:first-child input[type=checkbox]');
					if ($(chkbx).attr("checked")) {
						$(chkbx).attr("checked", "");
					} else {
						$(chkbx).attr("checked", "checked");
					}
				}
			}
		}
	);
	// Check/uncheck all checkboxes
	if ($(".standardTable tr th input[type=checkbox]").length) {
		$(".standardTable tr th input[type=checkbox]").click( function() {
			chkbxs = $(this).parent().parent().parent().find("tr td:first-child:first-child input[type=checkbox]");
			if (chkbxs.length) {
				if ($(this).attr("checked")) {
					chkbxs.each( function() {
						$(this).attr("checked", "checked");
					});
				} else {
					chkbxs.each( function() {
						$(this).attr("checked", "");
					});
				}
			}
		});
	}
	
	// Highlight errors
	if (isset('js_highlights')) {
		$.each(js_highlights , function(index, value) {
			$("input[name="+this+"]").addClass('error_highlight');
			$("textarea[name="+this+"]").addClass('error_highlight');
			$("select[name="+this+"]").addClass('error_highlight');
		});
	}

	// 'Write in Seo Style' button
	$(".seo_rewrite").click( function() {
		if ($("input[name=name]").length)
			name = $("input[name=name]").val().toLowerCase();
		if ($("input[name=login]").length)
			name = $("input[name=login]").val().toLowerCase();

	   name = name.replace(/[Ã¡|Ã|Ã£|Ãƒ|Ã¢|Ã‚|Ã€]/gi, "a"); // á, à, ã, â
       name = name.replace(/[Ã©|Ã‰|Ãª|ÃŠ]/gi, "e");   // é, è
       name = name.replace(/[Ã­|Ã|Ã³|Ã“]/gi, "i");  // í, ì
       name = name.replace(/[Ãµ|Ã•|Ã´|Ã”]/gi, "o");   // ó, ò
       name = name.replace(/[Ãº|Ãš|Ã¼|Ãœ]/gi, "u");  // ú, ù
       name = name.replace(/[Ã§|Ã‡]/gi, "c");   // ç
       name = name.replace(/[Ã±|Ã‘]/gi, "n");  // ñ (spanish)
       //change spaces and other characters by - (Hyphen)
       name = name.replace(/\W/gi, "_");
       // remove double - (Hyphen)
       name = name.replace(/(\_)\1+/gi, "_");
		
		seo_name_write(name);
	});

	// Auto resize wrong listings' logos
	$(".img_div, #img_div").each( function() {
		div_width = $(this).width();
		div_height = $(this).height();
		
		$(this).find("img").each( function() {
			img_width = $(this).css('width');
			img_height = $(this).css('height');
			//$(this).css("width", "auto").css("height", "auto"); // Remove existing CSS
	        //$(this).removeAttr("width").removeAttr("height");
			
			if (div_width < img_width) {
				$(this).css('width', div_width);
			} else {
				if (div_height < img_height) {
					$(this).css('height', div_height);
				}
			}
		});
	});
	
	// Move slowly through anchors
	$("a.ancLinks").click(function () {
		moveSlowly($(this));
    });
    
    if (window.$.datepicker != undefined) {
	    $.datepicker._generateHTML_Old = $.datepicker._generateHTML; $.datepicker._generateHTML = function(inst) {
			res = this._generateHTML_Old(inst); res = res.replace("_hideDatepicker()","_clearDate('#"+inst.id+"')"); return res;
		}
    }
    
    $(".popup_dialog").live('click', function() {
    	ajax_loader_show();
    	url = this.href;
    	title = this.title;
    	var dialog = $('<div style="display:hidden"></div>').appendTo('body');
    	// load remote content
    	dialog.load(
    		url,
    		{},
    		function (responseText, textStatus, XMLHttpRequest) {
    			dialog.dialog({title: title, draggable: false, modal: true, width: 580});
    			ajax_loader_hide();
    		}
    	);
    	//prevent the browser to follow the link
    	return false;
	});
	
	// Modal window
	if ($("a.nyroModal").length) {
		$('a.nyroModal').nyroModal({resizable: true, autoSizable: true});
	}
	
	// Place listings to/from quick list
	if ($(".add_to_favourites").length) {
		$(".add_to_favourites").each(function() {
			var listing_id = $(this).attr("listingid");

			if ((str = $.cookie("favourites")) != null) {
				favourites_array = PHPSerializer.unserialize(str);
			} else {
				var favourites_array = new Array();
			}
		
			if (in_array(listing_id, favourites_array) === false) {
				$(this).html(not_in_favourites_icon.clone());
			} else {
				$(this).html(in_favourites_icon.clone());
			}
		});

		$(".add_to_favourites, .add_to_favourites_button").click(function() {
			// there may be 'to/from quick list' link button
			// or just an icon
			if ($(this).hasClass("add_to_favourites_button")) {
				var obj = $(this).prev();
			} else {
				var obj = $(this);
			}
			var listing_id = $(obj).attr("listingid");

			if ((str = $.cookie("favourites")) != null) {
				favourites_array = PHPSerializer.unserialize(str);
			} else {
				var favourites_array = new Array();
			}
			if (in_array(listing_id, favourites_array) === false) {
				favourites_array.push(listing_id);
				$.jGrowl(to_favourites_msg, {
					life: 3000,
					theme: 'jGrowl_success_msg'
				});
				$(obj).html(in_favourites_icon.clone());
			} else {
				for (var count=0; count<favourites_array.length; count++) {
					if (favourites_array[count] == listing_id) {
						delete favourites_array[count];
					}
				}
				$.jGrowl(from_favourites_msg, {
					life: 3000,
					theme: 'jGrowl_success_msg'
				});
				$(obj).html(not_in_favourites_icon.clone());
			}
			$.cookie("favourites", PHPSerializer.serialize(favourites_array), {expires: 365, path: "/"});
			return false;
		});
	}
});

function moveSlowly(obj)
{
	elementClick = $(obj).attr("href");
	destination = $(elementClick).offset().top;
	if ($.browser.safari) {
		bodyElement = $("body");
	} else {
		bodyElement = $("html");
	}
	bodyElement.animate({ scrollTop: destination}, 1100 );
	return false;
}

function jGrowl_show_msg()
{
	if ($(".success_msgs").length) {
		$.jGrowl($(".success_msgs").html(), {
			life: 8000,
			theme: 'jGrowl_success_msg',
			beforeClose: function(e,m) {
				$(".success_msgs").remove();
			}
		});
	}
	if ($(".error_msgs").length) {
		$.jGrowl($(".error_msgs").html(), {
			sticky: true,
			theme: 'jGrowl_error_msg',
			beforeClose: function(e,m) {
				$(".error_msgs").remove();
			}
		});
	}
}

function ajax_loader_show(msg)
{
	if (msg == null)
		msg = 'Loading...';
	$("#ajax_loader").dialog({
		title: msg,
		resizable: false,
		draggable: false,
		autoOpen: false,
		modal: true,
		closeOnEscape: false,
		width: 250,
		minHeight: 30,
		open: function(event, ui) {$(".ui-dialog-titlebar-close").hide();}
	});
	$("#ajax_loader").dialog('open');
}

function ajax_loader_hide()
{
	$("#ajax_loader").dialog('close');
}

function parseScript(_source) {
	var source = _source;

	// Strip out tags
	while(source.indexOf("<" + "script") > -1 || source.indexOf("<" + "/script") > -1) {
							var s = source.indexOf("<" + "script");
							var s_e = source.indexOf(">", s);
							var e = source.indexOf("<" + "/script", s);
							var e_e = source.indexOf(">", e);
				 
							// Add to scripts array
							scripts.push(source.substring(s_e+1, e));
							// Strip from source
							source = source.substring(0, s) + source.substring(e_e+1);
	}

						// Return the cleaned source
						return source;
}

function evalScripts() {
	// Loop through every script collected and eval it
	for(var i=0; i<scripts.length; i++) {
		try {
			eval(scripts[i]);
		}
		catch(ex) {
			// do what you want here when a script fails
		}
	}
}

function seo_write(cat_name)
{
	var connect_symbol = "_";
    var str = "";
    var i;
    var exp_reg = new RegExp("[a-zA-Z0-9_]");
    var exp_reg2 = new RegExp("[ ]");
    cat_name.toString();
    for (i=0 ; i < cat_name.length; i++) {
        if (exp_reg.test(cat_name.charAt(i))) {
            str = str+cat_name.charAt(i);
        } else {
            if (exp_reg2.test(cat_name.charAt(i))) {
                if (str.charAt(str.length-1) != connect_symbol) {
                    str = str+connect_symbol;
                }
            }
        }
    }
    if (str.charAt(str.length-1) == connect_symbol)
        str = str.substr(0, str.length-1);
    return str;
}

function seo_name_write(cat_name)
{
	if ($("input[name=name]").length)
		$('#seo_name').val(seo_write(cat_name));
	if ($("input[name=login]").length)
		$('#seo_login').val(seo_write(cat_name));

    //$('#seo_name').val(seo_write(cat_name));
    return false;
}

function in_array(val, arr) 
{
	for (var i = 0; i < arr.length; i++) {
		if (arr[i] == val)
			return i;
	}
	return false;
}

function geocodeAddress(term, language, response, return_predefined_locations, location_index)
{
	var geocoder = new google.maps.Geocoder();
	geocoder.geocode( { 'address': term, 'language': language}, function(results, status) {
		if (status == "OK") {
			var res = new Array;
			var geocoded_names_array = new Array;
			for (var i=0; i<results.length; i++) {
				var res_string = new Array;
				var town = '';
				var city = '';
				var district = '';
				var province = '';
				var state = '';
				var country = '';
				for (var j=0; j<results[i].address_components.length; j++) {
					if (results[i].address_components[j].types[0] == "sublocality") {
						town =results[i].address_components[j].long_name;
						res_string.push(town);
					}
					if (results[i].address_components[j].types[0] == "locality") {
						city =results[i].address_components[j].long_name;
						res_string.push(city);
					}
					if (use_districts)
						if (results[i].address_components[j].types[0] == "administrative_area_level_3") {
							district = results[i].address_components[j].long_name;
							res_string.push(district);
						}
					if (use_provinces)
						if (results[i].address_components[j].types[0] == "administrative_area_level_2") {
							province = results[i].address_components[j].long_name;
							res_string.push(province);
						}
					if (results[i].address_components[j].types[0] == "administrative_area_level_1") {
						state = results[i].address_components[j].long_name;
						res_string.push(state);
					}
					if (results[i].address_components[j].types[0] == "country") {
						country = results[i].address_components[j].long_name;
						res_string.push(country);
					}
				}
				geocoded_name = res_string.join(', ');
				geocoded_names_array.push(geocoded_name);
				
				// --------------------------------------------------------------------------------------------
				// Here we will play with geocoded suggestions
				// --------------------------------------------------------------------------------------------
				if ((town || city) && (city == district || city == province || city == state)) {
					var res_string = new Array;
					if (district != '') res_string.push(district);
					if (province != '') res_string.push(province);
					if (state != '') res_string.push(state);
					res_string.push(country);
					geocoded_name = res_string.join(', ');
					geocoded_names_array.push(geocoded_name);
					if (district && (district == province || district == state)) {
						var res_string = new Array;
						if (province != '') res_string.push(province);
						if (state != '') res_string.push(state);
						res_string.push(country);
						geocoded_name = res_string.join(', ');
						geocoded_names_array.push(geocoded_name);
					}
					if (province && province == state) {
						var res_string = new Array;
						if (state != '') res_string.push(state);
						res_string.push(country);
						geocoded_name = res_string.join(', ');
						geocoded_names_array.push(geocoded_name);
					}
				}
				// --------------------------------------------------------------------------------------------
			}
			geocoded_names_array = geocoded_names_array.getUnique();
			for (var z=0; z<geocoded_names_array.length; z++) {
				res.push({
					label: geocoded_names_array[z],
					value: geocoded_names_array[z]
				});
			}
			// By the way, we can attach results from our DB (predefined locations)
			if (return_predefined_locations) {
				$.post(return_predefined_locations, {query: term}, function(data) {
					if (data = JSON.parse(data))
						res = res.concat(data);
					if (res) {
						response(res, location_index);
					} else {
						response(false, location_index);
					}
				});
			} else {
				if (res) {
					response(res, location_index);
				} else {
					response(false, location_index);
				}
			}
		} else {
			response(false, location_index);
		}
	});
}

function urlencode (str) {
    // URL-encodes string  
    // 
    // version: 911.718
    // discuss at: http://phpjs.org/functions/urlencode    // +   original by: Philip Peterson
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: AJ
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Brett Zamir (http://brett-zamir.me)    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +      input by: travc
    // +      input by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Lars Fischer    // +      input by: Ratheous
    // +      reimplemented by: Brett Zamir (http://brett-zamir.me)
    // +   bugfixed by: Joris
    // +      reimplemented by: Brett Zamir (http://brett-zamir.me)
    // %          note 1: This reflects PHP 5.3/6.0+ behavior    // %        note 2: Please be aware that this function expects to encode into UTF-8 encoded strings, as found on
    // %        note 2: pages served as UTF-8
    // *     example 1: urlencode('Kevin van Zonneveld!');
    // *     returns 1: 'Kevin+van+Zonneveld%21'
    // *     example 2: urlencode('http://kevin.vanzonneveld.net/');    // *     returns 2: 'http%3A%2F%2Fkevin.vanzonneveld.net%2F'
    // *     example 3: urlencode('http://www.google.nl/search?q=php.js&ie=utf-8&oe=utf-8&aq=t&rls=com.ubuntu:en-US:unofficial&client=firefox-a');
    // *     returns 3: 'http%3A%2F%2Fwww.google.nl%2Fsearch%3Fq%3Dphp.js%26ie%3Dutf-8%26oe%3Dutf-8%26aq%3Dt%26rls%3Dcom.ubuntu%3Aen-US%3Aunofficial%26client%3Dfirefox-a'
    str = (str+'').toString();
        // Tilde should be allowed unescaped in future versions of PHP (as reflected below), but if you want to reflect current
    // PHP behavior, you would need to add ".replace(/~/g, '%7E');" to the following.
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}

function isset(varname)  {
  if(typeof( window[ varname ] ) != "undefined") return true;
  else return false;
}

//Return new array with duplicate values removed
Array.prototype.getUnique = function(){
	var u = {}, a = [];
	for(var i = 0, l = this.length; i < l; ++i){
		if(this[i] in u)
			continue;
		a.push(this[i]);
		u[this[i]] = 1;
	}
	return a;
}

var delay = (function() {
	var timer = 0;
	return function(callback, ms) {
		clearTimeout (timer);
		timer = setTimeout(callback, ms);
		};
})();
