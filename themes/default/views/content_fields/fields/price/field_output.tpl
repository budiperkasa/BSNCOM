					{if $field_value && $field_currency}
					<div class="content_field_output">
						<nobr><strong>{if $field->frontend_name}{$field->frontend_name}{else}{$field->name}{/if}</strong>: {$field_currency} {$VH->number_format($field_value, 2, $decimals_separator, $thousands_separator)}</nobr>
					</div>
					{/if}