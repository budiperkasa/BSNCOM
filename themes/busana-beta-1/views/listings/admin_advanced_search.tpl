				<script language="javascript" type="text/javascript">
						{assign var=random_id value=$VH->genRandomString()}
						var {$random_id}_mode = '{$mode}';

						$(document).ready( function() {ldelim}
				            {if $content_access_obj->isPermission('Manage all listings')}
				            $('#search_owner').autocomplete({ldelim}
								source: function(request, response) {ldelim}
									$.post("{$VH->site_url('admin/users/ajax_autocomplete_request/')}", {ldelim}query: request.term{rdelim}, function(data) {ldelim}
										response($.map(data.suggestions, function(item) {ldelim}
											return {ldelim}
												label: item,
												value: item
											{rdelim};
										{rdelim}));
									{rdelim}, "json");
								{rdelim},
								minLength: 2,
								delay: 500,
								select: function(event, ui) {ldelim}
									$(this).val(ui.item.label);
									return false;
								{rdelim}
							{rdelim});
							{/if}

				            $("#search_form").submit( function() {ldelim}
				            	{if $content_access_obj->isPermission('Manage all listings')}
				            		if (use_advanced) {ldelim}
						               	if ({$random_id}_mode == 'single') {ldelim}
						                   	if ($("#search_tmstmp_creation_date").val() != '')
												global_js_url = global_js_url + "search_creation_date" + '/' + $("#search_tmstmp_creation_date").val() + '/';
										{rdelim}
										if ({$random_id}_mode == 'range') {ldelim}
											if ($("#from_tmstmp_creation_date").val() != '')
												global_js_url = global_js_url + "search_from_creation_date" + '/' + $("#from_tmstmp_creation_date").val() + '/';
											if ($("#to_tmstmp_creation_date").val() != '')
												global_js_url = global_js_url + "search_to_creation_date" + '/' + $("#to_tmstmp_creation_date").val() + '/';
										{rdelim}
										if ($("#search_owner").val() != '')
											global_js_url = global_js_url + 'search_owner/' + urlencode($("#search_owner").val()) + '/';
										if ($("#search_status").val() != -1)
											global_js_url = global_js_url + 'search_status/' + $("#search_status").val() + '/';
									{rdelim}
								{/if}
								window.location.href = global_js_url;
								return false;
							{rdelim});
						{rdelim});
				</script>

				{if $content_access_obj->isPermission('Manage all listings')}
				<div class="search_item">
					<label>{$LANG_SEARCH_BY_OWNER}:</label>
					<input type="text" id="search_owner" value="{$args.search_owner}" style="width: 205px;" />
				</div>
				<div class="search_item">
					<label>{$LANG_SEARCH_BY_STATUS}:</label>
					<select id="search_status" style="min-width: 100px;">
						<option value="-1">- - - {$LANG_STATUS_ANY} - - -</option>
						<option value="1" {if $args.search_status == 1}selected{/if}>{$LANG_STATUS_ACTIVE}</option>
						<option value="2" {if $args.search_status == 2}selected{/if}>{$LANG_STATUS_BLOCKED}</option>
						<option value="3" {if $args.search_status == 3}selected{/if}>{$LANG_STATUS_SUSPENDED}</option>
						<option value="4" {if $args.search_status == 4}selected{/if}>{$LANG_STATUS_UNAPPROVED}</option>
						<option value="5" {if $args.search_status == 5}selected{/if}>{$LANG_STATUS_NOTPAID}</option>
					</select>
				</div>
				{assign var=field_title value=$LANG_SEARCH_BY_CREATION_DATE}
				{assign var=search_mode value=$mode}
				{assign var=date_var_name value="creation_date"}
				{assign var=single_date_var_value value=$creation_date}
				{assign var=single_date_var_value_tmstmp value=$creation_date_tmstmp}
				{assign var=from_date_var_value value=$from_creation_date}
				{assign var=from_date_var_value_tmstmp value=$from_creation_date_tmstmp}
				{assign var=to_date_var_value value=$to_creation_date}
				{assign var=to_date_var_value_tmstmp value=$to_creation_date_tmstmp}
				{include file="content_fields/common_date_range_search.tpl"}
				<div class="clear_float"></div>
				{/if}
				
				{$search_fields->inputMode($args)}