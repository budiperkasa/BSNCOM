{include file="frontend/header.tpl"}

			<tr>
				<td id="search_bar" colspan="3">
				{include file="frontend/search_block.tpl"}
				</td>
			</tr>
			<tr>
				<td colspan="3">
					{php}$this->assign('listings', array());{/php}
					{foreach from=$types item=type}
						{assign var=type_id value=$type->id}
						{assign var=listings value=$VH->array_merge($listings_of_type[$type_id], $listings)}
					{/foreach}

					{render_frontend_block
	                   		block_type='map_and_markers'
	                   		block_template='frontend/blocks/map_standart.tpl'
	                   		existed_listings=$listings
	                   		markers_of_search_location=true
	                   		clasterization=false
					}
				</td>
			</tr>
			<tr>
				<td id="left_sidebar">
				{include file="frontend/left-sidebar.tpl"}
				</td>
      			<td id="content_block" valign="top">
      				<div id="content_wrapper">
      					{if $CI->load->is_module_loaded('rss')}
                        	<h1 id="index_header">
                        		<div class="rss_icon_index"">
                        			<a href="{$VH->getRssUrl()}" title="{$VH->getRssTitle()}">
                        				<nobr><img src="{$public_path}images/feed.png" />&nbsp;<img src="{$public_path}images/rss.png" /></nobr>
                        			</a>
                        		</div>
                        	</h1>
                        {/if}

                        <div class="index_listings">
                        	{foreach from=$types item=type}
                        		{assign var=type_id value=$type->id}
                        		{assign var=view value=$listings_views->getViewByTypeIdAndPage($type_id, 'index')}
                        		{render_frontend_block
                        			block_type='listings'
                        			block_template='frontend/blocks/for_index.tpl'
                        			items_array=$listings_of_type[$type_id]
                        			view_name=$view->view
                        			view_format=$view->format
                        			type=$type
                        		}
                        	{/foreach}
                        </div>
                 	</div>
                </td>
                <td id="right_sidebar">
                {include file="frontend/right-sidebar.tpl"}
                </td>
			</tr>

{include file="frontend/footer.tpl"}