{include file="backend/admin_header.tpl"}

                <div class="content">
                     <h3>{$LANG_TRANSLATE_INTERFACE} "{$language->name}"</h3>

					<form method="post" action="">
                    <div class="admin_option">
                         <div class="admin_option_name">{$LANG_LANG_SEARCH_TEXT}</div>
                          <div class="admin_option_description">{$LANG_LANG_SEARCH_TEXT_DESCR}</div>
                         <input type="text" size="110" name="search_text" value="{$search_text}" />&nbsp;&nbsp;
                         <input type="submit" name="submit" value="Search">
                    </div>
                    </form>
                    
                    <br />
                    <br />

					{if $language_strings|@count}
                    <form action="{$VH->site_url("admin/languages/translate_text/$language_id")}" method="post">
                    {foreach from=$language_strings item=string key=key}
                    <div class="admin_option">
                         <div class="admin_option_name">{$key}</div>
                         {if $language_code != $default_language_code}<div class="admin_option_description">{$default_language_strings.$key}</div>{/if}
                         {if $string|strlen > 110}
                         	<textarea rows=5 name="{$key}">{$string}</textarea>
                         {else}
                         	<input type="text" size="110" name="{$key}" value="{$string}">
                         {/if}
                    </div>
                    {/foreach}
                    <input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
                    </form>
                    {/if}
                </div>

{include file="backend/admin_footer.tpl"}