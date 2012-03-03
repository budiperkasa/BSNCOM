{assign var="listing_id" value=$listing->id}
{assign var="listing_unique_id" value=$listing->getUniqueId()}
{assign var="user_unique_id" value=$listing->user->getUniqueId()}

									<div id="listing_id-{$listing_unique_id}" class="listing_preview {if $listing->level->featured}featured{/if}">
										<div class="listing_head">
		                         			{if $listing->level->featured}
		                         				<div class="featured_label">{$LANG_FEATURED}</div>
		                         			{/if}
		                         			<div class="listing_title">
		                         				<a href="{$VH->site_url("listings/$listing_unique_id")}">{$listing->title()}</a>
		                         			</div>
		                         			<div style="text-align: right; float: right">
				                         		{if $listing->level->images_count && $listing->getAssignedImages()}
													<a href="{$VH->site_url("listings/$listing_unique_id")}#images" title="{$LANG_LISTING_IMAGES_OPTION}"><img src="{$public_path}/images/buttons/images.png" /></a>&nbsp;
												{/if}
												{if $listing->level->video_count && $listing->getAssignedVideos()}
													<a href="{$VH->site_url("listings/$listing_unique_id")}#videos" title="{$LANG_LISTING_VIDEOS_OPTION}"><img src="{$public_path}/images/buttons/videos.png" /></a>&nbsp;
												{/if}
												{if $listing->level->files_count && $listing->getAssignedFiles()}
													<a href="{$VH->site_url("listings/$listing_unique_id")}#files" title="{$LANG_LISTING_FILES_OPTION}"><img src="{$public_path}/images/buttons/page_link.png" /></a>&nbsp;
												{/if}
												{if $listing->type->locations_enabled && $listing->locations_count(true) && $listing->level->maps_enabled}
													<a href="{$VH->site_url("listings/$listing_unique_id")}#addresses" id="open_iw_{$listing_unique_id}" title="{$LANG_MAP}"><img src="{$public_path}/images/buttons/map.png" /></a>&nbsp;
												{/if}
												{if $listing->level->option_quick_list}
													<a href="javascript: void(0);" class="add_to_favourites" listingid="{$listing->id}" title="{$LANG_ADD_REMOVE_QUICK_LIST}"></a>&nbsp;
												{/if}
												{if $listing->level->option_email_friend}
													<a href="{$VH->site_url("email/send/listing_id/$listing_id/target/friend/")}" class="nyroModal" title="{$LANG_EMAIL_FRIEND}"><img src="{$public_path}/images/icons/email_go.png"></a>&nbsp;
												{/if}
			                         		</div>
		                         			<div class="listing_author">{$LANG_SUMITTED_1} {if $listing->user->users_group->is_own_page && $listing->user->users_group->id != 1}<a href="{$VH->site_url("users/$user_unique_id")}" title="{$LANG_VIEW_USER_PAGE_OPTION}">{$listing->user->login}</a>{else}<strong>{$listing->user->login}</strong>{/if} {$LANG_SUMITTED_2} {$listing->creation_date|date_format:"%D %H:%M"}</div>
		                         			{if $listing->type->categories_type != 'disabled' && $listing->level->categories_number && $listing->categories_array()|@count}
			                         			<div class="listing_categories">
			                         				{$LANG_SUMITTED_3}&nbsp;
			                         				{foreach from=$listing->categories_array() item=category}
			                         				{assign var=category_url value=$category->getUrl()}
			                         					<a href="{$VH->site_url($category->getUrl())}" class="listing_cat_link">{$category->name}</a>&nbsp;&nbsp;
			                         				{/foreach}
			                         			</div>
		                         			{/if}
		                         			<div class="clear_float"></div>

		                         			{if $listing->level->ratings_enabled}
												{assign var=avg_rating value=$listing->getRatings()}
												{$avg_rating->view()}
											{/if}
											{if $listing->level->reviews_mode && $listing->level->reviews_mode != 'disabled'}
		                         			<div class="stat">
		                         				<a href="{$VH->site_url("listings/$listing_unique_id")}#reviews" title="{if $listing->level->reviews_mode == 'reviews'}{$LANG_READ_REVIEWS}{else}{$LANG_READ_COMMENTS}{/if}">{$listing->getReviewsCount()} {if $listing->level->reviews_mode == 'reviews'}{$LANG_REVIEWS}{else}{$LANG_COMMENTS}{/if}</a> <a href="{$VH->site_url("listings/$listing_unique_id")}#reviews" title="{if $listing->level->reviews_mode == 'reviews'}{$LANG_READ_REVIEWS}{else}{$LANG_READ_COMMENTS}{/if}"><img src="{$public_path}/images/icons/comments.png" /></a>
		                         			</div>
		                         			{/if}
		                         			<div class="clear_float"></div>
	                         			</div>

										{if $listing->level->logo_enabled}
		                         		<div class="img_div_border" style="margin: 0 10px 10px 0; float: left; width: {$listing->level->explodeSize('logo_size', 'width')}px; height: {$listing->level->explodeSize('logo_size', 'height')}}px;">
											<div class="img_div" style="width: {$listing->level->explodeSize('logo_size', 'width')}px; height: {$listing->level->explodeSize('logo_size', 'height')}}px;">
												<table width="{$listing->level->explodeSize('logo_size', 'width')+4}px" height="{$listing->level->explodeSize('logo_size', 'height')+4}px">
													<tr>
														<td valign="middle" align="center">
														{if $listing->logo_file}
															<a href="{$VH->site_url("listings/$listing_unique_id")}"><img src="{$users_content}/users_images/logos/{$listing->logo_file}" alt="{$listing->title()}"/></a>
														{else}
															<img src="{$public_path}/images/default_logo.jpg" width="{$listing->level->explodeSize('logo_size', 'width')}" height="{$listing->level->explodeSize('logo_size', 'height')}" />
														{/if}
														</td>
													</tr>
												</table>
											</div>
										</div>
										{/if}
										<div class="listing_description">
											{if $listing->type->locations_enabled && $listing->locations_count()}
											<div class="listing_address_block">
												{assign var="i" value=1}
												{foreach from=$listing->locations_array() item=location}
												<div class="address_line">{if $location->calcDistanceFromCenter()}<span class="address_label" style="color: #006699">{$location->calcDistanceFromCenter()}</span>&nbsp;&nbsp;{/if} {if $listing->locations_count()>1}<span class="address_label">{$LANG_LISTING_ADDRESS} {$i++}:</span> {/if}{$location->compileAddress()}</div>
												{/foreach}
											</div>
											{/if}
											{if $listing->level->description_mode == 'richtext'}
											{$listing->listing_description_teaser()}
											{else}
											{$listing->listing_description_teaser()|nl2br}
											{/if}
		                         		</div>
	                         			<div class="clear_float"></div>
	                         			{$listing->outputMode(full)}
	                         			<a href="{$VH->site_url("listings/$listing_unique_id")}" class="view_listing_link">{$LANG_VIEW_LISTING} >>></a>
	                         		</div>