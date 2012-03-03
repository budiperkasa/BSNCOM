<script language="JavaScript" type="text/javascript">
	$(document).ready(function() {ldelim}
		$(".location_dropdown_list").bind("change", function() {ldelim}
			curr_order_num = $(this).attr('order_num');
			next_order_num = parseFloat(curr_order_num) + 1;
			if (curr_order_num != {$location_levels|@count} && this.options[this.selectedIndex].value != '') {ldelim}
				$(this).parent().parent().find(".location_dropdown_list").attr('disabled', 'disabled');
				$(this).parent().parent().find(".location_dropdown_list").css('background', 'url({$public_path}images/ajax-indicator.gif) no-repeat 50% 50%');
				for (i = next_order_num; i <= {$location_levels|@count}; i++)
					$(this).parent().parent().find(".loc_level_"+i+"_{$virtual_id}").html('<option value="" selected>- - - {$LANG_LOCATION_SELECT} ' + $(this).parent().parent().find(".loc_level_"+i+"_{$virtual_id}").attr("level_name") + ' - - -</option>');
				$(this).parent().parent().find(".loc_level_"+next_order_num+"_{$virtual_id}").load("{$VH->site_url('ajax/locations/build_drop_box')}", {ldelim}parent_id: this.options[this.selectedIndex].value, for_level: $(this).parent().parent().find(".loc_level_"+next_order_num+"_{$virtual_id}").attr("level_name"){rdelim},
				function(){ldelim}
					$(this).parent().parent().find(".location_dropdown_list").css('background','');
					$(this).parent().parent().find(".location_dropdown_list").attr('disabled', '');
				{rdelim});
			{rdelim}
		{rdelim});
	{rdelim});
</script>

<div>
{foreach from=$location_levels item=loc_level key=key}
{assign var=order_num value=$loc_level->order_num}
	<div class="location_level">
		<select class="location_dropdown_list loc_level_{$loc_level->order_num}_{$virtual_id}" order_num="{$loc_level->order_num}" level_name="{$loc_level->name}" name="loc_level_{$loc_level->order_num}[]" style="width: 300px;">
			<option value="">- - - {$LANG_LOCATION_SELECT} {$loc_level->name} - - -</option>
			{foreach from=$locations_by_level[$order_num] item=location}
				<option value="{$location->id}" {if $location->selected}selected{/if}>{$location->name}</option>
			{/foreach}
		</select>
	</div>
{/foreach}
</div>