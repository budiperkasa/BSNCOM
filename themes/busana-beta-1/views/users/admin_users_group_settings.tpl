{include file="backend/admin_header.tpl"}
{assign var="users_group_id" value=$users_group->id}

<script language="JavaScript" type="text/javascript">
$(document).ready(function() {ldelim}
	$("#is_own_page").click( function() {ldelim}
		if ($('#is_own_page').is(':checked')) {ldelim}
        	$('#use_seo_name').removeAttr('disabled');
        	$('#meta_enabled').removeAttr('disabled');
	    {rdelim} else {ldelim}
	        $('#use_seo_name').attr('disabled', true);
	        $('#meta_enabled').attr('disabled', true);
	    {rdelim}
	{rdelim});
	$("#logo_enabled").click( function() {ldelim}
		if ($('#logo_enabled').is(':checked')) {ldelim}
        	$('#logo_width').removeAttr('disabled');
        	$('#logo_height').removeAttr('disabled');
        	$('#logo_thumbnail_width').removeAttr('disabled');
        	$('#logo_thumbnail_height').removeAttr('disabled');
	    {rdelim} else {ldelim}
	        $('#logo_width').attr('disabled', true);
	        $('#logo_height').attr('disabled', true);
	        $('#logo_thumbnail_width').attr('disabled', true);
	        $('#logo_thumbnail_height').attr('disabled', true);
	    {rdelim}
	{rdelim});
{rdelim});
</script>

				<div class="content">
                	{$VH->validation_errors()}
					<h3>{if $users_group_id != 'new'}{$LANG_EDIT_USERGROUP}{else}{$LANG_CREATE_USERGROUP}{/if}</h3>

					{if $users_group_id !='new' }
					<div class="admin_top_menu_cell">
                     	<a href="{$VH->site_url("admin/users/users_groups/create")}"><img src="{$public_path}/images/buttons/page_add.png" title="{$LANG_CREATE_NEW_GROUP}" /></a>
						<a href="{$VH->site_url("admin/users/users_groups/create")}">{$LANG_CREATE_NEW_GROUP}</a>
					</div>

					<div class="admin_top_menu_cell">
						<a href="{$VH->site_url("admin/users/users_groups/delete/$users_group_id")}"><img src="{$public_path}/images/buttons/page_delete.png" title="{$LANG_DELETE_GROUP}" /></a>
						<a href="{$VH->site_url("admin/users/users_groups/delete/$users_group_id")}">{$LANG_DELETE_GROUP}</a>
					</div>
					<div class="clear_float"></div>
					<div class="px10"></div>
					{/if}

					<form action="" method="post">
					<input type=hidden name="id" value="{$users_group_id}">
					<div class="admin_option">
						<div class="admin_option_name" >
							{$LANG_USERS_GROUP_NAME}<span class="red_asterisk">*</span>
							{translate_content table='users_groups' field='name' row_id=$users_group_id}
						</div>
						<input type=text name="name" value="{$users_group->name}" size="40" class="admin_option_input">
					</div>
					<div class="admin_option">
						<div class="admin_option_name" >
							{$LANG_USERS_GROUP_OWN_PAGE}
						</div>
						<div class="admin_option_description" >
							{$LANG_USERS_GROUP_OWN_PAGE_DESCR}
						</div>
						<input type="checkbox" name="is_own_page" id="is_own_page" value="1" {if $users_group->is_own_page}checked{/if}>&nbsp;{$LANG_ENABLED}
						
						<div class="px10"></div>
						
						<div class="admin_option_name" >
							{$LANG_USERS_GROUP_USE_SEO}
						</div>
						<div class="admin_option_description" >
							{$LANG_USERS_GROUP_USE_SEO_DESCR}
						</div>
						<input type="checkbox" name="use_seo_name" id="use_seo_name" value="1" {if $users_group->use_seo_name}checked{/if} {if !$users_group->is_own_page}disabled{/if}>&nbsp;{$LANG_ENABLED}
						
						<div class="px10"></div>
						
						<div class="admin_option_name" >
							{$LANG_USERS_GROUP_META_ENABLED}
						</div>
						<div class="admin_option_description" >
							{$LANG_USERS_GROUP_META_ENABLED_DESCR}
						</div>
						<input type="checkbox" name="meta_enabled" id="meta_enabled" value="1" {if $users_group->meta_enabled}checked{/if} {if !$users_group->is_own_page}disabled{/if}>&nbsp;{$LANG_ENABLED}
					</div>
					<div class="admin_option">
						<div class="admin_option_name">
							{$LANG_USERS_GROUP_LOGO_ENABLED}
						</div>
						<input type="checkbox" name="logo_enabled" id="logo_enabled" {if $users_group->logo_enabled==1} checked {/if} />&nbsp;{$LANG_ENABLED}
						<div class="px10"></div>
						<div class="admin_option_name">
							{$LANG_USERS_GROUP_LOGO_SIZE}
						</div>
						{$LANG_WIDTH}, {$LANG_PX}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$LANG_HEIGHT}, {$LANG_PX}<br>
						<input type=text name="logo_width" id="logo_width" value="{$users_group->explodeSize('logo_size', 0)}" size="4" class="admin_option_input" {if $users_group->logo_enabled == 0}disabled{/if}>&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=text name="logo_height" id="logo_height" value="{$users_group->explodeSize('logo_size', 1)}" size="4" class="admin_option_input" {if $users_group->logo_enabled == 0}disabled{/if}>

						<div class="px10"></div>
						<div class="admin_option_name">
							{$LANG_USERS_GROUP_LOGO_THUMBNAIL_SIZE}
						</div>
						{$LANG_WIDTH}, {$LANG_PX}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{$LANG_HEIGHT}, {$LANG_PX}<br>
						<input type=text name="logo_thumbnail_width" id="logo_thumbnail_width" value="{$users_group->explodeSize('logo_thumbnail_size', 0)}" size="4" class="admin_option_input" {if $users_group->logo_enabled == 0}disabled{/if}>&nbsp;&nbsp;&nbsp;&nbsp;
						<input type=text name="logo_thumbnail_height" id="logo_thumbnail_height" value="{$users_group->explodeSize('logo_thumbnail_size', 1)}" size="4" class="admin_option_input" {if $users_group->logo_enabled == 0}disabled{/if}>
					</div>

					<input class="button save_button" type=submit name="submit" value="{if $users_group_id != 'new'}{$LANG_BUTTON_SAVE_CHANGES}{else}{$LANG_BUTTON_CREATE_USERGROUP}{/if}">
					</form>
				</div>

{include file="backend/admin_footer.tpl"}