<?php /* Smarty version 2.6.26, created on 2012-02-06 09:47:48
         compiled from frontend/contactus_page.tpl */ ?>
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
	      				<div class="content">
	      					<?php echo $this->_tpl_vars['VH']->validation_errors(); ?>

	                         <h1><?php echo $this->_tpl_vars['LANG_CONTACT_US_TITLE']; ?>
</h1>
	
	                         <form action="" method="post">
		                     <div class="admin_option noborder">
		                          <div class="admin_option_name" >
		                          	<?php echo $this->_tpl_vars['LANG_CONTACTUS_NAME']; ?>
<span class="red_asterisk">*</span>
		                          </div>
		                          <input type=text name="name" value="<?php echo $this->_tpl_vars['name']; ?>
" size="50" class="admin_option_input">
		                     </div>
		                     <div class="admin_option">
		                          <div class="admin_option_name">
		                          	<?php echo $this->_tpl_vars['LANG_CONTACTUS_EMAIL']; ?>
<span class="red_asterisk">*</span>
		                          </div>
		                          <input type=text name="email" value="<?php echo $this->_tpl_vars['email']; ?>
" size="50" class="admin_option_input">
		                     </div>
		                     <div class="admin_option noborder">
		                          <div class="admin_option_name" >
		                          	<?php echo $this->_tpl_vars['LANG_CONTACTUS_SUBJECT']; ?>
<span class="red_asterisk">*</span>
		                          </div>
		                          <input type=text name="subject" value="<?php echo $this->_tpl_vars['subject']; ?>
" size="50" class="admin_option_input">
		                     </div>
		                     <div class="admin_option">
		                          <div class="admin_option_name">
		                          	<?php echo $this->_tpl_vars['LANG_CONTACTUS_BODY']; ?>
<span class="red_asterisk">*</span>
		                          </div>
		                          <textarea name="message" rows="12" class="admin_option_input"><?php echo $this->_tpl_vars['message']; ?>
</textarea>
		                     </div>
		                     <?php echo $this->_tpl_vars['content_fields']->inputMode(); ?>

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
		                     <input class="front-btn" type=submit name="submit" value="<?php echo $this->_tpl_vars['LANG_CONTACTUS_SEND_BUTTON']; ?>
">
		                     </form>
	                 	</div>
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