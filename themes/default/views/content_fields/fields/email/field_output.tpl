					{if $field->value != ''}
					<div class="content_field_output">
						{if $field->frontend_name}<strong>{$field->frontend_name}</strong>:{/if}
						<script type="text/javascript">
							Email='{$part1}'+'{$part2}'; 
							document.write('<a href="mailto: '+Email+'">'+Email+'<'+'/a>');
						</script>
					</div>
					{/if}