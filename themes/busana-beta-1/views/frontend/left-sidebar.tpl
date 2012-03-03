
<!-- LEFT SIDEBAR -->
<!-- Categories list -->
{if !$current_type || $current_type->categories_type != 'disabled'}
	<!-- 
	$system_settings.categories_block_type - may be categories-bar-jshide.tpl or categories-bar.tpl or categories-bar-ajax.tpl
	 -->
	{assign var=categories_block_type value=$system_settings.categories_block_type}
	{render_frontend_block
		block_type='categories'
		block_template="frontend/blocks/"|cat:$categories_block_type|cat:".tpl"
		type=$current_type
		current_category=$current_category
		is_counter=true
		max_depth=2
	}
{/if}
<!-- /Categories list -->
             	

						{if $VH->checkQuickList()}
						<div id="quick_list">
							<a href="{$VH->site_url('quick_list')}">{$LANG_QUICK_LIST} ({$VH->checkQuickList()|@count})</a>
						</div>
						{/if}
						
						

<!-- /LEFT SIDEBAR -->
