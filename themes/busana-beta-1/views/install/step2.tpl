{include file="backend/admin_header.tpl"}

			<div class="content">
				{$VH->validation_errors()}
				<h3>{$LANG_INSTALL_STEP2_TITLE}</h3>
				<form action="" method="post">
				{$LANG_INSTALL_WEBSITE_TITLE}<span class="red_asterisk">*</span><br>
				<input type="text" name="website_title" value="{$CI->form_validation->set_value('website_title')}" size="40"><br><br>
				{$LANG_INSTALL_WEBSITE_EMAIL}<span class="red_asterisk">*</span><br>
				<input type="text" name="website_email" value="{$CI->form_validation->set_value('website_email')}" size="40"><br><br>
				<br/>
				<br/>
				<input type="submit" name="submit" value="{$LANG_INSTALL_BUTTON}" class="button save_button">
				</form>
			</div>
           
{include file="backend/admin_footer.tpl"}