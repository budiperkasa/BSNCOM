<?php /* Smarty version 2.6.26, created on 2012-02-07 04:40:02
         compiled from settings/admin_titles_templates.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

				<div class="content">
					<?php echo $this->_tpl_vars['VH']->validation_errors(); ?>

					<h3><?php echo $this->_tpl_vars['LANG_TITLES_TEMPLATES_TITLE']; ?>
</h3>
					
					<div>
						<?php echo $this->_tpl_vars['LANG_TITLES_TEMPLATES_DESCR']; ?>

					</div>
					
					<div class="px10"></div>
					
					<form action="" method="post">
					<?php $_from = $this->_tpl_vars['types']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['type']):
?>
						<?php $_from = $this->_tpl_vars['type']->levels; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['level']):
?>
						<div class="admin_option">
							<div class="admin_option_name">
								<?php echo $this->_tpl_vars['LANG_TITLES_OF_TYPE']; ?>
 "<?php echo $this->_tpl_vars['type']->name; ?>
" <?php echo $this->_tpl_vars['LANG_TITLES_OF_LEVEL']; ?>
 "<?php echo $this->_tpl_vars['level']->name; ?>
"<span class="red_asterisk">*</span>
							</div>
							<input type=text name="titles[<?php echo $this->_tpl_vars['level']->id; ?>
]" value="<?php echo $this->_tpl_vars['level']->titles_template; ?>
" size="90" />
						</div>
						<?php endforeach; endif; unset($_from); ?>
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