{include file="backend/admin_header.tpl"}

				<script language="javascript" type="text/javascript">
					window.onload = function() {ldelim}
						$(".content").oneTime("3s", function() {ldelim}
							window.location = '{$VH->site_url('admin/payment/invoices/my/')}';
						{rdelim});
					{rdelim}
				</script>

                <div class="content">
                     <h3>{$LANG_INVOICE_SUCCESS}</h3>
                </div>

{include file="backend/admin_footer.tpl"}