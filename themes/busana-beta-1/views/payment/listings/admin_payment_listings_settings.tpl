{include file="backend/admin_header.tpl"}

                <div class="content">
                	{$VH->validation_errors()}
                     <h3>{$LANG_LEVEL_PRICE_1} "{$level_name}" {$LANG_LEVEL_PRICE_2} "{$user_group_name}"</h3>

                     <form action="" method="post">
                     <div class="admin_option">
                     	<div class="admin_option_name">
                     		{$LANG_LISTINGS_PRICE}
                     	</div>
                     	<div class="admin_option_description">
                     		{$LANG_LEVEL_PRICE_DESCR}
                     	</div>
                     	<select name="currency" style="min-width: 40px;">
                     		{foreach from=$currencies item=currency}
                     			<option value="{$currency}" {if $price_row.currency == $currency}selected{/if}>{$currency}</option>
                     		{/foreach}
                     	</select>&nbsp;
                     	<input type="text" name="value" style="text-align: right;" value="{if $price_row.value === null}0{else}{$VH->number_format($price_row.value, 2, $decimals_separator, $thousands_separator)}{/if}" size="4" />
                     </div>
                     <input type=submit name="submit" class="button save_button" value="{$LANG_BUTTON_SAVE_PRICE}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}