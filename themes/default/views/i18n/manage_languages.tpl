{include file="backend/admin_header.tpl"}

                <div class="content">
                	{$VH->validation_errors()}
                     <h3>{$LANG_MANAGE_LANGS}</h3>
                     <div class="admin_option_description">
                     	{$LANG_MANAGE_LANGS_DESCR}
                     </div>
                     <a href="{$VH->site_url("admin/languages/create")}" title="{$LANG_CREATE_LANG_OPTION}"><img src="{$public_path}/images/buttons/page_add.png"></a>
					 <a href="{$VH->site_url("admin/languages/create")}" title="{$LANG_CREATE_LANG_OPTION}">{$LANG_CREATE_LANG_OPTION}</a>&nbsp;&nbsp;&nbsp;&nbsp;

                     {if $languages|@count > 0}
                     <table id="drag_table" class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr class="nodrop nodrag">
                         <th>{$LANG_CODE_TH}</th>
                         <th>{$LANG_NAME_TH}</th>
                         <th>{$LANG_FLAG_TH}</th>
                         <th>{$LANG_OPTIONS_TH}</th>
                       </tr>
                       {foreach from=$languages item=language_item}
                       {assign var="language_item_id" value=$language_item.id}
                       <tr id="{$language_item.id}_id">
                         <td>
                             {$language_item.code}
                         </td>
                         <td>
                             <a href="{$VH->site_url("admin/languages/translate/$language_item_id")}" title="{$LANG_TRANSLATE_INTERFACE_OPTION}">{$language_item.name}</a>
                         </td>
                         <td>
                             <img src="{$public_path}images/flags/{$language_item.flag}">
                         </td>
                         <td>
                         	<nobr>
                         	 <a href="{$VH->site_url("admin/languages/translate/$language_item_id")}" title="{$LANG_TRANSLATE_INTERFACE_OPTION}"><img src="{$public_path}images/buttons/text_allcaps.png"></a>
                             <a href="{$VH->site_url("admin/languages/edit/$language_item_id")}" title="{$LANG_EDIT_LANG_OPTION}"><img src="{$public_path}images/buttons/page_edit.png"></a>
                             {if ($system_settings.default_language != $language_item.code) && ($language_item.code != 'en')}
                             <a href="{$VH->site_url("admin/languages/delete/$language_item_id")}" title="{$LANG_DELETE_LANG_OPTION}"><img src="{$public_path}images/buttons/page_delete.png"></a>
                            {/if}
                            </nobr>
                         </td>
                       </tr>
                       {/foreach}
                     </table>
                     <br/>
                     <form action="" method="post">
                     <input type="hidden" id="serialized_order" name="serialized_order"> 
                     <input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
                     </form>
                     {/if}
                </div>

{include file="backend/admin_footer.tpl"}