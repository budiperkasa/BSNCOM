
<!-- CATEGORIES BLOCK -->

				{if $categories_tree|@count}
					<div class="block categories_block">
						<div class="block-top"><div class="block-top-title">{$LANG_CATEGORIES}{if $type->categories_type == 'local'} {$LANG_IN} {$type->name}{/if}</div></div>
						<div class="block-bottom">
							<div class="left_sidebar_categories">
							{include file='frontend/categories/normal.tpl' assign=template}
							{foreach from=$categories_tree item=category}
								{$category->render($template, $is_counter, $max_depth, $current_category->seo_name, 'class="active_category"')}
							{/foreach}
	                       	</div>
						</div>
					</div>
				{/if}
	                
<!-- /CATEGORIES BLOCK -->
