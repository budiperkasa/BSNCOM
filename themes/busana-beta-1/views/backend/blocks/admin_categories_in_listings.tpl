{if $categories_tree}
<script language="javascript" type="text/javascript">
var ajax_categories_url = "{$VH->site_url('ajax/categories_request/backend/categories/admin_categories_in_listings.tpl')}";
var stat =  [
{include file='backend/categories/admin_categories_in_listings.tpl' assign=template}
{assign var=i value=0}
{foreach from=$categories_tree item=category}
	{$category->render($template, false, $max_depth, null, '', ', state : "closed"')}
{/foreach}
];

jQuery( function($) {ldelim}
	function nesting() {ldelim}
		$("#categories_tree_wrap").jstree({ldelim}
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
						return {ldelim} id : ($(n).attr("id")+"").replace('category_in_listing_', ''), is_children_label : ', "state" : "closed"' {rdelim};
					{rdelim},
					"type" : "post"
				{rdelim},
				"progressive_render" : true
			{rdelim},
			"core" : {ldelim}
					"html_titles" : true,
					"animation" : 100
			{rdelim},
			"plugins" : ["themes", "json_data"]
		{rdelim});
	{rdelim}
	
	nesting();
{rdelim});
</script>

<div id="categories_tree_wrap"></div>
{else}
Create categories first!
{/if}