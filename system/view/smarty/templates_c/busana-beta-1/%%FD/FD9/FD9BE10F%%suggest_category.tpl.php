<?php /* Smarty version 2.6.26, created on 2012-02-07 05:50:01
         compiled from categories/suggest_category.tpl */ ?>
<script type="text/javascript">
$(document).ready(function() {
	ajax_loader_hide();
	var options = {
		target: ".email_form"
	};
	$('#email_form').ajaxForm(options);
	jGrowl_show_msg();
});
</script>
<div class="email_form" style="width: 450px">
	<?php echo $this->_tpl_vars['VH']->validation_errors(); ?>

	<form id="email_form" action="<?php echo $this->_tpl_vars['sender_url']; ?>
" method="post">
	<b><?php echo $this->_tpl_vars['LANG_SUGGEST_CATEGORY']; ?>
</b><span class="red_asterisk">*</span>
	<div class="px2"></div>
	<i><?php echo $this->_tpl_vars['LANG_SUGGEST_CATEGORY_DESCR']; ?>
</i>
	<div class="px2"></div>
	<input type="text" name="suggested_category" value="<?php echo $this->_tpl_vars['suggested_category']; ?>
" size="80">
	<div class="px5"></div>
	<b><?php echo $this->_tpl_vars['LANG_FILL_CAPTCHA']; ?>
</b><span class="red_asterisk">*</span>
	<div class="px2"></div>
	<input type="text" name="captcha" size="4">
	<div class="px3"></div>
	<?php echo $this->_tpl_vars['captcha']->view(); ?>

	<div class="px5"></div>
	<input type="submit" onclick="ajax_loader_show();" name="submit" value="<?php echo $this->_tpl_vars['LANG_BUTTON_SEND']; ?>
">&nbsp;&nbsp;&nbsp;<input type="button" class="nyroModalClose" onclick="$.nyroModalRemove();" name="close" value="<?php echo $this->_tpl_vars['LANG_BUTTON_CLOSE']; ?>
">
	</form>
</div>