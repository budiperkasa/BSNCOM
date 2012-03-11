{include file="backend/admin_header.tpl"}

                <div class="content">
                     <h3>{$LANG_LEVELS_OF_TYPE} "{$type->name}"</h3>
                     <div class="admin_top_menu_cell" style="width: auto">
	                     <a href="{$VH->site_url("admin/levels/create/type_id/$type_id")}" title="{$LANG_CREATE_LEVEL}"><img src="{$public_path}/images/buttons/page_add.png"></a>
	                     <a href="{$VH->site_url("admin/levels/create/type_id/$type_id")}">{$LANG_CREATE_LEVEL}</a>&nbsp;&nbsp;&nbsp;
	                 </div>
                     
                     {if $levels|@count > 0}
                     <table id="drag_table" class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr class="nodrop nodrag">
                         <th>{$LANG_ID_TH}</th>
                         <th>{$LANG_LEVEL_NAME_TH}</th>
                         <th>{$LANG_LEVEL_FEATURED_TH}</th>
                         <th>{$LANG_LEVEL_LISTING_DESCRIPTION}</th>
                         <th>{$LANG_LEVEL_CATEGORIES_TH}</th>
                         {if $type->locations_enabled}
                         <th>{$LANG_LEVEL_LOCATIONS_TH}</th>
                         {/if}
                         <th>{$LANG_TYPE_MODERATION_TH}</th>
                         <th>{$LANG_LEVEL_LOGO_TH}</th>
                         <th>{$LANG_LEVEL_IMAGES_TH}</th>
                         <th>{$LANG_LEVEL_VIDEOS_TH}</th>
                         <th>{$LANG_LEVEL_FILES_TH}</th>
                         <th>{$LANG_LEVEL_MAPS_TH}</th>
                         <th>{$LANG_OPTIONS_TH}</th>
                       </tr>
                       {foreach from=$levels item=level}
                       {assign var="level_id" value=$level->id}
                       {assign var="content_fields_group_id" value=$level->content_fields_group_id}
                       <tr id="{$level->id}_id">
                       	 <td>
                             {$level->id}
                         </td>
                         <td>
                             <a href="{$VH->site_url("admin/levels/edit/$level_id")}">{$level->name}</a>
                         </td>
                         <td>
                         	{if $level->featured}On{else}Off{/if}
                         </td>
                         <td>
                         	{if $level->description_mode != 'disabled'}On{else}Off{/if}
                         </td>
                         <td>
                         	{$level->categories_number}
                         </td>
                         {if $type->locations_enabled}
                         <td>
                         	{$level->locations_number}
                         </td>
                         {/if}
                         <td>
                         	{if $level->preapproved_mode}On{else}Off{/if}
                         </td>
                         <td>
                         	{if $level->logo_enabled}On{else}Off{/if}
                         </td>
                         <td>
                         	{$level->images_count}
                         </td>
                         <td>
                         	{$level->video_count}
                         </td>
                         <td>
                         	{$level->files_count}
                         </td>
                         <td>
                         	{if $level->maps_enabled}On{else}Off{/if}
                         </td>
                         <td>
                         	<nobr>
                         	 <a href="{$VH->site_url("admin/fields/groups/manage_fields/$content_fields_group_id")}" title="{$LANG_CONFIGURE_FIELDS_OPTION}"><img src="{$public_path}/images/buttons/page_green.png"></a>
                             <a href="{$VH->site_url("admin/levels/edit/$level_id")}" title="{$LANG_EDIT_LEVEL_OPTION}"><img src="{$public_path}images/buttons/page_edit.png"></a>
                             <a href="{$VH->site_url("admin/levels/delete/$level_id")}" title="{$LANG_DELETE_LEVEL_OPTION}"><img src="{$public_path}images/buttons/page_delete.png"></a>
                            </nobr>
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