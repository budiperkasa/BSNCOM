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

	$(document).ready(function() {ldelim}
		$("#hide_map_link_{$unique_map_id}").click(function() {ldelim}
			$("#maps_canvas_{$unique_map_id}").slideToggle(400);
		{rdelim});
	{rdelim});
</script>
{if $radius_search_args}
	{if $system_settings.search_in_raduis_measure == 'miles'}
		<input type=hidden id="map_in_miles_{$unique_map_id}" value="true">
	{else}
		<input type=hidden id="map_in_miles_{$unique_map_id}" value="false">
	{/if}
	<input type=hidden id="map_radius_{$unique_map_id}" value="{$radius_search_args.radius}">
	<input type=hidden id="map_coord_1_{$unique_map_id}" value="{$radius_search_args.map_coord_1}">
	<input type=hidden id="map_coord_2_{$unique_map_id}" value="{$radius_search_args.map_coord_2}">
{/if}
<a href="javascript: void(0)" id="hide_map_link_{$unique_map_id}">Hide/show map</a>
<div class="px5"></div>
<div id="maps_canvas_{$unique_map_id}" class="maps_canvas" {if $map_width || $map_height}style="{if $map_width}width: {$map_width}px;{/if} {if $map_height}height: {$map_height}px;{/if}"{/if}></div>
{/if}