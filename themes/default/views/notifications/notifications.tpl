{include file="backend/admin_header.tpl"}

                <div class="content">
                     <h3>{$LANG_VIEW_EMAIL_NOTIFICATIONS}</h3>

                     {if $notifications|@count > 0}
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th>{$LANG_EVENT_TH}</th>
                         <th>{$LANG_DESCRIPTION_TH}</th>
                         <th>{$LANG_OPTIONS_TH}</th>
                       </tr>
                       {foreach from=$notifications item=notification}
                       {assign var="notification_id" value=$notification.id}
                       <tr>
                         <td>
                         	{$notification.event}
                         </td>
                         <td>
                         	{$notification.description|nl2br}
                         </td>
                         <td>
                             <a href="{$VH->site_url("admin/notifications/edit/$notification_id")}" title="{$LANG_EDIT_NOTIFICATION_OPTION}"><img src="{$public_path}images/buttons/page_edit.png"></a>&nbsp;
                         </td>
                       </tr>
                       {/foreach}
                     </table>
                     {/if}
                </div>

{include file="backend/admin_footer.tpl"}