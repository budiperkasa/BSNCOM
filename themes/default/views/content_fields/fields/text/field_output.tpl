					{if $field->value != ''}
					<div class="content_field_output">
						{if $field->frontend_name}<strong>{$field->frontend_name}</strong>:<br />{/if}
						{$field->value|nl2br}
					</div>
					{/if}