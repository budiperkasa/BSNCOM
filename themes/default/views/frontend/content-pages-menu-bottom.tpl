				<span id="footer-links">
					<ul>
						{assign var = i value = 0}
						{foreach from=$content_pages item=page}
						{assign var="page_url" value=$page.url}
           					<li><a href="{$VH->site_url("node/$page_url")}">{$page.title}</a></li>
	           				{if $content_pages|@count != ($i++) + 1}
	           					<li>|</li>
	           				{/if}
           				{/foreach}
           				
           				{if $system_settings.enable_contactus_page}
           					{if $i>0}
           						<li>|</li>
           					{/if}
							<li><a href="{$VH->site_url('contactus')}" rel="nofollow">{$LANG_CONTACTUS_LINK}</a></li>
						{/if}
						
						{if $CI->load->is_module_loaded('sitemap')}
							<li>|</li>
							<li><a href="{$VH->site_url('sitemap/')}">{$LANG_SITEMAP_LINK}</a></li>
						{/if}
					</ul>
				</span>