{include file="backend/admin_header.tpl"}

<script language="JavaScript" type="text/javascript">
$(document).ready(function() {ldelim}
	$("input[name='default_group']").change(function() {ldelim}
		group_id = $(this).val();
		$("input[name*='may_register']").attr('disabled', false);
		$("input[name='may_register["+group_id+"]']").attr('checked', true);
		$("input[name='may_register["+group_id+"]']").attr('disabled', true);
	{rdelim});
{rdelim});
</script>

                <div class="content">
                	{$VH->validation_errors()}
                     <h3>{$LANG_USERS_GROUPS}</h3>
                     <div class="note">{$LANG_USERS_GROUPS_DESCR}</div>
                     
                     <div class="admin_top_menu_cell" style="width: auto;">
	                     <a href="{$VH->site_url("admin/users/users_groups/create")}" title="{$LANG_CREATE_NEW_GROUP}"><img src="{$public_path}/images/buttons/page_add.png"></a>
	                     <a href="{$VH->site_url('admin/users/users_groups/create')}">{$LANG_CREATE_NEW_GROUP}</a>&nbsp;&nbsp;&nbsp;
					</div>
					<div class="clear_float"></div>

                     {if $users_groups|@count > 0}
                     <form action="{$VH->site_url('admin/users/users_groups/save_default')}" method="post">
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th width="1">{$LANG_ID_TH}</th>
                         <th width="1">{$LANG_DEFAULT_GROUP_TH}</th>
                         <th width="1">{$LANG_MAY_REGISTER_TH}</th>
                         <th>{$LANG_NAME_TH}</th>
                         <th>{$LANG_USERS_GROUP_OWN_PAGE_TH}</th>
                         <th>{$LANG_USERS_GROUP_USE_SEO_TH}</th>
                         <th>{$LANG_USERS_GROUP_LOGO_ENABLED_TH}</th>
                         <th>{$LANG_OPTIONS_TH}</th>
                       </tr>
                       {foreach from=$users_groups item=group_item}
                       {assign var="group_id" value=$group_item->id}
                       <tr>
                         <td>
                             {$group_id}
                         </td>
                         <td>
                             <input type="radio" name="default_group" value="{$group_id}" {if $group_item->default_group}checked{/if} />
                         </td>
                         <td>
                         	{if $group_id != 1}
                             <input type="checkbox" name="may_register[{$group_id}]" value="1" {if $group_item->may_register}checked{/if} {if $group_item->default_group}disabled{/if}/>
                            {/if}
                         </td>
                         <td>
                             <a href="{$VH->site_url("admin/users/users_groups/edit/$group_id")}">{$group_item->name}</a>
                         </td>
                         <td>
                             {if $group_item->is_own_page}<img src="{$public_path}images/icons/accept.png" />{else}<img src="{$public_path}images/icons/delete.png" />{/if}
                         </td>
                         <td>
                             {if $group_item->use_seo_name}<img src="{$public_path}images/icons/accept.png" />{else}<img src="{$public_path}images/icons/delete.png" />{/if}
                         </td>
                         <td>
                             {if $group_item->logo_enabled}<img src="{$public_path}images/icons/accept.png" />{else}<img src="{$public_path}images/icons/delete.png" />{/if}
                         </td>
                         <td>
                         	 <nobr>
                             <a href="{$VH->site_url("admin/users/users_groups/edit/$group_id")}" title="{$LANG_EDIT_GROUP}"><img src="{$public_path}images/buttons/page_edit.png"></a>
                             {if $group_id != 1}
                             <a href="{$VH->site_url("admin/users/users_groups/delete/$group_id")}" title="{$LANG_DELETE_GROUP}"><img src="{$public_path}images/buttons/page_delete.png"></a>
                             {/if}
                             </nobr>
                         </td>
                       </tr>
                       {/foreach}
                     </table>
                     <input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
                     </form>
                     {/if}
                </div>

{include file="backend/admin_footer.tpl"}