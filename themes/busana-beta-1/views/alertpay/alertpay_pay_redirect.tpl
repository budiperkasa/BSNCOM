{include file="backend/admin_header.tpl"}

				<script language="javascript" type="text/javascript">
					window.onload = function() {ldelim}
						$(".content").oneTime("5s", function() {ldelim}
							$("#alertpay_form").submit();
						{rdelim});
					{rdelim}
				</script>

                <div class="content">
                     <h3>{$LANG_PROCESSING_PAYMENT}</h3>
                     <h4>{$LANG_PROCESSING_PAYMENT_DESCR}</h4>
                     
                     <form action="{$alertpay->gatewayUrl}" method="post" name="alertpay_form" id="alertpay_form">
                     {foreach from=$alertpay->fields item=value key=name}
                     	<input type="hidden" name="{$name}" value="{$value}" />
                     {/foreach}

                     {$LANG_ALERTPAY_REDIRECT_MANUAL}<br/><br/>
                     <input type="submit" value="{$LANG_CLICK_HERE}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}