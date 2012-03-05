<?php /* Smarty version 2.6.26, created on 2012-02-06 04:43:14
         compiled from content_fields/fields/checkboxes/field_configure.tpl */ ?>
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

					 <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/configure/".($this->_tpl_vars['field_id'])."/add_option/"); ?>
" title="<?php echo $this->_tpl_vars['LANG_ADD_CHECKBOX']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_add.png" /></a>
                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/configure/".($this->_tpl_vars['field_id'])."/add_option/"); ?>
"><?php echo $this->_tpl_vars['LANG_ADD_CHECKBOX']; ?>
</a>&nbsp;&nbsp;&nbsp;&nbsp;
                     
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
                     <table id="drag_table" class="standardTable" border="0" cellpadding="2" cellspacing="2">
                        <tr class="nodrop nodrag">
                         <th width="10"><?php echo $this->_tpl_vars['LANG_ID_TH']; ?>
</th>
                         <th width="10"><?php echo $this->_tpl_vars['LANG_WEIGHT_TH']; ?>
</th>
                         <th style="width: 300px;"><?php echo $this->_tpl_vars['LANG_CHECKBOX_NAME_TH']; ?>
</th>
                         <th width="10"><?php echo $this->_tpl_vars['LANG_OPTIONS_TH']; ?>
</th>
                       </tr>
                       <?php $_from = $this->_tpl_vars['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['option_item']):
?>
                       <?php $this->assign('field_option_id', $this->_tpl_vars['option_item']['id']); ?>
                       <tr id="<?php echo $this->_tpl_vars['option_item']['id']; ?>
_id">
                       	 <td>
	                         <?php echo $this->_tpl_vars['option_item']['id']; ?>

                         </td>
                         <td>
	                         <?php echo $this->_tpl_vars['option_item']['order_num']; ?>

                         </td>
                         <td>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/configure/".($this->_tpl_vars['field_id'])."/edit_option/".($this->_tpl_vars['field_option_id'])); ?>
"><?php echo $this->_tpl_vars['option_item']['option_name']; ?>
</a>
                         </td>
                         <td>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/configure/".($this->_tpl_vars['field_id'])."/edit_option/".($this->_tpl_vars['field_option_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_EDIT_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_edit.png" /></a>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/configure/".($this->_tpl_vars['field_id'])."/delete_option/".($this->_tpl_vars['field_option_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_DELETE_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_delete.png" /></a>
                         </td>
                       </tr>
                       <?php endforeach; endif; unset($_from); ?>
                     </table>
                     <br/>
                     <input type="hidden" id="serialized_order" name="serialized_order"> 
                     <input class="button save_button" type=submit name="submit" value="<?php echo $this->_tpl_vars['LANG_BUTTON_SAVE_CHANGES']; ?>
">
                     </form>
                </div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>