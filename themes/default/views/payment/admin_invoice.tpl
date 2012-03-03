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
                     <h3>{$LANG_PAY_INVOICE}</h3>

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
                         			{$invoice->currency} {$VH->number_format($invoice->value, 2, $decimals_separator, $thousands_separator)}
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
					 
					 {if $CI->load->is_module_loaded('discount_coupons') && $content_access_obj->isPermission('Use coupons')}
					 <div class="admin_option">
					 	<div class="admin_option_name">
					 		{$LANG_COUPON_ENTER}:
					 	</div>
					 	<input type="text" name="coupon_code" value="" style="min-width: 200px" />
					 	{if $my_coupons|@count}
					 	<div class="px10"></div>
					 	<select name="selected_coupon_code">
					 		<option value="-1">{$LANG_COUPON_USE_EXISTING}</option>
						 	{foreach from=$my_coupons item=coupon}
						 		<option value="{$coupon->code}">{$coupon->code}</option>
						 	{/foreach}
					 	</select>
					 	{/if}
					 </div>
					 {/if}

					 <div class="admin_option">
					 	<div class="admin_option_name">
					 		{$LANG_INVOICE_TOTAL}:
					 	</div>
					 	{$invoice->currency} <span id="total">{$VH->number_format($invoice->value*$quantity, 2, $decimals_separator, $thousands_separator)}</span>
					 </div>

					 <label class="block_title">{$LANG_PAYMENT_METHOD}</label>
                     <div class="admin_option">
                     	{foreach from=$gateways item=gateway}
                     		<label><input type="radio" name="gateway" value="{$gateway.module}" />&nbsp;{$gateway.gateway}</label>
                     	{/foreach}
                     </div>
					 
                     <input type=submit name="submit" class="button enter_button" value="{$LANG_PAY_NOW}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}