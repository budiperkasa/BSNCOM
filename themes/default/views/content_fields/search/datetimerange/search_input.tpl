{assign var=random_id value=$VH->genRandomString()}

						<script language="javascript" type="text/javascript">
								var {$random_id}_mode = '{$mode}';

				                    $(document).ready( function() {ldelim}
				                        $("#search_form").submit( function() {ldelim}
				                        	if ({$random_id}_mode == 'single') {ldelim}
					                        	if ($("#search_tmstmp_{$field->seo_name}").val() != '')
													global_js_url = global_js_url + "{$field_index}" + '/' + $("#search_tmstmp_{$field->seo_name}").val() + '/';
											{rdelim}
											if ({$random_id}_mode == 'range') {ldelim}
												if ($("#from_tmstmp_{$field->seo_name}").val() != '')
													global_js_url = global_js_url + "from_tmstmp_{$field->seo_name}" + '/' + $("#from_tmstmp_{$field->seo_name}").val() + '/';
												if ($("#to_tmstmp_{$field->seo_name}").val() != '')
													global_js_url = global_js_url + "to_tmstmp_{$field->seo_name}" + '/' + $("#to_tmstmp_{$field->seo_name}").val() + '/';
											{rdelim}
											window.location.href = global_js_url;
											return false;
										{rdelim});
									{rdelim});
							</script>

{if $field->frontend_name}
	{assign var=field_name value=$field->frontend_name}
{else}
	{assign var=field_name value=$field->name}
{/if}

							{assign var=field_title value=$field_name}
							{assign var=search_mode value=$mode}
							{assign var=date_var_name value=$field->seo_name}
							{assign var=single_date_var_value value=$date}
							{assign var=single_date_var_value_tmstmp value=$date_tmstmp}
							{assign var=from_date_var_value value=$from_date}
							{assign var=from_date_var_value_tmstmp value=$from_date_tmstmp}
							{assign var=to_date_var_value value=$to_date}
							{assign var=to_date_var_value_tmstmp value=$to_date_tmstmp}
							{include file="content_fields/common_date_range_search.tpl"}