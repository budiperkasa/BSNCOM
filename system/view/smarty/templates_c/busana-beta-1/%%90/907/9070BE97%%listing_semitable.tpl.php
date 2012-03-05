<?php /* Smarty version 2.6.26, created on 2012-02-06 08:44:32
         compiled from frontend/listings/listing_semitable.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'frontend/listings/listing_semitable.tpl', 43, false),)), $this); ?>
<?php $this->assign('listing_id', $this->_tpl_vars['listing']->id); ?>
<?php $this->assign('listing_unique_id', $this->_tpl_vars['listing']->getUniqueId()); ?>

										<span id="listing_id-<?php echo $this->_tpl_vars['listing_unique_id']; ?>
"></span>
	                         			<?php if ($this->_tpl_vars['listing']->level->featured): ?>
	                         				<div class="featured_label"><?php echo $this->_tpl_vars['LANG_FEATURED']; ?>
</div>
	                         			<?php else: ?>
	                         				<div class="featured_label">&nbsp;</div>
	                         			<?php endif; ?>
	                         			<div class="clear_float"></div>
										<?php if ($this->_tpl_vars['listing']->level->logo_enabled): ?>
		                         		<div class="img_div_border" style="width: <?php echo $this->_tpl_vars['listing']->level->explodeSize('logo_size','width'); ?>
px; height: <?php echo $this->_tpl_vars['listing']->level->explodeSize('logo_size','height'); ?>
}px;">
											<div class="img_div" style="width: <?php echo $this->_tpl_vars['listing']->level->explodeSize('logo_size','width'); ?>
px; height: <?php echo $this->_tpl_vars['listing']->level->explodeSize('logo_size','height'); ?>
}px;">
												<table width="<?php echo $this->_tpl_vars['listing']->level->explodeSize('logo_size','width')+4; ?>
px" height="<?php echo $this->_tpl_vars['listing']->level->explodeSize('logo_size','height')+4; ?>
px">
													<tr>
														<td valign="middle" align="center">
														<?php if ($this->_tpl_vars['listing']->logo_file): ?>
															<a href="<?php echo $this->_tpl_vars['VH']->site_url("listings/".($this->_tpl_vars['listing_unique_id'])); ?>
"><img src="<?php echo $this->_tpl_vars['users_content']; ?>
/users_images/logos/<?php echo $this->_tpl_vars['listing']->logo_file; ?>
" alt="<?php echo $this->_tpl_vars['listing']->title(); ?>
"/></a>
														<?php else: ?>
															<img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/default_logo.jpg" width="<?php echo $this->_tpl_vars['listing']->level->explodeSize('logo_size','width'); ?>
" height="<?php echo $this->_tpl_vars['listing']->level->explodeSize('logo_size','height'); ?>
" />
														<?php endif; ?>
														</td>
													</tr>
												</table>
											</div>
										</div>
										<?php endif; ?>

										<?php if ($this->_tpl_vars['listing']->level->ratings_enabled): ?>
											<?php $this->assign('avg_rating', $this->_tpl_vars['listing']->getRatings()); ?>
											<?php echo $this->_tpl_vars['avg_rating']->view(); ?>

										<?php endif; ?>
										<?php if ($this->_tpl_vars['listing']->level->reviews_mode && $this->_tpl_vars['listing']->level->reviews_mode != 'disabled'): ?>
		                         		<div class="stat">
		                         			<a href="<?php echo $this->_tpl_vars['VH']->site_url("listings/".($this->_tpl_vars['listing_unique_id'])); ?>
#reviews-tab" title="<?php if ($this->_tpl_vars['listing']->level->reviews_mode == 'reviews'): ?><?php echo $this->_tpl_vars['LANG_READ_REVIEWS']; ?>
<?php else: ?><?php echo $this->_tpl_vars['LANG_READ_COMMENTS']; ?>
<?php endif; ?>"><?php echo $this->_tpl_vars['listing']->getReviewsCount(); ?>
&nbsp;<?php if ($this->_tpl_vars['listing']->level->reviews_mode == 'reviews'): ?><?php echo $this->_tpl_vars['LANG_REVIEWS']; ?>
<?php else: ?><?php echo $this->_tpl_vars['LANG_COMMENTS']; ?>
<?php endif; ?></a>
		                         		</div>
		                         		<?php endif; ?>
		                         		<div class="clear_float"></div>

										<div class="listing_title listing_title_small">
	                         				<a href="<?php echo $this->_tpl_vars['VH']->site_url("listings/".($this->_tpl_vars['listing_unique_id'])); ?>
"><?php echo $this->_tpl_vars['listing']->title(); ?>
</a>
	                         			</div>
	                         			<?php if ($this->_tpl_vars['listing']->type->categories_type != 'disabled' && $this->_tpl_vars['listing']->level->categories_number && count($this->_tpl_vars['listing']->categories_array())): ?>
		                         			<div class="listing_categories_semitable">
		                         				<?php echo $this->_tpl_vars['LANG_SUMITTED_3']; ?>
&nbsp;
		                         				<?php $_from = $this->_tpl_vars['listing']->categories_array(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['category']):
?>
		                         				<?php $this->assign('category_seo_name', $this->_tpl_vars['category']->seo_name); ?>
		                         					<a href="<?php echo $this->_tpl_vars['VH']->site_url($this->_tpl_vars['category']->getUrl()); ?>
" class="listing_cat_link"><?php echo $this->_tpl_vars['category']->name; ?>
</a>&nbsp;&nbsp;
		                         				<?php endforeach; endif; unset($_from); ?>
		                         			</div>
	                         			<?php endif; ?>
	                         			<?php echo $this->_tpl_vars['listing']->outputMode('semitable'); ?>