$(document).ready( function() {
	var selected_location_num = parseInt(window.opener.document.getElementById("selected_location_num").value)+1;
	var selected_icon = window.opener.document.getElementById("map_icon_id["+selected_location_num+"]").value;
	var selected_icon_file = window.opener.document.getElementById("map_icon_file["+selected_location_num+"]").value;

	$(".icon").each(function() {
		if (selected_icon == $(this).attr('icon_id')) {
			$(this).toggleClass('selected_icon');
		}
	});

	$(".icon").click( function() {
		$(".icon").each(function() {
			$(this).removeClass('selected_icon');
		});
		$(this).addClass('selected_icon');
		selected_icon = $(this).attr('icon_id');
		selected_icon_file = $(this).attr('icon_file');

		window.opener.document.getElementById("map_icon_id["+selected_location_num+"]").value = selected_icon;
		window.opener.document.getElementById("map_icon_file["+selected_location_num+"]").value = selected_icon_file;
		window.opener.generateMap();
		window.close();
	});
	
	$(".reset_icon").click( function() {
		window.opener.document.getElementById("map_icon_id["+selected_location_num+"]").value = '';
		window.opener.document.getElementById("map_icon_file["+selected_location_num+"]").value = '';
		window.opener.generateMap();
		window.close();
	});
});