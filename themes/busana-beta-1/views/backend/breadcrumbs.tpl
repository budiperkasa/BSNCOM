{if $breadcrumbs|@count > 0}
				<div class="breadcrumbs">
					<ul>
					{assign var="i" value=1}
					{foreach from=$breadcrumbs item=breadcrumb key=link}
					{if $i++ != $breadcrumbs|@count}
						<li><a href="{$VH->site_url($link)}">{$breadcrumb}</a>&nbsp;>>&nbsp;</li>
					{else}
						<li>{$breadcrumb}</li>
					{/if}
					{/foreach}
					</ul>
					<div class="clear_float"></div>
				</div>
{/if}