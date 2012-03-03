{if $locations_tree}
{assign var=checked_locations value=$banner->getCheckedLocations()}
<script language="javascript" type="text/javascript">
	var ajax_locations_url = "{$VH->site_url('ajax/locations_request/backend/locations/admin_management_for_banners.tpl')}";
	var locations_stat =  [
	{include file='backend/locations/admin_management_for_banners.tpl' assign=template}
	{assign var=i value=0}
	{foreach from=$locations_tree item=location}
		{$location->render($template, false, $max_depth, $checked_locations, ', "checked" : "true"', ', state : "closed"', $is_only_labeled)}
	{/foreach}
	];

	jQuery( function($) {ldelim}
		function locations_check_checkboxes() {ldelim}
			$('#locations_list li[checked=true]').each(function() {ldelim}
				$.jstree._reference("#locations_list").check_node($(this));
			{rdelim});
		{rdelim}

		function locations_checkedSerialize() {ldelim}
			var checked_list = [];
			$.jstree._reference("#locations_list").get_checked().each(function() {ldelim}
				checked_list.push(($(this).attr("id")+"").replace('location_', ''));
			{rdelim});
			$('#locations_checked_list').attr('value', checked_list.join('*'));
		{rdelim}

		function locations_checkNode(node) {ldelim}
			$(node).attr("checked", "true");
			locations_checkedSerialize();
		{rdelim}

		function locations_uncheckNode(node) {ldelim}
			$(node).removeAttr("checked");
			locations_checkedSerialize();
		{rdelim}

		function locations_nesting() {ldelim}
			$("#locations_list").jstree({ldelim}
				"themes" : {ldelim}
					"theme" : "classic",
					"url" : "{$smarty_obj->getFileInTheme('css/jsTree_themes_v10/classic/style.css')}",
					"icons" : false
				{rdelim},
				"json_data" : {ldelim}
					"data" : locations_stat,
					"ajax" : {ldelim}
						"url" : ajax_locations_url,
						"data" : function (n) {ldelim}
							return {ldelim} id : ($(n).attr("id")+"").replace('location_', ''), max_depth : '{$max_depth}', selected_locations : JSON.stringify($('#locations_checked_list').val().split('*')), highlight_element : ', \"checked\" : \"true\"', is_children_label : ', "state" : "closed"', is_only_labeled : '{$is_only_labeled}' {rdelim};
						{rdelim},
						"type" : "post"
					{rdelim},
					"progressive_render" : true
				{rdelim},
				"core" : {ldelim}
						"html_titles" : true,
						"animation" : 100
				{rdelim},
				"plugins" : ["themes", "json_data", "checkbox"],
				"checkbox" : {ldelim} "two_state" : true {rdelim}
			{rdelim}).bind("check_node.jstree", function (e, data) {ldelim}
				locations_checkNode(data.rslt.obj);
			{rdelim}).bind("uncheck_node.jstree", function (e, data) {ldelim}
				locations_uncheckNode(data.rslt.obj);
			{rdelim}).bind("open_node.jstree", function (e, data) {ldelim}
				locations_check_checkboxes();
			{rdelim}).bind("loaded.jstree", function () {ldelim}
				locations_check_checkboxes();
			{rdelim});
			$.jstree._reference("#locations_list").show_checkboxes();
		{rdelim}
	
		locations_nesting();

		$("#use_all_locations").click( function() {ldelim}
			if ($('#use_all_locations').is(':checked')) {ldelim}
				$("#show_hide_locations").hide(200);
			{rdelim} else {ldelim}
				$("#show_hide_locations").show(200);
			{rdelim}
		{rdelim});
	{rdelim});
</script>

<div class="admin_option">
	<div class="admin_option_name" >
		{$LANG_BANNERS_IN_LOCATIONS}<span class="red_asterisk">*</span>
	</div>

	<label><input type="checkbox" name="use_all_locations" id="use_all_locations" value="1" {if $banner->isAllLocationsChecked()}checked="checked"{/if} /> {$LANG_BANNERS_IN_ALL_LOCATIONS}</label>
	<div id="show_hide_locations" {if $banner->isAllLocationsChecked()}style="display:none"{/if}>
		<div id="locations_list"></div>
		<input type="hidden" id="locations_checked_list" name="locations_checked_list" value="{$VH->implode('*', $checked_locations)}" />
	</div>
</div>
{/if}