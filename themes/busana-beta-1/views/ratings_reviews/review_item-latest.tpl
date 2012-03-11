{assign var=user value=$review->getUser()}
{assign var=object value=$review->getObject()}
{assign var=rating value=$review->setRating()}

<li>
	<div class="review">
		{if $review->status == 2}
			<div class="blocked_review">
				{$LANG_REVIEWS_MODERATED_AND_BLOCKED}
			</div>
		{else}
			<div class="review_author">
				{if $review->user->users_group->logo_enabled}
				<div class="review_author_img">
					{$review->user->renderThmbImage()}
				</div>
				{/if}
				<div class="review_author_name">
				{if $review->user->id}
					{if $review->user->id != 1}
						<a href="{$review->user->profileUrl()}">{$review->user->login}</a>
					{else}
						{$review->user->login}
					{/if}
				{else}
					{$LANG_ANONYM}: {$review->anonym_name}
				{/if}
				</div>
				{if $review->user_id && $review->rating}
				<div class="review_author_rating">
					{$review->rating->view()}
				</div>
				{/if}
				<div class="clear_float"></div>
				<div class="review_author_date">
					{$review->date_added|date_format:"%D %H:%M"}
				</div>
			</div>
			<div class="review_body">
				{$review->review|truncate:130|strip_tags}
			</div>
			<div class="review_link_to_object">
				<a href="{$review->object->getObjectFrontUrl()}">{$review->object->getObjectTitle()} >>></a>
			</div>
		{/if}
	</div>
</li>