{if $error_msgs != ''}
				<div class="error_msgs">
					<ul>
					{foreach from=$error_msgs item=error_item}
						<li>{$error_item}</li>
					{/foreach}
					</ul>
				</div>
{/if}