{include file="backend/admin_header.tpl"}

                <div class="content">
                     <h3>{$LANG_CHANGE_USERS_GROUP} "{$user->login}"</h3>

                     {include file="users/admin_user_options_menu.tpl"}

                     <form action="" method="post">
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th width="1">&nbsp;</th>
                         <th>{$LANG_USERS_GROUP_NAME_TH}</th>
                       </tr>
                       {foreach from=$users_groups item=group_item}
                       <tr>
                         <td>
                             <input type="radio" name="group_id" id="group_{$group_item->id}" value="{$group_item->id}" {if $group_item->id == $user->group_id}checked{/if}>
                         </td>
                         <td>
                             <label for="group_{$group_item->id}">{$group_item->name}</label>
                         </td>
                       </tr>
                       {/foreach}
                     </table>
                     <br/>
                     <input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}