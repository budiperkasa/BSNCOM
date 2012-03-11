			<div class="admin_option_name">
				{$field->name}{if $field->required}<span class="red_asterisk">*</span>{/if}
			</div>
			<div class="admin_option_description">
				{$field->description|nl2br}
			</div>
			<table cellspacing="3" cellpadding="0">
				<tr>
					{assign var=td_padding_pixels value=50}
					{assign var=i value=0}

					<td style="padding-right: {$td_padding_pixels}px" valign="top">
						{section name=key loop=$options start=0 step=3}
							<nobr>
							<label>
								<input type="checkbox" name="field_{$field->seo_name}_{$i++}" value="{$options[key].id}" {$options[key].checked}>
								{$options[key].option_name}
							</label>
							</nobr>
						{/section}
					</td>
						
					<td style="padding-right: {$td_padding_pixels}px" valign="top">
						{section name=key loop=$options start=1 step=3}
							<nobr>
							<label>
								<input type="checkbox" name="field_{$field->seo_name}_{$i++}" value="{$options[key].id}" {$options[key].checked}>
								{$options[key].option_name}
							</label>
							</nobr>
						{/section}
					</td>
						
					<td style="padding-right: {$td_padding_pixels}px" valign="top">
						{section name=key loop=$options start=2 step=3}
							<nobr>
							<label>
								<input type="checkbox" name="field_{$field->seo_name}_{$i++}" value="{$options[key].id}" {$options[key].checked}>
								{$options[key].option_name}
							</label>
							</nobr>
						{/section}
					</td>
				</tr>
			</table>
			<br />
			<br />