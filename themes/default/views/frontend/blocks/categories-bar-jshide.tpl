
<!-- CATEGORIES BLOCK -->

				{if $categories_tree|@count}
					<div class="block categories_block">
						<div class="block-top"><div class="block-top-title">{$LANG_CATEGORIES}{if $type->categories_type == 'local'} {$LANG_IN} {$type->name}{/if}</div></div>
						<div class="block-bottom">
							<div id="tree" style="overflow:hidden"></div>
						</div>
					</div>
					<script language="javascript" type="text/javascript">
						$(document).ready(function() {ldelim}
							// JsTree v1.0 version
			                $("#tree").jstree({ldelim}
								"themes" : {ldelim}
									"theme" : "classic",
									"url" : "{$smarty_obj->getFileInTheme('css/jsTree_themes_v10/classic/style.css')}",
									"icons" : false
								{rdelim},
								"json_data" : {ldelim}
									"data" : [
									{include file='frontend/categories/jshide.tpl' assign=template}
									{foreach from=$categories_tree item=category}
										{$category->render($template, $is_counter, $max_depth, $current_category->seo_name, ', "style" : "background: #FFFFCC"', ',"state" : "closed"')}
									{/foreach}
									],
									"progressive_render" : true
								{rdelim},
								"core" : {ldelim}
									"html_titles" : true
								{rdelim},
								"plugins" : ["themes","json_data"]
							{rdelim});
							{if $current_category}
								$("#tree").jstree("open_node", $("#category_list_{$current_category->parent_category_id}"));
							{/if}
						{rdelim});
					</script>
				{/if}

	                
<!-- /CATEGORIES BLOCK -->
