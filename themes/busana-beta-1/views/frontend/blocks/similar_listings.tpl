{if $items_array|@count}
	<div class="sidebar_block similar_listings">
		<h1>{$LANG_SIMILAR_LISTINGS_TITLE}</h1>
		{foreach from=$items_array item=listing}
			<div class="block_item">
				{if $listing->level->logo_enabled && $listing->logo_file}
				<div class="block_item_img">
					<a title="{$listing->title()}" href="{$VH->site_url($listing->url())}">
						<img src="{$users_content}/users_images/thmbs_cropped/{$listing->logo_file}" alt="{$listing->title()}" />
					</a>
				</div>
				{/if}
				<div>
					<a title="{$listing->title()}" href="{$VH->site_url($listing->url())}">{$listing->title()}</a>
				</div>
				<div>
				{if $listing->level->ratings_enabled}
					{assign var=avg_rating value=$listing->getRatings()}
					{$avg_rating->setInactive()}
					{$avg_rating->view()}
				{/if}
				</div>
				{$listing->creation_date|date_format:"%D %H:%M"}
			</div>
		{/foreach}
	</div>
{/if}