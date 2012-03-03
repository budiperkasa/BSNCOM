{include file="backend/admin_header.tpl"}

				{if !$invoice->fixed_price}
				<script language="javascript" type="text/javascript">
					var invoice_value = {$invoice->value};
				
					$(document).ready(function() {ldelim}
						$("#quantity").keyup(function() {ldelim}
							$("#total").html(parseFloat(Math.round(invoice_value * $("#quantity").val() * 100) / 100));
						{rdelim});
					{rdelim});
				</script>
				{/if}

                <div class="content">
                	{$VH->validation_errors()}
                     <h3>{$LANG_CREATE_TRANSACTIONS_TITLE}</h3>

                     <form action="" method="post">
                     <label class="block_title">{$LANG_INVOICE_INFO}</label>
                     <div class="admin_option">
						<table>
							<tr>
								<td class="table_left_side">{$LANG_PAYMENT_INVOICE_ID}</td>
								<td class="table_right_side">
                         			{$invoice->id}
								</td>
							</tr>
							<tr>
								<td class="table_left_side">{$LANG_PAYMENT_GOODS_CATEGORY}</td>
								<td class="table_right_side">
                         			{$invoice->goods_content->name()}
								</td>
							</tr>
							<tr>
								<td class="table_left_side">{$LANG_PAYMENT_GOODS_TITLE}</td>
								<td class="table_right_side">
                         			{$invoice->goods_title}
								</td>
							</tr>
							<tr>
								<td class="table_left_side">{$LANG_PAYMENT_INVOICE_OWNER}</td>
								<td class="table_right_side">
                         			{$invoice->owner->login}
								</td>
							</tr>
							<tr>
								<td class="table_left_side">{$LANG_INVOICE_STATUS_TD}</td>
								<td class="table_right_side">
                         			{if $invoice->status == 1}<span class="status_notpaid">{$LANG_INVOICE_STATUS_NOTPAID}</span>{/if}
                         			{if $invoice->status == 2}<span class="status_paid">{$LANG_INVOICE_STATUS_PAID}</span>{/if}
                         			&nbsp;
								</td>
							</tr>
							<tr>
								<td class="table_left_side">{$LANG_INVOICE_PRICE_TD}</td>
								<td class="table_right_side">
                         			{$invoice->currency} {$invoice->value}
								</td>
							</tr>
							<tr>
								<td class="table_left_side">{$LANG_INVOICE_CREATION_DATE}</td>
								<td class="table_right_side">
                         			{$invoice->creation_date|date_format:"%D %T"}&nbsp;
								</td>
							</tr>
							{$invoice->goods_content->showOptions()}
						</table>
					 </div>
					 {if !$invoice->fixed_price}
					 <div class="admin_option">
					 	<div class="admin_option_name">
					 		{$LANG_ENTER_QUANTITY}
					 	</div>
					 	<div class="admin_option_description">
					 		{$LANG_ENTER_QUANTITY_DESCR_1} <i>{$LANG_ENTER_QUANTITY_DESCR_2}</i>
					 	</div>
					 	<input type="text" name="quantity" id="quantity" value="{$quantity}" size="3" />
					 </div>
					 {/if}
					 
					 <div class="admin_option">
					 	<div class="admin_option_name">
					 		{$LANG_INVOICE_TOTAL}:
					 	</div>
					 	{$invoice->currency} <span id="total">{$invoice->value*$quantity}</span>
					 </div>

                     <input type=submit name="submit" class="button enter_button" value="{$LANG_CREATE_TRANSACTION}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}