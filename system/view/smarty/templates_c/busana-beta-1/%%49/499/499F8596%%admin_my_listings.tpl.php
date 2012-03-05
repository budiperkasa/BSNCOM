<?php /* Smarty version 2.6.26, created on 2012-02-06 04:22:25
         compiled from listings/admin_my_listings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'listings/admin_my_listings.tpl', 24, false),array('modifier', 'date_format', 'listings/admin_my_listings.tpl', 85, false),array('function', 'asc_desc_insert', 'listings/admin_my_listings.tpl', 30, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

				<script language="javascript" type="text/javascript">
				// Command variable, needs for delete listings button
				var action_cmd;

                function submit_listings_form()
                {
                	$("#listings_form").attr("action", '<?php echo $this->_tpl_vars['VH']->site_url('admin/listings/'); ?>
' + action_cmd + '/');
                	return true;
                }
                </script>
                <div class="content">
                     <h3><?php echo $this->_tpl_vars['LANG_MANAGE_LISTINGS_1']; ?>
 (<?php echo $this->_tpl_vars['listings_count']; ?>
 <?php echo $this->_tpl_vars['LANG_MANAGE_LISTINGS_2']; ?>
)</h3>
                     
                    <?php if ($this->_tpl_vars['content_access_obj']->isPermission('Create listings')): ?>
                    <div class="admin_top_menu_cell">
	                    <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/create"); ?>
" title="<?php echo $this->_tpl_vars['LANG_CREATE_LISTING_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_add.png" /></a>
	                    <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/create"); ?>
"><?php echo $this->_tpl_vars['LANG_CREATE_LISTING_OPTION']; ?>
</a>&nbsp;&nbsp;&nbsp;
					</div>
					<div class="clear_float"></div>
                    <?php endif; ?>

                     <?php if (count($this->_tpl_vars['listings']) > 0): ?>
                     <form id="listings_form" action="" method="post" onSubmit="submit_listings_form();">
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th width="1"><input type="checkbox"></th>
                         <!--<th><?php echo $this->_tpl_vars['LANG_LOGO_TH']; ?>
</th>-->
                         <th><?php echo smarty_function_asc_desc_insert(array('base_url' => $this->_tpl_vars['search_url'],'orderby' => 'title','orderby_query' => $this->_tpl_vars['orderby'],'direction' => $this->_tpl_vars['direction'],'title' => $this->_tpl_vars['LANG_TITLE_TH']), $this);?>
</th>
                         <?php if ($this->_tpl_vars['CI']->load->is_module_loaded('packages') && $this->_tpl_vars['system_settings']['packages_listings_creation_mode'] !== 'standalone_mode'): ?>
                         <th><?php echo $this->_tpl_vars['LANG_PACKAGE_TH']; ?>
</th>
                         <?php endif; ?>
                         <?php if (! $this->_tpl_vars['system_settings']['single_type_structure']): ?>
                         <th><?php echo $this->_tpl_vars['LANG_TYPE_TH']; ?>
</th>
                         <?php endif; ?>
                         <th><?php echo $this->_tpl_vars['LANG_LEVEL_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_STATUS_TH']; ?>
</th>
                         <th><?php echo smarty_function_asc_desc_insert(array('base_url' => $this->_tpl_vars['search_url'],'orderby' => 'creation_date','orderby_query' => $this->_tpl_vars['orderby'],'direction' => $this->_tpl_vars['direction'],'title' => $this->_tpl_vars['LANG_CREATION_DATE_TH']), $this);?>
</th>
                         <th><?php echo smarty_function_asc_desc_insert(array('base_url' => $this->_tpl_vars['search_url'],'orderby' => 'expiration_date','orderby_query' => $this->_tpl_vars['orderby'],'direction' => $this->_tpl_vars['direction'],'title' => $this->_tpl_vars['LANG_EXPIRATION_DATE_TH']), $this);?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_OPTIONS_TH']; ?>
</th>
                       </tr>
                       <?php $_from = $this->_tpl_vars['listings']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['listing']):
?>
                       <?php $this->assign('listing_id', $this->_tpl_vars['listing']->id); ?>
					   <?php $this->assign('listing_owner_id', $this->_tpl_vars['listing']->owner_id); ?>
                       <tr>
                         <td>
                    	  	<input type="checkbox" name="cb_<?php echo $this->_tpl_vars['listing']->id; ?>
" value="<?php echo $this->_tpl_vars['listing']->id; ?>
">
                    	 </td>
                         <!--<td>
                         <?php if ($this->_tpl_vars['listing']->level->logo_enabled): ?>
                         	 <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/edit/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_EDIT_LISTING_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['users_content']; ?>
/users_images/logos/<?php echo $this->_tpl_vars['listing']->logo_file; ?>
" /></a>&nbsp;
                         <?php endif; ?>
                         </td>-->
                         <td>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/view/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_EDIT_LISTING_OPTION']; ?>
"><?php echo $this->_tpl_vars['listing']->title(); ?>
</a>&nbsp;
                         </td>
                         <?php if ($this->_tpl_vars['CI']->load->is_module_loaded('packages') && $this->_tpl_vars['system_settings']['packages_listings_creation_mode'] !== 'standalone_mode'): ?>
                         <td>
                    	  	<?php if ($this->_tpl_vars['listing']->package): ?><?php echo $this->_tpl_vars['listing']->package->name; ?>
<?php else: ?>(<?php echo $this->_tpl_vars['LANG_PACKAGE_STANDALONE']; ?>
)<?php endif; ?>
                    	 </td>
                    	 <?php endif; ?>
                         <?php if (! $this->_tpl_vars['system_settings']['single_type_structure']): ?>
                         <td>
                             <?php echo $this->_tpl_vars['listing']->type->name; ?>
&nbsp;
                         </td>
                         <?php endif; ?>
                         <td>
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
                            &nbsp;
                         </td>
                         <td>
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
                         <td>
                             <?php echo ((is_array($_tmp=$this->_tpl_vars['listing']->creation_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%D %T") : smarty_modifier_date_format($_tmp, "%D %T")); ?>
&nbsp;
                         </td>
                         <td>
                         	<?php if (! $this->_tpl_vars['listing']->level->active_years && ! $this->_tpl_vars['listing']->level->active_months && ! $this->_tpl_vars['listing']->level->active_days && ! $this->_tpl_vars['listing']->level->allow_to_edit_active_period): ?>
                         		<span class="green"><?php echo $this->_tpl_vars['LANG_ETERNAL']; ?>
</span>
                         	<?php else: ?>
                         		<?php echo ((is_array($_tmp=$this->_tpl_vars['listing']->expiration_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%D %T") : smarty_modifier_date_format($_tmp, "%D %T")); ?>
&nbsp;
                         	<?php endif; ?>
                         </td>
                         <td>
                         	<nobr>
                         	 <?php if ($this->_tpl_vars['listing']->status == 1): ?>
                         	 <?php $this->assign('listing_unique_id', $this->_tpl_vars['listing']->getUniqueId()); ?>
                         	 <a href="<?php echo $this->_tpl_vars['VH']->site_url("listings/".($this->_tpl_vars['listing_unique_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_LISTING_LOOK_FRONTEND']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/house_go.png" /></a>
                         	 <?php endif; ?>
                         	 <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/view/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_VIEW_LISTING_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/page.png"></a>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/edit/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_EDIT_LISTING_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/page_edit.png"></a>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/delete/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_DELETE_LISTING_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/page_delete.png"></a>
                             <?php if ($this->_tpl_vars['listing']->level->images_count > 0): ?>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/images/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_LISTING_IMAGES_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/images.png"></a>
                             <?php endif; ?>
                             <?php if ($this->_tpl_vars['listing']->level->video_count > 0): ?>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/videos/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_LISTING_VIDEOS_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/videos.png"></a>
                             <?php endif; ?>
                             <?php if ($this->_tpl_vars['listing']->level->files_count > 0): ?>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/files/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_LISTING_FILES_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/page_link.png"></a>
                             <?php endif; ?>
                             <?php if (( $this->_tpl_vars['system_settings']['google_analytics_profile_id'] && $this->_tpl_vars['system_settings']['google_analytics_email'] && $this->_tpl_vars['system_settings']['google_analytics_password'] ) && ( $this->_tpl_vars['content_access_obj']->isPermission('View all statistics') || ( $this->_tpl_vars['content_access_obj']->isPermission('View self statistics') && $this->_tpl_vars['content_access_obj']->checkListingAccess($this->_tpl_vars['listing_id']) ) )): ?>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/statistics/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_LISTING_STATISTICS_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/chart_bar.png"></a>
                             <?php endif; ?>
                             <?php if ($this->_tpl_vars['listing']->level->ratings_enabled && ( $this->_tpl_vars['content_access_obj']->isPermission('Manage all ratings') || ( $this->_tpl_vars['content_access_obj']->isPermission('Manage self ratings') && $this->_tpl_vars['content_access_obj']->checkListingAccess($this->_tpl_vars['listing_id']) ) )): ?>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/ratings/listings/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_LISTING_RATINGS_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/star.png"></a>
                             <?php endif; ?>
                             <?php if ($this->_tpl_vars['listing']->level->reviews_mode && $this->_tpl_vars['listing']->level->reviews_mode != 'disabled' && ( $this->_tpl_vars['content_access_obj']->isPermission('Manage all reviews') || ( $this->_tpl_vars['content_access_obj']->isPermission('Manage self reviews') && $this->_tpl_vars['content_access_obj']->checkListingAccess($this->_tpl_vars['listing_id']) ) )): ?>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/reviews/listings/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_LISTING_REVIEWS_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/comments.png"></a>
                             <?php endif; ?>
                             </nobr>
                         </td>
                       </tr>
                       <?php endforeach; endif; unset($_from); ?>
                     </table>
                     <?php echo $this->_tpl_vars['LANG_WITH_SELECTED']; ?>
:
	                 <select name="table_action" onchange="action_cmd=this.options[this.selectedIndex].value; submit_listings_form(); this.form.submit()">
	                 	<option value=""><?php echo $this->_tpl_vars['LANG_CHOOSE_ACTION']; ?>
</option>
	                 	<option value="delete"><?php echo $this->_tpl_vars['LANG_BUTTON_DELETE_LISTINGS']; ?>
</option>
	                 </select>
                     </form>
                     
                     <?php echo $this->_tpl_vars['paginator']; ?>

                     <?php endif; ?>
                </div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>