{include file="backend/admin_header.tpl"}

				<div class="content">
					{$VH->validation_errors()}
					<h3>{$LANG_SETTINGS_LISTINGS_CREATION_MODE}</h3>
					<form action="" method="post">
					<div class="admin_option">
						<label><input type="radio" name="packages_listings_creation_mode" value="standalone_mode" {if $system_settings.packages_listings_creation_mode == 'standalone_mode'}checked{/if} /> {$LANG_SETTINGS_LISTINGS_CREATION_MODE_STANDALONE}</label>
						<label><input type="radio" name="packages_listings_creation_mode" value="memberships_mode" {if $system_settings.packages_listings_creation_mode == 'memberships_mode'}checked{/if} /> {$LANG_SETTINGS_LISTINGS_CREATION_MODE_MEMBERSHIPS}</label>
						<label><input type="radio" name="packages_listings_creation_mode" value="both_mode" {if $system_settings.packages_listings_creation_mode == 'both_mode'}checked{/if} /> {$LANG_SETTINGS_LISTINGS_CREATION_MODE_BOTH}</label>
					</div>

					<input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
					</form>
				</div>

{include file="backend/admin_footer.tpl"}