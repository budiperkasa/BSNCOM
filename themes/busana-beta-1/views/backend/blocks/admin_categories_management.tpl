{if $categories_tree}
<script language="javascript" type="text/javascript">
var ajax_categories_url = "{$VH->site_url('ajax/categories_request/backend/categories/admin_management.tpl')}";
var stat =  [
{include file='backend/categories/admin_management.tpl' assign=template}
{assign var=i value=0}
{foreach from=$categories_tree item=category}
	{$category->render($template, false, $max_depth, null, '', ', state : "closed"')}
{/foreach}
];

jQuery( function($) {ldelim}
	function liSerialize() {ldelim}
		serialized = '';
		$("#categories_list > ul > li").each(function() {ldelim}
			if ($(this).attr('id')) {ldelim}
				serialized += '*[' + ($(this).attr("id")+"").replace('category_', '') + ']';
				recursiveLiSerialize(this);
			{rdelim}
		{rdelim});
		$('#list').attr('value', serialized);
	{rdelim}
	
	function recursiveLiSerialize(li) {ldelim}
		$("#" + $(li).attr('id') + " > ul > li").each(function() {ldelim}
			if ($(this).attr('id'))
			    serialized += '*[' + ($(li).attr("id")+"").replace('category_', '') + '-' + ($(this).attr("id")+"").replace('category_', '') + ']';
			recursiveLiSerialize(this);
		{rdelim});
	{rdelim}

	function nesting() {ldelim}
		$("#categories_list").jstree({ldelim}
			"themes" : {ldelim}
				"theme" : "classic",
				"url" : "{$smarty_obj->getFileInTheme('css/jsTree_themes_v10/classic/style.css')}",
				"icons" : true
			{rdelim},
			"json_data" : {ldelim}
				"data" : stat,
				"ajax" : {ldelim}
					"url" : ajax_categories_url,
					"data" : function (n) {ldelim}
						return {ldelim} id : ($(n).attr("id")+"").replace('category_', ''), max_depth : '{$max_depth}', is_children_label : ', "state" : "closed"' {rdelim};
					{rdelim},
					"type" : "post"
				{rdelim},
				"progressive_render" : true
			{rdelim},
			"core" : {ldelim}
					"html_titles" : true,
					"animation" : 100
			{rdelim},
			"plugins" : ["themes", "json_data", "dnd"]
		{rdelim}).bind("move_node.jstree", function (e, data) {ldelim}
			liSerialize();
		{rdelim});
	{rdelim}
	
	nesting();
	liSerialize();
{rdelim});
</script>

<input type="hidden" id="list" name="list">
<div id="categories_list"></div>
{/if}