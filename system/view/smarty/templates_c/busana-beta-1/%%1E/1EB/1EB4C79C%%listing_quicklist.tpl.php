<?php /* Smarty version 2.6.26, created on 2012-02-21 09:53:05
         compiled from frontend/listings/listing_quicklist.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'frontend/listings/listing_quicklist.tpl', 31, false),array('modifier', 'count', 'frontend/listings/listing_quicklist.tpl', 32, false),array('modifier', 'nl2br', 'frontend/listings/listing_quicklist.tpl', 84, false),)), $this); ?>
<?php $this->assign('listing_id', $this->_tpl_vars['listing']->id); ?>
<?php $this->assign('listing_unique_id', $this->_tpl_vars['listing']->getUniqueId()); ?>

								<div>
									<div class="remove_favourite" id="<?php echo $this->_tpl_vars['listing_id']; ?>
"><a href="javascript: void(0);"><?php echo $this->_tpl_vars['LANG_REMOVE_FROM_QUICK_LIST']; ?>
</a></div>
									<div id="listing_id-<?php echo $this->_tpl_vars['listing_unique_id']; ?>
" class="listing_preview <?php if ($this->_tpl_vars['listing']->level->featured): ?>featured<?php endif; ?>">
										<div class="listing_head">
		                         			<?php if ($this->_tpl_vars['listing']->featured): ?>
		                         				<div class="featured_label"><?php echo $this->_tpl_vars['LANG_FEATURED']; ?>
</div>
		                         			<?php endif; ?>
		                         			<div class="listing_title">
		                         				<a href="<?php echo $this->_tpl_vars['VH']->site_url("listings/".($this->_tpl_vars['listing_unique_id'])); ?>
"><?php echo $this->_tpl_vars['listing']->title(); ?>
</a>
		                         			</div>
		                         			<div style="text-align: right; float: right">
												<?php if ($this->_tpl_vars['listing']->level->images_count && $this->_tpl_vars['listing']->getAssignedImages()): ?>
													<a href="<?php echo $this->_tpl_vars['VH']->site_url("listings/".($this->_tpl_vars['listing_unique_id'])); ?>
#images" title="<?php echo $this->_tpl_vars['LANG_LISTING_IMAGES_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/images.png" /></a>&nbsp;
												<?php endif; ?>
												<?php if ($this->_tpl_vars['listing']->level->videos_count && $this->_tpl_vars['listing']->getAssignedVideos()): ?>
													<a href="<?php echo $this->_tpl_vars['VH']->site_url("listings/".($this->_tpl_vars['listing_unique_id'])); ?>
#videos" title="<?php echo $this->_tpl_vars['LANG_LISTING_VIDEOS_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/videos.png" /></a>&nbsp;
												<?php endif; ?>
												<?php if ($this->_tpl_vars['listing']->level->files_count && $this->_tpl_vars['listing']->getAssignedFiles()): ?>
													<a href="<?php echo $this->_tpl_vars['VH']->site_url("listings/".($this->_tpl_vars['listing_unique_id'])); ?>
#files" title="<?php echo $this->_tpl_vars['LANG_LISTING_FILES_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_link.png" /></a>&nbsp;
												<?php endif; ?>
												<?php if ($this->_tpl_vars['listing']->type->locations_enabled && $this->_tpl_vars['listing']->locations_count(true) && $this->_tpl_vars['listing']->level->maps_enabled): ?>
													<a href="#maps_canvas" id="open_iw_<?php echo $this->_tpl_vars['listing_unique_id']; ?>
" title="<?php echo $this->_tpl_vars['LANG_MAP']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/map.png" /></a>&nbsp;
												<?php endif; ?>
												<?php if ($this->_tpl_vars['listing']->level->option_email_friend): ?>
													<a href="<?php echo $this->_tpl_vars['VH']->site_url("email/send/listing_id/".($this->_tpl_vars['listing_id'])."/target/friend/"); ?>
" class="nyroModal" title="<?php echo $this->_tpl_vars['LANG_EMAIL_FRIEND']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/icons/email_go.png"></a>&nbsp;
												<?php endif; ?>
			                         		</div>
		                         			<div class="listing_author"><?php echo $this->_tpl_vars['LANG_SUMITTED_1']; ?>
 <?php if ($this->_tpl_vars['listing']->user->users_group->is_own_page && $this->_tpl_vars['listing']->user->users_group->id != 1): ?><a href="<?php echo $this->_tpl_vars['VH']->site_url("users/".($this->_tpl_vars['user_unique_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_VIEW_USER_PAGE_OPTION']; ?>
"><?php echo $this->_tpl_vars['listing']->user->login; ?>
</a><?php else: ?><strong><?php echo $this->_tpl_vars['listing']->user->login; ?>
</strong><?php endif; ?> <?php echo $this->_tpl_vars['LANG_SUMITTED_2']; ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['listing']->creation_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%D %H:%M") : smarty_modifier_date_format($_tmp, "%D %H:%M")); ?>
</div>
		                         			<?php if ($this->_tpl_vars['listing']->type->categories_type != 'disabled' && $this->_tpl_vars['listing']->level->categories_number && count($this->_tpl_vars['listing']->categories_array())): ?>
			                         			<div class="listing_categories">
			                         				<?php echo $this->_tpl_vars['LANG_SUMITTED_3']; ?>
&nbsp;
			                         				<?php $_from = $this->_tpl_vars['listing']->categories_array(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['category']):
?>
			                         				<?php $this->assign('category_url', $this->_tpl_vars['category']->getUrl()); ?>
			                         					<a href="<?php echo $this->_tpl_vars['VH']->site_url($this->_tpl_vars['category']->getUrl()); ?>
" class="listing_cat_link"><?php echo $this->_tpl_vars['category']->name; ?>
</a>&nbsp;&nbsp;
			                         				<?php endforeach; endif; unset($_from); ?>
			                         			</div>
		                         			<?php endif; ?>
		                         			<div class="clear_float"></div>

		                         			<?php if ($this->_tpl_vars['listing']->level->ratings_enabled): ?>
												<?php $this->assign('avg_rating', $this->_tpl_vars['listing']->getRatings()); ?>
												<?php echo $this->_tpl_vars['avg_rating']->view(); ?>

											<?php endif; ?>
											<?php if ($this->_tpl_vars['listing']->level->reviews_mode && $this->_tpl_vars['listing']->level->reviews_mode != 'disabled'): ?>
		                         			<div class="stat">
		                         				<a href="<?php echo $this->_tpl_vars['VH']->site_url("listings/".($this->_tpl_vars['listing_unique_id'])); ?>
#reviews" title="<?php if ($this->_tpl_vars['listing']->level->reviews_mode == 'reviews'): ?><?php echo $this->_tpl_vars['LANG_READ_REVIEWS']; ?>
<?php else: ?><?php echo $this->_tpl_vars['LANG_READ_COMMENTS']; ?>
<?php endif; ?>"><?php echo $this->_tpl_vars['listing']->getReviewsCount(); ?>
 <?php if ($this->_tpl_vars['listing']->level->reviews_mode == 'reviews'): ?><?php echo $this->_tpl_vars['LANG_REVIEWS']; ?>
<?php else: ?><?php echo $this->_tpl_vars['LANG_COMMENTS']; ?>
<?php endif; ?></a> <a href="<?php echo $this->_tpl_vars['VH']->site_url("listings/".($this->_tpl_vars['listing_unique_id'])); ?>
#reviews" title="<?php if ($this->_tpl_vars['listing']->level->reviews_mode == 'reviews'): ?><?php echo $this->_tpl_vars['LANG_READ_REVIEWS']; ?>
<?php else: ?><?php echo $this->_tpl_vars['LANG_READ_COMMENTS']; ?>
<?php endif; ?>"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/icons/comments.png" /></a>
		                         			</div>
		                         			<?php endif; ?>
		                         			<div class="clear_float"></div>
	                         			</div>

										<?php if ($this->_tpl_vars['listing']->level->logo_enabled): ?>
		                         		<div class="img_div_border" style="margin: 0 10px 10px 0; float: left; width: <?php echo $this->_tpl_vars['listing']->level->explodeSize('logo_size','width'); ?>
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
										<div class="listing_description">
											<?php if ($this->_tpl_vars['listing']->type->locations_enabled && $this->_tpl_vars['listing']->locations_count()): ?>
											<div class="listing_address_block">
												<?php $this->assign('i', 1); ?>
												<?php $_from = $this->_tpl_vars['listing']->locations_array(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['location']):
?>
												<div class="address_line"><?php if ($this->_tpl_vars['listing']->locations_count() > 1): ?><span class="address_label"><?php echo $this->_tpl_vars['LANG_LISTING_ADDRESS']; ?>
 <?php echo $this->_tpl_vars['i']++; ?>
:</span> <?php endif; ?><?php echo $this->_tpl_vars['location']->compileAddress(); ?>
</div>
												<?php endforeach; endif; unset($_from); ?>
											</div>
											<?php endif; ?>
											<?php if ($this->_tpl_vars['listing']->level->description_mode == 'richtext'): ?>
											<?php echo $this->_tpl_vars['listing']->listing_description_teaser(); ?>

											<?php else: ?>
											<?php echo ((is_array($_tmp=$this->_tpl_vars['listing']->listing_description_teaser())) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

											<?php endif; ?>
		                         		</div>
	                         			<div class="clear_float"></div>
	                         		</div>
	                         	</div>