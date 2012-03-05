<?php /* Smarty version 2.6.26, created on 2012-02-07 04:39:55
         compiled from settings/admin_webservices_settings.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

				<div class="content">
					<?php echo $this->_tpl_vars['VH']->validation_errors(); ?>

					<h3><?php echo $this->_tpl_vars['LANG_WEBSERVICES']; ?>
</h3>
					<form action="" method="post">

					<label class="block_title"><?php echo $this->_tpl_vars['LANG_YOUTUBE_SETTINGS']; ?>
</label>
					<div class="admin_option">
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_YOUTUBE_KEY']; ?>

						</div>
						<div class="admin_option_description">
							<a href="http://code.google.com/apis/youtube/dashboard/" target="_blank"><?php echo $this->_tpl_vars['LANG_SIGNUP_SERVICES_LINK']; ?>
</a> <?php echo $this->_tpl_vars['LANG_FOR_A_YOUTUBE']; ?>

						</div>
						<input type=text name="youtube_key" value="<?php echo $this->_tpl_vars['system_settings']['youtube_key']; ?>
" size="80" />
						<br/>
						<br/>
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_YOUTUBE_USERNAME']; ?>

						</div>
						<input type="text" name="youtube_username" value="<?php echo $this->_tpl_vars['system_settings']['youtube_username']; ?>
" size="45" />
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_YOUTUBE_PASSWORD']; ?>

						</div>
						<input type="text" name="youtube_password" value="<?php echo $this->_tpl_vars['system_settings']['youtube_password']; ?>
" size="45" />
						<br />
						<br />
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_YOUTUBE_PRODUCT']; ?>

						</div>
						<input type="text" name="youtube_product_name" value="<?php echo $this->_tpl_vars['system_settings']['youtube_product_name']; ?>
" size="60" />
					</div>

					<label class="block_title"><?php echo $this->_tpl_vars['LANG_ANALYTICS_SETTINGS']; ?>
</label>
					<div class="admin_option">
						<div class="admin_option_description">
							<a href="https://www.google.com/accounts/NewAccount" target="_blank"><?php echo $this->_tpl_vars['LANG_ANALYTICS_ID_LINK']; ?>
</a>
						</div>
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_ANALYTICS_ACCOUNT_ID']; ?>

						</div>
						<input type=text name="google_analytics_account_id" value="<?php echo $this->_tpl_vars['system_settings']['google_analytics_account_id']; ?>
" size="14" />
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_ANALYTICS_PROFILE_ID']; ?>

						</div>
						<input type=text name="google_analytics_profile_id" value="<?php echo $this->_tpl_vars['system_settings']['google_analytics_profile_id']; ?>
" size="8" />
						<br/>
						<br/>
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_ANALYTICS_EMAIL']; ?>

						</div>
						<input type="text" name="google_analytics_email" value="<?php echo $this->_tpl_vars['system_settings']['google_analytics_email']; ?>
" size="45" />
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_ANALYTICS_PASSWORD']; ?>

						</div>
						<input type="text" name="google_analytics_password" value="<?php echo $this->_tpl_vars['system_settings']['google_analytics_password']; ?>
" size="45" />
					</div>
					
					<label class="block_title"><?php echo $this->_tpl_vars['LANG_MOLLOM_SETTINGS']; ?>
</label>
					<div class="admin_option">
						<div class="admin_option_description">
							<?php echo $this->_tpl_vars['LANG_MOLLOM_ACCOUNT_DESCR']; ?>

						</div>
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_MOLLOM_PUBLIC_KEY']; ?>

						</div>
						<input type=text name="mollom_public_key" value="<?php echo $this->_tpl_vars['system_settings']['mollom_public_key']; ?>
" size="45" />
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_MOLLOM_PRIVATE_KEY']; ?>

						</div>
						<input type="text" name="mollom_private_key" value="<?php echo $this->_tpl_vars['system_settings']['mollom_private_key']; ?>
" size="45" />
					</div>

					<input class="button save_button" type=submit name="submit" value="<?php echo $this->_tpl_vars['LANG_BUTTON_SAVE_CHANGES']; ?>
">
					</form>
				</div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>