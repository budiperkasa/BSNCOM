{assign var=user value=$review->getUser()}
{assign var=object value=$review->getObject()}
{assign var=rating value=$review->setRating()}

{assign var=review_id value=$review->id}
{assign var=owner value=$review->object->getOwner()}
<li>
	<div class="review">
		{if $review->status == 2}
			<div class="blocked_review">
				{$LANG_REVIEWS_MODERATED_AND_BLOCKED}
			</div>
		{else}
			<div class="review_author {if $owner->id == $review->user->id}owner_of_object{/if}">
				{if $review->user->users_group->logo_enabled}
				<div class="review_author_img">
					{$review->user->renderThmbImage()}
				</div>
				{/if}
				<div class="review_author_name">
				{if $review->user_id}
					{assign var="review_user_id" value=$review->user->id}
					{if $review_user_id != 1 && $content_access_obj->isPermission('Manage users')}
						<a href="{$VH->site_url("admin/users/view/$review_user_id")}">{$review->user->login}</a>
					{else}
						{$review->user->login}
					{/if}
				{else}
					{$LANG_ANONYM}: {$review->anonym_name} ({$review->ip})
				{/if}
				{if $owner->id == $review->user->id} <i>({$LANG_LISTING_OWNER})</i>{/if}
				</div>
				{if $review->user_id && $review->rating}
				<div class="review_author_rating">
					{$review->rating->view()}
				</div>
				{/if}
				<div class="review_author_date">
					{$review->date_added|date_format:"%D %H:%M"}
				</div>
				<div class="clear_float"></div>
			</div>
			<div class="review_body">
				{$review->review}
			</div>
		{/if}
	</div>
	{if $review->children|@count}
	<ul class="reviews_block">
	{foreach from=$review->children item=child}
		{$child->view()}
	{/foreach}
	</ul>
	{/if}
</li>