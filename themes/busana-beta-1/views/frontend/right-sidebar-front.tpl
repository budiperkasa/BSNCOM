
<!-- RIGHT SIDEBAR -->

<!-- Calendar -->
{if $CI->load->is_module_loaded('calendar')}
	{$VH->attachCalendar($CI)}
{/if}
<!-- /Calendar -->

<!-- Similar listings -->
{if $listing}
	{assign var=categories_list value=''}
	{foreach from=$listing->categories_array() item=category}
		{assign var=categories_list value=$categories_list|cat:","|cat:$category->id}
	{/foreach}

	{render_frontend_block
		block_type='listings'
		block_template='frontend/blocks/similar_listings.tpl'
		except_listings=$listing->id
		search_type=$listing->type
		search_status=1
		search_users_status=2
		search_category=$categories_list
		search_location=$current_location
		orderby='l.creation_date'
		limit=5
	}
{/if}
<!-- /Similar listings -->


<!-- /RIGHT SIDEBAR -->
