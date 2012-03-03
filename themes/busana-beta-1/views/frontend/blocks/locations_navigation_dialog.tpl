{if $locations_tree}
<div id="navigation_block">
	<div class="active_location">
		<span class="search_in_span">{$LANG_LOCATIONS_SEARCH_IN}:</span> <nobr>{if $current_location}<b>{$current_location->getChainAsString(' » ', false)}</b></nobr> | {/if}<nobr><img src="{$public_path}images/icons/world.png" />&nbsp;{if !$current_location}<b>{$LANG_LOCATIONS_EVERYWHERE}</b>{else}<a href="{$VH->site_url("location/any/$base_url")}" rel="nofollow">{$LANG_LOCATIONS_EVERYWHERE}</a>{/if}</nobr> | <nobr><a href="#hidden_locations" class="nyroModal">{$LANG_LOCATIONS_OTHER} >></a></nobr>
	</div>
	<div class="clear_float"></div>
	<div id="hidden_locations" style="display: none;">
	<div style="width: 690px;">
		<div><span class="search_in_span">{$LANG_LOCATIONS_SEARCH_IN}:</span>&nbsp;&nbsp;&nbsp;<img src="{$public_path}images/icons/world.png" />&nbsp;<a href="{$VH->site_url("location/any/$base_url")}" rel="nofollow">{$LANG_LOCATIONS_EVERYWHERE}</a></div>
		<div class="clear_float"></div>
		
		<table width="100%" cellspacing="0">
		<tr>
			{include file='frontend/locations/navigate.tpl' assign=template}
			{assign var=td_padding_pixels value=0}
			{assign var=i value=0}
			<td style="padding-right: {$td_padding_pixels}px" valign="top">
				{section name=key loop=$locations_tree start=0 step=3}
					{$locations_tree[key]->render($template, $is_counter, $current_location->seo_name, 'class="labeled_location_selected"', 'class="labeled_location_root"', $is_only_labeled)}
				{/section}
			</td>
			<td style="padding-right: {$td_padding_pixels}px" valign="top">
				{section name=key loop=$locations_tree start=1 step=3}
					{$locations_tree[key]->render($template, $is_counter, $current_location->seo_name, 'class="labeled_location_selected"', 'class="labeled_location_root"', $is_only_labeled)}
				{/section}
			</td>
			<td style="padding-right: {$td_padding_pixels}px" valign="top">
				{section name=key loop=$locations_tree start=2 step=3}
					{$locations_tree[key]->render($template, $is_counter, $current_location->seo_name, 'class="labeled_location_selected"', 'class="labeled_location_root"', $is_only_labeled)}
				{/section}
			</td>
		</tr>
		</table>
	</div>
	</div>
</div>
<div class="px5"></div>
{/if}