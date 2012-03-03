{include file="backend/admin_header.tpl"}

                <div class="content">
                	{$VH->validation_errors()}
                     <h3>{$LANG_MANAGE_CONTENT_PAGES} ({$nodes_count} {$LANG_PAGES})</h3>

                     <a href="{$VH->site_url("admin/pages/create")}" title="{$LANG_CREATE_PAGE}"><img src="{$public_path}/images/buttons/page_add.png" /></a>
                     <a href="{$VH->site_url("admin/pages/create")}">{$LANG_CREATE_PAGE}</a>&nbsp;&nbsp;&nbsp;
                     
                     {if $nodes|@count > 0}
                     <table id="drag_table" class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr class="nodrop nodrag">
                         <th>{$LANG_WEIGHT_TH}</th>
                         <th>{$LANG_URL_TH}</th>
                         <th>{$LANG_TITLE_TH}</th>
                         <th>{$LANG_TOP_MENU_TH}</th>
                         <th>{$LANG_CREATION_DATE_TH}</th>
                         <th>{$LANG_OPTIONS_TH}</th>
                       </tr>
                       {foreach from=$nodes item=node}
                       {assign var="node_id" value=$node.id}
                       <tr id="{$node_id}">
                       	 <td>
                             {$node.order_num}
                         </td>
                         <td>
                             {$node.url}
                         </td>
                         <td>
                         	<a href="{$VH->site_url("admin/pages/edit/$node_id")}" title="{$LANG_EDIT_CONTENT_PAGE}">{$node.title}</a>
                         </td>
                         <td>
                             {if $node.in_menu}<div class="yes"></div>{else}<div class="no"></div>{/if}
                         </td>
                         <td>
                             {$node.creation_date|date_format:"%D %T"}&nbsp;
                         </td>
                         <td>
                             <a href="{$VH->site_url("admin/pages/preview/$node_id")}" title="{$LANG_VIEW_CONTENT_PAGE}"><img src="{$public_path}images/buttons/page.png"></a>&nbsp;
                             <a href="{$VH->site_url("admin/pages/edit/$node_id")}" title="{$LANG_EDIT_CONTENT_PAGE}"><img src="{$public_path}images/buttons/page_edit.png"></a>&nbsp;
                             <a href="{$VH->site_url("admin/pages/delete/$node_id")}" title="{$LANG_DELETE_CONTENT_PAGE}"><img src="{$public_path}images/buttons/page_delete.png"></a>
                         </td>
                       </tr>
                       {/foreach}
                     </table>
                     <br/>
                     <form action="" method="post">
                     <input type="hidden" id="serialized_order" name="serialized_order"> 
                     <input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
                     </form>
                     {/if}
                </div>

{include file="backend/admin_footer.tpl"}