{include file="backend/admin_header.tpl"}

				<div class="content">
					<h3>{$LANG_MANAGE_COUPONS_TITLE}</h3>
					
					<div class="admin_top_menu_cell" style="width: auto">
						<a href="{$VH->site_url("admin/coupons/create")}" title="{$LANG_CREATE_COUPON_OPTION}"><img src="{$public_path}/images/buttons/page_add.png"></a>
						<a href="{$VH->site_url("admin/coupons/create")}">{$LANG_CREATE_COUPON_OPTION}</a>
					</div>

					{if $coupons|@count}
					<table class="standardTable" border="0" cellpadding="2" cellspacing="2">
					<tr>
						<th>{$LANG_COUPON_CODE}</th>
						<th>{$LANG_COUPON_DESCRIPTION}</th>
						<th>{$LANG_COUPON_USAGE_COUNT_LIMIT_ALL}</th>
						<th>{$LANG_COUPON_USAGE_COUNT_LIMIT_USER}</th>
						<th>{$LANG_COUPON_ALLOWED_GOODS}</th>
						<th>{$LANG_COUPON_VALUE}</th>
						<th width="1">{$LANG_OPTIONS_TH}</th>
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
							{if $coupon->usage_count_limit_all > 0}
								{$coupon->usage_count_limit_all}
							{else}
								<span class="green">{$LANG_UNLIMITED}</span>
							{/if}
						</td>
						<td>
							{if $coupon->usage_count_limit_user > 0}
								{$coupon->usage_count_limit_user}
							{else}
								<span class="green">{$LANG_UNLIMITED}</span>
							{/if}
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
						<td>
							<nobr>
								<a href="{$VH->site_url("admin/coupons/edit/$coupon_id")}" title="{$LANG_EDIT_COUPON_OPTION}"><img src="{$public_path}images/buttons/page_edit.png"></a>
								<a href="{$VH->site_url("admin/coupons/delete/$coupon_id")}" title="{$LANG_DELETE_COUPON_OPTION}"><img src="{$public_path}images/buttons/page_delete.png"></a>
							</nobr>
						</td>
					</tr>
					{/foreach}
					</table>
					{/if}
				</div>

{include file="backend/admin_footer.tpl"}