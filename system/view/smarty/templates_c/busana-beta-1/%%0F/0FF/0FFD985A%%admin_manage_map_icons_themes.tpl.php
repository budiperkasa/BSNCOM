<?php /* Smarty version 2.6.26, created on 2012-02-07 08:34:48
         compiled from categories/admin_manage_map_icons_themes.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'translate_content', 'categories/admin_manage_map_icons_themes.tpl', 13, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

                <div class="content">
                	 <?php echo $this->_tpl_vars['VH']->validation_errors(); ?>

                     <h3><?php echo $this->_tpl_vars['LANG_MANAGE_MARKER_ICONS_THEMES_TITLE']; ?>
</h3>

                     <form action="" method="post">
                     <?php $_from = $this->_tpl_vars['themes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['theme_item']):
?>
                     <?php $this->assign('folder_name', $this->_tpl_vars['theme_item']['folder_name']); ?>
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_MARKER_ICONS_THEME_NAME']; ?>
<span class="red_asterisk">*</span>
                          	<?php echo smarty_function_translate_content(array('table' => 'map_marker_icons_themes','field' => 'name','row_id' => $this->_tpl_vars['theme_item']['id']), $this);?>

                          </div>
                          <input type="text" name="<?php echo $this->_tpl_vars['folder_name']; ?>
" size="40" value="<?php echo $this->_tpl_vars['theme_item']['name']; ?>
"><br />
                          <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/manage_map_icons/folder_name/".($this->_tpl_vars['folder_name'])); ?>
"><?php echo $this->_tpl_vars['LANG_MANAGE_MARKER_ICONS_LINK']; ?>
</a>
                     </div>
                     <?php endforeach; endif; unset($_from); ?>

                     <input class="button save_button" type=submit name="submit" value="<?php echo $this->_tpl_vars['LANG_BUTTON_SAVE_CHANGES']; ?>
">
                     </form>
                </div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>