{include file="backend/admin_header.tpl"}

				<script language="javascript" type="text/javascript">
                var global_js_url = '{$base_url}';
                
                {assign var=random_id value=$VH->genRandomString()}
				var {$random_id}_mode = '{$mode}';
                
                jQuery( function($) {ldelim}
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

               		// Form submit event
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
	                		global_js_url = global_js_url + 'search_owner/' + $("#search_owner").val() + '/';
	                	if ($("#search_status").val() != -1)
	                		global_js_url = global_js_url + 'search_status/' + $("#search_status").val() + '/';

                		window.location.href = global_js_url;
						return false;
					{rdelim});
                {rdelim});
                </script>

                <div class="content">
                     <h3>{$LANG_SEARCH_INVOICES}</h3>

				<form id="search_form" action="" method="post">
                     <div class="search_block">
                     	<div class="search_item">
                     		<label>{$LANG_SEARCH_OWNER}:</label>
                     		<input type="text" id="search_owner" size="25" value="{$args.search_owner}" style="width: 205px;">
                     	</div>
                     	<div class="search_item">
                     		<label>{$LANG_SEARCH_STATUS}:</label>
                     		<select id="search_status" style="min-width: 100px;">
                     			<option value="-1">{$LANG_STATUS_ANY}</option>
                     			<option value="1" {if $args.search_status == 1}selected{/if}>{$LANG_INVOICE_STATUS_NOTPAID}</option>
                     			<option value="2" {if $args.search_status == 2}selected{/if}>{$LANG_INVOICE_STATUS_PAID}</option>
                     		</select>
                     	</div>
                     	
                     	{assign var=field_title value=$LANG_PACKAGES_SEARCH_ADDITION_DATE}
	                    {assign var=search_mode value=$mode}
	                    {assign var=date_var_name value="creation_date"}
	                    {assign var=single_date_var_value value=$creation_date}
	                    {assign var=single_date_var_value_tmstmp value=$creation_date_tmstmp}
	                    {assign var=from_date_var_value value=$from_creation_date}
	                    {assign var=from_date_var_value_tmstmp value=$from_creation_date_tmstmp}
	                    {assign var=to_date_var_value value=$to_creation_date}
	                    {assign var=to_date_var_value_tmstmp value=$to_creation_date_tmstmp}
	                    {include file="content_fields/common_date_range_search.tpl"}
					</div>
                    <div class="clear_float"></div>
                    <div class="search_item_button">
                    	<input type="submit" class="button search_button" id="process_search" value="{$LANG_BUTTON_SEARCH_INVOICES}">
                    </div>
				</form>

                     <div class="search_results_title">
                     	{$LANG_SEARCH_INVOICES_RESULT_1} ({$invoices_count} {$LANG_SEARCH_INVOICES_RESULT_2}):
                     </div>
                     
                     {if $invoices|@count > 0}
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th>{asc_desc_insert base_url=$search_url orderby='goods_category' orderby_query=$orderby direction=$direction title=$LANG_PAYMENT_GOODS_CATEGORY}</th>
                         <th>{$LANG_PAYMENT_GOODS_TITLE}</th>
                         <th>{$LANG_PAYMENT_INVOICE_OWNER}</th>
                         <th>{$LANG_INVOICE_STATUS_TD}</th>
                         <th>{$LANG_INVOICE_PRICE_TD}</th>
                         <th>{asc_desc_insert base_url=$search_url orderby='creation_date' orderby_query=$orderby direction=$direction title=$LANG_INVOICE_CREATION_DATE}</th>
                         <th>{$LANG_OPTIONS_TH}</th>
                       </tr>
                       {foreach from=$invoices item=invoice}
                       {assign var="invoice_id" value=$invoice->id}
                       {assign var="goods_category" value=$invoice->goods_category}
                       {assign var="goods_id" value=$invoice->goods_id}
                       {assign var="owner_id" value=$invoice->owner_id}
                       <tr>
                         <td>
                         	{$invoice->goods_content->name()}
                         </td>
                         <td>
                         	{if $invoice->goods_content->goods_id && $invoice->getViewUrl()}
                            <a href="{$invoice->getViewUrl()}" title="{$LANG_VIEW_ITEM_OPTION}">{$invoice->goods_title}</a>&nbsp;
                            {else}
                            {$invoice->goods_title}
                            {/if}
                         </td>
                         <td>
                         	{if $invoice->owner_id != 1 && $invoice->owner_id != $session_user_id && $content_access_obj->getUserAccess($owner_id, 'View all users')}
                         	<a href="{$VH->site_url("admin/users/profile/$owner_id")}" title="{$LANG_VIEW_USER_OPTION}">{$invoice->owner->login}</a>
                         	{else}
                         	{$invoice->owner->login}
                         	{/if}
                         	&nbsp;
                         </td>
                         <td>
                         	{if $invoice->status == 1}<span class="status_notpaid">{$LANG_INVOICE_STATUS_NOTPAID}</span>{/if}
                         	{if $invoice->status == 2}<span class="status_paid">{$LANG_INVOICE_STATUS_PAID}</span>{/if}
                         	&nbsp;
                         </td>
                         <td>
                             {$invoice->currency}&nbsp;{$VH->number_format($invoice->value, 2, $decimals_separator, $thousands_separator)}
                         </td>
                         <td>
                             {$invoice->creation_date|date_format:"%D %T"}&nbsp;
                         </td>
                         <td>
                         	<nobr>
                         	{if $gateways|@count && $invoice->status == 1 && $invoice->owner_id == $session_user_id && $invoice->goods_content->goods_id}
                         	<a href="{$VH->site_url("admin/payment/invoices/pay/$invoice_id")}" title="{$LANG_PAY_INVOICE_OPTION}"><img src="{$public_path}images/buttons/money_add.png"></a>&nbsp;
                         	{/if}
                         	{if $invoice->status == 1 && $content_access_obj->isPermission('Create transaction manually')}
                         	<a href="{$VH->site_url("admin/payment/transactions/create/$invoice_id")}" title="{$LANG_CREATE_TRANSACTIONS_TITLE}"><img src="{$public_path}images/buttons/transactions.png"></a>&nbsp;
                         	{/if}
                         	<a href="#" onClick="$.jqURL.loc('{$VH->site_url("admin/payment/invoices/print/$invoice_id")}', {ldelim}w:550,h:700,wintype:'_blank'{rdelim}); return false;" title="{$LANG_PRINT_INVOICE_OPTION}"><img src="{$public_path}images/icons/printer.png"></a>
                         	</nobr>
                         </td>
                       </tr>
                       {/foreach}
                     </table>
                     {$paginator}
                     {/if}
                </div>

{include file="backend/admin_footer.tpl"}