{include file="backend/admin_header.tpl"}
{assign var=colspan value=$users_groups|@count}

                <div class="content">
                     <h3>{$LANG_USERS_GROUP_PERMISSIONS}</h3>
                     <a href="{$VH->site_url("admin/users/users_groups/create")}"><img src="{$public_path}/images/buttons/page_add.png" title="{$LANG_CREATE_NEW_GROUP}" />
                     <a href="{$VH->site_url("admin/users/users_groups/create")}">{$LANG_CREATE_NEW_GROUP}</a>&nbsp;&nbsp;&nbsp;

                     <form action="" method="post">
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                       	 <th></th>
                         {foreach from=$users_groups item=group_item}
                         <th>{$group_item->name}{if $group_item->default_group} <i>(default)</i>{/if}</th>
                         {/foreach}
                       </tr>
                       {foreach from=$permissions key=module item=permissions_of_module}
                       <tr>
                       	     <td colspan="{$colspan+1}" class="td_header">
                       	         <b>{$modules_array.$module}</b>
                       	     </td>
                       </tr>
	                       {foreach from=$permissions_of_module item=permission}
	                       <tr>
	                         <td>
	                             {$permission}
	                         </td>
	                         {foreach from=$users_groups key=key item=group_item}
	                         <td>
	                             <input type="checkbox" name="{$group_item->id}-{$permission}" value="{$group_item->id}:{$permission}" {foreach from=$users_groups_permissions item=ugp}{if $ugp.group_id == $group_item->id && $ugp.function_access == $permission}checked{/if}{/foreach}>
	                         </td>
	                         {/foreach}
	                       </tr>
	                       {/foreach}
                       {/foreach}
                     </table>
                     <br/>
                     <input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}