{include file="backend/admin_header.tpl"}

                <div class="content">
                     <h3>{$LANG_TRANSLATE_INTERFACE} "{$language->name}"</h3>
                     
                     <form method="post" action="{$VH->site_url("admin/languages/languages_translate_search_text/$language_id")}">
                     <div class="admin_option">
                          <div class="admin_option_name">{$LANG_LANG_SEARCH_TEXT}</div>
                          <div class="admin_option_description">{$LANG_LANG_SEARCH_TEXT_DESCR}</div>
                          <input type="text" size="110" name="search_text" value="" />&nbsp;&nbsp;
                          <input type="submit" name="submit" value="Search">
                     </div>
                     </form>

                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                     	<tr>
                         <th>{$LANG_LANG_MODULE_TH}</th>
                         <th>{$LANG_LANG_FILE_TH}</th>
                        </tr>
                        {foreach from=$files_list item=item}
                        {assign var="item_id" value=$item.id}
                        <tr>
                          <td>
                          	{$item.module}
                          </td>
                          <td>
                          	<a href="{$VH->site_url("admin/languages/translate/$language_id/file_id/$item_id")}">{$item.file}</a>
                          </td>
                        </tr>
                        {/foreach}
                      </table>
                </div>

{include file="backend/admin_footer.tpl"}