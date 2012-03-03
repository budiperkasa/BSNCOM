{include file="backend/admin_header.tpl"}

                <div class="content">
                     <h3>{$LANG_CREATE_LISTING}</h3>
                     <h4>{$LANG_CREATE_STEP1}</h4>
                     
                     {if $CI->load->is_module_loaded('packages') && $system_settings.packages_listings_creation_mode !== 'standalone_mode' && $content_access_obj->isPermission('Use packages')}
	                     {if $my_packages_obj->packages|@count}
	                     	<div class="px10"></div>
	                     	<h4>{$LANG_PACKAGES_CREATE_UNDER_MEMBERSHIP}</h4>
	                     	<table class="advertiseTable" border="0" cellpadding="0" cellspacing="0">
	                         	<tr class="th_header">
	                         		<th>{$LANG_PACKAGE_NAME}</th>
	                         		<th>{$LANG_PACKAGE_STATUS_TH}</th>
	                         		<th>{$LANG_PACKAGE_LISTINGS_AVAILABLE}</th>
	                         		<th>&nbsp;</th>
	                         	</tr>
	                         	{foreach from=$my_packages_obj->packages item=user_package}
								{assign var=user_package_id value=$user_package->id}
								<tr>
									<td>
										{$user_package->package->name}
									</td>
									<td>
										{if $user_package->status == 1}<span class="status_active">{$LANG_STATUS_ACTIVE}</span>{/if}
										{if $user_package->status == 2}<span class="status_blocked">{$LANG_STATUS_BLOCKED}</span>{/if}
										{if $user_package->status == 3}<span class="status_notpaid">{$LANG_STATUS_NOTPAID}</span>{/if}
									</td>
									<td>
										{if $user_package->status == 1}
											{foreach from=$user_package->listings_left key=level_id item=listings_count}
												{if $content_access_obj->isContentPermission('levels', $level_id)}
													<b>{$user_package->package->levels[$level_id]->name}</b>: {if $listings_count !== 'unlimited'}{$listings_count} {$LANG_LISTINGS}{else}<span class="green">{$LANG_UNLIMITED}</span>{/if}<br />
												{/if}
											{/foreach}
										{/if}
									</td>
									<td>
										{if $user_package->status == 1}
											{foreach from=$user_package->listings_left key=level_id item=listings_count}
											{assign var="user_package_id" value=$user_package->id}
												{if $content_access_obj->isContentPermission('levels', $level_id)}
													{if ($listings_count === 'unlimited' || $listings_count > 0)}
														<a href="{$VH->site_url("admin/listings/create/level_id/$level_id/user_package_id/$user_package_id")}">{$LANG_PACKAGES_CREATE_LISTING_LINK}</a>
													{/if}
													<br />
												{/if}
											{/foreach}
										{/if}
									</td>
								</tr>
								<tr>
									<td class="gap" colspan="16">&nbsp;</td>
								</tr>
								{/foreach}
							</table>
							<div class="px10"></div>
	                    {/if}
	                    
	                    <a href="{$VH->site_url("admin/packages/add/")}"><img src="{$public_path}images/treeview_img/package_add.png"></a> <a href="{$VH->site_url("admin/packages/add/")}">{$LANG_ADD_PACKAGE_TITLE}</a>
	                    <div class="px10"></div>
	                    <div class="px10"></div>

	                    {if $system_settings.packages_listings_creation_mode !== 'memberships_mode'}
							<h4>{$LANG_PACKAGES_CREATE_STANFALONE}</h4>
						{/if}
                     {/if}

                     	{if $types|@count}
                         <table class="advertiseTable" border="0" cellpadding="0" cellspacing="0">
                         	<tr class="th_header">
                         		{if !$system_settings.single_type_structure}
                         		<th>&nbsp;</th>
                         		{/if}
                         		<th>{$LANG_LEVELS_TH}</th>
                         		{if !$CI->load->is_module_loaded('packages') || $system_settings.packages_listings_creation_mode !== 'memberships_mode'}
                         		<th>{$LANG_PRICE_TH}</th>
                         		{/if}
                         		<th title="{$LANG_PERIOD_ALT}">{$LANG_PERIOD_TH}</th>
                         		<th title="{$LANG_FEATURED_ALT}">{$LANG_FEATURED_TH}</th>
                         		<th title="{$LANG_LOGO_ALT}">{$LANG_LOGO_TH}</th>
                         		<th title="{$LANG_LOCATIONS_ALT}">{$LANG_LOCATIONS_TH}</th>
                         		<th title="{$LANG_MAP_ALT}">{$LANG_MAP_TH}</th>
                         		<th title="{$LANG_CATEGORIES_ALT}">{$LANG_CATEGORIES_TH}</th>
                         		<th title="{$LANG_LOCATIONS_ALT}">{$LANG_LOCATIONS_TH}</th>
                         		<th title="{$LANG_IMAGES_ALT}">{$LANG_IMAGES_TH}</th>
                         		<th title="{$LANG_VIDEOS_ALT}">{$LANG_VIDEOS_TH}</th>
                         		<th title="{$LANG_FILES_ALT}">{$LANG_FILES_TH}</th>
                         		<th title="{$LANG_RATINGS}">{$LANG_RATINGS}</th>
                         		<th title="{$LANG_REVIEWS}">{$LANG_REVIEWS}</th>
                         		<th width="90px">&nbsp;</th>
                         	</tr>
                         	{foreach from=$types item=type}
                         	{if $type->levels|@count > 0 && $type->isAnyLevelAvailable()}
							<tr>
								{if !$system_settings.single_type_structure}
								<td class="type_name">
									{$type->name}:
								</td>
								{/if}
								<td class="td_header">
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
									{foreach from=$type->levels item=level}
									{if $content_access_obj->isContentPermission('levels', $level->id)}
										<tr>
											<td class="sub_cell">
												{$level->name}
											</td>
										</tr>
									{/if}
									{/foreach}
									</table>
								</td>
								{if !$CI->load->is_module_loaded('packages') || $system_settings.packages_listings_creation_mode !== 'memberships_mode'}
								<td>
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
									{foreach from=$type->levels item=level}
									{if $content_access_obj->isContentPermission('levels', $level->id)}
										<tr>
											<td class="sub_cell">
												{if $level->price_value == null || $level->price_value == 0}<span class="free">{$LANG_FREE}</span>{else}{$level->price_currency}&nbsp;{$VH->number_format($level->price_value, 2, $decimals_separator, $thousands_separator)}{/if}
											</td>
										</tr>
									{/if}
									{/foreach}
									</table>
								</td>
								{/if}
								<td>
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
									{foreach from=$type->levels item=level}
									{if $content_access_obj->isContentPermission('levels', $level->id)}
										<tr>
											<td class="sub_cell" title="{$LANG_PERIOD_TD_ALT}">
												{if !$level->active_years && !$level->active_months && !$level->active_days}
													<span class="green">{$LANG_UNLIMITED}</span>
												{else}
													{if $level->active_years}
													{$LANG_YEARS}:&nbsp;<b>{$level->active_years}</b><br />
													{/if}
													{if $level->active_months}
													{$LANG_MONTHS}:&nbsp;<b>{$level->active_months}</b><br />
													{/if}
													{if $level->active_days}
													{$LANG_DAYS}:&nbsp;<b>{$level->active_days}</b>
													{/if}
												{/if}
											</td>
										</tr>
									{/if}
									{/foreach}
									</table>
								</td>
								<td>
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
									{foreach from=$type->levels item=level}
									{if $content_access_obj->isContentPermission('levels', $level->id)}
										<tr>
											<td class="sub_cell">
												{if $level->featured}<img src="{$public_path}images/icons/accept.png" />{else}<img src="{$public_path}images/icons/delete.png" />{/if}
											</td>
										</tr>
									{/if}
									{/foreach}
									</table>
								</td>
								<td>
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
									{foreach from=$type->levels item=level}
									{if $content_access_obj->isContentPermission('levels', $level->id)}
										<tr>
											<td class="sub_cell">
												{if $level->logo_enabled}<img src="{$public_path}images/icons/accept.png" />{else}<img src="{$public_path}images/icons/delete.png" />{/if}
											</td>
										</tr>
									{/if}
									{/foreach}
									</table>
								</td>
								<td>
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
									{foreach from=$type->levels item=level}
									{if $content_access_obj->isContentPermission('levels', $level->id)}
										<tr>
											<td class="sub_cell">
												{if $type->locations_enabled}<img src="{$public_path}images/icons/accept.png" />{else}<img src="{$public_path}images/icons/delete.png" />{/if}
											</td>
										</tr>
									{/if}
									{/foreach}
									</table>
								</td>
								<td>
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
									{foreach from=$type->levels item=level}
									{if $content_access_obj->isContentPermission('levels', $level->id)}
										<tr>
											<td class="sub_cell">
												{if $level->maps_enabled}<img src="{$public_path}images/icons/accept.png" />{else}<img src="{$public_path}images/icons/delete.png" />{/if}
											</td>
										</tr>
									{/if}
									{/foreach}
									</table>
								</td>
								<td>
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
									{foreach from=$type->levels item=level}
									{if $content_access_obj->isContentPermission('levels', $level->id)}
										<tr>
											<td class="sub_cell">
											{$level->categories_number}
											</td>
										</tr>
									{/if}
									{/foreach}
									</table>
								</td>
								<td>
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
									{foreach from=$type->levels item=level}
									{if $content_access_obj->isContentPermission('levels', $level->id)}
										<tr>
											<td class="sub_cell">
												{if $type->locations_enabled}{$level->locations_number}{else}<img src="{$public_path}images/icons/delete.png" />{/if}
											</td>
										</tr>
									{/if}
									{/foreach}
									</table>
								</td>
								<td>
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
									{foreach from=$type->levels item=level}
									{if $content_access_obj->isContentPermission('levels', $level->id)}
										<tr>
											<td class="sub_cell">
											{$level->images_count}
											</td>
										</tr>
									{/if}
									{/foreach}
									</table>
								</td>
								<td>
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
									{foreach from=$type->levels item=level}
									{if $content_access_obj->isContentPermission('levels', $level->id)}
										<tr>
											<td class="sub_cell">
											{$level->video_count}
											</td>
										</tr>
									{/if}
									{/foreach}
									</table>
								</td>
								<td>
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
									{foreach from=$type->levels item=level}
									{if $content_access_obj->isContentPermission('levels', $level->id)}
										<tr>
											<td class="sub_cell">
											{$level->files_count}
											</td>
										</tr>
									{/if}
									{/foreach}
									</table>
								</td>
								<td>
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
									{foreach from=$type->levels item=level}
									{if $content_access_obj->isContentPermission('levels', $level->id)}
										<tr>
											<td class="sub_cell">
											{if $level->ratings_enabled}<img src="{$public_path}images/icons/accept.png" />{else}<img src="{$public_path}images/icons/delete.png" />{/if}
											</td>
										</tr>
									{/if}
									{/foreach}
									</table>
								</td>
								<td>
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
									{foreach from=$type->levels item=level}
									{if $content_access_obj->isContentPermission('levels', $level->id)}
										<tr>
											<td class="sub_cell">
											{if $level->reviews_mode != 'disabled'}<img src="{$public_path}images/icons/accept.png" />{else}<img src="{$public_path}images/icons/delete.png" />{/if}
											</td>
										</tr>
									{/if}
									{/foreach}
									</table>
								</td>
								<td>
									<table width="100%" border="0" cellpadding="0" cellspacing="0">
									{foreach from=$type->levels item=level}
									{if $content_access_obj->isContentPermission('levels', $level->id)}
									{assign var="level_id" value=$level->id}
										<tr>
											<td class="sub_cell">
												{if !$CI->load->is_module_loaded('packages') || $system_settings.packages_listings_creation_mode !== 'memberships_mode'}
												<a href="{$VH->site_url("admin/listings/create/level_id/$level_id")}">{$LANG_CREATE_AN_AD}</a>
												{/if}
											</td>
										</tr>
									{/if}
									{/foreach}
									</table>
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
									<li>{$LANG_ADVERTISE_EXCLAMATION}</li>
								</ul>
							</div>
						{/if}
                </div>

{include file="backend/admin_footer.tpl"}