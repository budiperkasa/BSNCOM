<?php /* Smarty version 2.6.26, created on 2012-03-05 07:19:14
         compiled from frontend/left-sidebar.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'render_frontend_block', 'frontend/left-sidebar.tpl', 9, false),array('modifier', 'cat', 'frontend/left-sidebar.tpl', 9, false),array('modifier', 'count', 'frontend/left-sidebar.tpl', 22, false),)), $this); ?>

<!-- LEFT SIDEBAR -->
<!-- Categories list -->
<?php if (! $this->_tpl_vars['current_type'] || $this->_tpl_vars['current_type']->categories_type != 'disabled'): ?>
	<!-- 
	$system_settings.categories_block_type - may be categories-bar-jshide.tpl or categories-bar.tpl or categories-bar-ajax.tpl
	 -->
	<?php $this->assign('categories_block_type', $this->_tpl_vars['system_settings']['categories_block_type']); ?>
	<?php echo smarty_function_render_frontend_block(array('block_type' => 'categories','block_template' => ((is_array($_tmp=((is_array($_tmp="frontend/blocks/")) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['categories_block_type']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['categories_block_type'])))) ? $this->_run_mod_handler('cat', true, $_tmp, ".tpl") : smarty_modifier_cat($_tmp, ".tpl")),'type' => $this->_tpl_vars['current_type'],'current_category' => $this->_tpl_vars['current_category'],'is_counter' => true,'max_depth' => 2), $this);?>

<?php endif; ?>
<!-- /Categories list -->
             	

						<?php if ($this->_tpl_vars['VH']->checkQuickList()): ?>
						<div id="quick_list">
							<a href="<?php echo $this->_tpl_vars['VH']->site_url('quick_list'); ?>
"><?php echo $this->_tpl_vars['LANG_QUICK_LIST']; ?>
 (<?php echo count($this->_tpl_vars['VH']->checkQuickList()); ?>
)</a>
						</div>
						<?php endif; ?>
						
						

<!-- /LEFT SIDEBAR -->