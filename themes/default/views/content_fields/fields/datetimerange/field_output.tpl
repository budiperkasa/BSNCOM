					{if $from_value != '' || $to_value != '' || $dates_array|@count || $cycle_days_monday || $cycle_days_tuesday || $cycle_days_wednesday || $cycle_days_thursday || $cycle_days_friday || $cycle_days_saturday || $cycle_days_sunday}
						<div class="content_field_output">
							<strong>{if $field->frontend_name}{$field->frontend_name}{else}{$field->name}{/if}</strong>:
							{if $from_value != '' || $to_value != ''}<br />{/if}
							{if $from_value != ''}<b>{$LANG_FROM_DATERANGE}</b> {$from_value}{/if} {if $to_value != ''}<b>{$LANG_TO_DATERANGE}</b> {$to_value}{/if}
							{if $cycle_days_monday && $cycle_days_tuesday && $cycle_days_wednesday && $cycle_days_friday && $cycle_days_saturday && $cycle_days_sunday}
								<br />{$LANG_EVERY_DAY}
							{else}
								{if $dates_array|@count || $cycle_days_monday || $cycle_days_tuesday || $cycle_days_wednesday || $cycle_days_thursday || $cycle_days_friday || $cycle_days_saturday || $cycle_days_sunday}
									<br />
									{if $cycle_days_monday}{$LANG_EVERY_MONDAY}<br />{/if}
									{if $cycle_days_tuesday}{$LANG_EVERY_TUESDAY}<br />{/if}
									{if $cycle_days_wednesday}{$LANG_EVERY_WEDNESDAY}<br />{/if}
									{if $cycle_days_thursday}{$LANG_EVERY_THURSDAY}<br />{/if}
									{if $cycle_days_friday}{$LANG_EVERY_FRIDAY}<br />{/if}
									{if $cycle_days_saturday}{$LANG_EVERY_SATURDAY}<br />{/if}
									{if $cycle_days_sunday}{$LANG_EVERY_SUNDAY}<br />{/if}
									{if $dates_array|@count && ($cycle_days_monday || $cycle_days_tuesday || $cycle_days_wednesday || $cycle_days_thursday || $cycle_days_friday || $cycle_days_saturday || $cycle_days_sunday)}and<br />{/if}
									{foreach from=$dates_array item=date}
										{$VH->date($format, $VH->strtotime($date))}<br />
									{/foreach}
								{/if}
							{/if}
						</div>
					{/if}