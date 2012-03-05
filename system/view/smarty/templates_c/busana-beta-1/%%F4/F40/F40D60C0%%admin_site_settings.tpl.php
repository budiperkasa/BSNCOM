<?php /* Smarty version 2.6.26, created on 2012-02-06 08:56:33
         compiled from settings/admin_site_settings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'translate_content', 'settings/admin_site_settings.tpl', 10, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

				<div class="content">
					<?php echo $this->_tpl_vars['VH']->validation_errors(); ?>

					<h3><?php echo $this->_tpl_vars['LANG_SITE_SETTINGS']; ?>
</h3>
					<form action="" method="post">
					<div class="admin_option">
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_WEBSITE_TITLE']; ?>
<span class="red_asterisk">*</span>
							<?php echo smarty_function_translate_content(array('table' => 'site_settings','field' => 'value','row_id' => 1), $this);?>

						</div>
						<div class="admin_option_description">
							<?php echo $this->_tpl_vars['LANG_WEBSITE_TITLE_DESCR']; ?>

						</div>
						<input type=text name="website_title" value="<?php echo $this->_tpl_vars['site_settings']['website_title']; ?>
" size="100" />
					</div>
					<div class="admin_option">
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_WEBSITE_DESCRIPTION']; ?>

							<?php echo smarty_function_translate_content(array('table' => 'site_settings','field' => 'value','row_id' => 2,'field_type' => 'text'), $this);?>

						</div>
						<div class="admin_option_description">
							<?php echo $this->_tpl_vars['LANG_WEBSITE_DESCRIPTION_DESCR']; ?>

						</div>
						<textarea name="description" cols="60" rows="5"><?php echo $this->_tpl_vars['site_settings']['description']; ?>
</textarea>
					</div>
					<div class="admin_option">
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_WEBSITE_KEYWORDS']; ?>

							<?php echo smarty_function_translate_content(array('table' => 'site_settings','field' => 'value','row_id' => 3,'field_type' => 'keywords'), $this);?>

						</div>
						<div class="admin_option_description">
							<?php echo $this->_tpl_vars['LANG_WEBSITE_KEYWORDS_DESCR']; ?>

						</div>
						<textarea name="keywords" cols="40" rows="5"><?php echo $this->_tpl_vars['keywords']; ?>
</textarea>
					</div>
					<div class="admin_option">
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_EMAILS_SIGNATURE']; ?>

							<?php echo smarty_function_translate_content(array('table' => 'site_settings','field' => 'value','row_id' => 5), $this);?>

						</div>
						<div class="admin_option_description">
							<?php echo $this->_tpl_vars['LANG_EMAILS_SIGNATURE_DESCR']; ?>

						</div>
						<input type="text" name="signature_in_emails" value="<?php echo $this->_tpl_vars['system_settings']['signature_in_emails']; ?>
" size="100" />
					</div>
					<?php if ($this->_tpl_vars['CI']->load->is_module_loaded('payment')): ?>
					<div class="admin_option">
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_COMPANY_DETAILS']; ?>

							<?php echo smarty_function_translate_content(array('table' => 'site_settings','field' => 'value','row_id' => 4,'field_type' => 'text'), $this);?>

						</div>
						<div class="admin_option_description">
							<?php echo $this->_tpl_vars['LANG_COMPANY_DETAILS_DESCR']; ?>

						</div>
						<textarea name="company_details" rows="7"><?php echo $this->_tpl_vars['site_settings']['company_details']; ?>
</textarea>
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