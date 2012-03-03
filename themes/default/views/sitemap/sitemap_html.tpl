{include file="frontend/header.tpl"}

			<tr>
				<td id="left_sidebar">
				{include file="frontend/left-sidebar.tpl"}
				</td>
      			<td id="content_block" valign="top" colspan="2">
      				<div id="content_wrapper">
                         <h1>{$LANG_SITEMAP_TITLE}</h1>
                         <h2 class="sitemap_h2"><a href="{$VH->site_url()}">{$LANG_HOME_MENU}</a></h2>
                         <ul class="sitemap_root">
	                     {foreach from=$types item=type}
	                     	{assign var="type_seo_name" value=$type->seo_name}
	                     	{assign var="type_id" value=$type->id}
	                     	<li>
		                     	<h2 class="sitemap_h2"><a class="sitemap_h1" href="{$VH->site_url($type->getUrl())}">{$type->name}</a></h2>
		                     	<ul class="sitemap_type_root">
		                     	{foreach from=$listings.$type_id item=listing}
		                     		<li><a href="{$VH->site_url($listing->url())}">{$listing->title()}</a></li>
		                     	{/foreach}
		                     	</ul>
		                    </li>
	                     {/foreach}
	                     {foreach from=$content_pages item=page}
	                     	{assign var="page_url" value=$page.url}
	                     	<li>
	                     		<h2 class="sitemap_h2"><a class="sitemap_h1" href="{$VH->site_url("node/$page_url")}">{$page.title}</a></h2>
	                     	</li>
	                     {/foreach}
	                     {foreach from=$info_pages item=page}
	                     	{assign var="page_url" value=$page.url}
	                     	<li>
	                     		<h2 class="sitemap_h2"><a class="sitemap_h1" href="{$VH->site_url("$page_url")}">{$page.title}</a></h2>
	                     	</li>
	                     {/foreach}
	                     </ul>
                 	</div>
				</td>
			</tr>

{include file="frontend/footer.tpl"}