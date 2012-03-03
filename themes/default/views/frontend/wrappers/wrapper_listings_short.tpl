<div style="padding-bottom: 10px;">
	<table cellspacing="0" cellpadding="0" width="100%">
		<tr class="content_field_header">
			<th></th>
			{if $order_url !== null}
				{if $orderby == 'l.creation_date' && $direction == 'asc'}
					<th class="content_field_header_cell"><a class="descending" title="{$LANG_SORT_DESCENDING}" href="{$order_url}orderby/l.creation_date/direction/desc/" rel="nofollow">{$LANG_DATE}</a></th>
				{elseif $orderby == 'l.creation_date' && $direction == 'desc'}
					<th class="content_field_header_cell"><a class="ascending" title="{$LANG_SORT_ASCENDING}" href="{$order_url}orderby/l.creation_date/direction/asc/" rel="nofollow">{$LANG_DATE}</a></th>
				{else}
					<th class="content_field_header_cell"><a title="{$LANG_SORT_DESCENDING}" href="{$order_url}orderby/l.creation_date/direction/desc/" rel="nofollow">{$LANG_DATE}</a></th>
				{/if}
			{else}
				<th class="content_field_header_cell">{$LANG_DATE}</th>
			{/if}
			
			{if $logo_enabled}
				<th class="content_field_header_cell" style="padding:0;">{$LANG_LOGO}</th>
			{else}
				<th class="content_field_header_cell" style="padding:0;"></th>
			{/if}
			
			{if $order_url !== null}
				{if $orderby == 'l.title' && $direction == 'asc'}
					<th class="content_field_header_cell"><a class="descending" title="{$LANG_SORT_DESCENDING}" href="{$order_url}orderby/l.title/direction/desc/" rel="nofollow">{$LANG_TITLE}</a></th>
				{elseif $orderby == 'l.title' && $direction == 'desc'}
					<th class="content_field_header_cell"><a class="ascending" title="{$LANG_SORT_ASCENDING}" href="{$order_url}orderby/l.title/direction/asc/" rel="nofollow">{$LANG_TITLE}</a></th>
				{else}
					<th class="content_field_header_cell"><a title="{$LANG_SORT_ASCENDING}" href="{$order_url}orderby/l.title/direction/desc/" rel="nofollow">{$LANG_TITLE}</a></th>
				{/if}
			{else}
				<th class="content_field_header_cell">{$LANG_TITLE}</th>
			{/if}
			
			{foreach from=$content_fields key=field item=seo_name}
				{assign var=field_object value=$VH->unserialize($field)}
				{assign var=field_orderby value=$field_object->field->seo_name}
				
				{if $field_object->order_option && $order_url !== null}
					<th class="content_field_header_cell">
					{if $orderby == "cf."|cat:$field_orderby && $direction == 'asc'}
						<a class="descending" title="{$LANG_SORT_DESCENDING}" href="{$order_url}orderby/cf.{$field_orderby}/direction/desc/" rel="nofollow">
					{elseif $orderby == "cf."|cat:$field_orderby && $direction == 'desc'}
						<a class="ascending" title="{$LANG_SORT_ASCENDING}" href="{$order_url}orderby/cf.{$field_orderby}/direction/asc/" rel="nofollow">
					{else}
						<a title="{$LANG_SORT_DESCENDING}" href="{$order_url}orderby/cf.{$field_orderby}/direction/desc/" rel="nofollow">
					{/if}
					{if $field_object->field->frontend_name}{$field_object->field->frontend_name}{else}{$field_object->field->name}{/if}</a></th>
				{else}
					<th class="content_field_header_cell">{if $field_object->field->frontend_name}{$field_object->field->frontend_name}{else}{$field_object->field->name}{/if}</th>
				{/if}
			{/foreach}
			
			{assign var=counter value=0}
			{foreach from=$listings_array item=listing}
				{assign var=counter value=$counter+1}
				<tr class="{if $counter%2}even{else}odd{/if}" id="listing_id-{$listing->getUniqueId()}">{$listing->view(short)}</tr>
			{/foreach}

		</tr>
	</table>
</div>