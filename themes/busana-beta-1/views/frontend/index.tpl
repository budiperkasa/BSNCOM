{include file="frontend/header.tpl"}

			<tr>			  
			<td id="kolom_atas"colspan="3">
				 <div id="kolom_kanan">
				   <div class="site_desc"><h1>{$site_settings.description}</h1>
				       <p>{$site_settings.website_title}</p>
				   </div>
				   <div class="search_bar">				  
				     {include file="frontend/search_block.tpl"}
				   </div>	
				   <div class="video_action">
				     <div class="video">
					       <iframe src="http://player.vimeo.com/video/36276188?title=0&amp;byline=0&amp;portrait=0" width="200" height="108" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
					 </div>
					 <div class="action">
					   <div class="content_field_output">
					     <h2>Kenapa harus busana.com ?.</h2>
					   </div>
					   <div class="content_field_output">
						 <p>Karena Busana.com satu-satunya direktori website pertama kali di Indonesia yang melisting website-website busana. </p>
					   </div>
					 </div>
				   </div>			  
				 </div>	
				 <div id="kolom_kiri">
				    <div class="slideshow">
				      
					      {include file="frontend/slideshow.tpl"}
				      
					</div>
				 </div>			
			</td>
				
			</tr>
			<tr>
			    <td id="left_sidebar"></td>
				<td id="content_block" valign="top"></td>
				<td id="right_sidebar"></td>		
			</tr>
			<tr>
				<td id="left_sidebar_front">
				 <div class="modul_kiri">
				   {include file="frontend/left-sidebar-front.tpl"}
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
				 <div class="px5"></div>				
				</td>
      			<td id="content_block_front" valign="top">
				 <div class="modul_tengah">
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
				 <div class="px5"></div>      				
                </td>
                <td id="right_sidebar_front">
				 <div class="modul_kanan">
				   {include file="frontend/right-sidebar-front.tpl"}
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
				 <div class="px5"></div>
                </td>
			</tr>

{include file="frontend/footer.tpl"}