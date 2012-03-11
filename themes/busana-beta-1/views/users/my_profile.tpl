{include file="backend/admin_header.tpl"}

                <div class="content">
                	{$VH->validation_errors()}
                     <h3>{$LANG_MY_PROFILE}</h3>
                     <h4>Edit your login and account information, password and email.</h4>

                     <form action="" method="post">
                     <div class="admin_option">
                     	<div style="float: left;">
                     		<div class="admin_option_name" >
                     			{$LANG_USER_LOGIN}
                     		</div>
                     		<div class="admin_option_description">
                     			{$LANG_USER_LOGIN_DESCR}
                     		</div>
                     		<input type=text name="login" value="{$user->login}" size="50" class="admin_option_input">
                     		{if $user->users_group->is_own_page && $user->users_group->use_seo_name}
                     		&nbsp;&nbsp;<span class="seo_rewrite" title="{$LANG_WRITE_SEO_STYLE}"><img src="{$public_path}images/arrow_seo.gif"></span>&nbsp;&nbsp;
                     		{/if}
						</div>
						{if $user->users_group->is_own_page && $user->users_group->use_seo_name}
						<div style="float: left;">
							<div class="admin_option_name" >
								{$LANG_USER_SEO_LOGIN}
							</div>
							<div class="admin_option_description">
								{$LANG_SEO_DESCR}
							</div>
							<input type=text name="seo_login" id="seo_login" value="{$user->seo_login}" size="50" class="admin_option_input">
						</div>
						{/if}
						<div style="clear: both"></div>
					</div>
					{if $user->users_group->is_own_page && $user->users_group->meta_enabled}
					<div class="admin_option">
						<div class="admin_option_name">
							{$LANG_USER_META_DESCRIPTION}
							{translate_content table='users' field='meta_description' row_id=$user->id field_type='text'}
						</div>
						<div class="admin_option_description">
							{$LANG_USER_META_DESCRIPTION_DESCR}
						</div>
						<textarea name="meta_description" cols="40" rows="5">{$user->meta_description}</textarea>
						
						<div class="px10"></div>
						
						<div class="admin_option_name">
							{$LANG_USER_KEYWORDS}
							{translate_content table='users' field='meta_keywords' row_id=$user->id field_type='text'}
						</div>
						<div class="admin_option_description">
							{$LANG_USER_KEYWORDS_DESCR}
						</div>
						<textarea name="meta_keywords" cols="40" rows="5">{$user->meta_keywords}</textarea>
					</div>
					{/if}
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_USER_EMAIL}
                          </div>
                          <input type=text name="email" value="{$user->email}" size="50" class="admin_option_input">
                     </div>
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_PASSWORD}
                          </div>
                          <div class="admin_option_description">
                          	{$LANG_PASSWORD_DESCR}
	                      </div>
                          <input type=password name="password" size="50" class="admin_option_input">
                          <div class="admin_option_name">
                          	{$LANG_PASSWORD_REPEAT}
                          </div>
                          <input type=password name="repeat_password" size="50" class="admin_option_input">
                     </div>
                     {if $user->users_group->logo_enabled && $facebook_logo_file}
                     <div class="admin_option">
                     	<div class="admin_option_name" >
                          	{$LANG_FACEBOOK_USER_LOGO_IMAGE}
                        </div>
                     	<img src="{$facebook_logo_file}" />
                     	<div class="px5"></div>
                     	<label><input type="checkbox" name="use_facebook_logo" value="1" {if $user->use_facebook_logo}checked{/if} /> {$LANG_USE_FACEBOOK_LOGO_IMAGE}</label>
                     	<input type="hidden" name="facebook_logo_file" value="{$facebook_logo_file}"/>
                     </div>
                     {/if}
                     {if $user->users_group->logo_enabled}
                     	{$image_upload_block->setUploadBlock('files_upload/user_logo_upload_block.tpl')}
					 {/if}
                     {if $user->fieldsCount()}
	                     <label class="block_title">{$LANG_ADDITIONAL_INFORMATION}</label>
	                     <div class="admin_option">
	                     	{$user->inputMode()}
	                     </div>
                     {/if}
                     <input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}