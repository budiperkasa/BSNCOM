			<div class="admin_option_name">
				{$field->name}{if $field->required}<span class="red_asterisk">*</span>{/if}
			</div>
			<div class="admin_option_description">
				{$field->description|nl2br}
			</div>

			<script language="javascript" type="text/javascript">
                    $(document).ready( function() {ldelim}
						$("#from_{$field->seo_name}").datepicker({ldelim}
							showOn: "both",
							buttonImage: "{$public_path}images/calendar.png",
							buttonImageOnly: true,
							showButtonPanel: true,
							closeText: 'Clear',
							onSelect: function(dateText) {ldelim}
								if (dateText != '') {ldelim}
									var sDate = $("#from_{$field->seo_name}").datepicker("getDate");
									if (sDate) {ldelim}
										sDate.setMinutes(sDate.getMinutes() - sDate.getTimezoneOffset());
										tmstmp_str = $.datepicker.formatDate('@', sDate)/1000;
									{rdelim} else 
										tmstmp_str = 0;

									$("#from_tmstmp_{$field->seo_name}").val(tmstmp_str);
									$("#to_{$field->seo_name}").datepicker('option', 'minDate', $("#from_{$field->seo_name}").datepicker('getDate'));
									if ($("#from_{$field->seo_name}").datepicker('getDate') > $("#to_{$field->seo_name}").datepicker('getDate')) {ldelim}
										$("#to_{$field->seo_name}").val('');
									{rdelim}
								{rdelim} else {ldelim}
									$("#from_tmstmp_{$field->seo_name}").val('');
									$("#to_{$field->seo_name}").datepicker('option', 'minDate', null);
								{rdelim}
							{rdelim}
				        {rdelim});
				        $("#from_{$field->seo_name}").datepicker("option", $.datepicker.regional["{$current_language}"]);

						$("#to_{$field->seo_name}").datepicker({ldelim}
							showOn: "both",
							buttonImage: "{$public_path}images/calendar.png",
							buttonImageOnly: true,
							showButtonPanel: true,
							closeText: 'Clear',
							onSelect: function(dateText) {ldelim}
								if (dateText != '') {ldelim}
									var sDate = $("#to_{$field->seo_name}").datepicker("getDate");
									if (sDate) {ldelim}
										sDate.setMinutes(sDate.getMinutes() - sDate.getTimezoneOffset());
										tmstmp_str = $.datepicker.formatDate('@', sDate)/1000;
									{rdelim} else 
										tmstmp_str = 0;

									$("#to_tmstmp_{$field->seo_name}").val(tmstmp_str);
									$("#from_{$field->seo_name}").datepicker('option', 'maxDate', $("#to_{$field->seo_name}").datepicker('getDate'));
									if ($("#from_{$field->seo_name}").datepicker('getDate') > $("#to_{$field->seo_name}").datepicker('getDate')) {ldelim}
										$("#from_{$field->seo_name}").val('');
									{rdelim}
								{rdelim} else {ldelim}
									$("#to_tmstmp_{$field->seo_name}").val('');
									$("#from_{$field->seo_name}").datepicker('option', 'maxDate', null);
								{rdelim}
							{rdelim}
						{rdelim});
						$("#to_{$field->seo_name}").datepicker("option", $.datepicker.regional["{$current_language}"]);

						{if $from_date != '' && $from_date != '0000-00-00 00:00:00' && $from_date != '1970-01-01 00:00:00'}
							$("#from_{$field->seo_name}").datepicker('setDate', $.datepicker.parseDate('yy-mm-dd', '{$from_date}'));
							$("#to_{$field->seo_name}").datepicker('option', 'minDate', $("#from_{$field->seo_name}").datepicker('getDate'));
						{/if}
						{if $to_date != '' && $to_date != '9999-12-31 00:00:00'}
							$("#to_{$field->seo_name}").datepicker('setDate', $.datepicker.parseDate('yy-mm-dd', '{$to_date}'));
							$("#from_{$field->seo_name}").datepicker('option', 'maxDate', $("#to_{$field->seo_name}").datepicker('getDate'));
						{/if}
                    {rdelim});
			</script>
			<div><b>1. {$LANG_SELECT_DATES_RANGE}:</b></div>
			<div style="float:left; padding-right:20px;">
				<b>{$LANG_FROM_DATERANGE}</b><br/>
				{$LANG_DATE}:&nbsp;
				<input type="text" size="10" value="" name="from_{$field->seo_name}" id="from_{$field->seo_name}"/>&nbsp;&nbsp;
				<input type="hidden" id="from_tmstmp_{$field->seo_name}" name="from_tmstmp_{$field->seo_name}" value="{$from_date_tmstmp}">
				{if $enable_time}
				{$LANG_TIME}:&nbsp;
				<input type="text" size="1" value="{$from_time_h}" maxlength="2" name="from_h_{$field->seo_name}">&nbsp;:
				<input type="text" size="1" value="{$from_time_m}" maxlength="2" name="from_m_{$field->seo_name}">&nbsp;:
				<input type="text" size="1" value="{$from_time_s}" maxlength="2" name="from_s_{$field->seo_name}">
				{else}
				<input type="hidden" value="00" name="from_h_{$field->seo_name}">
				<input type="hidden" value="00" name="from_m_{$field->seo_name}">
				<input type="hidden" value="00" name="from_s_{$field->seo_name}">
				{/if}
			</div>

			<div style="float:left">
				<b>{$LANG_TO_DATERANGE}</b><br/>
				{$LANG_DATE}:&nbsp;
				<input type="text" size="10" value="" name="to_{$field->seo_name}" id="to_{$field->seo_name}"/>&nbsp;&nbsp;
				<input type="hidden" id="to_tmstmp_{$field->seo_name}" name="to_tmstmp_{$field->seo_name}" value="{$to_date_tmstmp}">
				{if $enable_time}
				{$LANG_TIME}:&nbsp;
				<input type="text" size="1" value="{$to_time_h}" maxlength="2" name="to_h_{$field->seo_name}">&nbsp;:
				<input type="text" size="1" value="{$to_time_m}" maxlength="2" name="to_m_{$field->seo_name}">&nbsp;:
				<input type="text" size="1" value="{$to_time_s}" maxlength="2" name="to_s_{$field->seo_name}">
				{else}
				<input type="hidden" value="00" name="to_h_{$field->seo_name}">
				<input type="hidden" value="00" name="to_m_{$field->seo_name}">
				<input type="hidden" value="00" name="to_s_{$field->seo_name}">
				{/if}
			</div>
			<div class="clear_float"></div>
			
			<br />

			<script language="javascript" type="text/javascript">
                    $(document).ready( function() {ldelim}
						$("#dates_{$field->seo_name}").multiDatesPicker({ldelim}
							altField: '#selected_dates_{$field->seo_name}'
						{rdelim});
						{foreach from=$dates_array item=date}
							$("#dates_{$field->seo_name}").multiDatesPicker('addDates', $.datepicker.parseDate('yy-mm-dd', '{$date}'));
						{/foreach}
						var dates = $("#dates_{$field->seo_name}").multiDatesPicker('getDates');
						$("#selected_dates_{$field->seo_name}").val(dates.join(','));

						$("#cycle_days_everyday_{$field->seo_name}").change( function() {ldelim}
							if ($("#cycle_days_everyday_{$field->seo_name}").is(":checked")) {ldelim}
								$(".cycle_days_{$field->seo_name}").each( function() {ldelim}
									$(this).attr('checked', 'checked');
								{rdelim});
							{rdelim} else {ldelim}
								$(".cycle_days_{$field->seo_name}").each( function() {ldelim}
									$(this).attr('checked', '');
								{rdelim});
							{rdelim}
						{rdelim});
						$(".cycle_days_{$field->seo_name}").change( function() {ldelim}
							if (!$(this).is(":checked")) {ldelim}
								$("#cycle_days_everyday_{$field->seo_name}").attr('checked', '');
							{rdelim}
						{rdelim});
					{rdelim});
			</script>
			<div style="float:left;">
				<b>2. {$LANG_SELECT_EXACT_DATES}:</b><br />
				<div id="dates_{$field->seo_name}"></div>
				<input type="hidden" id="selected_dates_{$field->seo_name}" name="selected_dates_{$field->seo_name}" value="" />
			</div>

			<div style="float:left; padding-left:50px;">
				<b>3. {$LANG_SELECT_CYCLIC_DATES}:</b><br />
				<label><input type=checkbox id="cycle_days_everyday_{$field->seo_name}" value="1" {if $cycle_days_monday && $cycle_days_tuesday && $cycle_days_wednesday && $cycle_days_friday && $cycle_days_saturday && $cycle_days_sunday}checked{/if} /> {$LANG_EVERY_DAY}</label>
				<label><input type=checkbox name="cycle_days_monday_{$field->seo_name}" value="1" class="cycle_days_{$field->seo_name}" {if $cycle_days_monday}checked{/if} /> {$LANG_EVERY_MONDAY}</label>
				<label><input type=checkbox name="cycle_days_tuesday_{$field->seo_name}" value="1" class="cycle_days_{$field->seo_name}" {if $cycle_days_tuesday}checked{/if}/> {$LANG_EVERY_TUESDAY}</label>
				<label><input type=checkbox name="cycle_days_wednesday_{$field->seo_name}" value="1" class="cycle_days_{$field->seo_name}" {if $cycle_days_wednesday}checked{/if} /> {$LANG_EVERY_WEDNESDAY}</label>
				<label><input type=checkbox name="cycle_days_thursday_{$field->seo_name}" value="1" class="cycle_days_{$field->seo_name}" {if $cycle_days_thursday}checked{/if} /> {$LANG_EVERY_THURSDAY}</label>
				<label><input type=checkbox name="cycle_days_friday_{$field->seo_name}" value="1" class="cycle_days_{$field->seo_name}" {if $cycle_days_friday}checked{/if} /> {$LANG_EVERY_FRIDAY}</label>
				<label><input type=checkbox name="cycle_days_saturday_{$field->seo_name}" value="1" class="cycle_days_{$field->seo_name}" {if $cycle_days_saturday}checked{/if} /> {$LANG_EVERY_SATURDAY}</label>
				<label><input type=checkbox name="cycle_days_sunday_{$field->seo_name}" value="1" class="cycle_days_{$field->seo_name}" {if $cycle_days_sunday}checked{/if} /> {$LANG_EVERY_SUNDAY}</label>
			</div>
			<div class="clear_float"></div>

			<br />
			<br />