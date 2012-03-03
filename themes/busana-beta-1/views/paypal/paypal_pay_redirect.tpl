{include file="backend/admin_header.tpl"}

				<script language="javascript" type="text/javascript">
					window.onload = function() {ldelim}
						$(".content").oneTime("5s", function() {ldelim}
							$("#paypal_form").submit();
						{rdelim});
					{rdelim}
				</script>

                <div class="content">
                     <h3>{$LANG_PROCESSING_PAYMENT}</h3>
                     <h4>{$LANG_PROCESSING_PAYMENT_DESCR}</h4>
                     
                     <form action="{$paypal->gatewayUrl}" method="post" name="paypal_form" id="paypal_form">
                     {foreach from=$paypal->fields item=value key=name}
                     	<input type="hidden" name="{$name}" value="{$value}" />
                     {/foreach}

                     {$LANG_PAYPAL_REDIRECT_MANUAL}<br/><br/>
                     <input type="submit" value="{$LANG_CLICK_HERE}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}