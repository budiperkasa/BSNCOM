<?php /* Smarty version 2.6.26, created on 2012-02-06 08:44:14
         compiled from listings/admin_listing_options_menu.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'listings/admin_listing_options_menu.tpl', 31, false),)), $this); ?>
<?php $this->assign('listing_unique_id', $this->_tpl_vars['listing']->getUniqueId()); ?>
<?php $this->assign('listing_id', $this->_tpl_vars['listing']->id); ?>

				<?php if ($this->_tpl_vars['listing']->status == 1): ?>
				<div class="admin_top_menu_cell">
					<a href="<?php echo $this->_tpl_vars['VH']->site_url("listings/".($this->_tpl_vars['listing_unique_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_LISTING_LOOK_FRONTEND']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/house_go.png" /></a>
                    <a href="<?php echo $this->_tpl_vars['VH']->site_url("listings/".($this->_tpl_vars['listing_unique_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_LISTING_LOOK_FRONTEND']; ?>
</a>&nbsp;&nbsp;&nbsp;
				</div>
				<?php endif; ?>
				
				<div class="admin_top_menu_cell">
					<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/create"); ?>
" title="<?php echo $this->_tpl_vars['LANG_CREATE_LISTING_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_add.png" /></a>
                    <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/create"); ?>
"><?php echo $this->_tpl_vars['LANG_CREATE_LISTING_OPTION']; ?>
</a>&nbsp;&nbsp;&nbsp;
				</div>
				
				<div class="admin_top_menu_cell">
                    <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/edit/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_EDIT_LISTING_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_edit.png" /></a>
                    <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/edit/".($this->_tpl_vars['listing_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_EDIT_LISTING_OPTION']; ?>
</a>&nbsp;&nbsp;&nbsp;
				</div>
				
				<div class="admin_top_menu_cell">
                    <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/view/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_VIEW_LISTING_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page.png" /></a>
                    <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/view/".($this->_tpl_vars['listing_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_VIEW_LISTING_OPTION']; ?>
</a>&nbsp;&nbsp;&nbsp;
				</div>
				
				<div class="admin_top_menu_cell">
                    <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/delete/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_DELETE_LISTING_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_delete.png" /></a>
                    <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/delete/".($this->_tpl_vars['listing_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_DELETE_LISTING_OPTION']; ?>
</a>&nbsp;&nbsp;&nbsp;
				</div>
				
				<?php if ($this->_tpl_vars['content_access_obj']->isPermission('Change listing level') && count($this->_tpl_vars['listing']->type->buildLevels()) > 1): ?>
				<div class="admin_top_menu_cell">
					<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/change_level/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_CHANGE_LISTING_LEVEL_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/icons/upgrade.png" /></a>
					<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/change_level/".($this->_tpl_vars['listing_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_CHANGE_LISTING_LEVEL_OPTION']; ?>
</a>&nbsp;&nbsp;&nbsp;
				</div>
				<?php endif; ?>
				
				<?php if ($this->_tpl_vars['listing']->level->images_count > 0): ?>
				<div class="admin_top_menu_cell">
                    <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/images/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_LISTING_IMAGES_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/images.png" /></a>
                    <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/images/".($this->_tpl_vars['listing_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_LISTING_IMAGES_OPTION']; ?>
</a>&nbsp;&nbsp;&nbsp;
				</div>
				<?php endif; ?>

				<?php if ($this->_tpl_vars['listing']->level->video_count > 0): ?>
				<div class="admin_top_menu_cell">
                    <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/videos/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_LISTING_VIDEOS_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/videos.png" /></a>
                    <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/videos/".($this->_tpl_vars['listing_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_LISTING_VIDEOS_OPTION']; ?>
</a>&nbsp;&nbsp;&nbsp;
				</div>
				<?php endif; ?>

				<?php if ($this->_tpl_vars['listing']->level->files_count > 0): ?>
				<div class="admin_top_menu_cell">
                    <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/files/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_LISTING_FILES_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_link.png" /></a>
                    <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/files/".($this->_tpl_vars['listing_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_LISTING_FILES_OPTION']; ?>
</a>&nbsp;&nbsp;&nbsp;
				</div>
				<?php endif; ?>

				<?php if (( $this->_tpl_vars['system_settings']['google_analytics_profile_id'] && $this->_tpl_vars['system_settings']['google_analytics_email'] && $this->_tpl_vars['system_settings']['google_analytics_password'] ) && ( $this->_tpl_vars['content_access_obj']->isPermission('View all statistics') || ( $this->_tpl_vars['content_access_obj']->isPermission('View self statistics') && $this->_tpl_vars['content_access_obj']->checkListingAccess($this->_tpl_vars['listing_id']) ) )): ?>
				<div class="admin_top_menu_cell">
                    <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/statistics/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_LISTING_STATISTICS_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/chart_bar.png" /></a>
                    <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/statistics/".($this->_tpl_vars['listing_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_LISTING_STATISTICS_OPTION']; ?>
</a>&nbsp;&nbsp;&nbsp;
				</div>
				<?php endif; ?>
				
				<?php if ($this->_tpl_vars['listing']->level->ratings_enabled && ( $this->_tpl_vars['content_access_obj']->isPermission('Manage all ratings') || ( $this->_tpl_vars['content_access_obj']->isPermission('Manage self ratings') && $this->_tpl_vars['content_access_obj']->checkListingAccess($this->_tpl_vars['listing_id']) ) )): ?>
				<div class="admin_top_menu_cell">
					<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/ratings/listings/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_LISTING_RATINGS_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/star.png"></a>
					<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/ratings/listings/".($this->_tpl_vars['listing_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_LISTING_RATINGS_OPTION']; ?>
</a>&nbsp;&nbsp;&nbsp;
				</div>
				<?php endif; ?>
				
				<?php if ($this->_tpl_vars['listing']->level->reviews_mode && $this->_tpl_vars['listing']->level->reviews_mode != 'disabled' && ( $this->_tpl_vars['content_access_obj']->isPermission('Manage all reviews') || ( $this->_tpl_vars['content_access_obj']->isPermission('Manage self reviews') && $this->_tpl_vars['content_access_obj']->checkListingAccess($this->_tpl_vars['listing_id']) ) )): ?>
				<div class="admin_top_menu_cell">
					<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/reviews/listings/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_LISTING_REVIEWS_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/comments.png"></a>
					<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/reviews/listings/".($this->_tpl_vars['listing_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_LISTING_REVIEWS_OPTION']; ?>
</a>&nbsp;&nbsp;&nbsp;
				</div>
				<?php endif; ?>

				<div class="clear_float"></div>
				<div class="px10"></div>