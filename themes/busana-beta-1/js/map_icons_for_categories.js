$(document).ready( function() {
	if (window.opener.document.getElementById("selected_icons_serialized").value) {
		selected_icons = PHPSerializer.unserialize(window.opener.document.getElementById("selected_icons_serialized").value);
	} else {
		var selected_icons = new Array();
	}
	
	$(".icon").each(function() {
		for (i=0; i<selected_icons.length; i++) {
			if (selected_icons[i] == $(this).attr('icon_id')) {
				$(this).toggleClass('selected_icon');
			}
		}
	});

	$(".icon").click( function() {
		$(this).toggleClass('selected_icon');
		if ($(this).is('.selected_icon')) {
			selected_icons[selected_icons.length] = $(this).attr('icon_id');
		} else {
			for (i=0; i<selected_icons.length; i++) {
				if (selected_icons[i] == $(this).attr('icon_id')) {
					delete selected_icons[i];
				}
			}
		}
		window.opener.document.getElementById("selected_icons_serialized").value = PHPSerializer.serialize(selected_icons);
	});
	
	$(".select_all").click( function() {
		var click_id = $(this).attr("id");
		$("."+click_id).each(function() {
			$(this).addClass('selected_icon');
			selected_icons[selected_icons.length] = $(this).attr('icon_id');
		});
		window.opener.document.getElementById("selected_icons_serialized").value = PHPSerializer.serialize(selected_icons);
		return false;
	});
	$(".deselect_all").click( function() {
		var click_id = $(this).attr("id");
		$("."+click_id).each(function() {
			$(this).removeClass('selected_icon');
			for (i=0; i<selected_icons.length; i++) {
				if (selected_icons[i] == $(this).attr('icon_id')) {
					delete selected_icons[i];
				}
			}
		});
		window.opener.document.getElementById("selected_icons_serialized").value = PHPSerializer.serialize(selected_icons);
		return false;
	});
});