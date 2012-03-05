<?php /* Smarty version 2.6.26, created on 2012-02-06 09:25:23
         compiled from content_pages/admin_content_pages_settings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'translate_content', 'content_pages/admin_content_pages_settings.tpl', 32, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $this->assign('node_id', $this->_tpl_vars['node']->id()); ?>

                <div class="content">
                	<?php echo $this->_tpl_vars['VH']->validation_errors(); ?>

                     <h3><?php if ($this->_tpl_vars['node']->id() == 'new'): ?><?php echo $this->_tpl_vars['LANG_CREATE_CONTENT_PAGE']; ?>
<?php else: ?><?php echo $this->_tpl_vars['LANG_EDIT_CONTENT_PAGE']; ?>
 "<?php echo $this->_tpl_vars['node']->title(); ?>
"<?php endif; ?></h3>
                     
                     <?php if ($this->_tpl_vars['node']->id() != 'new'): ?>
                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/pages/create"); ?>
" title="<?php echo $this->_tpl_vars['LANG_CREATE_PAGE']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_add.png" /></a>
                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/pages/create"); ?>
"><?php echo $this->_tpl_vars['LANG_CREATE_PAGE']; ?>
</a>&nbsp;&nbsp;&nbsp;
                     
                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/pages/preview/".($this->_tpl_vars['node_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_VIEW_CONTENT_PAGE']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page.png" /></a>
                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/pages/preview/".($this->_tpl_vars['node_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_VIEW_CONTENT_PAGE']; ?>
</a>&nbsp;&nbsp;&nbsp;
                     
                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/pages/delete/".($this->_tpl_vars['node_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_DELETE_CONTENT_PAGE']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_delete.png" /></a>
                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/pages/delete/".($this->_tpl_vars['node_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_DELETE_CONTENT_PAGE']; ?>
</a>&nbsp;&nbsp;&nbsp;
                     
                     <br />
                     <br />
                     <?php endif; ?>
                     
                     <form action="" method="post">
                     <div class="admin_option">
                          <div class="admin_option_name" >
                          	<?php echo $this->_tpl_vars['LANG_PAGE_PUBLIC_URL']; ?>
<span class="red_asterisk">*</span>
                          </div>
                          <input type=text name="url" value="<?php echo $this->_tpl_vars['node']->url(); ?>
" size="50" class="admin_option_input">
                     </div>
                     <div class="admin_option">
                          <div class="admin_option_name">
                               <?php echo $this->_tpl_vars['LANG_PAGE_TITLE']; ?>
<span class="red_asterisk">*</span>
                               <?php echo smarty_function_translate_content(array('table' => 'content_pages','field' => 'title','row_id' => $this->_tpl_vars['node_id']), $this);?>

                          </div>
                          <input type=text name="title" value="<?php echo $this->_tpl_vars['node']->title(); ?>
" size="50" class="admin_option_input">
                     </div>
                     <div class="admin_option">
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_ENABLE_LINK_IN_MENU']; ?>

						</div>
						<label><input type="checkbox" name="in_menu" value="1" <?php if ($this->_tpl_vars['node']->inMenu()): ?>checked<?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_ENABLE']; ?>
</label>
					 </div>

					 <div class="admin_option">
                     	<div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_META_TITLE']; ?>

                          	<?php echo smarty_function_translate_content(array('table' => 'content_pages','field' => 'meta_title','row_id' => $this->_tpl_vars['node_id']), $this);?>

                        </div>
                        <div class="admin_option_description">
                        	<?php echo $this->_tpl_vars['LANG_META_TITLE_DESCR']; ?>

                        </div>
                        <input type=text name="meta_title" value="<?php echo $this->_tpl_vars['node']->meta_title(); ?>
" size="60" class="admin_option_input">
                     </div>
                     <div class="admin_option">
                     	<div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_META_DESCRIPTION']; ?>

                          	<?php echo smarty_function_translate_content(array('table' => 'content_pages','field' => 'meta_description','row_id' => $this->_tpl_vars['node_id'],'field_type' => 'text'), $this);?>

                        </div>
                        <div class="admin_option_description">
                        	<?php echo $this->_tpl_vars['LANG_META_TITLE_DESCR']; ?>

                        </div>
                        <textarea name="meta_description" cols="60" rows="5"><?php echo $this->_tpl_vars['node']->meta_description(); ?>
</textarea>
                     </div>

                     <?php echo $this->_tpl_vars['node']->inputMode(); ?>

                     <input class="button save_button" type=submit name="submit" value="<?php if ($this->_tpl_vars['node']->id() != 'new'): ?><?php echo $this->_tpl_vars['LANG_BUTTON_SAVE_CHANGES']; ?>
<?php else: ?><?php echo $this->_tpl_vars['LANG_BUTTON_CREATE_NEW_PAGE']; ?>
<?php endif; ?>">
                     </form>
                </div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>