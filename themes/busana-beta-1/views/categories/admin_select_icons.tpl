<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>{$LANG_CATEGORIES_SELECT_ICONS_TITLE}</title>
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
	</head>
<body>
	{if !$multiple_select}
	<h1>{$LANG_SELECT_ICON_LISTING}</h1>
	<h2>{$LANG_SELECT_ICON_LISTING_NOTE}</h2>
	<div class="left_align"><button class="reset_icon">{$LANG_RESET_ICON}</button></div>
	{/if}
	<div>
		<table width="100%" cellpadding="0" cellspacing="0">
			<tr>
			{assign var="i" value=0}
			{foreach from=$themes item=theme}
			{if $theme.icons|@count}
				<td align="left" valign="top" width="33%">
					<div class="theme_block">
						<div class="theme_name">{$theme.name}</div>
						{if $multiple_select}<div><a href="javascript: void(0);" id="select_{$theme.id}" class="select_all">{$LANG_SELECT_ALL}</a>&nbsp;&nbsp;<a href="javascript: void(0);" id="deselect_{$theme.id}" class="deselect_all">{$LANG_DESELECT_ALL}</a></div>{/if}
						{foreach from=$theme.icons item=icon}
							<div class="icon icon_{$icon.id} select_{$theme.id} deselect_{$theme.id}" icon_id="{$icon.id}" icon_file="{$theme.folder_name}/{$icon.file_name}"><img src="{$public_path}map_icons/icons/{$theme.folder_name}/{$icon.file_name}" title="{$icon.name}" /></div>
						{/foreach}
					</div>
					<div class="clear_float"></div>
				</td>
				{if $i++ == 2}
					</tr><tr>
					{assign var="i" value=0}
				{/if}
			{/if}
			{/foreach}
			</tr>
		</table>
	</div>
</body>
</html>