{include file="backend/admin_header.tpl"}

                <div class="content">
                     <h3>{$LANG_LISTINGS_PRICE}</h3>
                     {if $types|@count && $users_groups|@count}
                     <table class="presentationTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th>{$LANG_TYPE_TH}</th>
                         <th>{$LANG_LEVEL_TH} \ {$LANG_USERS_GROUP_TH}</th>
                         {foreach from=$users_groups item=group}
                         <td class="td_header">{$group->name}</td>
                         {/foreach}
                       </tr>
                       {foreach from=$types item=type}
                       <tr>
                         <td class="td_header">
                         	{$type->name}
                         </td>
                         <td class="td_header">
                         	{if $type->levels|@count > 0}
                         	<table width="100%" border="0" cellpadding="0" cellspacing="0">
                         		{foreach from=$type->levels item=level}
                         		<tr>
                         			<td width="100%" style="border: 0;">
                         			{$level->name}
                         			</td>
                         		</tr>
                         		{/foreach}
                         	</table>
                         	{else}
                         	&nbsp;
                         	{/if}
                         </td>
                         {foreach from=$users_groups item=group}
                         {assign var="group_id" value=$group->id}
                         <td>
                            {if $type->levels|@count > 0}
                         	<table width="100%" border="0" cellpadding="0" cellspacing="0">
                         		{foreach from=$type->levels item=level}
                         		{assign var="level_id" value=$level->id}
                         		<tr>
                         			<td  style="border: 0;">
                         				<a href="{$VH->site_url("admin/listings/payment/settings/group_id/$group_id/level_id/$level_id")}" title="{$LANG_LISTINGS_PRICE_OPTION_1} '{$level->name}' {$LANG_LISTINGS_PRICE_OPTION_2} '{$group->name}'">{if $prices.$level_id.$group_id.value == null || $prices.$level_id.$group_id.value == 0}{$LANG_FREE}{else}{$prices.$level_id.$group_id.currency} {$VH->number_format($prices.$level_id.$group_id.value, 2, $decimals_separator, $thousands_separator)}{/if}</a>
                         			</td>
                         		</tr>
                         		{/foreach}
                         	</table>
                         	{else}
                         	&nbsp;
                         	{/if}
                         </td>
                         {/foreach}
                       </tr>
                       {/foreach}
                     </table>
                     {/if}
                </div>

{include file="backend/admin_footer.tpl"}