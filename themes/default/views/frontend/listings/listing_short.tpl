{assign var="listing_id" value=$listing->id}
{assign var="listing_unique_id" value=$listing->getUniqueId()}

	                         				{if $listing->level->featured}
	                         					<td class="featured_vertical">
	                         					{foreach from=$VH->mb_str_split($LANG_FEATURED) item=letter_of_featured_word}
	                         						<div>{$letter_of_featured_word}</div>
	                         					{/foreach}
	                         					</td>
	                         				{else}
	                         					<td>&nbsp;</td>
	                         				{/if}
		                         				<td class="content_field_output">
		                         					{if $listing->level->featured}<b>{/if}
		                         					{$listing->creation_date|date_format:"%D"}<br />
		                         					{$listing->creation_date|date_format:"%T"}
		                         					{if $listing->level->featured}</b>{/if}
		                         				</td>
	                         					<td align="center" {if $listing->level->logo_enabled}width="{$listing->level->explodeSize('logo_size', 'width')}px"{/if}>
	                         						{if $listing->level->logo_enabled}
					                         		<div class="img_div_border" style="margin:2px 0; border-width:2px; width: {$listing->level->explodeSize('logo_size', 'width')}px;">
														<div class="img_div" style="width: {$listing->level->explodeSize('logo_size', 'width')}px;">
															<table width="{$listing->level->explodeSize('logo_size', 'width')+2}px">
																<tr>
																	<td valign="middle" align="center">
																	{if $listing->logo_file}
																		<a href="{$VH->site_url("listings/$listing_unique_id")}"><img src="{$users_content}/users_images/logos/{$listing->logo_file}" alt="{$listing->title}"/></a>
																	{else}
																		<img src="{$public_path}/images/default_logo.jpg" width="{$listing->level->explodeSize('logo_size', 'width')}" height="{$listing->level->explodeSize('logo_size', 'height')}" />
																	{/if}
																	</td>
																</tr>
															</table>
														</div>
													</div>
													{/if}
												</td>
												<td class="content_field_output">
													{if $listing->level->featured}<b>{/if}
		                         					<a href="{$VH->site_url("listings/$listing_unique_id")}">{$listing->title()}</a>
		                         					{if $listing->level->featured}</b>{/if}
												</td>
												{$listing->outputMode(short)}