{include file="backend/admin_header.tpl"}

                <div class="content">
                	{if $content_access_obj->isPermission('Edit system settings')
                	|| $content_access_obj->isPermission('Manage all listings')
                	|| $content_access_obj->isPermission('Manage users')
                	|| $CI->load->is_module_loaded('banners') && $content_access_obj->isPermission('Manage all banners')
                	|| $CI->load->is_module_loaded('payment') && $content_access_obj->isPermission('View all invoices') && $content_access_obj->isPermission('View all transactions')
                	|| $content_access_obj->isPermission('Manage all reviews') || $content_access_obj->isPermission('Manage coupons')}
                    <h3>{$LANG_GLOBAL_SUMMARY_INFO}</h3>
                    {/if}

                     {if $content_access_obj->isPermission('Edit system settings')}
                     <div class="admin_option">
                     	<div class="admin_option_name">
                     		{$LANG_SUMMARY_VERSION_INFO}: v{$system_settings.W2D_VERSION}
                     	</div>
                     	<a href="http://www.salephpscripts.com/blog/" target="_blank">{$LANG_SUMMARY_VERSION_CHECK_LINK}</a>
                     </div>
                     {/if}

                     {if $content_access_obj->isPermission('Manage all listings')}
                     <label class="block_title">{$LANG_LISTINGS_SUMMARY}</label>
                     <div class="admin_option">
	                     <table>
	                     	{foreach from=$listings_status_count item=status_row}
	                     	{if $status_row.status == 1}
	                     	<tr>
	                     		<td class="table_left_side">
	                     			{$LANG_ACTIVE_LISTINGS}:
	                     		</td>
	                     		<td class="table_right_side">
	                     			{$status_row.active_status} <a href="{$VH->site_url("admin/listings/search/search_status/1")}">{$LANG_VIEW}</a>
	                     		</td>
	                     	</tr>
	                     	{/if}
	                     	{if $status_row.status == 2}
	                     	<tr>
	                     		<td class="table_left_side">
	                     			{$LANG_BLOCKED_LISTINGS}:
	                     		</td>
	                     		<td class="table_right_side">
	                     			{$status_row.blocked_status} <a href="{$VH->site_url("admin/listings/search/search_status/2")}">{$LANG_VIEW}</a>
	                     		</td>
	                     	</tr>
	                     	{/if}
	                     	{if $status_row.status == 3}
	                     	<tr>
	                     		<td class="table_left_side">
	                     			{$LANG_SUSPENDED_LISTINGS}:
	                     		</td>
	                     		<td class="table_right_side">
	                     			{$status_row.suspended_status} <a href="{$VH->site_url("admin/listings/search/search_status/3")}">{$LANG_VIEW}</a>
	                     		</td>
	                     	</tr>
	                     	{/if}
	                     	{if $status_row.status == 4}
	                     	<tr>
	                     		<td class="table_left_side">
	                     			{$LANG_UNAPPROVED_LISTINGS}:
	                     		</td>
	                     		<td class="table_right_side">
	                     			{$status_row.unapproved_status} <a href="{$VH->site_url("admin/listings/search/search_status/4")}">{$LANG_VIEW}</a>
	                     		</td>
	                     	</tr>
	                     	{/if}
	                     	{if $status_row.status == 5}
	                     	<tr>
	                     		<td class="table_left_side">
	                     			{$LANG_NOTPAID_LISTINGS}:
	                     		</td>
	                     		<td class="table_right_side">
	                     			{$status_row.not_paid_status} <a href="{$VH->site_url("admin/listings/search/search_status/5")}">{$LANG_VIEW}</a>
	                     		</td>
	                     	</tr>
	                     	{/if}
	                     	{/foreach}
	                     	{if $content_access_obj->isPermission('Manage ability to claim')}
	                     	<tr>
	                     		<td class="table_left_side">
	                     			{$LANG_LISTINGS_CLAIMED_STATUS}:
	                     		</td>
	                     		<td class="table_right_side">
	                     			{$unapproved_claims_count} {if $unapproved_claims_count}<a href="{$VH->site_url("admin/listings/search/search_claimed_listings/claimed/")}">{$LANG_VIEW}</a>{/if}
	                     		</td>
	                     	</tr>
	                     	{/if}
	                     </table>
                     </div>
                     {/if}

                     {if $content_access_obj->isPermission('Manage users')}
                     <label class="block_title">{$LANG_USERS_SUMMARY}</label>
                     <div class="admin_option">
	                     <table>
	                     	{foreach from=$users_status_count item=status_row}
	                     	{if $status_row.status == 1}
	                     	<tr>
	                     		<td class="table_left_side">
	                     			{$LANG_UNVERIFIED_USERS}:
	                     		</td>
	                     		<td class="table_right_side">
	                     			{$status_row.unverified_status} <a href="{$VH->site_url("admin/users/search/search_status/1")}">{$LANG_VIEW}</a>
	                     		</td>
	                     	</tr>
	                     	{/if}
	                     	{if $status_row.status == 2}
	                     	<tr>
	                     		<td class="table_left_side">
	                     			{$LANG_ACTIVE_USERS}:
	                     		</td>
	                     		<td class="table_right_side">
	                     			{$status_row.active_status} <a href="{$VH->site_url("admin/users/search/search_status/2")}">{$LANG_VIEW}</a>
	                     		</td>
	                     	</tr>
	                     	{/if}
	                     	{if $status_row.status == 3}
	                     	<tr>
	                     		<td class="table_left_side">
	                     			{$LANG_BLOCKED_USERS}:
	                     		</td>
	                     		<td class="table_right_side">
	                     			{$status_row.blocked_status} <a href="{$VH->site_url("admin/users/search/search_status/3")}">{$LANG_VIEW}</a>
	                     		</td>
	                     	</tr>
	                     	{/if}
	                     	{/foreach}
	                     	{foreach from=$users_in_groups_count item=count_row}
	                     	{assign var="users_group_id" value=$count_row.id}
	                     	<tr>
	                     		<td class="table_left_side">
	                     			{$LANG_USERS_IN_GROUP_STATS} '{$count_row.name}':
	                     		</td>
	                     		<td class="table_right_side">
	                     			{$count_row.users_count_in_group} {if $count_row.users_count_in_group}<a href="{$VH->site_url("admin/users/search/search_group/$users_group_id")}">{$LANG_VIEW}</a>{/if}
	                     		</td>
	                     	</tr>
	                     	{/foreach}
	                     </table>
                     </div>
                     {/if}
                     
                     {if $CI->load->is_module_loaded('banners') && $content_access_obj->isPermission('Manage all banners')}
                     <label class="block_title">{$LANG_BANNERS_SUMMARY}</label>
                     <div class="admin_option">
	                     <table>
	                     	{foreach from=$banners_status_count item=status_row}
	                     	{if $status_row.status == 1}
	                     	<tr>
	                     		<td class="table_left_side">
	                     			{$LANG_ACTIVE_BANNERS_COUNT}:
	                     		</td>
	                     		<td class="table_right_side">
	                     			{$status_row.active_status} <a href="{$VH->site_url('admin/banners/search/search_status/1')}">{$LANG_VIEW}</a>
	                     		</td>
	                     	</tr>
	                     	{/if}
	                     	{if $status_row.status == 2}
	                     	<tr>
	                     		<td class="table_left_side">
	                     			{$LANG_BLOCKED_BANNERS_COUNT}:
	                     		</td>
	                     		<td class="table_right_side">
	                     			{$status_row.blocked_status} <a href="{$VH->site_url('admin/banners/search/search_status/2')}">{$LANG_VIEW}</a>
	                     		</td>
	                     	</tr>
	                     	{/if}
	                     	{if $status_row.status == 3}
	                     	<tr>
	                     		<td class="table_left_side">
	                     			{$LANG_SUSPENDED_BANNERS_COUNT}:
	                     		</td>
	                     		<td class="table_right_side">
	                     			{$status_row.suspended_status} <a href="{$VH->site_url('admin/banners/search/search_status/3')}">{$LANG_VIEW}</a>
	                     		</td>
	                     	</tr>
	                     	{/if}
	                     	{if $status_row.status == 4}
	                     	<tr>
	                     		<td class="table_left_side">
	                     			{$LANG_NOTPAID_BANNERS_COUNT}:
	                     		</td>
	                     		<td class="table_right_side">
	                     			{$status_row.not_paid_status} <a href="{$VH->site_url('admin/banners/search/search_status/4')}">{$LANG_VIEW}</a>
	                     		</td>
	                     	</tr>
	                     	{/if}
	                     	{/foreach}
	                     </table>
                     </div>
                     {/if}
                     
                     {if $CI->load->is_module_loaded('payment') && $content_access_obj->isPermission('View all invoices') && $content_access_obj->isPermission('View all transactions')}
                     <label class="block_title">{$LANG_INVOICE_SUMMARY}</label>
                     <div class="admin_option">
	                     <table>
	                     	{foreach from=$invoices_status_count item=status_row}
	                     	{if $status_row.status == 1}
	                     	<tr>
	                     		<td class="table_left_side">
	                     			{$LANG_NOTPAID_INVOICES}:
	                     		</td>
	                     		<td class="table_right_side">
	                     			{$status_row.not_paid_status} <a href="{$VH->site_url('admin/payment/invoices/search/search_status/1')}">{$LANG_VIEW}</a>
	                     		</td>
	                     	</tr>
	                     	{/if}
	                     	{if $status_row.status == 2}
	                     	<tr>
	                     		<td class="table_left_side">
	                     			{$LANG_PAID_INVOICES}:
	                     		</td>
	                     		<td class="table_right_side">
	                     			{$status_row.paid_status} <a href="{$VH->site_url('admin/payment/invoices/search/search_status/2')}">{$LANG_VIEW}</a>
	                     		</td>
	                     	</tr>
	                     	{/if}
	                     	{/foreach}
	                     </table>
                     </div>
                     <label class="block_title">{$LANG_TRANSACTION_SUMMARY}</label>
                     <div class="admin_option">
	                     <table>
	                     	<tr>
	                     		<td class="table_left_side">
	                     			{$LANG_COMPLETED_TRANSACTIONS}:
	                     		</td>
	                     		<td class="table_right_side">
	                     			{$transactions_count}
	                     		</td>
	                     	</tr>
	                     	{foreach from=$transactions_summary item=summary}
	                     	<tr>
	                     		<td class="table_left_side">
	                     			{$LANG_TOTAL_SOLD} ({$summary.mc_currency}):
	                     		</td>
	                     		<td class="table_right_side">
	                     			{if $summary.transactions_amount != null}
	                     				{$summary.transactions_amount}
	                     			{else}
	                     				0
	                     			{/if}
	                     		</td>
	                     	</tr>
	                     	{/foreach}
	                     	{foreach from=$transactions_summary item=summary}
	                     	<tr>
	                     		<td class="table_left_side">
	                     			{$LANG_TOTAL_FEES} ({$summary.mc_currency}):
	                     		</td>
	                     		<td class="table_right_side">
	                     			{if $summary.transactions_fee_amount != null}
	                     				{$summary.transactions_fee_amount}
	                     			{else}
	                     				0
	                     			{/if}
	                     		</td>
	                     	</tr>
	                     	{/foreach}
	                     </table>
                     </div>
                     {/if}
                     
                     {if $content_access_obj->isPermission('Manage all reviews') || $content_access_obj->isPermission('Manage coupons')}
                     <label class="block_title">{$LANG_SUMMARY_MISCELLANEOUS}</label>
                     <div class="admin_option">
                     	{if $content_access_obj->isPermission('Manage all reviews')}
                     	<a href="{$VH->site_url("admin/reviews/listings/search/orderby/r.date_added/direction/desc/")}">{$LANG_VIEW_LATEST_REVIEWS}</a>
                     	<br />
                     	{/if}
                     	{if $content_access_obj->isPermission('Manage coupons')}
                     	<a href="{$VH->site_url("admin/coupons/search/orderby/dcu.usage_date/direction/desc/")}">{$LANG_VIEW_LATEST_COUPONS}</a>
                     	{/if}
                     </div>
                     {/if}
                     
                     
                     <!--<div class="px10"></div>
                     <div class="px10"></div>-->
                     <h3>{$LANG_MY_SUMMARY_INFO}</h3>
                     
                     {if $content_access_obj->isPermission('Manage self listings')}
                     <label class="block_title">{$LANG_MY_LISTINGS_SUMMARY}</label>
                     <div class="admin_option">
	                     <table>
	                     	{foreach from=$my_listings_status_count item=status_row}
	                     	{if $status_row.status == 1}
	                     	<tr>
	                     		<td class="table_left_side">
	                     			{$LANG_ACTIVE_LISTINGS}:
	                     		</td>
	                     		<td class="table_right_side">
	                     			{$status_row.active_status}
	                     		</td>
	                     	</tr>
	                     	{/if}
	                     	{if $status_row.status == 2}
	                     	<tr>
	                     		<td class="table_left_side">
	                     			{$LANG_BLOCKED_LISTINGS}:
	                     		</td>
	                     		<td class="table_right_side">
	                     			{$status_row.blocked_status}
	                     		</td>
	                     	</tr>
	                     	{/if}
	                     	{if $status_row.status == 3}
	                     	<tr>
	                     		<td class="table_left_side">
	                     			{$LANG_SUSPENDED_LISTINGS}:
	                     		</td>
	                     		<td class="table_right_side">
	                     			{$status_row.suspended_status}
	                     		</td>
	                     	</tr>
	                     	{/if}
	                     	{if $status_row.status == 4}
	                     	<tr>
	                     		<td class="table_left_side">
	                     			{$LANG_UNAPPROVED_LISTINGS}:
	                     		</td>
	                     		<td class="table_right_side">
	                     			{$status_row.unapproved_status}
	                     		</td>
	                     	</tr>
	                     	{/if}
	                     	{if $status_row.status == 5}
	                     	<tr>
	                     		<td class="table_left_side">
	                     			{$LANG_NOTPAID_LISTINGS}:
	                     		</td>
	                     		<td class="table_right_side">
	                     			{$status_row.not_paid_status}
	                     		</td>
	                     	</tr>
	                     	{/if}
	                     	{/foreach}
	                     </table>
                     </div>
                     {/if}

                     {if $CI->load->is_module_loaded('banners') && $content_access_obj->isPermission('Manage self banners')}
                     <label class="block_title">{$LANG_MY_BANNERS_SUMMARY}</label>
                     <div class="admin_option">
	                     <table>
	                     	{foreach from=$my_banners_status_count item=status_row}
	                     	{if $status_row.status == 1}
	                     	<tr>
	                     		<td class="table_left_side">
	                     			{$LANG_ACTIVE_BANNERS_COUNT}:
	                     		</td>
	                     		<td class="table_right_side">
	                     			{$status_row.active_status}
	                     		</td>
	                     	</tr>
	                     	{/if}
	                     	{if $status_row.status == 2}
	                     	<tr>
	                     		<td class="table_left_side">
	                     			{$LANG_BLOCKED_BANNERS_COUNT}:
	                     		</td>
	                     		<td class="table_right_side">
	                     			{$status_row.blocked_status}
	                     		</td>
	                     	</tr>
	                     	{/if}
	                     	{if $status_row.status == 3}
	                     	<tr>
	                     		<td class="table_left_side">
	                     			{$LANG_SUSPENDED_BANNERS_COUNT}:
	                     		</td>
	                     		<td class="table_right_side">
	                     			{$status_row.suspended_status}
	                     		</td>
	                     	</tr>
	                     	{/if}
	                     	{if $status_row.status == 4}
	                     	<tr>
	                     		<td class="table_left_side">
	                     			{$LANG_NOTPAID_BANNERS_COUNT}:
	                     		</td>
	                     		<td class="table_right_side">
	                     			{$status_row.not_paid_status}
	                     		</td>
	                     	</tr>
	                     	{/if}
	                     	{/foreach}
	                     </table>
                     </div>
                     {/if}
                     
                     {if $CI->load->is_module_loaded('payment') && $content_access_obj->isPermission('View self invoices') && $content_access_obj->isPermission('View self transactions')}
                     <label class="block_title">{$LANG_MY_INVOICE_SUMMARY}</label>
                     <div class="admin_option">
	                     <table>
	                     	{foreach from=$my_invoices_status_count item=status_row}
	                     	{if $status_row.status == 1}
	                     	<tr>
	                     		<td class="table_left_side">
	                     			{$LANG_NOTPAID_INVOICES}:
	                     		</td>
	                     		<td class="table_right_side">
	                     			{$status_row.not_paid_status}
	                     		</td>
	                     	</tr>
	                     	{/if}
	                     	{if $status_row.status == 2}
	                     	<tr>
	                     		<td class="table_left_side">
	                     			{$LANG_PAID_INVOICES}:
	                     		</td>
	                     		<td class="table_right_side">
	                     			{$status_row.paid_status}
	                     		</td>
	                     	</tr>
	                     	{/if}
	                     	{/foreach}
	                     </table>
                     </div>
                     <label class="block_title">{$LANG_MY_TRANSACTION_SUMMARY}</label>
                     <div class="admin_option">
	                     <table>
	                     	{if $summary.transactions_amount}
	                     	<tr>
	                     		<td class="table_left_side">
	                     			{$LANG_COMPLETED_TRANSACTIONS}:
	                     		</td>
	                     		<td class="table_right_side">
	                     			{$my_transactions_count}
	                     		</td>
	                     	</tr>
	                     	{/if}
	                     	{foreach from=$my_transactions_summary item=summary}
	                     	<tr>
	                     		<td class="table_left_side">
	                     			{$LANG_TOTAL_PAID} ({$summary.mc_currency}):
	                     		</td>
	                     		<td class="table_right_side">
	                     			{if $summary.transactions_amount != null}
	                     				{$summary.transactions_amount}
	                     			{else}
	                     				0
	                     			{/if}
	                     		</td>
	                     	</tr>
	                     	{/foreach}
	                     </table>
                     </div>
                     {/if}
                </div>

{include file="backend/admin_footer.tpl"}