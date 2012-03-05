<?php /* Smarty version 2.6.26, created on 2012-02-06 03:58:57
         compiled from users/admin_create_user.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'users/admin_create_user.tpl', 3, false),array('function', 'translate_content', 'users/admin_create_user.tpl', 65, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if (count($this->_tpl_vars['users_groups']) > 1): ?>
<script language="JavaScript" type="text/javascript">
var url = '<?php echo $this->_tpl_vars['registation_url']; ?>
';
$(document).ready(function() {
	$(".account_type_item").click(function() {
		group_id = $(this).val();
		url = '<?php echo $this->_tpl_vars['url']; ?>
'+'group_id/'+group_id+'/';
		$("#account_type_form").attr('action', url);
		$("#account_type_form").submit();
	});
});
</script>
<?php endif; ?>

                <div class="content">
                	<?php echo $this->_tpl_vars['VH']->validation_errors(); ?>

                	<h3><?php echo $this->_tpl_vars['LANG_USER_CRETE_TITLE']; ?>
</h3>
                	
                	<?php if (count($this->_tpl_vars['users_groups']) > 1): ?>
                	<form action="" method="post" id="account_type_form">
                		<div class="admin_option">
                			<div class="admin_option_name" >
                				<?php echo $this->_tpl_vars['LANG_SELECT_ACCOUNT_TYPE']; ?>
<span class="red_asterisk">*</span>:
                			</div>
                			<?php $_from = $this->_tpl_vars['users_groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['group']):
?>
                				<label><input type="radio" name="select_group" value="<?php echo $this->_tpl_vars['group']->id; ?>
" class="account_type_item admin_option_input" <?php if ($this->_tpl_vars['group']->id == $this->_tpl_vars['selected_users_group_id']): ?>checked<?php endif; ?>/> <?php echo $this->_tpl_vars['group']->name; ?>
</label>
                			<?php endforeach; endif; unset($_from); ?>
                		</div>
                	</form>
					<?php endif; ?>

					<form action="" method="post">
					<div class="admin_option">
                     	<div style="float: left;">
                     		<div class="admin_option_name" >
                     			<?php echo $this->_tpl_vars['LANG_USER_LOGIN']; ?>
<span class="red_asterisk">*</span>
                     		</div>
                     		<div class="admin_option_description">
                     			<?php echo $this->_tpl_vars['LANG_USER_LOGIN_DESCR']; ?>

                     		</div>
                     		<input type=text name="login" value="<?php echo $this->_tpl_vars['user']->login; ?>
" size="50" class="admin_option_input">
                     		<?php if ($this->_tpl_vars['user']->users_group->is_own_page && $this->_tpl_vars['user']->users_group->use_seo_name): ?>
                     		&nbsp;&nbsp;<span class="seo_rewrite" title="<?php echo $this->_tpl_vars['LANG_WRITE_SEO_STYLE']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/arrow_seo.gif"></span>&nbsp;&nbsp;
                     		<?php endif; ?>
						</div>
						<?php if ($this->_tpl_vars['user']->users_group->is_own_page && $this->_tpl_vars['user']->users_group->use_seo_name): ?>
						<div style="float: left;">
							<div class="admin_option_name" >
								<?php echo $this->_tpl_vars['LANG_USER_SEO_LOGIN']; ?>

							</div>
							<div class="admin_option_description">
								<?php echo $this->_tpl_vars['LANG_SEO_DESCR']; ?>

							</div>
							<input type=text name="seo_login" id="seo_login" value="<?php echo $this->_tpl_vars['user']->seo_login; ?>
" size="50" class="admin_option_input">
						</div>
						<?php endif; ?>
						<div style="clear: both"></div>
					</div>
					<?php if ($this->_tpl_vars['user']->users_group->is_own_page && $this->_tpl_vars['user']->users_group->meta_enabled): ?>
					<div class="admin_option">
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_USER_META_DESCRIPTION']; ?>

							<?php echo smarty_function_translate_content(array('table' => 'users','field' => 'meta_description','row_id' => $this->_tpl_vars['user']->id,'field_type' => 'text'), $this);?>

						</div>
						<div class="admin_option_description">
							<?php echo $this->_tpl_vars['LANG_USER_META_DESCRIPTION_DESCR']; ?>

						</div>
						<textarea name="meta_description" cols="40" rows="5"><?php echo $this->_tpl_vars['user']->meta_description; ?>
</textarea>
						
						<div class="px10"></div>
						
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_USER_KEYWORDS']; ?>

							<?php echo smarty_function_translate_content(array('table' => 'users','field' => 'meta_keywords','row_id' => $this->_tpl_vars['user']->id,'field_type' => 'text'), $this);?>

						</div>
						<div class="admin_option_description">
							<?php echo $this->_tpl_vars['LANG_USER_KEYWORDS_DESCR']; ?>

						</div>
						<textarea name="meta_keywords" cols="40" rows="5"><?php echo $this->_tpl_vars['user']->meta_keywords; ?>
</textarea>
					</div>
					<?php endif; ?>

					<div class="admin_option">
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_USER_EMAIL']; ?>
<span class="red_asterisk">*</span>
						</div>
						<input type=text name="email" value="<?php echo $this->_tpl_vars['user']->email; ?>
" size="50" class="admin_option_input">
					</div>
					<div class="admin_option">
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_PASSWORD']; ?>

						</div>
						<div class="admin_option_description">
							<?php echo $this->_tpl_vars['LANG_PASSWORD_DESCR']; ?>

						</div>
						<input type=password name="password" size="50" class="admin_option_input">
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_PASSWORD_REPEAT']; ?>

						</div>
						<input type=password name="repeat_password" size="50" class="admin_option_input">
					</div>
					<?php if ($this->_tpl_vars['user']->users_group->logo_enabled): ?>
						<?php echo $this->_tpl_vars['image_upload_block']->setUploadBlock('files_upload/user_logo_upload_block.tpl'); ?>

					<?php endif; ?>
					<?php if ($this->_tpl_vars['user']->content_fields->fieldsCount()): ?>
					<label class="block_title"><?php echo $this->_tpl_vars['LANG_LISTING_ADDITIONAL_INFORMATION']; ?>
</label>
					<div class="admin_option">
						<?php echo $this->_tpl_vars['user']->inputMode(); ?>

					</div>
					<?php endif; ?>
					<input class="button save_button" type=submit name="submit" value="<?php echo $this->_tpl_vars['LANG_BUTTON_SAVE_CHANGES']; ?>
">
					</form>
				</div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>