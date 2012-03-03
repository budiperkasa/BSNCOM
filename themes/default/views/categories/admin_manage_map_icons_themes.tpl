{include file="backend/admin_header.tpl"}

                <div class="content">
                	 {$VH->validation_errors()}
                     <h3>{$LANG_MANAGE_MARKER_ICONS_THEMES_TITLE}</h3>

                     <form action="" method="post">
                     {foreach from=$themes item="theme_item"}
                     {assign var="folder_name" value=$theme_item.folder_name}
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	{$LANG_MARKER_ICONS_THEME_NAME}<span class="red_asterisk">*</span>
                          	{translate_content table='map_marker_icons_themes' field='name' row_id=$theme_item.id}
                          </div>
                          <input type="text" name="{$folder_name}" size="40" value="{$theme_item.name}"><br />
                          <a href="{$VH->site_url("admin/manage_map_icons/folder_name/$folder_name")}">{$LANG_MANAGE_MARKER_ICONS_LINK}</a>
                     </div>
                     {/foreach}

                     <input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}