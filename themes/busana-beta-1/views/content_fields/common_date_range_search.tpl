				<script language="javascript" type="text/javascript">
					$(document).ready( function() {ldelim}
				       	var tmstmp_str = '';

				        {if $search_mode == 'single'}
				        	$("#range_toggle_{$date_var_name}").hide();
				        {else}
				        	$("#single_toggle_{$date_var_name}").hide();
				        {/if}

				        // Single date JS
				        $("#{$date_var_name}").datepicker({ldelim}
				        	showOn: "both",
				            buttonImage: "{$public_path}images/calendar.png",
				            buttonImageOnly: true,
				            showButtonPanel: true,
							closeText: 'Clear',
				            onSelect: function(dateText) {ldelim}
				            	if (dateText != '') {ldelim}
					        		var sDate = $("#{$date_var_name}").datepicker("getDate");
									if (sDate) {ldelim}
										sDate.setMinutes(sDate.getMinutes() - sDate.getTimezoneOffset());
										tmstmp_str = $.datepicker.formatDate('@', sDate)/1000;
									{rdelim} else 
										tmstmp_str = 0;

					                $("#search_tmstmp_{$date_var_name}").val(tmstmp_str);
					            {rdelim} else {ldelim}
									$("#search_tmstmp_{$date_var_name}").val('');
								{rdelim}
				             {rdelim}
				        {rdelim});
				        {if $date_var_name}
				        	$("#{$date_var_name}").datepicker('setDate', $.datepicker.parseDate('yy-mm-dd', '{$single_date_var_value}'));
				        {/if}
				        $("#{$date_var_name}").datepicker("option", $.datepicker.regional["{$current_language}"]);

						// Range date JS
						$("#from_{$date_var_name}").datepicker({ldelim}
							showOn: "both",
							buttonImage: "{$public_path}images/calendar.png",
							buttonImageOnly: true,
							showButtonPanel: true,
							closeText: 'Clear',
							onSelect: function(dateText) {ldelim}
								if (dateText != '') {ldelim}
									var sDate = $("#from_{$date_var_name}").datepicker("getDate");
									if (sDate) {ldelim}
										sDate.setMinutes(sDate.getMinutes() - sDate.getTimezoneOffset());
										tmstmp_str = $.datepicker.formatDate('@', sDate)/1000;
									{rdelim} else 
										tmstmp_str = 0;

									$("#from_tmstmp_{$date_var_name}").val(tmstmp_str);
									$("#to_{$date_var_name}").datepicker('option', 'minDate', $("#from_{$date_var_name}").datepicker('getDate'));
									if ($("#from_{$date_var_name}").datepicker('getDate') > $("#to_{$date_var_name}").datepicker('getDate')) {ldelim}
										$("#to_{$date_var_name}").val('');
									{rdelim}
								{rdelim} else {ldelim}
									$("#from_tmstmp_{$date_var_name}").val('');
									$("#to_{$date_var_name}").datepicker('option', 'minDate', null);
								{rdelim}
							{rdelim}
						{rdelim});
						$("#from_{$date_var_name}").datepicker("option", $.datepicker.regional["{$current_language}"]);

						$("#to_{$date_var_name}").datepicker({ldelim}
							showOn: "both",
							buttonImage: "{$public_path}images/calendar.png",
							buttonImageOnly: true,
							showButtonPanel: true,
							closeText: 'Clear',
							onSelect: function(dateText) {ldelim}
								if (dateText != '') {ldelim}
									var sDate = $("#to_{$date_var_name}").datepicker("getDate");
									if (sDate) {ldelim}
										sDate.setMinutes(sDate.getMinutes() - sDate.getTimezoneOffset());
										tmstmp_str = $.datepicker.formatDate('@', sDate)/1000;
									{rdelim} else 
										tmstmp_str = 0;

									$("#to_tmstmp_{$date_var_name}").val(tmstmp_str);
									$("#from_{$date_var_name}").datepicker('option', 'maxDate', $("#to_{$date_var_name}").datepicker('getDate'));
									if ($("#from_{$date_var_name}").datepicker('getDate') > $("#to_{$date_var_name}").datepicker('getDate')) {ldelim}
										$("#from_{$date_var_name}").val('');
									{rdelim}
								{rdelim} else {ldelim}
									$("#to_tmstmp_{$date_var_name}").val('');
									$("#from_{$date_var_name}").datepicker('option', 'maxDate', null);
								{rdelim}
							{rdelim}
						{rdelim});
						$("#to_{$date_var_name}").datepicker("option", $.datepicker.regional["{$current_language}"]);

						{if $from_date_var_value}
							$("#from_{$date_var_name}").datepicker('setDate', $.datepicker.parseDate('yy-mm-dd', '{$from_date_var_value}'));
							$("#to_{$date_var_name}").datepicker('option', 'minDate', $("#from_{$date_var_name}").datepicker('getDate'));
						{/if}
						{if $to_date_var_value}
							$("#to_{$date_var_name}").datepicker('setDate', $.datepicker.parseDate('yy-mm-dd', '{$to_date_var_value}'));
							$("#from_{$date_var_name}").datepicker('option', 'maxDate', $("#to_{$date_var_name}").datepicker('getDate'));
						{/if}

						$(".show_dtr_{$date_var_name}").click( function() {ldelim}
							$("#range_toggle_{$date_var_name}").show();
							$("#single_toggle_{$date_var_name}").hide();
							{$random_id}_mode = 'range';
							return false;
						{rdelim});
						$(".show_dts_{$date_var_name}").click( function() {ldelim}
							$("#single_toggle_{$date_var_name}").show();
							$("#range_toggle_{$date_var_name}").hide();
							{$random_id}_mode = 'single';
							return false;
						{rdelim});
					{rdelim});
                </script>

				<div class="search_item">
					<label>{$field_title}</label>
					<div id="single_toggle_{$date_var_name}">
						<input type="text" size="10" value="" name="{$date_var_name}" id="{$date_var_name}"/>&nbsp;&nbsp;
						<input type="hidden" id="search_tmstmp_{$date_var_name}" name="search_tmstmp_{$date_var_name}" value="{$single_date_var_value_tmstmp}">
						<a href="javascript: void(0);" class="toggle show_dtr_{$date_var_name}" title="{$LANG_SELECT_DATE_RANGE}"><img class="ui-datepicker-trigger" src="{$public_path}images/calendar_add.png" /></a>
					</div>
					<div id="range_toggle_{$date_var_name}">
						<b>{$LANG_FROM}</b>
						<input type="text" size="10" value="" name="from_{$date_var_name}" id="from_{$date_var_name}"/>&nbsp;&nbsp;
						—
						<b>{$LANG_TO}</b>
						<input type="text" size="10" value="" name="to_{$date_var_name}" id="to_{$date_var_name}"/>&nbsp;&nbsp;
						
						<a href="javascript: void(0);" class="toggle show_dts_{$date_var_name}" title="{$LANG_SELECT_SINGLE_DATE}"><img class="ui-datepicker-trigger" src="{$public_path}images/calendar_delete.png" /></a>

						<input type="hidden" id="from_tmstmp_{$date_var_name}" name="from_tmstmp_{$date_var_name}" value="{$from_date_var_value_tmstmp}">
						<input type="hidden" id="to_tmstmp_{$date_var_name}" name="to_tmstmp_{$date_var_name}" value="{$to_date_var_value_tmstmp}">
					</div>
				</div>