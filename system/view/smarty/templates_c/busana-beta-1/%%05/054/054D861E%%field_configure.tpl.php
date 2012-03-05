<?php /* Smarty version 2.6.26, created on 2012-02-06 04:39:32
         compiled from content_fields/fields/website/field_configure.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

                <div class="content">
                     <h3><?php echo $this->_tpl_vars['LANG_CONFIGURE_FIELD_1']; ?>
 "<?php echo $this->_tpl_vars['field']->type_name; ?>
" <?php echo $this->_tpl_vars['LANG_CONFIGURE_FIELD_2']; ?>
 "<?php echo $this->_tpl_vars['field']->name; ?>
"</h3>
                     <form action="" method="post">
					 <input type="hidden" name="id" value="<?php echo $this->_tpl_vars['field']->id; ?>
"> 
                     <div class="admin_option">
                          <div class="admin_option_name" >
                          	<?php echo $this->_tpl_vars['LANG_ENABLE_REDIRECT']; ?>

                          </div>
                          <label><input type=checkbox name="enable_redirect" value="1" <?php if ($this->_tpl_vars['enable_redirect']): ?>checked<?php endif; ?>> <?php echo $this->_tpl_vars['LANG_ENABLED']; ?>
</label>
                     </div>
                     <br/>
                     <input class="button save_button" type=submit name="submit" value="<?php echo $this->_tpl_vars['LANG_BUTTON_SAVE_CHANGES']; ?>
">
                     </form>
                </div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>