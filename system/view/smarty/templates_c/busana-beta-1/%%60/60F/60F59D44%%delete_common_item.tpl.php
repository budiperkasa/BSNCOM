<?php /* Smarty version 2.6.26, created on 2012-02-06 04:39:18
         compiled from backend/delete_common_item.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'backend/delete_common_item.tpl', 20, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<!-- 

There are 4 arguments, passed to this template
$options - array of arguments those will be called back in the controller, pass option values in array keys, but names in array values
$hidden - array of hidden arguments those will be called back in the controller
$heading - heading of the template
$question - question message

 -->

                <div class="content">
                     <h3><?php echo $this->_tpl_vars['heading']; ?>
</h3>
                     <form action="" method="post">
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['question']; ?>

                          </div>
                          <div class="admin_option_description">
                          	<?php if (count($this->_tpl_vars['options']) > 1): ?>
                          		<ul>
                          		<?php $_from = $this->_tpl_vars['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['option']):
?>
                          			<?php if ($this->_tpl_vars['option'] != '' && $this->_tpl_vars['option'] != null): ?>
                          			<li><?php echo $this->_tpl_vars['option']; ?>
</li>
                          			<?php else: ?>
                          			<li>(No Title)</li>
                          			<?php endif; ?>
                          			<input type="hidden" name="options[]" value="<?php echo $this->_tpl_vars['key']; ?>
" />
                          		<?php endforeach; endif; unset($_from); ?>
                          		</ul>
                          	<?php elseif (count($this->_tpl_vars['options']) == 1): ?>
                          		<?php $_from = $this->_tpl_vars['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['option']):
?>
	                          		<?php if ($this->_tpl_vars['option'] != '' && $this->_tpl_vars['option'] != null): ?>
	                          			<?php echo $this->_tpl_vars['option']; ?>

	                          		<?php else: ?>
	                          			(No Title)
	                          		<?php endif; ?>
	                          		<input type="hidden" name="options[]" value="<?php echo $this->_tpl_vars['key']; ?>
" />
                          		<?php endforeach; endif; unset($_from); ?>
                          	<?php else: ?>
                          		&nbsp;
                          	<?php endif; ?>
                          </div>
                          <br />
                          <br />

                          <?php $_from = $this->_tpl_vars['hidden']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['option']):
?>
                          <input type="hidden" name="<?php echo $this->_tpl_vars['key']; ?>
" value="<?php echo $this->_tpl_vars['option']; ?>
" />
                          <?php endforeach; endif; unset($_from); ?>

                          <input type=submit name="yes" value="<?php echo $this->_tpl_vars['LANG_YES']; ?>
">&nbsp;&nbsp;&nbsp;&nbsp;
                          <input type=submit name="no" value="<?php echo $this->_tpl_vars['LANG_NO']; ?>
">
                     </div>
                </div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>