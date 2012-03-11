{include file="backend/admin_header.tpl"}

                <div class="content">
                     <h3>{$LANG_JSADVERTISEMENT_ALL_BLOCKS}</h3>
                     <a href="{$VH->site_url("admin/js_advertisement/create")}" title="{$LANG_CREATE_JSADVERTISEMENT_BLOCK}"><img src="{$public_path}/images/buttons/page_add.png"></a>
                     <a href="{$VH->site_url("admin/js_advertisement/create")}">{$LANG_CREATE_JSADVERTISEMENT_BLOCK}</a>&nbsp;&nbsp;&nbsp;&nbsp;

                     {if $js_advertisement_blocks|@count > 0}
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th>{$LANG_JSADVERTISEMENT_BLOCK_NAME_TH}</th>
                         <th>{$LANG_JSADVERTISEMENT_BLOCK_MODE_TH}</th>
                         <th>{$LANG_JSADVERTISEMENT_BLOCK_SELECTOR_TH}</th>
                         <th>{$LANG_OPTIONS_TH}</th>
                       </tr>
                       {foreach from=$js_advertisement_blocks item=js_advertisement_item}
                       {assign var="js_advertisement_id" value=$js_advertisement_item.id}
                       <tr>
                         <td>
                             <a href="{$VH->site_url("admin/js_advertisement/edit/$js_advertisement_id")}">{$js_advertisement_item.name}</a>
                         </td>
                         <td>
                             {$js_advertisement_item.mode}
                         </td>
                         <td>
                             {$js_advertisement_item.selector}
                         </td>
                         <td>
                             <a href="{$VH->site_url("admin/js_advertisement/edit/$js_advertisement_id")}" title="{$LANG_EDIT_JSADVERTISEMENT_BLOCK}"><img src="{$public_path}images/buttons/page_edit.png"></a>&nbsp;
                             <a href="{$VH->site_url("admin/js_advertisement/delete/$js_advertisement_id")}" title="{$LANG_DELETE_JSADVERTISEMENT_BLOCK}"><img src="{$public_path}images/buttons/page_delete.png"></a>
                         </td>
                       </tr>
                       {/foreach}
                     </table>
                     {/if}
                </div>

{include file="backend/admin_footer.tpl"}