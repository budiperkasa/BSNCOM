{include file="backend/admin_header.tpl"}

                <div class="content">
					<h3>{$LANG_LISTING_LEVELS_1} "{$listing->title()}"</h3>
                     
					{include file="listings/admin_listing_options_menu.tpl"}
					
					<h4>{$LANG_LISTING_CHANGE_LEVEL_DESCR}</h4>
                     
                     <form action="" method="post">
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th width="1">&nbsp;</th>
                         <th>{$LANG_LEVEL_TH}</th>
                         <th>{$LANG_PAY_DIFFERENCE_TH}</th>
                       </tr>
                       {foreach from=$levels item=level}
                       {if $content_access_obj->isContentPermission('levels', $level->id)}
                       <tr>
                         <td>
                             <input type="radio" id="level_{$level->id}" name="level_id" value="{$level->id}" {if $listing->level_id == $level->id}checked{/if} {if $level->price_value === 'currency mismatch' || $level->price_value === 'package level not allowed'}disabled{/if}>
                         </td>
                         <td>
                             <label for="level_{$level->id}">{$level->name}</label>
                         </td>
                         <td>
                         	{if $level->price_value === 'current level'}
                         		{$LANG_CURRENT_LEVEL}
                         	{elseif $level->price_value === 'currency mismatch'}
                         		{$LANG_LEVEL_CURRENCY_MISMATCH}
                         	{elseif $level->price_value === 'package level not allowed'}
                         		{$LANG_PACKAGE_LEVEL_NOT_ALLOWED}
                         	{else}
                         		{if $level->price_value > 0}
                         		+{$VH->number_format($level->price_value, 2, $decimals_separator, $thousands_separator)} {$level->price_currency}
                         		{elseif $level->price_value <= 0}
                         		<span class="free">{$LANG_FREE}</span>
                         		{/if}
                         	{/if}
                         </td>
                       </tr>
                       {/if}
                       {/foreach}
                     </table>

                     <input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}