			<div class="admin_option_name">
				{$field->name}{if $field->required}<span class="red_asterisk">*</span>{/if}
			</div>
			<div class="admin_option_description">
				{$field->description|nl2br}
			</div>
			<select name="field_{$field->seo_name}" style="min-width: 200px;">
				<option value="-1">Select item</option>
				{foreach from=$options item=option}
				<option value="{$option.id}" {if $option.id == $field->value}selected{/if}>{$option.option_name}</option>
				{/foreach}
			</select>
			<br />
			<br />