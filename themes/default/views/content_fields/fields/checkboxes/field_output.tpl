					{if $checkboxes|@count != 0}
					<div class="content_field_output">
						<strong>{if $field->frontend_name}{$field->frontend_name}{else}{$field->name}{/if}</strong>:
						<ul class="checkboxes_ul">
						{foreach from=$checkboxes item=checkbox}
							<li>{$checkbox.option_name}</li>
						{/foreach}
						</ul>
					</div>
					{/if}