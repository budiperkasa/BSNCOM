{include file="backend/admin_header.tpl"}
{assign var="package_id" value=$package->id}

				<div class="content">
					<h3>{$LANG_MANAGE_PACKAGE_ITEMS_TITLE}</h3>

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

					{if $types|@count > 0}
					<form action="" method="post">
					<table class="standardTable" border="0" cellpadding="2" cellspacing="2">
					<tr>
						{if !$system_settings.single_type_structure}
						<th>{$LANG_TYPE_TH}</th>
						{/if}
						<th>{$LANG_LEVEL_TH}</th>
						<th>{$LANG_PACKAGE_LISTINGS_COUNT}</th>
					</tr>
					{foreach from=$types item=type}
					<tr>
						{if !$system_settings.single_type_structure}
						<td class="td_header">
							{$type->name}
						</td>
						{/if}
						<td class="td_header">
							{if $type->levels|@count > 0}
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
								{foreach from=$type->levels item=level}
								<tr>
									<td class="td_header" width="100%" style="border: 0;">
										{$level->name}
									</td>
								</tr>
								{/foreach}
								</table>
							{else}
								&nbsp;
							{/if}
						</td>
						<td>
							{if $type->levels|@count > 0}
								<table width="100%" border="0" cellpadding="0" cellspacing="0">
								{foreach from=$type->levels item=level}
								{assign var="level_id" value=$level->id}
								<tr>
									<td style="border: 0;">
										<input style="padding:0" type="text" name="count_{$level_id}" value="{if !$package->items[$level_id] || $package->items[$level_id] == 'unlimited'}0{else}{$package->items[$level_id]}{/if}" size="1" />&nbsp;&nbsp;&nbsp;<input type="checkbox" name="unlimited_{$level_id}" value="1" {if $package->items[$level_id] == 'unlimited'}checked{/if} /> {$LANG_UNLIMITED}
									</td>
								</tr>
								{/foreach}
								</table>
							{else}
								&nbsp;
							{/if}
						</td>
					</tr>
					{/foreach}
					</table>
					<br/>
					<input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
					</form>
					{/if}
				</div>

{include file="backend/admin_footer.tpl"}