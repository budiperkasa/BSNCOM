			<div class="admin_option_name">
				{$field->name}{if $field->required}<span class="red_asterisk">*</span>{/if}
			</div>
			<div class="admin_option_description">
				{$field->description|nl2br}
			</div>
			<script language="javascript" type="text/javascript">
                    $(document).ready( function() {ldelim}
                        $("#{$field->seo_name}").datepicker({ldelim}
                            showOn: "both",
                            buttonImage: "{$public_path}images/calendar.png",
                            buttonImageOnly: true,
                            showButtonPanel: true,
							closeText: 'Clear',
                            onSelect: function(dateText) {ldelim}
                            	var sDate = $("#{$field->seo_name}").datepicker("getDate");
                            	if (sDate) {ldelim}
									sDate.setMinutes(sDate.getMinutes() - sDate.getTimezoneOffset());
									tmstmp_str = $.datepicker.formatDate('@', sDate)/1000;
								{rdelim} else 
									tmstmp_str = 0;

                        		$("#tmstmp_{$field->seo_name}").val(tmstmp_str);
                        	{rdelim}
                        {rdelim});
                        $("#{$field->seo_name}").datepicker("option", $.datepicker.regional["{$current_language}"]);

                        {if $date != '' && $date != '0000-00-00 00:00:00' && $date != '1970-01-01 00:00:00'}
                        	$("#{$field->seo_name}").datepicker('setDate', $.datepicker.parseDate('yy-mm-dd', '{$date}'));
                        {/if}
                    {rdelim});
			</script>
			{$LANG_DATE}:&nbsp;
			<input type="text" size="10" value="" name="field_{$field->seo_name}" id="{$field->seo_name}"/>&nbsp;&nbsp;
			<input type="hidden" id="tmstmp_{$field->seo_name}" name="tmstmp_{$field->seo_name}" value="{$date_tmstmp}">
			{if $enable_time}
			{$LANG_TIME}:&nbsp;
			<input type="text" size="1" value="{$time_h}" maxlength="2" name="h_{$field->seo_name}">&nbsp;:
			<input type="text" size="1" value="{$time_m}" maxlength="2" name="m_{$field->seo_name}">&nbsp;:
			<input type="text" size="1" value="{$time_s}" maxlength="2" name="s_{$field->seo_name}">
			{else}
			<input type="hidden" value="00" name="h_{$field->seo_name}">
			<input type="hidden" value="00" name="m_{$field->seo_name}">
			<input type="hidden" value="00" name="s_{$field->seo_name}">
			{/if}
			<br />
			<br />