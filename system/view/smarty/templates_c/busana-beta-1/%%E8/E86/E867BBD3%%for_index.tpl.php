<?php /* Smarty version 2.6.26, created on 2012-03-05 07:12:38
         compiled from frontend/blocks/for_index.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'frontend/blocks/for_index.tpl', 1, false),)), $this); ?>
<?php if (count($this->_tpl_vars['items_array'])): ?>
	<?php if (! $this->_tpl_vars['system_settings']['single_type_structure']): ?>
	<div class="type_line">
		<div class="type_title">
			<a href="<?php echo $this->_tpl_vars['VH']->site_url($this->_tpl_vars['type']->getUrl()); ?>
"><?php echo $this->_tpl_vars['type']->name; ?>
</a>
		</div>
	</div>
	<?php endif; ?>

	<?php if ($this->_tpl_vars['wrapper_object']): ?>
		<?php echo $this->_tpl_vars['wrapper_object']->render(); ?>

	<?php else: ?>
		<?php $_from = $this->_tpl_vars['items_array']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['item']):
?>
			<?php echo $this->_tpl_vars['item']->view($this->_tpl_vars['view_name']); ?>

		<?php endforeach; endif; unset($_from); ?>
	<?php endif; ?>
<?php endif; ?>