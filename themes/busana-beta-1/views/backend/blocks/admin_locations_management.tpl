{if $locations_tree}
{assign var=loc_levels_count value=$CI->locations->getLocationsLevelsCount()}
{assign var=curr_levels_count value=1}
{assign var=labeled_locations value=$CI->locations->selectLocationsFromDB(true, true)}
<script language="javascript" type="text/javascript">
jQuery( function($) {ldelim}
	var ajax_locations_url = "{$VH->site_url('ajax/locations_request/backend/locations/admin_management.tpl')}";
	var locations_stat =  [
	{include file='backend/locations/admin_management.tpl' assign=template}
	{foreach from=$locations_tree item=location}
		{$location->render($template, false, $max_depth, $labeled_locations, 'class=\"bold\"', ', state : "closed"', $is_only_labeled)}
	{/foreach}
	];
	
	function liSerialize() {ldelim}
		serialized = '';
		$("#locations_list > ul > li").each(function() {ldelim}
			if ($(this).attr('id')) {ldelim}
				serialized += '*[' + ($(this).attr("id")+"").replace('location_', '') + ']';
				recursiveLiSerialize(this);
			{rdelim}
		{rdelim});
		$('#list').attr('value', serialized);
	{rdelim}
	
	function recursiveLiSerialize(li) {ldelim}
		$("#" + $(li).attr('id') + " > ul > li").each(function() {ldelim}
			if ($(this).attr('id'))
			    serialized += '*[' + ($(li).attr("id")+"").replace('location_', '') + '-' + ($(this).attr("id")+"").replace('location_', '') + ']';
			recursiveLiSerialize(this);
		{rdelim});
	{rdelim}
	
	function checkedSerialize() {ldelim}
		var checked_list = [];
		$.jstree._reference("#locations_list").get_checked().each(function() {ldelim}
			checked_list.push(($(this).attr("id")+"").replace('location_', ''));
		{rdelim});
		$('#checked_list').attr('value', checked_list.join('*'));
	{rdelim}
	
	function nesting() {ldelim}
		$("#locations_list").jstree({ldelim}
			"themes" : {ldelim}
				"theme" : "classic",
				"url" : "{$smarty_obj->getFileInTheme('css/jsTree_themes_v10/classic/style.css')}",
				"icons" : true
			{rdelim},
			"json_data" : {ldelim}
				"data" : locations_stat,
				"ajax" : {ldelim}
					"url" : ajax_locations_url,
					"data" : function (n) {ldelim}
						return {ldelim} id : ($(n).attr("id")+"").replace('location_', ''), max_depth : '{$max_depth}', selected_locations : '{$VH->json_encode($labeled_locations)}', highlight_element : 'class=\\"bold\\"', is_children_label : ', "state" : "closed"', is_only_labeled : '{$is_only_labeled}' {rdelim};
					{rdelim},
					"type" : "post"
				{rdelim},
				"progressive_render" : true
			{rdelim},
			"core" : {ldelim}
					"html_titles" : true,
					"animation" : 100
			{rdelim},
			"plugins" : ["themes", "json_data", "checkbox", "dnd", "types"],
			"checkbox" : {ldelim} "two_state" : false {rdelim},
			"types" : {ldelim}
				"max_depth" : {$loc_levels_count}
			{rdelim}
		{rdelim}).bind("check_node.jstree", function (e, data) {ldelim}
			checkedSerialize();
		{rdelim}).bind("uncheck_node.jstree", function (e, data) {ldelim}
			checkedSerialize();
		{rdelim}).bind("move_node.jstree", function (e, data) {ldelim}
			liSerialize();
		{rdelim});
	{rdelim}

	nesting();
	liSerialize();
{rdelim});
</script>

<input type="hidden" id="list" name="list">
<input type="hidden" id="checked_list" name="checked_list">
<div id="locations_list"></div>
{/if}