<?php /* Smarty version 2.6.26, created on 2012-02-06 08:44:32
         compiled from ratings_reviews/avg_rating.tpl */ ?>
<?php if ($this->_tpl_vars['avg_rating']->active): ?>
<script language="Javascript" type="text/javascript">
	$(document).ready(function() {
		$(function() {
			$("#rater-<?php echo $this->_tpl_vars['avg_rating']->getObjectId(); ?>
").rater({postHref: "<?php echo $this->_tpl_vars['avg_rating']->url_to_rate; ?>
"});
		});
	});
</script>
<?php endif; ?>

<div id="rater-<?php echo $this->_tpl_vars['avg_rating']->getObjectId(); ?>
" class="stat">
	<div class="statVal">
		<span class="ui-rater" title="<?php echo $this->_tpl_vars['LANG_AVERAGE']; ?>
: <?php echo $this->_tpl_vars['avg_rating']->avg_value; ?>
 (<?php echo $this->_tpl_vars['LANG_RATINGS']; ?>
: <?php echo $this->_tpl_vars['avg_rating']->ratings_count; ?>
)">
			<span class="ui-rater-starsOff" style="width:90px;"><span class="ui-rater-starsOn" style="width:<?php echo $this->_tpl_vars['avg_rating']->avg_value*18; ?>
px"></span></span>
		</span>
	</div>
</div>