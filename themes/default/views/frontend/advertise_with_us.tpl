{include file="frontend/header.tpl"}

			<tr>
      			<td valign="top">
      				<div id="content_wrapper">
      					{if $CI->load->is_module_loaded('packages') && $system_settings.packages_listings_creation_mode !== 'standalone_mode' && $content_access_obj->isPermission('Use packages')}
		                    {if $packages|@count}
		                    	<div class="px10"></div>
			                    <div class="px10"></div>
		                        <div class="type_line">
		                        	<div class="type_title">
		                        		{$LANG_PACKAGES_ADVERTISE_HEADER}
		                        	</div>
		                        </div>
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
												{if $packages_prices[$package_id].value == null || $packages_prices[$package_id].value == 0}<span class="free">{$LANG_FREE}</span>{else}{$packages_prices[$package_id].currency}&nbsp;{$VH->number_format($packages_prices[$package_id].value, 2, $decimals_separator, $thousands_separator)}{/if}
											</td>
											<td>
												{foreach from=$package->items key=level_id item=listings_count}
												{if $listings_count > 0 || $listings_count === 'unlimited'}
													{assign var=type value=$package->levels[$level_id]->getType()}
													<b>{if !$system_settings.single_type_structure}{$type->name} - {/if}<i>{$package->levels[$level_id]->name}</i>:</b> {if $listings_count !== 'unlimited'}{$listings_count} {$LANG_LISTINGS}{else}<span class="green">{$LANG_UNLIMITED}</span>{/if}<br />
												{/if}
												{/foreach}
											</td>
											<td>
												<a href="{$VH->site_url("admin/packages/add/$package_id/")}">{$LANG_ADD_PACKAGE_LINK}</a>
											</td>
										</tr>
										<tr>
											<td class="gap" colspan="16">&nbsp;</td>
										</tr>
									{/if}
									{/foreach}
								</table>
	                     	{/if}
	                     {/if}

      					{if $content_access_obj->isPermission('Create listings')}
      					<div class="px10"></div>
	                    <div class="px10"></div>
                        <div class="type_line">
                        	<div class="type_title">
                        		{$LANG_ADVERTISE_HEADER}
                        	</div>
                        </div>
                         
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
						{/if}
                 	</div>
                 </td>
			</tr>

{include file="frontend/footer.tpl"}