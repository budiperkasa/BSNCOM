					{if $field->value != ''}
					<div class="content_field_output">
						{if $field->frontend_name}<strong>{$field->frontend_name}</strong>:{/if} {$field->value}
					</div>
					{/if}