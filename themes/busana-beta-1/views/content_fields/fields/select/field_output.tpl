					{if $value && $option_id != -1}
					<div class="content_field_output"">
						<strong>{if $field->frontend_name}{$field->frontend_name}{else}{$field->name}{/if}</strong>: {$value}
					</div>
					{/if}