{include file="backend/admin_header.tpl"}

                <div class="content">
                	{$VH->validation_errors()}
                    <h3>{$LANG_VIDEO_UPLOAD}</h3>
                    <h4>{$LANG_VIDEO_UPLOAD_STEP1}</h4>

                    <form action="" method="post">
                    <div class="admin_option">
                    	<div class="admin_option_name">
                    		{$LANG_VIDEO_TITLE_1} <i>(255 {$LANG_VIDEO_TITLE_2})</i><span class="red_asterisk">*</span>
                    		{translate_content table='videos' field='title' row_id='new'}
                    	</div>
                    	<input type="text" name="title" value="{$CI->form_validation->set_value('title')}" size="60" />
                    </div>
                    <div class="admin_option">
                    	<div class="admin_option_name">
                    		{$LANG_VIDEO_DESCRIPTION}<span class="red_asterisk">*</span>
                    	</div>
                    	<textarea name="description" cols="60" rows="4">{$CI->form_validation->set_value('description')}</textarea>
                    </div>
                    <div class="admin_option">
                    	<div class="admin_option_name">
                    		{$LANG_VIDEO_CATEGORY}<span class="red_asterisk">*</span>
                    	</div>
                    	<select name="category">
									<option value="Autos" {if $CI->form_validation->set_value('category') == 'Autos'}selected{/if}>Autos & Vehicles</option>
									<option value="Comedy" {if $CI->form_validation->set_value('category') == 'Comedy'}selected{/if}>Comedy</option>
									<option value="Education" {if $CI->form_validation->set_value('category') == 'Education'}selected{/if}>Education</option>
									<option value="Entertainment" {if $CI->form_validation->set_value('category') == 'Entertainment'}selected{/if}>Entertainment</option>
									<option value="Film" {if $CI->form_validation->set_value('category') == 'Film'}selected{/if}>Film & Animation</option>
									<option value="Games" {if $CI->form_validation->set_value('category') == 'Games'}selected{/if}>Gaming</option>
									<option value="Howto" {if $CI->form_validation->set_value('category') == 'Howto'}selected{/if}>Howto & Style</option>
									<option value="Music" {if $CI->form_validation->set_value('category') == 'Music'}selected{/if}>Music</option>
									<option value="News" {if $CI->form_validation->set_value('category') == 'News'}selected{/if}>News & Politics</option>
									<option value="Nonprofit" {if $CI->form_validation->set_value('category') == 'Nonprofit'}selected{/if}>Nonprofits & Activism</option>
									<option value="People" {if $CI->form_validation->set_value('category') == 'People'}selected{/if}>People & Blogs</option>
									<option value="Animals" {if $CI->form_validation->set_value('category') == 'Animals'}selected{/if}>Pets & Animals</option>
									<option value="Tech" {if $CI->form_validation->set_value('category') == 'Tech'}selected{/if}>Science & Technology</option>
									<option value="Sports" {if $CI->form_validation->set_value('category') == 'Sports'}selected{/if}>Sports</option>
									<option value="Travel" {if $CI->form_validation->set_value('category') == 'Travel'}selected{/if}>Travel & Events</option>
						</select>
                    </div>
                    <div class="admin_option">
                    	<div class="admin_option_name">
                    		{$LANG_VIDEO_TAGS}<span class="red_asterisk">*</span>
                    	</div>
                    	<input type="text" name="tags" value="{$CI->form_validation->set_value('tags')}" size="60" />
                    </div>
                    <input type="submit" name="details" class="button save_button" value="{$LANG_BUTTON_ENTER_VIDEO}">
                    </form>
                </div>

{include file="backend/admin_footer.tpl"}