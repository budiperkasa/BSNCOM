{assign var="field_value_id" value=$field->field_value_id}

			<div class="admin_option_name">
				{$field->name}{if $field->required}<span class="red_asterisk">*</span>{/if}
				{translate_content table='content_fields_type_richtext_data' field='field_value' row_id=$field_value_id field_type='richtext'}
			</div>
			<div class="admin_option_description">
				{$field->description|nl2br}
			</div>
			{$value}
			<br />
			<br />