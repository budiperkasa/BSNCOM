{include file="backend/admin_header.tpl"}

                <div class="content">
                	 {$VH->validation_errors()}
                     <h3>{$LANG_MANAGE_MARKER_ICONS_TITLE} "{$theme.name}"</h3>

                     <form action="" method="post">
                     {foreach from=$icons item="icon_item"}
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_MARKER_ICON_NAME}<span class="red_asterisk">*</span>
                          	{translate_content table='map_marker_icons' field='name' row_id=$icon_item.id}
                          </div>
                          <input type="text" name="{$icon_item.id}" size="40" value="{$icon_item.name}"><br />
                          <img src="{$public_path}map_icons/icons/{$theme.folder_name}/{$icon_item.file_name}" />
                     </div>
                     {/foreach}

                     <input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}