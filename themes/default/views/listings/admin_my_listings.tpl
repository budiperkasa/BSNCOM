{include file="backend/admin_header.tpl"}

				<script language="javascript" type="text/javascript">
				// Command variable, needs for delete listings button
				var action_cmd;

                function submit_listings_form()
                {ldelim}
                	$("#listings_form").attr("action", '{$VH->site_url('admin/listings/')}' + action_cmd + '/');
                	return true;
                {rdelim}
                </script>
                <div class="content">
                     <h3>{$LANG_MANAGE_LISTINGS_1} ({$listings_count} {$LANG_MANAGE_LISTINGS_2})</h3>
                     
                    {if $content_access_obj->isPermission('Create listings')}
                    <div class="admin_top_menu_cell">
	                    <a href="{$VH->site_url("admin/listings/create")}" title="{$LANG_CREATE_LISTING_OPTION}"><img src="{$public_path}/images/buttons/page_add.png" /></a>
	                    <a href="{$VH->site_url("admin/listings/create")}">{$LANG_CREATE_LISTING_OPTION}</a>&nbsp;&nbsp;&nbsp;
					</div>
					<div class="clear_float"></div>
                    {/if}

                     {if $listings|@count > 0}
                     <form id="listings_form" action="" method="post" onSubmit="submit_listings_form();">
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th width="1"><input type="checkbox"></th>
                         <!--<th>{$LANG_LOGO_TH}</th>-->
                         <th>{asc_desc_insert base_url=$search_url orderby='title' orderby_query=$orderby direction=$direction title=$LANG_TITLE_TH}</th>
                         {if $CI->load->is_module_loaded('packages') && $system_settings.packages_listings_creation_mode !== 'standalone_mode'}
                         <th>{$LANG_PACKAGE_TH}</th>
                         {/if}
                         {if !$system_settings.single_type_structure}
                         <th>{$LANG_TYPE_TH}</th>
                         {/if}
                         <th>{$LANG_LEVEL_TH}</th>
                         <th>{$LANG_STATUS_TH}</th>
                         <th>{asc_desc_insert base_url=$search_url orderby='creation_date' orderby_query=$orderby direction=$direction title=$LANG_CREATION_DATE_TH}</th>
                         <th>{asc_desc_insert base_url=$search_url orderby='expiration_date' orderby_query=$orderby direction=$direction title=$LANG_EXPIRATION_DATE_TH}</th>
                         <th>{$LANG_OPTIONS_TH}</th>
                       </tr>
                       {foreach from=$listings item=listing}
                       {assign var="listing_id" value=$listing->id}
					   {assign var="listing_owner_id" value=$listing->owner_id}
                       <tr>
                         <td>
                    	  	<input type="checkbox" name="cb_{$listing->id}" value="{$listing->id}">
                    	 </td>
                         <!--<td>
                         {if $listing->level->logo_enabled}
                         	 <a href="{$VH->site_url("admin/listings/edit/$listing_id")}" title="{$LANG_EDIT_LISTING_OPTION}"><img src="{$users_content}/users_images/logos/{$listing->logo_file}" /></a>&nbsp;
                         {/if}
                         </td>-->
                         <td>
                             <a href="{$VH->site_url("admin/listings/view/$listing_id")}" title="{$LANG_EDIT_LISTING_OPTION}">{$listing->title()}</a>&nbsp;
                         </td>
                         {if $CI->load->is_module_loaded('packages') && $system_settings.packages_listings_creation_mode !== 'standalone_mode'}
                         <td>
                    	  	{if $listing->package}{$listing->package->name}{else}({$LANG_PACKAGE_STANDALONE}){/if}
                    	 </td>
                    	 {/if}
                         {if !$system_settings.single_type_structure}
                         <td>
                             {$listing->type->name}&nbsp;
                         </td>
                         {/if}
                         <td>
                         	{if $content_access_obj->isPermission('Change listing level') && $listing->type->buildLevels()|@count > 1}
								<a href="{$VH->site_url("admin/listings/change_level/$listing_id")}" title="{$LANG_CHANGE_LISTING_LEVEL_OPTION}">{$listing->level->name}</a> <a href="{$VH->site_url("admin/listings/change_level/$listing_id")}" title="{$LANG_CHANGE_LISTING_LEVEL_OPTION}"><img src="{$public_path}/images/icons/upgrade.png" /></a>
							{else}
								{$listing->level->name}
							{/if}
                            &nbsp;
                         </td>
                         <td>
                         	{if $listing->status == 1}<a href="{$VH->site_url("admin/listings/change_status/$listing_id")}" class="status_active">{$LANG_STATUS_ACTIVE}</a>{/if}
                         	{if $listing->status == 2}<a href="{$VH->site_url("admin/listings/change_status/$listing_id")}" class="status_blocked">{$LANG_STATUS_BLOCKED}</a>{/if}
                         	{if $listing->status == 3}{if $content_access_obj->isPermission('Manage all listings')}<a href="{$VH->site_url("admin/listings/change_status/$listing_id")}" class="status_suspended">{$LANG_STATUS_SUSPENDED}</a>{else}<span class="status_suspended">{$LANG_STATUS_SUSPENDED}</span>{/if}&nbsp;<a href="{$VH->site_url("admin/listings/prolong/$listing_id")}" title="{$LANG_PROLONG_ACTION}"><img src="{$public_path}images/icons/date_add.png"></a>{/if}
                         	{if $listing->status == 4}{if $content_access_obj->isPermission('Manage all listings')}<a href="{$VH->site_url("admin/listings/change_status/$listing_id")}" class="status_unapproved">{$LANG_STATUS_UNAPPROVED}</a>{else}<span class="status_unapproved">{$LANG_STATUS_UNAPPROVED}</span>{/if}{/if}
                         	{if $listing->status == 5}{if $content_access_obj->isPermission('Manage all listings')}<a href="{$VH->site_url("admin/listings/change_status/$listing_id")}" class="status_notpaid">{$LANG_STATUS_NOTPAID}</a>{else}<span class="status_notpaid">{$LANG_STATUS_NOTPAID}</span>{/if}{if $content_access_obj->isPermission('View self invoices')}&nbsp;<a href="{$VH->site_url("admin/payment/invoices/my/")}" title="{$LANG_VIEW_MY_INVOICES_MENU}"><img src="{$public_path}images/buttons/money_add.png"></a>{/if}{/if}
                         	&nbsp;
                         </td>
                         <td>
                             {$listing->creation_date|date_format:"%D %T"}&nbsp;
                         </td>
                         <td>
                         	{if !$listing->level->active_years && !$listing->level->active_months && !$listing->level->active_days && !$listing->level->allow_to_edit_active_period}
                         		<span class="green">{$LANG_ETERNAL}</span>
                         	{else}
                         		{$listing->expiration_date|date_format:"%D %T"}&nbsp;
                         	{/if}
                         </td>
                         <td>
                         	<nobr>
                         	 {if $listing->status == 1}
                         	 {assign var=listing_unique_id value=$listing->getUniqueId()}
                         	 <a href="{$VH->site_url("listings/$listing_unique_id")}" title="{$LANG_LISTING_LOOK_FRONTEND}"><img src="{$public_path}/images/buttons/house_go.png" /></a>
                         	 {/if}
                         	 <a href="{$VH->site_url("admin/listings/view/$listing_id")}" title="{$LANG_VIEW_LISTING_OPTION}"><img src="{$public_path}images/buttons/page.png"></a>
                             <a href="{$VH->site_url("admin/listings/edit/$listing_id")}" title="{$LANG_EDIT_LISTING_OPTION}"><img src="{$public_path}images/buttons/page_edit.png"></a>
                             <a href="{$VH->site_url("admin/listings/delete/$listing_id")}" title="{$LANG_DELETE_LISTING_OPTION}"><img src="{$public_path}images/buttons/page_delete.png"></a>
                             {if $listing->level->images_count > 0}
                             <a href="{$VH->site_url("admin/listings/images/$listing_id")}" title="{$LANG_LISTING_IMAGES_OPTION}"><img src="{$public_path}images/buttons/images.png"></a>
                             {/if}
                             {if $listing->level->video_count > 0}
                             <a href="{$VH->site_url("admin/listings/videos/$listing_id")}" title="{$LANG_LISTING_VIDEOS_OPTION}"><img src="{$public_path}images/buttons/videos.png"></a>
                             {/if}
                             {if $listing->level->files_count > 0}
                             <a href="{$VH->site_url("admin/listings/files/$listing_id")}" title="{$LANG_LISTING_FILES_OPTION}"><img src="{$public_path}images/buttons/page_link.png"></a>
                             {/if}
                             {if ($system_settings.google_analytics_profile_id && $system_settings.google_analytics_email && $system_settings.google_analytics_password) && ($content_access_obj->isPermission('View all statistics') || ($content_access_obj->isPermission('View self statistics') && $content_access_obj->checkListingAccess($listing_id)))}
                             <a href="{$VH->site_url("admin/listings/statistics/$listing_id")}" title="{$LANG_LISTING_STATISTICS_OPTION}"><img src="{$public_path}images/buttons/chart_bar.png"></a>
                             {/if}
                             {if $listing->level->ratings_enabled && ($content_access_obj->isPermission('Manage all ratings') || ($content_access_obj->isPermission('Manage self ratings') && $content_access_obj->checkListingAccess($listing_id)))}
                             <a href="{$VH->site_url("admin/ratings/listings/$listing_id")}" title="{$LANG_LISTING_RATINGS_OPTION}"><img src="{$public_path}images/icons/star.png"></a>
                             {/if}
                             {if $listing->level->reviews_mode && $listing->level->reviews_mode != 'disabled' && ($content_access_obj->isPermission('Manage all reviews') || ($content_access_obj->isPermission('Manage self reviews') && $content_access_obj->checkListingAccess($listing_id)))}
                             <a href="{$VH->site_url("admin/reviews/listings/$listing_id")}" title="{$LANG_LISTING_REVIEWS_OPTION}"><img src="{$public_path}images/icons/comments.png"></a>
                             {/if}
                             </nobr>
                         </td>
                       </tr>
                       {/foreach}
                     </table>
                     {$LANG_WITH_SELECTED}:
	                 <select name="table_action" onchange="action_cmd=this.options[this.selectedIndex].value; submit_listings_form(); this.form.submit()">
	                 	<option value="">{$LANG_CHOOSE_ACTION}</option>
	                 	<option value="delete">{$LANG_BUTTON_DELETE_LISTINGS}</option>
	                 </select>
                     </form>
                     
                     {$paginator}
                     {/if}
                </div>

{include file="backend/admin_footer.tpl"}