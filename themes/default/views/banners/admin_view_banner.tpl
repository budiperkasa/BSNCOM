{include file="backend/admin_header.tpl"}
{assign var="banner_id" value=$banner->id}
{assign var="banner_owner_id" value=$banner->owner_id}

                <div class="content">
                    <h3>{$LANG_BANNERS_VIEW_BANNER_TITLE}</h3>

                    <div class="admin_top_menu_cell">
	                    <a href="{$VH->site_url("admin/banners/create")}" title="{$LANG_CREATE_BANNER}"><img src="{$public_path}/images/buttons/page_add.png" /></a>
	                    <a href="{$VH->site_url("admin/banners/create")}">{$LANG_CREATE_BANNER}</a>
	                </div>
	                <div class="admin_top_menu_cell">
	                    <a href="{$VH->site_url("admin/banners/edit/$banner_id")}" title="{$LANG_EDIT_BANNER}"><img src="{$public_path}/images/buttons/page_edit.png" /></a>
	                    <a href="{$VH->site_url("admin/banners/edit/$banner_id")}">{$LANG_EDIT_BANNER}</a>
	                </div>
	                <div class="admin_top_menu_cell">
	                    <a href="{$VH->site_url("admin/banners/delete/$banner_id")}" title="{$LANG_DELETE_BANNER}"><img src="{$public_path}/images/buttons/page_delete.png" /></a>
	                    <a href="{$VH->site_url("admin/banners/delete/$banner_id")}">{$LANG_DELETE_BANNER}</a>
	                </div>
					<div class="clear_float"></div>
					<div class="px10"></div>

                    <label class="block_title">{$LANG_BANNER_INFO}</label>
                    <div class="admin_option">
						<table>
							<tr>
								<td class="table_left_side">{$LANG_STATUS_TH}:</td>
								<td class="table_right_side">
									{if $banner->status == 1}<a class="status_active" href="{$VH->site_url("admin/banners/change_status/$banner_id")}">{$LANG_STATUS_ACTIVE}</a>{/if}
		                         	{if $banner->status == 2}<a class="status_blocked" href="{$VH->site_url("admin/banners/change_status/$banner_id")}">{$LANG_STATUS_BLOCKED}</a>{/if}
		                         	{if $banner->status == 3}{if $content_access_obj->isPermission('Manage all banner')}<a class="status_suspended" href="{$VH->site_url("admin/banners/change_status/$banner_id")}">{$LANG_STATUS_SUSPENDED}</a>{else}<span class="status_suspended" title="{$LANG_CHANGE_BANNER_STATUS_OPTION}">{$LANG_STATUS_SUSPENDED}</span>{/if}{/if}
		                         	{if $banner->status == 4}{if $content_access_obj->isPermission('Manage all banner')}<a class="status_notpaid" href="{$VH->site_url("admin/banners/change_status/$banner_id")}">{$LANG_STATUS_NOTPAID}</a>{else}<span class="status_notpaid" title="{$LANG_STATUS_NOTPAID}">{$LANG_STATUS_NOTPAID}</span>{/if}{/if}
                         			&nbsp;
								</td>
							</tr>
							<tr>
								<td class="table_left_side">{$LANG_OWNER_TH}:</td>
								<td class="table_right_side">
									{if $banner->owner_id != 1 && $banner->owner_id != $session_user_id && $content_access_obj->getUserAccess($session_user_id, 'View all users')}
		                         	<a href="{$VH->site_url("admin/users/view/$listing_owner_id")}" title="{$LANG_VIEW_USER_OPTION}">{$banner->user->login}</a>
		                         	{else}
		                         	{$banner->user->login}
		                         	{/if}
		                         	&nbsp;
		                         </td>
							</tr>
							<tr>
								<td class="table_left_side">{$LANG_BANNERS_BLOCK_NAME_TH}:</td>
								<td class="table_right_side">{$banner->block->name}</td>
							</tr>
							<tr>
								<td class="table_left_side">{$LANG_BANNERS_BLOCK_LIMITATION_MODE_TH}:</td>
								<td class="table_right_side">
									 {if $banner->block->limit_mode == 'active_period'}{$LANG_BANNERS_ACTIVE_PERIOD_LIMITATION}{/if}
		                             {if $banner->block->limit_mode == 'clicks'}{$LANG_BANNERS_CLICKS_LIMITATION}{/if}
		                             {if $banner->block->limit_mode == 'both'}{$LANG_BANNERS_BOTH_LIMITATION}{/if}
								</td>
							</tr>
							<tr>
								<td class="table_left_side">{$LANG_BANNERS_BLOCK_CLICKS_LIMIT_TH}:</td>
								<td class="table_right_side">{$banner->block->clicks_limit}</td>
							</tr>
							<tr>
								<td class="table_left_side">{$LANG_CREATION_DATE_TH}:</td>
								<td class="table_right_side">{$banner->creation_date|date_format:"%D %T"}</td>
							</tr>
							<tr>
								<td class="table_left_side">{$LANG_EXPIRATION_DATE_TH}:</td>
								<td class="table_right_side">{$banner->expiration_date|date_format:"%D %T"}</td>
							</tr>
							<tr>
								<td class="table_left_side">{$LANG_BANNER_PROLONGED_TH}:</td>
								<td class="table_right_side">{$banner->was_prolonged_times}</td>
							</tr>
							<tr>
								<td class="table_left_side">{$LANG_BANNER_VIEWS_TH}:</td>
								<td class="table_right_side">{$banner->views}</td>
							</tr>
							<tr>
								<td class="table_left_side">{$LANG_BANNER_CLICKS_TH}:</td>
								<td class="table_right_side">{$banner->clicks_count}</td>
							</tr>
							<tr>
								<td class="table_left_side">{$LANG_BANNER_LINK_URL}:</td>
								<td class="table_right_side"><a href="{$banner->url}" target="_blank">{$banner->url}</a></td>
							</tr>
							{if $banner->banner_file}
							<tr>
								<td class="table_left_side">{$LANG_BANNER_IMAGE}:</td>
								<td class="table_right_side">
									<div>
										<div id="img_div_border" style="{if !$banner->banner_file}display:none; {/if}width: {$banner->block->explodeSize('block_size', 0)}px; height: {$banner->block->explodeSize('block_size', 1)}px;">
											<div id="img_div" style="width: {$banner->block->explodeSize('block_size', 0)}px; height: {$banner->block->explodeSize('block_size', 1)}px;">
												<table width="{$banner->block->explodeSize('block_size', 0)+4}px" height="{$banner->block->explodeSize('block_size', 1)+4}px">
													<tr>
														<td valign="middle" align="center">
															{if !$banner->is_uploaded_flash}
														    	<img id="img" src="{$users_content}banners/{$banner->banner_file}" />
														    {else}
																<script language="javascript" type="text/javascript">
													    			swfobject.embedSWF("{$users_content}banners/{$banner->banner_file}", "img_div_border", "{$banner->block->explodeSize('block_size', 0)}", "{$banner->block->explodeSize('block_size', 1)}", "9.0.0");
													    		</script>
													    	{/if}
														</td>
													</tr>
												</table>
											</div>
										</div>
									</div>
								</td>
							</tr>
							{/if}
							{if $banner->block->allow_remote_banners && $banner->remote_image_url}
							<tr>
								<td class="table_left_side">{$LANG_USE_REMOTE_BANNER}:</td>
								<td class="table_right_side">{if $banner->use_remote_image}{$LANG_ENABLED}{else}{$LANG_DISABLED}{/if}</td>
							</tr>
							<tr>
								<td class="table_left_side">{$LANG_BANNER_REMOTE_IMAGE}:</td>
								<td class="table_right_side"><a href="{$banner->remote_image_url}" target="_blank">{$banner->remote_image_url}</a></td>
							</tr>
							<tr>
								<td class="table_left_side"></td>
								<td class="table_right_side">
									<div id="remote_image_wrapper">
										{if !$banner->is_loaded_flash}
								    		<img id="remote_image" src="{$banner->remote_image_url}"/>
								    	{else}
								    		<script language="javascript" type="text/javascript">
								    			swfobject.embedSWF("{$banner->remote_image_url}", "remote_image_wrapper", "{$banner->block->explodeSize('block_size', 0)}", "{$banner->block->explodeSize('block_size', 1)}", "9.0.0");
								    		</script>
								    	{/if}
								    </div>
								</td>
							</tr>
							{/if}
						</table>
					</div>
                </div>

{include file="backend/admin_footer.tpl"}