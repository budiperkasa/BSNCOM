<script language="JavaScript" type="text/javascript">
jQuery( function($) {ldelim}
	$("#search_form").submit( function() {ldelim}
		if ($("#{$from_index}").val() != '')
			global_js_url = global_js_url + $("#{$field_index}").attr('id') + '/' + $("#{$field_index}").val() + '/';

		window.location.href = global_js_url;
		return false;
	{rdelim});
{rdelim});
</script>

							<div class="search_item">
								<label>{if $field->frontend_name}{$field->frontend_name}{else}{$field->name}{/if}</label>
								<input type="text" name="{$field_index}" id="{$field_index}" value="{$args[$field_index]}" size="{$max_length}" maxlength="{$max_length}">
                     		</div>