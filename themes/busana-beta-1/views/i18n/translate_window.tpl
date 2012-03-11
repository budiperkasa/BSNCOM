<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>{$title} - {$site_settings.website_title}</title>
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
	<div id="translate">
		{if $translated}
			<div class="success_msg rounded_corners">
				<ul>
					<li>{$LANG_TRANSLATION_SUCCESS}</li>
				</ul>
			</div>
			<script language="javascript" type="text/javascript">
				window.onload = function() {ldelim}
					$("#translate").oneTime("3s", function() {ldelim}
						window.close();
					{rdelim});
				{rdelim}
			</script>
		{/if}
		{$LANG_CONTENT_WILL_BE_TRANSLATED} <strong>{$language->name} ({$language->code})</strong>
		{if $row_id == 'new'}
		<br/>
		<i>({$LANG_CONTENT_WILL_BE_TRANSLATED_2})</i>
		{/if}
		<div>
			<div style="margin-bottom: 10px;">
				<img src="{$public_path}images/flags/{$language->flag}">
			</div>
		</div>
		<form action="" method="post">
		<div class="translated_input">
			{if $field_type == 'string'}
			<input name="translated_input" type="text" size="70" value="{$lang_area_value}">
			{else}
				{if $field_type == 'text' || $field_type == 'keywords'}
					<textarea name="translated_input" cols="70" rows="8">{$lang_area_value}</textarea>
				{else}
					{if $field_type == 'richtext'}
						{$richtext}
					{/if}
				{/if}
			{/if}
		</div>
		<br/>
		<input type="submit" name="submit" value="{$LANG_BUTTON_TRANSLATE}">&nbsp;&nbsp;&nbsp;<input type="button" onclick="window.close();" name="close" value="{$LANG_BUTTON_CLOSE}">
		</form>
	</div>
</body>
</html>