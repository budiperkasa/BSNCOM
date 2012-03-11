{include file="backend/admin_header.tpl"}

				<div class="content">
					{$VH->validation_errors()}
					<h3>{$LANG_2CHECKOUT_SETTINGS}</h3>

					<form action="" method="post">
					<div class="admin_option">
						<div class="admin_option_name">
							{$LANG_2CHECKOUT_SID}<span class="red_asterisk">*</span>
						</div>
						<input type="text" name="2checkout_sid" value="{$2checkout_sid}" size="45">
					</div>
					<div class="admin_option">
						<div class="admin_option_name">
							{$LANG_2CHECKOUT_SWORD}<span class="red_asterisk">*</span>
						</div>
						<div class="admin_option_description">
							{$LANG_2CHECKOUT_SWORD_DESCR}
						</div>
						<input type="text" name="2checkout_secret_word" value="{$2checkout_secret_word}" size="45">
					</div>
					<input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
					</form>
				</div>

{include file="backend/admin_footer.tpl"}