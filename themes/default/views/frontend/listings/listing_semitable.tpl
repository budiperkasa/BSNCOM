{assign var="listing_id" value=$listing->id}
{assign var="listing_unique_id" value=$listing->getUniqueId()}

										<span id="listing_id-{$listing_unique_id}"></span>
	                         			{if $listing->level->featured}
	                         				<div class="featured_label">{$LANG_FEATURED}</div>
	                         			{else}
	                         				<div class="featured_label">&nbsp;</div>
	                         			{/if}
	                         			<div class="clear_float"></div>
										{if $listing->level->logo_enabled}
		                         		<div class="img_div_border" style="width: {$listing->level->explodeSize('logo_size', 'width')}px; height: {$listing->level->explodeSize('logo_size', 'height')}}px;">
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

										{if $listing->level->ratings_enabled}
											{assign var=avg_rating value=$listing->getRatings()}
											{$avg_rating->view()}
										{/if}
										{if $listing->level->reviews_mode && $listing->level->reviews_mode != 'disabled'}
		                         		<div class="stat">
		                         			<a href="{$VH->site_url("listings/$listing_unique_id")}#reviews-tab" title="{if $listing->level->reviews_mode == 'reviews'}{$LANG_READ_REVIEWS}{else}{$LANG_READ_COMMENTS}{/if}">{$listing->getReviewsCount()}&nbsp;{if $listing->level->reviews_mode == 'reviews'}{$LANG_REVIEWS}{else}{$LANG_COMMENTS}{/if}</a>
		                         		</div>
		                         		{/if}
		                         		<div class="clear_float"></div>

										<div class="listing_title listing_title_small">
	                         				<a href="{$VH->site_url("listings/$listing_unique_id")}">{$listing->title()}</a>
	                         			</div>
	                         			{if $listing->type->categories_type != 'disabled' && $listing->level->categories_number && $listing->categories_array()|@count}
		                         			<div class="listing_categories_semitable">
		                         				{$LANG_SUMITTED_3}&nbsp;
		                         				{foreach from=$listing->categories_array() item=category}
		                         				{assign var="category_seo_name" value=$category->seo_name}
		                         					<a href="{$VH->site_url($category->getUrl())}" class="listing_cat_link">{$category->name}</a>&nbsp;&nbsp;
		                         				{/foreach}
		                         			</div>
	                         			{/if}
	                         			{$listing->outputMode(semitable)}