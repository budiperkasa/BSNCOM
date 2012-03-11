<script language="JavaScript" type="text/javascript">
jQuery( function($) {ldelim}
	$("#search_form").submit( function() {ldelim}
		if ($("#{$currency_index}").val() != '') {ldelim}
			global_js_url = global_js_url + $("#{$currency_index}").attr('id') + '/' + $("#{$currency_index}").val() + '/';

			if ($("#{$from_index}").val() != '') 
				global_js_url = global_js_url + $("#{$from_index}").attr('id') + '/' + $("#{$from_index}").val() + '/';
			if ($("#{$to_index}").val() != '') 
				global_js_url = global_js_url + $("#{$to_index}").attr('id') + '/' + $("#{$to_index}").val() + '/';
		{rdelim}

		window.location.href = global_js_url;
		return false;
	{rdelim});
{rdelim});
</script>

							<div class="search_item">
								<label>{if $field->frontend_name}{$field->frontend_name}{else}{$field->name}{/if}</label>
								<div style="float: left; margin-right: 10px">
									<select id="{$currency_index}" name="{$currency_index}" style="min-width: 110px;">
										<option value="">{$LANG_SELECT_CURRENCY}</option>
										{foreach from=$options item=option}
										<option value="{$option.option_name}" {if $option.option_name == $args[$currency_index]}selected{/if}>{$option.option_name}</option>
										{/foreach}
									</select>
								</div>
								<div style="float: left; margin-right: 10px">
	                     			<span>{$LANG_FROM}</span>
	                     			<input type="text" name="{$from_index}" id="{$from_index}" value="{$args[$from_index]}" size="5">
                     			</div>
                     			<div style="float: left;">
                     				<span>{$LANG_TO}</span>
	                     			<input type="text" name="{$to_index}" id="{$to_index}" value="{$args[$to_index]}" size="5">
                     			</div>
                     			<div class="clear_float"></div>
                     		</div>