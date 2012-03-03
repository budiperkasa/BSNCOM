<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>{if $title}{$title} - {/if}{$site_settings.website_title}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
{foreach from=$css_files item=css_media key=css_file}
		<link rel="stylesheet" href="{$css_file}" media="{$css_media}" type="text/css" />
{/foreach}
{foreach from=$ex_css_files item=ex_css_item}
		<link rel="stylesheet" href="{$ex_css_item}" type="text/css" />
{/foreach}
{foreach from=$ex_js_scripts item=ex_js_item}
		<script language="JavaScript" type="text/javascript" src="{$ex_js_item}"></script>
{/foreach}

{if !$CI->config->item('combine_static_files') || $CI->config->item('combine_static_files') === null}
	{foreach from=$js_scripts item=js_item}
			<script language="JavaScript" type="text/javascript" src="{$VH->base_url()}{$js_item}"></script>
	{/foreach}
{else}
	<script language="JavaScript" type="text/javascript" src="{$VH->base_url()},{$VH->implode(',', $js_scripts)}"></script>
{/if}
		<link rel="shortcut icon" href="{$public_path}images/favicon.ico" >
	</head>
<body>
		<div id="print_main">
      			<div id="content_block">
      				<div id="content_wrapper">
						<div id="listing_header_block">
	                        <div style="float: left; width: 345px;">
	                        	<input type="button" onclick="window.print();" value="{$LANG_PRINT_PAGE_BTN}">&nbsp;&nbsp;&nbsp;<input type="button" onclick="window.close();" value="{$LANG_BUTTON_CLOSE}">
	                        	<div class="px10"></div>
	                        	<img src="{$users_content}/users_images/site_logo/{$system_settings.site_logo_file}" >
	                        	<div class="px10"></div>
	                        	<h1 class="user_login">{$user->login}</h1>
	                        	<div class="listing_author">{$LANG_USER_REGISTERED} {$user->registration_date|date_format:"%D %H:%M"}</div>
		                        <div class="clear_float"></div>
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
                        	page='index'
                        	search_owner=$user->login
                        	search_location=$current_location
                        	orderby='l.creation_date'
                        	search_status=1
                        	search_users_status=2
                        	limit=3
                        }
						<div class="px10"></div>
						<input type="button" onclick="window.print();" value="{$LANG_PRINT_PAGE_BTN}">&nbsp;&nbsp;&nbsp;<input type="button" onclick="window.close();" value="{$LANG_BUTTON_CLOSE}">
                 	</div>
			</div>
		</div>
</body>
</html>