<?php /* Smarty version 2.6.26, created on 2012-02-06 04:39:05
         compiled from content_fields/fields/varchar/field_configure.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $this->assign('field_id', $this->_tpl_vars['field']->id); ?>

                <div class="content">
                	<?php echo $this->_tpl_vars['VH']->validation_errors(); ?>

                     <h3><?php echo $this->_tpl_vars['LANG_CONFIGURE_FIELD_1']; ?>
 "<?php echo $this->_tpl_vars['field']->type_name; ?>
" <?php echo $this->_tpl_vars['LANG_CONFIGURE_FIELD_2']; ?>
 "<?php echo $this->_tpl_vars['field']->name; ?>
"</h3>
                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/edit/".($this->_tpl_vars['field_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_EDIT_FIELD_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_edit.png"></a>
					 <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/edit/".($this->_tpl_vars['field_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_EDIT_FIELD_OPTION']; ?>
</a>&nbsp;&nbsp;&nbsp;&nbsp;

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

                     <form action="" method="post">
					 <input type="hidden" name="id" value="<?php echo $this->_tpl_vars['field']->id; ?>
"> 
                     <div class="admin_option">
                          <div class="admin_option_name" >
                               <?php echo $this->_tpl_vars['LANG_MAX_LENGTH_STRING']; ?>
<span class="red_asterisk">*</span>
                          </div>
                          <input type=text name="max_length" value="<?php echo $this->_tpl_vars['max_length']; ?>
" size="2" class="admin_option_input">
                     </div>
                     <div class="admin_option">
                          <div class="admin_option_name" >
                               <?php echo $this->_tpl_vars['LANG_REGEX_TEMPLATE']; ?>

                          </div>
                          <input type=text name="regex" value="<?php echo $this->_tpl_vars['regex']; ?>
" size="70" class="admin_option_input">
                     </div>
                     <div class="admin_option">
                          <div class="admin_option_name" >
                          	<?php echo $this->_tpl_vars['LANG_IS_NUMERIC']; ?>

                          </div>
                          <label><input type=checkbox name="is_numeric" value="1" <?php if ($this->_tpl_vars['is_numeric']): ?>checked<?php endif; ?>> <?php echo $this->_tpl_vars['LANG_ENABLE']; ?>
</label>
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