{if $items_array|@count}
	<div class="sidebar_block similar_listings">
		<h1>Upcoming events</h1>
		{foreach from=$items_array item=listing}
			<div class="block_item">
				{if $listing->level->logo_enabled && $listing->logo_file}
				<div class="block_item_img">
					<a title="{$listing->title()}" href="{$VH->site_url($listing->url())}">
						<img src="{$users_content}/users_images/thmbs_cropped/{$listing->logo_file}" alt="{$listing->title()}" />
					</a>
				</div>
				{/if}

				{assign var=event_field value=$listing->content_fields->getField(event_dates)}
				{assign var=event_field_dates value=$event_field->getSomeNearestDates($listing->id, "listing_level")}
				<script language="JavaScript" type="text/javascript">
					var monthNames = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
					var dates_array_{$listing->id} = {$VH->json_encode($event_field_dates)};
					var curr_date = new Date();
					curr_date.setUTCFullYear(curr_date.getFullYear().toString(), curr_date.getMonth().toString(), curr_date.getDate().toString());
					curr_date.setUTCHours(0, 0, 0, 0);
					for (var i=0; i<dates_array_{$listing->id}.length; i++) {ldelim}
						var select_date_{$listing->id} = new Date();
						str = dates_array_{$listing->id}[i].split('-');
						select_date_{$listing->id}.setUTCFullYear(str[0], str[1]-1, str[2]);
						select_date_{$listing->id}.setUTCHours(0, 0, 0, 0);
						if (select_date_{$listing->id}.getTime() >= curr_date.getTime()) {ldelim}
							break;
						{rdelim}
					{rdelim}
				</script>
				{if $event_field_dates|@count > 0}
				<div class="event_date">
					<div class="event_month"><script>document.write(monthNames[select_date_{$listing->id}.getMonth()]);</script></div>
					<div class="event_day"><script>document.write(select_date_{$listing->id}.getDate());</script></div>
				</div>
				{else}
				<div class="event_date" style="color:red">
					Expired
				</div>
				{/if}
				
				<div>
					<a title="{$listing->title()}" href="{$VH->site_url($listing->url())}">{$listing->title()}</a>
				</div>
				<div class="clear_float"></div>
			</div>
		{/foreach}
	</div>
{/if}