{if $avg_rating->active}
<script language="Javascript" type="text/javascript">
	$(document).ready(function() {ldelim}
		$(function() {ldelim}
			$("#rater-{$avg_rating->getObjectId()}").rater({ldelim}postHref: "{$avg_rating->url_to_rate}"{rdelim});
		{rdelim});
	{rdelim});
</script>
{/if}

<div id="rater-{$avg_rating->getObjectId()}" class="stat">
	<div class="statVal">
		<span class="ui-rater" title="{$LANG_AVERAGE}: {$avg_rating->avg_value} ({$LANG_RATINGS}: {$avg_rating->ratings_count})">
			<span class="ui-rater-starsOff" style="width:90px;"><span class="ui-rater-starsOn" style="width:{$avg_rating->avg_value*18}px"></span></span>
		</span>
	</div>
</div>