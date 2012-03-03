<span class="paginator_found">
	{$LANG_PAGINATION_FOUND_1} <b>{$listings_paginator->count()}</b> {$LANG_PAGINATION_FOUND_2} | {$LANG_PAGINATION_FOUND_3} <b>{$listings_paginator->getPage()}</b> {$LANG_PAGINATION_FOUND_4} <b>{$listings_paginator->countPages()}</b> {$LANG_PAGINATION_FOUND_5}</h1>
</span>
<div class="clear_float"></div>

<div class="paginator_orderby">
	<span class="orderby">{$LANG_SEARCH_ORDER_BY}:</span>&nbsp;
	{asc_desc_insert base_url=$order_url orderby='l.creation_date' orderby_query=$orderby direction=$direction title=$LANG_SEARCH_CREATION_DATE}
	|
	{asc_desc_insert base_url=$order_url orderby='l.title' orderby_query=$orderby direction=$direction title=$LANG_SEARCH_LISTING_TITLE default_direction='asc'}

	{if $current_type && $current_type->buildLevels()|@count > 1}
	|
	{asc_desc_insert base_url=$order_url orderby='lev.order_num' orderby_query=$orderby direction=$direction title=$LANG_SEARCH_INFO_VALUE default_direction='desc'}
	{/if}

	{if $current_type && $current_type->buildLevels()|@count == 1}
		{assign var=levels value=$current_type->buildLevels()}
		{assign var=only_level value=$levels[0]}
	{/if}
	{if !$current_type || !$only_level || ($only_level && $only_level->ratings_enabled)}
	|
	{asc_desc_insert base_url=$order_url orderby='rating' orderby_query=$orderby direction=$direction title=$LANG_SEARCH_RATING}
	{/if}
	
	{if $search_args.where_radius && $search_args.where_search}
	|
	{asc_desc_insert base_url=$order_url orderby='distance' orderby_query=$orderby direction=$direction title=$LANG_SEARCH_DISTANCE default_direction='asc'}
	{/if}
</div>
<div class="paginator_select_page">
	{$listings_paginator->selectPageBlock()}
</div>
<div class="clear_float"></div>

<div class="listings_list">
	{if $wrapper_object}
		{$wrapper_object->render()}
	{else}
		{foreach from=$items_array item=item}
			{$item->view($view_name)}
		{/foreach}
	{/if}
	{$listings_paginator->placeLinksToHtml()}
</div>