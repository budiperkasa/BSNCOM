<?php /* Smarty version 2.6.26, created on 2012-02-06 03:55:26
         compiled from users/admin_users_groups_permissions.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'users/admin_users_groups_permissions.tpl', 2, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $this->assign('colspan', count($this->_tpl_vars['users_groups'])); ?>

                <div class="content">
                     <h3><?php echo $this->_tpl_vars['LANG_USERS_GROUP_PERMISSIONS']; ?>
</h3>
                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/users_groups/create"); ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_add.png" title="<?php echo $this->_tpl_vars['LANG_CREATE_NEW_GROUP']; ?>
" />
                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/users_groups/create"); ?>
"><?php echo $this->_tpl_vars['LANG_CREATE_NEW_GROUP']; ?>
</a>&nbsp;&nbsp;&nbsp;

                     <form action="" method="post">
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                       	 <th></th>
                         <?php $_from = $this->_tpl_vars['users_groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['group_item']):
?>
                         <th><?php echo $this->_tpl_vars['group_item']->name; ?>
<?php if ($this->_tpl_vars['group_item']->default_group): ?> <i>(default)</i><?php endif; ?></th>
                         <?php endforeach; endif; unset($_from); ?>
                       </tr>
                       <?php $_from = $this->_tpl_vars['permissions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['module'] => $this->_tpl_vars['permissions_of_module']):
?>
                       <tr>
                       	     <td colspan="<?php echo $this->_tpl_vars['colspan']+1; ?>
" class="td_header">
                       	         <b><?php echo $this->_tpl_vars['modules_array'][$this->_tpl_vars['module']]; ?>
</b>
                       	     </td>
                       </tr>
	                       <?php $_from = $this->_tpl_vars['permissions_of_module']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['permission']):
?>
	                       <tr>
	                         <td>
	                             <?php echo $this->_tpl_vars['permission']; ?>

	                         </td>
	                         <?php $_from = $this->_tpl_vars['users_groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['group_item']):
?>
	                         <td>
	                             <input type="checkbox" name="<?php echo $this->_tpl_vars['group_item']->id; ?>
-<?php echo $this->_tpl_vars['permission']; ?>
" value="<?php echo $this->_tpl_vars['group_item']->id; ?>
:<?php echo $this->_tpl_vars['permission']; ?>
" <?php $_from = $this->_tpl_vars['users_groups_permissions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ugp']):
?><?php if ($this->_tpl_vars['ugp']['group_id'] == $this->_tpl_vars['group_item']->id && $this->_tpl_vars['ugp']['function_access'] == $this->_tpl_vars['permission']): ?>checked<?php endif; ?><?php endforeach; endif; unset($_from); ?>>
	                         </td>
	                         <?php endforeach; endif; unset($_from); ?>
	                       </tr>
	                       <?php endforeach; endif; unset($_from); ?>
                       <?php endforeach; endif; unset($_from); ?>
                     </table>
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