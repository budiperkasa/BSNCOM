{if $items_array|@count}
	{if $wrapper_object}
		{$wrapper_object->render()}
	{else}
		{foreach from=$items_array item=item}
			{$item->view($view_name)}
		{/foreach}
	{/if}
{/if}