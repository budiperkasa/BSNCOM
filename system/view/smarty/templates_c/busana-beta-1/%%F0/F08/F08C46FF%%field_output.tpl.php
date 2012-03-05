<?php /* Smarty version 2.6.26, created on 2012-02-06 08:44:14
         compiled from content_fields/fields/select/field_output.tpl */ ?>
					<?php if ($this->_tpl_vars['value'] && $this->_tpl_vars['option_id'] != -1): ?>
					<div class="content_field_output"">
						<strong><?php if ($this->_tpl_vars['field']->frontend_name): ?><?php echo $this->_tpl_vars['field']->frontend_name; ?>
<?php else: ?><?php echo $this->_tpl_vars['field']->name; ?>
<?php endif; ?></strong>: <?php echo $this->_tpl_vars['value']; ?>

					</div>
					<?php endif; ?>