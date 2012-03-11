{include file="backend/admin_header.tpl"}
{assign var="listing_id" value=$listing->id}
{assign var="listing_owner_id" value=$listing->owner_id}

				<script language="javascript" type="text/javascript">
				$(function () {ldelim}
					var plot = $.plot($("#my_chart"),
				          [ {ldelim} data: {$flot_data_views}, label: '{addslashes string=$LANG_LISTINGS_STATISTICS_PAGES}' {rdelim} ],
				          {ldelim}
				              series: {ldelim}
				                  lines: {ldelim} show: true {rdelim},
				                  points: {ldelim} show: true {rdelim}
				              {rdelim},
				              grid: {ldelim} hoverable: true, clickable: true {rdelim},
				              xaxis: {ldelim}
								mode: "time",
								min: (new Date("{$js_from_date}")).getTime()+21600000,
                				max: (new Date("{$js_to_date}")).getTime()+21600000
								{rdelim}
				           {rdelim});
	
				    function showTooltip(x, y, contents) {ldelim}
				        $('<div id="tooltip">' + contents + '<' + '/div>').css( {ldelim}
				            position: 'absolute',
				            display: 'none',
				            top: y - 5,
				            left: x + 10,
				            border: '1px solid #fdd',
				            padding: '2px',
				            'background-color': '#fee',
				            opacity: 0.80
				        {rdelim}).appendTo("body").fadeIn(200);
				    {rdelim}
				    
				    var previousPoint = null;
				    $("#my_chart").bind("plothover", function (event, pos, item) {ldelim}
						$("#x").text(pos.x.toFixed(2));
						$("#y").text(pos.y.toFixed(2));

						if (item) {ldelim}
							if (previousPoint != item.datapoint) {ldelim}
								previousPoint = item.datapoint;
						
								$("#tooltip").remove();
								var x = new Date(item.datapoint[0]).toDateString(),
								y = item.datapoint[1];

								showTooltip(item.pageX, item.pageY, item.series.label + " on " + x + ": <b>" + y + "</b>");
							{rdelim}
						{rdelim} else {ldelim}
							$("#tooltip").remove();
							previousPoint = null;            
						{rdelim}
					{rdelim});

			    {rdelim});
			    </script>

                <div class="content">
                    <h3>{$LANG_LISTINGS_STATISTICS} "{$listing->title()}"</h3>

                    {include file="listings/admin_listing_options_menu.tpl"}

                    {$LANG_LISTINGS_STATISTICS_PAGES}: {$pageviews_count}
                    <div class="px10"></div>

                    <div id="my_chart" style="width:650px; height:300px">{if $dates|@count == 0}{$LANG_LISTINGS_NO_STATISTICS}{/if}</div>
                    
                    <div class="px10"></div>
                    
                    {foreach from=$periods_to_select key=period item=href}
                    <a href="{$href}">{$period}</a>&nbsp;&nbsp;&nbsp;
                    {/foreach}
                </div>

{include file="backend/admin_footer.tpl"}