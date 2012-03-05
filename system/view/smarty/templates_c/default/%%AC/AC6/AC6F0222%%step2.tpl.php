<?php /* Smarty version 2.6.26, created on 2012-02-06 02:13:26
         compiled from install/step2.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

			<div class="content">
				<?php echo $this->_tpl_vars['VH']->validation_errors(); ?>

				<h3><?php echo $this->_tpl_vars['LANG_INSTALL_STEP2_TITLE']; ?>
</h3>
				<form action="" method="post">
				<?php echo $this->_tpl_vars['LANG_INSTALL_WEBSITE_TITLE']; ?>
<span class="red_asterisk">*</span><br>
				<input type="text" name="website_title" value="<?php echo $this->_tpl_vars['CI']->form_validation->set_value('website_title'); ?>
" size="40"><br><br>
				<?php echo $this->_tpl_vars['LANG_INSTALL_WEBSITE_EMAIL']; ?>
<span class="red_asterisk">*</span><br>
				<input type="text" name="website_email" value="<?php echo $this->_tpl_vars['CI']->form_validation->set_value('website_email'); ?>
" size="40"><br><br>
				<br/>
				<br/>
				<input type="submit" name="submit" value="<?php echo $this->_tpl_vars['LANG_INSTALL_BUTTON']; ?>
" class="button save_button">
				</form>
			</div>
           
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>