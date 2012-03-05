<?php /* Smarty version 2.6.26, created on 2012-02-06 03:22:06
         compiled from types_levels/admin_types.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'types_levels/admin_types.tpl', 7, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

                <div class="content">
                	<?php echo $this->_tpl_vars['VH']->validation_errors(); ?>

                     <h3><?php echo $this->_tpl_vars['LANG_LISTINGS_TYPES']; ?>
</h3>
                     
                    <?php if (! $this->_tpl_vars['system_settings']['single_type_structure'] && count($this->_tpl_vars['types']) >= 1): ?>
                    <div class="admin_top_menu_cell">
	                    <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/types/create"); ?>
" title="<?php echo $this->_tpl_vars['LANG_CREATE_TYPE_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_add.png"></a>
	                    <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/types/create"); ?>
"><?php echo $this->_tpl_vars['LANG_CREATE_TYPE_OPTION']; ?>
</a>
					</div>
					<?php endif; ?>

                     <?php if (count($this->_tpl_vars['types']) > 0): ?>
                     <table id="drag_table" class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr class="nodrop nodrag">
                       	 <th><?php echo $this->_tpl_vars['LANG_ID_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_TYPE_NAME_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_TYPE_LEVELS_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_TYPE_LOCATIONS_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_TYPE_ZIP_TH']; ?>
</th>
                         <?php if (! $this->_tpl_vars['system_settings']['single_type_structure']): ?>
                         <th><?php echo $this->_tpl_vars['LANG_TYPE_SEARCH_TYPE']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_TYPE_CATEGORIES_TYPE']; ?>
</th>
                         <?php endif; ?>
                         <th><?php echo $this->_tpl_vars['LANG_OPTIONS_TH']; ?>
</th>
                       </tr>
                       <?php $_from = $this->_tpl_vars['types']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['type']):
?>
                       <?php $this->assign('type_id', $this->_tpl_vars['type']->id); ?>
                       <tr id="<?php echo $this->_tpl_vars['type_id']; ?>
_id">
                       	 <td>
                             <?php echo $this->_tpl_vars['type']->id; ?>

                         </td>
                         <td>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/types/edit/".($this->_tpl_vars['type_id'])); ?>
"><?php echo $this->_tpl_vars['type']->name; ?>
</a>
                         </td>
                         <td>
                             <?php echo count($this->_tpl_vars['type']->levels); ?>

                         </td>
                         <td>
                         	<?php if ($this->_tpl_vars['type']->locations_enabled): ?>on<?php else: ?>off<?php endif; ?>
                         </td>
                         <td>
                         	<?php if ($this->_tpl_vars['type']->zip_enabled): ?>on<?php else: ?>off<?php endif; ?>
                         </td>
                         <?php if (! $this->_tpl_vars['system_settings']['single_type_structure']): ?>
                         <td>
                         	<?php if ($this->_tpl_vars['type']->search_type == 'global'): ?><?php echo $this->_tpl_vars['LANG_GLOABAL_SEARCH']; ?>
<?php endif; ?>
                         	<?php if ($this->_tpl_vars['type']->search_type == 'local'): ?><?php echo $this->_tpl_vars['LANG_LOCAL_SEARCH']; ?>
<?php endif; ?>
                         	<?php if ($this->_tpl_vars['type']->search_type == 'disabled'): ?><?php echo $this->_tpl_vars['LANG_DISABLED']; ?>
<?php endif; ?>
                         </td>
                         <td>
                         	<?php if ($this->_tpl_vars['type']->categories_type == 'global'): ?><?php echo $this->_tpl_vars['LANG_GLOABAL_CATEGORIES']; ?>
<?php endif; ?>
                         	<?php if ($this->_tpl_vars['type']->categories_type == 'local'): ?><?php echo $this->_tpl_vars['LANG_LOCAL_CATEGORIES']; ?>
<?php endif; ?>
                         	<?php if ($this->_tpl_vars['type']->categories_type == 'disabled'): ?><?php echo $this->_tpl_vars['LANG_DISABLED']; ?>
<?php endif; ?>
                         </td>
                         <?php endif; ?>
                         <td>
                         	<nobr>
                         	 <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/levels/type_id/".($this->_tpl_vars['type_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_VIEW_LEVELS_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/page_green.png"></a>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/types/edit/".($this->_tpl_vars['type_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_EDIT_TYPE_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/page_edit.png"></a>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/types/delete/".($this->_tpl_vars['type_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_DELETE_TYPE_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/page_delete.png"></a>
                            </nobr>
                         </td>
                       </tr>
                       <?php endforeach; endif; unset($_from); ?>
                     </table>
                     <br/>
                     <form action="" method="post">
                     <input type="hidden" id="serialized_order" name="serialized_order"> 
                     <input class="button save_button" type=submit name="submit" value="<?php echo $this->_tpl_vars['LANG_BUTTON_SAVE_CHANGES']; ?>
">
                     </form>
                     <?php endif; ?>
                </div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>