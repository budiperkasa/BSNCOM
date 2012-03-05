<?php /* Smarty version 2.6.26, created on 2012-02-06 08:50:06
         compiled from settings/admin_frontend_settings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'settings/admin_frontend_settings.tpl', 5, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

                <div class="content">
                     <h3><?php echo $this->_tpl_vars['LANG_FRONTEND_SETTINGS']; ?>
</h3>
                     <?php if (count($this->_tpl_vars['types']) > 0): ?>
                     <table class="presentationTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th><?php echo $this->_tpl_vars['LANG_TYPE_TH']; ?>
</th>
                         <?php $_from = $this->_tpl_vars['views']->available_pages['by_types']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['page_key'] => $this->_tpl_vars['page_name']):
?>
	                         <th><?php echo $this->_tpl_vars['page_name']; ?>
</th>
                         <?php endforeach; endif; unset($_from); ?>
                       </tr>
                       <?php $_from = $this->_tpl_vars['types']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['type']):
?>
                       <?php $this->assign('type_id', $this->_tpl_vars['type']->id); ?>
                       <tr>
                         <td>
                         	<?php echo $this->_tpl_vars['type']->name; ?>

                         </td>
                         <?php $_from = $this->_tpl_vars['views']->available_pages['by_types']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['page_key'] => $this->_tpl_vars['page_name']):
?>
	                         <?php $this->assign('view_obj', $this->_tpl_vars['views']->getViewByTypeIdAndPage($this->_tpl_vars['type']->id,$this->_tpl_vars['page_key'])); ?>
	                         <td>
	                         	<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/settings/frontend/configure/".($this->_tpl_vars['page_key'])."/".($this->_tpl_vars['type_id'])."/"); ?>
" title="<?php echo $this->_tpl_vars['LANG_CONFIGURE_VISIBILITY']; ?>
"><?php echo $this->_tpl_vars['view_obj']->getViewName(); ?>
 (<?php echo $this->_tpl_vars['view_obj']->format; ?>
) - <?php echo $this->_tpl_vars['LANG_SEARCH_ORDER_BY']; ?>
: <?php echo $this->_tpl_vars['view_obj']->getOrderBy(); ?>
</a>
	                         </td>
                         <?php endforeach; endif; unset($_from); ?>
                       </tr>
                       <?php endforeach; endif; unset($_from); ?>
                     </table>
                     <?php endif; ?>
                     <div class="px10"></div>
                     <h3><?php echo $this->_tpl_vars['LANG_FRONTEND_SETTINGS_ALONE_PAGES']; ?>
</h3>
                     <table class="presentationTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                       	 <?php $_from = $this->_tpl_vars['views']->available_pages['mixed_types']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['page_key'] => $this->_tpl_vars['page_name']):
?>
	                         <th><?php echo $this->_tpl_vars['page_name']; ?>
</th>
                         <?php endforeach; endif; unset($_from); ?>
                       </tr>
                       <tr>
                         <?php $_from = $this->_tpl_vars['views']->available_pages['mixed_types']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['page_key'] => $this->_tpl_vars['page_name']):
?>
	                         <?php $this->assign('view_obj', $this->_tpl_vars['views']->getViewByTypeIdAndPage(0,$this->_tpl_vars['page_key'])); ?>
	                         <td>
	                         	<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/settings/frontend/configure/".($this->_tpl_vars['page_key'])."/"); ?>
" title="<?php echo $this->_tpl_vars['LANG_CONFIGURE_VISIBILITY']; ?>
"><?php echo $this->_tpl_vars['view_obj']->getViewName(); ?>
 (<?php echo $this->_tpl_vars['view_obj']->format; ?>
) - <?php echo $this->_tpl_vars['LANG_SEARCH_ORDER_BY']; ?>
: <?php echo $this->_tpl_vars['view_obj']->getOrderBy(); ?>
</a>
	                         </td>
                         <?php endforeach; endif; unset($_from); ?>
                       </tr>
                     </table>
                </div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>