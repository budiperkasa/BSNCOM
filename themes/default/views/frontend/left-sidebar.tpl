
<!-- LEFT SIDEBAR -->

					<!-- Login Block Starts -->
						<div class="block login_block">
						{if $session_user_id}
							<!-- Heading Starts -->
							<div class="block-top"><div class="block-top-title">{$LANG_USER_FRONT_MENU_HEADER}</div></div>
							<!-- Heading Ends -->
							<!-- Content Starts -->
							<div class="block-bottom">
								<div class="px5"></div>
									{$VH->buildFrontendMenu($CI)}
								<div class="px5"></div>
								<!-- Login Form Ends -->
							</div>
							<!-- Content Ends -->
						{else}
							{if $content_access_obj->isPermission('Edit self profile')}
								<!-- Heading Starts -->
								<div class="block-top">{$LANG_LOGIN_HEADER}</div>
								<!-- Heading Ends -->
								<!-- Content Starts -->
								<div class="block-bottom">
			
									<!-- Login Form Starts -->
									<form id="login_form" action="{$VH->site_url('login')}" method="post">
										{$LANG_LOGIN_EMAIL}:<br />
										<input type="text" name="email" class="login_input" size="25"><br />
										{$LANG_LOGIN_PASSWORD}:<br />
										<input type="password" name="password" class="login_input" size="25">
										<div class="px5"></div>
										<input type="checkbox" name="remember_me"> {$LANG_REMEMBER_ME}
										<div class="px10"></div>
										<input type="submit" name="submit" class="front-btn" value="{$LANG_BUTTON_LOGIN}">
										<div class="login_block_link"><a href="{$VH->site_url('register')}" rel="nofollow">{$LANG_CREATE_ACCOUNT}</a></div>
										<div class="login_block_link"><a href="{$VH->site_url('pass_recovery_step1')}" rel="nofollow">{$LANG_FORGOT_PASS}</a></div>
									</form>
									<!-- Login Form Ends -->
								</div>
								<!-- Content Ends -->
							{/if}
						{/if}
						</div>
					<!-- Login Block Ends -->

						{if $VH->checkQuickList()}
						<div id="quick_list">
							<a href="{$VH->site_url('quick_list')}">{$LANG_QUICK_LIST} ({$VH->checkQuickList()|@count})</a>
						</div>
						{/if}
						
						<!-- QR Code -->
						<img src="http://chart.apis.google.com/chart?cht=qr&chs=200x200&chl=MECARD:N:{$VH->urlencode($site_settings.website_title)};URL:{if $system_settings.enable_contactus_page}{$VH->str_replace('http://', '', $VH->site_url('contactus'))}{else}{$VH->str_replace('http://', '', $VH->site_url())}{/if};;" />
						<!-- /QR Code -->

<!-- /LEFT SIDEBAR -->
