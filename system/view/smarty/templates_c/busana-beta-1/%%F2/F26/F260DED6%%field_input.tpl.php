<?php /* Smarty version 2.6.26, created on 2012-02-06 04:34:11
         compiled from content_fields/fields/select/field_input.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'nl2br', 'content_fields/fields/select/field_input.tpl', 5, false),)), $this); ?>
			<div class="admin_option_name">
				<?php echo $this->_tpl_vars['field']->name; ?>
<?php if ($this->_tpl_vars['field']->required): ?><span class="red_asterisk">*</span><?php endif; ?>
			</div>
			<div class="admin_option_description">
				<?php echo ((is_array($_tmp=$this->_tpl_vars['field']->description)) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

			</div>
			<select name="field_<?php echo $this->_tpl_vars['field']->seo_name; ?>
" style="min-width: 200px;">
				<option value="-1">Select item</option>
				<?php $_from = $this->_tpl_vars['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['option']):
?>
				<option value="<?php echo $this->_tpl_vars['option']['id']; ?>
" <?php if ($this->_tpl_vars['option']['id'] == $this->_tpl_vars['field']->value): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['option']['option_name']; ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
			</select>
			<br />
			<br />