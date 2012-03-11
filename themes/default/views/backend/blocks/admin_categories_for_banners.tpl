{if $categories_tree}
{assign var=types_model value=$CI->load->model('types', 'types_levels')}
{assign var=types_with_local_categories value=$types_model->selectLocalCategoriesTypes()}

{assign var=checked_categories value=$banner->getCheckedCategories()}
<script language="javascript" type="text/javascript">
	var ajax_categories_url = "{$VH->site_url('ajax/categories_request/backend/categories/admin_management_for_banners.tpl')}";
	var categories_stat =  [
	{include file='backend/categories/admin_management_for_banners.tpl' assign=template}
	{assign var=i value=0}
	{foreach from=$categories_tree item=category}
		{$category->render($template, false, $max_depth, $checked_categories, ' "checked" : "true"', ', state : "closed"')}
	{/foreach}
	];

	jQuery( function($) {ldelim}
		function categories_check_checkboxes() {ldelim}
			var checked_list = $('#categories_checked_list').val().split('*');
			$('#categories_list li').each(function() {ldelim}
				var check_this_id = ($(this).attr("id")+"").replace('category_', '');
				if ($.inArray(check_this_id, checked_list) != -1) {ldelim}
					$.jstree._reference("#categories_list").check_node($(this));
				{rdelim}
			{rdelim});
		{rdelim}

		function categories_checkedSerialize(node, unchecked) {ldelim}
			var checked_id = ($(node).attr("id")+"").replace('category_', '');
			var checked_list = $('#categories_checked_list').val().split('*');

			if (!unchecked && $.inArray(checked_id, checked_list) == -1) {ldelim}
				checked_list.push(checked_id);
			{rdelim}
			if (unchecked && $.inArray(checked_id, checked_list) != -1) {ldelim}
				for (var i in checked_list) {ldelim}
				    if(checked_list[i] == checked_id) {ldelim}
				    	checked_list.splice(i, 1);
				        break;
					{rdelim}
				{rdelim}
			{rdelim}
			$('#categories_checked_list').attr('value', checked_list.join('*'));
		{rdelim}

		function categories_nesting() {ldelim}
			$("#categories_list").jstree({ldelim}
				"themes" : {ldelim}
					"theme" : "classic",
					"url" : "{$smarty_obj->getFileInTheme('css/jsTree_themes_v10/classic/style.css')}",
					"icons" : true
				{rdelim},
				"json_data" : {ldelim}
					"data" : categories_stat,
					"ajax" : {ldelim}
						"url" : ajax_categories_url,
						"data" : function (n) {ldelim}
							return {ldelim} id : ($(n).attr("id")+"").replace('category_', ''), max_depth : '{$max_depth}', selected_categories : JSON.stringify($('#categories_checked_list').val().split('*')), highlight_element : ' \"checked\" : \"true\"', is_children_label : ', "state" : "closed"' {rdelim};
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
				categories_checkedSerialize(data.rslt.obj, false);
			{rdelim}).bind("uncheck_node.jstree", function (e, data) {ldelim}
				categories_checkedSerialize(data.rslt.obj, true);
			{rdelim}).bind("open_node.jstree", function (e, data) {ldelim}
				categories_check_checkboxes();
			{rdelim}).bind("loaded.jstree", function () {ldelim}
				categories_check_checkboxes();
			{rdelim});
		{rdelim}

		categories_nesting();

		$("#use_all_categories").click( function() {ldelim}
			if ($('#use_all_categories').is(':checked')) {ldelim}
				$("#show_hide_categories").hide(200);
			{rdelim} else {ldelim}
				$("#show_hide_categories").show(200);
			{rdelim}
		{rdelim});

		$("#select_categories_list").change( function() {ldelim}
			ajax_loader_show("Loading categories list...");
			var selected_type_id = $("#select_categories_list option:selected").val();
			$.post(ajax_categories_url, {ldelim} id:0, type_id:selected_type_id, max_depth:'{$max_depth}', selected_categories:JSON.stringify($('#categories_checked_list').val().split('*')), highlight_element:' \"checked\" : \"true\"', is_children_label:', "state" : "closed"' {rdelim}, function(data) {ldelim}
				$.jstree._reference("#categories_list")._get_settings().json_data.data = data;
				$.jstree._reference("#categories_list").refresh(-1);
				categories_check_checkboxes();
				ajax_loader_hide();
			{rdelim}, "json");
		{rdelim});
	{rdelim});
</script>

<div class="admin_option">
	<div class="admin_option_name" >
		{$LANG_BANNERS_IN_CATEGORIES}<span class="red_asterisk">*</span>
	</div>

	<label><input type="checkbox" name="use_all_categories" id="use_all_categories" value="1" {if $banner->isAllCategoriesChecked()}checked="checked"{/if} /> {$LANG_BANNERS_IN_ALL_CATEGORIES}</label>
	<div id="show_hide_categories" {if $banner->isAllCategoriesChecked()}style="display:none"{/if}>
		{if $types_with_local_categories|@count > 0}
		<select name="select_categories_list" id="select_categories_list">
			<option value="0">{$LANG_BANNERS_GLOBAL_CATEGORIES}</option>
			{foreach from=$types_with_local_categories item=type}
			<option value="{$type.id}">{$type.name}</option>
			{/foreach}
		</select>
		<br />
		<br />
		{/if}
		
		<div id="categories_list"></div>
		<input type="hidden" id="categories_checked_list" name="categories_checked_list" value="{$VH->implode('*', $checked_categories)}" />
	</div>
</div>
{/if}