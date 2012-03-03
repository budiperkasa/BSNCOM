{include file="backend/admin_header.tpl"}
{assign var="coupon_id" value=$coupon->id}

<script language="JavaScript" type="text/javascript">
$(document).ready(function() {ldelim}
	$("input[name=discount_type]").click( function() {ldelim}
		if ($('input[name="discount_type"]:checked').val() == 0) {ldelim}
			$('#percent_value').show();
			$('#exact_value').hide();
		{rdelim} else {ldelim}
			$('#percent_value').hide();
			$('#exact_value').show();
		{rdelim}
	{rdelim});
	
	$("#effective_date").datepicker({ldelim}
		showOn: "both",
		buttonImage: "{$public_path}images/calendar.png",
		buttonImageOnly: true,
		showButtonPanel: true,
		closeText: 'Clear',
		onSelect: function(dateText) {ldelim}
			if (dateText != '') {ldelim}
				var sDate = $("#effective_date").datepicker("getDate");
				if (sDate) {ldelim}
					sDate.setMinutes(sDate.getMinutes() - sDate.getTimezoneOffset());
					tmstmp_str = $.datepicker.formatDate('@', sDate)/1000;
				{rdelim} else 
					tmstmp_str = 0;

				$("#effective_date_tmstmp").val(tmstmp_str);
				$("#expiration_date").datepicker('option', 'minDate', $("#effective_date").datepicker('getDate'));
				if ($("#effective_date").datepicker('getDate') > $("#expiration_date").datepicker('getDate')) {ldelim}
					$("#expiration_date").val('');
				{rdelim}
			{rdelim} else {ldelim}
				$("#effective_date_tmstmp").val('');
				$("#expiration_date").datepicker('option', 'minDate', null);
			{rdelim}
		{rdelim}
	{rdelim});
	$("#effective_date").datepicker("option", $.datepicker.regional["{$current_language}"]);

	$("#expiration_date").datepicker({ldelim}
		showOn: "both",
		buttonImage: "{$public_path}images/calendar.png",
		buttonImageOnly: true,
		showButtonPanel: true,
		closeText: 'Clear',
		onSelect: function(dateText) {ldelim}
			if (dateText != '') {ldelim}
				var sDate = $("#expiration_date").datepicker("getDate");
				if (sDate) {ldelim}
					sDate.setMinutes(sDate.getMinutes() - sDate.getTimezoneOffset());
					tmstmp_str = $.datepicker.formatDate('@', sDate)/1000;
				{rdelim} else 
					tmstmp_str = 0;

				$("#expiration_date_tmstmp").val(tmstmp_str);
				$("#effective_date").datepicker('option', 'maxDate', $("#expiration_date").datepicker('getDate'));
				if ($("#effective_date").datepicker('getDate') > $("#expiration_date").datepicker('getDate')) {ldelim}
					$("#effective_date").val('');
				{rdelim}
			{rdelim} else {ldelim}
				$("#expiration_date_tmstmp").val('');
				$("#effective_date").datepicker('option', 'maxDate', null);
			{rdelim}
		{rdelim}
	{rdelim});
	$("#expiration_date").datepicker("option", $.datepicker.regional["{$current_language}"]);

	{if $coupon->effective_date != '' && $coupon->effective_date != '0000-00-00' && $coupon->effective_date != '1970-01-01'}
		$("#effective_date").datepicker('setDate', $.datepicker.parseDate('yy-mm-dd', '{$coupon->effective_date}'));
		$("#expiration_date").datepicker('option', 'minDate', $("#effective_date").datepicker('getDate'));
	{/if}
	{if $coupon->expiration_date != '' && $coupon->expiration_date != '9999-12-31'}
		$("#expiration_date").datepicker('setDate', $.datepicker.parseDate('yy-mm-dd', '{$coupon->expiration_date}'));
		$("#effective_date").datepicker('option', 'maxDate', $("#expiration_date").datepicker('getDate'));
	{/if}
{rdelim});
</script>

				<div class="content">
					{$VH->validation_errors()}

					<h3>{if $coupon_id != 'new'}{$LANG_EDIT_COUPON_TITLE} "{$coupon->code}"{else}{$LANG_CREATE_COUPON_TITLE}{/if}</h3>
					{if $coupon_id !='new' }
					<div class="admin_top_menu_cell">
						<a href="{$VH->site_url("admin/coupons/create")}" title="{$LANG_CREATE_COUPON_OPTION}"><img src="{$public_path}/images/buttons/page_add.png"></a>
						<a href="{$VH->site_url("admin/coupons/create")}">{$LANG_CREATE_COUPON_OPTION}</a>
					</div>
					<div class="admin_top_menu_cell">
						<a href="{$VH->site_url("admin/coupons/delete/$package_id")}" title="{$LANG_DELETE_COUPON_OPTION}"><img src="{$public_path}/images/buttons/page_delete.png"></a>
						<a href="{$VH->site_url("admin/coupons/delete/$package_id")}">{$LANG_DELETE_COUPON_OPTION}</a>
					</div>
					<div class="clear_float"></div>
					{/if}

					<form action="" method="post">
					<input type=hidden name="id" value="{$coupon_id}">
					<div class="admin_option">
                     	<div class="admin_option_name" >
                     		{$LANG_COUPON_CODE}<span class="red_asterisk">*</span>
						</div>
						<div class="admin_option_description" >
                     		{$LANG_COUPON_CODE_DESCR}
						</div>
						<input type="text" name="code" value="{$coupon->code}" size="40" />
					</div>
					<div class="admin_option">
                     	<div class="admin_option_name" >
                     		{$LANG_COUPON_DESCRIPTION}
                          	{translate_content table='discount_coupons' field='description' row_id=$coupon_id}
						</div>
						<textarea name="description">{$coupon->description}</textarea>
					</div>
					<div class="admin_option">
                     	<div class="admin_option_name" >
                     		{$LANG_COUPON_ALLOWED_GOODS}<span class="red_asterisk">*</span>
						</div>
						<label><input type="checkbox" name="allowed_goods[]" value="listings" {if $VH->in_array('listings', $coupon->allowed_goods)}checked{/if} /> {$LANG_LISTINGS_GOODS}</label>
						{if $CI->load->is_module_loaded('banners')}
						<label><input type="checkbox" name="allowed_goods[]" value="banners" {if $VH->in_array('banners', $coupon->allowed_goods)}checked{/if} /> {$LANG_BANNERS_GOODS}</label>
						{/if}
						{if $CI->load->is_module_loaded('packages')}
						<label><input type="checkbox" name="allowed_goods[]" value="packages" {if $VH->in_array('packages', $coupon->allowed_goods)}checked{/if} /> {$LANG_PACKAGES_GOODS}</label>
						{/if}
					</div>
					<div class="admin_option">
                     	<div class="admin_option_name" >
                     		{$LANG_COUPON_TYPE}<span class="red_asterisk">*</span>
						</div>
						<label><input type="radio" name="discount_type" value="0" {if $coupon->discount_type == 0}checked{/if} /> {$LANG_COUPON_TYPE_PERCENTS}</label>
						<label><input type="radio" name="discount_type" value="1" {if $coupon->discount_type == 1}checked{/if} /> {$LANG_COUPON_TYPE_EXACT_VALUE}</label>
					</div>
					<div class="admin_option">
                     	<div class="admin_option_name" >
                     		{$LANG_COUPON_VALUE}<span class="red_asterisk">*</span>
						</div>
						
						<div id="percent_value" {if $coupon->discount_type != 0}style="display: none"{/if}>
							<input type="text" name="percents_value" value="{$coupon->value}" size="1" maxlength="5" /> %
						</div>
						<div id="exact_value" {if $coupon->discount_type != 1}style="display: none"{/if}>
							<select name="exact_value_currency" style="min-width: 40px;">
                     		{foreach from=$currencies item=currency}
                     			<option value="{$currency}" {if $coupon->currency == $currency}selected{/if}>{$currency}</option>
                     		{/foreach}
                     		</select>&nbsp;
                     		<input type="text" name="exact_value" value="{$coupon->value}" size="1" />
						</div>
					</div>
					<div class="admin_option">
                     	<div class="admin_option_name" >
                     		{$LANG_COUPON_ACTIVE_PERIOD}
						</div>
						{$LANG_COUPON_EFFECTIVE_DATE}:&nbsp;
						<input type="text" size="10" value="" name="effective_date" id="effective_date"/>&nbsp;&nbsp;
						<input type="hidden" id="effective_date_tmstmp" name="effective_date_tmstmp" value="{$coupon->effective_date_tmstmp}">

						{$LANG_COUPON_EXPIRATION_DATE}:&nbsp;
						<input type="text" size="10" value="" name="expiration_date" id="expiration_date"/>&nbsp;&nbsp;
						<input type="hidden" id="expiration_date_tmstmp" name="expiration_date_tmstmp" value="{$coupon->expiration_date_tmstmp}">
					</div>
					<div class="admin_option">
                     	<div class="admin_option_name" >
                     		{$LANG_COUPON_USAGE_COUNT_LIMIT_ALL}<span class="red_asterisk">*</span>
						</div>
						<div class="admin_option_description" >
                     		{$LANG_ZERO_FOR_UNLIMITED}
						</div>
						<input type="text" size="2" value="{$coupon->usage_count_limit_all}" name="usage_count_limit_all" />
					</div>
					<div class="admin_option">
                     	<div class="admin_option_name" >
                     		{$LANG_COUPON_USAGE_COUNT_LIMIT_USER}<span class="red_asterisk">*</span>
						</div>
						<div class="admin_option_description" >
                     		{$LANG_ZERO_FOR_UNLIMITED}
						</div>
						<input type="text" size="2" value="{$coupon->usage_count_limit_user}" name="usage_count_limit_user" />
					</div>
					<div class="admin_option">
                     	<div class="admin_option_name" >
                     		{$LANG_COUPON_USE_IF_ASSIGNED}<span class="red_asterisk">*</span>
						</div>
						<label><input type="radio" name="use_if_assigned" value="0" {if $coupon->use_if_assigned == 0}checked{/if} /> {$LANG_COUPON_USE_IF_ASSIGNED_FALSE}</label>
						<label><input type="radio" name="use_if_assigned" value="1" {if $coupon->use_if_assigned == 1}checked{/if} /> {$LANG_COUPON_USE_IF_ASSIGNED_TRUE}</label>
					</div>
					<div class="admin_option">
                     	<div class="admin_option_name" >
                     		{$LANG_COUPON_ASSIGN_EVENTS}<span class="red_asterisk">*</span>
						</div>
						<div class="admin_option_description" >
                     		{$LANG_COUPON_ASSIGN_EVENTS_DESCR}
						</div>
						<label><input type="checkbox" name="assign_events[]" value="first_listing_creation" {if $VH->in_array('first_listing_creation', $coupon->assign_events)}checked{/if} /> {$LANG_COUPON_ASSIGN_EVENT_FIRST_LISTING}</label>
						<label><input type="checkbox" name="assign_events[]" value="any_listing_creation" {if $VH->in_array('any_listing_creation', $coupon->assign_events)}checked{/if} /> {$LANG_COUPON_ASSIGN_EVENT_ANY_LISTING}</label>
						{if $CI->load->is_module_loaded('banners')}
						<label><input type="checkbox" name="assign_events[]" value="first_banner_creation" {if $VH->in_array('first_banner_creation', $coupon->assign_events)}checked{/if} /> {$LANG_COUPON_ASSIGN_EVENT_FIRST_BANNER}</label>
						<label><input type="checkbox" name="assign_events[]" value="any_banner_creation" {if $VH->in_array('any_banner_creation', $coupon->assign_events)}checked{/if} /> {$LANG_COUPON_ASSIGN_EVENT_ANY_BANNER}</label>
						{/if}
						<label><input type="checkbox" name="assign_events[]" value="first_transaction" {if $VH->in_array('first_transaction', $coupon->assign_events)}checked{/if} /> {$LANG_COUPON_ASSIGN_EVENT_FIRST_TRANSACTION}</label>
						<label><input type="checkbox" name="assign_events[]" value="any_transaction" {if $VH->in_array('any_transaction', $coupon->assign_events)}checked{/if} /> {$LANG_COUPON_ASSIGN_EVENT_ANY_TRANSACTION}</label>
						<label><input type="checkbox" name="assign_events[]" value="registration" {if $VH->in_array('registration', $coupon->assign_events)}checked{/if} /> {$LANG_COUPON_ASSIGN_EVENT_REGISTRATION}</label>
						<label><input type="checkbox" name="assign_events[]" value="custom_users" {if $VH->in_array('custom_users', $coupon->assign_events)}checked{/if} /> {$LANG_COUPON_ASSIGN_EVENT_CUSTOM_USERS}</label>
					</div>

                    <input class="button save_button" type=submit name="submit" value="{if $coupon_id != 'new'}{$LANG_BUTTON_SAVE_CHANGES}{else}{$LANG_BUTTON_CREATE_COUPON}{/if}">
                    </form>
                </div>

{include file="backend/admin_footer.tpl"}