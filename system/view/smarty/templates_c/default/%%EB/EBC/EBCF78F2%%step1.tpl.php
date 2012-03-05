<?php /* Smarty version 2.6.26, created on 2012-02-06 02:11:48
         compiled from install/step1.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

			<div class="content">
				<?php echo $this->_tpl_vars['VH']->validation_errors(); ?>

				<h3><?php echo $this->_tpl_vars['LANG_INSTALL_STEP1_TITLE']; ?>
</h3>
				<h4><?php echo $this->_tpl_vars['LANG_INSTALL_STEP1_SUBTITLE']; ?>
</h4>

				<form action="" method="post">
				<?php echo $this->_tpl_vars['LANG_INSTALL_USER_LOGIN']; ?>
<span class="red_asterisk">*</span><br>
				<input type="text" name="user_login" value="<?php echo $this->_tpl_vars['CI']->form_validation->set_value('user_login'); ?>
" size="40"><br><br>
				<?php echo $this->_tpl_vars['LANG_INSTALL_USER_EMAIL']; ?>
<span class="red_asterisk">*</span><br>
				<input type="text" name="user_email" value="<?php echo $this->_tpl_vars['CI']->form_validation->set_value('user_email'); ?>
" size="40"><br><br>
				<?php echo $this->_tpl_vars['LANG_INSTALL_USER_PASSWORD']; ?>
<span class="red_asterisk">*</span><br>
				<input type="password" name="user_password" value="<?php echo $this->_tpl_vars['CI']->validation->password; ?>
"><br><br>
				<?php echo $this->_tpl_vars['LANG_INSTALL_USER_PASSWORD_REPEAT']; ?>
<span class="red_asterisk">*</span><br>
				<input type="password" name="user_password_repeat" value="<?php echo $this->_tpl_vars['CI']->validation->password_repeat; ?>
"><br>
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