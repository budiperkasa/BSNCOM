				<div id="head-links">
					<ul>
						<li><a href="{$VH->index_url()}">{$LANG_TOP_MENU_HOME}</a></li>

						{if $content_access_obj->isPermission('Create listings') || $content_access_obj->isPermission('Create banners')}
							<li>|</li>
							<li><a id="advertise_link" href="{$VH->site_url('advertise')}">{$LANG_TOP_MENU_ADS}</a></li>
						{/if}

						{foreach from=$content_pages item=page}
							{assign var="page_url" value=$page.url}
							<li>|</li>
           					<li><a href="{$VH->site_url("node/$page_url")}">{$page.title}</a></li>
           				{/foreach}
           				
           				{if $system_settings.enable_contactus_page}
							<li>|</li>
							<li><a href="{$VH->site_url('contactus')}" rel="nofollow">{$LANG_CONTACTUS_LINK}</a></li>
						{/if}
					</ul>
				</div>