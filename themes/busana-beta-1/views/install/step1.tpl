{include file="backend/admin_header.tpl"}

			<div class="content">
				{$VH->validation_errors()}
				<h3>{$LANG_INSTALL_STEP1_TITLE}</h3>
				<h4>{$LANG_INSTALL_STEP1_SUBTITLE}</h4>

				<form action="" method="post">
				{$LANG_INSTALL_USER_LOGIN}<span class="red_asterisk">*</span><br>
				<input type="text" name="user_login" value="{$CI->form_validation->set_value('user_login')}" size="40"><br><br>
				{$LANG_INSTALL_USER_EMAIL}<span class="red_asterisk">*</span><br>
				<input type="text" name="user_email" value="{$CI->form_validation->set_value('user_email')}" size="40"><br><br>
				{$LANG_INSTALL_USER_PASSWORD}<span class="red_asterisk">*</span><br>
				<input type="password" name="user_password" value="{$CI->validation->password}"><br><br>
				{$LANG_INSTALL_USER_PASSWORD_REPEAT}<span class="red_asterisk">*</span><br>
				<input type="password" name="user_password_repeat" value="{$CI->validation->password_repeat}"><br>
				<br/>
				<br/>
				<input type="submit" name="submit" value="{$LANG_INSTALL_BUTTON}" class="button save_button">
				</form>
			</div>
           
{include file="backend/admin_footer.tpl"}