{include file="backend/admin_header.tpl"}

                <div class="content">
                     <h3>{$LANG_BANNERS_PRICE}</h3>
                     {if $banners_blocks|@count && $users_groups|@count}
                     <table class="presentationTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th>{$LANG_BANNERS_BLOCK_NAME_TH}</th>
                         {foreach from=$users_groups item=group}
                         <td class="td_header">{$group->name}</td>
                         {/foreach}
                       </tr>
                       {foreach from=$banners_blocks item=block}
                       {assign var="block_id" value=$block->id}
                       <tr>
                         <td class="td_header">
                         	{$block->name}
                         </td>
                         {foreach from=$users_groups item=group}
                         {assign var="group_id" value=$group->id}
                         <td>
                         	<a href="{$VH->site_url("admin/banners/payment/settings/group_id/$group_id/block_id/$block_id")}" title="{$LANG_BANNERS_PRICE_OPTION_1} '{$block->name}' {$LANG_BANNERS_PRICE_OPTION_2} '{$group->name}'">{if $prices[$block_id][$group_id].value == null || $prices[$block_id][$group_id].value == 0}{$LANG_FREE}{else}{$prices[$block_id][$group_id].currency} {$VH->number_format($prices[$block_id][$group_id].value, 2, $decimals_separator, $thousands_separator)}{/if}</a>
                         </td>
                         {/foreach}
                       </tr>
                       {/foreach}
                     </table>
                     {/if}
                </div>

{include file="backend/admin_footer.tpl"}