					<td class="content_field_output">
						{if $field->value != ''}
							<script type="text/javascript">
								Email='{$part1}'+'{$part2}'; 
								document.write('<a href="mailto: '+Email+'">'+Email+'<'+'/a>');
							</script>
						{/if}
					</td>