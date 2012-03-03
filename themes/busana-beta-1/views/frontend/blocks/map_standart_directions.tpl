{if $items_array}
{if $clasterization}
<script language="JavaScript" type="text/javascript" src="{$smarty_obj->getFileInTheme('js/google_maps_clasterer.js')}"></script>
{/if}
<script language="Javascript" type="text/javascript">
	map_markers_attrs_array.push(new map_markers_attrs({$unique_map_id}, eval({$items_array})));
	var global_map_icons_path = '{$public_path}map_icons/';
	var global_server_path = '{$users_content}';
	var view_listing_label = '{addslashes string=$LANG_VIEW_LISTING}';
	var view_summary_label = '{addslashes string=$LANG_LISTING_SUMMARY}';
</script>

<div id="maps_canvas_{$unique_map_id}" class="maps_canvas" {if $map_width || $map_height}style="{if $map_width}width: {$map_width}px;{/if} {if $map_height}height: {$map_height}px;{/if}"{/if}></div>
From: <input type="text" size="60" id="from_direction_{$unique_map_id}" />
<div class="px3"></div>
To:
{if $common_locations_array|@count > 1}
	<div class="px3"></div>
{/if}
{assign var="i" value=1}
{foreach from=$common_locations_array item=location}
	<input type="radio" name="select_direction" class="select_direction_{$unique_map_id}" {if $common_locations_array|@count == 1}style="display:none"{/if} {if $i++ == 1}checked{/if} value="{$location->compileAddress()}" />&nbsp;<b>{$location->compileAddress()}</b>
	<div class="px3"></div>
{/foreach}
<input type="button" class="direction_button front-btn" id="get_direction_button_{$unique_map_id}" value="Get direction">
<div class="px10"></div>
<div id="route_{$unique_map_id}"></div>
{/if}