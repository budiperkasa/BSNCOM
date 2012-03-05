<?php /* Smarty version 2.6.26, created on 2012-02-06 03:20:36
         compiled from content_fields/admin_field_settings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'translate_content', 'content_fields/admin_field_settings.tpl', 28, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $this->assign('field_id', $this->_tpl_vars['field']->id); ?>

				<div class="content">
					<?php echo $this->_tpl_vars['VH']->validation_errors(); ?>

					<h3><?php if ($this->_tpl_vars['field_id'] != 'new'): ?><?php echo $this->_tpl_vars['LANG_EDIT_FIELD']; ?>
<?php else: ?><?php echo $this->_tpl_vars['LANG_CREATE_FIELD']; ?>
<?php endif; ?></h3>

					<?php if ($this->_tpl_vars['field_id'] != 'new'): ?>
						<?php if ($this->_tpl_vars['field']->configuration_page): ?>
							<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/configure/".($this->_tpl_vars['field_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_CONFIGURE_FIELD_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_gear.png"></a>
							<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/configure/".($this->_tpl_vars['field_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_CONFIGURE_FIELD_OPTION']; ?>
</a>&nbsp;&nbsp;&nbsp;&nbsp;
						<?php endif; ?>
						<?php if ($this->_tpl_vars['field']->search_configuration_page): ?>
                         	<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/configure_search/".($this->_tpl_vars['field_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_CONFIGURE_SEARCH_FIELD_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/page_find.png"></a>
                         	<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/configure_search/".($this->_tpl_vars['field_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_CONFIGURE_SEARCH_FIELD_OPTION']; ?>
</a>&nbsp;&nbsp;&nbsp;&nbsp;
                        <?php endif; ?>
						<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/delete/".($this->_tpl_vars['field_id'])); ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_delete.png"></a>
						<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/delete/".($this->_tpl_vars['field_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_DELETE_FIELD_OPTION']; ?>
</a>&nbsp;&nbsp;&nbsp;&nbsp;
						<br />
						<br />
					<?php endif; ?>

					<form action="" method="post">
					<div class="admin_option">
						<div style="float: left;">
							<div class="admin_option_name" >
								<?php echo $this->_tpl_vars['LANG_FIELD_NAME']; ?>
<span class="red_asterisk">*</span>
								<?php echo smarty_function_translate_content(array('table' => 'content_fields','field' => 'name','row_id' => $this->_tpl_vars['field_id']), $this);?>

							</div>
							<div class="admin_option_description">
								<?php echo $this->_tpl_vars['LANG_FIELD_NAME_DESCR']; ?>

							</div>
							<input type=text name="name" value="<?php echo $this->_tpl_vars['field']->name; ?>
" size="40" class="admin_option_input">
							&nbsp;&nbsp;<span class="seo_rewrite" title="<?php echo $this->_tpl_vars['LANG_WRITE_SEO_STYLE']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/arrow_seo.gif"></span>&nbsp;&nbsp;
						</div>
						<div style="float: left;">
							<div class="admin_option_name">
								<?php echo $this->_tpl_vars['LANG_FIELD_SEO_NAME']; ?>
<span class="red_asterisk">*</span>
							</div>
							<div class="admin_option_description">
								<?php echo $this->_tpl_vars['LANG_FIELD_SEO_NAME_DESCR']; ?>

							</div>
							<input type=text name="seo_name" id="seo_name" value="<?php echo $this->_tpl_vars['field']->seo_name; ?>
" size="40" class="admin_option_input">
						</div>
						<div style="clear: both"></div>
						<br />
						<div class="admin_option_name" >
							<?php echo $this->_tpl_vars['LANG_FIELD_FRONTEND_NAME']; ?>

							<?php echo smarty_function_translate_content(array('table' => 'content_fields','field' => 'frontend_name','row_id' => $this->_tpl_vars['field_id']), $this);?>

						</div>
						<div class="admin_option_description">
							<?php echo $this->_tpl_vars['LANG_FIELD_FRONTEND_NAME_DESCR']; ?>

						</div>
						<input type=text name="frontend_name" value="<?php echo $this->_tpl_vars['field']->frontend_name; ?>
" size="40" class="admin_option_input">
					</div>

					<?php if ($this->_tpl_vars['field_id'] == 'new'): ?>
					<div class="admin_option">
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_FIELD_TYPE']; ?>
<span class="red_asterisk">*</span>
                          </div>
                          <div class="admin_option_description">
                          	<?php echo $this->_tpl_vars['LANG_FIELD_TYPE_DESCR']; ?>

                          </div>
                          <select name="type" style="width:200px">
                          <?php $_from = $this->_tpl_vars['field_types']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['type_key'] => $this->_tpl_vars['type']):
?>
                          		<option value="<?php echo $this->_tpl_vars['type_key']; ?>
" <?php if ($this->_tpl_vars['field']->type == $this->_tpl_vars['type_key']): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['type']->name; ?>
</option>
                          <?php endforeach; endif; unset($_from); ?>
                          </select>
                     </div>
                     <?php endif; ?>
                     
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_FIELD_DESCRIPTION']; ?>

                          	<?php echo smarty_function_translate_content(array('table' => 'content_fields','field' => 'description','row_id' => $this->_tpl_vars['field_id'],'field_type' => 'text'), $this);?>

                          </div>
                          <div class="admin_option_description">
                          	<?php echo $this->_tpl_vars['LANG_FIELD_DESCRIPTION_DESCR']; ?>

                          </div>
                          <textarea name="description" rows="5" cols="80"><?php echo $this->_tpl_vars['field']->description; ?>
</textarea>
                     </div>
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_FIELD_REQUIRED']; ?>

                          </div>
                          <label><input type="checkbox" name="required" value="1" <?php if ($this->_tpl_vars['field']->required == 1): ?>checked<?php endif; ?>> <?php echo $this->_tpl_vars['LANG_REQUIRED_TH']; ?>
</label>
                     </div>
                     
                     <label class="block_title"><?php echo $this->_tpl_vars['LANG_FIELD_VISIBILITY']; ?>
</label>
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_FIELD_VISIBILITY_INDEX']; ?>

                          </div>
                          <label><input type="checkbox" name="v_index_page" value="1" <?php if ($this->_tpl_vars['field']->v_index_page == 1): ?>checked<?php endif; ?>> <?php echo $this->_tpl_vars['LANG_FIELD_VISIBILE']; ?>
</label>
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_FIELD_VISIBILITY_TYPES']; ?>

                          </div>
                          <label><input type="checkbox" name="v_types_page" value="1" <?php if ($this->_tpl_vars['field']->v_types_page == 1): ?>checked<?php endif; ?>> <?php echo $this->_tpl_vars['LANG_FIELD_VISIBILE']; ?>
</label>
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_FIELD_VISIBILITY_CATEGORIES']; ?>

                          </div>
                          <label><input type="checkbox" name="v_categories_page" value="1" <?php if ($this->_tpl_vars['field']->v_categories_page == 1): ?>checked<?php endif; ?>> <?php echo $this->_tpl_vars['LANG_FIELD_VISIBILE']; ?>
</label>
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_FIELD_VISIBILITY_SEARCH']; ?>

                          </div>
                          <label><input type="checkbox" name="v_search_page" value="1" <?php if ($this->_tpl_vars['field']->v_search_page == 1): ?>checked<?php endif; ?>> <?php echo $this->_tpl_vars['LANG_FIELD_VISIBILE']; ?>
</label>
                     </div>
                     <input class="button save_button" type=submit name="submit" value="<?php if ($this->_tpl_vars['field_id'] != 'new'): ?><?php echo $this->_tpl_vars['LANG_BUTTON_SAVE_CHANGES']; ?>
<?php else: ?><?php echo $this->_tpl_vars['LANG_CREATE_FIELD']; ?>
<?php endif; ?>">
                     </form>
                </div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>