{include file="backend/admin_header.tpl"}

                <div class="content">
                     <h3>{$LANG_CHANGE_USERS_STATUS} "{$user->login}"</h3>

                     {include file="users/admin_user_options_menu.tpl"}

                     <form action="" method="post">
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th width="1">&nbsp;</th>
                         <th>{$LANG_USERS_STATUS_TH}</th>
                       </tr>
                       <tr>
                         <td>
                             <input type="radio" name="status" id="status_1" value="1" {if $user->status == 1}checked{/if}>
                         </td>
                         <td>
                             <label for="status_1">{$LANG_USER_STATUS_UNVERIFIED}</label>
                         </td>
                       </tr>
                       <tr>
                         <td>
                             <input type="radio" name="status" id="status_2" value="2" {if $user->status == 2}checked{/if}>
                         </td>
                         <td>
                             <label for="status_2">{$LANG_USER_STATUS_ACTIVE}</label>
                         </td>
                       </tr>
                       <tr>
                         <td>
                             <input type="radio" name="status" id="status_3" value="3" {if $user->status == 3}checked{/if}>
                         </td>
                         <td>
                             <label for="status_3">{$LANG_USER_STATUS_BLOCKED}</label>
                         </td>
                       </tr>
                     </table>
                     <br/>
                     <input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}