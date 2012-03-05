<?php /* Smarty version 2.6.26, created on 2012-02-06 08:44:14
         compiled from listings/admin_view_listing.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'listings/admin_view_listing.tpl', 42, false),array('modifier', 'date_format', 'listings/admin_view_listing.tpl', 51, false),array('modifier', 'nl2br', 'listings/admin_view_listing.tpl', 96, false),array('function', 'render_frontend_block', 'listings/admin_view_listing.tpl', 162, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $this->assign('listing_id', $this->_tpl_vars['listing']->id); ?>
<?php $this->assign('listing_owner_id', $this->_tpl_vars['listing']->owner_id); ?>

                <div class="content">
                    <h3><?php echo $this->_tpl_vars['LANG_VIEW_LISTING']; ?>
 "<?php echo $this->_tpl_vars['listing']->title(); ?>
"</h3>
                    
                    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "listings/admin_listing_options_menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

                    <label class="block_title"><?php echo $this->_tpl_vars['LANG_LISTING_INFO']; ?>
</label>
                    <div class="admin_option">
						<table>
							<tr>
								<td class="table_left_side"><?php echo $this->_tpl_vars['LANG_STATUS_TH']; ?>
:</td>
								<td class="table_right_side">
									<?php if ($this->_tpl_vars['listing']->status == 1): ?><a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/change_status/".($this->_tpl_vars['listing_id'])); ?>
" class="status_active"><?php echo $this->_tpl_vars['LANG_STATUS_ACTIVE']; ?>
</a><?php endif; ?>
	    	                     	<?php if ($this->_tpl_vars['listing']->status == 2): ?><a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/change_status/".($this->_tpl_vars['listing_id'])); ?>
" class="status_blocked"><?php echo $this->_tpl_vars['LANG_STATUS_BLOCKED']; ?>
</a><?php endif; ?>
	        	                 	<?php if ($this->_tpl_vars['listing']->status == 3): ?><?php if ($this->_tpl_vars['content_access_obj']->isPermission('Manage all listings')): ?><a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/change_status/".($this->_tpl_vars['listing_id'])); ?>
" class="status_suspended"><?php echo $this->_tpl_vars['LANG_STATUS_SUSPENDED']; ?>
</a><?php else: ?><span class="status_suspended"><?php echo $this->_tpl_vars['LANG_STATUS_SUSPENDED']; ?>
</span><?php endif; ?>&nbsp;<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/prolong/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_PROLONG_ACTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/date_add.png"></a><?php endif; ?>
	            	             	<?php if ($this->_tpl_vars['listing']->status == 4): ?><?php if ($this->_tpl_vars['content_access_obj']->isPermission('Manage all listings')): ?><a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/change_status/".($this->_tpl_vars['listing_id'])); ?>
" class="status_unapproved"><?php echo $this->_tpl_vars['LANG_STATUS_UNAPPROVED']; ?>
</a><?php else: ?><span class="status_unapproved"><?php echo $this->_tpl_vars['LANG_STATUS_UNAPPROVED']; ?>
</span><?php endif; ?><?php endif; ?>
	                	         	<?php if ($this->_tpl_vars['listing']->status == 5): ?><?php if ($this->_tpl_vars['content_access_obj']->isPermission('Manage all listings')): ?><a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/change_status/".($this->_tpl_vars['listing_id'])); ?>
" class="status_notpaid"><?php echo $this->_tpl_vars['LANG_STATUS_NOTPAID']; ?>
</a><?php else: ?><span class="status_notpaid"><?php echo $this->_tpl_vars['LANG_STATUS_NOTPAID']; ?>
</span><?php endif; ?><?php if ($this->_tpl_vars['content_access_obj']->isPermission('View self invoices')): ?>&nbsp;<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/payment/invoices/my/"); ?>
" title="<?php echo $this->_tpl_vars['LANG_VIEW_MY_INVOICES_MENU']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/money_add.png"></a><?php endif; ?><?php endif; ?>
                         			&nbsp;
								</td>
							</tr>
							<tr>
								<td class="table_left_side"><?php echo $this->_tpl_vars['LANG_OWNER_TH']; ?>
:</td>
								<td class="table_right_side">
									<?php if ($this->_tpl_vars['listing']->owner_id != 1 && $this->_tpl_vars['listing']->owner_id != $this->_tpl_vars['session_user_id'] && $this->_tpl_vars['content_access_obj']->isPermission('View all users')): ?>
		                         		<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/view/".($this->_tpl_vars['listing_owner_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_VIEW_USER_OPTION']; ?>
"><?php echo $this->_tpl_vars['listing']->owner_login(); ?>
</a>
		                         	<?php else: ?>
		                         		<?php echo $this->_tpl_vars['listing']->user->login; ?>

		                         	<?php endif; ?>
		                         	&nbsp;
		                         </td>
							</tr>
							<tr>
								<td class="table_left_side"><?php echo $this->_tpl_vars['LANG_TYPE_TH']; ?>
:</td>
								<td class="table_right_side"><?php echo $this->_tpl_vars['listing']->type->name; ?>
</td>
							</tr>
							<tr>
								<td class="table_left_side"><?php echo $this->_tpl_vars['LANG_LEVEL_TH']; ?>
:</td>
								<td class="table_right_side">
									<?php if ($this->_tpl_vars['content_access_obj']->isPermission('Change listing level') && count($this->_tpl_vars['listing']->type->buildLevels()) > 1): ?>
			                        	<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/change_level/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_CHANGE_LISTING_LEVEL_OPTION']; ?>
"><?php echo $this->_tpl_vars['listing']->level->name; ?>
</a> <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/change_level/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_CHANGE_LISTING_LEVEL_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/icons/upgrade.png" /></a>
									<?php else: ?>
										<?php echo $this->_tpl_vars['listing']->level->name; ?>

									<?php endif; ?>
								</td>
							</tr>
							<tr>
								<td class="table_left_side"><?php echo $this->_tpl_vars['LANG_CREATION_DATE_TH']; ?>
:</td>
								<td class="table_right_side"><?php echo ((is_array($_tmp=$this->_tpl_vars['listing']->creation_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%D %T") : smarty_modifier_date_format($_tmp, "%D %T")); ?>
</td>
							</tr>
							<tr>
								<td class="table_left_side"><?php echo $this->_tpl_vars['LANG_EXPIRATION_DATE_TH']; ?>
:</td>
								<td class="table_right_side">
									<?php if ($this->_tpl_vars['listing']->level->eternal_active_period && ! $this->_tpl_vars['listing']->level->allow_to_edit_active_period): ?>
		                         		<span class="green"><?php echo $this->_tpl_vars['LANG_ETERNAL']; ?>
</span>
		                         	<?php else: ?>
		                         		<?php echo ((is_array($_tmp=$this->_tpl_vars['listing']->expiration_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%D %T") : smarty_modifier_date_format($_tmp, "%D %T")); ?>

		                         	<?php endif; ?>
								</td>
							</tr>
							<tr>
								<td class="table_left_side"><?php echo $this->_tpl_vars['LANG_LAST_MODIFIED_DATE_TH']; ?>
:</td>
								<td class="table_right_side"><?php echo ((is_array($_tmp=$this->_tpl_vars['listing']->last_modified_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%D %T") : smarty_modifier_date_format($_tmp, "%D %T")); ?>
</td>
							</tr>
							<tr>
								<td class="table_left_side"><?php echo $this->_tpl_vars['LANG_LISTING_PROLONGED_TH']; ?>
:</td>
								<td class="table_right_side"><?php echo $this->_tpl_vars['listing']->was_prolonged_times; ?>
</td>
							</tr>
						</table>
					</div>

					<label class="block_title"><?php echo $this->_tpl_vars['LANG_LISTING_SUMMARY']; ?>
</label>
					<div class="admin_option">
						<table>
							<?php if ($this->_tpl_vars['listing']->level->title_enabled): ?>
							<tr>
								<td class="table_left_side"><?php echo $this->_tpl_vars['LANG_TITLE_TD']; ?>
:</td>
								<td class="table_right_side"><?php echo $this->_tpl_vars['listing']->title(); ?>
</td>
							</tr>
							<?php endif; ?>
							<?php if ($this->_tpl_vars['listing']->level->seo_title_enabled): ?>
							<tr>
								<td class="table_left_side"><?php echo $this->_tpl_vars['LANG_SEO_TITLE_TD']; ?>
:</td>
								<td class="table_right_side"><?php echo $this->_tpl_vars['listing']->seo_title; ?>
</td>
							</tr>
							<?php endif; ?>
							<?php if ($this->_tpl_vars['listing']->level->description_mode != 'disabled'): ?>
							<tr>
								<td class="table_left_side"><?php echo $this->_tpl_vars['LANG_SHORT_DESCRIPTION']; ?>
:</td>
								<td class="table_right_side">
									<?php if ($this->_tpl_vars['listing']->level->description_mode == 'richtext'): ?>
									<?php echo $this->_tpl_vars['listing']->listing_description; ?>

									<?php else: ?>
									<?php echo ((is_array($_tmp=$this->_tpl_vars['listing']->listing_description)) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

									<?php endif; ?>
								</td>
							</tr>
							<?php endif; ?>
							<?php if ($this->_tpl_vars['listing']->level->meta_enabled): ?>
							<tr>
								<td class="table_left_side"><?php echo $this->_tpl_vars['LANG_LISTING_META_DESCRIPTION']; ?>
:</td>
								<td class="table_right_side"><?php echo ((is_array($_tmp=$this->_tpl_vars['listing']->listing_meta_description)) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
							</tr>
							<tr>
								<td class="table_left_side"><?php echo $this->_tpl_vars['LANG_LISTING_KEYWORDS']; ?>
:</td>
								<td class="table_right_side"><?php echo ((is_array($_tmp=$this->_tpl_vars['listing']->listing_keywords)) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</td>
							</tr>
							<?php endif; ?>
							<?php if ($this->_tpl_vars['listing']->level->logo_enabled && $this->_tpl_vars['listing']->logo_file): ?>
							<tr>
								<td class="table_left_side"><?php echo $this->_tpl_vars['LANG_LOGO_TH']; ?>
:</td>
								<td class="table_right_side">
									<div id="img_wrapper">
										<div id="img_div_border" style="width: <?php echo $this->_tpl_vars['listing']->level->explodeSize('logo_size','width'); ?>
px; height: <?php echo $this->_tpl_vars['listing']->level->explodeSize('logo_size','height'); ?>
px;">
											<div id="img_div" style="width: <?php echo $this->_tpl_vars['listing']->level->explodeSize('logo_size','width'); ?>
px; height: <?php echo $this->_tpl_vars['listing']->level->explodeSize('logo_size','height'); ?>
px;">
												<table width="<?php echo $this->_tpl_vars['listing']->level->explodeSize('logo_size','width')+4; ?>
px" height="<?php echo $this->_tpl_vars['listing']->level->explodeSize('logo_size','height')+4; ?>
px">
													<tr>
														<td valign="middle" align="center">
															<img id="img" src="<?php echo $this->_tpl_vars['users_content']; ?>
/users_images/logos/<?php echo $this->_tpl_vars['listing']->logo_file; ?>
">
														</td>
													</tr>
												</table>
											</div>
										</div>
									</div>
								</td>
							</tr>
							<?php endif; ?>
						</table>
					</div>

					<?php if ($this->_tpl_vars['listing']->type->categories_type != 'disabled' && $this->_tpl_vars['listing']->level->categories_number): ?>
					<label class="block_title"><?php echo $this->_tpl_vars['LANG_LISTING_IN_CATEGORIES']; ?>
</label>
					<div class="admin_option">
						<ul>
						<?php $_from = $this->_tpl_vars['listing']->categories_array(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['category']):
?>
                        	<div id="category_<?php echo $this->_tpl_vars['category']->id; ?>
" class="categories_item" unselectable="on">
                        		<li><?php echo $this->_tpl_vars['category']->getChainAsString(); ?>
</li>
                        	</div>
                        <?php endforeach; endif; unset($_from); ?>
						</ul>
					</div>
					<?php endif; ?>

					<?php if ($this->_tpl_vars['listing']->type->locations_enabled && $this->_tpl_vars['listing']->locations_count()): ?>
					<label class="block_title"><?php echo $this->_tpl_vars['LANG_LISTING_LOCATIONS']; ?>
</label>
					<div class="admin_option">
						<table>
							<?php $this->assign('i', 1); ?>
							<?php $_from = $this->_tpl_vars['listing']->locations_array(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['location']):
?>
							<tr>
								<td class="table_left_side"><?php echo $this->_tpl_vars['LANG_LISTING_ADDRESS']; ?>
 <?php echo $this->_tpl_vars['i']++; ?>
</td>
								<td class="table_right_side"><?php echo $this->_tpl_vars['location']->compileAddress(); ?>
</td>
							</tr>
							<?php endforeach; endif; unset($_from); ?>
							<?php if ($this->_tpl_vars['listing']->level->maps_enabled && $this->_tpl_vars['listing']->locations_count(true)): ?>
							<tr>
								<td class="table_left_side"><?php echo $this->_tpl_vars['LANG_MAP']; ?>
:</td>
								<td class="table_right_side">
									<?php echo smarty_function_render_frontend_block(array('block_type' => 'map_and_markers','block_template' => 'frontend/blocks/map_standart.tpl','existed_listings' => $this->_tpl_vars['listing']->id,'map_width' => $this->_tpl_vars['listing']->level->explodeSize('maps_size','width'),'map_height' => $this->_tpl_vars['listing']->level->explodeSize('maps_size','height'),'show_anchors' => false,'show_links' => false), $this);?>

								</td>
							</tr>
							<?php endif; ?>
						</table>
					</div>
					<?php endif; ?>

					<?php if ($this->_tpl_vars['listing']->content_fields->fieldsCount() && $this->_tpl_vars['listing']->content_fields->isAnyValue()): ?>
						<label class="block_title"><?php echo $this->_tpl_vars['LANG_LISTING_ADDITIONAL_INFORMATION']; ?>
</label>
						<div class="admin_option">
		                   	<?php echo $this->_tpl_vars['listing']->content_fields->outputMode(); ?>

						</div>
                    <?php endif; ?>
                </div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>