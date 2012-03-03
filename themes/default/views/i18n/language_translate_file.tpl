{include file="backend/admin_header.tpl"}

                <div class="content">
                     <h3>{$LANG_TRANSLATE_INTERFACE} "{$language->name}" {$LANG_TRANSLATE_INTERFACE_2} "{$file}"</h3>

                     <form action="" method="post">
                     {foreach from=$language_strings item=string key=key}
                     <div class="admin_option">
                          <div class="admin_option_name">{$key}</div>
                          {if $language_code != $default_language_code}<div class="admin_option_description">{$default_language_strings.$key}</div>{/if}
                          <input type="text" size="110" name="{$key}" value="{$string}">
                     </div>
                     {/foreach}
                     <input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}