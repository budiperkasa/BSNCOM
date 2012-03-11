{include file="backend/admin_header.tpl"}

                <div class="content">
                     <h3>{$LANG_BANNERS_ADD_BANNER_TITLE}</h3>
                     <h4>{$LANG_BANNERS_ADD_STEP1}</h4>
                     
                     {if $banners_blocks|@count}
                     <table class="presentationTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th>{$LANG_BANNERS_BLOCK_NAME_TH}</th>
                         <th>{$LANG_PRICE_TH}</th>
                         <th>{$LANG_BANNERS_BLOCK_SIZE_TH}</th>
                         <th>{$LANG_BANNERS_BLOCK_ACTIVE_PERIOD_TH}</th>
                         <th>{$LANG_BANNERS_BLOCK_CLICKS_LIMIT_TH}</th>
                         <th>{$LANG_BANNERS_BLOCK_LIMITATION_MODE_TH}</th>
                         <th>{$LANG_OPTIONS_TH}</th>
                       </tr>
                       {foreach from=$banners_blocks item=banners_block}
                       {assign var="banners_block_id" value=$banners_block->id}
                       <tr>
                         <td class="td_header">
                             {$banners_block->name}
                         </td>
                         <td>
							 {if $banners_prices[$banners_block_id].price_value == null || $banners_prices[$banners_block_id].price_value == 0}<span class="free">{$LANG_FREE}</span>{else}{$banners_prices[$banners_block_id].price_currency}&nbsp;{$banners_prices[$banners_block_id].price_value}{/if}
                         <td>
                             {$banners_block->block_size}
                         </td>
                         <td title="{$LANG_PERIOD_TD_ALT}">
                         	{if $banners_block->limit_mode == 'active_period' || $banners_block->limit_mode == 'both'}
                         		{if $banners_block->active_years}
									{$LANG_YEARS}:&nbsp;<b>{$banners_block->active_years}</b><br />
								{/if}
								{if $banners_block->active_months}
									{$LANG_MONTHS}:&nbsp;<b>{$banners_block->active_months}</b><br />
								{/if}
								{if $banners_block->active_days}
									{$LANG_DAYS}:&nbsp;<b>{$banners_block->active_days}</b>
								{/if}
                         	{else}
                         		<span class="green">{$LANG_UNLIMITED}</span>
                         	{/if}
                         </td>
                         <td>
                         	{if $banners_block->limit_mode == 'clicks' || $banners_block->limit_mode == 'both'}
                         		{$banners_block->clicks_limit}
                         	{else}
                         		<span class="green">{$LANG_UNLIMITED}</span>
                         	{/if}
                         </td>
                         <td>
                             {if $banners_block->limit_mode == 'active_period'}{$LANG_BANNERS_ACTIVE_PERIOD_LIMITATION}{/if}
                             {if $banners_block->limit_mode == 'clicks'}{$LANG_BANNERS_CLICKS_LIMITATION}{/if}
                             {if $banners_block->limit_mode == 'both'}{$LANG_BANNERS_BOTH_LIMITATION}{/if}
                         </td>
                         <td>
                         	 <a href="{$VH->site_url("admin/banners/create/block_id/$banners_block_id")}">{$LANG_BANNERS_ADD_BANNER}</a>
                         </td>
                       </tr>
                       {/foreach}
                     </table>
					{/if}
                </div>

{include file="backend/admin_footer.tpl"}