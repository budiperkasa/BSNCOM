<?php /* Smarty version 2.6.26, created on 2012-02-06 02:18:21
         compiled from authorization/admin_login.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<script language="Javascript" type="text/javascript">
$(document).ready(function() {
	$("input:text:visible:first").focus();
});
</script>

		<div class="content">
			<?php echo $this->_tpl_vars['VH']->validation_errors(); ?>

			<div id="login_form">
				<form action="" method="post">
				<?php echo $this->_tpl_vars['LANG_LOGIN_EMAIL']; ?>
:<br>
				<input type="text" name="email" value="<?php echo $this->_tpl_vars['CI']->validation->email; ?>
" class="login_input" size="45"><br>
				<div class="px5"></div>
				<?php echo $this->_tpl_vars['LANG_LOGIN_PASSWORD']; ?>
:<br>
				<input type="password" name="password" class="login_input" size="45">
				<div class="px5"></div>
				<input type="checkbox" name="remember_me"> <?php echo $this->_tpl_vars['LANG_REMEMBER_ME']; ?>

				<div class="px10"></div>
				<input type="submit" name="submit" value="<?php echo $this->_tpl_vars['LANG_BUTTON_LOGIN']; ?>
" class="front-btn">
				</form>
				<div class="px5"></div>
				<div class="login_block_link"><?php echo $this->_tpl_vars['VH']->anchor('register',$this->_tpl_vars['LANG_CREATE_ACCOUNT']); ?>
</div>
				<div class="login_block_link"><?php echo $this->_tpl_vars['VH']->anchor('pass_recovery_step1',$this->_tpl_vars['LANG_FORGOT_PASS']); ?>
</div>
			</div>
		</div>
           
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>