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
	<form id="email_form" action="{$sender_url}" method="post" style="width:350px">
	{if !$session_user_id}
		<b>{$LANG_YOUR_NAME}</b>
		<div class="px2"></div>
		<input type="text" name="sender_name" value="{$sender_name}" style="width:98%">
		<div class="px5"></div>
		<b>{$LANG_YOUR_EMAIL}</b><span class="red_asterisk">*</span>
		<div class="px2"></div>
		<input type="text" name="sender_email" value="{$sender_email}" style="width:98%">
		<div class="px5"></div>
	{/if}
	{if $target == 'friend'}
		<b>{$LANG_RECIPIENT_NAME}</b>
		<div class="px2"></div>
		<input type="text" name="recipient_name" value="{$recipient_name}" style="width:98%">
		<div class="px5"></div>
		<b>{$LANG_RECIPIENT_EMAIL}</b><span class="red_asterisk">*</span>
		<div class="px2"></div>
		<input type="text" name="recipient_email" value="{$recipient_email}" style="width:98%">
		<div class="px5"></div>
	{/if}
	<b>{$LANG_SUBJECT}</b><span class="red_asterisk">*</span>
	<div class="px2"></div>
	<input type="text" name="subject" value="{$subject}" style="width:98%">
	<div class="px5"></div>
	<b>{$LANG_MESSAGE}</b><span class="red_asterisk">*</span>
	<div class="px2"></div>
	<textarea name="body" style="width:98%" rows="9">{$body}</textarea>
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