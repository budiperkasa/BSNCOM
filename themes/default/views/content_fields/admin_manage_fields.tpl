{include file="backend/admin_header.tpl"}

                <div class="content">
                     <h3>{$LANG_MANAGE_CONTENT_FIELDS}</h3>
                     <a href="{$VH->site_url("admin/fields/create")}" title="{$LANG_CREATE_FIELD_OPTION}"><img src="{$public_path}/images/buttons/page_add.png"></a>
					 <a href="{$VH->site_url("admin/fields/create")}" title="{$LANG_CREATE_FIELD_OPTION}">{$LANG_CREATE_FIELD_OPTION}</a>&nbsp;&nbsp;&nbsp;&nbsp;
                     {if $fields|@count > 0}
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th>{$LANG_NAME_TH}</th>
                         <th>{$LANG_FIELD_FRONTEND_NAME}</th>
                         <th>{$LANG_FIELD_TYPE_TH}</th>
                         <th>{$LANG_DESCRIPTION_TH}</th>
                         <th>{$LANG_REQUIRED_TH}</th>
                         <th>{$LANG_FIELD_VISIBILITY_INDEX_TH}</th>
                         <th>{$LANG_FIELD_VISIBILITY_TYPES_TH}</th>
                         <th>{$LANG_FIELD_VISIBILITY_CATEGORIES_TH}</th>
                         <th>{$LANG_FIELD_VISIBILITY_SEARCH_TH}</th>
                         <th>{$LANG_OPTIONS_TH}</th>
                       </tr>
                       {foreach from=$fields item=field_item}
                       {assign var="field_item_id" value=$field_item.id}
                       <tr>
                         <td>
							<a href="{$VH->site_url("admin/fields/edit/$field_item_id")}">{$field_item.name}</a>
                         </td>
                         <td>
							{$field_item.frontend_name}
                         </td>
                         <td>
                             {$field_item.type_name}&nbsp;
                         </td>
                         <td>
                             {$field_item.description|nl2br}&nbsp;
                         </td>
                         <td>
                             {if $field_item.required == 1}<span class="red_asterisk">*</span>{/if}&nbsp;
                         </td>
                         <td>
                             {if $field_item.v_index_page == 1}<div class="yes"></div>{else}<div class="no"></div>{/if}
                         </td>
                         <td>
                             {if $field_item.v_types_page == 1}<div class="yes"></div>{else}<div class="no"></div>{/if}
                         </td>
                         <td>
                             {if $field_item.v_categories_page == 1}<div class="yes"></div>{else}<div class="no"></div>{/if}
                         </td>
                         <td>
                             {if $field_item.v_search_page == 1}<div class="yes"></div>{else}<div class="no"></div>{/if}
                         </td>
                         <td>
                         	<nobr>
                         	 {if $field_item.configuration_page}
                         	 	<a href="{$VH->site_url("admin/fields/configure/$field_item_id")}" title="{$LANG_CONFIGURE_FIELD_OPTION}"><img src="{$public_path}images/buttons/page_gear.png"></a>
                             {/if}
                             {if $field_item.search_configuration_page}
                         	 	<a href="{$VH->site_url("admin/fields/configure_search/$field_item_id")}" title="{$LANG_CONFIGURE_SEARCH_FIELD_OPTION}"><img src="{$public_path}images/buttons/page_find.png"></a>
                             {/if}
                             <a href="{$VH->site_url("admin/fields/copy/$field_item_id")}" title="{$LANG_MAKE_FIELD_COPY}"><img src="{$public_path}images/buttons/page_copy.png"></a>
                             <a href="{$VH->site_url("admin/fields/edit/$field_item_id")}" title="{$LANG_EDIT_FIELD_OPTION}"><img src="{$public_path}images/buttons/page_edit.png"></a>
                             <a href="{$VH->site_url("admin/fields/delete/$field_item_id")}" title="{$LANG_DELETE_FIELD_OPTION}"><img src="{$public_path}images/buttons/page_delete.png"></a>
                            <nobr>
                         </td>
                       </tr>
                       {/foreach}
                     </table>
                     {/if}
                </div>

{include file="backend/admin_footer.tpl"}