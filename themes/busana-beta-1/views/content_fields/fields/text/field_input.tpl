{assign var="field_value_id" value=$field->field_value_id}
			
				<script language="javascript" type="text/javascript">
					$(document).ready( function() {ldelim}
						$("#{$field->seo_name}").keyup( function() {ldelim}
							chars_limit("{$field->seo_name}", {$max_length});
						{rdelim});
					{rdelim});
				</script>
					<div class="admin_option_name">
				   		{$field->name}{if $field->required}<span class="red_asterisk">*</span>{/if}
				   		{translate_content table='content_fields_type_text_data' field='field_value' row_id=$field_value_id field_type='text'}
				    </div>
				    <div class="admin_option_description">
				    	{$field->description|nl2br}
				    </div>
				    <div class="admin_option_description">
				    	{$LANG_SYMBOLS_LEFT}: <span id="{$field->seo_name}_symbols_left" class="symbols_left">{$count_chars}</span>
				    </div>
				    <textarea id="{$field->seo_name}" name="field_{$field->seo_name}" cols="90" rows="12">{$field->value}</textarea>
				    <br />
					<br />