{include file="backend/admin_header.tpl"}

                <div class="content">
                	{$VH->validation_errors()}
                     <h3>{$LANG_CALENDAR_SETTINGS_TITLE}</h3>

                     <form action="" method="post">
                     <div class="admin_option">
                          <div class="admin_option_name" >
                          	{$LANG_CALENDAR_TYPE}<span class="red_asterisk">*</span>
                          </div>
                          <select name="connected_type_select" onChange="this.form.submit()">
                          	<option value="0" {if $connected_type_id==0}selected{/if}>{$LANG_CALENDAR_ANY_TYPE}</option>
                          	{foreach from=$types item=type}
                          		<option value="{$type->id}" {if $type->id==$connected_type_id}selected{/if}>{$type->name}</option>
                          	{/foreach}
                          </select>
                     </div>
                     </form>

                     <form action="" method="post">
                     <input type="hidden" name="connected_type_id" value="{$connected_type_id}" />
                     <div class="admin_option">
                          <div class="admin_option_name" >
                          	{$LANG_CALENDAR_NAME}<span class="red_asterisk">*</span>
                          	{translate_content table='calendar_settings' field='name' row_id=1}
                          </div>
                          <input type=text name="name" value="{$settings.name}" size="40" class="admin_option_input">
                     </div>
                     <div class="admin_option">
                          <div class="admin_option_name" >
                          	{$LANG_CALENDAR_FIELD}<span class="red_asterisk">*</span>
                          </div>
                          <select name="connected_field">
                          	<option value="search_creation_date" {if $settings.connected_field=='search_creation_date'}selected{/if}>{$LANG_CALENDAR_SEARCH_BY_CREATION_DATE}</option>
                          	{foreach from=$search_fields item="field_item"}
                          		<option value="{$field_item.field_id}" {if $settings.connected_field==$field_item.field_id}selected{/if}>{$field_item.name}</option>
                          	{/foreach}
                          </select>
                     </div>
                     <div class="admin_option">
                          <div class="admin_option_name" >
                          	{$LANG_CALENDAR_VISIBILITY_INDEX}
                          </div>
                          <label><input type="radio" name="visibility_on_index" value="1" {if $settings.visibility_on_index}checked{/if}/> {$LANG_VISIBILE}</label>
                          <label><input type="radio" name="visibility_on_index" value="0" {if !$settings.visibility_on_index}checked{/if}/> {$LANG_NOT_VISIBILE}</label>
                     </div>
                     <div class="admin_option">
                          <div class="admin_option_name" >
                          	{$LANG_CALENDAR_VISIBILITY_FOR_TYPES}
                          </div>
                          <label><input type="radio" name="visibility_for_all_types" value="1" {if $settings.visibility_for_all_types}checked{/if}/> {$LANG_CALENDAR_VISIBILITY_FOR_ALL_TYPES}</label>
                          <label><input type="radio" name="visibility_for_all_types" value="0" {if !$settings.visibility_for_all_types}checked{/if}/> {$LANG_CALENDAR_VISIBILITY_FOR_CONNECTED_TYPE}</label>
                     </div>

                     <input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}