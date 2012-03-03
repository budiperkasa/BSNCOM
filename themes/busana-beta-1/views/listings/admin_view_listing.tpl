{include file="backend/admin_header.tpl"}
{assign var="listing_id" value=$listing->id}
{assign var="listing_owner_id" value=$listing->owner_id}

                <div class="content">
                    <h3>{$LANG_VIEW_LISTING} "{$listing->title()}"</h3>
                    
                    {include file="listings/admin_listing_options_menu.tpl"}

                    <label class="block_title">{$LANG_LISTING_INFO}</label>
                    <div class="admin_option">
						<table>
							<tr>
								<td class="table_left_side">{$LANG_STATUS_TH}:</td>
								<td class="table_right_side">
									{if $listing->status == 1}<a href="{$VH->site_url("admin/listings/change_status/$listing_id")}" class="status_active">{$LANG_STATUS_ACTIVE}</a>{/if}
	    	                     	{if $listing->status == 2}<a href="{$VH->site_url("admin/listings/change_status/$listing_id")}" class="status_blocked">{$LANG_STATUS_BLOCKED}</a>{/if}
	        	                 	{if $listing->status == 3}{if $content_access_obj->isPermission('Manage all listings')}<a href="{$VH->site_url("admin/listings/change_status/$listing_id")}" class="status_suspended">{$LANG_STATUS_SUSPENDED}</a>{else}<span class="status_suspended">{$LANG_STATUS_SUSPENDED}</span>{/if}&nbsp;<a href="{$VH->site_url("admin/listings/prolong/$listing_id")}" title="{$LANG_PROLONG_ACTION}"><img src="{$public_path}images/icons/date_add.png"></a>{/if}
	            	             	{if $listing->status == 4}{if $content_access_obj->isPermission('Manage all listings')}<a href="{$VH->site_url("admin/listings/change_status/$listing_id")}" class="status_unapproved">{$LANG_STATUS_UNAPPROVED}</a>{else}<span class="status_unapproved">{$LANG_STATUS_UNAPPROVED}</span>{/if}{/if}
	                	         	{if $listing->status == 5}{if $content_access_obj->isPermission('Manage all listings')}<a href="{$VH->site_url("admin/listings/change_status/$listing_id")}" class="status_notpaid">{$LANG_STATUS_NOTPAID}</a>{else}<span class="status_notpaid">{$LANG_STATUS_NOTPAID}</span>{/if}{if $content_access_obj->isPermission('View self invoices')}&nbsp;<a href="{$VH->site_url("admin/payment/invoices/my/")}" title="{$LANG_VIEW_MY_INVOICES_MENU}"><img src="{$public_path}images/buttons/money_add.png"></a>{/if}{/if}
                         			&nbsp;
								</td>
							</tr>
							<tr>
								<td class="table_left_side">{$LANG_OWNER_TH}:</td>
								<td class="table_right_side">
									{if $listing->owner_id != 1 && $listing->owner_id != $session_user_id && $content_access_obj->isPermission('View all users')}
		                         		<a href="{$VH->site_url("admin/users/view/$listing_owner_id")}" title="{$LANG_VIEW_USER_OPTION}">{$listing->owner_login()}</a>
		                         	{else}
		                         		{$listing->user->login}
		                         	{/if}
		                         	&nbsp;
		                         </td>
							</tr>
							<tr>
								<td class="table_left_side">{$LANG_TYPE_TH}:</td>
								<td class="table_right_side">{$listing->type->name}</td>
							</tr>
							<tr>
								<td class="table_left_side">{$LANG_LEVEL_TH}:</td>
								<td class="table_right_side">
									{if $content_access_obj->isPermission('Change listing level') && $listing->type->buildLevels()|@count > 1}
			                        	<a href="{$VH->site_url("admin/listings/change_level/$listing_id")}" title="{$LANG_CHANGE_LISTING_LEVEL_OPTION}">{$listing->level->name}</a> <a href="{$VH->site_url("admin/listings/change_level/$listing_id")}" title="{$LANG_CHANGE_LISTING_LEVEL_OPTION}"><img src="{$public_path}/images/icons/upgrade.png" /></a>
									{else}
										{$listing->level->name}
									{/if}
								</td>
							</tr>
							<tr>
								<td class="table_left_side">{$LANG_CREATION_DATE_TH}:</td>
								<td class="table_right_side">{$listing->creation_date|date_format:"%D %T"}</td>
							</tr>
							<tr>
								<td class="table_left_side">{$LANG_EXPIRATION_DATE_TH}:</td>
								<td class="table_right_side">
									{if $listing->level->eternal_active_period && !$listing->level->allow_to_edit_active_period}
		                         		<span class="green">{$LANG_ETERNAL}</span>
		                         	{else}
		                         		{$listing->expiration_date|date_format:"%D %T"}
		                         	{/if}
								</td>
							</tr>
							<tr>
								<td class="table_left_side">{$LANG_LAST_MODIFIED_DATE_TH}:</td>
								<td class="table_right_side">{$listing->last_modified_date|date_format:"%D %T"}</td>
							</tr>
							<tr>
								<td class="table_left_side">{$LANG_LISTING_PROLONGED_TH}:</td>
								<td class="table_right_side">{$listing->was_prolonged_times}</td>
							</tr>
						</table>
					</div>

					<label class="block_title">{$LANG_LISTING_SUMMARY}</label>
					<div class="admin_option">
						<table>
							{if $listing->level->title_enabled}
							<tr>
								<td class="table_left_side">{$LANG_TITLE_TD}:</td>
								<td class="table_right_side">{$listing->title()}</td>
							</tr>
							{/if}
							{if $listing->level->seo_title_enabled}
							<tr>
								<td class="table_left_side">{$LANG_SEO_TITLE_TD}:</td>
								<td class="table_right_side">{$listing->seo_title}</td>
							</tr>
							{/if}
							{if $listing->level->description_mode != 'disabled'}
							<tr>
								<td class="table_left_side">{$LANG_SHORT_DESCRIPTION}:</td>
								<td class="table_right_side">
									{if $listing->level->description_mode == 'richtext'}
									{$listing->listing_description}
									{else}
									{$listing->listing_description|nl2br}
									{/if}
								</td>
							</tr>
							{/if}
							{if $listing->level->meta_enabled}
							<tr>
								<td class="table_left_side">{$LANG_LISTING_META_DESCRIPTION}:</td>
								<td class="table_right_side">{$listing->listing_meta_description|nl2br}</td>
							</tr>
							<tr>
								<td class="table_left_side">{$LANG_LISTING_KEYWORDS}:</td>
								<td class="table_right_side">{$listing->listing_keywords|nl2br}</td>
							</tr>
							{/if}
							{if $listing->level->logo_enabled && $listing->logo_file}
							<tr>
								<td class="table_left_side">{$LANG_LOGO_TH}:</td>
								<td class="table_right_side">
									<div id="img_wrapper">
										<div id="img_div_border" style="width: {$listing->level->explodeSize('logo_size', 'width')}px; height: {$listing->level->explodeSize('logo_size', 'height')}px;">
											<div id="img_div" style="width: {$listing->level->explodeSize('logo_size', 'width')}px; height: {$listing->level->explodeSize('logo_size', 'height')}px;">
												<table width="{$listing->level->explodeSize('logo_size', 'width')+4}px" height="{$listing->level->explodeSize('logo_size', 'height')+4}px">
													<tr>
														<td valign="middle" align="center">
															<img id="img" src="{$users_content}/users_images/logos/{$listing->logo_file}">
														</td>
													</tr>
												</table>
											</div>
										</div>
									</div>
								</td>
							</tr>
							{/if}
						</table>
					</div>

					{if $listing->type->categories_type != 'disabled' && $listing->level->categories_number}
					<label class="block_title">{$LANG_LISTING_IN_CATEGORIES}</label>
					<div class="admin_option">
						<ul>
						{foreach from=$listing->categories_array() item=category}
                        	<div id="category_{$category->id}" class="categories_item" unselectable="on">
                        		<li>{$category->getChainAsString()}</li>
                        	</div>
                        {/foreach}
						</ul>
					</div>
					{/if}

					{if $listing->type->locations_enabled && $listing->locations_count()}
					<label class="block_title">{$LANG_LISTING_LOCATIONS}</label>
					<div class="admin_option">
						<table>
							{assign var="i" value=1}
							{foreach from=$listing->locations_array() item=location}
							<tr>
								<td class="table_left_side">{$LANG_LISTING_ADDRESS} {$i++}</td>
								<td class="table_right_side">{$location->compileAddress()}</td>
							</tr>
							{/foreach}
							{if $listing->level->maps_enabled && $listing->locations_count(true)}
							<tr>
								<td class="table_left_side">{$LANG_MAP}:</td>
								<td class="table_right_side">
									{render_frontend_block
										block_type='map_and_markers'
										block_template='frontend/blocks/map_standart.tpl'
										existed_listings=$listing->id
										map_width=$listing->level->explodeSize('maps_size', 'width')
										map_height=$listing->level->explodeSize('maps_size', 'height')
										show_anchors=false
										show_links=false
									}
								</td>
							</tr>
							{/if}
						</table>
					</div>
					{/if}

					{if $listing->content_fields->fieldsCount() && $listing->content_fields->isAnyValue()}
						<label class="block_title">{$LANG_LISTING_ADDITIONAL_INFORMATION}</label>
						<div class="admin_option">
		                   	{$listing->content_fields->outputMode()}
						</div>
                    {/if}
                </div>

{include file="backend/admin_footer.tpl"}