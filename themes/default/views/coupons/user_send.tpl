<script type="text/javascript">
$(document).ready(function() {ldelim}
	ajax_loader_hide();
	var options = {ldelim}
		target: ".email_form",
		success:    function() {ldelim}
			jGrowl_show_msg();
		{rdelim}
	{rdelim};
	$('#email_form').ajaxForm(options);
{rdelim});
</script>
<div class="email_form">
	{$VH->validation_errors()}
	{$LANG_SELECT_COUPON}:
	<div class="px10"></div>
	<form id="email_form" action="{$url}" method="post">
	<select name="coupon_code">
		{foreach from=$coupons item=coupon}
			<option value="{$coupon->code}">{$coupon->code}</option>
		{/foreach}
	</select>
	<div class="px10"></div>
	<input type="submit" onclick="ajax_loader_show();" name="submit" value="{$LANG_BUTTON_SEND}">&nbsp;&nbsp;&nbsp;<input type="button" class="nyroModalClose" onclick="$.nyroModalRemove();" name="close" value="{$LANG_BUTTON_CLOSE}">
	</form>
</div>