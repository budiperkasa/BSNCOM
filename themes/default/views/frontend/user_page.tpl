{include file="frontend/header.tpl"}
{assign var="user_id" value=$user->id}
{assign var="user_unique_id" value=$user->getUniqueId()}
{assign var="user_login" value=$user->login}

			<tr>
				<td id="search_bar" colspan="3">
				{include file="frontend/search_block.tpl"}
				</td>
			</tr>
			<tr>
				<td id="left_sidebar">
				{include file="frontend/left-sidebar.tpl"}
				</td>
      			<td id="content_block" valign="top">
      				<div id="content_wrapper">
						<div class="breadcrumbs"><a href="{$VH->index_url()}">{$LANG_HOME_PAGE}</a> » <span>{$user->login}</span></div>

						<div id="user_header_block">
	                        <div style="float: left; width: 345px;">
	                        	<h1 class="user_login">{$user->login}</h1>
	                        	<div class="listing_author">{$LANG_USER_REGISTERED} {$user->registration_date|date_format:"%D %H:%M"}</div>
	                        	<div class="px5"></div>

		                        {if $user->users_group->logo_enabled && ($user->user_logo_image || $user->facebook_logo_file)}
		                        <div id="user_logo">
		                        	{if $user->use_facebook_logo && $user->facebook_logo_file}
										<img src="{$user->facebook_logo_file}" />
									{elseif $user->users_group->logo_enabled && !$user->use_facebook_logo && $user->user_logo_image}
										<img src="{$users_content}/users_images/users_logos/{$user->user_logo_image}">
									{/if}
								</div>
								{/if}
							</div>

							<div id="user_options_panel">
								<a class="a2a_dd" href="http://www.addtoany.com/share_save"><img src="http://static.addtoany.com/buttons/share_save_171_16.png" width="171" height="16" border="0" alt="Share/Bookmark"/></a><script type="text/javascript" src="http://static.addtoany.com/menu/page.js"></script>
								<span class="user_options_panel_item print"><a href="javascript: void(0);" onClick="$.jqURL.loc('{$VH->site_url("print_user/$user_id/")}', {ldelim}w:590,h:750,wintype:'_blank'{rdelim}); return false;">{$LANG_PRINT_PAGE}</a></span>
								<span class="user_options_panel_item pdf"><a href="http://pdfmyurl.com/?url={$VH->site_url("users/$user_unique_id")}">{$LANG_PDF_PAGE}</a></span>
								<span class="user_options_panel_item owner"><a href="{$VH->site_url("email/send/user_id/$user_id")}" class="nyroModal" title="{$LANG_SEND_EMAIL_TO_USER}">{$LANG_SEND_EMAIL_TO_USER}</a></span>
								<span class="user_options_panel_item all_listings"><a href="{$VH->site_url("search/search_owner/$user_login")}" title="{$LANG_USER_VIEW_ALL_LISTINGS}">{$LANG_USER_VIEW_ALL_LISTINGS}</a></span>
							</div>
							<div class="clear_float"></div>
						</div>

						{if $user->content_fields->fieldsCount() && $user->content_fields->isAnyValue()}
							<div class="px10"></div>
							<h1>{$LANG_LISTING_INFORMATION}</h1>
		                   	{$user->content_fields->outputMode()}
						{/if}

						{render_frontend_block
                        	block_type='listings'
                        	block_template='frontend/blocks/listings_of_user.tpl'
                        	view_name='semitable'
                        	view_format='3*1'
                        	search_owner=$user->login
                        	search_location=$current_location
                        	orderby='l.creation_date'
                        	search_status=1
                        	search_users_status=2
                        	limit=3
                        }
                 	</div>
                </td>
                <td id="right_sidebar">
                {include file="frontend/right-sidebar.tpl"}
                </td>
			</tr>

{include file="frontend/footer.tpl"}