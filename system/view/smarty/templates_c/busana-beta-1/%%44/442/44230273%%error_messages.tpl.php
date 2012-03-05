<?php /* Smarty version 2.6.26, created on 2012-02-06 04:30:22
         compiled from backend/error_messages.tpl */ ?>
<?php if ($this->_tpl_vars['error_msgs'] != ''): ?>
				<div class="error_msgs">
					<ul>
					<?php $_from = $this->_tpl_vars['error_msgs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['error_item']):
?>
						<li><?php echo $this->_tpl_vars['error_item']; ?>
</li>
					<?php endforeach; endif; unset($_from); ?>
					</ul>
				</div>
<?php endif; ?>