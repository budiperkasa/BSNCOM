<?php /* Smarty version 2.6.26, created on 2012-02-06 04:43:19
         compiled from content_fields/fields/checkboxes/field_option_settings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'translate_content', 'content_fields/fields/checkboxes/field_option_settings.tpl', 18, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $this->assign('field_id', $this->_tpl_vars['field']->id); ?>

                <div class="content">
                	<?php echo $this->_tpl_vars['VH']->validation_errors(); ?>

                     <h3>
                     <?php if ($this->_tpl_vars['option_id'] == 'new'): ?><?php echo $this->_tpl_vars['LANG_CREATE_FIELD_OPTION_TITLE_1']; ?>
 "<?php echo $this->_tpl_vars['field']->type_name; ?>
" <?php echo $this->_tpl_vars['LANG_CREATE_FIELD_OPTION_TITLE_2']; ?>
 "<?php echo $this->_tpl_vars['field']->name; ?>
"
                     <?php else: ?>
                     <?php echo $this->_tpl_vars['LANG_EDIT_FIELD_OPTION_TITLE_1']; ?>
 "<?php echo $this->_tpl_vars['field']->type_name; ?>
" <?php echo $this->_tpl_vars['LANG_EDIT_FIELD_OPTION_TITLE_2']; ?>
 "<?php echo $this->_tpl_vars['field']->name; ?>
"
                     <?php endif; ?>
                     </h3>

                     <form action="" method="post">
					 <input type="hidden" name="id" value="<?php echo $this->_tpl_vars['field']->id; ?>
">
                     <div class="admin_option">
						<div class="admin_option_name" >
							<?php echo $this->_tpl_vars['LANG_CHECKBOX_NAME_TH']; ?>
<span class="red_asterisk">*</span>
							<?php echo smarty_function_translate_content(array('table' => 'content_fields_type_checkboxes','field' => 'option_name','row_id' => $this->_tpl_vars['option_id']), $this);?>

						</div>
						<input type=text name="name" value="<?php echo $this->_tpl_vars['name']; ?>
" size="40" class="admin_option_input">
					 </div>
					 <?php if ($this->_tpl_vars['option_id'] != 'new'): ?>
                     <input class="button save_button" type=submit name="submit" value="<?php echo $this->_tpl_vars['LANG_BUTTON_SAVE_CHANGES']; ?>
">
                     <?php else: ?>
                     <input class="button create_button" type=submit name="submit" value="<?php echo $this->_tpl_vars['LANG_BUTTON_CREATE_FIELD_OPTION']; ?>
">
                     <?php endif; ?>
                     </form>
                </div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>