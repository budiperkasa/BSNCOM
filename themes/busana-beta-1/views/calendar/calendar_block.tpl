	<script language="javascript" type="text/javascript">
		$(document).ready( function() {ldelim}
			$("#datepicker").datepicker({ldelim}
				changeMonth: true,
				changeYear: true,
				onSelect: function(dateText) {ldelim}
					window.location.href = '{$url}'+$.datepicker.formatDate('yy-mm-dd', $("#datepicker").datepicker("getDate"));
				{rdelim}
			{rdelim});
			{if ($defaultDate)}
			$("#datepicker").datepicker('setDate', $.datepicker.parseDate('@', '{$defaultDate}000'));
			{/if}
			$("#datepicker").datepicker("option", $.datepicker.regional["{$current_language}"]);
		{rdelim});
	</script>
	<div class="block calendar_block">
		<!-- Heading Starts -->
		<div class="block-top"><div class="block-top-title">{$name}</div></div>
		<div id="datepicker"></div>
	</div>
	