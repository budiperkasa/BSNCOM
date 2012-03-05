<?php /* Smarty version 2.6.26, created on 2012-02-17 07:07:52
         compiled from frontend/pass_recovery_step1.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "frontend/header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

			<tr>
				<td id="search_bar" colspan="3">
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "frontend/search_block.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
				</td>
			</tr>
			<tr>
				<td id="left_sidebar">
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "frontend/left-sidebar.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
				</td>
      			<td id="content_block" valign="top">
      				<div id="content_wrapper">
      					<?php echo $this->_tpl_vars['VH']->validation_errors(); ?>

                         <h1><?php echo $this->_tpl_vars['LANG_PASSWORD_RECOVERY1']; ?>
</h1>

                         <form action="" method="post">
	                     <div class="admin_option">
	                          <div class="admin_option_name">
	                          	<?php echo $this->_tpl_vars['LANG_YOUR_EMAIL']; ?>
<span class="red_asterisk">*</span>
	                          </div>
	                          <input type=text name="email" value="<?php echo $this->_tpl_vars['email']; ?>
" size="40" class="admin_option_input">
	                     </div>
	                     <div class="admin_option">
	                          <div class="admin_option_name">
	                          	<?php echo $this->_tpl_vars['LANG_FILL_CAPTCHA']; ?>
<span class="red_asterisk">*</span>
	                          </div>
	                          <input type="text" name="captcha" size="4">
	                          <div class="px10"></div>
	                          <?php echo $this->_tpl_vars['captcha']->view(); ?>

						 <div>
						 <div class="px5"></div>
	                     <input class="front-btn" type=submit name="submit" value="<?php echo $this->_tpl_vars['LANG_BUTTON_PASSWORD_RECOVERY']; ?>
">
	                     </form>
                 	</div>
				</td>
                <td id="right_sidebar">
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "frontend/right-sidebar.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </td>
			</tr>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "frontend/footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>