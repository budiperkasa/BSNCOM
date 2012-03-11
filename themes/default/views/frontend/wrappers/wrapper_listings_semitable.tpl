{assign var=i value=0}
	{foreach from=$listings_array item=listing}
		{assign var=i value=$i+1}
		{if $i == 1}
			<div style="padding-bottom: 10px">
			<table width="100%" cellspacing="0"><tr>
			<td width="{$td_width}px" class="listing_preview {if $listing->level->featured}featured{/if}">{$listing->view('semitable')}</td>
		{else}
			<td width="{$td_space_width}px"></td>
			<td width="{$td_width}px" class="listing_preview {if $listing->level->featured}featured{/if}">{$listing->view('semitable')}</td>
		{/if}
		{if $i == $columns}
			</tr></table>
			</div>
			{assign var=i value=0}
		{/if}
	{/foreach}
	{if $i > 0}
		{section name="myLoop" start=$i loop=$columns-$i}
			<td width="{$td_space_width}px"></td>
			<td width="{$td_width+$td_padding_border}px"></td>
		{/section}
		</tr></table></div>
	{/if}