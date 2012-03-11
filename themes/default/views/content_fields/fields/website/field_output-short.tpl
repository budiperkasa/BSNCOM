					<td class="content_field_output">
						{if $field->value}
							{if !$enable_redirect}
								<a href="{$value}" target="_blank" title="{$value}">{$value}</a>
							{else}
								<a href="{$VH->site_url("redirect/$field_value_id")}" rel="nofollow" target="_blank" title="{$value}">{$value}</a>
							{/if}
						{/if}
					</td>