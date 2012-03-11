					<td class="content_field_output">
						{if $checkboxes|@count != 0}
							{if $checkboxes|@count == 1}
								{$checkboxes.0}
							{else}
							<br/>
							<ul class="checkboxes_ul">
							{foreach from=$checkboxes item=checkbox}
								<li>{$checkbox}</li>
							{/foreach}
							</ul>
							{/if}
						{/if}
					</td>