{include file="backend/admin_header.tpl"}

                <div class="content">
                	{$VH->validation_errors()}
                     <h3>{$LANG_CREATE_TRANSACTIONS_TITLE}</h3>

                     <form action="" method="post">
					 <div class="admin_option">
					 	<div class="admin_option_name">
					 		{$LANG_PAYMENT_INVOICE_ID}<span class="red_asterisk">*</span>
					 	</div>
					 	<input type="text" name="invoice_id" value="{$invoice_id}" size="3" />
					 </div>

                     <input type=submit name="submit" class="button enter_button" value="{$LANG_CREATE_TRANSACTION}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}