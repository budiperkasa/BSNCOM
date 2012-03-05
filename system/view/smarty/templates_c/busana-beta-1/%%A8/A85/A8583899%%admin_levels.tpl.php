<?php /* Smarty version 2.6.26, created on 2012-02-06 04:24:52
         compiled from types_levels/admin_levels.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'types_levels/admin_levels.tpl', 10, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

                <div class="content">
                     <h3><?php echo $this->_tpl_vars['LANG_LEVELS_OF_TYPE']; ?>
 "<?php echo $this->_tpl_vars['type']->name; ?>
"</h3>
                     <div class="admin_top_menu_cell" style="width: auto">
	                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/levels/create/type_id/".($this->_tpl_vars['type_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_CREATE_LEVEL']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_add.png"></a>
	                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/levels/create/type_id/".($this->_tpl_vars['type_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_CREATE_LEVEL']; ?>
</a>&nbsp;&nbsp;&nbsp;
	                 </div>
                     
                     <?php if (count($this->_tpl_vars['levels']) > 0): ?>
                     <table id="drag_table" class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr class="nodrop nodrag">
                         <th><?php echo $this->_tpl_vars['LANG_ID_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_LEVEL_NAME_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_LEVEL_FEATURED_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_LEVEL_LISTING_DESCRIPTION']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_LEVEL_CATEGORIES_TH']; ?>
</th>
                         <?php if ($this->_tpl_vars['type']->locations_enabled): ?>
                         <th><?php echo $this->_tpl_vars['LANG_LEVEL_LOCATIONS_TH']; ?>
</th>
                         <?php endif; ?>
                         <th><?php echo $this->_tpl_vars['LANG_TYPE_MODERATION_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_LEVEL_LOGO_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_LEVEL_IMAGES_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_LEVEL_VIDEOS_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_LEVEL_FILES_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_LEVEL_MAPS_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_OPTIONS_TH']; ?>
</th>
                       </tr>
                       <?php $_from = $this->_tpl_vars['levels']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['level']):
?>
                       <?php $this->assign('level_id', $this->_tpl_vars['level']->id); ?>
                       <?php $this->assign('content_fields_group_id', $this->_tpl_vars['level']->content_fields_group_id); ?>
                       <tr id="<?php echo $this->_tpl_vars['level']->id; ?>
_id">
                       	 <td>
                             <?php echo $this->_tpl_vars['level']->id; ?>

                         </td>
                         <td>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/levels/edit/".($this->_tpl_vars['level_id'])); ?>
"><?php echo $this->_tpl_vars['level']->name; ?>
</a>
                         </td>
                         <td>
                         	<?php if ($this->_tpl_vars['level']->featured): ?>On<?php else: ?>Off<?php endif; ?>
                         </td>
                         <td>
                         	<?php if ($this->_tpl_vars['level']->description_mode != 'disabled'): ?>On<?php else: ?>Off<?php endif; ?>
                         </td>
                         <td>
                         	<?php echo $this->_tpl_vars['level']->categories_number; ?>

                         </td>
                         <?php if ($this->_tpl_vars['type']->locations_enabled): ?>
                         <td>
                         	<?php echo $this->_tpl_vars['level']->locations_number; ?>

                         </td>
                         <?php endif; ?>
                         <td>
                         	<?php if ($this->_tpl_vars['level']->preapproved_mode): ?>On<?php else: ?>Off<?php endif; ?>
                         </td>
                         <td>
                         	<?php if ($this->_tpl_vars['level']->logo_enabled): ?>On<?php else: ?>Off<?php endif; ?>
                         </td>
                         <td>
                         	<?php echo $this->_tpl_vars['level']->images_count; ?>

                         </td>
                         <td>
                         	<?php echo $this->_tpl_vars['level']->video_count; ?>

                         </td>
                         <td>
                         	<?php echo $this->_tpl_vars['level']->files_count; ?>

                         </td>
                         <td>
                         	<?php if ($this->_tpl_vars['level']->maps_enabled): ?>On<?php else: ?>Off<?php endif; ?>
                         </td>
                         <td>
                         	<nobr>
                         	 <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/groups/manage_fields/".($this->_tpl_vars['content_fields_group_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_CONFIGURE_FIELDS_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_green.png"></a>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/levels/edit/".($this->_tpl_vars['level_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_EDIT_LEVEL_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/page_edit.png"></a>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/levels/delete/".($this->_tpl_vars['level_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_DELETE_LEVEL_OPTION']; ?>
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