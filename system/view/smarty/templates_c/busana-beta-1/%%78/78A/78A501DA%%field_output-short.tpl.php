<?php /* Smarty version 2.6.26, created on 2012-02-06 08:50:42
         compiled from content_fields/fields/website/field_output-short.tpl */ ?>
					<td class="content_field_output">
						<?php if ($this->_tpl_vars['field']->value): ?>
							<?php if (! $this->_tpl_vars['enable_redirect']): ?>
								<a href="<?php echo $this->_tpl_vars['value']; ?>
" target="_blank" title="<?php echo $this->_tpl_vars['value']; ?>
"><?php echo $this->_tpl_vars['value']; ?>
</a>
							<?php else: ?>
								<a href="<?php echo $this->_tpl_vars['VH']->site_url("redirect/".($this->_tpl_vars['field_value_id'])); ?>
" rel="nofollow" target="_blank" title="<?php echo $this->_tpl_vars['value']; ?>
"><?php echo $this->_tpl_vars['value']; ?>
</a>
							<?php endif; ?>
						<?php endif; ?>
					</td>