{include file="backend/admin_header.tpl"}

<script language="Javascript" type="text/javascript">
$(document).ready(function() {ldelim}
	$("input:text:visible:first").focus();
{rdelim});
</script>

		<div class="content">
			{$VH->validation_errors()}
			<div id="login_form">
				<form action="" method="post">
				{$LANG_LOGIN_EMAIL}:<br>
				<input type="text" name="email" value="{$CI->validation->email}" class="login_input" size="45"><br>
				<div class="px5"></div>
				{$LANG_LOGIN_PASSWORD}:<br>
				<input type="password" name="password" class="login_input" size="45">
				<div class="px5"></div>
				<input type="checkbox" name="remember_me"> {$LANG_REMEMBER_ME}
				<div class="px10"></div>
				<input type="submit" name="submit" value="{$LANG_BUTTON_LOGIN}" class="front-btn">
				</form>
				<div class="px5"></div>
				<div class="login_block_link">{$VH->anchor('register', $LANG_CREATE_ACCOUNT)}</div>
				<div class="login_block_link">{$VH->anchor('pass_recovery_step1', $LANG_FORGOT_PASS)}</div>
			</div>
		</div>
           
{include file="backend/admin_footer.tpl"}