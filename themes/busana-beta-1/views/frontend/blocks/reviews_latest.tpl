{if $reviews_block->reviews_count}
{assign var=objects_table value=$reviews_block->objects_table}
{assign var=object_id value=$reviews_block->objects_ids}

<div id="reviews_div">
	<h1>Latest comments</h1>
		<ul class="root_reviews_block">
			{foreach from=$reviews_block->reviews_structured_array item=review}
				{$review->view(false, $review_item_template)}
			{/foreach}
		</ul>
</div>
{/if}