{include file="backend/admin_header.tpl"}
{assign var="listing_id" value=$listing->id}

                <script language="javascript" type="text/javascript"><!--
                var select_icons_window_url = "{$VH->site_url("admin/categories/select_icons_for_listings/")}";
                {if $listing->type->categories_type == 'global'}
                var typeid_url_part = "";
                {elseif $listing->type->categories_type == 'local'}
                var typeid_url_part = "/type_id/{$listing->type->id}/";
                {/if}

                var global_title = '{addslashes string=$listing->title()}';
			    var global_logo = '{$listing->logo_file}';
			    var global_server_path = '{$users_content}';

			    var global_map_icons_path = '{$public_path}map_icons/';
			    var coords_by_click = true;

			    {if $listing->type->locations_enabled && $listing->level->locations_number}
			    var selected_location_num = 0;
			    {assign var=locations_array value=$listing->locations_array()}
			    {assign var=location value=$locations_array[0]}
                {if $location->id != 'new'}
			    var selected_location_id = {$location->id};
			    {else}
			    var selected_location_id = {$location->virtual_id};
			    {/if}
			    {/if}

                var selected_category_id = 0;
                var categories_limit = {$listing->level->categories_number};
                var selected_categories_count = 0;
                var categories = new Array();
                var serialized_categories = '';
                
                var locations_limit = {$listing->level->locations_number};

                var description_max_size = {$listing->level->description_length};
                
                var use_districts = {$system_settings.geocoded_locations_mode_districts};
                var use_provinces = {$system_settings.geocoded_locations_mode_provinces};

                $(document).ready(function() {ldelim}
                	{if $content_access_obj->isPermission('Edit listings expiration date') && (!$listing->level->eternal_active_period || $listing->level->allow_to_edit_active_period)}
                	$("#expiration_date").datepicker({ldelim}
						showOn: "both",
						buttonImage: "{$public_path}images/calendar.png",
						buttonImageOnly: true
                    {rdelim});
                    $("#expiration_date").datepicker('setDate', $.datepicker.parseDate('yy-mm-dd', '{$listing->expiration_date}'));
                    $("#expiration_date").datepicker("option", $.datepicker.regional["{$current_language}"]);
                    {/if}
                
                	buildAccordionAndLocationAutocomplete();

				    {if $listing->level->description_mode == 'enabled'}
				    ///////////////////////////////////////////////////////////////////////////////////
				    // Listing description chars counter
                    $("#listing_description_symbols_left").html(String(description_max_size - $("#listing_description").val().length));
                    $("#listing_description").keyup( function() {ldelim}
						var left;
						left = description_max_size - $("#listing_description").val().length;
						if (left >= 0) {ldelim}
							$("#listing_description_symbols_left").html(String(left));
						{rdelim} else {ldelim}
							alert("{addslashes string=$LANG_LISTING_DESCRIPTION_LIMIT_ERROR}");
							text = $("#listing_description").val();
							$("#listing_description").val(text.substr(0, description_max_size));
						{rdelim}
					{rdelim});
					{/if}

					{if $listing->type->categories_type != 'disabled'}
					///////////////////////////////////////////////////////////////////////////////////
					// Load categories array into tree
                    {foreach from=$listing->categories_array() item=category}
						categories[{$category->id}] = {$category->id};
						selected_categories_count++;
					{/foreach}
					serializeCategories();

					{if $listing->type->locations_enabled && $listing->level->locations_number && $listing->level->maps_enabled}
					$.post('{$VH->site_url("ajax/categories/is_icons")}'+typeid_url_part, {ldelim}'categories_list': PHPSerializer.serialize(categories), 'selected_icons': serializeIcons(){rdelim}, function(data) {ldelim}showHideMarkerIconsBtn(data); {rdelim}, "json");
					{/if}

                    $(".categories_item").live("click", function() {ldelim}
                        id =$(this).attr('id');
	                    $('#' + selected_category_id).css('background-color', '#FFFFFF');
	                    $('#' + id).css('background-color', '#90C2E1');
	                    selected_category_id = id;
                    {rdelim});

                    ///////////////////////////////////////////////////////////////////////////////////
                    // Delete category item
                    $("#delete_category_from_list").click(function() {ldelim}
                        if (selected_categories_count > 0 && selected_category_id != 0) {ldelim}
                        	ajax_loader_show("Deleting category...");
                            for (i = 0; i < categories.length; i++) {ldelim}
                                if ("category_" + categories[i] == selected_category_id) {ldelim}
                                    $("#" + selected_category_id).remove();
                                    delete categories[i];
                                    serializeCategories();
                                    selected_categories_count--;
                                    selected_category_id = 0;
                                {rdelim}
                            {rdelim}
                            {if $listing->type->locations_enabled && $listing->level->locations_number && $listing->level->maps_enabled}
                            $.post('{$VH->site_url("ajax/categories/is_icons")}'+typeid_url_part, {ldelim}'categories_list': PHPSerializer.serialize(categories), 'selected_icons': serializeIcons(){rdelim}, function(data) {ldelim}showHideMarkerIconsBtn(data); generateMap(); {rdelim}, "json");
                            {else}
                            ajax_loader_hide();
                            {/if}
                        {rdelim}
                    {rdelim});
                    {/if}

                    ///////////////////////////////////////////////////////////////////////////////////
                    // Open manual coordinates form
                    $(".manual_coords").live('click', function() {ldelim}
                    	if ($(this).is(":checked"))
                    		$(this).parent().parent().find(".manual_coords_block").show(200);
                    	else
                    		$(this).parent().parent().find(".manual_coords_block").hide(200);
                    {rdelim});
                    
                    {if $system_settings.predefined_locations_mode && $system_settings.predefined_locations_mode != 'disabled' && $system_settings.predefined_locations_mode != 'only'}
                    ///////////////////////////////////////////////////////////////////////////////////
                    // Open predefined locations drop boxes
                    $(".use_predefined_locations").live('click', function() {ldelim}
                    	if ($(this).is(":checked")) {ldelim}
                    		$(this).parent().parent().find(".location_drop_boxes").show(200);
                    		$(this).parent().parent().find(".location_string").hide(200);
                    	{rdelim} else {ldelim}
                    		$(this).parent().parent().find(".location_string").show(200);
                    		$(this).parent().parent().find(".location_drop_boxes").hide(200);
                    	{rdelim}
                    {rdelim});
                    {/if}

                    {if !$system_settings.predefined_locations_mode || ($system_settings.predefined_locations_mode && $system_settings.predefined_locations_mode != 'only')}
					///////////////////////////////////////////////////////////////////////////////////
                    // Work with geocoded names
                    $(".geocoded_names_options").live('click', function() {ldelim}
	            		$(".geocoded_name:eq("+selected_location_num+")").val($('input[name=geocoded_names_options_'+selected_location_num+']:checked').val());
					{rdelim});
                    var response = (function(val, location_index) {ldelim}
                    	if (!location_index)
                    		location_index = selected_location_num;
                    	ajax_loader_hide();
	            		this.res = val;
	            		if (val) {ldelim}
	            			var suggestions_string = '<div style="padding: 5px 0 5px"><b>{$LANG_GEO_NAME_DESCR}<'+'/b>';
	            			for (var i=0; i<this.res.length; i++) {ldelim}
	            				if (this.res.length == 1)
	            					$(".geocoded_name:eq("+location_index+")").val(this.res[i].label);

	            				if (i==0 || $(".geocoded_name:eq("+location_index+")").val()==this.res[i].label)
	            					var str_checked = 'checked';
	            				else
	            					var str_checked = '';
	            				suggestions_string = suggestions_string + '<label><input type="radio" name="geocoded_names_options_' + location_index + '" class="geocoded_names_options" ' + str_checked + ' value="' + this.res[i].label + '" /> ' + this.res[i].label + '<'+'/label>';
	            			{rdelim}
	            			suggestions_string = suggestions_string + '<'+'/div>';
	            			$(".geocoded_suggestions:eq("+location_index+")").html(suggestions_string).show();
	            		{rdelim}
	            	{rdelim});

					$(".location").each(function(i, val) {ldelim}
						if ($(".location:eq("+i+")").val()) {ldelim}
							ajax_loader_show('GeoCoding...');
                    		geocodeAddress($(".location:eq("+i+")").val(), '{$system_settings.default_language}', response, false, i);
                    	{rdelim}
                    {rdelim});

                    $('.location').live('keyup', function() {ldelim}
                    	var keyup_location = $(this);
	            		delay(function() {ldelim}
	            			if (keyup_location.val()) {ldelim}
		            			ajax_loader_show('GeoCoding...');
		            			geocodeAddress(keyup_location.val(), '{$system_settings.default_language}', response, false, false);
		            		{rdelim} else {ldelim}
	            				$(".geocoded_suggestions:eq("+selected_location_num+")").html('').hide();
	            				$(".geocoded_name:eq("+selected_location_num+")").val('');
	            			{rdelim}
	            		{rdelim}, 3500);
	            	{rdelim});
                    {/if}
                    
                    ///////////////////////////////////////////////////////////////////////////////////
                    // Show delete address button, only if there are more than 1 address
                    {if $listing->locations_array()|@count > 1}
                    $(".delete_location").show();
                    {/if}
                    
                    ///////////////////////////////////////////////////////////////////////////////////
                    // Adds new locations block into accordion
                    $(".add_location").click(function() {ldelim}
                    	$('#locations_accordion').accordion('destroy');
                    	$('.location').autocomplete('destroy');
                    	$("#locations_accordion").append($("#locations_accordion>h3:first").clone());

                    	var i = 1;
						$(".location_number").each(function() {ldelim}
							$(this).html(i);
							i++;
						{rdelim});
						
						$(".delete_location").show();

                    	var location = $("#locations_accordion>div:first").clone();
                    	var locations_blocks = location.find('input, select');
                    	var ddate = new Date();
                    	var virtual_location_id = parseInt($(".location_id").last().val())+ddate.getTime();
                    	locations_blocks.each( function() {ldelim}
                    		// clear any value of location block,
                    		// except zoom - as it is the same for any location
                    		if (!$(this).is(".map_zoom"))
	                    		$(this).val('');

	                    	// it will be virtual location id while it isn't saved
	                    	if ($(this).is(".location_id"))
	                    		$(this).val(virtual_location_id);
	                    	if ($(this).is(".manual_coords")) {ldelim}
	                    		$(this).val(virtual_location_id);
		                    	// clear manual coords block
		                    	$(this).removeAttr("checked");
	                    	{rdelim}
	                    	$(this).removeAttr("disabled");
		                    $(this).removeAttr("selected");
	                    	if ($(this).is(".use_predefined_locations")) {ldelim}
	                    		$(this).val(virtual_location_id);
	                    		{if $system_settings.predefined_locations_mode && ($system_settings.predefined_locations_mode == 'prefered' || $system_settings.predefined_locations_mode == 'only')}
		                    	$(this).attr("checked", "checked");
		                    	{else}
		                    	$(this).removeAttr("checked");
		                    	{/if}
	                    	{rdelim}
	                    	$(this).parent().parent().find(".manual_coords_block").hide();
	                    	// increment name and id attributes of inputs from their class
	                    	var id = $(this).attr('class')+'['+(i-1)+']';
	                    	$(this).attr('id', id);
	               		{rdelim});
	               		// Clear geocoded suggestions list for new accordion's tab 
	               		location.find('.geocoded_suggestions').html('');

	               		{if $CI->load->is_module_loaded('i18n')}
	               		///////////////////////////////////////////////////////////////////////////////////
                    	// content translation links
	               		$.post('{$VH->site_url("ajax/languages/get_translation_link/listings_in_locations/location/new/string/")}'+virtual_location_id,
	                    function(data) {ldelim}
	                    	location.find(".translate_location").html(data);
	                    {rdelim});
	                    $.post('{$VH->site_url("ajax/languages/get_translation_link/listings_in_locations/address_line_1/new/string/")}'+virtual_location_id,
	                    function(data) {ldelim}
	                    	location.find(".translate_address_line_1").html(data);
	                    {rdelim});
	                    $.post('{$VH->site_url("ajax/languages/get_translation_link/listings_in_locations/address_line_2/new/string/")}'+virtual_location_id,
	                    function(data) {ldelim}
	                    	location.find(".translate_address_line_2").html(data);
	                    {rdelim});
	                    {/if}
	                    
	                    {if $system_settings.predefined_locations_mode && ($system_settings.predefined_locations_mode == 'prefered' || $system_settings.predefined_locations_mode == 'only')}
	                    location.find(".location_drop_boxes").show();
	                    location.find(".location_string").hide();
	                    {else}
	                    location.find(".location_drop_boxes").hide();
	                    location.find(".location_string").show();
	                    {/if}
	                    location.find(".location_dropdown_list").each(function() {ldelim}
	                    	if ($(this).attr("order_num") != 1)
	                    		$(this).find("option").each(function() {ldelim}
	                    			if ($(this).val())
	                    				$(this).remove();
	                    		{rdelim});
	                    {rdelim});

                    	$("#locations_accordion").append(location);
                    	
                    	///////////////////////////////////////////////////////////////////////////////////
                    	// check icons for new location
                    	{if $listing->type->categories_type != 'disabled' && $listing->type->locations_enabled && $listing->level->locations_number && $listing->level->maps_enabled}
						$.post('{$VH->site_url("ajax/categories/is_icons")}'+typeid_url_part, {ldelim}'categories_list': PHPSerializer.serialize(categories), 'selected_icons': serializeIcons(){rdelim}, function(data) {ldelim}showHideMarkerIconsBtn(data); {rdelim}, "json");
						{/if}

                    	buildAccordionAndLocationAutocomplete();

                    	if (locations_limit <= $("#locations_accordion>div").length)
                    		$(".add_location").hide();
						
                    	return false;
                    {rdelim});

                    {if $listing->type->locations_enabled && $listing->level->locations_number}
                    ///////////////////////////////////////////////////////////////////////////////////
                    // Delete locations block from accordion
                    $(".delete_location").live('click', function() {ldelim}
                    	$('#locations_accordion').accordion('destroy');
                    	$('.location').autocomplete('destroy');
                    	$(this).parent().prev().remove();
                    	$(this).parent().remove();
                    	
                    	var i = 1;
						$(".location_number").each(function() {ldelim}
							$(this).html(i);
							i++;
						{rdelim});
						if (i == 2) {ldelim}
							$(".delete_location").hide();
						{rdelim}

						$.each($("#locations_accordion>div"), function(i, val) {ldelim}
							var locations_blocks = $("#locations_accordion>div:eq("+i+")").find('input');
	                    	locations_blocks.each( function() {ldelim}
	                    		var id = $(this).attr('class')+'['+(i+1)+']';
	                    		$(this).attr('id', id);
	               			{rdelim});
	               		{rdelim});
	               		
	               		buildAccordionAndLocationAutocomplete();
	               		
	               		$('#locations_accordion').accordion("activate", 0);
	               		
	               		{if $listing->type->locations_enabled && $listing->level->locations_number && $listing->level->maps_enabled}
	               		// Need to clear map zoom field
	               		$(".map_zoom").val('');
						generateMap();
						{/if}

						if (locations_limit > $("#locations_accordion>div").length)
                    		$(".add_location").show();

                    	return false;
                    {rdelim});
                    {/if}
                    
                    {if $listing->level->categories_number}
                    $("#suggest_category_link").click(function() {ldelim}
                    	$("#suggest_category_block").slideToggle();
                    {rdelim});
                    {/if}
                {rdelim});
                
                function buildAccordionAndLocationAutocomplete() {ldelim}
                	{if $listing->type->locations_enabled && $listing->level->locations_number}
                	$("#locations_accordion").accordion({ldelim}
						autoHeight: false,
						change: function(event, ui) {ldelim}
							// Set location ID and number of the current selected tab
							selected_location_num = $("#locations_accordion").accordion("option", "active");
							selected_location_id = $(".location_id:eq("+selected_location_num+")").val();
							$("#selected_location_num").val(selected_location_num);
						{rdelim}
					{rdelim});

					var geocoder = new google.maps.Geocoder();
					$('.location').autocomplete({ldelim}
	                	source: function(request, response) {ldelim}
	                		geocodeAddress(request.term, '{$system_settings.default_language}', response, false, false);
		                {rdelim},
		                focus: function(event, ui) {ldelim}
							$(this).val(ui.item.label);
							return false;
						{rdelim},
						select: function(event, ui) {ldelim}
							$(this).val(ui.item.label);
							return false;
						{rdelim},
						minLength: 2,
	                	delay: 600
	                {rdelim});
	                {/if}
				{rdelim}
                
                ///////////////////////////////////////////////////////////////////////////////////
                // Add category item,
                // calls from categories tree
				function addCategory(node) {ldelim}
					// Check categories count limit?
					if (selected_categories_count < categories_limit) {ldelim}
						selected_cat_id = ($(node).parent().parent().attr("id")+"").replace('category_in_listing_', '');
						cat_bool = true;
						// does it already in list?
						for (i = 0; i < categories.length; i++) {ldelim}
							if (categories[i] == selected_cat_id)
								cat_bool = false;
						{rdelim}

						if (cat_bool) {ldelim}
							ajax_loader_show("Category addition...");
							$.post('{$VH->site_url("ajax/categories/get_categories_path")}'+typeid_url_part, {ldelim}'category_id': selected_cat_id, 'categories_list': PHPSerializer.serialize(categories){rdelim}, addCategoryIntoList, "json");
						{rdelim} else {ldelim}
							alert('{addslashes string=$LANG_LISTING_DUBLE_CATEGORY_ERROR}');
						{rdelim}
					{rdelim} else {ldelim}
						alert('{addslashes string=$LANG_LISTING_CATEGORY_LIMIT_ERROR}');
					{rdelim}
					return false;
				{rdelim}
				
				///////////////////////////////////////////////////////////////////////////////////
				// Adds category into html list
				function addCategoryIntoList(data) {ldelim}
					$('<div id="category_' + data.selected_cat_id + '" class="categories_item" unselectable="on">' + data.selected_cat_name + '<'+'/div>').appendTo("#selected_categories_list");
					categories[data.selected_cat_id] = data.selected_cat_id;
					selected_categories_count++;
					serializeCategories();
					{if $listing->type->locations_enabled && $listing->level->locations_number && $listing->level->maps_enabled}
					showHideMarkerIconsBtn(data);
					generateMap();
					{/if}
					ajax_loader_hide();
				{rdelim}
				
				function showHideMarkerIconsBtn(data) {ldelim}
					{if $listing->type->locations_enabled && $listing->level->locations_number && $listing->level->maps_enabled}
					var is_selected_icons = PHPSerializer.unserialize(data.is_selected_icons);
					for (var i=0; i<is_selected_icons.length; i++) {ldelim}
						if ($(".map_icon_id:eq("+i+")").val() && !is_selected_icons[i]) {ldelim}
							$(".map_icon_id:eq("+i+")").val('');
							$(".map_icon_file:eq("+i+")").val('');
						{rdelim}
					{rdelim}
					if (!data.is_icons) {ldelim}
						$(".icons_btn").hide();
						$(".map_icon_id").val('');
						$(".map_icon_file").val('');
					{rdelim} else {ldelim}
						$(".icons_btn").show();
					{rdelim}
					if (data.single_icon) {ldelim}
						$(".map_icon_id").val(data.single_icon);
						$(".map_icon_file").val(data.single_icon_file);
						$("#single_icon").val(true);
					{rdelim} else {ldelim}
						$("#single_icon").val(false);
					{rdelim}
					{/if}
				{rdelim}

                function serializeCategories() {ldelim}
                    categories = jQuery.grep(categories, function(value) {ldelim}
                    	return value != undefined;
                    {rdelim});
                    serialized_categories = categories.join(',');
                    if (categories.length)
                    	$('#serialized_categories_list').attr('value', PHPSerializer.serialize(categories));
                    else
                    	$('#serialized_categories_list').attr('value', '');
                {rdelim}
                
                function serializeIcons() {ldelim}
                	var icons = new Array();
                	$.each($(".map_icon_id"), function(index, value) {ldelim}
                		icons[index] = $(this).val();
                	{rdelim});
                	return PHPSerializer.serialize(icons);
                {rdelim}
                --></script>

                <div class="content">
                	{$VH->validation_errors()}
                	{if $listing_id == 'new'}
                    <h3>{$LANG_CREATE_LISTING_1} "{$type_name}" {$LANG_CREATE_LISTING_2} "{$level_name}"</h3>
                    <h4>{$LANG_CREATE_STEP2}</h4>
                    {else}
                    <h3>{$LANG_EDIT_LISTING_1} "{$type_name}" {$LANG_EDIT_LISTING_2} "{$level_name}"</h3>
                    {/if}

                    {if $listing->id != 'new'}
                    {include file="listings/admin_listing_options_menu.tpl"}
                    {/if}

                    <form action="" method="post">
                    <input type="hidden" name="last_page" value="{$last_page}">
                    <input type="hidden" name="id" value="{$listing->id}">
                    <input type="hidden" id="serialized_categories_list" name="serialized_categories_list">
                    
                    {if $content_access_obj->isPermission('Manage ability to claim')}
                    <label class="block_title">{$LANG_LISTING_ABILITY_TO_CLAIM}</label>
					<div class="admin_option">
						{if !$listing->claim_row || !$listing->claim_row.approved}
							{if !$listing->claim_row || $listing->claim_row.ability_to_claim}
								<label><input type="checkbox" value="1" name="ability_to_claim" {if $listing->claim_row.ability_to_claim}checked{/if} /> {$LANG_LISTING_ABILITY_TO_CLAIM_DESCR}</label>
							{elseif $listing->claim_row.to_user_id && $listing->claim_user}
								{assign var=user_id value=$listing->claim_user->id}
								{$LANG_LISTING_CLAIMED_BY} <a href="{$VH->site_url("admin/users/view/$user_id")}">{$listing->claim_user->login}</a><br />
								<a href="{$VH->site_url("admin/listings/approve_claim/$listing_id")}"><img src="{$public_path}images/icons/accept.png" /> {$LANG_LISTING_CLAIME_APPROVE}</a> <a href="{$VH->site_url("admin/listings/decline_claim/$listing_id")}"><img src="{$public_path}images/icons/delete.png" /> {$LANG_LISTING_CLAIME_DECLINE}</a>
							{/if}
						{elseif $listing->claim_user}
							{$LANG_LISTING_CLAIME_DELEGATED_TO} {$listing->claim_user->login} (<a href="{$VH->site_url("admin/listings/decline_claim/$listing_id")}"><img src="{$public_path}images/icons/delete.png" /> {$LANG_LISTING_CLAIM_ROLL_BACK}</a>)
						{/if}
                    </div>
					{/if}
                    
                    {if $listing->level->title_enabled || $listing->level->seo_title_enabled || $listing->level->description_mode != 'disabled' || $listing->level->meta_enabled}
					<label class="block_title">{$LANG_LISTING_META_INFO}</label>
					<div class="admin_option">
						{if $listing->level->title_enabled}
						<div style="float: left;">
							<div class="admin_option_name">
								{$LANG_LISTING_TITLE}<span class="red_asterisk">*</span>
								{translate_content table='listings' field='title' row_id=$listing_id}
							</div>
							<div class="admin_option_description">
								{$LANG_LISTING_TITLE_DESCR}
							</div>
							<input type=text name="name" value="{$listing->title()}" size="45">
							{if $listing->level->seo_title_enabled}
							&nbsp;&nbsp;<span class="seo_rewrite" title="{$LANG_WRITE_SEO_STYLE}"><img src="{$public_path}images/arrow_seo.gif"></span>&nbsp;&nbsp;
							{/if}
						</div>
						{/if}
						{if $listing->level->seo_title_enabled}
						<div style="float: left;">
							<div class="admin_option_name">
								{$LANG_LISTING_SEO_TITLE}
							</div>
							<div class="admin_option_description">
								{$LANG_SEO_DESCR}
							</div>
							<input type=text name="seo_name" id="seo_name" value="{$listing->seo_title}" size="45">
						</div>
						{/if}
						<div style="clear: both"></div>

						<div class="px10"></div>

						{if $listing->level->description_mode != 'disabled'}
							<div class="admin_option_name">
								{$LANG_LISTING_DESCRIPTION}
								{translate_content table='listings' field='listing_description' row_id=$listing_id field_type='text'}
							</div>
						    {if $listing->level->description_mode == 'enabled'}
							    <div class="admin_option_description">
									{$LANG_LISTING_DESCRIPTION_DESCR} {$LANG_SYMBOLS_LEFT}: <span id="listing_description_symbols_left" class="symbols_left"></span>
							    </div>
								<textarea name="listing_description" id="listing_description" cols="70" rows="8">{$listing->listing_description}</textarea>
							{/if}
							{if $listing->level->description_mode == 'richtext'}
								{$listing->description()}
							{/if}
							<div class="px10"></div>
						{/if}

						{if $listing->level->meta_enabled}
						<div class="admin_option_name">
							{$LANG_LISTING_META_DESCRIPTION}
							{translate_content table='listings' field='listing_meta_description' row_id=$listing_id field_type='text'}
						</div>
						<div class="admin_option_description">
							{$LANG_LISTING_META_DESCRIPTION_DESCR}
						</div>
						<textarea name="listing_meta_description" cols="40" rows="5">{$listing->listing_meta_description}</textarea>
						
						<div class="px10"></div>
						
						<div class="admin_option_name">
							{$LANG_LISTING_KEYWORDS}
							{translate_content table='listings' field='listing_keywords' row_id=$listing_id field_type='keywords'}
						</div>
						<div class="admin_option_description">
							{$LANG_LISTING_KEYWORDS_DESCR}
						</div>
						<textarea name="listing_keywords" cols="40" rows="5">{$VH->str_replace(", ", "\n", $listing->listing_keywords)}</textarea>
						{/if}
					</div>
					{/if}
					
					{if $content_access_obj->isPermission('Edit listings expiration date')}
					<label class="block_title">{$LANG_EXPIRATION_DATE}</label>
					<div class="admin_option">
						{if !$listing->level->eternal_active_period || $listing->level->allow_to_edit_active_period}
							<div class="admin_option_description">
								{$LANG_EXPIRATION_DATE_DESCR}
							</div>
	                        <input type="text" size="10" value="" name="expiration_date" id="expiration_date"/>
                        {else}
                        	<span class="green">{$LANG_ETERNAL}</span>
                        {/if}
                     </div>
					{/if}

                    {if $listing->level->logo_enabled}
                    {$image_upload_block->setUploadBlock('files_upload/listing_logo_upload_block.tpl')}
                    {/if}

                    {if $listing->type->categories_type != 'disabled' && $listing->level->categories_number}
                    <label class="block_title">{$LANG_LISTING_CATEGORIES}</label>
                    <div class="admin_option">
                        <div class="admin_option_name">
                        	{$LANG_LISTING_CATEGORIES_TREE}
                        </div>
                        <div class="admin_option_description">
                        	{$LANG_LISTING_CATEGORIES_DESCRIPTION} <b>{$listing->level->categories_number}</b>.
                        </div>
                        {render_frontend_block
							block_type='categories'
							block_template='backend/blocks/admin_categories_in_listings.tpl'
							is_counter=false
							type=$listing->type->id
							max_depth='max'
						 }

                        <div class="px10"></div>

                        <div class="admin_option_name">
                        	{$LANG_LISTING_SELECTED_CATEGORIES}<span class="red_asterisk">*</span>
                        </div>
                        <div class="admin_option_description">
                        	{$LANG_LISTING_SELECTED_CATEGORIES_DESCR}
                        </div>
                        <div id="selected_categories_list">
                        	{foreach from=$listing->categories_array() item=category}
                        	<div id="category_{$category->id}" class="categories_item" unselectable="on">
                        		{$category->getChainAsString()}
                        	</div>
                        	{/foreach}
                        </div>
                        <br />
                        <input type="button" class="button delete_button" id="delete_category_from_list" value="{$LANG_BUTTON_DELETE_FROM_LIST}">

                        <div class="px10"></div>

                        <a href="{$VH->site_url('ajax/categories/send_suggestion/')}" class="nyroModal noborder" title="{$LANG_SUGGEST_CATEGORY}">{$LANG_SUGGEST_CATEGORY}</a>
                    </div>
                    {/if}

                    {if $listing->type->locations_enabled && $listing->level->locations_number}
                    <input type="hidden" id="sssssss" value="1">
                    <input type="hidden" id="selected_location_num" value="0">
                    <label class="block_title">{$LANG_LISTING_LOCATIONS}</label>
                    <div class="admin_option">
                        <div class="px10"></div>
                        <div id="locations_accordion">
                        {assign var="i" value=1}
                        {foreach from=$listing->locations_array() item=location}
                        	<!-- location_number - position required for jquery selector -->
	                        <h3><a href="javascript: void(0);">{$LANG_LISTING_ADDRESS} <span class="location_number">{$i++}</span></a></h3>
	                        <div class="location_block">
	                        	<!-- location_id - position required for jquery selector -->
	                        	{if $location->id != 'new'}
	                        	<input type="hidden" name="location_id[]" class="location_id" value="{$location->id}">
	                        	{else}
		                        <input type="hidden" name="location_id[]" class="location_id" value="{$location->virtual_id}">
		                        {/if}

		                        <div>
									{if $system_settings.predefined_locations_mode && $system_settings.predefined_locations_mode != 'disabled'}
			                        <div class="location_drop_boxes" {if !$location->use_predefined_locations}style="display:none"{/if}>
			                        	{$location->renderDropBoxes()}
			                        	<div class="px4"></div>
			                        </div>
			                        {/if}

			                        {if !$system_settings.predefined_locations_mode || ($system_settings.predefined_locations_mode && $system_settings.predefined_locations_mode != 'only')}
									<div class="location_string" {if $location->use_predefined_locations}style="display:none"{/if}>
				                        <div class="admin_option_description">
				                        	{$LANG_LISTING_LOCATION_DESCR}
				                        	{if $location->id != 'new'}
				                        	<span class="translate_location">{translate_content table='listings_in_locations' field='location' row_id=$location->id}</span>
				                        	{else}
				                        	<span class="translate_location">{translate_content table='listings_in_locations' field='location' row_id='new' virtual_id=$location->virtual_id}</span>
				                        	{/if}
				                        </div>
				                        <input type="text" size="50" name="location[]" class="location" value="{$location->location}" />
				                        <br />
				                        <div class="geocoded_suggestions" style="display: none;"></div>
				                        <input type="hidden" name="geocoded_name[]" class="geocoded_name" value="{$location->geocoded_name}">
				                        <div class="px4"></div>
			                        </div>
			                        {if $system_settings.predefined_locations_mode && $system_settings.predefined_locations_mode != 'disabled' && $system_settings.predefined_locations_mode != 'only'}
			                        <label>
		                        		<img src="{$public_path}images/buttons/map_magnify.png" />&nbsp;
			                        	<input type="checkbox" name="use_predefined_locations[]" value="{if $location->id != 'new'}{$location->id}{else}{$location->virtual_id}{/if}" class="use_predefined_locations" {if $location->use_predefined_locations}checked{/if}>&nbsp;{$LANG_USE_PREDEFINED_LOCATIONS}
			                        </label>
			                        <div class="px10"></div>
			                        {/if}
			                        {/if}
								</div>

		                        <div class="admin_option_description">
		                        	{$LANG_ADDRESS_LINE1_1} &nbsp;<i>({$LANG_ADDRESS_LINE1_2})</i>
		                        	{if $location->id != 'new'}
		                        	<span class="translate_address_line_1">{translate_content table='listings_in_locations' field='address_line_1' row_id=$location->id}</span>
		                        	{else}
		                        	<span class="translate_address_line_1">{translate_content table='listings_in_locations' field='address_line_1' row_id='new' virtual_id=$location->virtual_id}</span>
		                        	{/if}
		                        </div>
		                        <input type="text" size="50" name="address_line_1[]" class="address_line_1" value="{$location->address_line_1}">
		                        <div class="px4"></div>
		
		                        <div class="admin_option_description">
		                        	{$LANG_ADDRESS_LINE2_1} &nbsp;<i>({$LANG_ADDRESS_LINE2_2})</i>
		                        	{if $location->id != 'new'}
		                        	<span class="translate_address_line_2">{translate_content table='listings_in_locations' field='address_line_2' row_id=$location->id}</span>
		                        	{else}
		                        	<span class="translate_address_line_2">{translate_content table='listings_in_locations' field='address_line_2' row_id='new' virtual_id=$location->virtual_id}</span>
		                        	{/if}
		                        </div>
		                        <input type="text" size="50" name="address_line_2[]" class="address_line_2" value="{$location->address_line_2}">
		                        <div class="px4"></div>
		
		                        {if $listing->type->zip_enabled}
		                        <div class="admin_option_description">
		                        	{$LANG_LISTING_ZIP}
		                        </div>
		                        <input type="text" size="50" name="zip_or_postal_index[]" class="zip_or_postal_index" value="{$location->zip_or_postal_index}">
		                        <div class="px5"></div>
		                        {/if}
		                        {if $listing->level->maps_enabled}
		                        <!-- map_zoom - required in google_maps.js -->
		                        <input type="hidden" name="map_zoom[]" class="map_zoom" value="{$location->map_zoom}">
		                        <a href="javascript: void(0);" onClick="$.jqURL.loc(select_icons_window_url+serialized_categories+typeid_url_part, {ldelim}w:750,h:620,wintype:'_blank'{rdelim}); return false;" class="icons_btn"><img src="{$public_path}images/buttons/marker_pencil.png" /></a>
		                        <a href="javascript: void(0);" onClick="$.jqURL.loc(select_icons_window_url+serialized_categories+typeid_url_part, {ldelim}w:750,h:620,wintype:'_blank'{rdelim}); return false;" class="icons_btn">{$LANG_BUTTON_MARKER_ICON}</a>
		                        <div class="px4"></div>
		                        <label>
		                        	<img src="{$public_path}images/buttons/map_edit.png" />&nbsp;
		                        	<!-- manual_coords - required in google_maps.js -->
		                        	<input type="checkbox" name="manual_coords[]" value="{if $location->id != 'new'}{$location->id}{else}{$location->virtual_id}{/if}" class="manual_coords" {if $location->manual_coords}checked{/if}>&nbsp;{$LANG_ENTER_LTLG_MANUALLY}
		                        </label>
		                        <!-- manual_coords_block - position required for jquery selector -->
		                        <div class="admin_option manual_coords_block" {if !$location->manual_coords}style="display: none;"{/if} style="margin:0">
		                        	<div class="admin_option_description">
			                        	{$LANG_MAP_LATITUDE}
			                        </div>
			                        <!-- map_coords_1 - required in google_maps.js -->
		                        	<input type="text" name="map_coords_1[]" class="map_coords_1" value="{$location->map_coords_1}">
		                        	<div class="admin_option_description">
			                        	{$LANG_MAP_LONGITUDE}
			                        </div>
			                        <!-- map_coords_2 - required in google_maps.js -->
		                        	<input type="text" name="map_coords_2[]" class="map_coords_2" value="{$location->map_coords_2}">
		                        </div>
		                        <!-- map_icon_id, map_icon_file - required in map_icons_for_listings.js -->
		                        <input type="hidden" name="map_icon_id[]" id="map_icon_id[{$i-1}]" class="map_icon_id" value="{$location->map_icon_id}">
		                        <input type="hidden" name="map_icon_file[]" id="map_icon_file[{$i-1}]" class="map_icon_file" value="{$location->map_icon_file}">
		                        <div class="px4"></div>
		                        {/if}
		                        <a href="javascript: void(0);" style="display:none;" class="delete_location"><img src="{$public_path}images/buttons/map_delete.png" /></a>
		                        <a href="javascript: void(0);" style="display:none;" class="delete_location">{$LANG_DELETE_ADDRESS}</a>
	                        </div>
	                    {/foreach}
                        </div>

                        <div class="px10"></div>
                        <a href="javascript: void(0);" class="add_location" {if $listing->level->locations_number <= $listing->locations_array()|@count}style="display:none"{/if}><img src="{$public_path}images/buttons/map_add.png" /></a>
                        <a href="javascript: void(0);" class="add_location" {if $listing->level->locations_number <= $listing->locations_array()|@count}style="display:none"{/if}>{$LANG_ADD_ADDRESS}</a> 
                        <div class="px10 add_location" {if $listing->level->locations_number <= $listing->locations_array()|@count}style="display:none"{/if}></div>
                        {if $listing->level->maps_enabled}
                        <input type="button" onClick="generateMap(); return false;" name="maps_btn" class="button maps_button" value="{$LANG_BUTTON_MAP}">
                        <div class="px10"></div>
                        <input type="hidden" name="single_icon" id="single_icon" value="false">
                        <div id="maps_canvas" class="maps_canvas" style="width: {$listing->level->explodeSize('maps_size', 'width')}px; height: {$listing->level->explodeSize('maps_size', 'height')}px"></div>
                        {/if}
                    </div>
                    {/if}

                    {if $listing->content_fields->fieldsCount()}
                    <label class="block_title">{$LANG_LISTING_ADDITIONAL_INFORMATION}</label>
                    <div class="admin_option">
                    	{$listing->inputMode()}
                    </div>
                    {/if}

                    {if $listing->id == 'new'}
                    <input type=submit name="submit" class="button create_button" value="{$LANG_BUTTON_CREATE_LISTING}">
                    {else}
                    <input type=submit name="submit" class="button save_button" value="{$LANG_BUTTON_SAVE_CHANGES}">
                    {/if}
                    </form>
                </div>

{include file="backend/admin_footer.tpl"}