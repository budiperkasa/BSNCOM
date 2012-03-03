{include file="backend/admin_header.tpl"}

				<div class="content">
					{$VH->validation_errors()}
					<h3>{$LANG_SITE_SETTINGS}</h3>
					<form action="" method="post">
					<div class="admin_option">
						<div class="admin_option_name">
							{$LANG_WEBSITE_TITLE}<span class="red_asterisk">*</span>
							{translate_content table='site_settings' field='value' row_id=1}
						</div>
						<div class="admin_option_description">
							{$LANG_WEBSITE_TITLE_DESCR}
						</div>
						<input type=text name="website_title" value="{$site_settings.website_title}" size="100" />
					</div>
					<div class="admin_option">
						<div class="admin_option_name">
							{$LANG_WEBSITE_DESCRIPTION}
							{translate_content table='site_settings' field='value' row_id=2 field_type='text'}
						</div>
						<div class="admin_option_description">
							{$LANG_WEBSITE_DESCRIPTION_DESCR}
						</div>
						<textarea name="description" cols="60" rows="5">{$site_settings.description}</textarea>
					</div>
					<div class="admin_option">
						<div class="admin_option_name">
							{$LANG_WEBSITE_KEYWORDS}
							{translate_content table='site_settings' field='value' row_id=3 field_type='keywords'}
						</div>
						<div class="admin_option_description">
							{$LANG_WEBSITE_KEYWORDS_DESCR}
						</div>
						<textarea name="keywords" cols="40" rows="5">{$keywords}</textarea>
					</div>
					<div class="admin_option">
						<div class="admin_option_name">
							{$LANG_EMAILS_SIGNATURE}
							{translate_content table='site_settings' field='value' row_id=5}
						</div>
						<div class="admin_option_description">
							{$LANG_EMAILS_SIGNATURE_DESCR}
						</div>
						<input type="text" name="signature_in_emails" value="{$system_settings.signature_in_emails}" size="100" />
					</div>
					{if $CI->load->is_module_loaded('payment')}
					<div class="admin_option">
						<div class="admin_option_name">
							{$LANG_COMPANY_DETAILS}
							{translate_content table='site_settings' field='value' row_id=4 field_type='text'}
						</div>
						<div class="admin_option_description">
							{$LANG_COMPANY_DETAILS_DESCR}
						</div>
						<textarea name="company_details" rows="7">{$site_settings.company_details}</textarea>
					</div>
					{/if}

					<input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
					</form>
				</div>

{include file="backend/admin_footer.tpl"}