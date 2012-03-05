<?php /* Smarty version 2.6.26, created on 2012-02-06 02:23:50
         compiled from frontend/right-sidebar.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'cat', 'frontend/right-sidebar.tpl', 14, false),array('function', 'render_frontend_block', 'frontend/right-sidebar.tpl', 17, false),)), $this); ?>

<!-- RIGHT SIDEBAR -->

<!-- Calendar -->
<?php if ($this->_tpl_vars['CI']->load->is_module_loaded('calendar')): ?>
	<?php echo $this->_tpl_vars['VH']->attachCalendar($this->_tpl_vars['CI']); ?>

<?php endif; ?>
<!-- /Calendar -->

<!-- Similar listings -->
<?php if ($this->_tpl_vars['listing']): ?>
	<?php $this->assign('categories_list', ''); ?>
	<?php $_from = $this->_tpl_vars['listing']->categories_array(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['category']):
?>
		<?php $this->assign('categories_list', ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['categories_list'])) ? $this->_run_mod_handler('cat', true, $_tmp, ",") : smarty_modifier_cat($_tmp, ",")))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['category']->id) : smarty_modifier_cat($_tmp, $this->_tpl_vars['category']->id))); ?>
	<?php endforeach; endif; unset($_from); ?>

	<?php echo smarty_function_render_frontend_block(array('block_type' => 'listings','block_template' => 'frontend/blocks/similar_listings.tpl','except_listings' => $this->_tpl_vars['listing']->id,'search_type' => $this->_tpl_vars['listing']->type,'search_status' => 1,'search_users_status' => 2,'search_category' => $this->_tpl_vars['categories_list'],'search_location' => $this->_tpl_vars['current_location'],'orderby' => 'l.creation_date','limit' => 5), $this);?>

<?php endif; ?>
<!-- /Similar listings -->

<!-- Categories list -->
<?php if (! $this->_tpl_vars['current_type'] || $this->_tpl_vars['current_type']->categories_type != 'disabled'): ?>
	<!-- 
	$system_settings.categories_block_type - may be categories-bar-jshide.tpl or categories-bar.tpl or categories-bar-ajax.tpl
	 -->
	<?php $this->assign('categories_block_type', $this->_tpl_vars['system_settings']['categories_block_type']); ?>
	<?php echo smarty_function_render_frontend_block(array('block_type' => 'categories','block_template' => ((is_array($_tmp=((is_array($_tmp="frontend/blocks/")) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['categories_block_type']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['categories_block_type'])))) ? $this->_run_mod_handler('cat', true, $_tmp, ".tpl") : smarty_modifier_cat($_tmp, ".tpl")),'type' => $this->_tpl_vars['current_type'],'current_category' => $this->_tpl_vars['current_category'],'is_counter' => true,'max_depth' => 2), $this);?>

<?php endif; ?>
<!-- /Categories list -->
             	
<!-- /RIGHT SIDEBAR -->