{if $success_msgs!=''}
				<div class="success_msgs">
					<ul>
					{foreach from=$success_msgs item=success_item}
						<li>{$success_item}</li>
					{/foreach}
					</ul>
				</div>
{/if}