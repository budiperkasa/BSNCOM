<?php /* Smarty version 2.6.26, created on 2012-02-06 04:20:14
         compiled from frontend/advertise_with_us.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'frontend/advertise_with_us.tpl', 7, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "frontend/header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

			<tr>
      			<td valign="top">
      				<div id="content_wrapper">
      					<?php if ($this->_tpl_vars['CI']->load->is_module_loaded('packages') && $this->_tpl_vars['system_settings']['packages_listings_creation_mode'] !== 'standalone_mode' && $this->_tpl_vars['content_access_obj']->isPermission('Use packages')): ?>
		                    <?php if (count($this->_tpl_vars['packages'])): ?>
		                    	<div class="px10"></div>
			                    <div class="px10"></div>
		                        <div class="type_line">
		                        	<div class="type_title">
		                        		<?php echo $this->_tpl_vars['LANG_PACKAGES_ADVERTISE_HEADER']; ?>

		                        	</div>
		                        </div>
								<table class="advertiseTable" style="width: 100%" border="0" cellpadding="0" cellspacing="0">
									<tr class="th_header">
										<th><?php echo $this->_tpl_vars['LANG_PACKAGE_NAME']; ?>
</th>
										<th><?php echo $this->_tpl_vars['LANG_PRICE_TH']; ?>
</th>
										<th><?php echo $this->_tpl_vars['LANG_PACKAGE_LISTINGS_COUNT']; ?>
</th>
										<th>&nbsp;</th>
									</tr>
									<?php $_from = $this->_tpl_vars['packages']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['package']):
?>
									<?php if ($this->_tpl_vars['package']->items): ?>
										<?php $this->assign('package_id', $this->_tpl_vars['package']->id); ?>
										<tr>
											<td>
												<?php echo $this->_tpl_vars['package']->name; ?>

											</td>
											<td>
												<?php if ($this->_tpl_vars['packages_prices'][$this->_tpl_vars['package_id']]['value'] == null || $this->_tpl_vars['packages_prices'][$this->_tpl_vars['package_id']]['value'] == 0): ?><span class="free"><?php echo $this->_tpl_vars['LANG_FREE']; ?>
</span><?php else: ?><?php echo $this->_tpl_vars['packages_prices'][$this->_tpl_vars['package_id']]['currency']; ?>
&nbsp;<?php echo $this->_tpl_vars['VH']->number_format($this->_tpl_vars['packages_prices'][$this->_tpl_vars['package_id']]['value'],2,$this->_tpl_vars['decimals_separator'],$this->_tpl_vars['thousands_separator']); ?>
<?php endif; ?>
											</td>
											<td>
												<?php $_from = $this->_tpl_vars['package']->items; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['level_id'] => $this->_tpl_vars['listings_count']):
?>
												<?php if ($this->_tpl_vars['listings_count'] > 0 || $this->_tpl_vars['listings_count'] === 'unlimited'): ?>
													<?php $this->assign('type', $this->_tpl_vars['package']->levels[$this->_tpl_vars['level_id']]->getType()); ?>
													<b><?php if (! $this->_tpl_vars['system_settings']['single_type_structure']): ?><?php echo $this->_tpl_vars['type']->name; ?>
 - <?php endif; ?><i><?php echo $this->_tpl_vars['package']->levels[$this->_tpl_vars['level_id']]->name; ?>
</i>:</b> <?php if ($this->_tpl_vars['listings_count'] !== 'unlimited'): ?><?php echo $this->_tpl_vars['listings_count']; ?>
 <?php echo $this->_tpl_vars['LANG_LISTINGS']; ?>
<?php else: ?><span class="green"><?php echo $this->_tpl_vars['LANG_UNLIMITED']; ?>
</span><?php endif; ?><br />
												<?php endif; ?>
												<?php endforeach; endif; unset($_from); ?>
											</td>
											<td>
												<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/packages/add/".($this->_tpl_vars['package_id'])."/"); ?>
"><?php echo $this->_tpl_vars['LANG_ADD_PACKAGE_LINK']; ?>
</a>
											</td>
										</tr>
										<tr>
											<td class="gap" colspan="16">&nbsp;</td>
										</tr>
									<?php endif; ?>
									<?php endforeach; endif; unset($_from); ?>
								</table>
	                     	<?php endif; ?>
	                     <?php endif; ?>

      					<?php if ($this->_tpl_vars['content_access_obj']->isPermission('Create listings')): ?>
      					<div class="px10"></div>
	                    <div class="px10"></div>
                        <div class="type_line">
                        	<div class="type_title">
                        		<?php echo $this->_tpl_vars['LANG_ADVERTISE_HEADER']; ?>

                        	</div>
                        </div>
                         
						 <?php if (count($this->_tpl_vars['types'])): ?>
                         <table class="advertiseTable" border="0" cellpadding="0" cellspacing="0">
                         	<tr class="th_header">
                         		<?php if (! $this->_tpl_vars['system_settings']['single_type_structure']): ?>
                         		<th>&nbsp;</th>
                         		<?php endif; ?>
                         		<th><?php echo $this->_tpl_vars['LANG_LEVELS_TH']; ?>
</th>
                         		<?php if (! $this->_tpl_vars['CI']->load->is_module_loaded('packages') || $this->_tpl_vars['system_settings']['packages_listings_creation_mode'] !== 'memberships_mode'): ?>
                         		<th><?php echo $this->_tpl_vars['LANG_PRICE_TH']; ?>
</th>
                         		<?php endif; ?>
                         		<th title="<?php echo $this->_tpl_vars['LANG_PERIOD_ALT']; ?>
"><?php echo $this->_tpl_vars['LANG_PERIOD_TH']; ?>
</th>
                         		<th title="<?php echo $this->_tpl_vars['LANG_FEATURED_ALT']; ?>
"><?php echo $this->_tpl_vars['LANG_FEATURED_TH']; ?>
</th>
                         		<th title="<?php echo $this->_tpl_vars['LANG_LOGO_ALT']; ?>
"><?php echo $this->_tpl_vars['LANG_LOGO_TH']; ?>
</th>
                         		<th title="<?php echo $this->_tpl_vars['LANG_LOCATIONS_ALT']; ?>
"><?php echo $this->_tpl_vars['LANG_LOCATIONS_TH']; ?>
</th>
                         		<th title="<?php echo $this->_tpl_vars['LANG_MAP_ALT']; ?>
"><?php echo $this->_tpl_vars['LANG_MAP_TH']; ?>
</th>
                         		<th title="<?php echo $this->_tpl_vars['LANG_CATEGORIES_ALT']; ?>
"><?php echo $this->_tpl_vars['LANG_CATEGORIES_TH']; ?>
</th>
                         		<th title="<?php echo $this->_tpl_vars['LANG_LOCATIONS_ALT']; ?>
"><?php echo $this->_tpl_vars['LANG_LOCATIONS_TH']; ?>
</th>
                         		<th title="<?php echo $this->_tpl_vars['LANG_IMAGES_ALT']; ?>
"><?php echo $this->_tpl_vars['LANG_IMAGES_TH']; ?>
</th>
                         		<th title="<?php echo $this->_tpl_vars['LANG_VIDEOS_ALT']; ?>
"><?php echo $this->_tpl_vars['LANG_VIDEOS_TH']; ?>
</th>
                         		<th title="<?php echo $this->_tpl_vars['LANG_FILES_ALT']; ?>
"><?php echo $this->_tpl_vars['LANG_FILES_TH']; ?>
</th>
                         		<th title="<?php echo $this->_tpl_vars['LANG_RATINGS']; ?>
"><?php echo $this->_tpl_vars['LANG_RATINGS']; ?>
</th>
                         		<th title="<?php echo $this->_tpl_vars['LANG_REVIEWS']; ?>
"><?php echo $this->_tpl_vars['LANG_REVIEWS']; ?>
</th>
                         		<th width="90px">&nbsp;</th>
                         	</tr>
                         	<?php $_from = $this->_tpl_vars['types']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['type']):
?>
                         	<?php if (count($this->_tpl_vars['type']->levels) > 0 && $this->_tpl_vars['type']->isAnyLevelAvailable()): ?>
							<tr>
								<?php if (! $this->_tpl_vars['system_settings']['single_type_structure']): ?>
								<td class="type_name">
									<?php echo $this->_tpl_vars['type']->name; ?>
:
								</td>
								<?php endif; ?>
								<td class="td_header">
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<?php $_from = $this->_tpl_vars['type']->levels; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['level']):
?>
									<?php if ($this->_tpl_vars['content_access_obj']->isContentPermission('levels',$this->_tpl_vars['level']->id)): ?>
										<tr>
											<td class="sub_cell">
												<?php echo $this->_tpl_vars['level']->name; ?>

											</td>
										</tr>
									<?php endif; ?>
									<?php endforeach; endif; unset($_from); ?>
									</table>
								</td>
								<?php if (! $this->_tpl_vars['CI']->load->is_module_loaded('packages') || $this->_tpl_vars['system_settings']['packages_listings_creation_mode'] !== 'memberships_mode'): ?>
								<td>
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<?php $_from = $this->_tpl_vars['type']->levels; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['level']):
?>
									<?php if ($this->_tpl_vars['content_access_obj']->isContentPermission('levels',$this->_tpl_vars['level']->id)): ?>
										<tr>
											<td class="sub_cell">
												<?php if ($this->_tpl_vars['level']->price_value == null || $this->_tpl_vars['level']->price_value == 0): ?><span class="free"><?php echo $this->_tpl_vars['LANG_FREE']; ?>
</span><?php else: ?><?php echo $this->_tpl_vars['level']->price_currency; ?>
&nbsp;<?php echo $this->_tpl_vars['VH']->number_format($this->_tpl_vars['level']->price_value,2,$this->_tpl_vars['decimals_separator'],$this->_tpl_vars['thousands_separator']); ?>
<?php endif; ?>
											</td>
										</tr>
									<?php endif; ?>
									<?php endforeach; endif; unset($_from); ?>
									</table>
								</td>
								<?php endif; ?>
								<td>
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<?php $_from = $this->_tpl_vars['type']->levels; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['level']):
?>
									<?php if ($this->_tpl_vars['content_access_obj']->isContentPermission('levels',$this->_tpl_vars['level']->id)): ?>
										<tr>
											<td class="sub_cell" title="<?php echo $this->_tpl_vars['LANG_PERIOD_TD_ALT']; ?>
">
												<?php if (! $this->_tpl_vars['level']->active_years && ! $this->_tpl_vars['level']->active_months && ! $this->_tpl_vars['level']->active_days): ?>
													<span class="green"><?php echo $this->_tpl_vars['LANG_UNLIMITED']; ?>
</span>
												<?php else: ?>
													<?php if ($this->_tpl_vars['level']->active_years): ?>
													<?php echo $this->_tpl_vars['LANG_YEARS']; ?>
:&nbsp;<b><?php echo $this->_tpl_vars['level']->active_years; ?>
</b><br />
													<?php endif; ?>
													<?php if ($this->_tpl_vars['level']->active_months): ?>
													<?php echo $this->_tpl_vars['LANG_MONTHS']; ?>
:&nbsp;<b><?php echo $this->_tpl_vars['level']->active_months; ?>
</b><br />
													<?php endif; ?>
													<?php if ($this->_tpl_vars['level']->active_days): ?>
													<?php echo $this->_tpl_vars['LANG_DAYS']; ?>
:&nbsp;<b><?php echo $this->_tpl_vars['level']->active_days; ?>
</b>
													<?php endif; ?>
												<?php endif; ?>
											</td>
										</tr>
									<?php endif; ?>
									<?php endforeach; endif; unset($_from); ?>
									</table>
								</td>
								<td>
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<?php $_from = $this->_tpl_vars['type']->levels; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['level']):
?>
									<?php if ($this->_tpl_vars['content_access_obj']->isContentPermission('levels',$this->_tpl_vars['level']->id)): ?>
										<tr>
											<td class="sub_cell">
												<?php if ($this->_tpl_vars['level']->featured): ?><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/accept.png" /><?php else: ?><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/delete.png" /><?php endif; ?>
											</td>
										</tr>
									<?php endif; ?>
									<?php endforeach; endif; unset($_from); ?>
									</table>
								</td>
								<td>
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<?php $_from = $this->_tpl_vars['type']->levels; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['level']):
?>
									<?php if ($this->_tpl_vars['content_access_obj']->isContentPermission('levels',$this->_tpl_vars['level']->id)): ?>
										<tr>
											<td class="sub_cell">
												<?php if ($this->_tpl_vars['level']->logo_enabled): ?><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/accept.png" /><?php else: ?><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/delete.png" /><?php endif; ?>
											</td>
										</tr>
									<?php endif; ?>
									<?php endforeach; endif; unset($_from); ?>
									</table>
								</td>
								<td>
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<?php $_from = $this->_tpl_vars['type']->levels; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['level']):
?>
									<?php if ($this->_tpl_vars['content_access_obj']->isContentPermission('levels',$this->_tpl_vars['level']->id)): ?>
										<tr>
											<td class="sub_cell">
												<?php if ($this->_tpl_vars['type']->locations_enabled): ?><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/accept.png" /><?php else: ?><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/delete.png" /><?php endif; ?>
											</td>
										</tr>
									<?php endif; ?>
									<?php endforeach; endif; unset($_from); ?>
									</table>
								</td>
								<td>
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<?php $_from = $this->_tpl_vars['type']->levels; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['level']):
?>
									<?php if ($this->_tpl_vars['content_access_obj']->isContentPermission('levels',$this->_tpl_vars['level']->id)): ?>
										<tr>
											<td class="sub_cell">
												<?php if ($this->_tpl_vars['level']->maps_enabled): ?><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/accept.png" /><?php else: ?><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/delete.png" /><?php endif; ?>
											</td>
										</tr>
									<?php endif; ?>
									<?php endforeach; endif; unset($_from); ?>
									</table>
								</td>
								<td>
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<?php $_from = $this->_tpl_vars['type']->levels; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['level']):
?>
									<?php if ($this->_tpl_vars['content_access_obj']->isContentPermission('levels',$this->_tpl_vars['level']->id)): ?>
										<tr>
											<td class="sub_cell">
											<?php echo $this->_tpl_vars['level']->categories_number; ?>

											</td>
										</tr>
									<?php endif; ?>
									<?php endforeach; endif; unset($_from); ?>
									</table>
								</td>
								<td>
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<?php $_from = $this->_tpl_vars['type']->levels; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['level']):
?>
									<?php if ($this->_tpl_vars['content_access_obj']->isContentPermission('levels',$this->_tpl_vars['level']->id)): ?>
										<tr>
											<td class="sub_cell">
												<?php if ($this->_tpl_vars['type']->locations_enabled): ?><?php echo $this->_tpl_vars['level']->locations_number; ?>
<?php else: ?><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/delete.png" /><?php endif; ?>
											</td>
										</tr>
									<?php endif; ?>
									<?php endforeach; endif; unset($_from); ?>
									</table>
								</td>
								<td>
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<?php $_from = $this->_tpl_vars['type']->levels; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['level']):
?>
									<?php if ($this->_tpl_vars['content_access_obj']->isContentPermission('levels',$this->_tpl_vars['level']->id)): ?>
										<tr>
											<td class="sub_cell">
											<?php echo $this->_tpl_vars['level']->images_count; ?>

											</td>
										</tr>
									<?php endif; ?>
									<?php endforeach; endif; unset($_from); ?>
									</table>
								</td>
								<td>
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<?php $_from = $this->_tpl_vars['type']->levels; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['level']):
?>
									<?php if ($this->_tpl_vars['content_access_obj']->isContentPermission('levels',$this->_tpl_vars['level']->id)): ?>
										<tr>
											<td class="sub_cell">
											<?php echo $this->_tpl_vars['level']->video_count; ?>

											</td>
										</tr>
									<?php endif; ?>
									<?php endforeach; endif; unset($_from); ?>
									</table>
								</td>
								<td>
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<?php $_from = $this->_tpl_vars['type']->levels; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['level']):
?>
									<?php if ($this->_tpl_vars['content_access_obj']->isContentPermission('levels',$this->_tpl_vars['level']->id)): ?>
										<tr>
											<td class="sub_cell">
											<?php echo $this->_tpl_vars['level']->files_count; ?>

											</td>
										</tr>
									<?php endif; ?>
									<?php endforeach; endif; unset($_from); ?>
									</table>
								</td>
								<td>
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<?php $_from = $this->_tpl_vars['type']->levels; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['level']):
?>
									<?php if ($this->_tpl_vars['content_access_obj']->isContentPermission('levels',$this->_tpl_vars['level']->id)): ?>
										<tr>
											<td class="sub_cell">
											<?php if ($this->_tpl_vars['level']->ratings_enabled): ?><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/accept.png" /><?php else: ?><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/delete.png" /><?php endif; ?>
											</td>
										</tr>
									<?php endif; ?>
									<?php endforeach; endif; unset($_from); ?>
									</table>
								</td>
								<td>
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<?php $_from = $this->_tpl_vars['type']->levels; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['level']):
?>
									<?php if ($this->_tpl_vars['content_access_obj']->isContentPermission('levels',$this->_tpl_vars['level']->id)): ?>
										<tr>
											<td class="sub_cell">
											<?php if ($this->_tpl_vars['level']->reviews_mode != 'disabled'): ?><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/accept.png" /><?php else: ?><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/delete.png" /><?php endif; ?>
											</td>
										</tr>
									<?php endif; ?>
									<?php endforeach; endif; unset($_from); ?>
									</table>
								</td>
								<td>
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
									<?php $_from = $this->_tpl_vars['type']->levels; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['level']):
?>
									<?php if ($this->_tpl_vars['content_access_obj']->isContentPermission('levels',$this->_tpl_vars['level']->id)): ?>
									<?php $this->assign('level_id', $this->_tpl_vars['level']->id); ?>
										<tr>
											<td class="sub_cell">
												<?php if (! $this->_tpl_vars['CI']->load->is_module_loaded('packages') || $this->_tpl_vars['system_settings']['packages_listings_creation_mode'] !== 'memberships_mode'): ?>
												<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/create/level_id/".($this->_tpl_vars['level_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_CREATE_AN_AD']; ?>
</a>
												<?php endif; ?>
											</td>
										</tr>
									<?php endif; ?>
									<?php endforeach; endif; unset($_from); ?>
									</table>
								</td>
							</tr>
							<tr>
								<td class="gap" colspan="16">&nbsp;</td>
							</tr>
							<?php endif; ?>
							<?php endforeach; endif; unset($_from); ?>
						</table>
						<?php else: ?>
							<div class="error_msg rounded_corners">
								<ul>
									<li><?php echo $this->_tpl_vars['LANG_ADVERTISE_EXCLAMATION']; ?>
</li>
								</ul>
							</div>
						<?php endif; ?>
						<?php endif; ?>
                 	</div>
                 </td>
			</tr>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "frontend/footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>