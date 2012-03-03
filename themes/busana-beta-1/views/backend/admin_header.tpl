<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>{$title}{if $site_settings.website_title} - {$site_settings.website_title}{/if}</title>
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
<div id="messages"></div>
<div id="ajax_loader"><img src="{$public_path}images/ajax-loader.gif"></div>
<div id="wrapper">
      <div id="header_content">
           <table width="100%">
           	<tr>
           		<td id="header_left">
           			{if $system_settings.site_logo_file}
           				<a href="{$VH->site_url()}">
           					<img src="{$users_content}/users_images/site_logo/{$system_settings.site_logo_file}">
           				</a>
           			{/if}
           		</td>
           		<td id="header_right">
           			<!-- If i18n module enabled - insert languages panel -->
           			{if $CI->load->is_module_loaded('i18n')}
           				{$VH->buildLanguagesPanels($CI)}
           			{/if}
           		</td>
           	</tr>
           </table>
      </div>

      <div id="main">
      	<table width="100%">
      		<tr id="content_table">
      			{if $CI->router->fetch_module() != 'authorization' && $CI->router->fetch_module() != 'install'}
      				{$VH->buildAdminMenu($CI)}
      			{/if}
      			<td style="padding: 0 0 0 10px;">
      				<div id="content_wrapper">
      				{$VH->buildBreadcrumbs($CI)}