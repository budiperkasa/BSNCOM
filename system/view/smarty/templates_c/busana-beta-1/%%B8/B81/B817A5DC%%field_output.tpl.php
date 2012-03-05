<?php /* Smarty version 2.6.26, created on 2012-02-06 08:44:14
         compiled from content_fields/fields/checkboxes/field_output.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'content_fields/fields/checkboxes/field_output.tpl', 1, false),)), $this); ?>
					<?php if (count($this->_tpl_vars['checkboxes']) != 0): ?>
					<div class="content_field_output">
						<strong><?php if ($this->_tpl_vars['field']->frontend_name): ?><?php echo $this->_tpl_vars['field']->frontend_name; ?>
<?php else: ?><?php echo $this->_tpl_vars['field']->name; ?>
<?php endif; ?></strong>:
						<ul class="checkboxes_ul">
						<?php $_from = $this->_tpl_vars['checkboxes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['checkbox']):
?>
							<li><?php echo $this->_tpl_vars['checkbox']['option_name']; ?>
</li>
						<?php endforeach; endif; unset($_from); ?>
						</ul>
					</div>
					<?php endif; ?>