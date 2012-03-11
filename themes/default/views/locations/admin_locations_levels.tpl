{include file="backend/admin_header.tpl"}

                <div class="content">
                     <h3>{$LANG_MANAGE_LOC_LEVELS}</h3>
                     <a href="{$VH->site_url("admin/locations/levels/create")}" title="{$LANG_CREATE_LOC_LEVEL_OPTION}"><img src="{$public_path}/images/buttons/page_add.png"></a>
                     <a href="{$VH->site_url("admin/locations/levels/create")}">{$LANG_CREATE_LOC_LEVEL_OPTION}</a>&nbsp;&nbsp;&nbsp;&nbsp;

                     {if $levels|@count}
                     <table id="drag_table" class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr class="nodrop nodrag">
                         <th width="1">{$LANG_WEIGHT_TH}</th>
                         <th>{$LANG_NAME_TH}</th>
                         <th width="1">{$LANG_OPTIONS_TH}</th>
                       </tr>
                       {foreach from=$levels item=level}
                       {assign var="level_id" value=$level->id}
                       <tr id="{$level_id}_id">
                         <td>
                             {$level->order_num}
                         </td>
                         <td>
                             <a href="{$VH->site_url("admin/locations/levels/edit/$level_id")}" title="{$LANG_EDIT_LOC_LEVEL_OPTION}">{$level->name}</a>
                         </td>
                         <td>
                             <a href="{$VH->site_url("admin/locations/levels/edit/$level_id")}" title="{$LANG_EDIT_LOC_LEVEL_OPTION}"><img src="{$public_path}images/buttons/page_edit.png"></a>
                             <a href="{$VH->site_url("admin/locations/levels/delete/$level_id")}" title="{$LANG_DELETE_LOC_LEVEL_OPTION}"><img src="{$public_path}images/buttons/page_delete.png"></a>
                         </td>
                       </tr>
                       {/foreach}
                     </table>

                     <br />
                     <form action="" method="post">
                     <input type="hidden" id="serialized_order" name="serialized_order"> 
                     <input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
                     </form>
                     {/if}
                </div>

{include file="backend/admin_footer.tpl"}