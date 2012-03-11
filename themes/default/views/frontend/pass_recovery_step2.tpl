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
      					{$VH->validation_errors()}
                         <h1>{$LANG_PASSWORD_RECOVERY2}</h1>

                         <form action="" method="post">
	                     <div class="admin_option">
	                          <div class="admin_option_name">
	                          	{$LANG_PASSWORD}<span class="red_asterisk">*</span>
	                          </div>
	                          <div class="admin_option_description">
	                          	{$LANG_PASSWORD_DESCR}
	                          </div>
	                          <input type=password name="password" size="50" class="admin_option_input">
	                          <div class="admin_option_name">
	                          	{$LANG_PASSWORD_REPEAT}<span class="red_asterisk">*</span>
	                          </div>
	                          <input type=password name="repeat_password" size="50" class="admin_option_input">
	                     </div>
						 <div class="px5"></div>
	                     <input class="front-btn" type=submit name="submit" value="{$LANG_BUTTON_PASSWORD_RECOVERY2}">
	                     </form>
                 	</div>
				</td>
                <td id="right_sidebar">
                {include file="frontend/right-sidebar.tpl"}
                </td>
			</tr>

{include file="frontend/footer.tpl"}