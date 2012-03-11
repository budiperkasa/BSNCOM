{include file="backend/admin_header.tpl"}

                <div class="content">
                     <h3>{$LANG_MY_INVOICES}</h3>

                     {if $invoices|@count > 0}
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                       	 <th>{$LANG_ID_TH}</th>
                         <th>{asc_desc_insert base_url=$search_url orderby='goods_category' orderby_query=$orderby direction=$direction title=$LANG_PAYMENT_GOODS_CATEGORY}</th>
                         <th>{$LANG_PAYMENT_GOODS_TITLE}</th>
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
                         	{$invoice->id}
                         </td>
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