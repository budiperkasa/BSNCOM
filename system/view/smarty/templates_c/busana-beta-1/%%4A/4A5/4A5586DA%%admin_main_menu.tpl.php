<?php /* Smarty version 2.6.26, created on 2012-02-06 03:14:04
         compiled from backend/admin_main_menu.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('insert', 'route', 'backend/admin_main_menu.tpl', 18, false),)), $this); ?>

<!-- MAIN MENU -->
			<script language="JavaScript" type="text/javascript" src="<?php echo $this->_tpl_vars['smarty_obj']->getFileInTheme('js/admin_menu.js'); ?>
"></script>
			<td width="230px">
				<div class="main_menu">
                     <div id="extra">
                       <h2><span><?php echo $this->_tpl_vars['LANG_MAIN_MENU']; ?>
</span></h2>
                     </div>
                     <div class="menu_content">
                     	<div id="ex1">
                     		<ul id="browser" class="filetree">
                     			<?php echo $this->_tpl_vars['main_menu_list']; ?>

                     		</ul>
                     	</div>
                     	
                     	<script language="javascript" type="text/javascript">
                     		<!-- smarty 'insert.route.php' function -->
                     		<?php require_once(SMARTY_CORE_DIR . 'core.run_insert_handler.php');
echo smarty_core_run_insert_handler(array('args' => array('name' => 'route')), $this); ?>


                         	 var sinonims = new Array();
                         	 <?php echo $this->_tpl_vars['sinonims_sinonim_input']; ?>

                         	 var urls = new Array();
                         	 <?php echo $this->_tpl_vars['sinonims_url_input']; ?>

                     	</script>

                     </div>
                </div>
             </td>
<!-- /MAIN MENU -->