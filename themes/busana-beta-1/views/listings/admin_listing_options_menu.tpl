{assign var=listing_unique_id value=$listing->getUniqueId()}
{assign var=listing_id value=$listing->id}

				{if $listing->status == 1}
				<div class="admin_top_menu_cell">
					<a href="{$VH->site_url("listings/$listing_unique_id")}" title="{$LANG_LISTING_LOOK_FRONTEND}"><img src="{$public_path}/images/buttons/house_go.png" /></a>
                    <a href="{$VH->site_url("listings/$listing_unique_id")}">{$LANG_LISTING_LOOK_FRONTEND}</a>&nbsp;&nbsp;&nbsp;
				</div>
				{/if}
				
				<div class="admin_top_menu_cell">
					<a href="{$VH->site_url("admin/listings/create")}" title="{$LANG_CREATE_LISTING_OPTION}"><img src="{$public_path}/images/buttons/page_add.png" /></a>
                    <a href="{$VH->site_url("admin/listings/create")}">{$LANG_CREATE_LISTING_OPTION}</a>&nbsp;&nbsp;&nbsp;
				</div>
				
				<div class="admin_top_menu_cell">
                    <a href="{$VH->site_url("admin/listings/edit/$listing_id")}" title="{$LANG_EDIT_LISTING_OPTION}"><img src="{$public_path}/images/buttons/page_edit.png" /></a>
                    <a href="{$VH->site_url("admin/listings/edit/$listing_id")}">{$LANG_EDIT_LISTING_OPTION}</a>&nbsp;&nbsp;&nbsp;
				</div>
				
				<div class="admin_top_menu_cell">
                    <a href="{$VH->site_url("admin/listings/view/$listing_id")}" title="{$LANG_VIEW_LISTING_OPTION}"><img src="{$public_path}/images/buttons/page.png" /></a>
                    <a href="{$VH->site_url("admin/listings/view/$listing_id")}">{$LANG_VIEW_LISTING_OPTION}</a>&nbsp;&nbsp;&nbsp;
				</div>
				
				<div class="admin_top_menu_cell">
                    <a href="{$VH->site_url("admin/listings/delete/$listing_id")}" title="{$LANG_DELETE_LISTING_OPTION}"><img src="{$public_path}/images/buttons/page_delete.png" /></a>
                    <a href="{$VH->site_url("admin/listings/delete/$listing_id")}">{$LANG_DELETE_LISTING_OPTION}</a>&nbsp;&nbsp;&nbsp;
				</div>
				
				{if $content_access_obj->isPermission('Change listing level') && $listing->type->buildLevels()|@count > 1}
				<div class="admin_top_menu_cell">
					<a href="{$VH->site_url("admin/listings/change_level/$listing_id")}" title="{$LANG_CHANGE_LISTING_LEVEL_OPTION}"><img src="{$public_path}/images/icons/upgrade.png" /></a>
					<a href="{$VH->site_url("admin/listings/change_level/$listing_id")}">{$LANG_CHANGE_LISTING_LEVEL_OPTION}</a>&nbsp;&nbsp;&nbsp;
				</div>
				{/if}
				
				{if $listing->level->images_count > 0}
				<div class="admin_top_menu_cell">
                    <a href="{$VH->site_url("admin/listings/images/$listing_id")}" title="{$LANG_LISTING_IMAGES_OPTION}"><img src="{$public_path}/images/buttons/images.png" /></a>
                    <a href="{$VH->site_url("admin/listings/images/$listing_id")}">{$LANG_LISTING_IMAGES_OPTION}</a>&nbsp;&nbsp;&nbsp;
				</div>
				{/if}

				{if $listing->level->video_count > 0}
				<div class="admin_top_menu_cell">
                    <a href="{$VH->site_url("admin/listings/videos/$listing_id")}" title="{$LANG_LISTING_VIDEOS_OPTION}"><img src="{$public_path}/images/buttons/videos.png" /></a>
                    <a href="{$VH->site_url("admin/listings/videos/$listing_id")}">{$LANG_LISTING_VIDEOS_OPTION}</a>&nbsp;&nbsp;&nbsp;
				</div>
				{/if}

				{if $listing->level->files_count > 0}
				<div class="admin_top_menu_cell">
                    <a href="{$VH->site_url("admin/listings/files/$listing_id")}" title="{$LANG_LISTING_FILES_OPTION}"><img src="{$public_path}/images/buttons/page_link.png" /></a>
                    <a href="{$VH->site_url("admin/listings/files/$listing_id")}">{$LANG_LISTING_FILES_OPTION}</a>&nbsp;&nbsp;&nbsp;
				</div>
				{/if}

				{if ($system_settings.google_analytics_profile_id && $system_settings.google_analytics_email && $system_settings.google_analytics_password) && ($content_access_obj->isPermission('View all statistics') || ($content_access_obj->isPermission('View self statistics') && $content_access_obj->checkListingAccess($listing_id)))}
				<div class="admin_top_menu_cell">
                    <a href="{$VH->site_url("admin/listings/statistics/$listing_id")}" title="{$LANG_LISTING_STATISTICS_OPTION}"><img src="{$public_path}/images/buttons/chart_bar.png" /></a>
                    <a href="{$VH->site_url("admin/listings/statistics/$listing_id")}">{$LANG_LISTING_STATISTICS_OPTION}</a>&nbsp;&nbsp;&nbsp;
				</div>
				{/if}
				
				{if $listing->level->ratings_enabled && ($content_access_obj->isPermission('Manage all ratings') || ($content_access_obj->isPermission('Manage self ratings') && $content_access_obj->checkListingAccess($listing_id)))}
				<div class="admin_top_menu_cell">
					<a href="{$VH->site_url("admin/ratings/listings/$listing_id")}" title="{$LANG_LISTING_RATINGS_OPTION}"><img src="{$public_path}images/icons/star.png"></a>
					<a href="{$VH->site_url("admin/ratings/listings/$listing_id")}">{$LANG_LISTING_RATINGS_OPTION}</a>&nbsp;&nbsp;&nbsp;
				</div>
				{/if}
				
				{if $listing->level->reviews_mode && $listing->level->reviews_mode != 'disabled' && ($content_access_obj->isPermission('Manage all reviews') || ($content_access_obj->isPermission('Manage self reviews') && $content_access_obj->checkListingAccess($listing_id)))}
				<div class="admin_top_menu_cell">
					<a href="{$VH->site_url("admin/reviews/listings/$listing_id")}" title="{$LANG_LISTING_REVIEWS_OPTION}"><img src="{$public_path}images/icons/comments.png"></a>
					<a href="{$VH->site_url("admin/reviews/listings/$listing_id")}">{$LANG_LISTING_REVIEWS_OPTION}</a>&nbsp;&nbsp;&nbsp;
				</div>
				{/if}

				<div class="clear_float"></div>
				<div class="px10"></div>