<?php /* Smarty version 2.6.26, created on 2012-02-06 03:22:47
         compiled from categories/admin_categories.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'render_frontend_block', 'categories/admin_categories.tpl', 16, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

                <div class="content">
                     <h3><?php echo $this->_tpl_vars['LANG_EDIT_CATEGORIES']; ?>
</h3>
                     <h4><?php echo $this->_tpl_vars['LANG_EDIT_CATEGORIES_DESCR']; ?>
</h4>
                     
                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/categories/create"); ?>
" title="<?php echo $this->_tpl_vars['LANG_NEW_CATEGORY_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_add.png" /></a>
                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/categories/create"); ?>
"><?php echo $this->_tpl_vars['LANG_NEW_CATEGORY_OPTION']; ?>
</a>&nbsp;&nbsp;&nbsp;
                     <br />
                     <br />

                     <form action="" method="post">
                     <div class="admin_option_name">
                     	<?php echo $this->_tpl_vars['LANG_CATEGORIES_LIST']; ?>
:
                     </div>
                     <?php echo smarty_function_render_frontend_block(array('block_type' => 'categories','block_template' => 'backend/blocks/admin_categories_management.tpl','is_counter' => false,'max_depth' => 'max'), $this);?>


                     <br/>
                     <br/>
                     <input type="submit" class="button save_button" name="submit" value="<?php echo $this->_tpl_vars['LANG_BUTTON_SAVE_CHANGES']; ?>
">
                     </form>
                </div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>