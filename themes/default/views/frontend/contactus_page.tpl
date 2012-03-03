{include file="frontend/header.tpl"}

			<tr>
				<td id="search_bar" colspan="3">
				{include file="frontend/search_block.tpl"}
				</td>
			</tr>
			<tr>
				<td id="left_sidebar">
				{include file="frontend/left-sidebar.tpl"}
				</td>
      			<td id="content_block" valign="top">
      				<div id="content_wrapper">
	      				<div class="content">
	      					{$VH->validation_errors()}
	                         <h1>{$LANG_CONTACT_US_TITLE}</h1>
	
	                         <form action="" method="post">
		                     <div class="admin_option noborder">
		                          <div class="admin_option_name" >
		                          	{$LANG_CONTACTUS_NAME}<span class="red_asterisk">*</span>
		                          </div>
		                          <input type=text name="name" value="{$name}" size="50" class="admin_option_input">
		                     </div>
		                     <div class="admin_option">
		                          <div class="admin_option_name">
		                          	{$LANG_CONTACTUS_EMAIL}<span class="red_asterisk">*</span>
		                          </div>
		                          <input type=text name="email" value="{$email}" size="50" class="admin_option_input">
		                     </div>
		                     <div class="admin_option noborder">
		                          <div class="admin_option_name" >
		                          	{$LANG_CONTACTUS_SUBJECT}<span class="red_asterisk">*</span>
		                          </div>
		                          <input type=text name="subject" value="{$subject}" size="50" class="admin_option_input">
		                     </div>
		                     <div class="admin_option">
		                          <div class="admin_option_name">
		                          	{$LANG_CONTACTUS_BODY}<span class="red_asterisk">*</span>
		                          </div>
		                          <textarea name="message" rows="12" class="admin_option_input">{$message}</textarea>
		                     </div>
		                     {$content_fields->inputMode()}
		                     <div class="admin_option">
		                          <div class="admin_option_name">
		                          	{$LANG_FILL_CAPTCHA}<span class="red_asterisk">*</span>
		                          </div>
		                          <input type="text" name="captcha" size="4">
		                          <div class="px10"></div>
		                          {$captcha->view()}
							 <div>
							 <div class="px5"></div>
		                     <input class="front-btn" type=submit name="submit" value="{$LANG_CONTACTUS_SEND_BUTTON}">
		                     </form>
	                 	</div>
	                 </div>
				</td>
                <td id="right_sidebar">
                {include file="frontend/right-sidebar.tpl"}
                </td>
			</tr>

{include file="frontend/footer.tpl"}