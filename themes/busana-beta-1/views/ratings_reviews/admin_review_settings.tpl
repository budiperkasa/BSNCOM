{include file="backend/admin_header.tpl"}

                <div class="content">
                	{$VH->validation_errors()}
                    <h3>{$LANG_EDIT_REVIEWS_TITLE}</h3>

                    <form action="" method="post">
                    <div class="admin_option">
                    	<div class="admin_option_name">
                    		{$LANG_REVIEW_BODY}<span class="red_asterisk">*</span>
                    	</div>
                    	{if $review->is_richtext}
                    		{$review->body()}
                    	{else}
                    		<textarea name="review_body" style="width:100%;" rows="6">{$review->body()}</textarea>
                    	{/if}
                    </div>
                    <input type="submit" name="submit" class="button save_button" value="{$LANG_BUTTON_SAVE_CHANGES}">&nbsp;&nbsp;
                    </form>
                </div>

{include file="backend/admin_footer.tpl"}