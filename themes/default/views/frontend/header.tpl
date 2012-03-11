<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>{if $title}{$title} - {/if}{$site_settings.website_title}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="description" content="{if $description}{$description}{else}{$site_settings.description}{/if}" />
		<meta name="keywords" content="{if $keywords}{$keywords}{else}{$site_settings.keywords}{/if}" />
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
		{if $CI->load->is_module_loaded('rss')}
			{if $VH->getRssTitle()}
				<link title="{$VH->getRssTitle()}" type="application/rss+xml" rel="alternate" href="{$VH->getRssUrl()}" />
			{/if}
		{/if}
		<link rel="shortcut icon" href="{$public_path}images/favicon.ico" >
	</head>
<body>
<div id="messages"></div>

<script language="Javascript" type="text/javascript">
	var in_favourites_icon = $('<img />').attr('src', '{$public_path}/images/icons/folder_star.png');
	var not_in_favourites_icon = $('<img />').attr('src', '{$public_path}/images/icons/folder_star_grscl.png');
	var to_favourites_msg = '{addslashes string=$LANG_QUICK_LIST_SUCCESS}';
	var from_favourites_msg = '{addslashes string=$LANG_QUICK_FROM_LIST_SUCCESS}';
</script>

<div id="ajax_loader"><img src="{$public_path}images/ajax-loader.gif"></div>
<!-- Wrapper Starts -->
	<div id="wrapper">

	<!-- Header Starts -->
		<div id="header_content">
		<!-- Logo Starts -->
			<div id="header_left">
				{if $system_settings.site_logo_file}
				<a href="{$VH->index_url()}">
					<img src="{$users_content}/users_images/site_logo/{$system_settings.site_logo_file}" />
				</a>
				{/if}
			</div>
		<!-- Logo Ends -->
		<!-- I18n panels Starts -->
			<div id="header_right">
				{if $CI->load->is_module_loaded('i18n')}
					{$VH->buildLanguagesPanels($CI)}
				{/if}
				{$VH->buildContentPagesMenu_top($CI)}
			</div>
		<!-- I18n panels Ends -->
		</div>
		<!-- Header Ends -->

		<!-- Menu Starts  -->
		{if !$system_settings.single_type_structure}
		<div id="menu">
			<ul>
				{assign var = i value = 0}
				{foreach from=$types item=type}
					<li><a href="{$VH->site_url($type->getUrl())}" class="{if $type->id == $current_type->id}active_type{/if}">{$type->name}</a></li>
					{if $types|@count != ($i++) + 1}
						<li>|</li>
					{/if}
				{/foreach}
			</ul>
		</div>
		{/if}
		<!-- Menu Ends -->

		<!-- Content Starts -->
		<div id="main">
			<table width="100%" valign="top" cellpadding="0" cellspacing="0">