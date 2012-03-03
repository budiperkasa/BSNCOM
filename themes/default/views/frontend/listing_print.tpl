<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>{if $title}{$title} - {/if}{$site_settings.website_title}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
{foreach from=$css_files item=css_media key=css_file}
		<link rel="stylesheet" href="{$css_file}" media="{$css_media}" type="text/css" />
{/foreach}
{foreach from=$ex_css_files item=ex_css_item}
		<link rel="stylesheet" href="{$ex_css_item}" type="text/css" />
{/foreach}
{foreach from=$ex_js_scripts item=ex_js_item}
		<script language="JavaScript" type="text/javascript" src="{$ex_js_item}"></script>
{/foreach}

{if !$CI->config->item('combine_static_files') || $CI->config->item('combine_static_files') === null}
	{foreach from=$js_scripts item=js_item}
			<script language="JavaScript" type="text/javascript" src="{$VH->base_url()}{$js_item}"></script>
	{/foreach}
{else}
	<script language="JavaScript" type="text/javascript" src="{$VH->base_url()},{$VH->implode(',', $js_scripts)}"></script>
{/if}
		<link rel="shortcut icon" href="{$public_path}images/favicon.ico" >
	</head>
<body>
{assign var="listing_id" value=$listing->id}
{assign var="listing_unique_id" value=$listing->getUniqueId()}
		<div id="print_main">
      			<div id="content_block">
      				<div id="content_wrapper">
      				{if $listing->title != 'untranslated'}
						<div id="listing_header_block">
	                        <div style="float: left; width: 345px;">
	                        	<input type="button" onclick="window.print();" value="{$LANG_PRINT_PAGE_BTN}">&nbsp;&nbsp;&nbsp;<input type="button" onclick="window.close();" value="{$LANG_BUTTON_CLOSE}">
	                        	<div class="px10"></div>
	                        	<img src="{$users_content}/users_images/site_logo/{$system_settings.site_logo_file}" >
	                        	<div class="px10"></div>
	                        	<h1 class="listing_title">{$listing->title()}</h1>
		                        <div class="listing_author">{$LANG_SUMITTED_1} <strong>{$listing->user->login}</strong> {$LANG_SUMITTED_2} {$listing->creation_date|date_format:"%D %H:%M"}</div>
		                        {if $listing->type->categories_type != 'disabled' && $listing->level->categories_number && $listing->categories_array()|@count}
			                        <div class="listing_page_categories">{$LANG_SUMITTED_3} 
			                        {foreach from=$listing->categories_array() item=category}
			                        	<span class="listing_cat_link">{$category->name}</span>&nbsp;&nbsp;
			                        {/foreach}
			                        </div>
		                        {/if}
		                        <div class="clear_float"></div>

		                        {if $listing->level->ratings_enabled}
		                        	{assign var=avg_rating value=$listing->getRatings()}
									{$avg_rating->setInactive()}
									{$avg_rating->view()}
								{/if}
		                        <div class="clear_float"></div>
		                        <div class="px5"></div>
		                        
		                        {if $listing->level->logo_enabled && $listing->logo_file}
		                        <div id="listing_logo">
					                <!--<h1>{$LANG_LISTING_LOGO}</h1>-->
									<img src="{$users_content}/users_images/thmbs_big/{$listing->logo_file}" alt="{$listing->title()}"/>
								</div>
								{/if}
								
								{if $listing->level->images_count && $images|@count}
								{assign var="columns_num" value=3}
								<div class="listing_images_gallery">
									<table>
										<tr>
										{assign var="i" value=0}
										{foreach from=$images item=image}
											{assign var="i" value=$i+1}
											<td align="center" valign="middle" class="small_image_bg">
												<img src="{$users_content}/users_images/thmbs_small/{$image->file}"/>
											</td>
											{if $i >= $columns_num}
											</tr><tr>
											{assign var="i" value=0}
											{/if}
										{/foreach}
										</tr>
									</table>
								</div>
								{/if}
							</div>
							<div class="clear_float"></div>
						</div>
						{if $listing->listing_description}
						<h1>{$LANG_LISTING_SHORT_DESCRIPTION}</h1>
						<div id="listing_description">
							{if $listing->level->description_mode == 'richtext'}
							{$listing->listing_description}
							{else}
							{$listing->listing_description|nl2br}
							{/if}
						</div>
						{/if}

						{if $listing->content_fields->fieldsCount() && $listing->content_fields->isAnyValue()}
							<h1>{$LANG_LISTING_INFORMATION}</h1>
		                   	{$listing->content_fields->outputMode()}
						{/if}
						
	                    {if $listing->type->locations_enabled && $listing->locations_count()}
							<div id="addresses-tab">
								<h1 id="map">{$LANG_LISTING_ADDRESS}</h1>
								<div class="listing_address_block">
									{assign var="i" value=1}
									{foreach from=$listing->locations_array() item=location}
									<div class="address_line">{if $listing->locations_count()>1}<span class="address_label">{$LANG_LISTING_ADDRESS} {$i++}:</span> {/if}{$location->compileAddress()}</div>
									{/foreach}
								</div>
								{if $listing->level->maps_enabled}
									{render_frontend_block
										block_type='map_and_markers'
										block_template='frontend/blocks/map_standart.tpl'
										existed_listings=$listing
										map_width=$listing->level->explodeSize('maps_size', 'width')
										map_height=$listing->level->explodeSize('maps_size', 'height')
									}
								{/if}
							</div>
						{/if}

						{if $listing->level->video_count && $videos|@count}
							<div id="videos-tab">
								<h1 id="videos">{$LANG_LISTING_VIDEOS}</h1>
								{foreach from=$videos item=video}
								<div class="listing_videos_block">
									<div>
		                      			<img src="http://img.youtube.com/vi/{$video->video_code}/0.jpg" title="{$video->title}" />
		                      		</div>
		                       		<div class="listing_videos_title">
		                      			{$video->title}
		                      		</div>
		                       	</div>
								{/foreach}
							</div>
						{/if}

						{if $listing->level->files_count && $files|@count}
							<div id="files-tab">
								<h1 id="files">{$LANG_LISTING_FILES}</h1>
								<div class="listing_files_block">
								{foreach from=$files item=file}
									{assign var="file_id" value=$file->id}
									<span class="listing_file_item" style="background: url('{$public_path}images/file_types/{$file->file_format}.png') 0 0 no-repeat;">{$file->title} ({$file->file_size})</span>
								{/foreach}
								</div>
							</div>
						{/if}

						{if $listing->level->reviews_mode != 'disabled'}
							<div id="reviews-tab">
								{render_frontend_block
							      	block_type='reviews_comments'
							      	block_template='frontend/blocks/reviews_comments_add.tpl'
							      	objects_table='listings'
							      	objects_ids=$listing->id
							      	comment_area_enabled=false
							      	reviews_mode=$listing->level->reviews_mode
							      	admin_mode=false
								}
							</div>
						{/if}
						<div class="px10"></div>
						<input type="button" onclick="window.print();" value="{$LANG_PRINT_PAGE_BTN}">&nbsp;&nbsp;&nbsp;<input type="button" onclick="window.close();" value="{$LANG_BUTTON_CLOSE}">
					{else}
					<div class="error_msg rounded_corners">
						<ul>
							<li>{$LANG_LISTING_TRANSLATION_ERROR}</li>
						</ul>
					</div>
					{/if}
                 	</div>
			</div>
		</div>
</body>
</html>