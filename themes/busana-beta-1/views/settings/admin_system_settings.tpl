{include file="backend/admin_header.tpl"}

				<div class="content">
					{$VH->validation_errors()}
					<h3>{$LANG_SYSTEM_SETTINGS}</h3>
					<form action="" method="post">
					{$image_upload_block}
					<div class="admin_option">
						<div class="admin_option_name">
							{$LANG_SETTINGS_TYPE_STRUCTURE}
						</div>
						<label><input type="radio" name="single_type_structure" value="0" {if $system_settings.single_type_structure == 0}checked{/if} /> {$LANG_SETTINGS_TYPE_STRUCTURE_MULTI}</label>
						<label><input type="radio" name="single_type_structure" value="1" {if $system_settings.single_type_structure == 1}checked{/if} /> {$LANG_SETTINGS_TYPE_STRUCTURE_SINGLE}</label>
						<i>{$LANG_SETTINGS_TYPE_STRUCTURE_SINGLE_NOTE}</i>
					</div>
					<div class="admin_option">
						<div class="admin_option_name">
							{$LANG_WEBSITE_EMAIL}<span class="red_asterisk">*</span>
						</div>
						<div class="admin_option_description">
							{$LANG_WEBSITE_EMAIL_DESCR}
						</div>
						<input type=text name="website_email" value="{$system_settings.website_email}" size="40" />
					</div>
					<div class="admin_option">
						<div class="admin_option_name">
							{$LANG_CURRENT_THEME}<span class="red_asterisk">*</span>
						</div>
						<select name="design_theme">
							<option value="-1" {if $system_settings.design_theme == -1}selected{/if}>{$LANG_SELECT_THEME}</option>
							{foreach from=$themes_list item=item}
								<option value="{$item}" {if $system_settings.design_theme == $item}selected{/if}>{$item}</option>
							{/foreach}
						</select>
					</div>
					<div class="admin_option">
						<div class="admin_option_name">
							{$LANG_ENABLE_WHAT_SEARCH}
						</div>
						<label><input type="checkbox" name="global_what_search" value="1" {if $system_settings.global_what_search == 1}checked{/if} /> {$LANG_ENABLE}</label>
					</div>
					<div class="admin_option">
						<div class="admin_option_name">
							{$LANG_ENABLE_WHERE_SEARCH}
						</div>
						<label><input type="checkbox" name="global_where_search" value="1" {if $system_settings.global_where_search == 1}checked{/if} /> {$LANG_ENABLE}</label>
					</div>
					<div class="admin_option">
						<div class="admin_option_name">
							{$LANG_ENABLE_CATEGORIES_SEARCH}
						</div>
						<label><input type="checkbox" name="global_categories_search" value="1" {if $system_settings.global_categories_search == 1}checked{/if} /> {$LANG_ENABLE}</label>
					</div>
					<div class="admin_option">
						<div class="admin_option_name">
							{$LANG_DEFAULT_SEARCH_IN_RADIUS_MEASURE}
						</div>
						<label><input type="radio" name="search_in_raduis_measure" value="miles" {if $system_settings.search_in_raduis_measure == 'miles'}checked{/if} /> {$LANG_SEARCH_IN_RADIUS_MILES}</label>
						<label><input type="radio" name="search_in_raduis_measure" value="kilometres" {if $system_settings.search_in_raduis_measure == 'kilometres'}checked{/if} /> {$LANG_SEARCH_IN_RADIUS_KILOMETRES}</label>
					</div>
					<div class="admin_option">
						<div class="admin_option_name">
							{$LANG_ENABLE_ANONYM_RATINGS_REVIEWS}
						</div>
						<label><input type="checkbox" name="anonym_rates_reviews" value="1" {if $system_settings.anonym_rates_reviews == 1}checked{/if} /> {$LANG_ENABLE}</label>
					</div>
					<div class="admin_option">
						<div class="admin_option_name">
							{$LANG_CATEGORIES_BLOCK_VIEW}
						</div>
						<label><input type="radio" name="categories_block_type" value="categories-bar" {if $system_settings.categories_block_type == 'categories-bar'}checked{/if} /> {$LANG_NORMAL_CATEGORIES_BAR}</label>
						<label><input type="radio" name="categories_block_type" value="categories-bar-jshide" {if $system_settings.categories_block_type == 'categories-bar-jshide'}checked{/if} /> {$LANG_JSHIDE_CATEGORIES_BAR}</label>
						<label><input type="radio" name="categories_block_type" value="categories-bar-ajax" {if $system_settings.categories_block_type == 'categories-bar-ajax'}checked{/if} /> {$LANG_AJAX_CATEGORIES_BAR}</label>
					</div>
					<div class="admin_option">
						<div class="admin_option_name">
							{$LANG_ENABLE_CONTACTUS_PAGE}
						</div>
						<label><input type="checkbox" name="enable_contactus_page" value="1" {if $system_settings.enable_contactus_page == 1}checked{/if} /> {$LANG_ENABLE}</label>
					</div>
					<div class="admin_option">
						<div class="admin_option_name">
							{$LANG_PATH_TO_TERMS_CONDITIONS}
						</div>
						<div class="admin_option_description">
							{$LANG_PATH_TO_TERMS_CONDITIONS_DESCR}
						</div>
						<input type="text" name="path_to_terms_and_conditions" value="{$system_settings.path_to_terms_and_conditions}" size="60" />
					</div>

					<input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
					&nbsp;&nbsp;
					<input class="button delete_button" type=submit name="clear_cache" value="{$LANG_BUTTON_CLEAR_CACHE}">
					&nbsp;&nbsp;
					<input class="button activate_button" type=submit name="synchronize_users_content" value="{$LANG_BUTTON_SYNCHRONIZE_USERS_CONTENT}">
					</form>
				</div>

{include file="backend/admin_footer.tpl"}