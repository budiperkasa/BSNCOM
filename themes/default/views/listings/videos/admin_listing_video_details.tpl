{include file="backend/admin_header.tpl"}
{assign var="video_id" value=$video->id}

                <div class="content">
                	{include file="listings/admin_listing_options_menu.tpl"}
                
                    <h3>{if $video->id != 'new'}{$LANG_EDIT_VIDEO}{else}{$LANG_ATTACH_VIDEO}{/if}</h3>
                    
                    {if $video->id != 'new'}
                    <a href="{$VH->site_url("admin/listings/videos/delete/$video_id")}" title="{$LANG_BUTTON_VIDEO_DELETE}"><img src="{$public_path}/images/buttons/page_delete.png" /></a>
                    <a href="{$VH->site_url("admin/listings/videos/delete/$video_id")}">{$LANG_BUTTON_VIDEO_DELETE}</a>&nbsp;&nbsp;&nbsp;
                    {/if}
                    
                    <div class="px10"></div>

                    <form action="" method="post">
                    {if $video->id != 'new'}
                    <div class="admin_option">
                    	<div class="admin_option_name">
                    		{$LANG_STATUS_TH}: 
                    		{if $video->status == 'success'}{$LANG_VIDEO_STATUS_ACTIVE}{/if}
                    	  	{if $video->status == 'processing'}{$LANG_VIDEO_STATUS_PROCESSING}{/if}
                    	  	{if $video->status == 'restricted'}{$LANG_VIDEO_STATUS_RESTRICTED} ({$video->error_code}){/if}
                    	  	{if $video->status == 'rejected'}{$LANG_VIDEO_STATUS_REJECTED} ({$video->error_code}){/if}
                    	  	{if $video->status == 'failed'}{$LANG_VIDEO_STATUS_FAILED} ({$video->error_code}){/if}
                    	  	{if $video->status == 'error'}{$LANG_VIDEO_ERROR} ({$video->error_code}){/if}
                    	</div>
                    </div>
                    {/if}
                    {if $video->mode == 'attached'}
                    <div class="admin_option">
                    	<div class="admin_option_name">
                    		{$LANG_YOUTUBE_VIDEO_CODE}<span class="red_asterisk">*</span>
                    	</div>
                    	<input type="text" name="video_code" value="{$video->video_code}" size="12" />
                    </div>
                    {/if}
                    <div class="admin_option">
                    	<div class="admin_option_name">
                    		{$LANG_VIDEO_TITLE_1} <i>(255 {$LANG_VIDEO_TITLE_2})</i>
                    		{translate_content table='videos' field='title' row_id=$video_id}
                    	</div>
                    	<input type="text" name="title" value="{$video->title}" size="60" />
                    </div>
                    {if $video->id != 'new'}
                    <div class="admin_option">
                    	<div class="admin_option_name">
                    		<object width="{$listing->level->explodeSize('video_size', 'width')}" height="{$listing->level->explodeSize('video_size', 'height')}"><param name="movie" value="http://www.youtube.com/v/{$video->video_code}&hl=en&fs=1&"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/{$video->video_code}&hl=en&fs=1&" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="{$listing->level->explodeSize('video_size', 'width')}" height="{$listing->level->explodeSize('video_size', 'height')}"></embed></object>
                    	</div>
                    </div>
                    {/if}
                    <input type="submit" name="submit" class="button save_button" value="{if $video->id != 'new'}{$LANG_BUTTON_SAVE_CHANGES}{else}{$LANG_BUTTON_ATTACH}{/if}">
                    </form>
                </div>

{include file="backend/admin_footer.tpl"}