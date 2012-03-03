
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
									{include file='frontend/categories/ajax.tpl' assign=template}
									{foreach from=$categories_tree item=category}
										{$category->render($template, $is_counter, $max_depth, $current_category->seo_name, ', "style" : "background: #FFFFCC"', ',"state" : "closed"')},
									{/foreach}
									],
									"ajax" : {ldelim}
										"url" : "{$VH->site_url("ajax/categories_request/frontend/categories/ajax.tpl")}",
										"data" : function (n) {ldelim}
											$("#"+$(n).attr("id")+" a ins").css("display", "block");
											return {ldelim} id : $(n).attr("id") ? $(n).attr("id").split("_")[1] : 0 , is_counter : '{$is_counter}', max_depth : '{$max_depth}' {rdelim};
										{rdelim},
										"success" : function (data) {ldelim}
											$("#tree li a ins").hide();
										{rdelim},
										"type" : "post"
									{rdelim},
									"progressive_render" : true
								{rdelim},
								"core" : {ldelim}
									"html_titles" : true
								{rdelim},
								"plugins" : ["themes","json_data"]
							{rdelim});
						{rdelim});
					</script>
				{/if}

	                
<!-- /CATEGORIES BLOCK -->
