{include file="backend/admin_header.tpl"}

				<div class="content">
					<h3>{$LANG_MANAGE_PACKAGES_TITLE}</h3>
					<h4>{$LANG_MANAGE_PACKAGES_DESCR}</h4>
					
					<div class="admin_top_menu_cell" style="width: auto">
						<a href="{$VH->site_url("admin/packages/create")}" title="{$LANG_CREATE_PACKAGE_LINK}"><img src="{$public_path}/images/buttons/page_add.png"></a>
						<a href="{$VH->site_url("admin/packages/create")}">{$LANG_CREATE_PACKAGE_LINK}</a>
					</div>

					{if $packages|@count}
					<table id="drag_table" class="standardTable" border="0" cellpadding="2" cellspacing="2">
					<tr class="nodrop nodrag">
						<th>{$LANG_PACKAGE_NAME}</th>
						<th>{$LANG_PACKAGE_LISTINGS_COUNT}</th>
						<th width="1">{$LANG_OPTIONS_TH}</th>
					</tr>
					{foreach from=$packages item=package}
					{assign var=package_id value=$package->id}
					<tr id="{$package_id}_id">
						<td>
							{$package->name}
						</td>
						<td>
							{foreach from=$package->items key=level_id item=listings_count}
								{assign var=type value=$package->levels[$level_id]->getType()}
								<b>{if !$system_settings.single_type_structure}{$type->name} - {/if}<i>{$package->levels[$level_id]->name}</i>:</b> {if $listings_count !== 'unlimited'}{$listings_count} {$LANG_LISTINGS}{else}<span class="green">{$LANG_UNLIMITED}</span>{/if}<br />
							{/foreach}
						</td>
						<td>
							<nobr>
								<a href="{$VH->site_url("admin/packages/items/$package_id")}" title="{$LANG_MANAGE_PACKAGE_ITEMS_OPTION}"><img src="{$public_path}images/buttons/page_green.png"></a>
								<a href="{$VH->site_url("admin/packages/edit/$package_id")}" title="{$LANG_EDIT_PACKAGE_OPTION}"><img src="{$public_path}images/buttons/page_edit.png"></a>
								<a href="{$VH->site_url("admin/packages/delete/$package_id")}" title="{$LANG_DELETE_PACKAGE_OPTION}"><img src="{$public_path}images/buttons/page_delete.png"></a>
							</nobr>
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