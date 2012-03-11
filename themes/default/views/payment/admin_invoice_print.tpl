<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>{$title} - {$site_settings.website_title}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
{foreach from=$css_files item=css_media key=css_file}
		<link rel="stylesheet" href="{$css_file}" media="{$css_media}, print" type="text/css" />
{/foreach}
{foreach from=$ex_css_files item=ex_css_item}
		<link rel="stylesheet" href="{$ex_css_item}" type="text/css" />
{/foreach}
{foreach from=$ex_js_scripts item=ex_js_item}
		<script language="JavaScript" type="text/javascript" src="{$ex_js_item}"></script>
{/foreach}
{if !$CI->config->item('combine_static_files') || $CI->config->item('combine_static_files') === null}
	{foreach from=$js_scripts item=js_item}
			<script language="JavaScript" type="text/javascript" src="{$VH->base_url()}{$js_item}"></script>
	{/foreach}
{else}
	<script language="JavaScript" type="text/javascript" src="{$VH->base_url()},{$VH->implode(',', $js_scripts)}"></script>
{/if}
	</head>
<body>
	<script language="javascript" type="text/javascript">
		var invoice_value = {$invoice->value};

		$(document).ready(function() {ldelim}
		$("#quantity").keyup(function() {ldelim}
			$("#total").html(parseFloat(Math.round(invoice_value * $("#quantity").val() * 100) / 100));
			{rdelim});
		{rdelim});
	</script>

	<div class="content">
		<img src="{$users_content}/users_images/site_logo/{$system_settings.site_logo_file}" >
		
		<div class="px10"></div>
		<h3>{$LANG_PRINT_INVOICE_TITLE}</h3>

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

		<label class="block_title">{$LANG_INVOICE_TO}</label>
		<div class="admin_option">
			<textarea style="width:100%" rows="7">{$invoice->owner->login}
{$LANG_INVOICE_PRINT_ACCOUNT_ID}: {$invoice->owner_id}
{$LANG_EMAIL}: {$invoice->owner->email}
</textarea>
		</div>
		
		<label class="block_title">{$LANG_INVOICE_FROM}</label>
		<div class="admin_option">
			{$site_settings.company_details|nl2br}
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
			{$invoice->currency} <span id="total">{$VH->number_format($invoice->value*$quantity, 2, $decimals_separator, $thousands_separator)}</span>
		</div>

		<input type=button name="submit" class="button enter_button" onclick="window.print(); return false;" value="{$LANG_PRINT_INVOICE_BTN}">
		&nbsp;
		<input type=button name="close" class="button block_button" onclick="window.close(); return false;" value="{$LANG_BUTTON_CLOSE}">
	</div>
</body>
</html>