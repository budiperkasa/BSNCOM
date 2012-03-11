<script language="JavaScript" type="text/javascript">
jQuery( function($) {ldelim}
	$("#any_{$field->seo_name}").change( function() {ldelim}
		checkAll_{$field->seo_name}();
	{rdelim});

	$("#search_form").submit( function() {ldelim}
		if (!$("#any_{$field->seo_name}").is(":checked")) {ldelim}
			var url = [];
			$(".{$field_index}:checked").each( function() {ldelim}
				url.push($(this).val());
			{rdelim});
			url = url.join('-');
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

	checkAll_{$field->seo_name}();

	function checkAll_{$field->seo_name}() {ldelim}
		if ($("#any_{$field->seo_name}").is(":checked")) {ldelim}
			$(".{$field_index}").each( function() {ldelim}
				$(this).attr('disabled', 'disabled');
			{rdelim});
			$("input[name={$field_mode}]").attr('disabled', 'disabled');
		{rdelim} else {ldelim}
			$(".{$field_index}").each( function() {ldelim}
				$(this).attr('disabled', '');
			{rdelim});
			$("input[name={$field_mode}]").attr('disabled', '');
		{rdelim}
	{rdelim}
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
														<input type="checkbox" name="{$field_index}_{$key}" value="{$options[key].id}" id="{$field_index}_{$key}" class="search_{$field->seo_name}" {$options[key].checked}>
														{$options[key].option_name}
													</nobr>
													</label>
												{/section}
											</td>
											
											<td style="padding-right: {$td_padding_pixels}px" valign="top">
												{section name=key loop=$options start=1 step=3}
													<label style="display:block;">
													<nobr>
														<input type="checkbox" name="{$field_index}_{$key}" value="{$options[key].id}" id="{$field_index}_{$key}" class="search_{$field->seo_name}" {$options[key].checked}>
														{$options[key].option_name}
													</nobr>
													</label>
												{/section}
											</td>
											
											<td style="padding-right: {$td_padding_pixels}px" valign="top">
												{section name=key loop=$options start=2 step=3}
													<label style="display:block;">
													<nobr>
														<input type="checkbox" name="{$field_index}_{$key}" value="{$options[key].id}" id="{$field_index}_{$key}" class="search_{$field->seo_name}" {$options[key].checked}>
														{$options[key].option_name}
													</nobr>
													</label>
												{/section}
											</td>
										</tr>
									</table>
								</div>
								<div>
	                     			<span>{$LANG_MATCH}:</span><br />
	                     			<label style="display:block;"><input type="radio" name="{$field_mode}" value="any" {if $args[$field_mode] == 'any' || !$args[$field_mode]}checked{/if} /> {$LANG_ANY_MATCH}</label>
	                     			<label style="display:block;"><input type="radio" name="{$field_mode}" value="exact" {if $args[$field_mode] == 'exact'}checked{/if} /> {$LANG_EXACT_MATCH}</label>
                     			</div>
                     		</div>