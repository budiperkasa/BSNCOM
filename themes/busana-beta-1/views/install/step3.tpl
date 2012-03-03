{include file="backend/admin_header.tpl"}

			<div class="content">
				{$VH->validation_errors()}
				<h3>{$LANG_INSTALL_STEP3_TITLE}</h3>
				<h4>{$LANG_INSTALL_STEP3_SUBTITLE}</h4>
				<br/>
				<br/>
				<form action="" method="post">
				<input type="submit" name="submit" value="{$LANG_INSTALL_FINISH_BUTTON}" class="button save_button">
				</form>
			</div>
           
{include file="backend/admin_footer.tpl"}