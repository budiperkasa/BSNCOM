<?php /* Smarty version 2.6.26, created on 2012-02-09 05:47:50
         compiled from locations/admin_locations.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'render_frontend_block', 'locations/admin_locations.tpl', 27, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

				<script language="javascript" type="text/javascript">
					
					//var loc_levels_count = <?php echo $this->_tpl_vars['loc_levels_count']; ?>
;
					var action_cmd;
					
					function submit_locations_form()
	                {
	                	$("#locations_form").attr("action", '<?php echo $this->_tpl_vars['VH']->site_url('admin/locations/'); ?>
' + action_cmd + '/');
	                	return true;
	                }
				</script>

                <div class="content">
                     <h3><?php echo $this->_tpl_vars['LANG_EDIT_LOCATIONS']; ?>
</h3>
                     <h4><?php echo $this->_tpl_vars['LANG_EDIT_LOCATIONS_DESCR']; ?>
</h4>

                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/locations/create"); ?>
" title="<?php echo $this->_tpl_vars['LANG_NEW_LOCATION_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_add.png" /></a>
                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/locations/create"); ?>
"><?php echo $this->_tpl_vars['LANG_NEW_LOCATION_OPTION']; ?>
</a>&nbsp;&nbsp;&nbsp;
                     <div class="px10"></div>

                     <form id="locations_form" action="" method="post" onSubmit="submit_locations_form();">
                     <div class="admin_option_name">
                         <?php echo $this->_tpl_vars['LANG_LOCATIONS_LIST']; ?>

                     </div>
                     <?php echo smarty_function_render_frontend_block(array('block_type' => 'locations','block_template' => 'backend/blocks/admin_locations_management.tpl','is_counter' => false,'max_depth' => 'max'), $this);?>

                     <div class="px10"></div>
					 <?php echo $this->_tpl_vars['LANG_WITH_SELECTED']; ?>
:
	                 <select name="table_action" onchange="action_cmd=this.options[this.selectedIndex].value; submit_locations_form(); this.form.submit()">
	                 	<option value=""><?php echo $this->_tpl_vars['LANG_CHOOSE_ACTION']; ?>
</option>
	                 	<option value="label"><?php echo $this->_tpl_vars['LANG_BUTTON_LABEL_LOCATIONS']; ?>
</option>
	                 	<option value="delete"><?php echo $this->_tpl_vars['LANG_BUTTON_DELETE_LOCATIONS']; ?>
</option>
	                 </select>
	                 <div class="px10"></div>
	                 <input type="button" class="button save_button" onClick="action_cmd='save'; submit_locations_form(); this.form.submit()" value="<?php echo $this->_tpl_vars['LANG_BUTTON_SAVE_CHANGES']; ?>
">
                     &nbsp;&nbsp;
					 <input type=submit class="button activate_button" onClick="action_cmd='synchronize'; submit_locations_form(); this.form.submit()" value="<?php echo $this->_tpl_vars['LANG_BUTTON_SYNCHRONIZE_LOCATIONS']; ?>
">
					 &nbsp;&nbsp;
					 <input type=submit class="button refresh_button" onClick="action_cmd='regeocode'; submit_locations_form(); this.form.submit()" value="<?php echo $this->_tpl_vars['LANG_BUTTON_REGEOCODE_LOCATIONS']; ?>
">
                     </form>
                </div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>