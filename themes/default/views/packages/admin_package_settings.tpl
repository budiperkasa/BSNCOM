{include file="backend/admin_header.tpl"}
{assign var="package_id" value=$package->id}

				<div class="content">
					{$VH->validation_errors()}

					<h3>{if $package_id != 'new'}{$LANG_EDIT_PACKAGE_TITLE} "{$package->name}"{else}{$LANG_CREATE_PACKAGE_TITLE}{/if}</h3>
					{if $package_id !='new' }
					<div class="admin_top_menu_cell">
						<a href="{$VH->site_url("admin/packages/create")}" title="{$LANG_CREATE_PACKAGE_LINK}"><img src="{$public_path}/images/buttons/page_add.png"></a>
						<a href="{$VH->site_url("admin/packages/create")}">{$LANG_CREATE_PACKAGE_LINK}</a>
					</div>
					<div class="admin_top_menu_cell">
						<a href="{$VH->site_url("admin/packages/items/$package_id")}" title="{$LANG_MANAGE_PACKAGE_ITEMS_OPTION}"><img src="{$public_path}/images/buttons/page_green.png"></a>
						<a href="{$VH->site_url("admin/packages/items/$package_id")}">{$LANG_MANAGE_PACKAGE_ITEMS_OPTION}</a>
					</div>
					<div class="admin_top_menu_cell">
						<a href="{$VH->site_url("admin/packages/edit/$package_id")}" title="{$LANG_EDIT_PACKAGE_OPTION}"><img src="{$public_path}/images/buttons/page_edit.png"></a>
						<a href="{$VH->site_url("admin/packages/edit/$package_id")}">{$LANG_EDIT_PACKAGE_OPTION}</a>
					</div>
					<div class="admin_top_menu_cell">
						<a href="{$VH->site_url("admin/packages/delete/$package_id")}" title="{$LANG_DELETE_PACKAGE_OPTION}"><img src="{$public_path}/images/buttons/page_delete.png"></a>
						<a href="{$VH->site_url("admin/packages/delete/$package_id")}">{$LANG_DELETE_PACKAGE_OPTION}</a>
					</div>
					<div class="clear_float"></div>
					{/if}

					<form action="" method="post">
					<input type=hidden name="id" value="{$package_id}">
					<div class="admin_option">
                     	<div class="admin_option_name" >
                     		{$LANG_PACKAGE_NAME}<span class="red_asterisk">*</span>
                          	{translate_content table='packages' field='name' row_id=$package_id}
						</div>
						<input type="text" name="name" value="{$package->name}" size="40" />
					</div>

                     <input class="button save_button" type=submit name="submit" value="{if $package_id != 'new'}{$LANG_BUTTON_SAVE_CHANGES}{else}{$LANG_BUTTON_CREATE_PACKAGE}{/if}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}