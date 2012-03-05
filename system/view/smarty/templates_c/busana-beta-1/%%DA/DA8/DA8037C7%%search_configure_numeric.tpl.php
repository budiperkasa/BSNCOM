<?php /* Smarty version 2.6.26, created on 2012-03-03 04:09:08
         compiled from content_fields/search/varchar/search_configure_numeric.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $this->assign('field_id', $this->_tpl_vars['field']->id); ?>

                <div class="content">
                	<?php echo $this->_tpl_vars['VH']->validation_errors(); ?>

                     <h3><?php echo $this->_tpl_vars['LANG_CONFIGURE_SEARCH_FIELD_1']; ?>
 "<?php echo $this->_tpl_vars['field']->type_name; ?>
" <?php echo $this->_tpl_vars['LANG_CONFIGURE_SEARCH_FIELD_2']; ?>
 "<?php echo $this->_tpl_vars['field']->name; ?>
"</h3>
                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/edit/".($this->_tpl_vars['field_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_EDIT_FIELD_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_edit.png"></a>
					 <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/edit/".($this->_tpl_vars['field_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_EDIT_FIELD_OPTION']; ?>
</a>&nbsp;&nbsp;&nbsp;&nbsp;
                     <?php if ($this->_tpl_vars['field']->configuration_page): ?>
						<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/configure/".($this->_tpl_vars['field_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_CONFIGURE_FIELD_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_gear.png"></a>
						<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/configure/".($this->_tpl_vars['field_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_CONFIGURE_FIELD_OPTION']; ?>
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

                     <form action="" method="post">
					 <input type="hidden" name="id" value="<?php echo $this->_tpl_vars['field_id']; ?>
"> 
                     <div class="admin_option">
                          <div class="admin_option_name" >
                          	<?php echo $this->_tpl_vars['LANG_SELECT_SEARCH_TYPE']; ?>

                          </div>
                          <div class="admin_option_description" >
                          	<?php echo $this->_tpl_vars['LANG_SELECT_SEARCH_TYPE_NUM_DESCR']; ?>

                          </div>
                          <label><input type=radio name="search_type" value="exact_match" <?php if ($this->_tpl_vars['search_type'] == 'exact_match'): ?>checked<?php endif; ?> > <?php echo $this->_tpl_vars['LANG_EXACT_MATCH']; ?>
</label>
                          <label style="float:left"><input type=radio name="search_type" value="min_max" <?php if ($this->_tpl_vars['search_type'] == 'min_max'): ?>checked<?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_MIN_MAX_TYPE']; ?>
</label><div style="float:left; padding: 4px 0;">&nbsp;&nbsp;(<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/configure_search/".($this->_tpl_vars['field_id'])."/manage_options/"); ?>
"><?php echo $this->_tpl_vars['LANG_MANAGE_OPTIONS']; ?>
</a>)</div>
                          <div class="clear_float"></div>
                     </div>
                     <br/>
                     <input class="button save_button" type=submit name="submit" value="<?php echo $this->_tpl_vars['LANG_BUTTON_SAVE_CHANGES']; ?>
">
                     </form>
                </div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>