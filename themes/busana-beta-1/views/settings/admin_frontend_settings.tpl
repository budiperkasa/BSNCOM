{include file="backend/admin_header.tpl"}

                <div class="content">
                     <h3>{$LANG_FRONTEND_SETTINGS}</h3>
                     {if $types|@count > 0}
                     <table class="presentationTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th>{$LANG_TYPE_TH}</th>
                         {foreach from=$views->available_pages.by_types key=page_key item=page_name}
	                         <th>{$page_name}</th>
                         {/foreach}
                       </tr>
                       {foreach from=$types item=type}
                       {assign var="type_id" value=$type->id}
                       <tr>
                         <td>
                         	{$type->name}
                         </td>
                         {foreach from=$views->available_pages.by_types key=page_key item=page_name}
	                         {assign var="view_obj" value=$views->getViewByTypeIdAndPage($type->id, $page_key)}
	                         <td>
	                         	<a href="{$VH->site_url("admin/settings/frontend/configure/$page_key/$type_id/")}" title="{$LANG_CONFIGURE_VISIBILITY}">{$view_obj->getViewName()} ({$view_obj->format}) - {$LANG_SEARCH_ORDER_BY}: {$view_obj->getOrderBy()}</a>
	                         </td>
                         {/foreach}
                       </tr>
                       {/foreach}
                     </table>
                     {/if}
                     <div class="px10"></div>
                     <h3>{$LANG_FRONTEND_SETTINGS_ALONE_PAGES}</h3>
                     <table class="presentationTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                       	 {foreach from=$views->available_pages.mixed_types key=page_key item=page_name}
	                         <th>{$page_name}</th>
                         {/foreach}
                       </tr>
                       <tr>
                         {foreach from=$views->available_pages.mixed_types key=page_key item=page_name}
	                         {assign var="view_obj" value=$views->getViewByTypeIdAndPage(0, $page_key)}
	                         <td>
	                         	<a href="{$VH->site_url("admin/settings/frontend/configure/$page_key/")}" title="{$LANG_CONFIGURE_VISIBILITY}">{$view_obj->getViewName()} ({$view_obj->format}) - {$LANG_SEARCH_ORDER_BY}: {$view_obj->getOrderBy()}</a>
	                         </td>
                         {/foreach}
                       </tr>
                     </table>
                </div>

{include file="backend/admin_footer.tpl"}