{include file="backend/admin_header.tpl"}

				<div class="content">
                     <h3>{$LANG_MY_TRANSACTIONS}</h3>

                     {if $transactions|@count > 0}
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th>{$LANG_PAYMENT_GOODS_TITLE}</th>
                         <th>{$LANG_TRANSACTION_METHOD}</th>
                         <th>{$LANG_TRANSACTION_STATUS}</th>
                         <th>{$LANG_TRANSACTION_QUANTITY}</th>
                         <th>{$LANG_TRANSACTION_CURRENCY}</th>
                         <th>{$LANG_TRANSACTION_AMOUNT}</th>
                         <th>{$LANG_TRANSACTION_FEE}</th>
                         <th>{asc_desc_insert base_url=$search_url orderby='payment_date' orderby_query=$orderby direction=$direction title=$LANG_TRANSACTION_PAYMENTDATE}</th>
                       </tr>
                       {foreach from=$transactions item=transaction}
                       <tr>
                         <td>
                         	{if $transaction->invoice->goods_content->goods_id && $transaction->invoice->getViewUrl()}
                            <a href="{$transaction->invoice->getViewUrl()}" title="{$LANG_VIEW_ITEM_OPTION}">{$transaction->invoice->goods_title}</a>&nbsp;
                            {else}
                            {$transaction->invoice->goods_title}
                            {/if}
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