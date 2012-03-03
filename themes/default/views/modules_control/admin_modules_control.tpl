{include file="backend/admin_header.tpl"}

           		<div class="content">
                     <h3>{$LANG_MODULES_LIST}</h3>
                     {if $modules|@count > 0}
                     <form action="" method="post">
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th>{$LANG_MODULE_TITLE_TH}</th>
                         <th>{$LANG_MODULE_SETUP_TH}</th>
                         <th>{$LANG_MODULE_VERSION_TH}</th>
                         <th>{$LANG_MODULE_DESCRIPTION_TH}</th>
                       </tr>
                       {foreach from=$modules item=module_item key=module_dir}
                       <tr>
                         <td>
                             {$module_item->title}&nbsp;

                             {assign var='disabled' value=0}
                             {if $module_item->required_by|@count}
	                             <br />
	                             <span class="required_module">{$LANG_REQUIRED_BY}:</span>&nbsp;
	                             {assign var="i" value=0}
	                             {foreach from=$module_item->required_by item=module}
	                             	{assign var="i" value=$i+1}
	                             	<span class="required_module">{$module->title}</span> ({if $module->active}{assign var='disabled' value=1}<span class='required_module_active'>{$LANG_ENABLED}</span>{else}<span class='required_module_inactive'>{$LANG_DISABLED}</span>{/if}){if $i!=($module_item->required_by|@count)},{/if}
	                             {/foreach}
                             {/if}
                             {if $module_item->depends_on|@count}
	                             <br />
	                             <span class="required_module">{$LANG_DEPENDS_ON}:</span>&nbsp;
	                             {assign var="i" value=0}
	                             {foreach from=$module_item->depends_on item=module}
	                             	{assign var="i" value=$i+1}
	                             	<span class="required_module">{$module->title}</span> ({if $module->active}<span class="required_module_active">{$LANG_ENABLED}</span>{else}{assign var='disabled' value=1}<span class="required_module_inactive">{$LANG_DISABLED}</span>{/if}){if $i!=($module_item->depends_on|@count)},{/if}
	                             {/foreach}
                             {/if}
                         </td>
                         <td>
                         	{if $module_item->type != 'core'}
                         		{if $module_item->active && !$disabled}
                         			<input type="hidden" name="installed_{$module_dir}" value="1">
                         		{/if}
                            	<input type="checkbox" name="install_{$module_dir}" value="1" {if $disabled}disabled{/if} {if $module_item->active}checked{/if}>
                            {/if}&nbsp;
                         </td>
                         <td>
                             {$module_item->version}&nbsp;
                         </td>
                         <td>
                             {$module_item->description}&nbsp;
                         </td>
                       </tr>
                       {/foreach}
                     </table>
                     <input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
                     </form>
                     {/if}
                </div>
           
{include file="backend/admin_footer.tpl"}