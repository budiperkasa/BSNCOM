					{if $value && $option_id != -1}
					<div class="content_field_output"">
						<strong>{if $field->frontend_name}{$field->frontend_name}{else}{$field->name}{/if}</strong>:
						{if $value == '1 star'}
							<img src="{$public_path}/images/stars/1star.png" title="{$value}" />
						{elseif $value == '2 stars'}
							<img src="{$public_path}/images/stars/2star.png" title="{$value}" />
						{elseif $value == '3 stars'}
							<img src="{$public_path}/images/stars/3star.png" title="{$value}" />
						{elseif $value == '4 stars'}
							<img src="{$public_path}/images/stars/4star.png" title="{$value}" />
						{elseif $value == '5 stars'}
							<img src="{$public_path}/images/stars/5star.png" title="{$value}" />
						{/if}
					</div>
					{/if}