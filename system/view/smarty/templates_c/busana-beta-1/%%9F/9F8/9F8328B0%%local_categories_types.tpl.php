<?php /* Smarty version 2.6.26, created on 2012-02-06 05:51:29
         compiled from categories/local_categories_types.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'categories/local_categories_types.tpl', 6, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

                <div class="content">
                     <h3><?php echo $this->_tpl_vars['LANG_CHOOSE_TYPE_OF_CATEGORIES_TITLE']; ?>
</h3>

                     <?php if (count($this->_tpl_vars['types']) > 0): ?>
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th><?php echo $this->_tpl_vars['LANG_TYPES_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_OPTIONS_TH']; ?>
</th>
                       </tr>
                       <?php $_from = $this->_tpl_vars['types']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['types_item']):
?>
                       <?php $this->assign('types_item_id', $this->_tpl_vars['types_item']['id']); ?>
                       <tr>
                         <td>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/categories/by_type/".($this->_tpl_vars['types_item_id'])); ?>
"><?php echo $this->_tpl_vars['types_item']['name']; ?>
</a>
                         </td>
                         <td>
                         	 <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/categories/by_type/".($this->_tpl_vars['types_item_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_MANAGE_CATEGORIES_BY_TYPE']; ?>
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