
<!-- CATEGORIES BLOCK -->

				{if $categories_tree|@count}
					<div id="search_by_category_tree"></div>
					
					<script language="javascript" type="text/javascript">
						// JsTree v1.0 version
						$("#search_by_category_tree").jstree({ldelim}
							"themes" : {ldelim}
								"theme" : "classic",
								"url" : "{$smarty_obj->getFileInTheme('css/jsTree_themes_v10/classic/style.css')}",
								"icons" : true
							{rdelim},
							"json_data" : {ldelim}
								"data" : [
								{include file='frontend/categories/search_ajax.tpl' assign=template}
								{foreach from=$categories_tree item=category}
									{$category->render($template, $is_counter, $max_depth, $search_categories_array, ' "checked" : "true"', ',"state" : "closed"')},
								{/foreach}
								],
								"ajax" : {ldelim}
									"url" : "{$VH->site_url("ajax/categories_request/frontend/categories/search_ajax.tpl")}",
									"data" : function (n) {ldelim}
										return {ldelim} id : $(n).attr ? $(n).attr("id").split("_")[1] : 0 , is_counter : '{$is_counter}', max_depth : '{$max_depth}', selected_categories : '{$VH->json_encode($search_categories_array)}', highlight_element : ', "checked" : "true"', is_children_label : ', "state" : "closed"' {rdelim};
									{rdelim},
									"type" : "post"
								{rdelim},
								"progressive_render" : true
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
						{foreach from=$search_categories_array item=selected_category}
							{if $VH->is_numeric($selected_category)}
								//$("#search_by_category_tree").jstree("check_node", $("#search_{$selected_category}"));
							{elseif $VH->is_object($selected_category)}
								//$("#search_by_category_tree").jstree("check_node", $("#search_{$selected_category->id}"));
							{/if}
						{/foreach}
					</script>
				{/if}

<!-- /CATEGORIES BLOCK -->
