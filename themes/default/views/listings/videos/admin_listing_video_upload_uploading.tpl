{include file="backend/admin_header.tpl"}

                <div class="content">
                    <h3>{$LANG_VIDEO_UPLOAD}</h3>
                    <h4>{$LANG_VIDEO_UPLOAD_STEP2}</h4>

                    <form action="{$postUrl}?nexturl={$nextUrl}" enctype="multipart/form-data" method="post">
                    <input name="token" type="hidden" value="{$tokenValue}"/>
                    <div class="admin_option">
                    	<div class="admin_option_name">
                    		{$LANG_VIDEO_TITLE_1}
                    	</div>
                    	{$video_title}
                    </div>
                    <div class="admin_option">
                    	<div class="admin_option_name">
                    		{$LANG_VIDEO_FILE}
                    	</div>
                    	<input name="file" type="file"/>
                    </div>
                    <input type="submit" name="uploading" class="button save_button" value="{$LANG_BUTTON_UPLOAD_VIDEO}">
                    </form>
                </div>

{include file="backend/admin_footer.tpl"}