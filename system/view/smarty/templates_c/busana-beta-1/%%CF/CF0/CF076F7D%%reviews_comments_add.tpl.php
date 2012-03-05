<?php /* Smarty version 2.6.26, created on 2012-03-05 07:19:15
         compiled from frontend/blocks/reviews_comments_add.tpl */ ?>
<?php $this->assign('objects_table', $this->_tpl_vars['reviews_block']->objects_table); ?>
<?php $this->assign('object_id', $this->_tpl_vars['reviews_block']->objects_ids); ?>

<?php if ($this->_tpl_vars['comment_area_enabled']): ?>
<script language="javascript" type="text/javascript">
$(document).ready(function() {
	$(".answer_link").click(function() {
		var str = $(this).attr('id');
		var parent_id = str.substr(7);
		$("#parent_id").val(parent_id);

		if (parent_id != 0)
			$(this).parent().append($("#review_form").attr('style', 'margin-left:25px'));
		else
			$(this).parent().append($("#review_form").attr('style', 'margin-left:0'));

		$(this).parent().hide().show();

		return false;
	});

	$("#add_review").click(function() {
		<?php if ($this->_tpl_vars['reviews_block']->is_richtext): ?>
			var body = FCKeditorAPI.GetInstance('review_body').GetHTML();
		<?php else: ?>
			var body = $("#review_body").val();
		<?php endif; ?>
		ajax_loader_show();
		$.post('<?php echo $this->_tpl_vars['VH']->site_url("reviews/add/".($this->_tpl_vars['objects_table'])."/".($this->_tpl_vars['object_id'])."/"); ?>
', {review: body, parent_id: $("#parent_id").val(), anonym_name: $("#anonym_name").val(), anonym_email: $("#anonym_email").val(), captcha: $("#captcha").val()},
		function (data, status) {
			if(typeof(data.error_msg) != 'undefined') {
				if(data.error_msg != '') {
					ajax_loader_hide();
					alert(data.error_msg);
				} else {
					$.post('<?php echo $this->_tpl_vars['VH']->site_url("reviews/refresh/"); ?>
', {params : '<?php echo $this->_tpl_vars['VH']->json_encode($this->_tpl_vars['all_params']); ?>
' },
						function(data) {
							$("#reviews_div").html(data);
							ajax_loader_hide();
						}
					);
				}
			} else {
				ajax_loader_hide();
			}
		}, 'json');
	});

	<?php if ($this->_tpl_vars['reviews_block']->is_richtext): ?>
		//FCKeditorAPI.GetInstance('review_body').MakeEditable();
	<?php endif; ?>
});
</script>
<?php endif; ?>

<div id="reviews_div">
	<h1><?php echo $this->_tpl_vars['reviews_block']->reviews_count; ?>
 <?php if ($this->_tpl_vars['reviews_block']->mode == 'reviews'): ?><?php echo $this->_tpl_vars['LANG_REVIEWS']; ?>
<?php else: ?><?php echo $this->_tpl_vars['LANG_COMMENTS']; ?>
<?php endif; ?></h1>
	<?php if ($this->_tpl_vars['reviews_block']->reviews_count): ?>
		<ul class="root_reviews_block">
			<?php $_from = $this->_tpl_vars['reviews_block']->reviews_structured_array; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['review']):
?>
				<?php echo $this->_tpl_vars['review']->view($this->_tpl_vars['comment_area_enabled']); ?>

			<?php endforeach; endif; unset($_from); ?>
		</ul>
	<?php endif; ?>
	
	<?php if ($this->_tpl_vars['comment_area_enabled']): ?>
		<?php if ($this->_tpl_vars['system_settings']['anonym_rates_reviews'] || $this->_tpl_vars['user']): ?>
			<a href="#" class="answer_link" id="review-0"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/comments.png"></a> <a href="#" style="font-size:14px; font-weight:bold;" class="answer_link" id="review-0"><?php if ($this->_tpl_vars['reviews_block']->mode == 'reviews'): ?><?php echo $this->_tpl_vars['LANG_ADD_REVIEW_HERE']; ?>
<?php else: ?><?php echo $this->_tpl_vars['LANG_COMMENT_LISTINGS']; ?>
<?php endif; ?></a>
			<div id="review_form">
				<input type="hidden" id="parent_id" value="0" />
				<?php if ($this->_tpl_vars['system_settings']['anonym_rates_reviews'] && ! $this->_tpl_vars['user']): ?>
					<div>
						<div class="admin_option_name" >
							<?php echo $this->_tpl_vars['LANG_ANONYM_NAME']; ?>
<span class="red_asterisk">*</span>
						</div>
						<input type="text" id="anonym_name" size="50"/>
					</div>
					<div>
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_ANONYM_EMAIL']; ?>
<span class="red_asterisk">*</span>
						</div>
						<input type="text" id="anonym_email" size="50"/>
					</div>
				<?php else: ?>
					<div class="review_author_self">
						<div class="review_author_img">
							<?php echo $this->_tpl_vars['user']->renderThmbImage(); ?>

						</div>
						<div class="review_author_name">
							<?php echo $this->_tpl_vars['user']->login; ?>

						</div>
						<div class="clear_float"></div>
					</div>
				<?php endif; ?>
				<div>
					<?php echo $this->_tpl_vars['reviews_block']->placeCommentArea(); ?>

				</div>
				<?php if ($this->_tpl_vars['system_settings']['anonym_rates_reviews'] && ! $this->_tpl_vars['user']): ?>
				<div>
					<div class="admin_option_name">
						<?php echo $this->_tpl_vars['LANG_FILL_CAPTCHA']; ?>
<span class="red_asterisk">*</span>
					</div>
					<input type="text" id="captcha" name="captcha" size="4">
					<div class="px10"></div>
					<?php echo $this->_tpl_vars['captcha']->view(); ?>

				<div>
				<?php endif; ?>
				<input id="add_review" class="front-btn" type="button" value="<?php if ($this->_tpl_vars['reviews_block']->mode == 'reviews'): ?><?php echo $this->_tpl_vars['LANG_ADD_REVIEW_BTN']; ?>
<?php else: ?><?php echo $this->_tpl_vars['LANG_ADD_COMMENT_BTN']; ?>
<?php endif; ?>" />
			</div>
		<?php else: ?>
			<?php if ($this->_tpl_vars['system_settings']['anonym_rates_reviews']): ?>
				<?php echo $this->_tpl_vars['LANG_REVIEW_LOGIN_ERROR']; ?>

			<?php endif; ?>
		<?php endif; ?>
	<?php endif; ?>
</div>