<?php /* Smarty version 2.6.26, created on 2012-02-07 04:42:49
         compiled from modules_control/admin_modules_control.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'modules_control/admin_modules_control.tpl', 5, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

           		<div class="content">
                     <h3><?php echo $this->_tpl_vars['LANG_MODULES_LIST']; ?>
</h3>
                     <?php if (count($this->_tpl_vars['modules']) > 0): ?>
                     <form action="" method="post">
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th><?php echo $this->_tpl_vars['LANG_MODULE_TITLE_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_MODULE_SETUP_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_MODULE_VERSION_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_MODULE_DESCRIPTION_TH']; ?>
</th>
                       </tr>
                       <?php $_from = $this->_tpl_vars['modules']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['module_dir'] => $this->_tpl_vars['module_item']):
?>
                       <tr>
                         <td>
                             <?php echo $this->_tpl_vars['module_item']->title; ?>
&nbsp;

                             <?php $this->assign('disabled', 0); ?>
                             <?php if (count($this->_tpl_vars['module_item']->required_by)): ?>
	                             <br />
	                             <span class="required_module"><?php echo $this->_tpl_vars['LANG_REQUIRED_BY']; ?>
:</span>&nbsp;
	                             <?php $this->assign('i', 0); ?>
	                             <?php $_from = $this->_tpl_vars['module_item']->required_by; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['module']):
?>
	                             	<?php $this->assign('i', $this->_tpl_vars['i']+1); ?>
	                             	<span class="required_module"><?php echo $this->_tpl_vars['module']->title; ?>
</span> (<?php if ($this->_tpl_vars['module']->active): ?><?php $this->assign('disabled', 1); ?><span class='required_module_active'><?php echo $this->_tpl_vars['LANG_ENABLED']; ?>
</span><?php else: ?><span class='required_module_inactive'><?php echo $this->_tpl_vars['LANG_DISABLED']; ?>
</span><?php endif; ?>)<?php if ($this->_tpl_vars['i'] != ( count($this->_tpl_vars['module_item']->required_by) )): ?>,<?php endif; ?>
	                             <?php endforeach; endif; unset($_from); ?>
                             <?php endif; ?>
                             <?php if (count($this->_tpl_vars['module_item']->depends_on)): ?>
	                             <br />
	                             <span class="required_module"><?php echo $this->_tpl_vars['LANG_DEPENDS_ON']; ?>
:</span>&nbsp;
	                             <?php $this->assign('i', 0); ?>
	                             <?php $_from = $this->_tpl_vars['module_item']->depends_on; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['module']):
?>
	                             	<?php $this->assign('i', $this->_tpl_vars['i']+1); ?>
	                             	<span class="required_module"><?php echo $this->_tpl_vars['module']->title; ?>
</span> (<?php if ($this->_tpl_vars['module']->active): ?><span class="required_module_active"><?php echo $this->_tpl_vars['LANG_ENABLED']; ?>
</span><?php else: ?><?php $this->assign('disabled', 1); ?><span class="required_module_inactive"><?php echo $this->_tpl_vars['LANG_DISABLED']; ?>
</span><?php endif; ?>)<?php if ($this->_tpl_vars['i'] != ( count($this->_tpl_vars['module_item']->depends_on) )): ?>,<?php endif; ?>
	                             <?php endforeach; endif; unset($_from); ?>
                             <?php endif; ?>
                         </td>
                         <td>
                         	<?php if ($this->_tpl_vars['module_item']->type != 'core'): ?>
                         		<?php if ($this->_tpl_vars['module_item']->active && ! $this->_tpl_vars['disabled']): ?>
                         			<input type="hidden" name="installed_<?php echo $this->_tpl_vars['module_dir']; ?>
" value="1">
                         		<?php endif; ?>
                            	<input type="checkbox" name="install_<?php echo $this->_tpl_vars['module_dir']; ?>
" value="1" <?php if ($this->_tpl_vars['disabled']): ?>disabled<?php endif; ?> <?php if ($this->_tpl_vars['module_item']->active): ?>checked<?php endif; ?>>
                            <?php endif; ?>&nbsp;
                         </td>
                         <td>
                             <?php echo $this->_tpl_vars['module_item']->version; ?>
&nbsp;
                         </td>
                         <td>
                             <?php echo $this->_tpl_vars['module_item']->description; ?>
&nbsp;
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