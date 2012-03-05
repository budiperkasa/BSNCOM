<?php /* Smarty version 2.6.26, created on 2012-03-05 07:12:38
         compiled from frontend/listings/listing_short.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'frontend/listings/listing_short.tpl', 15, false),)), $this); ?>
<?php $this->assign('listing_id', $this->_tpl_vars['listing']->id); ?>
<?php $this->assign('listing_unique_id', $this->_tpl_vars['listing']->getUniqueId()); ?>

	                         				<?php if ($this->_tpl_vars['listing']->level->featured): ?>
	                         					<td class="featured_vertical">
	                         					<?php $_from = $this->_tpl_vars['VH']->mb_str_split($this->_tpl_vars['LANG_FEATURED']); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['letter_of_featured_word']):
?>
	                         						<div><?php echo $this->_tpl_vars['letter_of_featured_word']; ?>
</div>
	                         					<?php endforeach; endif; unset($_from); ?>
	                         					</td>
	                         				<?php else: ?>
	                         					<td>&nbsp;</td>
	                         				<?php endif; ?>
		                         				<td class="content_field_output">
		                         					<?php if ($this->_tpl_vars['listing']->level->featured): ?><b><?php endif; ?>
		                         					<?php echo ((is_array($_tmp=$this->_tpl_vars['listing']->creation_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%D") : smarty_modifier_date_format($_tmp, "%D")); ?>

		                         					<?php if ($this->_tpl_vars['listing']->level->featured): ?></b><?php endif; ?>
		                         				</td>
	                         					<td align="center" <?php if ($this->_tpl_vars['listing']->level->logo_enabled): ?>width="<?php echo $this->_tpl_vars['listing']->level->explodeSize('logo_size','width'); ?>
px"<?php endif; ?>>
	                         						<?php if ($this->_tpl_vars['listing']->level->logo_enabled): ?>
					                         		<div class="img_div_border" style="margin:2px 0; border-width:2px; width: <?php echo $this->_tpl_vars['listing']->level->explodeSize('logo_size','width'); ?>
px;">
														<div class="img_div" style="width: <?php echo $this->_tpl_vars['listing']->level->explodeSize('logo_size','width'); ?>
px;">
															<table width="<?php echo $this->_tpl_vars['listing']->level->explodeSize('logo_size','width')+2; ?>
px">
																<tr>
																	<td valign="middle" align="center">
																	<?php if ($this->_tpl_vars['listing']->logo_file): ?>
																		<a href="<?php echo $this->_tpl_vars['VH']->site_url("listings/".($this->_tpl_vars['listing_unique_id'])); ?>
"><img src="<?php echo $this->_tpl_vars['users_content']; ?>
/users_images/logos/<?php echo $this->_tpl_vars['listing']->logo_file; ?>
" alt="<?php echo $this->_tpl_vars['listing']->title; ?>
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
												</td>
												<td class="content_field_output">
													<?php if ($this->_tpl_vars['listing']->level->featured): ?><b><?php endif; ?>
		                         					<a href="<?php echo $this->_tpl_vars['VH']->site_url("listings/".($this->_tpl_vars['listing_unique_id'])); ?>
"><?php echo $this->_tpl_vars['listing']->title(); ?>
</a>
		                         					<?php if ($this->_tpl_vars['listing']->level->featured): ?></b><?php endif; ?>
												</td>
												<?php echo $this->_tpl_vars['listing']->outputMode('short'); ?>