<?php /* Smarty version 2.6.26, created on 2012-02-06 03:58:49
         compiled from content_fields/common_date_range_search.tpl */ ?>
				<script language="javascript" type="text/javascript">
					$(document).ready( function() {
				       	var tmstmp_str = '';

				        <?php if ($this->_tpl_vars['search_mode'] == 'single'): ?>
				        	$("#range_toggle_<?php echo $this->_tpl_vars['date_var_name']; ?>
").hide();
				        <?php else: ?>
				        	$("#single_toggle_<?php echo $this->_tpl_vars['date_var_name']; ?>
").hide();
				        <?php endif; ?>

				        // Single date JS
				        $("#<?php echo $this->_tpl_vars['date_var_name']; ?>
").datepicker({
				        	showOn: "both",
				            buttonImage: "<?php echo $this->_tpl_vars['public_path']; ?>
images/calendar.png",
				            buttonImageOnly: true,
				            showButtonPanel: true,
							closeText: 'Clear',
				            onSelect: function(dateText) {
				            	if (dateText != '') {
					        		var sDate = $("#<?php echo $this->_tpl_vars['date_var_name']; ?>
").datepicker("getDate");
									if (sDate) {
										sDate.setMinutes(sDate.getMinutes() - sDate.getTimezoneOffset());
										tmstmp_str = $.datepicker.formatDate('@', sDate)/1000;
									} else 
										tmstmp_str = 0;

					                $("#search_tmstmp_<?php echo $this->_tpl_vars['date_var_name']; ?>
").val(tmstmp_str);
					            } else {
									$("#search_tmstmp_<?php echo $this->_tpl_vars['date_var_name']; ?>
").val('');
								}
				             }
				        });
				        <?php if ($this->_tpl_vars['date_var_name']): ?>
				        	$("#<?php echo $this->_tpl_vars['date_var_name']; ?>
").datepicker('setDate', $.datepicker.parseDate('yy-mm-dd', '<?php echo $this->_tpl_vars['single_date_var_value']; ?>
'));
				        <?php endif; ?>
				        $("#<?php echo $this->_tpl_vars['date_var_name']; ?>
").datepicker("option", $.datepicker.regional["<?php echo $this->_tpl_vars['current_language']; ?>
"]);

						// Range date JS
						$("#from_<?php echo $this->_tpl_vars['date_var_name']; ?>
").datepicker({
							showOn: "both",
							buttonImage: "<?php echo $this->_tpl_vars['public_path']; ?>
images/calendar.png",
							buttonImageOnly: true,
							showButtonPanel: true,
							closeText: 'Clear',
							onSelect: function(dateText) {
								if (dateText != '') {
									var sDate = $("#from_<?php echo $this->_tpl_vars['date_var_name']; ?>
").datepicker("getDate");
									if (sDate) {
										sDate.setMinutes(sDate.getMinutes() - sDate.getTimezoneOffset());
										tmstmp_str = $.datepicker.formatDate('@', sDate)/1000;
									} else 
										tmstmp_str = 0;

									$("#from_tmstmp_<?php echo $this->_tpl_vars['date_var_name']; ?>
").val(tmstmp_str);
									$("#to_<?php echo $this->_tpl_vars['date_var_name']; ?>
").datepicker('option', 'minDate', $("#from_<?php echo $this->_tpl_vars['date_var_name']; ?>
").datepicker('getDate'));
									if ($("#from_<?php echo $this->_tpl_vars['date_var_name']; ?>
").datepicker('getDate') > $("#to_<?php echo $this->_tpl_vars['date_var_name']; ?>
").datepicker('getDate')) {
										$("#to_<?php echo $this->_tpl_vars['date_var_name']; ?>
").val('');
									}
								} else {
									$("#from_tmstmp_<?php echo $this->_tpl_vars['date_var_name']; ?>
").val('');
									$("#to_<?php echo $this->_tpl_vars['date_var_name']; ?>
").datepicker('option', 'minDate', null);
								}
							}
						});
						$("#from_<?php echo $this->_tpl_vars['date_var_name']; ?>
").datepicker("option", $.datepicker.regional["<?php echo $this->_tpl_vars['current_language']; ?>
"]);

						$("#to_<?php echo $this->_tpl_vars['date_var_name']; ?>
").datepicker({
							showOn: "both",
							buttonImage: "<?php echo $this->_tpl_vars['public_path']; ?>
images/calendar.png",
							buttonImageOnly: true,
							showButtonPanel: true,
							closeText: 'Clear',
							onSelect: function(dateText) {
								if (dateText != '') {
									var sDate = $("#to_<?php echo $this->_tpl_vars['date_var_name']; ?>
").datepicker("getDate");
									if (sDate) {
										sDate.setMinutes(sDate.getMinutes() - sDate.getTimezoneOffset());
										tmstmp_str = $.datepicker.formatDate('@', sDate)/1000;
									} else 
										tmstmp_str = 0;

									$("#to_tmstmp_<?php echo $this->_tpl_vars['date_var_name']; ?>
").val(tmstmp_str);
									$("#from_<?php echo $this->_tpl_vars['date_var_name']; ?>
").datepicker('option', 'maxDate', $("#to_<?php echo $this->_tpl_vars['date_var_name']; ?>
").datepicker('getDate'));
									if ($("#from_<?php echo $this->_tpl_vars['date_var_name']; ?>
").datepicker('getDate') > $("#to_<?php echo $this->_tpl_vars['date_var_name']; ?>
").datepicker('getDate')) {
										$("#from_<?php echo $this->_tpl_vars['date_var_name']; ?>
").val('');
									}
								} else {
									$("#to_tmstmp_<?php echo $this->_tpl_vars['date_var_name']; ?>
").val('');
									$("#from_<?php echo $this->_tpl_vars['date_var_name']; ?>
").datepicker('option', 'maxDate', null);
								}
							}
						});
						$("#to_<?php echo $this->_tpl_vars['date_var_name']; ?>
").datepicker("option", $.datepicker.regional["<?php echo $this->_tpl_vars['current_language']; ?>
"]);

						<?php if ($this->_tpl_vars['from_date_var_value']): ?>
							$("#from_<?php echo $this->_tpl_vars['date_var_name']; ?>
").datepicker('setDate', $.datepicker.parseDate('yy-mm-dd', '<?php echo $this->_tpl_vars['from_date_var_value']; ?>
'));
							$("#to_<?php echo $this->_tpl_vars['date_var_name']; ?>
").datepicker('option', 'minDate', $("#from_<?php echo $this->_tpl_vars['date_var_name']; ?>
").datepicker('getDate'));
						<?php endif; ?>
						<?php if ($this->_tpl_vars['to_date_var_value']): ?>
							$("#to_<?php echo $this->_tpl_vars['date_var_name']; ?>
").datepicker('setDate', $.datepicker.parseDate('yy-mm-dd', '<?php echo $this->_tpl_vars['to_date_var_value']; ?>
'));
							$("#from_<?php echo $this->_tpl_vars['date_var_name']; ?>
").datepicker('option', 'maxDate', $("#to_<?php echo $this->_tpl_vars['date_var_name']; ?>
").datepicker('getDate'));
						<?php endif; ?>

						$(".show_dtr_<?php echo $this->_tpl_vars['date_var_name']; ?>
").click( function() {
							$("#range_toggle_<?php echo $this->_tpl_vars['date_var_name']; ?>
").show();
							$("#single_toggle_<?php echo $this->_tpl_vars['date_var_name']; ?>
").hide();
							<?php echo $this->_tpl_vars['random_id']; ?>
_mode = 'range';
							return false;
						});
						$(".show_dts_<?php echo $this->_tpl_vars['date_var_name']; ?>
").click( function() {
							$("#single_toggle_<?php echo $this->_tpl_vars['date_var_name']; ?>
").show();
							$("#range_toggle_<?php echo $this->_tpl_vars['date_var_name']; ?>
").hide();
							<?php echo $this->_tpl_vars['random_id']; ?>
_mode = 'single';
							return false;
						});
					});
                </script>

				<div class="search_item">
					<label><?php echo $this->_tpl_vars['field_title']; ?>
</label>
					<div id="single_toggle_<?php echo $this->_tpl_vars['date_var_name']; ?>
">
						<input type="text" size="10" value="" name="<?php echo $this->_tpl_vars['date_var_name']; ?>
" id="<?php echo $this->_tpl_vars['date_var_name']; ?>
"/>&nbsp;&nbsp;
						<input type="hidden" id="search_tmstmp_<?php echo $this->_tpl_vars['date_var_name']; ?>
" name="search_tmstmp_<?php echo $this->_tpl_vars['date_var_name']; ?>
" value="<?php echo $this->_tpl_vars['single_date_var_value_tmstmp']; ?>
">
						<a href="javascript: void(0);" class="toggle show_dtr_<?php echo $this->_tpl_vars['date_var_name']; ?>
" title="<?php echo $this->_tpl_vars['LANG_SELECT_DATE_RANGE']; ?>
"><img class="ui-datepicker-trigger" src="<?php echo $this->_tpl_vars['public_path']; ?>
images/calendar_add.png" /></a>
					</div>
					<div id="range_toggle_<?php echo $this->_tpl_vars['date_var_name']; ?>
">
						<b><?php echo $this->_tpl_vars['LANG_FROM']; ?>
</b>
						<input type="text" size="10" value="" name="from_<?php echo $this->_tpl_vars['date_var_name']; ?>
" id="from_<?php echo $this->_tpl_vars['date_var_name']; ?>
"/>&nbsp;&nbsp;
						—
						<b><?php echo $this->_tpl_vars['LANG_TO']; ?>
</b>
						<input type="text" size="10" value="" name="to_<?php echo $this->_tpl_vars['date_var_name']; ?>
" id="to_<?php echo $this->_tpl_vars['date_var_name']; ?>
"/>&nbsp;&nbsp;
						
						<a href="javascript: void(0);" class="toggle show_dts_<?php echo $this->_tpl_vars['date_var_name']; ?>
" title="<?php echo $this->_tpl_vars['LANG_SELECT_SINGLE_DATE']; ?>
"><img class="ui-datepicker-trigger" src="<?php echo $this->_tpl_vars['public_path']; ?>
images/calendar_delete.png" /></a>

						<input type="hidden" id="from_tmstmp_<?php echo $this->_tpl_vars['date_var_name']; ?>
" name="from_tmstmp_<?php echo $this->_tpl_vars['date_var_name']; ?>
" value="<?php echo $this->_tpl_vars['from_date_var_value_tmstmp']; ?>
">
						<input type="hidden" id="to_tmstmp_<?php echo $this->_tpl_vars['date_var_name']; ?>
" name="to_tmstmp_<?php echo $this->_tpl_vars['date_var_name']; ?>
" value="<?php echo $this->_tpl_vars['to_date_var_value_tmstmp']; ?>
">
					</div>
				</div>