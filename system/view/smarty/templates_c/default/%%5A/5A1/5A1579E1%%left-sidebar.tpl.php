<?php /* Smarty version 2.6.26, created on 2012-02-06 02:23:50
         compiled from frontend/left-sidebar.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'frontend/left-sidebar.tpl', 49, false),)), $this); ?>

<!-- LEFT SIDEBAR -->

					<!-- Login Block Starts -->
						<div class="block login_block">
						<?php if ($this->_tpl_vars['session_user_id']): ?>
							<!-- Heading Starts -->
							<div class="block-top"><div class="block-top-title"><?php echo $this->_tpl_vars['LANG_USER_FRONT_MENU_HEADER']; ?>
</div></div>
							<!-- Heading Ends -->
							<!-- Content Starts -->
							<div class="block-bottom">
								<div class="px5"></div>
									<?php echo $this->_tpl_vars['VH']->buildFrontendMenu($this->_tpl_vars['CI']); ?>

								<div class="px5"></div>
								<!-- Login Form Ends -->
							</div>
							<!-- Content Ends -->
						<?php else: ?>
							<?php if ($this->_tpl_vars['content_access_obj']->isPermission('Edit self profile')): ?>
								<!-- Heading Starts -->
								<div class="block-top"><?php echo $this->_tpl_vars['LANG_LOGIN_HEADER']; ?>
</div>
								<!-- Heading Ends -->
								<!-- Content Starts -->
								<div class="block-bottom">
			
									<!-- Login Form Starts -->
									<form id="login_form" action="<?php echo $this->_tpl_vars['VH']->site_url('login'); ?>
" method="post">
										<?php echo $this->_tpl_vars['LANG_LOGIN_EMAIL']; ?>
:<br />
										<input type="text" name="email" class="login_input" size="25"><br />
										<?php echo $this->_tpl_vars['LANG_LOGIN_PASSWORD']; ?>
:<br />
										<input type="password" name="password" class="login_input" size="25">
										<div class="px5"></div>
										<input type="checkbox" name="remember_me"> <?php echo $this->_tpl_vars['LANG_REMEMBER_ME']; ?>

										<div class="px10"></div>
										<input type="submit" name="submit" class="front-btn" value="<?php echo $this->_tpl_vars['LANG_BUTTON_LOGIN']; ?>
">
										<div class="login_block_link"><a href="<?php echo $this->_tpl_vars['VH']->site_url('register'); ?>
" rel="nofollow"><?php echo $this->_tpl_vars['LANG_CREATE_ACCOUNT']; ?>
</a></div>
										<div class="login_block_link"><a href="<?php echo $this->_tpl_vars['VH']->site_url('pass_recovery_step1'); ?>
" rel="nofollow"><?php echo $this->_tpl_vars['LANG_FORGOT_PASS']; ?>
</a></div>
									</form>
									<!-- Login Form Ends -->
								</div>
								<!-- Content Ends -->
							<?php endif; ?>
						<?php endif; ?>
						</div>
					<!-- Login Block Ends -->

						<?php if ($this->_tpl_vars['VH']->checkQuickList()): ?>
						<div id="quick_list">
							<a href="<?php echo $this->_tpl_vars['VH']->site_url('quick_list'); ?>
"><?php echo $this->_tpl_vars['LANG_QUICK_LIST']; ?>
 (<?php echo count($this->_tpl_vars['VH']->checkQuickList()); ?>
)</a>
						</div>
						<?php endif; ?>
						
						<!-- QR Code -->
						<img src="http://chart.apis.google.com/chart?cht=qr&chs=200x200&chl=MECARD:N:<?php echo $this->_tpl_vars['VH']->urlencode($this->_tpl_vars['site_settings']['website_title']); ?>
;URL:<?php if ($this->_tpl_vars['system_settings']['enable_contactus_page']): ?><?php echo $this->_tpl_vars['VH']->str_replace('http://','',$this->_tpl_vars['VH']->site_url('contactus')); ?>
<?php else: ?><?php echo $this->_tpl_vars['VH']->str_replace('http://','',$this->_tpl_vars['VH']->site_url()); ?>
<?php endif; ?>;;" />
						<!-- /QR Code -->

<!-- /LEFT SIDEBAR -->