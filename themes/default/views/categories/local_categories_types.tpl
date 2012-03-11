{include file="backend/admin_header.tpl"}

                <div class="content">
                     <h3>{$LANG_CHOOSE_TYPE_OF_CATEGORIES_TITLE}</h3>

                     {if $types|@count > 0}
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th>{$LANG_TYPES_TH}</th>
                         <th>{$LANG_OPTIONS_TH}</th>
                       </tr>
                       {foreach from=$types item=types_item}
                       {assign var="types_item_id" value=$types_item.id}
                       <tr>
                         <td>
                             <a href="{$VH->site_url("admin/categories/by_type/$types_item_id")}">{$types_item.name}</a>
                         </td>
                         <td>
                         	 <a href="{$VH->site_url("admin/categories/by_type/$types_item_id")}" title="{$LANG_MANAGE_CATEGORIES_BY_TYPE}"><img src="{$public_path}images/buttons/page_green.png"></a>&nbsp;
                         </td>
                       </tr>
                       {/foreach}
                     </table>
                     {/if}
                </div>

{include file="backend/admin_footer.tpl"}