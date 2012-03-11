{if $items_array|@count}
	{if !$system_settings.single_type_structure}
	<div class="type_line">
		<div class="type_title">
			<a href="{$VH->site_url($type->getUrl())}">{$type->name}</a>
		</div>
	</div>
	{/if}

	{if $wrapper_object}
		{$wrapper_object->render()}
	{else}
		{foreach from=$items_array item=item}
			{$item->view($view_name)}
		{/foreach}
	{/if}
{/if}