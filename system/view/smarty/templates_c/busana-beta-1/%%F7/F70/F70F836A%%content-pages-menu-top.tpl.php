<?php /* Smarty version 2.6.26, created on 2012-02-06 03:19:59
         compiled from frontend/content-pages-menu-top.tpl */ ?>
				<div id="head-links">
					<ul>
						<li><a href="<?php echo $this->_tpl_vars['VH']->index_url(); ?>
"><?php echo $this->_tpl_vars['LANG_TOP_MENU_HOME']; ?>
</a></li>

						<?php if ($this->_tpl_vars['content_access_obj']->isPermission('Create listings') || $this->_tpl_vars['content_access_obj']->isPermission('Create banners')): ?>
							<li>|</li>
							<li><a id="advertise_link" href="<?php echo $this->_tpl_vars['VH']->site_url('advertise'); ?>
"><?php echo $this->_tpl_vars['LANG_TOP_MENU_ADS']; ?>
</a></li>
						<?php endif; ?>

						<?php $_from = $this->_tpl_vars['content_pages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['page']):
?>
							<?php $this->assign('page_url', $this->_tpl_vars['page']['url']); ?>
							<li>|</li>
           					<li><a href="<?php echo $this->_tpl_vars['VH']->site_url("node/".($this->_tpl_vars['page_url'])); ?>
"><?php echo $this->_tpl_vars['page']['title']; ?>
</a></li>
           				<?php endforeach; endif; unset($_from); ?>
           				
           				<?php if ($this->_tpl_vars['system_settings']['enable_contactus_page']): ?>
							<li>|</li>
							<li><a href="<?php echo $this->_tpl_vars['VH']->site_url('contactus'); ?>
" rel="nofollow"><?php echo $this->_tpl_vars['LANG_CONTACTUS_LINK']; ?>
</a></li>
						<?php endif; ?>
					</ul>
				</div>