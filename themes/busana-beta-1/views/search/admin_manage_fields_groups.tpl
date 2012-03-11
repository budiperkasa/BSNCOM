{include file="backend/admin_header.tpl"}

                <div class="content">
                     <h3>{$LANG_MANAGE_SEARCH_FIELDS_GROUPS_BY_TYPE}</h3>

                     {if $groups|@count > 0}
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th>{$LANG_NAME_TH}</th>
                         <th>{$LANG_SEARCH_MODE}</th>
                         <th>{$LANG_OPTIONS_TH}</th>
                       </tr>
                       {foreach from=$groups item=groups_item}
                       {assign var="groups_item_id" value=$groups_item.id}
                       <tr>
                         <td>
                             <a href="{$VH->site_url("admin/search/by_type/$groups_item_id")}">{$groups_item.name}</a>
                         </td>
                         <td>
                         	{if $groups_item.mode == 'quick'}{$LANG_QUICK_SEARCH_MODE}{else}{$LANG_ADVANCED_SEARCH_MODE}{/if}
                         </td>
                         <td>
                         	 <a href="{$VH->site_url("admin/search/by_type/$groups_item_id")}" title="{$LANG_MANAGE_CONTENT_FIELDS_IN_GROUP_OPTION}"><img src="{$public_path}images/buttons/page_green.png"></a>&nbsp;
                         </td>
                       </tr>
                       {/foreach}
                     </table>
                     {/if}
                </div>

{include file="backend/admin_footer.tpl"}