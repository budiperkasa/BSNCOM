{assign var=objects_table value=$reviews_block->objects_table}
{assign var=object_id value=$reviews_block->objects_ids}

{if $comment_area_enabled}
<script language="javascript" type="text/javascript">
$(document).ready(function() {ldelim}
	$(".answer_link").click(function() {ldelim}
		var str = $(this).attr('id');
		var parent_id = str.substr(7);
		$("#parent_id").val(parent_id);

		if (parent_id != 0)
			$(this).parent().append($("#review_form").attr('style', 'margin-left:25px'));
		else
			$(this).parent().append($("#review_form").attr('style', 'margin-left:0'));

		$(this).parent().hide().show();

		return false;
	{rdelim});

	$("#add_review").click(function() {ldelim}
		{if $reviews_block->is_richtext}
			var body = FCKeditorAPI.GetInstance('review_body').GetHTML();
		{else}
			var body = $("#review_body").val();
		{/if}
		ajax_loader_show();
		$.post('{$VH->site_url("reviews/add/$objects_table/$object_id/")}', {ldelim}review: body, parent_id: $("#parent_id").val(), anonym_name: $("#anonym_name").val(), anonym_email: $("#anonym_email").val(), captcha: $("#captcha").val(){rdelim},
		function (data, status) {ldelim}
			if(typeof(data.error_msg) != 'undefined') {ldelim}
				if(data.error_msg != '') {ldelim}
					ajax_loader_hide();
					alert(data.error_msg);
				{rdelim} else {ldelim}
					$.post('{$VH->site_url("reviews/refresh/")}', {ldelim}params : '{$VH->json_encode($all_params)}' {rdelim},
						function(data) {ldelim}
							$("#reviews_div").html(data);
							ajax_loader_hide();
						{rdelim}
					);
				{rdelim}
			{rdelim} else {ldelim}
				ajax_loader_hide();
			{rdelim}
		{rdelim}, 'json');
	{rdelim});

	{if $reviews_block->is_richtext}
		//FCKeditorAPI.GetInstance('review_body').MakeEditable();
	{/if}
{rdelim});
</script>
{/if}

<div id="reviews_div">
	<h1>{$reviews_block->reviews_count} {if $reviews_block->mode == 'reviews'}{$LANG_REVIEWS}{else}{$LANG_COMMENTS}{/if}</h1>
	{if $reviews_block->reviews_count}
		<ul class="root_reviews_block">
			{foreach from=$reviews_block->reviews_structured_array item=review}
				{$review->view($comment_area_enabled)}
			{/foreach}
		</ul>
	{/if}
	
	{if $comment_area_enabled}
		{if $system_settings.anonym_rates_reviews || $user}
			<a href="#" class="answer_link" id="review-0"><img src="{$public_path}images/icons/comments.png"></a> <a href="#" style="font-size:14px; font-weight:bold;" class="answer_link" id="review-0">{if $reviews_block->mode == 'reviews'}{$LANG_ADD_REVIEW_HERE}{else}{$LANG_COMMENT_LISTINGS}{/if}</a>
			<div id="review_form">
				<input type="hidden" id="parent_id" value="0" />
				{if $system_settings.anonym_rates_reviews && !$user}
					<div>
						<div class="admin_option_name" >
							{$LANG_ANONYM_NAME}<span class="red_asterisk">*</span>
						</div>
						<input type="text" id="anonym_name" size="50"/>
					</div>
					<div>
						<div class="admin_option_name">
							{$LANG_ANONYM_EMAIL}<span class="red_asterisk">*</span>
						</div>
						<input type="text" id="anonym_email" size="50"/>
					</div>
				{else}
					<div class="review_author_self">
						<div class="review_author_img">
							{$user->renderThmbImage()}
						</div>
						<div class="review_author_name">
							{$user->login}
						</div>
						<div class="clear_float"></div>
					</div>
				{/if}
				<div>
					{$reviews_block->placeCommentArea()}
				</div>
				{if $system_settings.anonym_rates_reviews && !$user}
				<div>
					<div class="admin_option_name">
						{$LANG_FILL_CAPTCHA}<span class="red_asterisk">*</span>
					</div>
					<input type="text" id="captcha" name="captcha" size="4">
					<div class="px10"></div>
					{$captcha->view()}
				<div>
				{/if}
				<input id="add_review" class="front-btn" type="button" value="{if $reviews_block->mode == 'reviews'}{$LANG_ADD_REVIEW_BTN}{else}{$LANG_ADD_COMMENT_BTN}{/if}" />
			</div>
		{else}
			{if $system_settings.anonym_rates_reviews}
				{$LANG_REVIEW_LOGIN_ERROR}
			{/if}
		{/if}
	{/if}
</div>