<?php /* Smarty version 2.6.26, created on 2012-02-08 07:13:03
         compiled from frontend/blocks/listings_of_user.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'frontend/blocks/listings_of_user.tpl', 1, false),)), $this); ?>
<?php if (count($this->_tpl_vars['items_array'])): ?>
	<div class="px10"></div>
	<div class="px10"></div>
	<div class="listings_of_user">
		<h1><?php echo $this->_tpl_vars['LANG_LISTINGS_OF_USER_TITLE']; ?>
</h1>
		<?php echo $this->_tpl_vars['wrapper_object']->render(); ?>

	</div>
<?php endif; ?>