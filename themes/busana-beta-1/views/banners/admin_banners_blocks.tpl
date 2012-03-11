{include file="backend/admin_header.tpl"}

                <div class="content">
                     <h3>{$LANG_BANNERS_ALL_BLOCKS}</h3>

					<div class="admin_top_menu_cell">
	                    <a href="{$VH->site_url("admin/banners_blocks/create")}" title="{$LANG_CREATE_BANNERS_BLOCK}"><img src="{$public_path}/images/buttons/page_add.png"></a>
	                    <a href="{$VH->site_url("admin/banners_blocks/create")}">{$LANG_CREATE_BANNERS_BLOCK}</a>&nbsp;&nbsp;&nbsp;&nbsp;
					</div>
                    <div class="clear_float"></div>

                     {if $banners_blocks|@count > 0}
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th>{$LANG_ID_TH}</th>
                         <th>{$LANG_BANNERS_BLOCK_NAME_TH}</th>
                         <th>{$LANG_BANNERS_BLOCK_MODE_TH}</th>
                         <th>{$LANG_BANNERS_BLOCK_SELECTOR_TH}</th>
                         <th>{$LANG_BANNERS_BLOCK_SIZE_TH}</th>
                         <th>{$LANG_BANNERS_BLOCK_ACTIVE_PERIOD_TH}</th>
                         <th>{$LANG_BANNERS_BLOCK_CLICKS_LIMIT_TH}</th>
                         <th>{$LANG_BANNERS_BLOCK_LIMITATION_MODE_TH}</th>
                         <th>{$LANG_OPTIONS_TH}</th>
                       </tr>
                       {foreach from=$banners_blocks item=banners_block}
                       {assign var="banners_block_id" value=$banners_block->id}
                       <tr>
                       	 <td>
                             {$banners_block->id}
                         </td>
                         <td>
                             <a href="{$VH->site_url("admin/banners_blocks/edit/$banners_block_id")}">{$banners_block->name}</a>
                         </td>
                         <td>
                             {$banners_block->mode}
                         </td>
                         <td>
                             {$banners_block->selector}
                         </td>
                         <td>
                             {$banners_block->block_size}
                         </td>
                         <td title="{$LANG_PERIOD_TD_ALT}">
                         	{if $banners_block->limit_mode == 'active_period' || $banners_block->limit_mode == 'both'}
                            	{$banners_block->active_years}/{$banners_block->active_months}/{$banners_block->active_days}
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
                         	<nobr>
                             <a href="{$VH->site_url("admin/banners_blocks/edit/$banners_block_id")}" title="{$LANG_EDIT_BANNERS_BLOCK}"><img src="{$public_path}images/buttons/page_edit.png"></a>
                             <a href="{$VH->site_url("admin/banners_blocks/delete/$banners_block_id")}" title="{$LANG_DELETE_BANNERS_BLOCK}"><img src="{$public_path}images/buttons/page_delete.png"></a>
                            </nobr>
                         </td>
                       </tr>
                       {/foreach}
                     </table>
                     {/if}
                </div>

{include file="backend/admin_footer.tpl"}