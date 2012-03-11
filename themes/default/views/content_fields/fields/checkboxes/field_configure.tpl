{include file="backend/admin_header.tpl"}
{assign var="field_id" value=$field->id}

                <div class="content">
                	{$VH->validation_errors()}
                     <h3>{$LANG_CONFIGURE_FIELD_1} "{$field->type_name}" {$LANG_CONFIGURE_FIELD_2} "{$field->name}"</h3>

					 <a href="{$VH->site_url("admin/fields/configure/$field_id/add_option/")}" title="{$LANG_ADD_CHECKBOX}"><img src="{$public_path}/images/buttons/page_add.png" /></a>
                     <a href="{$VH->site_url("admin/fields/configure/$field_id/add_option/")}">{$LANG_ADD_CHECKBOX}</a>&nbsp;&nbsp;&nbsp;&nbsp;
                     
                     <a href="{$VH->site_url("admin/fields/edit/$field_id")}" title="{$LANG_EDIT_FIELD_OPTION}"><img src="{$public_path}/images/buttons/page_edit.png"></a>
					 <a href="{$VH->site_url("admin/fields/edit/$field_id")}">{$LANG_EDIT_FIELD_OPTION}</a>&nbsp;&nbsp;&nbsp;&nbsp;

					 {if $field->search_configuration_page}
                       	<a href="{$VH->site_url("admin/fields/configure_search/$field_id")}" title="{$LANG_CONFIGURE_SEARCH_FIELD_OPTION}"><img src="{$public_path}images/buttons/page_find.png"></a>
                       	<a href="{$VH->site_url("admin/fields/configure_search/$field_id")}">{$LANG_CONFIGURE_SEARCH_FIELD_OPTION}</a>&nbsp;&nbsp;&nbsp;&nbsp;
                     {/if}
					 
					 <a href="{$VH->site_url("admin/fields/delete/$field_id")}"><img src="{$public_path}/images/buttons/page_delete.png"></a>
					 <a href="{$VH->site_url("admin/fields/delete/$field_id")}">{$LANG_DELETE_FIELD_OPTION}</a>&nbsp;&nbsp;&nbsp;&nbsp;
					 <br />
					 <br />
                     
                     <form action="" method="post">
					 <input type="hidden" name="id" value="{$field->id}">
                     <table id="drag_table" class="standardTable" border="0" cellpadding="2" cellspacing="2">
                        <tr class="nodrop nodrag">
                         <th width="10">{$LANG_ID_TH}</th>
                         <th width="10">{$LANG_WEIGHT_TH}</th>
                         <th style="width: 300px;">{$LANG_CHECKBOX_NAME_TH}</th>
                         <th width="10">{$LANG_OPTIONS_TH}</th>
                       </tr>
                       {foreach from=$options item=option_item}
                       {assign var="field_option_id" value=$option_item.id}
                       <tr id="{$option_item.id}_id">
                       	 <td>
	                         {$option_item.id}
                         </td>
                         <td>
	                         {$option_item.order_num}
                         </td>
                         <td>
                             <a href="{$VH->site_url("admin/fields/configure/$field_id/edit_option/$field_option_id")}">{$option_item.option_name}</a>
                         </td>
                         <td>
                             <a href="{$VH->site_url("admin/fields/configure/$field_id/edit_option/$field_option_id")}" title="{$LANG_EDIT_OPTION}"><img src="{$public_path}/images/buttons/page_edit.png" /></a>
                             <a href="{$VH->site_url("admin/fields/configure/$field_id/delete_option/$field_option_id")}" title="{$LANG_DELETE_OPTION}"><img src="{$public_path}/images/buttons/page_delete.png" /></a>
                         </td>
                       </tr>
                       {/foreach}
                     </table>
                     <br/>
                     <input type="hidden" id="serialized_order" name="serialized_order"> 
                     <input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}