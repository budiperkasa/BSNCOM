{include file="backend/admin_header.tpl"}

				<script language="javascript" type="text/javascript">
					var global_js_url = '{$base_url}';

					{assign var=random_id value=$VH->genRandomString()}
					var {$random_id}_mode = '{$mode}';

					$(document).ready( function() {ldelim}    	
				        $('#search_login').autocomplete({ldelim}
							source: function(request, response) {ldelim}
								$.post("{$VH->site_url('admin/users/ajax_autocomplete_request/')}", {ldelim}query: request.term{rdelim}, function(data) {ldelim}
									response($.map(data.suggestions, function(item) {ldelim}
										return {ldelim}
											label: item,
											value: item
										{rdelim};
									{rdelim}));
								{rdelim}, "json");
							{rdelim},
							minLength: 2,
							delay: 500,
							select: function(event, ui) {ldelim}
								$(this).val(ui.item.label);
								return false;
							{rdelim}
						{rdelim});

				         $("#search_form").submit( function() {ldelim}
				           	if ({$random_id}_mode == 'single') {ldelim}
					        	if ($("#search_tmstmp_usage_date").val() != '')
									global_js_url = global_js_url + "search_usage_date" + '/' + $("#search_tmstmp_usage_date").val() + '/';
							{rdelim}
							if ({$random_id}_mode == 'range') {ldelim}
								if ($("#from_tmstmp_usage_date").val() != '')
									global_js_url = global_js_url + "search_from_usage_date" + '/' + $("#from_tmstmp_usage_date").val() + '/';
								if ($("#to_tmstmp_usage_date").val() != '')
									global_js_url = global_js_url + "search_to_usage_date" + '/' + $("#to_tmstmp_usage_date").val() + '/';
							{rdelim}

							if ($("#search_login").val() != '')
		                		global_js_url = global_js_url + 'search_login/' + $("#search_login").val() + '/';
		                	if ($("#search_coupon").val() != -1)
		                		global_js_url = global_js_url + 'search_coupon/' + $("#search_coupon").val() + '/';

							window.location.href = global_js_url;
							return false;
						{rdelim});
					{rdelim});
                </script>

                <div class="content">
                     <h3>{$LANG_SEARCH_COUPONS_USAGE_TITLE}</h3>
                     
                     <form id="search_form" action="" method="post">
                     	<div class="search_block">
                     		<div class="search_item">
	                     		<label>{$LANG_SEARCH_BY_COUPON}:</label>
	                     		<select id="search_coupon">
	                     			<option value="-1">- - - {$LANG_SEARCH_BY_ANY_COUPON} - - -</option>
	                     			{foreach from=$all_coupons item=coupon}
	                     			<option value="{$coupon->id}" {if $args.search_coupon == $coupon->id}selected{/if}>{$coupon->code}</option>
	                     			{/foreach}
	                     		</select>
	                     	</div>
	                     	<div class="search_item">
	                     		<label>{$LANG_SEARCH_LOGIN}:</label>
	                     		<input type="text" id="search_login" value="{$args.search_login}" style="width: 205px;" />
	                     	</div>

	                     	{assign var=field_title value=$LANG_COUPONS_SEARCH_USAGE_DATE}
	                     	{assign var=search_mode value=$mode}
	                     	{assign var=date_var_name value="usage_date"}
	                     	{assign var=single_date_var_value value=$usage_date}
	                     	{assign var=single_date_var_value_tmstmp value=$usage_date_tmstmp}
	                     	{assign var=from_date_var_value value=$from_usage_date}
	                     	{assign var=from_date_var_value_tmstmp value=$from_usage_date_tmstmp}
	                     	{assign var=to_date_var_value value=$to_usage_date}
	                     	{assign var=to_date_var_value_tmstmp value=$to_usage_date_tmstmp}
	                     	{include file="content_fields/common_date_range_search.tpl"}
                     	</div>
                     	<div class="clear_float"></div>
                     	<div class="search_item_button">
	                    	<input type="submit" class="button search_button" id="process_search" value="{$LANG_BUTTON_SEARCH_COUPONS}">
	                    </div>
                     </form>
                     
                     <div class="search_results_title">
                     	{$LANG_SEARCH_COUPONS_RESULT_1} ({$usages_count} {$LANG_SEARCH_COUPONS_RESULT_2}):
                     </div>

					{if $coupons_usage|@count}
					<table class="standardTable" border="0" cellpadding="2" cellspacing="2">
					<tr>
						<th>{$LANG_OWNER_TH}</th>
						<th>{$LANG_COUPON_CODE}</th>
						<th>{$LANG_PAYMENT_INVOICE_ID}</th>
						<th>{$LANG_PAYMENT_GOODS_TITLE}</th>
						<th>{$LANG_COUPON_PROVIDED_DISCOUNT}</th>
						<th>{asc_desc_insert base_url=$search_url orderby='dcu.usage_date' orderby_query=$orderby direction=$direction title=$LANG_COUPON_USAGE_DATE}</th>
					</tr>
					{foreach from=$coupons_usage item=usage}
					<tr>
						<td>
							{$usage->user->login}
						</td>
						<td>
							<nobr>{$usage->coupon->code}</nobr>
						</td>
						<td>
							{$usage->invoice->id}
						</td>
						<td>
							{$usage->invoice->goods_title}
						</td>
						<td>
							{$usage->invoice->currency} {$usage->provided_discount}
						</td>
						<td>
							{$usage->usage_date|date_format:"%D %T"}
						</td>
					</tr>
					{/foreach}
					</table>
                    {$paginator}
                    {/if}
                    

					{if $coupons_by_user|@count}
					<div class="px10"></div>
					<div class="px10"></div>
					<div class="px10"></div>

					<h3>{$LANG_VIEW_USER_COUPONS} "{$user->login}"</h3>
					<table class="standardTable" border="0" cellpadding="2" cellspacing="2">
					<tr>
						<th>{$LANG_COUPON_CODE}</th>
						<th>{$LANG_COUPON_DESCRIPTION}</th>
						<th>{$LANG_COUPON_IS_ACTIVE}</th>
						<th>{$LANG_COUPON_ALLOWED_GOODS}</th>
						<th>{$LANG_COUPON_VALUE}</th>
					</tr>
					{foreach from=$coupons_by_user item=coupon}
					{assign var=coupon_id value=$coupon->id}
					<tr>
						<td>
							<nobr>{$coupon->code}</nobr>
						</td>
						<td>
							{$coupon->description|nl2br}
						</td>
						<td>
							{if $coupon->active}<img src="{$public_path}images/icons/accept.png" />{else}<img src="{$public_path}images/icons/delete.png" />{/if}
						</td>
						<td>
							{if $VH->in_array('listings', $coupon->allowed_goods)}{$LANG_LISTINGS_GOODS}<br />{/if}
							{if $VH->in_array('banners', $coupon->allowed_goods)}{$LANG_BANNERS_GOODS}<br />{/if}
							{if $VH->in_array('packages', $coupon->allowed_goods)}{$LANG_PACKAGES_GOODS}<br />{/if}
						</td>
						<td>
							{if $coupon->discount_type == 0}
							{$coupon->value}%
							{else}
							{$coupon->currency} {$VH->number_format($coupon->value, 2, $decimals_separator, $thousands_separator)}
							{/if}
						</td>
					</tr>
					{/foreach}
					</table>
					{/if}
                </div>

{include file="backend/admin_footer.tpl"}