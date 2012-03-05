<?php /* Smarty version 2.6.26, created on 2012-02-06 03:20:36
         compiled from backend/breadcrumbs.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'backend/breadcrumbs.tpl', 1, false),)), $this); ?>
<?php if (count($this->_tpl_vars['breadcrumbs']) > 0): ?>
				<div class="breadcrumbs">
					<ul>
					<?php $this->assign('i', 1); ?>
					<?php $_from = $this->_tpl_vars['breadcrumbs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['link'] => $this->_tpl_vars['breadcrumb']):
?>
					<?php if ($this->_tpl_vars['i']++ != count($this->_tpl_vars['breadcrumbs'])): ?>
						<li><a href="<?php echo $this->_tpl_vars['VH']->site_url($this->_tpl_vars['link']); ?>
"><?php echo $this->_tpl_vars['breadcrumb']; ?>
</a>&nbsp;>>&nbsp;</li>
					<?php else: ?>
						<li><?php echo $this->_tpl_vars['breadcrumb']; ?>
</li>
					<?php endif; ?>
					<?php endforeach; endif; unset($_from); ?>
					</ul>
					<div class="clear_float"></div>
				</div>
<?php endif; ?>