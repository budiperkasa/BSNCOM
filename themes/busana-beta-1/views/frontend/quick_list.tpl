{include file="frontend/header.tpl"}

<script language="Javascript" type="text/javascript">
	$(document).ready(function() {ldelim}
		$(".remove_favourite").click(function() {ldelim}
			favourites_array = PHPSerializer.unserialize($.cookie("favourites"));

			id = $(this).attr('id');
			if ((key = in_array(id, favourites_array)) !== false) {ldelim}
				favourites_array[key] = undefined;
				$.cookie("favourites", PHPSerializer.serialize(favourites_array), {ldelim}expires: 365, path: "/"{rdelim});
				$(this).parent().slideUp("normal", function() {ldelim}
					$(this).remove();
				{rdelim});
				$.jGrowl('{addslashes string=$LANG_QUICK_FROM_LIST_SUCCESS}', {ldelim}
					life: 3000,
					theme: 'jGrowl_success_msg'
				{rdelim});
			{rdelim}
			return false;
		{rdelim});
	{rdelim});
</script>

			<tr>
				<td id="search_bar" colspan="3">
				{include file="frontend/search_block.tpl"}
				</td>
			</tr>
			<tr>
				<td id="left_sidebar">
				{include file="frontend/left-sidebar.tpl"}
				</td>
      			<td id="content_block" valign="top">
      				<div id="content_wrapper">

                        <h1>{$LANG_QUICK_LIST} ({$listings|@count}):</h1>
                        <div class="quicklist_listings">
                        	{render_frontend_block
								block_type='listings'
	                        	block_template='frontend/blocks/with_paginator.tpl'
	                        	items_array=$listings
	                        	view_name=$view->view
	                        	view_format=$view->format
	                        	listings_paginator=$listings_paginator
	                        	order_url=$order_url
	                        	orderby=$orderby
	                        	direction=$direction
	                        }
						</div>
                 	</div>
                </td>
                <td id="right_sidebar">
                {include file="frontend/right-sidebar.tpl"}
                </td>
			</tr>

{include file="frontend/footer.tpl"}