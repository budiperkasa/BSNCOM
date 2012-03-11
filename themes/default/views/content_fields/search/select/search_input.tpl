<script language="JavaScript" type="text/javascript">
jQuery( function($) {ldelim}
	$("#any_{$field->seo_name}").change( function() {ldelim}
		if ($("#any_{$field->seo_name}").is(":checked")) {ldelim}
			$(".{$field_index}").each( function() {ldelim}
				$(this).attr('disabled', 'disabled');
			{rdelim});
		{rdelim} else {ldelim}
			$(".{$field_index}").each( function() {ldelim}
				$(this).attr('disabled', '');
			{rdelim});
		{rdelim}
	{rdelim});

	$("#search_form").submit( function() {ldelim}
		if (!$("#any_{$field->seo_name}").is(":checked")) {ldelim}
			var options = [];
			$(".{$field_index}:checked").each( function() {ldelim}
				options.push($(this).val());
			{rdelim});
			var url = options.join('-');
		{rdelim} else
			url = 'any';

		if (url != '') {ldelim}
			global_js_url = global_js_url + "{$field_index}" + '/' + url + '/';
			if (!$("#any_{$field->seo_name}").is(":checked"))
				global_js_url = global_js_url + "{$field_mode}" + '/' + $("input[name={$field_mode}]:checked").val() + '/';
		{rdelim}
		
		window.location.href = global_js_url;
		return false;
	{rdelim});
{rdelim});
</script>

							<div class="search_item">
								<label>{if $field->frontend_name}{$field->frontend_name}{else}{$field->name}{/if}</label>
								<div>
									<table cellspacing="3" cellpadding="0">
										{if $options|@count}
										<tr>
											<td width="10px" colspan=3><input type="checkbox" id="any_{$field->seo_name}" {if $check_all}checked{/if} />
												{$LANG_SHOW_ALL}
											</td>
										</tr>
										{/if}
										<tr>
											{assign var=td_padding_pixels value=20}
											{assign var=i value=0}

											<td style="padding-right: {$td_padding_pixels}px" valign="top">
												{section name=key loop=$options start=0 step=3}
													<label style="display:block;">
													<nobr>
														<input type="checkbox" name="{$field_index}_{$key}" value="{$options[key].id}" class="search_{$field->seo_name}" {$options[key].checked} {if $check_all}disabled{/if} />
														{$options[key].option_name}
													</nobr>
													</label>
												{/section}
											</td>
											
											<td style="padding-right: {$td_padding_pixels}px" valign="top">
												{section name=key loop=$options start=1 step=3}
													<label style="display:block;">
													<nobr>
														<input type="checkbox" name="{$field_index}_{$key}" value="{$options[key].id}" class="search_{$field->seo_name}" {$options[key].checked} {if $check_all}disabled{/if} />
														{$options[key].option_name}
													</nobr>
													</label>
												{/section}
											</td>
											
											<td style="padding-right: {$td_padding_pixels}px" valign="top">
												{section name=key loop=$options start=2 step=3}
													<label style="display:block;">
													<nobr>
														<input type="checkbox" name="{$field_index}_{$key}" value="{$options[key].id}" class="search_{$field->seo_name}" {$options[key].checked} {if $check_all}disabled{/if} />
														{$options[key].option_name}
													</nobr>
													</label>
												{/section}
											</td>
										</tr>
									</table>
								</div>
                     		</div>