<?php /* Smarty version 2.6.26, created on 2012-02-06 04:34:41
         compiled from content_pages/admin_content_pages.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'content_pages/admin_content_pages.tpl', 10, false),array('modifier', 'date_format', 'content_pages/admin_content_pages.tpl', 36, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

                <div class="content">
                	<?php echo $this->_tpl_vars['VH']->validation_errors(); ?>

                     <h3><?php echo $this->_tpl_vars['LANG_MANAGE_CONTENT_PAGES']; ?>
 (<?php echo $this->_tpl_vars['nodes_count']; ?>
 <?php echo $this->_tpl_vars['LANG_PAGES']; ?>
)</h3>

                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/pages/create"); ?>
" title="<?php echo $this->_tpl_vars['LANG_CREATE_PAGE']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_add.png" /></a>
                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/pages/create"); ?>
"><?php echo $this->_tpl_vars['LANG_CREATE_PAGE']; ?>
</a>&nbsp;&nbsp;&nbsp;
                     
                     <?php if (count($this->_tpl_vars['nodes']) > 0): ?>
                     <table id="drag_table" class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr class="nodrop nodrag">
                         <th><?php echo $this->_tpl_vars['LANG_WEIGHT_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_URL_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_TITLE_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_TOP_MENU_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_CREATION_DATE_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_OPTIONS_TH']; ?>
</th>
                       </tr>
                       <?php $_from = $this->_tpl_vars['nodes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['node']):
?>
                       <?php $this->assign('node_id', $this->_tpl_vars['node']['id']); ?>
                       <tr id="<?php echo $this->_tpl_vars['node_id']; ?>
">
                       	 <td>
                             <?php echo $this->_tpl_vars['node']['order_num']; ?>

                         </td>
                         <td>
                             <?php echo $this->_tpl_vars['node']['url']; ?>

                         </td>
                         <td>
                         	<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/pages/edit/".($this->_tpl_vars['node_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_EDIT_CONTENT_PAGE']; ?>
"><?php echo $this->_tpl_vars['node']['title']; ?>
</a>
                         </td>
                         <td>
                             <?php if ($this->_tpl_vars['node']['in_menu']): ?><div class="yes"></div><?php else: ?><div class="no"></div><?php endif; ?>
                         </td>
                         <td>
                             <?php echo ((is_array($_tmp=$this->_tpl_vars['node']['creation_date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%D %T") : smarty_modifier_date_format($_tmp, "%D %T")); ?>
&nbsp;
                         </td>
                         <td>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/pages/preview/".($this->_tpl_vars['node_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_VIEW_CONTENT_PAGE']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/page.png"></a>&nbsp;
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/pages/edit/".($this->_tpl_vars['node_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_EDIT_CONTENT_PAGE']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/page_edit.png"></a>&nbsp;
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/pages/delete/".($this->_tpl_vars['node_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_DELETE_CONTENT_PAGE']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/page_delete.png"></a>
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