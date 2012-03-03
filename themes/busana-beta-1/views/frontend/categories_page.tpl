{include file="frontend/header.tpl"}

			<tr>
				<td id="search_bar" colspan="3">
				{include file="frontend/search_block.tpl"}
				</td>
			</tr>
			<tr>
				<td id="left_sidebar">
				{include file="frontend/left-sidebar.tpl"}
				</td>
      			<td id="content_block" valign="top">
      				<div id="content_wrapper">

				      	{render_frontend_block
							block_type='map_and_markers'
							block_template='frontend/blocks/map_standart.tpl'
							existed_listings=$listings
							clasterization=false
				      	}

      					<div class="breadcrumbs">
      						<a href="{$VH->index_url()}">{$LANG_HOME_PAGE}</a>{foreach from=$breadcrumbs item="source_page" key="source_url"} » <a href="{$source_url}">{$source_page}</a>{/foreach} » <span>{$current_category->getChainAsLinks()}</span>
      						{if $CI->load->is_module_loaded('rss')}
                        		<div class="rss_icon"">
                        			<a href="{$VH->getRssUrl()}" title="{$VH->getRssTitle()}">
                        				<nobr><img src="{$public_path}images/feed.png" />&nbsp;<img src="{$public_path}images/rss.png" /></nobr>
                        			</a>
                        		</div>
                        	{/if}
      					</div>

	      				{if $current_category->children|@count}
						<h1>{$LANG_SUBCATEGORIES}</h1>
                        <div class="subcategories_list">
                        	{foreach from=$current_category->children item=category}
                        	{assign var=subcategory_id value=$category->id}
                        		<span class="subcategory_item">
                        			<a href="{$VH->site_url($category->getUrl())}" class="subcategory">{$category->name}&nbsp;({$category->countListings()})</a>&nbsp;&nbsp;
                        		</span>
                        	{/foreach}
                        	<div class="clear_float"></div>
                        </div>
                        {/if}

                        {render_frontend_block
                        	block_type='listings'
                        	block_template='frontend/blocks/with_paginator.tpl'
                        	items_array=$listings
                        	view_name=$view->view
                        	view_format=$view->format
                        	listings_paginator=$listings_paginator
                        	order_url=$order_url
                        	orderby=$orderby
                        	direction=$direction
                        }
                 	</div>
                </td>
                <td id="right_sidebar">
                {include file="frontend/right-sidebar.tpl"}
                </td>
			</tr>

{include file="frontend/footer.tpl"}