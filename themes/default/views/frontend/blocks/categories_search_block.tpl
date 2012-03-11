
<!-- CATEGORIES BLOCK -->

				{if $categories_tree|@count}
					<div id="search_by_category_tree"></div>
					
					<script language="javascript" type="text/javascript">
						// JsTree v1.0 version
						$("#search_by_category_tree").jstree({ldelim}
							"themes" : {ldelim}
								"theme" : "default",
								"url" : "{$smarty_obj->getFileInTheme('css/jsTree_themes_v10/default/style.css')}",
								"icons" : false
							{rdelim},
							"json_data" : {ldelim}
								"data" : [
								{include file='frontend/categories/search_normal.tpl' assign=template}
								{foreach from=$categories_tree item=category}
									{$category->render($template, $is_counter, $max_depth, $search_categories_array, ', "checked" : "true"', ',"state" : "closed"')}
								{/foreach}
								]
							{rdelim},
							"core" : {ldelim}
									"html_titles" : true
							{rdelim},
							"plugins" : ["themes","json_data","checkbox"]
						{rdelim}).bind("loaded.jstree", function () {ldelim}
							$('#search_by_category_tree li[checked=true]').each(function() {ldelim}
								$.jstree._reference("#search_by_category_tree").check_node($(this));
							{rdelim});
						{rdelim});
					</script>
				{/if}

<!-- /CATEGORIES BLOCK -->
