{include file="backend/admin_header.tpl"}

				<script language="javascript" type="text/javascript">
				// Command variable, needs for delete banners button
				var action_cmd;

				function submit_banners_form()
				{ldelim}
					$("#banners_form").attr("action", '{$VH->site_url('admin/banners/')}' + action_cmd + '/');
					return true;
				{rdelim}
				</script>

                <div class="content">
                     <h3>{$LANG_MANAGE_BANNERS_1} ({$banners_count} {$LANG_MANAGE_BANNERS_2})</h3>
                     
                     <div class="admin_top_menu_cell">
	                     <a href="{$VH->site_url("admin/banners/create")}" title="{$LANG_CREATE_BANNER}"><img src="{$public_path}/images/buttons/page_add.png" /></a>
	                     <a href="{$VH->site_url("admin/banners/create")}">{$LANG_CREATE_BANNER}</a>
	                 </div>
	                 <div class="clear_float"></div>

                     {if $banners|@count > 0}
                     <form id="banners_form" action="" method="post" onSubmit="submit_banners_form();">
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th><input type="checkbox"></th>
                         <th>{asc_desc_insert base_url=$search_url orderby='block_id' orderby_query=$orderby direction=$direction title=$LANG_BANNERS_BLOCK_NAME_TH}</th>
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
                       <tr>
                         <td>
                    	  	<input type="checkbox" name="cb_{$banner->id}" value="{$banner->id}">
                    	 </td>
                         <td>
                             {$banner->block->name}
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
                         	{if $banner->status == 3}<span class="status_suspended">{$LANG_STATUS_SUSPENDED}</span>&nbsp;<a href="{$VH->site_url("admin/banners/prolong/$banner_id")}" title="{$LANG_PROLONG_ACTION}"><img src="{$public_path}images/icons/date_add.png"></a>{/if}
                         	{if $banner->status == 4}<span class="status_notpaid">{$LANG_STATUS_NOTPAID}</span>&nbsp;<a href="{$VH->site_url("admin/payment/invoices/my/")}" title="{$LANG_VIEW_MY_INVOICES_MENU}"><img src="{$public_path}images/buttons/money_add.png"></a>{/if}
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
                            <nobr>
                         </td>
                       </tr>
                       {/foreach}
                     </table>
                     {$LANG_WITH_SELECTED}:
	                 <select name="table_action" onchange="action_cmd=this.options[this.selectedIndex].value; submit_banners_form(); this.form.submit()">
	                 	<option value="">{$LANG_CHOOSE_ACTION}</option>
	                 	<option value="delete">{$LANG_BUTTON_DELETE_BANNERS}</option>
	                 </select>
                     </form>
                     {$paginator}
                     {/if}
                </div>

{include file="backend/admin_footer.tpl"}