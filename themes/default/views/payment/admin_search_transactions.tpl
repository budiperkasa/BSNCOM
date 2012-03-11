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

                		window.location.href = global_js_url;
						return false;
					{rdelim});
                {rdelim});
                </script>

                <div class="content">
                     <h3>{$LANG_SEARCH_TRANSACTIONS}</h3>

				<form id="search_form" action="" method="post">
                    <div class="search_block">
                     	<div class="search_item">
                     		<label>{$LANG_SEARCH_OWNER}:</label>
                     		<input type="text" id="search_owner" size="25" value="{$args.search_owner}" style="width: 205px;">
                     	</div>
                     	
                     	{assign var=field_title value=$LANG_SEARCH_PAYMENT_DATE}
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
                    	<input type="submit" class="button search_button" id="process_search" value="{$LANG_BUTTON_SEARCH_TRANSACTIONS}">
					</div>
				</form>
                     
                     <div class="search_results_title">
                     	{$LANG_SEARCH_TRANSACTIONS_RESULT_1} ({$transactions_count} {$LANG_SEARCH_TRANSACTIONS_RESULT_2}):
                     </div>
                     
                     {if $transactions|@count > 0}
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th>{$LANG_PAYMENT_GOODS_TITLE}</th>
                         <th>{$LANG_PAYMENT_INVOICE_OWNER}</th>
                         <th>{$LANG_TRANSACTION_METHOD}</th>
                         <th>{$LANG_TRANSACTION_STATUS}</th>
                         <th>{$LANG_TRANSACTION_QUANTITY}</th>
                         <th>{$LANG_TRANSACTION_CURRENCY}</th>
                         <th>{$LANG_TRANSACTION_AMOUNT}</th>
                         <th>{$LANG_TRANSACTION_FEE}</th>
                         <th>{asc_desc_insert base_url=$search_url orderby='payment_date' orderby_query=$orderby direction=$direction title=$LANG_TRANSACTION_PAYMENTDATE}</th>
                       </tr>
                       {foreach from=$transactions item=transaction}
                       {assign var="transaction_owner_id" value=$transaction->invoice->owner_id}
                       <tr>
                         <td>
                            {if $transaction->invoice->goods_content->goods_id && $transaction->invoice->getViewUrl()}
                            <a href="{$transaction->invoice->getViewUrl()}" title="{$LANG_VIEW_ITEM_OPTION}">{$transaction->invoice->goods_title}</a>&nbsp;
                            {else}
                            {$transaction->invoice->goods_title}
                            {/if}
                         </td>
                         <td>
                         	{if $transaction->invoice->owner_id != 1}
                         	<a href="{$VH->site_url("admin/users/profile/$transaction_owner_id")}" title="{$LANG_VIEW_USER_OPTION}">{$transaction->invoice->owner->login}</a>
                         	{else}
                         	{$transaction->invoice->owner->login}
                         	{/if}
                         	&nbsp;
                         </td>
                         <td>
                         	{$transaction->payment_gateway}
                         </td>
                         <td>
                         	{$transaction->payment_status}
                         </td>
                         <td>
                             {$transaction->quantity}
                         </td>
                         <td>
                             {$transaction->currency}
                         </td>
                         <td>
                             {$VH->number_format($transaction->value, 2, $decimals_separator, $thousands_separator)}
                         </td>
                         <td>
                             {$transaction->fee}
                         </td>
                         <td>
                             {$transaction->payment_date|date_format:"%D %T"}&nbsp;
                         </td>
                       </tr>
                       {/foreach}
                     </table>
                     {$paginator}
                     {/if}
                </div>

{include file="backend/admin_footer.tpl"}