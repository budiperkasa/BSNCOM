{include file="backend/admin_header.tpl"}

				<div class="content">
					{$VH->validation_errors()}
					<h3>{$LANG_WEBSERVICES}</h3>
					<form action="" method="post">

					<label class="block_title">{$LANG_YOUTUBE_SETTINGS}</label>
					<div class="admin_option">
						<div class="admin_option_name">
							{$LANG_YOUTUBE_KEY}
						</div>
						<div class="admin_option_description">
							<a href="http://code.google.com/apis/youtube/dashboard/" target="_blank">{$LANG_SIGNUP_SERVICES_LINK}</a> {$LANG_FOR_A_YOUTUBE}
						</div>
						<input type=text name="youtube_key" value="{$system_settings.youtube_key}" size="80" />
						<br/>
						<br/>
						<div class="admin_option_name">
							{$LANG_YOUTUBE_USERNAME}
						</div>
						<input type="text" name="youtube_username" value="{$system_settings.youtube_username}" size="45" />
						<div class="admin_option_name">
							{$LANG_YOUTUBE_PASSWORD}
						</div>
						<input type="text" name="youtube_password" value="{$system_settings.youtube_password}" size="45" />
						<br />
						<br />
						<div class="admin_option_name">
							{$LANG_YOUTUBE_PRODUCT}
						</div>
						<input type="text" name="youtube_product_name" value="{$system_settings.youtube_product_name}" size="60" />
					</div>

					<label class="block_title">{$LANG_ANALYTICS_SETTINGS}</label>
					<div class="admin_option">
						<div class="admin_option_description">
							<a href="https://www.google.com/accounts/NewAccount" target="_blank">{$LANG_ANALYTICS_ID_LINK}</a>
						</div>
						<div class="admin_option_name">
							{$LANG_ANALYTICS_ACCOUNT_ID}
						</div>
						<input type=text name="google_analytics_account_id" value="{$system_settings.google_analytics_account_id}" size="14" />
						<div class="admin_option_name">
							{$LANG_ANALYTICS_PROFILE_ID}
						</div>
						<input type=text name="google_analytics_profile_id" value="{$system_settings.google_analytics_profile_id}" size="8" />
						<br/>
						<br/>
						<div class="admin_option_name">
							{$LANG_ANALYTICS_EMAIL}
						</div>
						<input type="text" name="google_analytics_email" value="{$system_settings.google_analytics_email}" size="45" />
						<div class="admin_option_name">
							{$LANG_ANALYTICS_PASSWORD}
						</div>
						<input type="text" name="google_analytics_password" value="{$system_settings.google_analytics_password}" size="45" />
					</div>
					
					<label class="block_title">{$LANG_MOLLOM_SETTINGS}</label>
					<div class="admin_option">
						<div class="admin_option_description">
							{$LANG_MOLLOM_ACCOUNT_DESCR}
						</div>
						<div class="admin_option_name">
							{$LANG_MOLLOM_PUBLIC_KEY}
						</div>
						<input type=text name="mollom_public_key" value="{$system_settings.mollom_public_key}" size="45" />
						<div class="admin_option_name">
							{$LANG_MOLLOM_PRIVATE_KEY}
						</div>
						<input type="text" name="mollom_private_key" value="{$system_settings.mollom_private_key}" size="45" />
					</div>

					<input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
					</form>
				</div>

{include file="backend/admin_footer.tpl"}