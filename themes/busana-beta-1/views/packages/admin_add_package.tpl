{include file="backend/admin_header.tpl"}

				<div class="content">
					<h3>{$LANG_ADD_PACKAGE_TITLE}</h3>
					<h4>{$LANG_ADD_PACKAGE_DESCR}</h4>
					
					{if $packages|@count}
					<table class="advertiseTable" style="width: 100%" border="0" cellpadding="0" cellspacing="0">
						<tr class="th_header">
							<th>{$LANG_PACKAGE_NAME}</th>
							<th>{$LANG_PRICE_TH}</th>
							<th>{$LANG_PACKAGE_LISTINGS_COUNT}</th>
							<th>&nbsp;</th>
						</tr>
						{foreach from=$packages item=package}
						{if $package->items}
							{assign var=package_id value=$package->id}
							<tr>
								<td>
									{$package->name}
								</td>
								<td>
									{if $packages_prices[$package_id].value == null || $packages_prices[$package_id].value == 0}<span class="free">{$LANG_FREE}</span>{else}{$packages_prices[$package_id].currency}&nbsp;{$packages_prices[$package_id].value}{/if}
								</td>
								<td>
									{assign var="package_available_for_user" value=0}
									{foreach from=$package->items key=level_id item=listings_count}
									{if $listings_count > 0 || $listings_count === 'unlimited'}
										{if $content_access_obj->isContentPermission('levels', $level_id)}
											{assign var=type value=$package->levels[$level_id]->getType()}
											<b>{if !$system_settings.single_type_structure}{$type->name} - {/if}<i>{$package->levels[$level_id]->name}</i>:</b> {if $listings_count !== 'unlimited'}{$listings_count} {$LANG_LISTINGS}{else}<span class="green">{$LANG_UNLIMITED}</span>{/if}<br />
											{assign var="package_available_for_user" value=1}
										{/if}
									{/if}
									{/foreach}
								</td>
								<td>
									{if $package_available_for_user}
										<a href="{$VH->site_url("admin/packages/add/$package_id/")}">{$LANG_ADD_PACKAGE_LINK}</a>
									{/if}
								</td>
							</tr>
							<tr>
								<td class="gap" colspan="16">&nbsp;</td>
							</tr>
						{/if}
						{/foreach}
					</table>
					{else}
						<div class="error_msg rounded_corners">
							<ul>
								<li>{$LANG_PACKAGES_ADVERTISE_EXCLAMATION}</li>
							</ul>
						</div>
					{/if}
				</div>

{include file="backend/admin_footer.tpl"}