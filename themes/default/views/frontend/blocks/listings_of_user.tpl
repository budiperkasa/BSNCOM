{if $items_array|@count}
	<div class="px10"></div>
	<div class="px10"></div>
	<div class="listings_of_user">
		<h1>{$LANG_LISTINGS_OF_USER_TITLE}</h1>
		{$wrapper_object->render()}
	</div>
{/if}