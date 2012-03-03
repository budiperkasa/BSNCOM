{include file="backend/admin_header.tpl"}

                <div class="content">
					<h3>{$LANG_LISTING_STATUS} "{$listing->title()}"</h3>
                     
					{include file="listings/admin_listing_options_menu.tpl"}
                     
                     <form action="" method="post">
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th width="1">&nbsp;</th>
                         <th>{$LANG_STATUS_TH}</th>
                       </tr>
                       <tr>
                         <td>
                             <input type="radio" id="status_1" name="status" value="1" {if $listing->status == 1}checked{/if}>
                         </td>
                         <td>
                             <label for="status_1">{$LANG_STATUS_ACTIVE}</label>
                         </td>
                       </tr>
                       <tr>
                         <td>
                             <input type="radio" id="status_2" name="status" value="2" {if $listing->status == 2}checked{/if}>
                         </td>
                         <td>
                             <label for="status_2">{$LANG_STATUS_BLOCKED}</label>
                         </td>
                       </tr>

                       {if $content_access_obj->isPermission('Manage all listings')}
                       <tr>
                         <td>
                             <input type="radio" id="status_3" name="status" value="3" {if $listing->status == 3}checked{/if}>
                         </td>
                         <td>
                             <label for="status_3">{$LANG_STATUS_SUSPENDED}</label>
                         </td>
                       </tr>
                       <tr>
                         <td>
                             <input type="radio" id="status_4" name="status" value="4" {if $listing->status == 4}checked{/if}>
                         </td>
                         <td>
                             <label for="status_4">{$LANG_STATUS_UNAPPROVED}</label>
                         </td>
                       </tr>
                       <tr>
                         <td>
                             <input type="radio" id="status_5" name="status" value="5" {if $listing->status == 5}checked{/if}>
                         </td>
                         <td>
                             <label for="status_5">{$LANG_STATUS_NOTPAID}</label>
                         </td>
                       </tr>
                       {/if}
                     </table>

                     <input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}