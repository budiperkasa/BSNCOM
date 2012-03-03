{include file="backend/admin_header.tpl"}

				<div class="content">
					{$VH->validation_errors()}
					<h3>{$LANG_ALERTPAY_SETTINGS}</h3>

					<form action="" method="post">
					<div class="admin_option">
						<div class="admin_option_name">
							{$LANG_ALERTPAY_EMAIL}<span class="red_asterisk">*</span>
						</div>
						<input type="text" name="alertpay_email" value="{$alertpay_email}" size="50">
					</div>
					<div class="admin_option">
						<div class="admin_option_name">
							{$LANG_ALERTPAY_SECURITYCODE}<span class="red_asterisk">*</span>
						</div>
						<div class="admin_option_description">
							{$LANG_ALERTPAY_SECURITYCODE_DESCR}
						</div>
						<input type="text" name="alertpay_securitycode" value="{$alertpay_securitycode}" size="50">
					</div>
					<input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
					</form>
				</div>

{include file="backend/admin_footer.tpl"}