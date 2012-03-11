{include file="backend/admin_header.tpl"}

				<div class="content">
					<h3>{$LANG_MY_PACKAGES_TITLE}</h3>
					<h4>{$LANG_MY_PACKAGES_DESCR}</h4>

					{if $my_packages_obj->packages|@count}
					<table class="standardTable" border="0" cellpadding="2" cellspacing="2">
					<tr>
						<th>{$LANG_PACKAGE_NAME}</th>
						<th>{$LANG_PACKAGE_STATUS_TH}</th>
						<th>{$LANG_PACKAGE_LISTINGS_AVAILABLE}</th>
						<th>{$LANG_PACKAGE_ADDITION_DATE_TH}</th>
						<th></th>
					</tr>
					{foreach from=$my_packages_obj->packages item=user_package}
					{assign var=user_package_id value=$user_package->id}
					<tr>
						<td>
							{$user_package->package->name}
						</td>
						<td>
							{if $user_package->status == 1}{if $content_access_obj->isPermission('Change user packages status')}<a href="{$VH->site_url("admin/packages/change_status/$user_package_id")}" class="status_active">{$LANG_STATUS_ACTIVE}</a>{else}<span class="status_active">{$LANG_STATUS_ACTIVE}</span>{/if}{/if}
							{if $user_package->status == 2}{if $content_access_obj->isPermission('Change user packages status')}<a href="{$VH->site_url("admin/packages/change_status/$user_package_id")}" class="status_blocked">{$LANG_STATUS_BLOCKED}</a>{else}<span class="status_blocked">{$LANG_STATUS_BLOCKED}</span>{/if}{/if}
							{if $user_package->status == 3}{if $content_access_obj->isPermission('Change user packages status')}<a href="{$VH->site_url("admin/packages/change_status/$user_package_id")}" class="status_notpaid">{$LANG_STATUS_NOTPAID}</a>{else}<span class="status_notpaid">{$LANG_STATUS_NOTPAID}</span>{/if}{/if}
						</td>
						<td>
							{assign var="package_available_for_user" value=0}
							{foreach from=$user_package->listings_left key=level_id item=listings_count}
								{if $content_access_obj->isContentPermission('levels', $level_id)}
									{if $listings_count > 0 || $listings_count === 'unlimited'}
										{assign var=type value=$user_package->package->levels[$level_id]->getType()}
										<b>{if !$system_settings.single_type_structure}{$type->name} - {/if}<i>{$user_package->package->levels[$level_id]->name}</i>:</b> {if $listings_count !== 'unlimited'}{$listings_count} {$LANG_LISTINGS}{else}<span class="green">{$LANG_UNLIMITED}</span>{/if}<br />
										{assign var="package_available_for_user" value=1}
									{/if}
								{/if}
							{/foreach}
						</td>
						<td>
							{$user_package->creation_date|date_format:"%D %T"}
						</td>
						<td>
							{if $user_package->status == 1 && $package_available_for_user}
							<nobr>
								<a href="{$VH->site_url("admin/listings/create/")}">{$LANG_CREATE_LISTING_IN_PACKAGE}</a>
							</nobr>
							{/if}
						</td>
					</tr>
					{/foreach}
					</table>
					{/if}
				</div>

{include file="backend/admin_footer.tpl"}