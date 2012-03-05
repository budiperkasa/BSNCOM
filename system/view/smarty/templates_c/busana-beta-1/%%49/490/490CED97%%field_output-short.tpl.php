<?php /* Smarty version 2.6.26, created on 2012-02-06 08:50:42
         compiled from content_fields/fields/checkboxes/field_output-short.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'content_fields/fields/checkboxes/field_output-short.tpl', 2, false),)), $this); ?>
					<td class="content_field_output">
						<?php if (count($this->_tpl_vars['checkboxes']) != 0): ?>
							<?php if (count($this->_tpl_vars['checkboxes']) == 1): ?>
								<?php echo $this->_tpl_vars['checkboxes']['0']; ?>

							<?php else: ?>
							<br/>
							<ul class="checkboxes_ul">
							<?php $_from = $this->_tpl_vars['checkboxes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['checkbox']):
?>
								<li><?php echo $this->_tpl_vars['checkbox']; ?>
</li>
							<?php endforeach; endif; unset($_from); ?>
							</ul>
							<?php endif; ?>
						<?php endif; ?>
					</td>