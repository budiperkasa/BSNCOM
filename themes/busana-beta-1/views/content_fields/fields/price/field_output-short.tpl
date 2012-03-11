					<td class="content_field_output">
						{if $field_value && $field_currency}
							<nobr><strong>{$field_currency} {$VH->number_format($field_value, 2, $decimals_separator, $thousands_separator)}</strong></nobr>
						{/if}
					</td>