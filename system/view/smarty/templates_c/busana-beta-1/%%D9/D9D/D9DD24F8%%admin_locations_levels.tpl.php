<?php /* Smarty version 2.6.26, created on 2012-02-09 05:48:33
         compiled from locations/admin_locations_levels.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'locations/admin_locations_levels.tpl', 8, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

                <div class="content">
                     <h3><?php echo $this->_tpl_vars['LANG_MANAGE_LOC_LEVELS']; ?>
</h3>
                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/locations/levels/create"); ?>
" title="<?php echo $this->_tpl_vars['LANG_CREATE_LOC_LEVEL_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_add.png"></a>
                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/locations/levels/create"); ?>
"><?php echo $this->_tpl_vars['LANG_CREATE_LOC_LEVEL_OPTION']; ?>
</a>&nbsp;&nbsp;&nbsp;&nbsp;

                     <?php if (count($this->_tpl_vars['levels'])): ?>
                     <table id="drag_table" class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr class="nodrop nodrag">
                         <th width="1"><?php echo $this->_tpl_vars['LANG_WEIGHT_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_NAME_TH']; ?>
</th>
                         <th width="1"><?php echo $this->_tpl_vars['LANG_OPTIONS_TH']; ?>
</th>
                       </tr>
                       <?php $_from = $this->_tpl_vars['levels']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['level']):
?>
                       <?php $this->assign('level_id', $this->_tpl_vars['level']->id); ?>
                       <tr id="<?php echo $this->_tpl_vars['level_id']; ?>
_id">
                         <td>
                             <?php echo $this->_tpl_vars['level']->order_num; ?>

                         </td>
                         <td>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/locations/levels/edit/".($this->_tpl_vars['level_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_EDIT_LOC_LEVEL_OPTION']; ?>
"><?php echo $this->_tpl_vars['level']->name; ?>
</a>
                         </td>
                         <td>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/locations/levels/edit/".($this->_tpl_vars['level_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_EDIT_LOC_LEVEL_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/page_edit.png"></a>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/locations/levels/delete/".($this->_tpl_vars['level_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_DELETE_LOC_LEVEL_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/page_delete.png"></a>
                         </td>
                       </tr>
                       <?php endforeach; endif; unset($_from); ?>
                     </table>

                     <br />
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