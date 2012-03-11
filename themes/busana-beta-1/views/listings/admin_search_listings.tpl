{include file="backend/admin_header.tpl"}

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
                	{if (!$current_type) || ($current_type && $current_type->search_type != 'disabled')}
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

				               		/*{if $system_settings.predefined_locations_mode != 'only'}
				               			geocodeAddress(request.term, '{$system_settings.default_language}', response);
				               		{elseif $system_settings.predefined_locations_mode != 'disabled'}
					               		$.post('{$VH->site_url("ajax/locations/autocomplete_request/")}', {ldelim}query: request.term{rdelim}, function(data) {ldelim}
					               			if (data = JSON.parse(data))
					               				response(data);
					               		{rdelim});
				               		{/if}*/
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
			                $('#where_radius_slider').slider({ldelim}
								min: 0,
								max: 10,
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
	                			if (where_advanced_opened && parseInt($("#where_radius").val())>0) {ldelim}
	                				global_js_url = global_js_url + 'where_radius/' + $("#where_radius").val() + '/';
	                			{rdelim}
	                		{rdelim}
	                		{/if}
	                		
	                		{if (($system_settings.single_type_structure || !$current_type) && $system_settings.global_categories_search) || (!$system_settings.single_type_structure && $current_type && $current_type->categories_search)}
	                		if ($("#search_category").val() != '') {ldelim}
	                			global_js_url = global_js_url + 'search_category/' + $("#search_category").val() + '/';
	                		{rdelim}
	                		{/if}
	                		
	                		if ($("input[name=search_claimed_listings]:checked").val() != 'any') {ldelim}
	                			global_js_url = global_js_url + 'search_claimed_listings/' + $("input[name=search_claimed_listings]:checked").val() + '/';
	                		{rdelim}
	
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
									$.post('{$VH->site_url("ajax/listings/build_advanced_search/")}', {ldelim}type_id: $("#search_type").val(), args: '{$VH->json_encode($args)}'{rdelim}, 
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
					{/if}
					
					$("#search_type").change( function() {ldelim}
						if ($(this).val() != 0)
							global_js_url = '{$clean_url}search_type_id/' + $(this).val() + '/';
						else
							global_js_url = '{$clean_url}';
						window.location.href = global_js_url;
					{rdelim});
                {rdelim});

                function submit_listings_form()
                {ldelim}
                	$("#listings_form").attr("action", '{$VH->site_url('admin/listings/')}' + action_cmd + '/');
                	return true;
                {rdelim}
                </script>

                <div class="content">
                     <h3>{$LANG_SEARCH_LISTINGS}</h3>

                     <form id="search_form" action="" method="post">
	                    {if !$system_settings.single_type_structure}
	                    <div class="search_filter_standart">
		                    {$LANG_SEARCH_BY_TYPE}:
		                    <select id="search_type" name="search_type">
								<option value="0">- - - {$LANG_SEARCH_FOR_ALL_TYPES} - - -</option>
								{foreach from=$types item=type}
								<option value="{$type->id}" {if $args.search_type_id == $type->id}selected{/if}>{$type->name}</option>
								{/foreach}
							</select>
						</div>
						<div class="clear_float"></div>
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
	                     		</div>
                     			<div id="where_advanced_block" style="display: none;">
                     				<div class="px5"></div>
                     				{$LANG_SEARCH_IN_RADIUS}
									<input type="text" id="where_radius_label" value="{if $args.where_radius}{$args.where_radius}{else}0{/if}" size="1" maxlength="2" disabled />
									<input type="hidden" name="where_radius" id="where_radius" value="{if $args.where_radius}{$args.where_radius}{else}0{/if}" />
									{if $search_in_raduis_measure == 'miles'}
										{$LANG_SEARCH_IN_RADIUS_MILES}
									{else}
										{$LANG_SEARCH_IN_RADIUS_KILOMETRES}
									{/if}
									<div id="where_radius_slider"></div>
	                     		</div>
                     		</div>
                     		{/if}

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
                     		{if $content_access_obj->isPermission('Manage ability to claim')}
                     		<div class="search_item">
                     			<label><input type="radio" name="search_claimed_listings" value="any" {if !$args.search_claimed_listings}checked{/if} /> {$LANG_SEARCH_ANY_LISTINGS}</label>
                     			<label><input type="radio" name="search_claimed_listings" value="ability_to_claim" {if $args.search_claimed_listings == 'ability_to_claim'}checked{/if} /> {$LANG_LISTINGS_ABILITY_TO_CLAIM_STATUS}</label>
                     			<label><input type="radio" name="search_claimed_listings" value="claimed" {if $args.search_claimed_listings == 'claimed'}checked{/if} /> {$LANG_LISTINGS_CLAIMED_STATUS}</label>
                     			<label><input type="radio" name="search_claimed_listings" value="approved_claim" {if $args.search_claimed_listings == 'approved_claim'}checked{/if} /> {$LANG_LISTINGS_APPROVED_CLAIM_STATUS}</label>
                     		</div>
                     		{/if}

                     		{$search_fields->inputMode($args)}
                     	</div>
                     	<div class="clear_float"></div>
                     	<div id="advanced_search_block" style="display: none;"></div>
                     	<div class="clear_float"></div>
                     	<div class="search_item_button">
	                    	<input type="submit" class="button search_button" id="process_search" value="{$LANG_BUTTON_SEARCH_LISTINGS}">
	                    </div>
                     </form>

                     <div class="search_results_title">
                     	{$LANG_SEARCH_RESULTS_1} ({$listings_count} {$LANG_SEARCH_RESULTS_2}):
                     </div>

                     {if $listings|@count > 0}
                     <form id="listings_form" action="" method="post" onSubmit="submit_listings_form();">
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th><input type="checkbox"></th>
                         <!--<th>{$LANG_LOGO_TH}</th>-->
                         <th>{asc_desc_insert base_url=$search_url orderby='title' orderby_query=$orderby direction=$direction title=$LANG_TITLE_TH}</th>
                         <th>{$LANG_OWNER_TH}</th>
                         {if !$system_settings.single_type_structure}
                         <th>{$LANG_TYPE_TH}</th>
                         {/if}
                         <th>{$LANG_LEVEL_TH}</th>
                         <th>{$LANG_STATUS_TH}</th>
                         <th>{asc_desc_insert base_url=$search_url orderby='creation_date' orderby_query=$orderby direction=$direction title=$LANG_CREATION_DATE_TH}</th>
                         <th>{asc_desc_insert base_url=$search_url orderby='expiration_date' orderby_query=$orderby direction=$direction title=$LANG_EXPIRATION_DATE_TH}</th>
                         <th>{$LANG_OPTIONS_TH}</th>
                       </tr>
                       {foreach from=$listings item=listing}
                       {assign var="listing_id" value=$listing->id}
					   {assign var="listing_owner_id" value=$listing->owner_id}
					   {assign var="listing_owner_login" value=$listing->user->login}
                       <tr>
                         <td>
                    	  	<input type="checkbox" name="cb_{$listing->id}" value="{$listing->id}">
                    	 </td>
                         <!--<td>
                         {if $listing->level->logo_enabled}
                         	 <a href="{$VH->site_url("admin/listings/edit/$listing_id")}" title="{$LANG_EDIT_LISTING_OPTION}"><img src="{$users_content}/users_images/logos/{$listing->logo_file}" /></a>&nbsp;
                         {/if}
                         </td>-->
                         <td>
                             <a href="{$VH->site_url("admin/listings/view/$listing_id")}" title="{$LANG_VIEW_LISTING_OPTION}">{$listing->title()}</a>&nbsp;
                         </td>
                         <td>
                         	 {if $listing_owner_id != 1 && $listing_owner_id != $session_user_id && $content_access_obj->getUserAccess($listing_owner_id, 'View all users')}
                             <a href="{$VH->site_url("admin/users/view/$listing_owner_id")}" title="{$LANG_VIEW_USER_OPTION}">{$listing_owner_login}</a>&nbsp;
                             {else}
                             {$listing_owner_login}
                             {/if}

                             {if $content_access_obj->isPermission('Manage ability to claim')}
                             	 {if !$listing->claim_row.approved}
									{if !$listing->claim_row.to_user_id && $listing->claim_row.ability_to_claim}
										<br />(<i>May be claimed</i>)
									{elseif $listing->claim_row.to_user_id && $listing->claim_user}
										{assign var=user_id value=$listing->claim_user->id}
										<br />(<i>{$LANG_LISTING_SHORT_CLAIMED_BY} <a href="{$VH->site_url("admin/users/view/$user_id")}">{$listing->claim_user->login}</a><br />
											<nobr><a href="{$VH->site_url("admin/listings/approve_claim/$listing_id")}"><img src="{$public_path}images/icons/accept.png" /> {$LANG_LISTING_CLAIME_APPROVE}</a></nobr>
											<nobr><a href="{$VH->site_url("admin/listings/decline_claim/$listing_id")}"><img src="{$public_path}images/icons/delete.png" /> {$LANG_LISTING_CLAIME_DECLINE}</a></nobr>
										</i>)
									{/if}
								 {elseif $listing->claim_user}
									<br />(<i><nobr><a href="{$VH->site_url("admin/listings/decline_claim/$listing_id")}"><img src="{$public_path}images/icons/delete.png" /> {$LANG_LISTING_CLAIM_ROLL_BACK}</a></nobr></i>)
								 {/if}
							 {/if}
                         </td>
                         {if !$system_settings.single_type_structure}
                         <td>
                             {$listing->type->name}&nbsp;
                         </td>
                         {/if}
                         <td>
                            {if $content_access_obj->isPermission('Change listing level') && $listing->type->buildLevels()|@count > 1}
								<a href="{$VH->site_url("admin/listings/change_level/$listing_id")}" title="{$LANG_CHANGE_LISTING_LEVEL_OPTION}">{$listing->level->name}</a> <a href="{$VH->site_url("admin/listings/change_level/$listing_id")}" title="{$LANG_CHANGE_LISTING_LEVEL_OPTION}"><img src="{$public_path}/images/icons/upgrade.png" /></a>
							{else}
								{$listing->level->name}
							{/if}
                         </td>
                         <td>
                         	{if $listing->status == 1}<nobr><a href="{$VH->site_url("admin/listings/change_status/$listing_id")}" class="status_active" title="{$LANG_CHANGE_LISTING_STATUS_OPTION}">{$LANG_STATUS_ACTIVE}</a></nobr>{/if}
                         	{if $listing->status == 2}<nobr><a href="{$VH->site_url("admin/listings/change_status/$listing_id")}" class="status_blocked" title="{$LANG_CHANGE_LISTING_STATUS_OPTION}">{$LANG_STATUS_BLOCKED}</a></nobr>{/if}
                         	{if $listing->status == 3}<nobr><a href="{$VH->site_url("admin/listings/change_status/$listing_id")}" class="status_suspended" title="{$LANG_CHANGE_LISTING_STATUS_OPTION}">{$LANG_STATUS_SUSPENDED}</a>&nbsp;<a href="{$VH->site_url("admin/listings/prolong/$listing_id")}" title="{$LANG_PROLONG_ACTION}"><img src="{$public_path}images/icons/date_add.png"></a></nobr>{/if}
                         	{if $listing->status == 4}<nobr><a href="{$VH->site_url("admin/listings/change_status/$listing_id")}" class="status_unapproved" title="{$LANG_CHANGE_LISTING_STATUS_OPTION}">{$LANG_STATUS_UNAPPROVED}</a></nobr>{/if}
                         	{if $listing->status == 5}<nobr><a href="{$VH->site_url("admin/listings/change_status/$listing_id")}" class="status_notpaid" title="{$LANG_CHANGE_LISTING_STATUS_OPTION}">{$LANG_STATUS_NOTPAID}</a>{if $listing_owner_id == $session_user_id}&nbsp;<a href="{$VH->site_url("admin/payment/invoices/my/")}" title="{$LANG_VIEW_MY_INVOICES_MENU}"><img src="{$public_path}images/buttons/money_add.png"></a>{/if}</nobr>{/if}
                         	&nbsp;
                         </td>
                         <td>
                             {$listing->creation_date|date_format:"%D %T"}&nbsp;
                         </td>
                         <td>
                            {if !$listing->level->active_years && !$listing->level->active_months && !$listing->level->active_days && !$listing->level->allow_to_edit_active_period}
                         		<span class="green">{$LANG_ETERNAL}</span>
                         	{else}
                         		{$listing->expiration_date|date_format:"%D %T"}&nbsp;
                         	{/if}
                         </td>
                         <td>
                         	<nobr>
                         	 {if $listing->status == 1}
                         	 {assign var=listing_unique_id value=$listing->getUniqueId()}
                         	 <a href="{$VH->site_url("listings/$listing_unique_id")}" title="{$LANG_LISTING_LOOK_FRONTEND}"><img src="{$public_path}/images/buttons/house_go.png" /></a>
                         	 {/if}
                         	 <a href="{$VH->site_url("admin/listings/view/$listing_id")}" title="{$LANG_VIEW_LISTING_OPTION}"><img src="{$public_path}images/buttons/page.png"></a>
                             <a href="{$VH->site_url("admin/listings/edit/$listing_id")}" title="{$LANG_EDIT_LISTING_OPTION}"><img src="{$public_path}images/buttons/page_edit.png"></a>
                             <a href="{$VH->site_url("admin/listings/delete/$listing_id")}" title="{$LANG_DELETE_LISTING_OPTION}"><img src="{$public_path}images/buttons/page_delete.png"></a>
                             {if $listing->level->images_count > 0}
                             <a href="{$VH->site_url("admin/listings/images/$listing_id")}" title="{$LANG_LISTING_IMAGES_OPTION}"><img src="{$public_path}images/buttons/images.png"></a>
                             {/if}
                             {if $listing->level->video_count > 0}
                             <a href="{$VH->site_url("admin/listings/videos/$listing_id")}" title="{$LANG_LISTING_VIDEOS_OPTION}"><img src="{$public_path}images/buttons/videos.png"></a>
                             {/if}
                             {if $listing->level->files_count > 0}
                             <a href="{$VH->site_url("admin/listings/files/$listing_id")}" title="{$LANG_LISTING_FILES_OPTION}"><img src="{$public_path}images/buttons/page_link.png"></a>
                             {/if}
                             {if ($system_settings.google_analytics_profile_id && $system_settings.google_analytics_email && $system_settings.google_analytics_password) && ($content_access_obj->isPermission('View all statistics') || ($content_access_obj->isPermission('View self statistics') && $content_access_obj->checkListingAccess($listing_id)))}
                             <a href="{$VH->site_url("admin/listings/statistics/$listing_id")}" title="{$LANG_LISTING_STATISTICS_OPTION}"><img src="{$public_path}images/buttons/chart_bar.png"></a>
                             {/if}
                             {if $listing->level->ratings_enabled && $content_access_obj->isPermission('Manage all ratings')}
                             <a href="{$VH->site_url("admin/ratings/listings/$listing_id")}" title="{$LANG_LISTING_RATINGS_OPTION}"><img src="{$public_path}images/icons/star.png"></a>
                             {/if}
                             {if $listing->level->reviews_mode && $listing->level->reviews_mode != 'disabled' && $content_access_obj->isPermission('Manage all reviews')}
                             <a href="{$VH->site_url("admin/reviews/listings/$listing_id")}" title="{$LANG_LISTING_REVIEWS_OPTION}"><img src="{$public_path}images/icons/comments.png"></a>
                             {/if}
                            </nobr>
                         </td>
                       </tr>
                       {/foreach}
                     </table>
                     {$LANG_WITH_SELECTED}:
	                 <select name="table_action" onchange="action_cmd=this.options[this.selectedIndex].value; submit_listings_form(); this.form.submit()">
	                 	<option value="">{$LANG_CHOOSE_ACTION}</option>
	                 	<option value="delete">{$LANG_BUTTON_DELETE_LISTINGS}</option>
	                 	<option value="activate">{$LANG_BUTTON_ACTIVATE_LISTINGS}</option>
	                 	<option value="block">{$LANG_BUTTON_BLOCK_LISTINGS}</option>
	                 </select>
                     </form>
                     {$paginator}
                     {/if}
                </div>

{include file="backend/admin_footer.tpl"}