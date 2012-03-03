{include file="backend/admin_header.tpl"}

                <div class="content">
                	{$VH->validation_errors()}
                     <h3>{$LANG_LISTINGS_TYPES}</h3>
                     
                    {if !$system_settings.single_type_structure && $types|@count >= 1}
                    <div class="admin_top_menu_cell">
	                    <a href="{$VH->site_url("admin/types/create")}" title="{$LANG_CREATE_TYPE_OPTION}"><img src="{$public_path}/images/buttons/page_add.png"></a>
	                    <a href="{$VH->site_url("admin/types/create")}">{$LANG_CREATE_TYPE_OPTION}</a>
					</div>
					{/if}

                     {if $types|@count > 0}
                     <table id="drag_table" class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr class="nodrop nodrag">
                       	 <th>{$LANG_ID_TH}</th>
                         <th>{$LANG_TYPE_NAME_TH}</th>
                         <th>{$LANG_TYPE_LEVELS_TH}</th>
                         <th>{$LANG_TYPE_LOCATIONS_TH}</th>
                         <th>{$LANG_TYPE_ZIP_TH}</th>
                         {if !$system_settings.single_type_structure}
                         <th>{$LANG_TYPE_SEARCH_TYPE}</th>
                         <th>{$LANG_TYPE_CATEGORIES_TYPE}</th>
                         {/if}
                         <th>{$LANG_OPTIONS_TH}</th>
                       </tr>
                       {foreach from=$types item=type}
                       {assign var="type_id" value=$type->id}
                       <tr id="{$type_id}_id">
                       	 <td>
                             {$type->id}
                         </td>
                         <td>
                             <a href="{$VH->site_url("admin/types/edit/$type_id")}">{$type->name}</a>
                         </td>
                         <td>
                             {$type->levels|@count}
                         </td>
                         <td>
                         	{if $type->locations_enabled}on{else}off{/if}
                         </td>
                         <td>
                         	{if $type->zip_enabled}on{else}off{/if}
                         </td>
                         {if !$system_settings.single_type_structure}
                         <td>
                         	{if $type->search_type == 'global'}{$LANG_GLOABAL_SEARCH}{/if}
                         	{if $type->search_type == 'local'}{$LANG_LOCAL_SEARCH}{/if}
                         	{if $type->search_type == 'disabled'}{$LANG_DISABLED}{/if}
                         </td>
                         <td>
                         	{if $type->categories_type == 'global'}{$LANG_GLOABAL_CATEGORIES}{/if}
                         	{if $type->categories_type == 'local'}{$LANG_LOCAL_CATEGORIES}{/if}
                         	{if $type->categories_type == 'disabled'}{$LANG_DISABLED}{/if}
                         </td>
                         {/if}
                         <td>
                         	<nobr>
                         	 <a href="{$VH->site_url("admin/levels/type_id/$type_id")}" title="{$LANG_VIEW_LEVELS_OPTION}"><img src="{$public_path}images/buttons/page_green.png"></a>
                             <a href="{$VH->site_url("admin/types/edit/$type_id")}" title="{$LANG_EDIT_TYPE_OPTION}"><img src="{$public_path}images/buttons/page_edit.png"></a>
                             <a href="{$VH->site_url("admin/types/delete/$type_id")}" title="{$LANG_DELETE_TYPE_OPTION}"><img src="{$public_path}images/buttons/page_delete.png"></a>
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