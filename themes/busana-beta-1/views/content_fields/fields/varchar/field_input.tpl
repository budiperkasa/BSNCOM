{assign var="field_value_id" value=$field->field_value_id}

			<div class="admin_option_name">
				{$field->name}{if $field->required}<span class="red_asterisk">*</span>{/if}
				{if !$is_numeric && !$regex}
					{translate_content table='content_fields_type_varchar_data' field='field_value' row_id=$field_value_id}
				{/if}
			</div>
			<div class="admin_option_description">
				{$field->description|nl2br}
			</div>
			<input type="text" name="field_{$field->seo_name}" value="{$value}" size="{$field_length}" maxlength="{$max_length}" class="admin_option_input">
			<br />
			<br />