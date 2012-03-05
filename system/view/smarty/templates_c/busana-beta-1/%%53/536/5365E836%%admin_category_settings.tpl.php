<?php /* Smarty version 2.6.26, created on 2012-02-06 03:22:52
         compiled from categories/admin_category_settings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'translate_content', 'categories/admin_category_settings.tpl', 23, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $this->assign('category_id', $this->_tpl_vars['category']->id); ?>

                <div class="content">
                	<?php echo $this->_tpl_vars['VH']->validation_errors(); ?>

                     <h3><?php if ($this->_tpl_vars['category_id'] == 'new'): ?><?php echo $this->_tpl_vars['LANG_CREATE_CATEGORY']; ?>
<?php else: ?><?php echo $this->_tpl_vars['LANG_EDIT_CATEGORY']; ?>
<?php endif; ?></h3>
                     
                     <?php if ($this->_tpl_vars['category_id'] != 'new'): ?>
                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/categories/create"); ?>
" title="<?php echo $this->_tpl_vars['LANG_NEW_CATEGORY_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_add.png" /></a>
                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/categories/create"); ?>
"><?php echo $this->_tpl_vars['LANG_NEW_CATEGORY_OPTION']; ?>
</a>&nbsp;&nbsp;&nbsp;

                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/categories/delete/".($this->_tpl_vars['category_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_DELETE_CATEGORY_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_delete.png" /></a>
                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/categories/delete/".($this->_tpl_vars['category_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_DELETE_CATEGORY_OPTION']; ?>
</a>&nbsp;&nbsp;&nbsp;
                     <br />
                     <br />
                     <?php endif; ?>

                     <form action="" method="post">
                     <div class="admin_option">
                     	<div style="float: left;">
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_CATEGORY_NAME']; ?>
<span class="red_asterisk">*</span>
                          	<?php echo smarty_function_translate_content(array('table' => 'categories','field' => 'name','row_id' => $this->_tpl_vars['category_id']), $this);?>

                          </div>
                          <input type=text id="name" name="name" value="<?php echo $this->_tpl_vars['category']->name; ?>
" size="45" class="admin_option_input">
                          &nbsp;&nbsp;<span class="seo_rewrite" title="<?php echo $this->_tpl_vars['LANG_WRITE_SEO_STYLE']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/arrow_seo.gif"></span>&nbsp;&nbsp;
                        </div>
                        <div style="float: left;">
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_CATEGORY_SEO_NAME']; ?>
<span class="red_asterisk">*</span>
                          </div>
                          <input type=text id="seo_name" name="seo_name" value="<?php echo $this->_tpl_vars['category']->seo_name; ?>
" size="45" class="admin_option_input">&nbsp;&nbsp;
                        </div>
                        <div style="clear: both"></div>
                     </div>
                     
                     <div class="admin_option">
                     	<a href="javascript: void(0);" onClick="$.jqURL.loc('<?php echo $this->_tpl_vars['VH']->site_url("admin/categories/select_icons_for_categories/".($this->_tpl_vars['category_id'])."/"); ?>
', {w:750,h:620,wintype:'_blank'}); return false;"><?php echo $this->_tpl_vars['LANG_SELECT_ICONS']; ?>
</a><br />
                     	<input type="hidden" id="selected_icons_serialized" name="selected_icons_serialized" value='<?php echo $this->_tpl_vars['category']->selected_icons_serialized; ?>
'>
                     	<b><?php echo $this->_tpl_vars['LANG_NOTE']; ?>
:</b> <i><?php echo $this->_tpl_vars['LANG_CATEGORIES_ICONS_SELECTION_NOTE']; ?>
</i>
                     </div>

                     <div class="admin_option">
                     	<div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_META_TITLE']; ?>

                          	<?php echo smarty_function_translate_content(array('table' => 'categories','field' => 'meta_title','row_id' => $this->_tpl_vars['category_id']), $this);?>

                        </div>
                        <div class="admin_option_description">
                        	<?php echo $this->_tpl_vars['LANG_META_TITLE_DESCR']; ?>

                        </div>
                        <input type=text name="meta_title" value="<?php echo $this->_tpl_vars['category']->meta_title; ?>
" size="60" class="admin_option_input">
                     </div>
                     <div class="admin_option">
                     	<div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_META_DESCRIPTION']; ?>

                          	<?php echo smarty_function_translate_content(array('table' => 'categories','field' => 'meta_description','row_id' => $this->_tpl_vars['category_id'],'field_type' => 'text'), $this);?>

                        </div>
                        <div class="admin_option_description">
                        	<?php echo $this->_tpl_vars['LANG_META_TITLE_DESCR']; ?>

                        </div>
                        <textarea name="meta_description" cols="60" rows="5"><?php echo $this->_tpl_vars['category']->meta_description; ?>
</textarea>
                     </div>

                     <input class="button save_button" type=submit name="submit" value="<?php if ($this->_tpl_vars['category_id'] != 'new'): ?><?php echo $this->_tpl_vars['LANG_BUTTON_SAVE_CHANGES']; ?>
<?php else: ?><?php echo $this->_tpl_vars['LANG_BUTTON_CREATE_CATEGORY']; ?>
<?php endif; ?>">
                     </form>
                </div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>