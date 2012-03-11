{assign var="rand_val" value=$VH->rand()}
<script language="JavaScript" type="text/javascript">
	$(document).ready(function() {ldelim}
		$(".refresh_click_{$rand_val}").live("click", function() {ldelim}
			showNewCaptcha();
			return false;
		{rdelim});
		showNewCaptcha({$rand_val});
	{rdelim});

	function showNewCaptcha(rand_val) {ldelim}
		$(document).ready(function() {ldelim}
			$("#captcha_image_"+rand_val).html('<img src="{$public_path}images/ajax-indicator.gif" />');
			$.ajax({ldelim}
				url: '{$VH->site_url('refresh_captcha')}',
				success: function(data){ldelim}
					$("#captcha_image_"+rand_val).html(data);
				{rdelim}
			{rdelim});
		{rdelim});
	{rdelim}
</script>
<div style="margin-bottom:4px">
	<div id="captcha_image_{$rand_val}" style="margin-bottom:4px; height:30px;"></div>
	<div>
		<a href="javascript: void(0);" onclick="showNewCaptcha({$rand_val}); return false;"><img src="{$public_path}images/buttons/arrow_refresh.png"></a> <a href="javascript: void(0);" onclick="showNewCaptcha({$rand_val}); return false;">{$LANG_REFRESH_CAPTCHA}</a>
	</div>
</div>