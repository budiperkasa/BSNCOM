			<div class="admin_option_name">
				{$field->name}{if $field->required}<span class="red_asterisk">*</span>{/if}
			</div>
			<div class="admin_option_description">
				{$field->description|nl2br}
			</div>
			<input type="text" name="field_{$field->seo_name}" value="{$value}" size="60" class="admin_option_input">
			<br />
			<br />