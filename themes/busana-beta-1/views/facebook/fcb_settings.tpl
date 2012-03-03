					<label class="block_title">{$LANG_FACEBOOK_SETTINGS}</label>
					<div class="admin_option">
						<div class="admin_option_name">
							{$LANG_FACEBOOK_APP_ID}
						</div>
						<div class="admin_option_description">
							{$LANG_FACEBOOK_API_KEY_SIGNUP}
						</div>
						<input type=text name="facebook_app_id" value="{$system_settings.facebook_app_id}" size="80" />

						<div class="admin_option_name">
							{$LANG_FACEBOOK_API_KEY}
						</div>
						<input type=text name="facebook_api_key" value="{$system_settings.facebook_api_key}" size="80" />
						
						<div class="admin_option_name">
							{$LANG_FACEBOOK_APP_SECRET}
						</div>
						<input type=text name="facebook_app_secret" value="{$system_settings.facebook_app_secret}" size="80" />
					</div>