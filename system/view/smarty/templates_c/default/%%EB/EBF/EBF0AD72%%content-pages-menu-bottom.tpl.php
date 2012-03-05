<?php /* Smarty version 2.6.26, created on 2012-02-06 02:23:50
         compiled from frontend/content-pages-menu-bottom.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'frontend/content-pages-menu-bottom.tpl', 7, false),)), $this); ?>
				<span id="footer-links">
					<ul>
						<?php $this->assign('i', 0); ?>
						<?php $_from = $this->_tpl_vars['content_pages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['page']):
?>
						<?php $this->assign('page_url', $this->_tpl_vars['page']['url']); ?>
           					<li><a href="<?php echo $this->_tpl_vars['VH']->site_url("node/".($this->_tpl_vars['page_url'])); ?>
"><?php echo $this->_tpl_vars['page']['title']; ?>
</a></li>
	           				<?php if (count($this->_tpl_vars['content_pages']) != ( $this->_tpl_vars['i']++ ) + 1): ?>
	           					<li>|</li>
	           				<?php endif; ?>
           				<?php endforeach; endif; unset($_from); ?>
           				
           				<?php if ($this->_tpl_vars['system_settings']['enable_contactus_page']): ?>
           					<?php if ($this->_tpl_vars['i'] > 0): ?>
           						<li>|</li>
           					<?php endif; ?>
							<li><a href="<?php echo $this->_tpl_vars['VH']->site_url('contactus'); ?>
" rel="nofollow"><?php echo $this->_tpl_vars['LANG_CONTACTUS_LINK']; ?>
</a></li>
						<?php endif; ?>
						
						<?php if ($this->_tpl_vars['CI']->load->is_module_loaded('sitemap')): ?>
							<li>|</li>
							<li><a href="<?php echo $this->_tpl_vars['VH']->site_url('sitemap/'); ?>
"><?php echo $this->_tpl_vars['LANG_SITEMAP_LINK']; ?>
</a></li>
						<?php endif; ?>
					</ul>
				</span>