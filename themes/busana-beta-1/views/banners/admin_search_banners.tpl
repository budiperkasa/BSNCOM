{include file="backend/admin_header.tpl"}

				<script language="javascript" type="text/javascript">
				// Global url string
				var global_js_url = '{$base_url}';

				// Command variable, needs for delete, block listings buttons
				var action_cmd;

				jQuery( function($) {ldelim}
					{assign var=random_id value=$VH->genRandomString()}
					var {$random_id}_mode = '{$mode}';

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

					$("#search_form").submit( function() {ldelim}
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
						if ($("#search_block_id").val() != -1)
							global_js_url = global_js_url + 'search_block_id/' + $("#search_block_id").val() + '/';

						window.location.href = global_js_url;
						return false;
					{rdelim});
				{rdelim});

				function submit_banners_form()
				{ldelim}
					$("#banners_form").attr("action", '{$VH->site_url('admin/banners/')}' + action_cmd + '/');
					return true;
				{rdelim}
				</script>

                <div class="content">
                     <h3>{$LANG_SEARCH_LISTINGS}</h3>

                     <form id="search_form" action="" method="post">
                     	<div class="search_block">
                     		<div class="search_item">
								<label>{$LANG_SEARCH_BY_BANNERS_BLOCK}:</label>
								<select id="search_block_id" style="min-width: 200px;">
									<option value="-1">- - - {$LANG_BANNERS_BLOCK_ANY} - - -</option>
									{foreach from=$banners_blocks item=block}
									<option value="{$block->id}" {if $args.search_block_id == $block->id}selected{/if}>{$block->name}</option>
									{/foreach}
								</select>
							</div>
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
									<option value="4" {if $args.search_status == 4}selected{/if}>{$LANG_STATUS_NOTPAID}</option>
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
                     	</div>
                     	<div class="search_item_button">
	                    	<input type="submit" class="button search_button" value="{$LANG_BUTTON_SEARCH_BANNERS}">
	                    </div>
                     </form>

                     <div class="search_results_title">
                     	{$LANG_SEARCH_RESULTS_1} ({$banners_count} {$LANG_SEARCH_RESULTS_2}):
                     </div>

                     {if $banners|@count > 0}
                     <form id="banners_form" action="" method="post" onSubmit="submit_banners_form();">
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th><input type="checkbox"></th>
                         <th>{asc_desc_insert base_url=$search_url orderby='block_id' orderby_query=$orderby direction=$direction title=$LANG_BANNERS_BLOCK_NAME_TH}</th>
                         <th>{$LANG_OWNER_TH}</th>
                         <th>{$LANG_BANNER_VIEWS_TH}</th>
                         <th>{$LANG_BANNER_CLICKS_TH}</th>
                         <th>{$LANG_BANNERS_BLOCK_ACTIVE_PERIOD_TH}</th>
                         <th>{$LANG_BANNERS_BLOCK_CLICKS_LIMIT_TH}</th>
                         <th>{$LANG_BANNERS_BLOCK_LIMITATION_MODE_TH}</th>
                         <th>{$LANG_BANNER_LINK_URL}</th>
                         <th>{$LANG_STATUS_TH}</th>
                         <th>{asc_desc_insert base_url=$search_url orderby='creation_date' orderby_query=$orderby direction=$direction title=$LANG_CREATION_DATE_TH}</th>
                         <th>{asc_desc_insert base_url=$search_url orderby='expiration_date' orderby_query=$orderby direction=$direction title=$LANG_EXPIRATION_DATE_TH}</th>
                         <th>{$LANG_BANNERS_EXPIRATION_CLICKS_COUNT}</th>
                         <th>{$LANG_OPTIONS_TH}</th>
                       </tr>
                       {foreach from=$banners item=banner}
                       {assign var="banner_id" value=$banner->id}
					   {assign var="banner_owner_id" value=$banner->owner_id}
					   {assign var="banner_owner_login" value=$banner->user->login}
                       <tr>
                         <td>
                    	  	<input type="checkbox" name="cb_{$banner->id}" value="{$banner->id}">
                    	 </td>
                         <td>
                             {$banner->block->name}
                         </td>
                         <td>
                         	 {if $banner_owner_id != 1 && $banner_owner_id != $session_user_id && $content_access_obj->getUserAccess($banner_owner_id, 'View all users')}
                             <a href="{$VH->site_url("admin/users/view/$banner_owner_id")}" title="{$LANG_VIEW_USER_OPTION}">{$banner_owner_login}</a>&nbsp;
                             {else}
                             {$banner_owner_login}
                             {/if}
                         </td>
                         <td>
                             {$banner->views}
                         </td>
                         <td>
                             {$banner->clicks_count}
                         </td>
                         <td title="{$LANG_PERIOD_TD_ALT}">
                            {if $banner->block->limit_mode == 'active_period' || $banner->block->limit_mode == 'both'}
                         		{if $banner->block->active_years}
									{$LANG_YEARS}:&nbsp;<b>{$banner->block->active_years}</b><br />
								{/if}
								{if $banner->block->active_months}
									{$LANG_MONTHS}:&nbsp;<b>{$banner->block->active_months}</b><br />
								{/if}
								{if $banner->block->active_days}
									{$LANG_DAYS}:&nbsp;<b>{$banner->block->active_days}</b>
								{/if}
                         	{else}
                         		<span class="green">{$LANG_UNLIMITED}</span>
                         	{/if}
                         </td>
                         <td>
                            {if $banner->block->limit_mode == 'clicks' || $banner->block->limit_mode == 'both'}
                         		{$banner->block->clicks_limit}
                         	{else}
                         		<span class="green">{$LANG_UNLIMITED}</span>
                         	{/if}
                         </td>
                         <td>
                             {if $banner->block->limit_mode == 'active_period'}{$LANG_BANNERS_ACTIVE_PERIOD_LIMITATION}{/if}
                             {if $banner->block->limit_mode == 'clicks'}{$LANG_BANNERS_CLICKS_LIMITATION}{/if}
                             {if $banner->block->limit_mode == 'both'}{$LANG_BANNERS_BOTH_LIMITATION}{/if}
                         </td>
                         <td>
                             <a href="{$banner->url}" target="_blank">{$banner->url}</a>
                         </td>
                         <td>
                         	{if $banner->status == 1}<a href="{$VH->site_url("admin/banners/change_status/$banner_id")}" class="status_active" title="{$LANG_CHANGE_BANNER_STATUS_OPTION}">{$LANG_STATUS_ACTIVE}</a>{/if}
                         	{if $banner->status == 2}<a href="{$VH->site_url("admin/banners/change_status/$banner_id")}" class="status_blocked" title="{$LANG_CHANGE_BANNER_STATUS_OPTION}">{$LANG_STATUS_BLOCKED}</a>{/if}
                         	{if $banner->status == 3}<a href="{$VH->site_url("admin/banners/change_status/$banner_id")}" class="status_suspended" title="{$LANG_CHANGE_BANNER_STATUS_OPTION}">{$LANG_STATUS_SUSPENDED}</a>&nbsp;<a href="{$VH->site_url("admin/banners/prolong/$banner_id")}" title="{$LANG_PROLONG_ACTION}"><img src="{$public_path}images/icons/date_add.png"></a>{/if}
                         	{if $banner->status == 4}<a href="{$VH->site_url("admin/banners/change_status/$banner_id")}" class="status_notpaid" title="{$LANG_CHANGE_BANNER_STATUS_OPTION}">{$LANG_STATUS_NOTPAID}</a>{if $banner_owner_id == $session_user_id}&nbsp;<a href="{$VH->site_url("admin/payment/invoices/my/")}" title="{$LANG_VIEW_MY_INVOICES_MENU}"><img src="{$public_path}images/buttons/money_add.png"></a>{/if}{/if}
                         	&nbsp;
                         </td>
                         <td>
                             {$banner->creation_date|date_format:"%D %T"}
                         </td>
                         <td>
                             {$banner->expiration_date|date_format:"%D %T"}
                         </td>
                         <td>
                         	{if $banner->block->limit_mode == 'clicks' || $banner->block->limit_mode == 'both'}
                         		{$banner->clicks_expiration_count}
                            {else}
                         		<span class="green">{$LANG_UNLIMITED}</span>
                         	{/if}
                         </td>
                         <td>
                         	<nobr>
                         	 <a href="{$VH->site_url("admin/banners/view/$banner_id")}" title="{$LANG_VIEW_BANNER_OPTION}"><img src="{$public_path}images/buttons/page.png"></a>
                             <a href="{$VH->site_url("admin/banners/edit/$banner_id")}" title="{$LANG_EDIT_BANNER_OPTION}"><img src="{$public_path}images/buttons/page_edit.png"></a>
                             <a href="{$VH->site_url("admin/banners/delete/$banner_id")}" title="{$LANG_DELETE_BANNER_OPTION}"><img src="{$public_path}images/buttons/page_delete.png"></a>
                            </nobr>
                         </td>
                       </tr>
                       {/foreach}
                     </table>
                     {$LANG_WITH_SELECTED}:
	                 <select name="table_action" onchange="action_cmd=this.options[this.selectedIndex].value; submit_banners_form(); this.form.submit()">
	                 	<option value="">{$LANG_CHOOSE_ACTION}</option>
	                 	<option value="delete">{$LANG_BUTTON_DELETE_BANNERS}</option>
	                 	<option value="activate">{$LANG_BUTTON_ACTIVATE_BANNERS}</option>
	                 	<option value="block">{$LANG_BUTTON_BLOCK_BANNERS}</option>
	                 </select>
                     </form>
                     {$paginator}
                     {/if}
                </div>

{include file="backend/admin_footer.tpl"}