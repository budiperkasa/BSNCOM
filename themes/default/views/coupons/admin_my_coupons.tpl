{include file="backend/admin_header.tpl"}

				<div class="content">
					<h3>{$LANG_VIEW_MY_COUPONS}</h3>

					{if $coupons|@count}
					<table class="standardTable" border="0" cellpadding="2" cellspacing="2">
					<tr>
						<th>{$LANG_COUPON_CODE}</th>
						<th>{$LANG_COUPON_DESCRIPTION}</th>
						<th>{$LANG_COUPON_IS_ACTIVE}</th>
						<th>{$LANG_COUPON_ALLOWED_GOODS}</th>
						<th>{$LANG_COUPON_VALUE}</th>
					</tr>
					{foreach from=$coupons item=coupon}
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
					
					{if $coupons_usage|@count}
					<div class="px10"></div>

					<h3>{$LANG_COUPONS_USAGE_TITLE}</h3>

					<table class="standardTable" border="0" cellpadding="2" cellspacing="2">
					<tr>
						<th>{$LANG_COUPON_CODE}</th>
						<th>{$LANG_PAYMENT_INVOICE_ID}</th>
						<th>{$LANG_PAYMENT_GOODS_TITLE}</th>
						<th>{$LANG_COUPON_PROVIDED_DISCOUNT}</th>
						<th>{$LANG_COUPON_USAGE_DATE}</th>
					</tr>
					{foreach from=$coupons_usage item=usage}
					<tr>
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
					{/if}
				</div>

{include file="backend/admin_footer.tpl"}