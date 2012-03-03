{include file="backend/admin_header.tpl"}

				<script language="javascript" type="text/javascript">
					var global_js_url = '{$base_url}';

					$(document).ready( function() {ldelim}
				       	{assign var=random_id value=$VH->genRandomString()}
						var {$random_id}_mode = '{$mode}';
    	
				        $('#search_login').autocomplete({ldelim}
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

				         $("#search_form").submit( function() {ldelim}
				           	if ({$random_id}_mode == 'single') {ldelim}
					        	if ($("#search_tmstmp_addition_date").val() != '')
									global_js_url = global_js_url + "search_addition_date" + '/' + $("#search_tmstmp_addition_date").val() + '/';
							{rdelim}
							if ({$random_id}_mode == 'range') {ldelim}
								if ($("#from_tmstmp_addition_date").val() != '')
									global_js_url = global_js_url + "search_from_addition_date" + '/' + $("#from_tmstmp_addition_date").val() + '/';
								if ($("#to_tmstmp_addition_date").val() != '')
									global_js_url = global_js_url + "search_to_addition_date" + '/' + $("#to_tmstmp_addition_date").val() + '/';
							{rdelim}

							if ($("#search_login").val() != '')
		                		global_js_url = global_js_url + 'search_login/' + $("#search_login").val() + '/';
		                	if ($("#search_status").val() != -1)
		                		global_js_url = global_js_url + 'search_status/' + $("#search_status").val() + '/';
		                	if ($("#search_package").val() != -1)
		                		global_js_url = global_js_url + 'search_package/' + $("#search_package").val() + '/';

							window.location.href = global_js_url;
							return false;
						{rdelim});
					{rdelim});

					function submit_packages_form()
	            	{ldelim}
	              		$("#packages_form").attr("action", '{$VH->site_url('admin/packages')}' + action_cmd + '/');
	               		return true;
	            	{rdelim}
                </script>

                <div class="content">
                     <h3>{$LANG_SEARCH_PACKAGES_TITLE}</h3>
                     
                     <form id="search_form" action="" method="post">
                     	<div class="search_block">
                     		<div class="search_item">
	                     		<label>{$LANG_SEARCH_BY_PACKAGE}:</label>
	                     		<select id="search_package">
	                     			<option value="-1">- - - {$LANG_SEARCH_BY_ANY_PACKAGE} - - -</option>
	                     			{foreach from=$all_packages item=package}
	                     			<option value="{$package->id}" {if $args.search_package == $package->id}selected{/if}>{$package->name}</option>
	                     			{/foreach}
	                     		</select>
	                     	</div>
	                     	<div class="search_item">
	                     		<label>{$LANG_SEARCH_LOGIN}:</label>
	                     		<input type="text" id="search_login" value="{$args.search_login}" style="width: 205px;" />
	                     	</div>
	                     	<div class="search_item">
	                     		<label>{$LANG_SEARCH_STATUS}:</label>
	                     		<select id="search_status">
	                     			<option value="-1">- - - {$LANG_SEARCH_ANY_STATUS} - - -</option>
	                     			<option value="1" {if $args.search_status == 1}selected{/if}>{$LANG_STATUS_ACTIVE}</option>
	                     			<option value="2" {if $args.search_status == 2}selected{/if}>{$LANG_STATUS_BLOCKED}</option>
	                     			<option value="3" {if $args.search_status == 3}selected{/if}>{$LANG_STATUS_NOTPAID}</option>
	                     		</select>
	                     	</div>

	                     	{assign var=field_title value=$LANG_PACKAGES_SEARCH_ADDITION_DATE}
	                     	{assign var=search_mode value=$mode}
	                     	{assign var=date_var_name value="addition_date"}
	                     	{assign var=single_date_var_value value=$addition_date}
	                     	{assign var=single_date_var_value_tmstmp value=$addition_date_tmstmp}
	                     	{assign var=from_date_var_value value=$from_addition_date}
	                     	{assign var=from_date_var_value_tmstmp value=$from_addition_date_tmstmp}
	                     	{assign var=to_date_var_value value=$to_addition_date}
	                     	{assign var=to_date_var_value_tmstmp value=$to_addition_date_tmstmp}
	                     	{include file="content_fields/common_date_range_search.tpl"}
                     	</div>
                     	<div class="clear_float"></div>
                     	<div class="search_item_button">
	                    	<input type="submit" class="button search_button" id="process_search" value="{$LANG_BUTTON_SEARCH_PACKAGES}">
	                    </div>
                     </form>
                     
                     <div class="search_results_title">
                     	{$LANG_SEARCH_PACKAGES_RESULT_1} ({$packages_count} {$LANG_SEARCH_PACKAGES_RESULT_2}):
                     </div>

					{if $users_packages|@count > 0}
					<table class="standardTable" border="0" cellpadding="2" cellspacing="2">
					<tr>
						<th>{$LANG_OWNER_TH}</th>
						<th>{$LANG_PACKAGE_NAME}</th>
						<th>{asc_desc_insert base_url=$search_url orderby='pu.status' orderby_query=$orderby direction=$direction title=$LANG_PACKAGE_STATUS_TH}</th>
						<th>{$LANG_PACKAGE_LISTINGS_AVAILABLE}</th>
						<th>{asc_desc_insert base_url=$search_url orderby='pu.creation_date' orderby_query=$orderby direction=$direction title=$LANG_PACKAGE_ADDITION_DATE_TH}</th>
					</tr>
					{foreach from=$users_packages item=user_package}
					{assign var=user_package_id value=$user_package->id}
					<tr>
						<td>
							{if $user_package->user_id != 1 && $user_package->user_id != $session_user_id && $content_access_obj->isPermission('View all users')}
		                         <a href="{$VH->site_url("admin/users/view/$user_package_id")}" title="{$LANG_VIEW_USER_OPTION}">{$user_package->user->login}</a>
							{else}
								{$user_package->user->login}
							{/if}
						</td>
						<td>
							{$user_package->package->name}
						</td>
						<td>
							{if $user_package->status == 1}{if $content_access_obj->isPermission('Change user packages status')}<a href="{$VH->site_url("admin/packages/change_status/$user_package_id")}" class="status_active">{$LANG_STATUS_ACTIVE}</a>{else}<span class="status_active">{$LANG_STATUS_ACTIVE}</span>{/if}{/if}
							{if $user_package->status == 2}{if $content_access_obj->isPermission('Change user packages status')}<a href="{$VH->site_url("admin/packages/change_status/$user_package_id")}" class="status_blocked">{$LANG_STATUS_BLOCKED}</a>{else}<span class="status_blocked">{$LANG_STATUS_BLOCKED}</span>{/if}{/if}
							{if $user_package->status == 3}{if $content_access_obj->isPermission('Change user packages status')}<a href="{$VH->site_url("admin/packages/change_status/$user_package_id")}" class="status_notpaid">{$LANG_STATUS_NOTPAID}</a>{else}<span class="status_notpaid">{$LANG_STATUS_NOTPAID}</span>{/if}{/if}
						</td>
						<td>
							{foreach from=$user_package->listings_left key=level_id item=listings_count}
								{if $listings_count > 0 || $listings_count === 'unlimited'}
									{assign var=type value=$user_package->package->levels[$level_id]->getType()}
									<b>{if !$system_settings.single_type_structure}{$type->name} - {/if}<i>{$user_package->package->levels[$level_id]->name}</i>:</b> {if $listings_count !== 'unlimited'}{$listings_count} {$LANG_LISTINGS}{else}<span class="green">{$LANG_UNLIMITED}</span>{/if}<br />
								{/if}
							{/foreach}
						</td>
						<td>
							{$user_package->creation_date|date_format:"%D %T"}
						</td>
					</tr>
					{/foreach}
					</table>
                    {$paginator}
                    {/if}
                </div>

{include file="backend/admin_footer.tpl"}