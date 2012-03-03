{include file="backend/admin_header.tpl"}

                <div class="content">
                     <h3>{$LANG_PACKAGES_PRICES_TITLE}</h3>
                     {if $packages|@count && $users_groups|@count}
                     <table class="presentationTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th>{$LANG_PACKAGE_NAME} \ {$LANG_USERS_GROUP_TH}</th>
                         {foreach from=$users_groups item=group}
                         <td class="td_header">{$group->name}</td>
                         {/foreach}
                       </tr>
                       {foreach from=$packages item=package}
                       {assign var="package_id" value=$package->id}
                       <tr>
                         <td class="td_header">
                         	{$package->name}
                         </td>
                         {foreach from=$users_groups item=group}
                         {assign var="group_id" value=$group->id}
                         <td>
                         	<a href="{$VH->site_url("admin/packages/payment/settings/group_id/$group_id/package_id/$package_id")}" title="{$LANG_PACKAGES_PRICE_OPTION_1} '{$package->name}' {$LANG_PACKAGES_PRICE_OPTION_2} '{$group->name}'">{if $prices[$package_id][$group_id].value == null || $prices[$package_id][$group_id].value == 0}{$LANG_FREE}{else}{$prices[$package_id][$group_id].currency} {$VH->number_format($prices[$package_id][$group_id].value, 2, $decimals_separator, $thousands_separator)}{/if}</a>
                         </td>
                         {/foreach}
                       </tr>
                       {/foreach}
                     </table>
                     {/if}
                </div>

{include file="backend/admin_footer.tpl"}