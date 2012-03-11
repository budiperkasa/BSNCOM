{assign var=user value=$review->getUser()}
{assign var=object value=$review->getObject()}
{assign var=rating value=$review->setRating()}

{assign var=review_id value=$review->id}
<li>
	<div class="review">
		<div class="review_author">
			<div class="review_author_item">
				<input type="checkbox" name="cb_{$review->id}" value="{$review->id}">&nbsp;&nbsp;
			</div>
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
			</div>
			{if $review->user_id && $review->rating}
			<div class="review_author_rating">
				{$review->rating->view()}
			</div>
			{/if}
			<div class="review_author_date">
				{$review->date_added|date_format:"%D %H:%M"}
			</div>
			<div class="review_author_options">
				<a href="{$VH->site_url("admin/reviews/edit/$review_id")}"><img src="{$public_path}/images/buttons/page_edit.png" /></a>
				&nbsp;&nbsp;
				{if $review->status == 1}<span class="status_active">{$LANG_STATUS_ACTIVE}</span>{/if}
				{if $review->status == 2}<span class="status_spam">{$LANG_STATUS_SPAM}</span>{/if}
			</div>
			<div class="clear_float"></div>
		</div>
		<div class="review_body">
			{$review->review}
		</div>
	</div>
	{if $review->children|@count}
	<ul class="reviews_block">
	{foreach from=$review->children item=child}
		{$child->view()}
	{/foreach}
	</ul>
	{/if}
</li>