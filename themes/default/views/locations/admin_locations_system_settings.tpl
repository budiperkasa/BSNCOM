{include file="backend/admin_header.tpl"}

                <div class="content">
                	 {$VH->validation_errors()}
                     <h3>{$LANG_LOCATIONS_SETTINGS_TITLE}</h3>

                     <form action="" method="post">
                     <div class="admin_option">
                          <div class="admin_option_name" >
                          	{$LANG_PREDEFINED_LOCATIONS_MODE}<span class="red_asterisk">*</span>
                          </div>
                          <label><input type="radio" name="predefined_locations_mode" value="disabled" {if $settings.predefined_locations_mode == 'disabled'}checked{/if} class="admin_option_input"> {$LANG_PREDEFINED_LOCATIONS_MODE_DISABLED}</label>
                          <label><input type="radio" name="predefined_locations_mode" value="only" {if $settings.predefined_locations_mode == 'only'}checked{/if} class="admin_option_input"> {$LANG_PREDEFINED_LOCATIONS_MODE_ONLY}</label>
                          <label><input type="radio" name="predefined_locations_mode" value="global" {if $settings.predefined_locations_mode == 'global'}checked{/if} class="admin_option_input"> {$LANG_PREDEFINED_LOCATIONS_MODE_GLOBAL}</label>
                          <label><input type="radio" name="predefined_locations_mode" value="prefered" {if $settings.predefined_locations_mode == 'prefered'}checked{/if} class="admin_option_input"> {$LANG_PREDEFINED_LOCATIONS_MODE_PREFERED}</label>
                          <label><input type="radio" name="predefined_locations_mode" value="hide" {if $settings.predefined_locations_mode == 'hide'}checked{/if} class="admin_option_input"> {$LANG_PREDEFINED_LOCATIONS_MODE_HIDE}</label>
                     </div>
                     
                     <div class="admin_option">
                          <div class="admin_option_name" >
                          	{$LANG_GEOCODED_LOCATIONS_MODE}
                          </div>
                          <label><input type="checkbox" name="geocoded_locations_mode_districts" value="use_districts" {if $settings.geocoded_locations_mode_districts}checked{/if} class="admin_option_input"> {$LANG_PREDEFINED_LOCATIONS_MODE_UDE_DISTRICTS}</label>
                          <label><input type="checkbox" name="geocoded_locations_mode_provinces" value="use_provinces" {if $settings.geocoded_locations_mode_provinces}checked{/if} class="admin_option_input"> {$LANG_PREDEFINED_LOCATIONS_MODE_UDE_PROVINCES}</label>
                     </div>

                     <input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}