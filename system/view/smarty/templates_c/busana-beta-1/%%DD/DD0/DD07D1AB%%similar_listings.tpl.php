<?php /* Smarty version 2.6.26, created on 2012-03-05 07:19:15
         compiled from frontend/blocks/similar_listings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'frontend/blocks/similar_listings.tpl', 1, false),array('modifier', 'date_format', 'frontend/blocks/similar_listings.tpl', 23, false),)), $this); ?>
<?php if (count($this->_tpl_vars['items_array'])): ?>
	<div class="sidebar_block similar_listings">
		<h1><?php echo $this->_tpl_vars['LANG_SIMILAR_LISTINGS_TITLE']; ?>
</h1>
		<?php $_from = $this->_tpl_vars['items_array']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['listing']):
?>
			<div class="block_item">
				<?php if ($this->_tpl_vars['listing']->level->logo_enabled && $this->_tpl_vars['listing']->logo_file): ?>
				<div class="block_item_img">
					<a title="<?php echo $this->_tpl_vars['listing']->title(); ?>
" href="<?php echo $this->_tpl_vars['VH']->site_url($this->_tpl_vars['listing']->url()); ?>
">
						<img src="<?php echo $this->_tpl_vars['users_content']; ?>
/users_images/thmbs_cropped/<?php echo $this->_tpl_vars['listing']->logo_file; ?>
" alt="<?php echo $this->_tpl_vars['listing']->title(); ?>
" />
					</a>
				</div>
				<?php endif; ?>
				<div>
					<a title="<?php echo $this->_tpl_vars['listing']->title(); ?>
" href="<?php echo $this->_tpl_vars['VH']->site_url($this->_tpl_vars['listing']->url()); ?>
"><?php echo $this->_tpl_vars['listing']->title(); ?>
</a>
				</div>
				<div>
				<?php if ($this->_tpl_vars['listing']->level->ratings_enabled): ?>
					<?php $this->assign('avg_rating', $this->_tpl_vars['listing']->getRatings()); ?>
					<?php echo $this->_tpl_vars['avg_rating']->setInactive(); ?>

					<?php echo $this->_tpl_vars['avg_rating']->view(); ?>

				<?php endif; ?>
				</div>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['listing']->creation_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%D %H:%M") : smarty_modifier_date_format($_tmp, "%D %H:%M")); ?>

			</div>
		<?php endforeach; endif; unset($_from); ?>
	</div>
<?php endif; ?>