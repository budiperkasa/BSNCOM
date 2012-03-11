{if (!$current_type) || ($current_type && $current_type->search_type != 'disabled')}
				<script language="javascript" type="text/javascript">
                // Global url string
                {if $base_url}
                	var global_js_url = '{$base_url}';
                {else}
                	var global_js_url = '{$VH->site_url("search/")}';
                {/if}
                
                // Command variable, needs for delete, block listings buttons
                var action_cmd;
                
                // Is advanced search in use? Attach to global_js_url
                var use_advanced = '';
                
                // Was advanced search block loaded?
                var advanced_loaded = false;

                // Scripts code array - will be evaluated when advanced search attached
                var scripts = new Array();
                
                var default_what = '{addslashes string=$LANG_SEARCH_WHAT} ({addslashes string=$LANG_SEARCH_WHAT_DESCR})';
                var default_where = '{addslashes string=$LANG_SEARCH_WHERE} ({addslashes string=$LANG_SEARCH_WHERE_DESCR})';
                
                var use_districts = {$system_settings.geocoded_locations_mode_districts};
                var use_provinces = {$system_settings.geocoded_locations_mode_provinces};

                var ajax_autocomplete_request = '{$VH->site_url("ajax/locations/autocomplete_request/")}';

                $(document).ready(function() {ldelim}
                	{if (($system_settings.single_type_structure || !$current_type) && $system_settings.global_where_search) || (!$system_settings.single_type_structure && $current_type && $current_type->where_search && $current_type->locations_enabled)}
                		$('#where_search').autocomplete({ldelim}
			               	source: function(request, response) {ldelim}
			               		{if $system_settings.predefined_locations_mode != 'only'}
			               			{if $system_settings.predefined_locations_mode != 'disabled'}
			               				// geocode + ajax from DB
			               				geocodeAddress(request.term, '{$system_settings.default_language}', response, ajax_autocomplete_request, false);
			               			{else}
			               				// only geocode
			               				geocodeAddress(request.term, '{$system_settings.default_language}', response, false, false);
			               			{/if}
			               		{elseif $system_settings.predefined_locations_mode != 'disabled'}
			               			// only ajax from DB
			               			$.post(ajax_autocomplete_request, {ldelim}query: request.term{rdelim}, function(data) {ldelim}
				               			if (data = JSON.parse(data))
				               				response(data);
				               		{rdelim});
			               		{/if}
							{rdelim},
							focus: function(event, ui) {ldelim}
								$(this).val(ui.item.label);
								return false;
							{rdelim},
							select: function(event, ui) {ldelim}
								$(this).val(ui.item.label);
								if (ui.item.label != ui.item.value)
									$("#predefined_location_id").val(ui.item.value);
								return false;
							{rdelim},
							minLength: 2,
							delay: 600
						{rdelim});
                		$('#where_search').keyup(function() {ldelim}
                			$("#predefined_location_id").val('');
                		{rdelim});
		                $('#where_radius_slider').slider({ldelim}
							min: 0,
							max: 10,
{assign var=default_radius value=0}
							range: "min",
							value: $("#where_radius_label").val(),
							slide: function(event, ui) {ldelim}
								$("#where_radius_label").val(ui.value);
								$("#where_radius").val(ui.value);
							{rdelim}
						{rdelim});
						if ($("#where_search").val() == default_where)
							$("#where_search").addClass("highlight_search_input");
					{/if}

					{if (($system_settings.single_type_structure || !$current_type) && $system_settings.global_categories_search) || (!$system_settings.single_type_structure && $current_type && $current_type->categories_search)}
	                $("#search_by_category_tree").bind("change_state.jstree", function (event, data) {ldelim}
						var categories_list = [];
						$.each(data.inst.get_checked(), function() {ldelim}
							categories_list.push($(this).attr('id').replace("search_", ""));
						{rdelim});
						$("#search_category").val(categories_list.join(','));
						return false;
					{rdelim});

	                $("#search_category_button, #search_by_category_tree").hover(
	                	function() {ldelim}
		                	$("#search_by_category_tree").show();
		                	var pos = $("#search_category_button").offset();
		                	$("#search_by_category_tree").css({ldelim}'top': parseInt(pos.top)+parseInt(14)+'px', 'left': parseInt(pos.left)+parseInt(5)+'px'{rdelim});
		                	return false;
	                	{rdelim},
	                	function() {ldelim}
	                		$("#search_by_category_tree").hide();
	                	{rdelim}
	                );
	                {/if}

	                {if (($system_settings.single_type_structure || !$current_type) && $system_settings.global_what_search) || (!$system_settings.single_type_structure && $current_type && $current_type->what_search)}
					$("#what_search").blur( function() {ldelim}
						if ($(this).val() == '' )
							$(this).val(default_what).addClass('highlight_search_input');
					{rdelim});
					$("#what_search").focus( function() {ldelim}
						if ($(this).val() == default_what)
							$(this).val('').removeClass('highlight_search_input');
					{rdelim});

					var what_advanced_opened = false;
					$("#what_match").click( function() {ldelim}
						$("#what_advanced_block").slideToggle(200);
						if (what_advanced_opened) {ldelim}
							$("#what_match img").attr('src', '{$public_path}images/icons/add.png');
							what_advanced_opened = false;
						{rdelim} else {ldelim}
							$("#what_match img").attr('src', '{$public_path}images/icons/delete.png');
							what_advanced_opened = true;
						{rdelim}
						return false;
					{rdelim});

					if ($("#what_search").val() == default_what)
						$("#what_search").addClass("highlight_search_input");
					{/if}

					{if (($system_settings.single_type_structure || !$current_type) && $system_settings.global_where_search) || (!$system_settings.single_type_structure && $current_type && $current_type->where_search && $current_type->locations_enabled)}
					$("#where_search").blur( function() {ldelim}
						if ($(this).val() == '' )
							$(this).val(default_where).addClass('highlight_search_input');
					{rdelim});
					$("#where_search").focus( function() {ldelim}
						if ($(this).val() == default_where)
							$(this).val('').removeClass('highlight_search_input');
					{rdelim});
					
					var where_advanced_opened = false;
					$("#where_advanced").click( function() {ldelim}
						$("#where_advanced_block").slideToggle(200);
						if (where_advanced_opened) {ldelim}
							$("#where_advanced img").attr('src', '{$public_path}images/icons/add.png');
							where_advanced_opened = false;
						{rdelim} else {ldelim}
							$("#where_advanced img").attr('src', '{$public_path}images/icons/delete.png');
							where_advanced_opened = true;
						{rdelim}
						return false;
					{rdelim});
					{/if}
					
					// Form submit event
					$("#search_form").submit( function() {ldelim}
						{if (($system_settings.single_type_structure || !$current_type) && $system_settings.global_what_search) || (!$system_settings.single_type_structure && $current_type && $current_type->what_search)}
						if ($("#what_search").val() != '' && $("#what_search").val() != default_what) {ldelim}
                			global_js_url = global_js_url + 'what_search/' + urlencode($("#what_search").val()) + '/';
                			global_js_url = global_js_url + "what_match" + '/' + $("input[name=what_match]:checked").val() + '/';
                		{rdelim}
                		{/if}

                		{if (($system_settings.single_type_structure || !$current_type) && $system_settings.global_where_search) || (!$system_settings.single_type_structure && $current_type && $current_type->where_search && $current_type->locations_enabled)}
                		if ($("#where_search").val() != '' && $("#where_search").val() != default_where) {ldelim}
                			global_js_url = global_js_url + 'where_search/' + urlencode($("#where_search").val()) + '/';
                			if (parseInt($("#where_radius").val())>0) {ldelim}
                				global_js_url = global_js_url + 'where_radius/' + $("#where_radius").val() + '/';
                			{rdelim}
                		{rdelim}
                		if ($("#predefined_location_id").val() != '') {ldelim}
                			global_js_url = global_js_url + 'predefined_location_id/' + urlencode($("#predefined_location_id").val()) + '/';
                		{rdelim}
                		{/if}

                		{if !$system_settings.single_type_structure}
                		if ($("#search_type").val() != '0') {ldelim}
                			global_js_url = global_js_url + 'search_type/' + $("#search_type").val() + '/';
                		{rdelim}
    					{/if}
                		
                		{if (($system_settings.single_type_structure || !$current_type) && $system_settings.global_categories_search) || (!$system_settings.single_type_structure && $current_type && $current_type->categories_search)}
                		if ($("#search_category").val() != '') {ldelim}
                			global_js_url = global_js_url + 'search_category/' + $("#search_category").val() + '/';
                		{rdelim}
                		{/if}

                		global_js_url = global_js_url + use_advanced;
                		window.location.href = global_js_url;
						return false;
					{rdelim});

					{if $advanced_search_fields->fieldsCount() || $content_access_obj->isPermission('Manage all listings')}
					var advanced_opened = false;
					$(".advanced_search").bind('click', function() {ldelim}
						if (advanced_opened) {ldelim}
							$(".advanced_search img").attr('src', '{$public_path}images/icons/add.png');
							$("#advanced_search_block").hide();
							advanced_opened = false;
							use_advanced = '';
						{rdelim} else {ldelim}
							$(".advanced_search img").attr('src', '{$public_path}images/icons/delete.png');
							$("#advanced_search_block").show();
							advanced_opened = true;

							if (!advanced_loaded) {ldelim}
								ajax_loader_show();
								$.post('{$VH->site_url("ajax/listings/build_advanced_search/")}', {ldelim}type_id: $("#search_type").val(), args: '{addslashes string=$VH->json_encode($args)}'{rdelim},
									function(data) {ldelim}
										$("#advanced_search_block").html(parseScript(data));
										evalScripts(data);
										advanced_loaded = true;
										ajax_loader_hide();
									{rdelim}
								);
							{rdelim}
							use_advanced = 'use_advanced/true/';
						{rdelim}
						return false;
					{rdelim});
					{if $args.use_advanced}
					$('.advanced_search').triggerHandler('click');
					{/if}
					{/if}
                {rdelim});
                </script>

                	 <div class="px5"></div>
                     <form id="search_form" action="" method="post">
                     	{if !$system_settings.single_type_structure && $current_type && $current_type->categories_type == 'local'}
                     	<input type="hidden" id="search_type" name="search_type" value="{$current_type->id}" />
                     	{else}
						<input type="hidden" id="search_type" name="search_type" value="0" />
						{/if}
                     	<div>
                     		{if (($system_settings.single_type_structure || !$current_type) && $system_settings.global_categories_search) || (!$system_settings.single_type_structure && $current_type && $current_type->categories_search)}
	                     		<div class="search_filter_standart">
		                     		<input type="hidden" id="search_category" value="{$args.search_category}">
									<div id="search_category_button">
			                     		<a class="search_category"><img src="{$public_path}images/icons/chart_organisation.png"></a> <a class="search_category">{$LANG_SEARCH_CATEGORIES}</a>
			                     	</div>
			                    </div>

								{if $system_settings.categories_block_type == 'categories-bar-ajax'}
									{assign var=categories_search_template value='categories_search_block_ajax.tpl'}
								{else}
									{assign var=categories_search_template value='categories_search_block.tpl'}
								{/if}
								{render_frontend_block
									block_type='categories'
									block_template="frontend/blocks/"|cat:$categories_search_template
									type=$current_type->id
									search_categories_array=$search_categories_array
									is_counter=false
									max_depth=2
								}
			                {/if}
                     	
                     		{if (($system_settings.single_type_structure || !$current_type) && $system_settings.global_what_search) || (!$system_settings.single_type_structure && $current_type && $current_type->what_search)}
                     		<div class="search_filter_standart">
                     			<div>
                     				<a href="javascript: void(0);" id="what_match"><img style="padding-top: 3px;" src="{$public_path}images/icons/add.png"></a> <input type="text" class="what_where_search_input" name="what_search" id="what_search" value="{if $args.what_search}{$args.what_search}{else}{$LANG_SEARCH_WHAT} ({$LANG_SEARCH_WHAT_DESCR}){/if}" />
                     			</div>
                     			<div id="what_advanced_block" style="display: none;">
                     				<label><input type="radio" name="what_match" value="any" {if $args.what_match == 'any' || !$args[$field_mode]}checked{/if} /> {$LANG_ANY_MATCH}</label>
	                     			<label><input type="radio" name="what_match" value="exact" {if $args.what_match == 'exact'}checked{/if} /> {$LANG_EXACT_MATCH}</label>
	                     		</div>
                     		</div>
                     		{/if}
                     		
                     		{if (($system_settings.single_type_structure || !$current_type) && $system_settings.global_where_search) || (!$system_settings.single_type_structure && $current_type && $current_type->where_search && $current_type->locations_enabled)}
                     		<div class="search_filter_standart">
                     			<div>
	                     			<a href="javascript: void(0);" id="where_advanced"><img style="padding-top: 3px;" src="{$public_path}images/icons/add.png"></a>
	                     			<input type="text" class="what_where_search_input" name="where_search" id="where_search" value="{if $args.where_search}{$args.where_search}{elseif $current_location}{$current_location->getChainAsString()}{else}{$LANG_SEARCH_WHERE} ({$LANG_SEARCH_WHERE_DESCR}){/if}" />
	                     			<input type="hidden" name="predefined_location_id" id="predefined_location_id" value="{if $args.predefined_location_id}{$args.predefined_location_id}{/if}" />
	                     		</div>
                     			<div id="where_advanced_block" style="display: none;">
                     				<div class="px5"></div>
                     				{$LANG_SEARCH_IN_RADIUS}
									<input type="text" id="where_radius_label" value="{if $args.where_radius}{$args.where_radius}{else}{$default_radius}{/if}" size="1" maxlength="2" disabled />
									<input type="hidden" name="where_radius" id="where_radius" value="{if $args.where_radius}{$args.where_radius}{else}{$default_radius}{/if}" />
									{if $system_settings.search_in_raduis_measure == 'miles'}
										{$LANG_SEARCH_IN_RADIUS_MILES}
									{else}
										{$LANG_SEARCH_IN_RADIUS_KILOMETRES}
									{/if}
									<div id="where_radius_slider"></div>
	                     		</div>
                     		</div>
                     		{/if}

	                     	<div id="search_button">
                     			<input type="submit" name="submit" class="front-btn" value="{$LANG_BUTTON_SEARCH_LISTINGS}" />
                     		</div>
                     		
                     		{if $advanced_search_fields->fieldsCount() || $content_access_obj->isPermission('Manage all listings')}
                     		<div class="search_filter_standart">
	                     		<div id="advanced_search_button">
	                     			<a href="javascript: void(0);" class="toggle advanced_search"><img src="{$public_path}images/icons/add.png"></a> <a href="javascript: void(0);" class="toggle advanced_search">{$LANG_ADVANCED_SEARCH}</a>
	                     		</div>
	                     	</div>
	                     	{/if}
	                     	<div class="clear_float"></div>
	                    </div>
	                     	
                     	<div class="search_block">
                     		{$search_fields->inputMode($args)}
                     	</div>
                     	<div class="clear_float"></div>
                     	<div id="advanced_search_block" style="display: none;"></div>
                     	<div class="clear_float"></div>
                     </form>
{/if}

{if (!$current_type) || ($current_type && $current_type->locations_enabled)}
	<div class="px5"></div>
	{render_frontend_block
		block_type='locations'
		block_template='frontend/blocks/locations_navigation.tpl'
		is_counter=true
		is_only_labeled=true
		max_depth=3
	}
{/if}

<div class="px5"></div>