<?php /* Smarty version 2.6.26, created on 2012-02-08 07:16:19
         compiled from ratings_reviews/review_item-comments.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', 'ratings_reviews/review_item-comments.tpl', 39, false),array('modifier', 'count', 'ratings_reviews/review_item-comments.tpl', 55, false),)), $this); ?>
<?php $this->assign('user', $this->_tpl_vars['review']->getUser()); ?>
<?php $this->assign('object', $this->_tpl_vars['review']->getObject()); ?>
<?php $this->assign('rating', $this->_tpl_vars['review']->setRating()); ?>

<?php $this->assign('review_id', $this->_tpl_vars['review']->id); ?>
<?php $this->assign('owner', $this->_tpl_vars['review']->object->getOwner()); ?>
<li>
	<div class="review">
		<?php if ($this->_tpl_vars['review']->status == 2): ?>
			<div class="blocked_review">
				<?php echo $this->_tpl_vars['LANG_REVIEWS_MODERATED_AND_BLOCKED']; ?>

			</div>
		<?php else: ?>
			<div class="review_author <?php if ($this->_tpl_vars['owner']->id == $this->_tpl_vars['review']->user->id): ?>owner_of_object<?php endif; ?>">
				<?php if ($this->_tpl_vars['review']->user->users_group->logo_enabled): ?>
				<div class="review_author_img">
					<?php echo $this->_tpl_vars['review']->user->renderThmbImage(); ?>

				</div>
				<?php endif; ?>
				<div class="review_author_name">
				<?php if ($this->_tpl_vars['review']->user_id): ?>
					<?php $this->assign('review_user_id', $this->_tpl_vars['review']->user->id); ?>
					<?php if ($this->_tpl_vars['review_user_id'] != 1 && $this->_tpl_vars['content_access_obj']->isPermission('Manage users')): ?>
						<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/view/".($this->_tpl_vars['review_user_id'])); ?>
"><?php echo $this->_tpl_vars['review']->user->login; ?>
</a>
					<?php else: ?>
						<?php echo $this->_tpl_vars['review']->user->login; ?>

					<?php endif; ?>
				<?php else: ?>
					<?php echo $this->_tpl_vars['LANG_ANONYM']; ?>
: <?php echo $this->_tpl_vars['review']->anonym_name; ?>
 (<?php echo $this->_tpl_vars['review']->ip; ?>
)
				<?php endif; ?>
				<?php if ($this->_tpl_vars['owner']->id == $this->_tpl_vars['review']->user->id): ?> <i>(<?php echo $this->_tpl_vars['LANG_LISTING_OWNER']; ?>
)</i><?php endif; ?>
				</div>
				<?php if ($this->_tpl_vars['review']->user_id && $this->_tpl_vars['review']->rating): ?>
				<div class="review_author_rating">
					<?php echo $this->_tpl_vars['review']->rating->view(); ?>

				</div>
				<?php endif; ?>
				<div class="review_author_date">
					<?php echo ((is_array($_tmp=$this->_tpl_vars['review']->date_added)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%D %H:%M") : smarty_modifier_date_format($_tmp, "%D %H:%M")); ?>

				</div>
				<div class="clear_float"></div>
			</div>
			<div class="review_body">
				<?php echo $this->_tpl_vars['review']->review; ?>

			</div>
			<?php if ($this->_tpl_vars['comment_area_enabled']): ?>
				<?php if ($this->_tpl_vars['system_settings']['anonym_rates_reviews'] || $this->_tpl_vars['user_login']): ?>
				<div class="answer_link" id="review-<?php echo $this->_tpl_vars['review']->id; ?>
">	
					<a href="#"><?php echo $this->_tpl_vars['LANG_REPLY_COMMENT']; ?>
</a>
				</div>
				<?php endif; ?>
			<?php endif; ?>
		<?php endif; ?>
	</div>
	<?php if (count($this->_tpl_vars['review']->children)): ?>
	<ul class="reviews_block">
	<?php $_from = $this->_tpl_vars['review']->children; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['child']):
?>
		<?php echo $this->_tpl_vars['child']->view(); ?>

	<?php endforeach; endif; unset($_from); ?>
	</ul>
	<?php endif; ?>
</li>