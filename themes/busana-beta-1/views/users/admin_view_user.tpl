{include file="backend/admin_header.tpl"}
{assign var="user_id" value=$user->id}
{assign var="user_login" value=$user->login}

                <div class="content">
                    <h3>{$LANG_VIEW_USER_PROFILE} "{$user->login}"</h3>

                    {include file="users/admin_user_options_menu.tpl"}
                    
                    <label class="block_title">{$LANG_USER_INFO}</label>
                    <div class="admin_option">
						<table>
							<tr>
								<td class="table_left_side">{$LANG_USERS_LOGIN_TH}:</td>
								<td class="table_right_side">
									{$user->login}
								</td>
							</tr>
							{if $user->users_group->is_own_page}
							{if $user->users_group->use_seo_name}
							<tr>
								<td class="table_left_side">{$LANG_USER_SEO_LOGIN}:</td>
								<td class="table_right_side">
									{$user->seo_login}
								</td>
							</tr>
							{/if}
							{if $user->users_group->meta_enabled}
							<tr>
								<td class="table_left_side">{$LANG_USER_META_DESCRIPTION}:</td>
								<td class="table_right_side">
									{$user->meta_description}
								</td>
							</tr>
							<tr>
								<td class="table_left_side">{$LANG_USER_KEYWORDS}:</td>
								<td class="table_right_side">
									{$user->meta_keywords}
								</td>
							</tr>
							{/if}
							{/if}
							<tr>
								<td class="table_left_side">{$LANG_USERS_EMAIL_TH}:</td>
								<td class="table_right_side">
									<a href="{$VH->site_url("email/send/user_id/$user_id")}" class="nyroModal noborder" title="{$LANG_SEND_EMAIL_TO_USER}">{$user->email}</a>
								</td>
							</tr>
							<tr>
								<td class="table_left_side">{$LANG_USERS_GROUP_NAME_TH}:</td>
								<td class="table_right_side">
									<a href="{$VH->site_url("admin/users/change_group/$user_id")}" title="{$LANG_USER_CHANGE_GROUP_OPTION}">{$user->users_group->name}</a>
								</td>
							</tr>
							<tr>
								<td class="table_left_side">{$LANG_USERS_STATUS_TH}:</td>
								<td class="table_right_side">
									{if $user->status == 1}<span class="user_first">{$LANG_USER_FIRST_USER}</span>{/if}
                         			{if $user->status == 2}<a href="{$VH->site_url("admin/users/change_status/$user_id")}" class="status_active" title="{$LANG_USER_CHANGE_STATUS_OPTION}">{$LANG_USER_STATUS_ACTIVE}</a>{/if}
                         			{if $user->status == 3}<a href="{$VH->site_url("admin/users/change_status/$user_id")}" class="status_blocked" title="{$LANG_USER_CHANGE_STATUS_OPTION}">{$LANG_USER_STATUS_BLOCKED}</a>{/if}
                         			&nbsp;
								</td>
							</tr>
							<tr>
								<td class="table_left_side">{$LANG_USERS_LAST_IP_TH}:</td>
								<td class="table_right_side">{$user->last_ip}</td>
							</tr>
							<tr>
								<td class="table_left_side">{$LANG_USERS_LAST_VISIT_TH}:</td>
								<td class="table_right_side">{$user->last_login_date|date_format:"%D %T"}</td>
							</tr>
							<tr>
								<td class="table_left_side">{$LANG_USERS_REGISTRATION_DATE_TH}:</td>
								<td class="table_right_side">{$user->registration_date|date_format:"%D %T"}</td>
							</tr>
							{if $user->users_group->logo_enabled && $user->use_facebook_logo && $user->facebook_logo_file}
							<tr>
								<td class="table_left_side">{$LANG_FACEBOOK_USER_LOGO_IMAGE}:</td>
								<td class="table_right_side"><img src="{$user->facebook_logo_file}" /></td>
							</tr>
							{/if}
							{if $user->users_group->logo_enabled && !$user->use_facebook_logo && $user->user_logo_image}
							<tr>
								<td class="table_left_side">{$LANG_CUSTOM_USER_LOGO_IMAGE}:</td>
								<td class="table_right_side">
									<div id="img_wrapper">
										<div id="img_div_border" style="width: {$user->users_group->explodeSize('logo_size', 'width')}px; height: {$user->users_group->explodeSize('logo_size', 'height')}px;">
											<div id="img_div" style="width: {$user->users_group->explodeSize('logo_size', 'width')}px; height: {$user->users_group->explodeSize('logo_size', 'height')}px;">
												<table width="{$user->users_group->explodeSize('logo_size', 'width')+4}px" height="{$user->users_group->explodeSize('logo_size', 'height')+4}px">
													<tr>
														<td valign="middle" align="center">
															<img id="img" src="{$users_content}/users_images/users_logos/{$user->user_logo_image}">
														</td>
													</tr>
												</table>
											</div>
										</div>
									</div>
								</td>
							</tr>
							{/if}
						</table>
					</div>

                    {if $user->content_fields->fieldsCount() && $user->content_fields->isAnyValue()}
						<label class="block_title">{$LANG_ADDITIONAL_FIELDS}</label>
						<div class="admin_option">
		                   	{$user->content_fields->outputMode()}
						</div>
                    {/if}
                </div>

{include file="backend/admin_footer.tpl"}