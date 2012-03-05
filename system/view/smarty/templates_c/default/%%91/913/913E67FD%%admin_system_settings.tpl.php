<?php /* Smarty version 2.6.26, created on 2012-02-06 02:19:50
         compiled from settings/admin_system_settings.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

				<div class="content">
					<?php echo $this->_tpl_vars['VH']->validation_errors(); ?>

					<h3><?php echo $this->_tpl_vars['LANG_SYSTEM_SETTINGS']; ?>
</h3>
					<form action="" method="post">
					<?php echo $this->_tpl_vars['image_upload_block']; ?>

					<div class="admin_option">
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_SETTINGS_TYPE_STRUCTURE']; ?>

						</div>
						<label><input type="radio" name="single_type_structure" value="0" <?php if ($this->_tpl_vars['system_settings']['single_type_structure'] == 0): ?>checked<?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_SETTINGS_TYPE_STRUCTURE_MULTI']; ?>
</label>
						<label><input type="radio" name="single_type_structure" value="1" <?php if ($this->_tpl_vars['system_settings']['single_type_structure'] == 1): ?>checked<?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_SETTINGS_TYPE_STRUCTURE_SINGLE']; ?>
</label>
						<i><?php echo $this->_tpl_vars['LANG_SETTINGS_TYPE_STRUCTURE_SINGLE_NOTE']; ?>
</i>
					</div>
					<div class="admin_option">
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_WEBSITE_EMAIL']; ?>
<span class="red_asterisk">*</span>
						</div>
						<div class="admin_option_description">
							<?php echo $this->_tpl_vars['LANG_WEBSITE_EMAIL_DESCR']; ?>

						</div>
						<input type=text name="website_email" value="<?php echo $this->_tpl_vars['system_settings']['website_email']; ?>
" size="40" />
					</div>
					<div class="admin_option">
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_CURRENT_THEME']; ?>
<span class="red_asterisk">*</span>
						</div>
						<select name="design_theme">
							<option value="-1" <?php if ($this->_tpl_vars['system_settings']['design_theme'] == -1): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['LANG_SELECT_THEME']; ?>
</option>
							<?php $_from = $this->_tpl_vars['themes_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
								<option value="<?php echo $this->_tpl_vars['item']; ?>
" <?php if ($this->_tpl_vars['system_settings']['design_theme'] == $this->_tpl_vars['item']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['item']; ?>
</option>
							<?php endforeach; endif; unset($_from); ?>
						</select>
					</div>
					<div class="admin_option">
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_ENABLE_WHAT_SEARCH']; ?>

						</div>
						<label><input type="checkbox" name="global_what_search" value="1" <?php if ($this->_tpl_vars['system_settings']['global_what_search'] == 1): ?>checked<?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_ENABLE']; ?>
</label>
					</div>
					<div class="admin_option">
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_ENABLE_WHERE_SEARCH']; ?>

						</div>
						<label><input type="checkbox" name="global_where_search" value="1" <?php if ($this->_tpl_vars['system_settings']['global_where_search'] == 1): ?>checked<?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_ENABLE']; ?>
</label>
					</div>
					<div class="admin_option">
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_ENABLE_CATEGORIES_SEARCH']; ?>

						</div>
						<label><input type="checkbox" name="global_categories_search" value="1" <?php if ($this->_tpl_vars['system_settings']['global_categories_search'] == 1): ?>checked<?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_ENABLE']; ?>
</label>
					</div>
					<div class="admin_option">
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_DEFAULT_SEARCH_IN_RADIUS_MEASURE']; ?>

						</div>
						<label><input type="radio" name="search_in_raduis_measure" value="miles" <?php if ($this->_tpl_vars['system_settings']['search_in_raduis_measure'] == 'miles'): ?>checked<?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_SEARCH_IN_RADIUS_MILES']; ?>
</label>
						<label><input type="radio" name="search_in_raduis_measure" value="kilometres" <?php if ($this->_tpl_vars['system_settings']['search_in_raduis_measure'] == 'kilometres'): ?>checked<?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_SEARCH_IN_RADIUS_KILOMETRES']; ?>
</label>
					</div>
					<div class="admin_option">
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_ENABLE_ANONYM_RATINGS_REVIEWS']; ?>

						</div>
						<label><input type="checkbox" name="anonym_rates_reviews" value="1" <?php if ($this->_tpl_vars['system_settings']['anonym_rates_reviews'] == 1): ?>checked<?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_ENABLE']; ?>
</label>
					</div>
					<div class="admin_option">
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_CATEGORIES_BLOCK_VIEW']; ?>

						</div>
						<label><input type="radio" name="categories_block_type" value="categories-bar" <?php if ($this->_tpl_vars['system_settings']['categories_block_type'] == 'categories-bar'): ?>checked<?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_NORMAL_CATEGORIES_BAR']; ?>
</label>
						<label><input type="radio" name="categories_block_type" value="categories-bar-jshide" <?php if ($this->_tpl_vars['system_settings']['categories_block_type'] == 'categories-bar-jshide'): ?>checked<?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_JSHIDE_CATEGORIES_BAR']; ?>
</label>
						<label><input type="radio" name="categories_block_type" value="categories-bar-ajax" <?php if ($this->_tpl_vars['system_settings']['categories_block_type'] == 'categories-bar-ajax'): ?>checked<?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_AJAX_CATEGORIES_BAR']; ?>
</label>
					</div>
					<div class="admin_option">
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_ENABLE_CONTACTUS_PAGE']; ?>

						</div>
						<label><input type="checkbox" name="enable_contactus_page" value="1" <?php if ($this->_tpl_vars['system_settings']['enable_contactus_page'] == 1): ?>checked<?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_ENABLE']; ?>
</label>
					</div>
					<div class="admin_option">
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_PATH_TO_TERMS_CONDITIONS']; ?>

						</div>
						<div class="admin_option_description">
							<?php echo $this->_tpl_vars['LANG_PATH_TO_TERMS_CONDITIONS_DESCR']; ?>

						</div>
						<input type="text" name="path_to_terms_and_conditions" value="<?php echo $this->_tpl_vars['system_settings']['path_to_terms_and_conditions']; ?>
" size="60" />
					</div>

					<input class="button save_button" type=submit name="submit" value="<?php echo $this->_tpl_vars['LANG_BUTTON_SAVE_CHANGES']; ?>
">
					&nbsp;&nbsp;
					<input class="button delete_button" type=submit name="clear_cache" value="<?php echo $this->_tpl_vars['LANG_BUTTON_CLEAR_CACHE']; ?>
">
					&nbsp;&nbsp;
					<input class="button activate_button" type=submit name="synchronize_users_content" value="<?php echo $this->_tpl_vars['LANG_BUTTON_SYNCHRONIZE_USERS_CONTENT']; ?>
">
					</form>
				</div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>