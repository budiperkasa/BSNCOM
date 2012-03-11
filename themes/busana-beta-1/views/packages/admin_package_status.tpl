{include file="backend/admin_header.tpl"}

                <div class="content">
					<h3>{$LANG_PACKAGE_STATUS_TITLE} "{$user_package->package->name}"</h3>

                     <form action="" method="post">
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th width="1">&nbsp;</th>
                         <th>{$LANG_STATUS_TH}</th>
                       </tr>
                       <tr>
                         <td>
                             <input type="radio" id="status_1" name="status" value="1" {if $user_package->status == 1}checked{/if}>
                         </td>
                         <td>
                             <label for="status_1">{$LANG_STATUS_ACTIVE}</label>
                         </td>
                       </tr>
                       <tr>
                         <td>
                             <input type="radio" id="status_2" name="status" value="2" {if $user_package->status == 2}checked{/if}>
                         </td>
                         <td>
                             <label for="status_2">{$LANG_STATUS_BLOCKED}</label>
                         </td>
                       </tr>
                       <tr>
                         <td>
                             <input type="radio" id="status_3" name="status" value="3" {if $user_package->status == 3}checked{/if}>
                         </td>
                         <td>
                             <label for="status_3">{$LANG_STATUS_NOTPAID}</label>
                         </td>
                       </tr>
                     </table>

                     <input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}