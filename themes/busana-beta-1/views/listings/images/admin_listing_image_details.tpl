{include file="backend/admin_header.tpl"}
{assign var="image_id" value=$image->id}

                <div class="content">
                	{include file="listings/admin_listing_options_menu.tpl"}

                    <h3>{$LANG_IMAGE_DETAILS}</h3>
                    
                    {if $image->id != 'new'}
                    <a href="{$VH->site_url("admin/listings/images/delete/$image_id")}" title="{$LANG_BUTTON_DELETE_IMAGE}"><img src="{$public_path}/images/buttons/page_delete.png" /></a>
                    <a href="{$VH->site_url("admin/listings/images/delete/$image_id")}">{$LANG_BUTTON_DELETE_IMAGE}</a>&nbsp;&nbsp;&nbsp;
                    {/if}
                    
                    <div class="px10"></div>

                    <form action="" method="post">
                    <div class="admin_option">
                    	<div class="admin_option_name">
                    		{$LANG_IMAGE_DATE}
                    	</div>
                    	<div class="admin_option_description">
                    		{$image->creation_date|date_format:"%D %T"}
                    	</div>
                    </div>
                    <div class="admin_option">
                    	<img src="{$users_content}/users_images/images/{$image->file}">
                    </div>
                    <div class="admin_option">
                    	<div class="admin_option_name">
                    		{$LANG_IMAGE_TITLE_1} <i>(255 {$LANG_IMAGE_TITLE_2})</i>
                    		{translate_content table='images' field='title' row_id=$image_id}
                    	</div>
                    	<input type="text" name="title" value="{$image->title}" size="60" />
                    </div>
                    <input type="submit" name="submit" class="button save_button" value="{$LANG_BUTTON_SAVE_CHANGES}">&nbsp;&nbsp;
                    </form>
                </div>

{include file="backend/admin_footer.tpl"}