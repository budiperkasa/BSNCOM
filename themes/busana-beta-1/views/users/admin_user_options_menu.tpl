{assign var=user_id value=$user->id}
{assign var=user_login value=$user->login}

				<div class="admin_top_menu_cell">
					<a href="{$VH->site_url("admin/users/create")}" title="{$LANG_CREATE_USER_OPTION}"><img src="{$public_path}/images/buttons/user_add.png" /></a>
					<a href="{$VH->site_url("admin/users/create")}">{$LANG_CREATE_USER_OPTION}</a>
				</div>

				<div class="admin_top_menu_cell">
					<a href="{$VH->site_url("admin/listings/search/use_advanced/true/search_owner/$user_login/")}" title="{$LANG_USER_LISTINGS_OPTION}"><img src="{$public_path}/images/buttons/page_copy.png" /></a>
                    <a href="{$VH->site_url("admin/listings/search/use_advanced/true/search_owner/$user_login/")}">{$LANG_USER_LISTINGS_OPTION}</a>
				</div>
				
				{if $CI->load->is_module_loaded('banners') && $content_access_obj->isPermission('Manage all banners')}
				<div class="admin_top_menu_cell">
					<a href="{$VH->site_url("admin/banners/search/search_owner/$user_login/")}" title="{$LANG_USER_BANNERS_OPTION}"><img src="{$public_path}/images/buttons/banners.png" /></a>
                    <a href="{$VH->site_url("admin/banners/search/search_owner/$user_login/")}">{$LANG_USER_BANNERS_OPTION}</a>
				</div>
				{/if}
				
				{if $CI->load->is_module_loaded('payment') && $content_access_obj->isPermission('View all invoices')}
				<div class="admin_top_menu_cell">
					<a href="{$VH->site_url("admin/payment/invoices/search/search_owner/$user_login/")}" title="{$LANG_USER_INVOICES_OPTION}"><img src="{$public_path}/images/buttons/invoices.png" /></a>
                    <a href="{$VH->site_url("admin/payment/invoices/search/search_owner/$user_login/")}">{$LANG_USER_INVOICES_OPTION}</a>
				</div>
				{/if}
				
				{if $CI->load->is_module_loaded('payment') && $content_access_obj->isPermission('View all transactions')}
				<div class="admin_top_menu_cell">
					<a href="{$VH->site_url("admin/payment/transactions/search/search_owner/$user_login/")}" title="{$LANG_USER_TRANSACTIONS_OPTION}"><img src="{$public_path}/images/buttons/transactions.png" /></a>
                    <a href="{$VH->site_url("admin/payment/transactions/search/search_owner/$user_login/")}">{$LANG_USER_TRANSACTIONS_OPTION}</a>
				</div>
				{/if}
				
				{if $CI->load->is_module_loaded('packages') && $content_access_obj->isPermission('Manage packages')}
				<div class="admin_top_menu_cell">
					<a href="{$VH->site_url("admin/packages/search/search_login/$user_login/")}" title="{$LANG_USER_PACKAGES_OPTION}"><img src="{$public_path}/images/buttons/packages.png" /></a>
                    <a href="{$VH->site_url("admin/packages/search/search_login/$user_login/")}">{$LANG_USER_PACKAGES_OPTION}</a>
				</div>
				{/if}
					
				{if $CI->load->is_module_loaded('discount_coupons') && $content_access_obj->isPermission('Manage coupons')}
				<div class="admin_top_menu_cell">
					<a href="{$VH->site_url("admin/coupons/send/user_id/$user_id")}" class="nyroModal noborder" title="{$LANG_SEND_COUPON_TO_USER}"><img src="{$public_path}/images/buttons/tag_blue_add.png" /></a>
					<a href="{$VH->site_url("admin/coupons/send/user_id/$user_id")}" class="nyroModal noborder" title="{$LANG_SEND_COUPON_TO_USER}">{$LANG_SEND_COUPON_TO_USER}</a>
				</div>
				
				<div class="admin_top_menu_cell">
					<a href="{$VH->site_url("admin/coupons/search/search_login/$user_login")}" title="{$LANG_USER_COUPONS_OPTION}"><img src="{$public_path}/images/buttons/coupons.png" /></a>
					<a href="{$VH->site_url("admin/coupons/search/search_login/$user_login")}">{$LANG_USER_COUPONS_OPTION}</a>
				</div>
				{/if}

				{if $user->id != 1}
				<div class="admin_top_menu_cell">
					<a href="{$VH->site_url("admin/users/profile/$user_id")}" title="{$LANG_USER_EDIT_PROFILE_OPTION}"><img src="{$public_path}/images/buttons/user_edit.png" /></a>
                    <a href="{$VH->site_url("admin/users/profile/$user_id")}">{$LANG_USER_EDIT_PROFILE_OPTION}</a>
				</div>

				<div class="admin_top_menu_cell">
                    <a href="{$VH->site_url("admin/users/change_group/$user_id")}" title="{$LANG_USER_CHANGE_GROUP_OPTION}"><img src="{$public_path}/images/buttons/vcard.png" /></a>
                    <a href="{$VH->site_url("admin/users/change_group/$user_id")}">{$LANG_USER_CHANGE_GROUP_OPTION}</a>
				</div>
				
				<div class="admin_top_menu_cell">
                    <a href="{$VH->site_url("admin/users/change_status/$user_id")}" title="{$LANG_USER_CHANGE_STATUS_OPTION}"><img src="{$public_path}/images/buttons/user_red.png" /></a>
                    <a href="{$VH->site_url("admin/users/change_status/$user_id")}">{$LANG_USER_CHANGE_STATUS_OPTION}</a>
				</div>

				<div class="admin_top_menu_cell">
					<a href="{$VH->site_url("admin/users/delete/$user_id")}" title="{$LANG_USER_DELETE_PROFILE_OPTION}"><img src="{$public_path}images/buttons/user_delete.png"></a>
					<a href="{$VH->site_url("admin/users/delete/$user_id")}">{$LANG_USER_DELETE_PROFILE_OPTION}</a>
				</div>
				{/if}

				<div class="admin_top_menu_cell">
                    <a href="{$VH->site_url("email/send/user_id/$user_id")}" class="nyroModal noborder" title="{$LANG_SEND_EMAIL_TO_USER}"><img src="{$public_path}/images/buttons/user_go.png" /></a>
                    <a href="{$VH->site_url("email/send/user_id/$user_id")}" class="nyroModal noborder" title="{$LANG_SEND_EMAIL_TO_USER}">{$LANG_SEND_EMAIL_TO_USER}</a>
				</div>

				<div class="clear_float"></div>
				<div class="px10"></div>