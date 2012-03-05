<?php /* Smarty version 2.6.26, created on 2012-02-06 08:03:48
         compiled from content_fields/search/varchar/search_numeric_match.tpl */ ?>
<script language="JavaScript" type="text/javascript">
jQuery( function($) {
	$("#search_form").submit( function() {
		if ($("#<?php echo $this->_tpl_vars['from_index']; ?>
").val() != '')
			global_js_url = global_js_url + $("#<?php echo $this->_tpl_vars['field_index']; ?>
").attr('id') + '/' + $("#<?php echo $this->_tpl_vars['field_index']; ?>
").val() + '/';
			
		window.location.href = global_js_url;
		return false;
	});
});
</script>

							<div class="search_item">
								<label><?php if ($this->_tpl_vars['field']->frontend_name): ?><?php echo $this->_tpl_vars['field']->frontend_name; ?>
<?php else: ?><?php echo $this->_tpl_vars['field']->name; ?>
<?php endif; ?></label>
								<input type="text" name="<?php echo $this->_tpl_vars['field_index']; ?>
" id="<?php echo $this->_tpl_vars['field_index']; ?>
" value="<?php echo $this->_tpl_vars['args'][$this->_tpl_vars['field_index']]; ?>
" size="<?php echo $this->_tpl_vars['max_length']; ?>
" maxlength="<?php echo $this->_tpl_vars['max_length']; ?>
">
                     		</div>