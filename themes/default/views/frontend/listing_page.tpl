{include file="frontend/header.tpl"}

{assign var="listing_id" value=$listing->id}
{assign var="listing_unique_id" value=$listing->getUniqueId()}
{assign var="user_unique_id" value=$listing->user->getUniqueId()}
{assign var="user_login" value=$listing->user->login}

{assign var="max_images_for_carousel" value=9}

			<tr>
				<td id="search_bar" colspan="3">
				{include file="frontend/search_block.tpl"}
				</td>
			</tr>
			<tr>
				<td id="left_sidebar">
				{include file="frontend/left-sidebar.tpl"}
				</td>
      			<td id="content_block" valign="top">
      				<div id="content_wrapper">
      				{if $listing->title != 'untranslated'}
						<script language="Javascript" type="text/javascript">
						$(document).ready(function() {ldelim}
							var $tabs = $("#tabs").tabs();
							$('.open_tab').click(function() {ldelim}
								$tabs.tabs('select', $(this).attr("href"));
								moveSlowly($(this));
								return false;
							{rdelim});

							$('a.lightbox_image').live('mouseover', function(){ldelim}
								$('a.lightbox_image, a.hidden_imgs').lightBox({ldelim}
									overlayOpacity: 0.75,
									imageLoading: '{$public_path}images/lightbox-ico-loading.gif',
									imageBtnClose: '{$public_path}images/lightbox-btn-close.gif',
									imageBtnPrev: '{$public_path}images/lightbox-btn-prev.gif',
									imageBtnNext: '{$public_path}images/lightbox-btn-next.gif'
								{rdelim});
							{rdelim});

							{if $listing->level->images_count && $images|@count}
							$(".img_small").click(function() {ldelim}
								if ($("#listing_logo").find("img").attr("src") != "{$public_path}images/lightbox-ico-loading.gif") {ldelim}
									var img_small = $(this).clone();
									// retrieve basename of image file
									var file_name = img_small.find("img").attr('src').replace(/^.*\/|\.*$/g, '');

									// Set the size of big image and place ajax loader there
									var big_image_width = $("#listing_logo").find("img").width();
									var big_image_height = $("#listing_logo").find("img").height();
									
									$(".lightbox_image").html("<img src='{$public_path}images/lightbox-ico-loading.gif' />");
									$("#listing_logo").css("width", big_image_width);
									$("#listing_logo").css("height", big_image_height);
									$(".lightbox_image").css("position", "relative");
									$(".lightbox_image").css("top", (big_image_height/2)-20);
									$(".lightbox_image").css("left", (big_image_width/2)-20);

									// Remove thmb of logo from lighbox images
									$(".hidden_divs").each(function() {ldelim}
										if (file_name == $(this).find("a").attr('href').replace(/^.*\/|\.*$/g, ''))
											$(this).find("a").removeClass("hidden_imgs");
										else
											$(this).find("a").addClass("hidden_imgs");
									{rdelim});

									// Load new image into big image container
									var img = new Image();
							        $(img).load(function () {ldelim}
							            $(this).hide();
							            img_small.html(this);
							            img_small.removeClass("img_small").addClass("lightbox_image");
							            $("#listing_logo").html(img_small);
							            $("#listing_logo").css("width", img_small.find("img").width());
										$("#listing_logo").css("height", img_small.find("img").height());
							            $(this).fadeIn();
							        {rdelim}).attr('src', '{$users_content}/users_images/thmbs_big/'+file_name);
								{rdelim}
								return false;
							{rdelim});
							{/if}
						{rdelim});
						</script>

						<div class="breadcrumbs"><a href="{$VH->index_url()}">{$LANG_HOME_PAGE}</a>{foreach from=$breadcrumbs item="source_page" key="source_url"}{if $source_page} » <a href="{$source_url}">{$source_page}</a>{/if}{/foreach} » <span>{$listing->title()}</span></div>
						<div id="messages" style="display: none;"></div>

						<div id="listing_header_block">
	                        <div style="float: left; width: 345px;">
	                        	<h1 class="listing_title">{$listing->title()}</h1>
		                        <div class="listing_author">{$LANG_SUMITTED_1} {if $listing->user->users_group->is_own_page && $listing->user->users_group->id != 1}<a href="{$VH->site_url("users/$user_unique_id")}" title="{$LANG_VIEW_USER_PAGE_OPTION}">{$listing->user->login}</a>{else}<strong>{$listing->user->login}</strong>{/if} {$LANG_SUMITTED_2} {$listing->creation_date|date_format:"%D %H:%M"}</div>
		                        {if $listing->type->categories_type != 'disabled' && $listing->level->categories_number && $listing->categories_array()|@count}
			                        <div class="listing_page_categories">{$LANG_SUMITTED_3} 
			                        {foreach from=$listing->categories_array() item=category}
			                        	<a href="{$VH->site_url($category->getUrl())}" class="listing_cat_link">{$category->name}</a>&nbsp;&nbsp;
			                        {/foreach}
			                        </div>
		                        {/if}
		                        <div class="clear_float"></div>

		                        {if $listing->level->ratings_enabled}
		                        	{assign var=avg_rating value=$listing->getRatings()}
									{$avg_rating->view()}
								{/if}
		                        <div class="clear_float"></div>
		                        <div class="px5"></div>
		                        
		                        {if ($listing->level->logo_enabled && $listing->logo_file) || $images|@count}
		                        <div id="listing_logo">
		                        	{if $listing->level->logo_enabled && $listing->logo_file}
		                        		{assign var=logo value=$listing->logo_file}
		                        	{else}
		                        		{assign var=logo value=$images[0]->file}
		                        	{/if}
									<a href="{$users_content}users_images/images/{$logo}" class="lightbox_image" title="{$listing->title()}"><img src="{$users_content}/users_images/thmbs_big/{$logo}" alt="{$listing->title()}"/></a>
								</div>
								{/if}
								
								{if $listing->level->images_count && $images|@count}
								{if $images|@count <= $max_images_for_carousel}
									{assign var="columns_num" value=3}
									<div class="listing_images_gallery">
										<table>
											<tr>
											{assign var="i" value=0}
											{foreach from=$images item=image}
												{assign var="i" value=$i+1}
												<td align="center" valign="middle" class="small_image_bg">
													<a href="{$users_content}users_images/images/{$image->file}" class="img_small" title="{$image->title}"><img src="{$users_content}/users_images/thmbs_small/{$image->file}"/></a>
													<div class="hidden_divs" style="display:none"><a href="{$users_content}users_images/images/{$image->file}" {if $image->file != $listing->logo_file}class="hidden_imgs"{/if}></a></div>
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
								{/if}
							</div>

							<div id="listing_options_panel">
								{if $listing->level->social_bookmarks_enabled}
									<a class="a2a_dd" href="http://www.addtoany.com/share_save"><img src="http://static.addtoany.com/buttons/share_save_171_16.png" width="171" height="16" border="0" alt="Share/Bookmark"/></a><script type="text/javascript" src="http://static.addtoany.com/menu/page.js"></script>
								{/if}
								{if $listing->user->id == $session_user_id || $content_access_obj->isPermission('Manage all listings')}
									<span class="listing_options_panel_item edit"><a href="{$VH->site_url("admin/listings/edit/$listing_id")}">{$LANG_EDIT_LISTING_OPTION}</a></span>
								{/if}
								{if $listing->level->option_print}
									<span class="listing_options_panel_item print"><a href="javascript: void(0);" rel="nofollow" onClick="$.jqURL.loc('{$VH->site_url("print_listing/$listing_unique_id/")}', {ldelim}w:590,h:750,wintype:'_blank'{rdelim}); return false;">{$LANG_PRINT_PAGE}</a></span>
								{/if}
								{if $listing->level->option_pdf}
									<span class="listing_options_panel_item pdf"><a href="http://pdfmyurl.com/?url={$VH->site_url("listings/$listing_unique_id")}">{$LANG_PDF_PAGE}</a></span>
								{/if}
								{if $listing->level->option_quick_list}
									<a href="javascript: void(0);" class="add_to_favourites" listingid="{$listing->id}" title="{$LANG_ADD_REMOVE_QUICK_LIST}"></a>&nbsp;<a href="javascript: void(0);" class="add_to_favourites_button">{$LANG_ADD_REMOVE_QUICK_LIST}</a>&nbsp;
								{/if}
								{if $listing->level->option_email_friend}
									<span class="listing_options_panel_item friend"><a href="{$VH->site_url("email/send/listing_id/$listing_id/target/friend/")}" class="nyroModal">{$LANG_EMAIL_FRIEND}</a></span>
								{/if}
								{if $listing->level->option_email_owner}
									<span class="listing_options_panel_item owner"><a href="{$VH->site_url("email/send/listing_id/$listing_id/target/owner/")}" class="nyroModal">{$LANG_EMAIL_OWNER}</a></span>
								{/if}
								<span class="user_options_panel_item all_listings"><a href="{$VH->site_url("search/search_owner/$user_login")}" title="{$LANG_USER_VIEW_ALL_LISTINGS}">{$LANG_USER_VIEW_ALL_LISTINGS}</a></span>
								{if $listing->level->option_report}
		                        	<span class="listing_options_panel_item report"><a href="{$VH->site_url("email/send/listing_id/$listing_id/target/report/")}" class="nyroModal">{$LANG_REPORT_ADMIN}</a></span>
		                        {/if}
								{if $listing->type->locations_enabled && $listing->locations_count() && $listing->level->maps_enabled}
									<span class="listing_options_panel_item map"><a href="#addresses-tab" id="addresses" class="open_tab">{$LANG_LISTING_ADDRESSES_OPTION}</a></span>
								{/if}
								{if $listing->level->video_count && $listing->getAssignedVideos()}
									<span class="listing_options_panel_item videos"><a href="#videos-tab" id="videos" class="open_tab">{$LANG_LISTING_VIDEOS_OPTION}</a></span>
								{/if}
								{if $listing->level->files_count && $listing->getAssignedFiles()}
									<span class="listing_options_panel_item files"><a href="#files-tab" id="files" class="open_tab">{$LANG_LISTING_FILES_OPTION}</a></span>
								{/if}
								{if $listing->level->reviews_mode && $listing->level->reviews_mode != 'disabled'}
		                        	<span class="listing_options_panel_item reviews"><a href="#reviews-tab" id="reviews" class="open_tab">{$listing->getReviewsCount()} {if $listing->level->reviews_mode == 'reviews'}{$LANG_REVIEWS}{else}{$LANG_COMMENTS}{/if}</a></span>
		                        {/if}
		                        {if $content_access_obj->isPermission("Claim on listings") && $listing->claim_row.ability_to_claim}
		                        	<span class="listing_options_panel_item claim"><a href="{$VH->site_url("listings/claim/$listing_unique_id")}">{$LANG_CLAIM_LISTING_OPTION}</a></span>
		                        {/if}
		                        {if $listing->user->users_group->is_own_page && $listing->user->users_group->id != 1}
		                        	<span class="listing_options_panel_item author"><a href="{$VH->site_url("users/$user_unique_id")}">{$LANG_VIEW_USER_PAGE_OPTION}</a></span>
		                        {/if}
		                        {if $content_access_obj->isPermission('Change listing level') && $listing->type->buildLevels()|@count > 1}
		                        	<span class="listing_options_panel_item upgrade">{$LANG_LEVEL_TH}: {$listing->level->name} (<a href="{$VH->site_url("admin/listings/change_level/$listing_id")}">{$LANG_CHANGE_LISTING_LEVEL_OPTION}</a>)</span>
		                        {/if}
							</div>
							<div class="clear_float"></div>
						</div>
						{if $images|@count > $max_images_for_carousel}
							<!-- Carousel js gallery -->
							<script type="text/javascript">
								jQuery(document).ready(function() {ldelim}
								    jQuery('#mycarousel').jcarousel({ldelim}visible:4, scroll:2{rdelim});
								{rdelim});
							</script>
							<ul id="mycarousel" class="jcarousel-skin-tango">
								{foreach from=$images item=image}
									<li>
										<a href="{$users_content}users_images/images/{$image->file}" class="img_small" title="{$image->title}"><img src="{$users_content}/users_images/thmbs_small/{$image->file}"/></a>
										<div class="hidden_divs" style="display:none"><a href="{$users_content}users_images/images/{$image->file}" {if $image->file != $listing->logo_file}class="hidden_imgs"{/if}></a></div>
									</li>
								{/foreach}
							</ul>
						{/if}

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
		                   	<div class="px10"></div>
						{/if}
						
							{if $available_options_count > 1}
						<div id="tabs">
							<ul>
								{if $listing->type->locations_enabled && $listing->locations_count()}
									<li><a href="#addresses-tab">{$LANG_LISTING_ADDRESS}</a></li>
								{/if}
								{if $listing->level->video_count && $listing->getAssignedVideos()}
									<li><a href="#videos-tab">{$LANG_LISTING_VIDEOS}</a></li>
								{/if}
								{if $listing->level->files_count && $listing->getAssignedFiles()}
									<li><a href="#files-tab">{$LANG_LISTING_FILES}</a></li>
								{/if}
								{if $listing->level->reviews_mode && $listing->level->reviews_mode != 'disabled'}
									<li><a href="#reviews-tab">{if $listing->level->reviews_mode == 'reviews'}{$LANG_REVIEWS}{elseif $listing->level->reviews_mode == 'comments'}{$LANG_COMMENTS}{/if} ({$listing->getReviewsCount()})</a></li>
								{/if}
							</ul>
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
									{if $listing->level->maps_enabled && $listing->locations_count(true)}
							      		{render_frontend_block
							      			block_type='map_and_markers'
							      			block_template='frontend/blocks/map_standart_directions.tpl'
							      			existed_listings=$listing
							      			map_width=$listing->level->explodeSize('maps_size', 'width')
							      			map_height=$listing->level->explodeSize('maps_size', 'height')
							      			show_anchors=false
							      			show_links=false
										}
									{/if}
								</div>
							{/if}

							{if $listing->level->video_count && $listing->getAssignedVideos()}
								<div id="videos-tab">
									<h1 id="videos">{$LANG_LISTING_VIDEOS}</h1>
									{foreach from=$videos item=video}
									<div class="listing_videos_block">
										<div>
		                         			<object width="{$listing->level->explodeSize('video_size', 'width')}" height="{$listing->level->explodeSize('video_size', 'height')}"><param name="movie" value="http://www.youtube.com/v/{$video->video_code}&hl=en&fs=1&"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/{$video->video_code}&hl=en&fs=1&" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="{$listing->level->explodeSize('video_size', 'width')}" height="{$listing->level->explodeSize('video_size', 'height')}"></embed></object>
		                         		</div>
		                         		<div class="listing_videos_title">
		                         			{$video->title}
		                         		</div>
		                         	</div>
									{/foreach}
								</div>
							{/if}
							
							{if $listing->level->files_count && $listing->getAssignedFiles()}
								<div id="files-tab">
									<h1 id="files">{$LANG_LISTING_FILES}</h1>
									<div class="listing_files_block">
									{foreach from=$files item=file}
										{assign var="file_id" value=$file->id}
										<span class="listing_file_item" style="background: url('{$public_path}images/file_types/{$file->file_format}.png') 0 0 no-repeat;"><a href="{$VH->site_url("download/$file_id")}" target="_blank">{$file->title} ({$file->file_size})</a></span>
									{/foreach}
									</div>
								</div>
							{/if}
							
							{if $listing->level->reviews_mode && $listing->level->reviews_mode != 'disabled'}
								<div id="reviews-tab">
									{render_frontend_block
							      		block_type='reviews_comments'
							      		block_template='frontend/blocks/reviews_comments_add.tpl'
							      		objects_table='listings'
							      		objects_ids=$listing->id
							      		comment_area_enabled=true
							      		reviews_mode=$listing->level->reviews_mode
							      		admin_mode=false
							      		is_richtext=$listing->level->reviews_richtext_enabled
									}
								</div>
							{/if}
							
						{if $available_options_count > 1}
						</div>
						{/if}
					{else}
					<div class="error_msg rounded_corners">
						<ul>
							<li>{$LANG_LISTING_TRANSLATION_ERROR}</li>
						</ul>
					</div>
					{/if}
						
                 	</div>
                </td>
                <td id="right_sidebar">
                {include file="frontend/right-sidebar.tpl"}
                </td>
			</tr>

{include file="frontend/footer.tpl"}