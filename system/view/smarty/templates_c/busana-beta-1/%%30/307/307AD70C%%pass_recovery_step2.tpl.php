<?php /* Smarty version 2.6.26, created on 2012-02-17 07:09:05
         compiled from frontend/pass_recovery_step2.tpl */ ?>
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

                         <h1><?php echo $this->_tpl_vars['LANG_PASSWORD_RECOVERY2']; ?>
</h1>

                         <form action="" method="post">
	                     <div class="admin_option">
	                          <div class="admin_option_name">
	                          	<?php echo $this->_tpl_vars['LANG_PASSWORD']; ?>
<span class="red_asterisk">*</span>
	                          </div>
	                          <div class="admin_option_description">
	                          	<?php echo $this->_tpl_vars['LANG_PASSWORD_DESCR']; ?>

	                          </div>
	                          <input type=password name="password" size="50" class="admin_option_input">
	                          <div class="admin_option_name">
	                          	<?php echo $this->_tpl_vars['LANG_PASSWORD_REPEAT']; ?>
<span class="red_asterisk">*</span>
	                          </div>
	                          <input type=password name="repeat_password" size="50" class="admin_option_input">
	                     </div>
						 <div class="px5"></div>
	                     <input class="front-btn" type=submit name="submit" value="<?php echo $this->_tpl_vars['LANG_BUTTON_PASSWORD_RECOVERY2']; ?>
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