{include file="backend/admin_header.tpl"}

                <div class="content">
                	{$VH->validation_errors()}
                    <h3>{$LANG_I18N_SETTINGS_TITLE}</h3>

                    <form action="" method="post">
					<div class="admin_option">
						<div class="admin_option_name">
							{$LANG_DEFUALT_LANGUAGE}
						</div>
						<div class="admin_option_description">
							{$LANG_DEFUALT_LANGUAGE_DESCR}
						</div>
						<select name="default_language">
							{foreach from=$languages_list item=language}
								<option value="{$language.code}" {if $system_settings.default_language == $language.code}selected{/if}>{$language.name}</option>
							{/foreach}
						</select>
					</div>
					<div class="admin_option">
						<div class="admin_option_name">
						{$LANG_ENABLE_MULTILANGUAGE}
						</div>
						<div class="admin_option_description">
							{$LANG_ENABLE_MULTILANGUAGE_DESCR}
						</div>
						<label><input type="checkbox" name="multilanguage_enabled" value="1" {if $system_settings.multilanguage_enabled == 1}checked{/if} /> {$LANG_ENABLE}</label>
					</div>
					<div class="admin_option">
						<div class="admin_option_name">
						{$LANG_ENABLE_LANG_AREAS}
						</div>
						<div class="admin_option_description">
							{$LANG_ENABLE_LANG_AREAS_DESCR}
						</div>
						<label><input type="checkbox" name="language_areas_enabled" value="1" {if $system_settings.language_areas_enabled == 1}checked{/if} /> {$LANG_ENABLE}</label>
					</div>
					
					<input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
                    </form>
				</div>

{include file="backend/admin_footer.tpl"}