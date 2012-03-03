{include file="backend/admin_header.tpl"}

				<script language="javascript" type="text/javascript">
					var global_js_url = '{$base_url}';
					
					{assign var=random_id value=$VH->genRandomString()}
					var {$random_id}_mode = '{$mode}';
					
					var action_cmd;

					$(document).ready( function() {ldelim}
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
					        	if ($("#search_tmstmp_last_visit_date").val() != '')
									global_js_url = global_js_url + "search_last_visit_date" + '/' + $("#search_tmstmp_last_visit_date").val() + '/';
							{rdelim}
							if ({$random_id}_mode == 'range') {ldelim}
								if ($("#from_tmstmp_last_visit_date").val() != '')
									global_js_url = global_js_url + "search_from_last_visit_date" + '/' + $("#from_tmstmp_last_visit_date").val() + '/';
								if ($("#to_tmstmp_last_visit_date").val() != '')
									global_js_url = global_js_url + "search_to_last_visit_date" + '/' + $("#to_tmstmp_last_visit_date").val() + '/';
							{rdelim}

							if ($("#search_login").val() != '')
		                		global_js_url = global_js_url + 'search_login/' + $("#search_login").val() + '/';
		                	if ($("#search_email").val() != '')
		                		global_js_url = global_js_url + 'search_email/' + $("#search_email").val() + '/';
		                	if ($("#groups").val() != -1)
		                		global_js_url = global_js_url + 'search_group/' + $("#groups").val() + '/';
		                	if ($("#search_status").val() != -1)
		                		global_js_url = global_js_url + 'search_status/' + $("#search_status").val() + '/';

							window.location.href = global_js_url;
							return false;
						{rdelim});
					{rdelim});

					function submit_users_form()
	            	{ldelim}
	              		$("#users_form").attr("action", '{$VH->site_url('admin/users')}' + action_cmd + '/');
	               		return true;
	            	{rdelim}
                </script>

                <div class="content">
                     <h3>{$LANG_SEARCH_USERS}</h3>
                     
                     <form id="search_form" action="" method="post">
                     	<div class="search_block">
	                     	<div class="search_item">
	                     		<label>{$LANG_SEARCH_LOGIN}:</label>
	                     		<input type="text" id="search_login" value="{$args.search_login}" style="width: 205px;" />
	                     	</div>
	                     	<div class="search_item">
	                     		<label>{$LANG_SEARCH_EMAIL}:</label>
	                     		<input type="text" id="search_email" size="25" value="{$args.search_email}" style="width: 205px;" />
	                     	</div>
	                     	<div class="search_item">
	                     		<label>{$LANG_SEARCH_STATUS}:</label>
	                     		<select id="search_status">
	                     			<option value="-1">- - - {$LANG_SEARCH_ANY_STATUS} - - -</option>
	                     			<option value="1" {if $args.search_status == 1}selected{/if}>{$LANG_USER_STATUS_UNVERIFIED}</option>
	                     			<option value="2" {if $args.search_status == 2}selected{/if}>{$LANG_USER_STATUS_ACTIVE}</option>
	                     			<option value="3" {if $args.search_status == 3}selected{/if}>{$LANG_USER_STATUS_BLOCKED}</option>
	                     		</select>
	                     	</div>
	                     	<div class="search_item">
	                     		<label>{$LANG_SEARCH_GROUP}:</label>
	                     		<select id="groups" name="search_group">
	                     			<option value="-1">- - - {$LANG_SEARCH_ANY_GROUP} - - -</option>
	                     			{foreach from=$groups_list item=groups_item}
	                     			<option value="{$groups_item->id}" {if $args.search_group == $groups_item->id}selected{/if}>{$groups_item->name}</option>
	                     			{/foreach}
				                </select>
	                     	</div>
	                     	
	                     	{assign var=field_title value=$LANG_SEARCH_LASTVISIT}
	                     	{assign var=search_mode value=$mode}
	                     	{assign var=date_var_name value="last_visit_date"}
	                     	{assign var=single_date_var_value value=$last_visit_date}
	                     	{assign var=single_date_var_value_tmstmp value=$last_visit_date_tmstmp}
	                     	{assign var=from_date_var_value value=$from_last_visit_date}
	                     	{assign var=from_date_var_value_tmstmp value=$from_last_visit_date_tmstmp}
	                     	{assign var=to_date_var_value value=$to_last_visit_date}
	                     	{assign var=to_date_var_value_tmstmp value=$to_last_visit_date_tmstmp}
	                     	{include file="content_fields/common_date_range_search.tpl"}
                     	</div>
                     	<div class="clear_float"></div>
                     	<div class="search_item_button">
	                    	<input type="submit" class="button search_button" id="process_search" value="{$LANG_BUTTON_SEARCH_USERS}">
	                    </div>
                     </form>
                     
                     <div class="admin_top_menu_cell">
	                    <a href="{$VH->site_url("admin/users/create")}" title="{$LANG_CREATE_USER_OPTION}"><img src="{$public_path}/images/buttons/user_add.png" /></a>
	                    <a href="{$VH->site_url("admin/users/create")}">{$LANG_CREATE_USER_OPTION}</a>
					 </div>
					 <div class="clear_float"></div>
                     
                     <div class="search_results_title">
                     	{$LANG_SEARCH_USERS_RESULT_1} ({$users_count} {$LANG_SEARCH_USERS_RESULT_2}):
                     </div>

                     {if $users|@count > 0}
                     <form id="users_form" action="" method="post" onSubmit="submit_users_form();">
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th width="1"><input type="checkbox"></th>
                         <th>{asc_desc_insert base_url=$search_url orderby='login' orderby_query=$orderby direction=$direction title=$LANG_USERS_LOGIN_TH}</th>
                         <th>{$LANG_USERS_GROUP_NAME_TH}</th>
                         <th>{$LANG_USERS_STATUS_TH}</th>
                         <th>{$LANG_USERS_EMAIL_TH}</th>
                         <th>{asc_desc_insert base_url=$search_url orderby='last_login_date' orderby_query=$orderby direction=$direction title=$LANG_USERS_LAST_VISIT_TH}</th>
                         <th>{$LANG_OPTIONS_TH}</th>
                       </tr>
                       {foreach from=$users item=user}
                       {assign var="user_id" value=$user->id}
                       {assign var="user_login" value=$VH->urlencode($user->login)}
                       <tr>
                         <td>
                         	{if $user_id != 1}
                    	  	<input type="checkbox" name="cb_{$user->id}" value="{$user->id}">
                    	  	{/if}
                    	 </td>
                         <td>
                         	 {if $user_id != 1}
                             <a href="{$VH->site_url("admin/users/view/$user_id")}" title="{$LANG_VIEW_USER_OPTION}">{$user->login}</a>
                             {else}
                             {$user->login}
                             {/if}
                             &nbsp;
                         </td>
                         <td>
                         	 {if $user_id != 1}
                             <a href="{$VH->site_url("admin/users/change_group/$user_id")}" title="{$LANG_USER_CHANGE_GROUP_OPTION}">{$user->users_group->name}</a>
                             {else}
                             {$user->users_group->name}
                             {/if}
                             &nbsp;
                         </td>
                         <td>
                         	{if $user_id == 1}
                         	<span class="user_first">{$LANG_USER_FIRST_USER}</span>
                         	{else}
                         	{if $user->status == 1}<a href="{$VH->site_url("admin/users/change_status/$user_id")}" class="status_unverified" title="{$LANG_USER_CHANGE_STATUS_OPTION}">{$LANG_USER_STATUS_UNVERIFIED}</a>{/if}
                         	{if $user->status == 2}<a href="{$VH->site_url("admin/users/change_status/$user_id")}" class="status_active" title="{$LANG_USER_CHANGE_STATUS_OPTION}">{$LANG_USER_STATUS_ACTIVE}</a>{/if}
                         	{if $user->status == 3}<a href="{$VH->site_url("admin/users/change_status/$user_id")}" class="status_blocked" title="{$LANG_USER_CHANGE_STATUS_OPTION}">{$LANG_USER_STATUS_BLOCKED}</a>{/if}
                         	{/if}
                         	&nbsp;
                         </td>
                         <td>
                         	{if $user_id == 1}
                         	{$user->email}&nbsp;
                         	{else}
                         	<a href="{$VH->site_url("email/send/user_id/$user_id")}" class="nyroModal noborder" title="{$LANG_SEND_EMAIL_TO_USER}">{$user->email}</a>&nbsp;
                         	{/if}
                         </td>
                         <td>
                            {$user->last_login_date|date_format:"%D %T"}&nbsp;
                         </td>
                         <td>
                         	<nobr>
                            <a href="{$VH->site_url("admin/listings/search/use_advanced/true/search_owner/$user_login/")}" title="{$LANG_USER_LISTINGS_OPTION}"><img src="{$public_path}images/buttons/page_copy.png"></a>
                            <a href="{$VH->site_url("admin/reviews/search/search_login/$user_login/")}" title="{$LANG_USER_REVIEWS_OPTION}"><img src="{$public_path}/images/buttons/comments.png" /></a>
                            {if $CI->load->is_module_loaded('banners') && $content_access_obj->isPermission('Manage all banners')}
                            <a href="{$VH->site_url("admin/banners/search/search_owner/$user_login/")}" title="{$LANG_USER_BANNERS_OPTION}"><img src="{$public_path}/images/buttons/banners.png" /></a>
                            {/if}
                            {if $CI->load->is_module_loaded('payment') && $content_access_obj->isPermission('View all invoices')}
                            <a href="{$VH->site_url("admin/payment/invoices/search/search_owner/$user_login/")}" title="{$LANG_USER_INVOICES_OPTION}"><img src="{$public_path}/images/buttons/invoices.png" /></a>
                            {/if}
                            {if $CI->load->is_module_loaded('payment') && $content_access_obj->isPermission('View all transactions')}
                            <a href="{$VH->site_url("admin/payment/transactions/search/search_owner/$user_login/")}" title="{$LANG_USER_TRANSACTIONS_OPTION}"><img src="{$public_path}/images/buttons/transactions.png" /></a>
                            {/if}
                            {if $CI->load->is_module_loaded('packages') && $content_access_obj->isPermission('Manage packages')}
                            <a href="{$VH->site_url("admin/packages/search/search_login/$user_login/")}" title="{$LANG_USER_PACKAGES_OPTION}"><img src="{$public_path}/images/buttons/packages.png" /></a>
                            {/if}
                            {if $CI->load->is_module_loaded('discount_coupons') && $content_access_obj->isPermission('Manage coupons')}
                            <a href="{$VH->site_url("admin/coupons/send/user_id/$user_id")}" class="nyroModal noborder" title="{$LANG_SEND_COUPON_TO_USER}"><img src="{$public_path}/images/buttons/tag_blue_add.png" /></a>
                            <a href="{$VH->site_url("admin/coupons/search/search_login/$user_login")}" title="{$LANG_USER_COUPONS_OPTION}"><img src="{$public_path}/images/buttons/coupons.png" /></a>
                            {/if}
                         	{if $user_id != 1}
                         	<a href="{$VH->site_url("admin/users/view/$user_id")}" title="{$LANG_USER_VIEW_PROFILE_OPTION}"><img src="{$public_path}images/buttons/page.png"></a>
                         	<a href="{$VH->site_url("admin/users/profile/$user_id")}" title="{$LANG_USER_EDIT_PROFILE_OPTION}"><img src="{$public_path}images/buttons/page_edit.png"></a>
                         	<a href="{$VH->site_url("admin/users/delete/$user_id")}" title="{$LANG_USER_DELETE_PROFILE_OPTION}"><img src="{$public_path}images/buttons/user_delete.png"></a>
                            {/if}
                            <a href="{$VH->site_url("email/send/user_id/$user_id")}" class="nyroModal noborder" title="{$LANG_SEND_EMAIL_TO_USER}"><img src="{$public_path}/images/buttons/user_go.png" /></a>
                            </nobr>
                         </td>
                       </tr>
                       {/foreach}
                     </table>
                     {$LANG_WITH_SELECTED}:
	                 <select name="table_action" onchange="action_cmd=this.options[this.selectedIndex].value; submit_users_form(); this.form.submit()">
	                 	<option value="">{$LANG_CHOOSE_ACTION}</option>
	                 	<option value="block">{$LANG_BUTTON_BLOCK_USERS}</option>
	                 </select>
                     {$paginator}
                     </form>
                     {/if}
                </div>

{include file="backend/admin_footer.tpl"}