<?php /* Smarty version 2.6.26, created on 2012-02-08 07:41:16
         compiled from users/admin_users_profiles.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'translate_content', 'users/admin_users_profiles.tpl', 43, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

                <div class="content">
                	<?php echo $this->_tpl_vars['VH']->validation_errors(); ?>

                	<h3><?php echo $this->_tpl_vars['LANG_USER_PROFILE']; ?>
 "<?php echo $this->_tpl_vars['user']->login; ?>
"</h3>

					<?php if ($this->_tpl_vars['user']->id != 'new'): ?>
					<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "users/admin_user_options_menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
					<?php endif; ?>

					<form action="" method="post">
                    <input type=hidden name="id" value="<?php echo $this->_tpl_vars['user']->id; ?>
">
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
					<?php if ($this->_tpl_vars['user']->users_group->logo_enabled && $this->_tpl_vars['facebook_logo_file']): ?>
					<div class="admin_option">
						<div class="admin_option_name" >
							<?php echo $this->_tpl_vars['LANG_FACEBOOK_USER_LOGO_IMAGE']; ?>

						</div>
						<img src="<?php echo $this->_tpl_vars['facebook_logo_file']; ?>
" />
						<div class="px5"></div>
						<label><input type="checkbox" name="use_facebook_logo" value="1" <?php if ($this->_tpl_vars['user']->use_facebook_logo): ?>checked<?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_USE_FACEBOOK_LOGO_IMAGE']; ?>
</label>
						<input type="hidden" name="facebook_logo_file" value="<?php echo $this->_tpl_vars['facebook_logo_file']; ?>
"/>
					</div>
					<?php endif; ?>
					<?php if ($this->_tpl_vars['user']->users_group->logo_enabled): ?>
						<?php echo $this->_tpl_vars['image_upload_block']->setUploadBlock('files_upload/user_logo_upload_block.tpl'); ?>

					<?php endif; ?>
					<div class="admin_option">
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_USER_STATUS']; ?>

						</div>
						<select name="status">
							<option value="1" <?php if ($this->_tpl_vars['user']->status == 1): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG_USER_STATUS_UNVERIFIED']; ?>
</option>
							<option value="2" <?php if ($this->_tpl_vars['user']->status == 2): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG_USER_STATUS_ACTIVE']; ?>
</option>
							<option value="3" <?php if ($this->_tpl_vars['user']->status == 3): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG_USER_STATUS_BLOCKED']; ?>
</option>
						</select>
					</div>
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