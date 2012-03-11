<script type="text/javascript">
$(document).ready(function() {ldelim}
	ajax_loader_hide();
	var options = {ldelim}
		target: ".email_form"
	{rdelim};
	$('#email_form').ajaxForm(options);
	jGrowl_show_msg();
{rdelim});
</script>
<div class="email_form" style="width: 450px">
	{$VH->validation_errors()}
	<form id="email_form" action="{$sender_url}" method="post">
	<b>{$LANG_SUGGEST_CATEGORY}</b><span class="red_asterisk">*</span>
	<div class="px2"></div>
	<i>{$LANG_SUGGEST_CATEGORY_DESCR}</i>
	<div class="px2"></div>
	<input type="text" name="suggested_category" value="{$suggested_category}" size="80">
	<div class="px5"></div>
	<b>{$LANG_FILL_CAPTCHA}</b><span class="red_asterisk">*</span>
	<div class="px2"></div>
	<input type="text" name="captcha" size="4">
	<div class="px3"></div>
	{$captcha->view()}
	<div class="px5"></div>
	<input type="submit" onclick="ajax_loader_show();" name="submit" value="{$LANG_BUTTON_SEND}">&nbsp;&nbsp;&nbsp;<input type="button" class="nyroModalClose" onclick="$.nyroModalRemove();" name="close" value="{$LANG_BUTTON_CLOSE}">
	</form>
</div>