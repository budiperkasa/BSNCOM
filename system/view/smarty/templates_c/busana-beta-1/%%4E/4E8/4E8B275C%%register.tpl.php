<?php /* Smarty version 2.6.26, created on 2012-02-06 03:36:08
         compiled from frontend/register.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'frontend/register.tpl', 3, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "frontend/header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if (count($this->_tpl_vars['users_groups_allowed']) > 1): ?>
<script language="JavaScript" type="text/javascript">
var url = '<?php echo $this->_tpl_vars['registation_url']; ?>
';
$(document).ready(function() {
	$(".account_type_item").click(function() {
		group_id = $(this).val();
		url = url+'group_id/'+group_id+'/';
		$("#account_type_form").attr('action', url);
		$("#account_type_form").submit();
	});
});
</script>
<?php endif; ?>

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

                         <h1><?php echo $this->_tpl_vars['LANG_CREATE_ACCOUNT']; ?>
</h1>
                         
                         <?php if (count($this->_tpl_vars['users_groups_allowed']) > 1): ?>
                         <form action="" method="post" id="account_type_form">
	                     <div class="admin_option noborder">
	                          <div class="admin_option_name" >
	                          	<?php echo $this->_tpl_vars['LANG_SELECT_ACCOUNT_TYPE']; ?>
<span class="red_asterisk">*</span>:
	                          </div>
	                          <?php $_from = $this->_tpl_vars['users_groups_allowed']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['group_item']):
?>
	                          <label><input type="radio" name="select_group" value="<?php echo $this->_tpl_vars['group_item']->id; ?>
" class="account_type_item admin_option_input" <?php if ($this->_tpl_vars['group_item']->id == $this->_tpl_vars['registration_user_group']->id): ?>checked<?php endif; ?>/> <?php echo $this->_tpl_vars['group_item']->name; ?>
</label>
	                          <?php endforeach; endif; unset($_from); ?>
	                     </div>
	                     </form>
	                     <?php endif; ?>

                         <form action="" method="post">
	                     <div class="admin_option noborder">
	                          <div class="admin_option_name" >
	                          	<?php echo $this->_tpl_vars['LANG_LOGIN']; ?>
<span class="red_asterisk">*</span>
	                          </div>
	                          <div class="admin_option_description">
	                          	<?php echo $this->_tpl_vars['LANG_LOGIN_DESCR']; ?>

	                          </div>
	                          <input type=text name="login" value="<?php echo $this->_tpl_vars['user']->login; ?>
" size="50" class="admin_option_input">
	                     </div>
	                     <div class="admin_option">
	                          <div class="admin_option_name">
	                          	<?php echo $this->_tpl_vars['LANG_EMAIL']; ?>
<span class="red_asterisk">*</span>
	                          </div>
	                          <input type=text name="email" value="<?php echo $this->_tpl_vars['user']->email; ?>
" size="50" class="admin_option_input">
	                     </div>
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

						 <div class="admin_option">
	                          <div class="admin_option_name">
	                          	<?php echo $this->_tpl_vars['LANG_FILL_CAPTCHA']; ?>
<span class="red_asterisk">*</span>
	                          </div>
	                          <input type="text" name="captcha" size="4">
	                          <div class="px10"></div>
	                          <?php if ($this->_tpl_vars['captcha']): ?>
	                          <?php echo $this->_tpl_vars['captcha']->view(); ?>

	                          <?php else: ?>
	                          <?php echo $this->_tpl_vars['LANG_USERS_CONTENT_ERROR']; ?>

	                          <?php endif; ?>
						 <div>

						 <?php if ($this->_tpl_vars['system_settings']['path_to_terms_and_conditions']): ?>
						 <div class="admin_option">
	                          <div class="admin_option_name">
	                          	<input type="checkbox" name="terms_agreement" value="1" />&nbsp;&nbsp;<?php echo $this->_tpl_vars['LANG_TERMS_CONDITIONS_1']; ?>
 <a href="<?php echo $this->_tpl_vars['VH']->site_url($this->_tpl_vars['system_settings']['path_to_terms_and_conditions']); ?>
" target="_blank"><?php echo $this->_tpl_vars['LANG_TERMS_CONDITIONS_2']; ?>
</a>
	                          </div>
						 <div>
						 <?php endif; ?>

						 <div class="px5"></div>
	                     <input class="front-btn" type=submit name="submit" value="<?php echo $this->_tpl_vars['LANG_BUTTON_CREATE_ACCOUNT']; ?>
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