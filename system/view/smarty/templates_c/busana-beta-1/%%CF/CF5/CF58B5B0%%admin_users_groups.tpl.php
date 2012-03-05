<?php /* Smarty version 2.6.26, created on 2012-02-06 03:55:16
         compiled from users/admin_users_groups.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'users/admin_users_groups.tpl', 25, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<script language="JavaScript" type="text/javascript">
$(document).ready(function() {
	$("input[name='default_group']").change(function() {
		group_id = $(this).val();
		$("input[name*='may_register']").attr('disabled', false);
		$("input[name='may_register["+group_id+"]']").attr('checked', true);
		$("input[name='may_register["+group_id+"]']").attr('disabled', true);
	});
});
</script>

                <div class="content">
                	<?php echo $this->_tpl_vars['VH']->validation_errors(); ?>

                     <h3><?php echo $this->_tpl_vars['LANG_USERS_GROUPS']; ?>
</h3>
                     <div class="note"><?php echo $this->_tpl_vars['LANG_USERS_GROUPS_DESCR']; ?>
</div>
                     
                     <div class="admin_top_menu_cell" style="width: auto;">
	                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/users_groups/create"); ?>
" title="<?php echo $this->_tpl_vars['LANG_CREATE_NEW_GROUP']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_add.png"></a>
	                     <a href="<?php echo $this->_tpl_vars['VH']->site_url('admin/users/users_groups/create'); ?>
"><?php echo $this->_tpl_vars['LANG_CREATE_NEW_GROUP']; ?>
</a>&nbsp;&nbsp;&nbsp;
					</div>
					<div class="clear_float"></div>

                     <?php if (count($this->_tpl_vars['users_groups']) > 0): ?>
                     <form action="<?php echo $this->_tpl_vars['VH']->site_url('admin/users/users_groups/save_default'); ?>
" method="post">
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th width="1"><?php echo $this->_tpl_vars['LANG_ID_TH']; ?>
</th>
                         <th width="1"><?php echo $this->_tpl_vars['LANG_DEFAULT_GROUP_TH']; ?>
</th>
                         <th width="1"><?php echo $this->_tpl_vars['LANG_MAY_REGISTER_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_NAME_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_USERS_GROUP_OWN_PAGE_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_USERS_GROUP_USE_SEO_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_USERS_GROUP_LOGO_ENABLED_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_OPTIONS_TH']; ?>
</th>
                       </tr>
                       <?php $_from = $this->_tpl_vars['users_groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['group_item']):
?>
                       <?php $this->assign('group_id', $this->_tpl_vars['group_item']->id); ?>
                       <tr>
                         <td>
                             <?php echo $this->_tpl_vars['group_id']; ?>

                         </td>
                         <td>
                             <input type="radio" name="default_group" value="<?php echo $this->_tpl_vars['group_id']; ?>
" <?php if ($this->_tpl_vars['group_item']->default_group): ?>checked<?php endif; ?> />
                         </td>
                         <td>
                         	<?php if ($this->_tpl_vars['group_id'] != 1): ?>
                             <input type="checkbox" name="may_register[<?php echo $this->_tpl_vars['group_id']; ?>
]" value="1" <?php if ($this->_tpl_vars['group_item']->may_register): ?>checked<?php endif; ?> <?php if ($this->_tpl_vars['group_item']->default_group): ?>disabled<?php endif; ?>/>
                            <?php endif; ?>
                         </td>
                         <td>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/users_groups/edit/".($this->_tpl_vars['group_id'])); ?>
"><?php echo $this->_tpl_vars['group_item']->name; ?>
</a>
                         </td>
                         <td>
                             <?php if ($this->_tpl_vars['group_item']->is_own_page): ?><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/accept.png" /><?php else: ?><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/delete.png" /><?php endif; ?>
                         </td>
                         <td>
                             <?php if ($this->_tpl_vars['group_item']->use_seo_name): ?><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/accept.png" /><?php else: ?><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/delete.png" /><?php endif; ?>
                         </td>
                         <td>
                             <?php if ($this->_tpl_vars['group_item']->logo_enabled): ?><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/accept.png" /><?php else: ?><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/delete.png" /><?php endif; ?>
                         </td>
                         <td>
                         	 <nobr>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/users_groups/edit/".($this->_tpl_vars['group_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_EDIT_GROUP']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/page_edit.png"></a>
                             <?php if ($this->_tpl_vars['group_id'] != 1): ?>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/users_groups/delete/".($this->_tpl_vars['group_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_DELETE_GROUP']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/page_delete.png"></a>
                             <?php endif; ?>
                             </nobr>
                         </td>
                       </tr>
                       <?php endforeach; endif; unset($_from); ?>
                     </table>
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