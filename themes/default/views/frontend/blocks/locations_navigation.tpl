{if $locations_tree}
{assign var=base_url value=$VH->getBaseUrl()}
<script language="javascript" type="text/javascript">
$(document).ready(function() {ldelim}
	$("#other_locations").click(function() {ldelim}
		$("#hidden_locations").slideToggle(300, function() {ldelim}
			if ($(this).is(":visible"))
				$("#other_locations").html('<<&nbsp;{addslashes string=$LANG_LOCATIONS_HIDE}');
			else
				$("#other_locations").html('{addslashes string=$LANG_LOCATIONS_OTHER}&nbsp;>>');
		{rdelim});
		return false;
	{rdelim})
{rdelim});
</script>
<div id="navigation_block">
	<div class="active_location">
		<span class="search_in_span">
			{$LANG_LOCATIONS_SEARCH_IN_1}
			{if $current_type}<span class="search_in_span_object">{$current_type->name}</span>{/if}
			{if $current_category}<span class="search_in_span_object">{if $current_type}/{/if}{$current_category->getChainAsString()}</span>{/if}
			{$LANG_LOCATIONS_SEARCH_IN_2}:
		</span>
		&nbsp;&nbsp;{if $current_location}<b>{$current_location->getChainAsString(' » ', false)}</b>&nbsp;&nbsp;|&nbsp;&nbsp;{/if}&nbsp;{if !$current_location}<b>{$LANG_LOCATIONS_EVERYWHERE}</b>{else}<a href="{$VH->site_url("location/any/$base_url")}" rel="nofollow">{$LANG_LOCATIONS_EVERYWHERE}</a>{/if}&nbsp;&nbsp;|&nbsp;&nbsp;<a href="javascript: void(0);" id="other_locations">{$LANG_LOCATIONS_OTHER} >></a>
	</div>
	<div class="clear_float"></div>
	<div id="hidden_locations" style="display: none;">
		{include file='frontend/locations/navigate.tpl' assign=template}
		{foreach from=$locations_tree item=location}
			<div style="padding-bottom: 25px">
				{$location->render($template, $is_counter, $max_depth, $current_location->seo_name, 'class="labeled_location_selected"', 'class="labeled_location_root"', $is_only_labeled)}
			</div>
			<div class="clear_float"></div>
		{/foreach}
	</div>
</div>
<div class="px5"></div>
{/if}