<?php /* Smarty version 2.6.26, created on 2012-02-06 04:33:18
         compiled from content_fields/admin_manage_fields_groups.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'content_fields/admin_manage_fields_groups.tpl', 6, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

                <div class="content">
                     <h3><?php echo $this->_tpl_vars['LANG_VIEW_CONTENT_FIELDS_GROUP']; ?>
</h3>

                     <?php if (count($this->_tpl_vars['groups']) > 0): ?>
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th><?php echo $this->_tpl_vars['LANG_NAME_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_FIELDS_COUNT_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_OPTIONS_TH']; ?>
</th>
                       </tr>
                       <?php $_from = $this->_tpl_vars['groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['groups_item']):
?>
                       <?php $this->assign('groups_item_id', $this->_tpl_vars['groups_item']['id']); ?>
                       <tr>
                         <td>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/groups/manage_fields/".($this->_tpl_vars['groups_item_id'])); ?>
"><?php echo $this->_tpl_vars['groups_item']['name']; ?>
</a>
                         </td>
                         <td>
                             <?php echo $this->_tpl_vars['groups_item']['f_count']; ?>

                         </td>
                         <td>
                         	 <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/groups/manage_fields/".($this->_tpl_vars['groups_item_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_MANAGE_CONTENT_FIELDS_IN_GROUP_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/page_green.png"></a>&nbsp;
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