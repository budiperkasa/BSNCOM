<?php /* Smarty version 2.6.26, created on 2012-02-06 03:21:22
         compiled from types_levels/admin_type_settings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'types_levels/admin_type_settings.tpl', 9, false),array('function', 'translate_content', 'types_levels/admin_type_settings.tpl', 40, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $this->assign('type_id', $this->_tpl_vars['type']->id); ?>

                <div class="content">
                	<?php echo $this->_tpl_vars['VH']->validation_errors(); ?>

             	
					<h3><?php if ($this->_tpl_vars['type_id'] != 'new'): ?><?php echo $this->_tpl_vars['LANG_EDIT_TYPE']; ?>
 "<?php echo $this->_tpl_vars['type_name']; ?>
"<?php else: ?><?php echo $this->_tpl_vars['LANG_CREATE_TYPE']; ?>
<?php endif; ?></h3>
					<?php if ($this->_tpl_vars['type_id'] != 'new'): ?>
					<?php if (! $this->_tpl_vars['system_settings']['single_type_structure'] && count($this->_tpl_vars['types']) >= 1): ?>
					<div class="admin_top_menu_cell">
	                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/types/create"); ?>
" title="<?php echo $this->_tpl_vars['LANG_CREATE_TYPE_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_add.png" /></a>
	                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/types/create"); ?>
"><?php echo $this->_tpl_vars['LANG_CREATE_TYPE_OPTION']; ?>
</a>
					</div>
					<?php endif; ?>
					<div class="admin_top_menu_cell">
						<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/types/delete/".($this->_tpl_vars['type_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_DELETE_TYPE_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_delete.png" /></a>
						<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/types/delete/".($this->_tpl_vars['type_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_DELETE_TYPE_OPTION']; ?>
</a>&nbsp;&nbsp;&nbsp;
					</div>
					<div class="admin_top_menu_cell">
						<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/levels/type_id/".($this->_tpl_vars['type_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_VIEW_LEVELS_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_green.png"></a>
						<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/levels/type_id/".($this->_tpl_vars['type_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_VIEW_LEVELS_OPTION']; ?>
</a>&nbsp;&nbsp;&nbsp;
					</div>
					<div class="clear_float"></div>
					<div class="px10"></div>
					<?php endif; ?>
					
					<?php if ($this->_tpl_vars['system_settings']['single_type_structure']): ?>
                     <div>
                     	<b><i><?php echo $this->_tpl_vars['LANG_SIGNLE_TYPE_NOTIFICATION']; ?>
</i></b>
                     </div>
                     <div class="px10"></div>
                     <?php endif; ?>

                     <form action="" method="post">
                     <input type=hidden name="id" value="<?php echo $this->_tpl_vars['type_id']; ?>
">
                     <div class="admin_option">
						<div style="float: left;">
                          <div class="admin_option_name" >
                          	<?php echo $this->_tpl_vars['LANG_TYPE_NAME']; ?>
<span class="red_asterisk">*</span>
                          	<?php echo smarty_function_translate_content(array('table' => 'types','field' => 'name','row_id' => $this->_tpl_vars['type_id']), $this);?>

                          </div>
                          <div class="admin_option_description">
                          	<?php echo $this->_tpl_vars['LANG_TYPE_NAME_DESCR']; ?>

                          </div>
                          <input type=text name="name" value="<?php echo $this->_tpl_vars['type']->name; ?>
" size="40" class="admin_option_input">
                          &nbsp;&nbsp;<span class="seo_rewrite" title="<?php echo $this->_tpl_vars['LANG_WRITE_SEO_STYLE']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/arrow_seo.gif"></span>&nbsp;&nbsp;
                     	</div>
                     	<div style="float: left;">
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_TYPE_SEO_NAME']; ?>
<span class="red_asterisk">*</span>
                          </div>
                          <div class="admin_option_description">
                          	<?php echo $this->_tpl_vars['LANG_SEO_DESCR']; ?>

                          </div>
                          <input type=text name="seo_name" id="seo_name" value="<?php echo $this->_tpl_vars['type']->seo_name; ?>
" size="40" class="admin_option_input">
                     	</div>
                     	<div style="clear: both"></div>
                     </div>

                     <div class="admin_option">
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_TYPE_LOCATIONS']; ?>

                          </div>
                          <div class="admin_option_description">
                          	<?php echo $this->_tpl_vars['LANG_TYPE_LOCATIONS_DESCR']; ?>

                          </div>
                          <input type="checkbox" name="locations_enabled" <?php if ($this->_tpl_vars['type']->locations_enabled == 1): ?> checked <?php endif; ?> />&nbsp;<?php echo $this->_tpl_vars['LANG_ENABLED']; ?>

                     </div>
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_TYPE_ZIP']; ?>

                          </div>
                          <div class="admin_option_description">
                          	<?php echo $this->_tpl_vars['LANG_TYPE_ZIP_DESCR']; ?>

                          </div>
                          <input type="checkbox" name="zip_enabled" <?php if ($this->_tpl_vars['type']->zip_enabled == 1): ?> checked <?php endif; ?> />&nbsp;<?php echo $this->_tpl_vars['LANG_ENABLED']; ?>

                     </div>
                     <?php if (! $this->_tpl_vars['system_settings']['single_type_structure']): ?>
                     <label class="block_title"><?php echo $this->_tpl_vars['LANG_TYPE_SEARCH_SETTINGS']; ?>
</label>
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_TYPE_SEARCH_TYPE']; ?>

                          </div>
                          <div class="admin_option_description">
                          	<?php echo $this->_tpl_vars['LANG_TYPE_SEARCH_TYPE_DESCR']; ?>

                          </div>
                          <label><input type="radio" name="search_type" value="global" <?php if ($this->_tpl_vars['type']->search_type == 'global'): ?> checked <?php endif; ?> />&nbsp;<?php echo $this->_tpl_vars['LANG_GLOABAL_SEARCH']; ?>
</label>
                          <label><input type="radio" name="search_type" value="local" <?php if ($this->_tpl_vars['type']->search_type == 'local'): ?> checked <?php endif; ?> />&nbsp;<?php echo $this->_tpl_vars['LANG_LOCAL_SEARCH']; ?>
</label>
                          <label><input type="radio" name="search_type" value="disabled" <?php if ($this->_tpl_vars['type']->search_type == 'disabled'): ?> checked <?php endif; ?> />&nbsp;<?php echo $this->_tpl_vars['LANG_DISABLED']; ?>
</label>
                          
                          <div class="px10"></div>
                          
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_TYPE_WHAT_SEARCH']; ?>

                          </div>
                          <label><input type="radio" name="what_search" value="1" <?php if ($this->_tpl_vars['type']->what_search): ?> checked <?php endif; ?> />&nbsp;<?php echo $this->_tpl_vars['LANG_ENABLED']; ?>
</label>
                          <label><input type="radio" name="what_search" value="0" <?php if (! $this->_tpl_vars['type']->what_search): ?> checked <?php endif; ?> />&nbsp;<?php echo $this->_tpl_vars['LANG_DISABLED']; ?>
</label>
                          
                          <div class="px10"></div>
                          
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_TYPE_WHERE_SEARCH']; ?>

                          </div>
                          <label><input type="radio" name="where_search" value="1" <?php if ($this->_tpl_vars['type']->where_search): ?> checked <?php endif; ?> />&nbsp;<?php echo $this->_tpl_vars['LANG_ENABLED']; ?>
</label>
                          <label><input type="radio" name="where_search" value="0" <?php if (! $this->_tpl_vars['type']->where_search): ?> checked <?php endif; ?> />&nbsp;<?php echo $this->_tpl_vars['LANG_DISABLED']; ?>
</label>

                          <div class="px10"></div>
                          
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_TYPE_CATEGORIES_SEARCH']; ?>

                          </div>
                          <label><input type="radio" name="categories_search" value="1" <?php if ($this->_tpl_vars['type']->categories_search): ?> checked <?php endif; ?> />&nbsp;<?php echo $this->_tpl_vars['LANG_ENABLED']; ?>
</label>
                          <label><input type="radio" name="categories_search" value="0" <?php if (! $this->_tpl_vars['type']->categories_search): ?> checked <?php endif; ?> />&nbsp;<?php echo $this->_tpl_vars['LANG_DISABLED']; ?>
</label>
                     </div>
                     <label class="block_title"><?php echo $this->_tpl_vars['LANG_TYPE_CATEGORIES_SETTINGS']; ?>
</label>
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_TYPE_CATEGORIES_TYPE']; ?>

                          </div>
                          <div class="admin_option_description">
                          	<?php echo $this->_tpl_vars['LANG_TYPE_CATEGORIES_TYPE_DESCR']; ?>

                          </div>
                          <label><input type="radio" name="categories_type" value="global" <?php if ($this->_tpl_vars['type']->categories_type == 'global'): ?> checked <?php endif; ?> />&nbsp;<?php echo $this->_tpl_vars['LANG_GLOABAL_CATEGORIES']; ?>
</label>
                          <label><input type="radio" name="categories_type" value="local" <?php if ($this->_tpl_vars['type']->categories_type == 'local'): ?> checked <?php endif; ?> />&nbsp;<?php echo $this->_tpl_vars['LANG_LOCAL_CATEGORIES']; ?>
</label>
                          <label><input type="radio" name="categories_type" value="disabled" <?php if ($this->_tpl_vars['type']->categories_type == 'disabled'): ?> checked <?php endif; ?> />&nbsp;<?php echo $this->_tpl_vars['LANG_DISABLED']; ?>
</label>
                     </div>
                     <?php endif; ?>
                     <div class="admin_option">
                     	<div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_META_TITLE']; ?>

                          	<?php echo smarty_function_translate_content(array('table' => 'types','field' => 'meta_title','row_id' => $this->_tpl_vars['type_id']), $this);?>

                        </div>
                        <div class="admin_option_description">
                        	<?php echo $this->_tpl_vars['LANG_META_TITLE_DESCR']; ?>

                        </div>
                        <input type=text name="meta_title" value="<?php echo $this->_tpl_vars['type']->meta_title; ?>
" size="60" class="admin_option_input">
                     </div>
                     <div class="admin_option">
                     	<div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_META_DESCRIPTION']; ?>

                          	<?php echo smarty_function_translate_content(array('table' => 'types','field' => 'meta_description','row_id' => $this->_tpl_vars['type_id'],'field_type' => 'text'), $this);?>

                        </div>
                        <div class="admin_option_description">
                        	<?php echo $this->_tpl_vars['LANG_META_TITLE_DESCR']; ?>

                        </div>
                        <textarea name="meta_description" cols="60" rows="5"><?php echo $this->_tpl_vars['type']->meta_description; ?>
</textarea>
                     </div>

                     <input class="button save_button" type=submit name="submit" value="<?php if ($this->_tpl_vars['type_id'] != 'new'): ?><?php echo $this->_tpl_vars['LANG_BUTTON_SAVE_CHANGES']; ?>
<?php else: ?><?php echo $this->_tpl_vars['LANG_BUTTON_CREATE_TYPE']; ?>
<?php endif; ?>">
                     </form>
                </div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>