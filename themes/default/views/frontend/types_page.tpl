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

      					{if $type->locations_enabled}
				      		{render_frontend_block
						      	block_type='map_and_markers'
						      	block_template='frontend/blocks/map_standart.tpl'
						      	existed_listings=$listings
						      	clasterization=false
							}
					    {/if}

      					<div class="breadcrumbs">
      						<a href="{$VH->index_url()}">{$LANG_HOME_PAGE}</a> » <span>{$type->name}</span>
	      				{if $CI->load->is_module_loaded('rss')}
	                        		<div class="rss_icon"">
	                        			<a href="{$VH->getRssUrl()}" title="{$VH->getRssTitle()}">
	                        				<nobr><img src="{$public_path}images/feed.png" />&nbsp;<img src="{$public_path}images/rss.png" /></nobr>
	                        			</a>
	                        		</div>
	                        	{/if}
      					</div>

                    	{render_frontend_block
                        	block_type='listings'
                        	block_template='frontend/blocks/with_paginator.tpl'
                        	items_array=$listings
                        	view_name=$view->view
                        	view_format=$view->format
                        	type=$type
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