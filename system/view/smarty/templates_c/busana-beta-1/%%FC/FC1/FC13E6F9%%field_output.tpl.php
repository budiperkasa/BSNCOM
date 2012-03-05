<?php /* Smarty version 2.6.26, created on 2012-02-06 08:44:14
         compiled from content_fields/fields/varchar/field_output.tpl */ ?>
					<?php if ($this->_tpl_vars['field']->value != ''): ?>
					<div class="content_field_output">
						<?php if ($this->_tpl_vars['field']->frontend_name): ?><strong><?php echo $this->_tpl_vars['field']->frontend_name; ?>
</strong>:<?php endif; ?> <?php echo $this->_tpl_vars['field']->value; ?>

					</div>
					<?php endif; ?>