{include file="backend/admin_header.tpl"}

                <div class="content">
                     <h3>{$LANG_CONTENT_PERMISSIONS}</h3>
                     <h4>{$LANG_CONTENT_PERMISSIONS_DESCR}</h4>
                     {if $types|@count && $users_groups|@count}
                     <form action="" method="post">
                     <table class="presentationTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                       	 {if !$system_settings.single_type_structure}
                         <th>{$LANG_TYPE_TH}</th>
                         {/if}
                         <th>{$LANG_LEVEL_TH} \ {$LANG_USERS_GROUP_TH}</th>
                         {foreach from=$users_groups item=group}
                         <td class="td_header">{$group->name}</td>
                         {/foreach}
                       </tr>
                       {foreach from=$types item=type}
                       <tr>
                       	 {if !$system_settings.single_type_structure}
                         <td class="td_header">
                         	{$type->name}
                         </td>
                         {/if}
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
                         				<input type="checkbox" name="{$group_id}-{$level_id}" value="{$group_id}:{$level_id}" {foreach from=$content_permissions item=cp}{if $cp.group_id == $group_id && $cp.object_id == $level_id}checked{/if}{/foreach} />
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

                     <input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
                     </form>
                     {/if}
                </div>

{include file="backend/admin_footer.tpl"}