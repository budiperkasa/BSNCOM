<?php /* Smarty version 2.6.26, created on 2012-02-06 03:20:30
         compiled from content_fields/admin_manage_fields.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'content_fields/admin_manage_fields.tpl', 7, false),array('modifier', 'nl2br', 'content_fields/admin_manage_fields.tpl', 34, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

                <div class="content">
                     <h3><?php echo $this->_tpl_vars['LANG_MANAGE_CONTENT_FIELDS']; ?>
</h3>
                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/create"); ?>
" title="<?php echo $this->_tpl_vars['LANG_CREATE_FIELD_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_add.png"></a>
					 <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/create"); ?>
" title="<?php echo $this->_tpl_vars['LANG_CREATE_FIELD_OPTION']; ?>
"><?php echo $this->_tpl_vars['LANG_CREATE_FIELD_OPTION']; ?>
</a>&nbsp;&nbsp;&nbsp;&nbsp;
                     <?php if (count($this->_tpl_vars['fields']) > 0): ?>
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th><?php echo $this->_tpl_vars['LANG_NAME_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_FIELD_FRONTEND_NAME']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_FIELD_TYPE_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_DESCRIPTION_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_REQUIRED_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_FIELD_VISIBILITY_INDEX_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_FIELD_VISIBILITY_TYPES_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_FIELD_VISIBILITY_CATEGORIES_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_FIELD_VISIBILITY_SEARCH_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_OPTIONS_TH']; ?>
</th>
                       </tr>
                       <?php $_from = $this->_tpl_vars['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['field_item']):
?>
                       <?php $this->assign('field_item_id', $this->_tpl_vars['field_item']['id']); ?>
                       <tr>
                         <td>
							<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/edit/".($this->_tpl_vars['field_item_id'])); ?>
"><?php echo $this->_tpl_vars['field_item']['name']; ?>
</a>
                         </td>
                         <td>
							<?php echo $this->_tpl_vars['field_item']['frontend_name']; ?>

                         </td>
                         <td>
                             <?php echo $this->_tpl_vars['field_item']['type_name']; ?>
&nbsp;
                         </td>
                         <td>
                             <?php echo ((is_array($_tmp=$this->_tpl_vars['field_item']['description'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
&nbsp;
                         </td>
                         <td>
                             <?php if ($this->_tpl_vars['field_item']['required'] == 1): ?><span class="red_asterisk">*</span><?php endif; ?>&nbsp;
                         </td>
                         <td>
                             <?php if ($this->_tpl_vars['field_item']['v_index_page'] == 1): ?><div class="yes"></div><?php else: ?><div class="no"></div><?php endif; ?>
                         </td>
                         <td>
                             <?php if ($this->_tpl_vars['field_item']['v_types_page'] == 1): ?><div class="yes"></div><?php else: ?><div class="no"></div><?php endif; ?>
                         </td>
                         <td>
                             <?php if ($this->_tpl_vars['field_item']['v_categories_page'] == 1): ?><div class="yes"></div><?php else: ?><div class="no"></div><?php endif; ?>
                         </td>
                         <td>
                             <?php if ($this->_tpl_vars['field_item']['v_search_page'] == 1): ?><div class="yes"></div><?php else: ?><div class="no"></div><?php endif; ?>
                         </td>
                         <td>
                         	<nobr>
                         	 <?php if ($this->_tpl_vars['field_item']['configuration_page']): ?>
                         	 	<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/configure/".($this->_tpl_vars['field_item_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_CONFIGURE_FIELD_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/page_gear.png"></a>
                             <?php endif; ?>
                             <?php if ($this->_tpl_vars['field_item']['search_configuration_page']): ?>
                         	 	<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/configure_search/".($this->_tpl_vars['field_item_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_CONFIGURE_SEARCH_FIELD_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/page_find.png"></a>
                             <?php endif; ?>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/copy/".($this->_tpl_vars['field_item_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_MAKE_FIELD_COPY']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/page_copy.png"></a>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/edit/".($this->_tpl_vars['field_item_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_EDIT_FIELD_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/page_edit.png"></a>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/delete/".($this->_tpl_vars['field_item_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_DELETE_FIELD_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/page_delete.png"></a>
                            <nobr>
                         </td>
                       </tr>
                       <?php endforeach; endif; unset($_from); ?>
                     </table>
                     <?php endif; ?>
                </div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>