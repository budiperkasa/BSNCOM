			<div class="admin_option_name">
				{$field->name}{if $field->required}<span class="red_asterisk">*</span>{/if}
			</div>
			<div class="admin_option_description">
				{$field->description|nl2br}
			</div>
			<select name="field_currency_{$field->seo_name}" style="min-width: 110px;">
				{if $options|@count>1}
				<option value="-1">{$LANG_SELECT_CURRENCY}</option>
				{/if}

				{foreach from=$options item=option}
				<option value="{$option.id}" {if $option.id == $field_currency}selected{/if}>{$option.option_name}</option>
				{/foreach}
			</select>
			&nbsp;&nbsp;
			<input type="text" name="field_value_{$field->seo_name}" value="{$field_value}" size="5">
			<br />
			<br />