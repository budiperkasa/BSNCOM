{include file="backend/admin_header.tpl"}
{assign var="file_id" value=$file->id}

                <div class="content">
                	{include file="listings/admin_listing_options_menu.tpl"}

                    <h3>{$LANG_FILE_DETAILS}</h3>
                    
                    {if $file->id != 'new'}
                    <div class="admin_top_menu_cell">
	                    <a href="{$VH->site_url("admin/listings/files/delete/$file_id")}" title="{$LANG_BUTTON_DELETE_FILE}"><img src="{$public_path}/images/buttons/page_delete.png" /></a>
	                    <a href="{$VH->site_url("admin/listings/files/delete/$file_id")}">{$LANG_BUTTON_DELETE_FILE}</a>&nbsp;&nbsp;&nbsp;
					</div>
					<div class="clear_float"></div>
                    {/if}

                    <form action="" method="post">
                    <div class="admin_option">
                    	<div class="admin_option_name">
                    		{$LANG_FILE_DATE}
                    	</div>
                    	<div class="admin_option_description">
                    		{$file->creation_date|date_format:"%D %T"}
                    	</div>
                    </div>
                    <div class="admin_option">
                    	<div class="admin_option_name">
                    		{$LANG_FILE_FORMAT}
                    	</div>
                    	<div class="admin_option_description">
                    		{$file->file_format}
                    	</div>
                    </div>
                    <div class="admin_option">
                    	<div class="admin_option_name">
                    		{$LANG_FILE_TITLE_1} <i>(255 {$LANG_FILE_TITLE_2})</i>
                    		{translate_content table='files' field='title' row_id=$file_id}
                    	</div>
                    	<input type="text" name="title" value="{$file->title}" size="60" />
                    </div>
                    <input type="submit" name="submit" class="button save_button" value="{$LANG_BUTTON_SAVE_CHANGES}">&nbsp;&nbsp;
                    </form>
                </div>

{include file="backend/admin_footer.tpl"}