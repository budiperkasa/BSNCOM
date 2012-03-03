{include file="frontend/header.tpl"}

{if $users_groups_allowed|@count > 1}
<script language="JavaScript" type="text/javascript">
var url = '{$registation_url}';
$(document).ready(function() {ldelim}
	$(".account_type_item").click(function() {ldelim}
		group_id = $(this).val();
		url = url+'group_id/'+group_id+'/';
		$("#account_type_form").attr('action', url);
		$("#account_type_form").submit();
	{rdelim});
{rdelim});
</script>
{/if}

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
                         <h1>{$LANG_CREATE_ACCOUNT}</h1>
                         
                         {if $users_groups_allowed|@count > 1}
                         <form action="" method="post" id="account_type_form">
	                     <div class="admin_option noborder">
	                          <div class="admin_option_name" >
	                          	{$LANG_SELECT_ACCOUNT_TYPE}<span class="red_asterisk">*</span>:
	                          </div>
	                          {foreach from=$users_groups_allowed item=group_item}
	                          <label><input type="radio" name="select_group" value="{$group_item->id}" class="account_type_item admin_option_input" {if $group_item->id==$registration_user_group->id}checked{/if}/> {$group_item->name}</label>
	                          {/foreach}
	                     </div>
	                     </form>
	                     {/if}

                         <form action="" method="post">
	                     <div class="admin_option noborder">
	                          <div class="admin_option_name" >
	                          	{$LANG_LOGIN}<span class="red_asterisk">*</span>
	                          </div>
	                          <div class="admin_option_description">
	                          	{$LANG_LOGIN_DESCR}
	                          </div>
	                          <input type=text name="login" value="{$user->login}" size="50" class="admin_option_input">
	                     </div>
	                     <div class="admin_option">
	                          <div class="admin_option_name">
	                          	{$LANG_EMAIL}<span class="red_asterisk">*</span>
	                          </div>
	                          <input type=text name="email" value="{$user->email}" size="50" class="admin_option_input">
	                     </div>
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

						 <div class="admin_option">
	                          <div class="admin_option_name">
	                          	{$LANG_FILL_CAPTCHA}<span class="red_asterisk">*</span>
	                          </div>
	                          <input type="text" name="captcha" size="4">
	                          <div class="px10"></div>
	                          {if $captcha}
	                          {$captcha->view()}
	                          {else}
	                          {$LANG_USERS_CONTENT_ERROR}
	                          {/if}
						 <div>

						 {if $system_settings.path_to_terms_and_conditions}
						 <div class="admin_option">
	                          <div class="admin_option_name">
	                          	<input type="checkbox" name="terms_agreement" value="1" />&nbsp;&nbsp;{$LANG_TERMS_CONDITIONS_1} <a href="{$VH->site_url($system_settings.path_to_terms_and_conditions)}" target="_blank">{$LANG_TERMS_CONDITIONS_2}</a>
	                          </div>
						 <div>
						 {/if}

						 <div class="px5"></div>
	                     <input class="front-btn" type=submit name="submit" value="{$LANG_BUTTON_CREATE_ACCOUNT}">
	                     </form>
                 	</div>
				</td>
                <td id="right_sidebar">
                {include file="frontend/right-sidebar.tpl"}
                </td>
			</tr>

{include file="frontend/footer.tpl"}