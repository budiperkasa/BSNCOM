	<div class="current_lang_block">
{if $system_settings.multilanguage_enabled}
	{foreach from=$languages item=language_item}
	{if $language_item.active}
		{if $language_item.code != $current_language}
			{assign var=lang_code value=$language_item.code}
			{if $lang_code != $system_settings.default_language}
				{assign var=lang_segment value="lang/$lang_code/"}
			{else}
				{assign var=lang_segment value=""}
			{/if}
			
			{if $CI->config->item('enable_query_strings')}
			<a class="noscript_language" style="opacity:0.3; filter:alpha(opacity=30); zoom:1;" href="{$VH->base_url()}{$CI->config->slash_item('index_page')}?route=/{$lang_segment}{$uri_string}" title="{$LANG_CHANGE_INTERFACE} {$language_item.name}"><img src="{$public_path}images/flags/{$language_item.flag}" height="24px" /></a>&nbsp;&nbsp;
			{else}
			<a class="noscript_language" style="opacity:0.3; filter:alpha(opacity=30); zoom:1;" href="{$VH->base_url()}{$CI->config->slash_item('index_page')}{$lang_segment}{$uri_string}" title="{$LANG_CHANGE_INTERFACE} {$language_item.name}"><img src="{$public_path}images/flags/{$language_item.flag}" height="24px" /></a>&nbsp;&nbsp;
			{/if}
		{else}
			<img src="{$public_path}images/flags/{$language_item.flag}" height="24px" />&nbsp;&nbsp;
		{/if}
	{/if}
	{/foreach}
{/if}
	</div>