<script language="JavaScript" type="text/javascript">
jQuery( function($) {ldelim}
	$("#search_form").submit( function() {ldelim}
		if ($("#{$from_index}").val() != '') 
			global_js_url = global_js_url + $("#{$from_index}").attr('id') + '/' + $("#{$from_index}").val() + '/';
		if ($("#{$to_index}").val() != '') 
			global_js_url = global_js_url + $("#{$to_index}").attr('id') + '/' + $("#{$to_index}").val() + '/';
			
		window.location.href = global_js_url;
		return false;
	{rdelim});
{rdelim});
</script>

							<div class="search_item">
								<label>{if $field->frontend_name}{$field->frontend_name}{else}{$field->name}{/if}</label>
								<div style="float: left; margin-right: 10px">
	                     			<span>{$LANG_FROM}</span>
	                     			<input type="text" name="{$from_index}" id="{$from_index}" value="{$args[$from_index]}" size="{$max_length}" maxlength="{$max_length}">
                     			</div>
                     			<div style="float: left;">
                     				<span>{$LANG_TO}</span>
	                     			<input type="text" name="{$to_index}" id="{$to_index}" value="{$args[$to_index]}" size="{$max_length}" maxlength="{$max_length}">
                     			</div>
                     			<div class="clear_float"></div>
                     		</div>