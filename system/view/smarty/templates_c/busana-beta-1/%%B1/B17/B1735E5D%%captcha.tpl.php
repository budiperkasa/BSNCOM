<?php /* Smarty version 2.6.26, created on 2012-02-06 03:36:08
         compiled from backend/captcha.tpl */ ?>
<?php $this->assign('rand_val', $this->_tpl_vars['VH']->rand()); ?>
<script language="JavaScript" type="text/javascript">
	$(document).ready(function() {
		$(".refresh_click_<?php echo $this->_tpl_vars['rand_val']; ?>
").live("click", function() {
			showNewCaptcha();
			return false;
		});
		showNewCaptcha(<?php echo $this->_tpl_vars['rand_val']; ?>
);
	});

	function showNewCaptcha(rand_val) {
		$(document).ready(function() {
			$("#captcha_image_"+rand_val).html('<img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/ajax-indicator.gif" />');
			$.ajax({
				url: '<?php echo $this->_tpl_vars['VH']->site_url('refresh_captcha'); ?>
',
				success: function(data){
					$("#captcha_image_"+rand_val).html(data);
				}
			});
		});
	}
</script>
<div style="margin-bottom:4px">
	<div id="captcha_image_<?php echo $this->_tpl_vars['rand_val']; ?>
" style="margin-bottom:4px; height:30px;"></div>
	<div>
		<a href="javascript: void(0);" onclick="showNewCaptcha(<?php echo $this->_tpl_vars['rand_val']; ?>
); return false;"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/arrow_refresh.png"></a> <a href="javascript: void(0);" onclick="showNewCaptcha(<?php echo $this->_tpl_vars['rand_val']; ?>
); return false;"><?php echo $this->_tpl_vars['LANG_REFRESH_CAPTCHA']; ?>
</a>
	</div>
</div>