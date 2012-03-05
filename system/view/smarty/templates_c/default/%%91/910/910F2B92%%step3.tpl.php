<?php /* Smarty version 2.6.26, created on 2012-02-06 02:18:04
         compiled from install/step3.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

			<div class="content">
				<?php echo $this->_tpl_vars['VH']->validation_errors(); ?>

				<h3><?php echo $this->_tpl_vars['LANG_INSTALL_STEP3_TITLE']; ?>
</h3>
				<h4><?php echo $this->_tpl_vars['LANG_INSTALL_STEP3_SUBTITLE']; ?>
</h4>
				<br/>
				<br/>
				<form action="" method="post">
				<input type="submit" name="submit" value="<?php echo $this->_tpl_vars['LANG_INSTALL_FINISH_BUTTON']; ?>
" class="button save_button">
				</form>
			</div>
           
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>