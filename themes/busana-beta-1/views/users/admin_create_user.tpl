{include file="backend/admin_header.tpl"}

{if $users_groups|@count > 1}
<script language="JavaScript" type="text/javascript">
var url = '{$registation_url}';
$(document).ready(function() {ldelim}
	$(".account_type_item").click(function() {ldelim}
		group_id = $(this).val();
		url = '{$url}'+'group_id/'+group_id+'/';
		$("#account_type_form").attr('action', url);
		$("#account_type_form").submit();
	{rdelim});
{rdelim});
</script>
{/if}

                <div class="content">
                	{$VH->validation_errors()}
                	<h3>{$LANG_USER_CRETE_TITLE}</h3>
                	
                	{if $users_groups|@count > 1}
                	<form action="" method="post" id="account_type_form">
                		<div class="admin_option">
                			<div class="admin_option_name" >
                				{$LANG_SELECT_ACCOUNT_TYPE}<span class="red_asterisk">*</span>:
                			</div>
                			{foreach from=$users_groups item=group}
                				<label><input type="radio" name="select_group" value="{$group->id}" class="account_type_item admin_option_input" {if $group->id==$selected_users_group_id}checked{/if}/> {$group->name}</label>
                			{/foreach}
                		</div>
                	</form>
					{/if}

					<form action="" method="post">
					<div class="admin_option">
                     	<div style="float: left;">
                     		<div class="admin_option_name" >
                     			{$LANG_USER_LOGIN}<span class="red_asterisk">*</span>
                     		</div>
                     		<div class="admin_option_description">
                     			{$LANG_USER_LOGIN_DESCR}
                     		</div>
                     		<input type=text name="login" value="{$user->login}" size="50" class="admin_option_input">
                     		{if $user->users_group->is_own_page && $user->users_group->use_seo_name}
                     		&nbsp;&nbsp;<span class="seo_rewrite" title="{$LANG_WRITE_SEO_STYLE}"><img src="{$public_path}images/arrow_seo.gif"></span>&nbsp;&nbsp;
                     		{/if}
						</div>
						{if $user->users_group->is_own_page && $user->users_group->use_seo_name}
						<div style="float: left;">
							<div class="admin_option_name" >
								{$LANG_USER_SEO_LOGIN}
							</div>
							<div class="admin_option_description">
								{$LANG_SEO_DESCR}
							</div>
							<input type=text name="seo_login" id="seo_login" value="{$user->seo_login}" size="50" class="admin_option_input">
						</div>
						{/if}
						<div style="clear: both"></div>
					</div>
					{if $user->users_group->is_own_page && $user->users_group->meta_enabled}
					<div class="admin_option">
						<div class="admin_option_name">
							{$LANG_USER_META_DESCRIPTION}
							{translate_content table='users' field='meta_description' row_id=$user->id field_type='text'}
						</div>
						<div class="admin_option_description">
							{$LANG_USER_META_DESCRIPTION_DESCR}
						</div>
						<textarea name="meta_description" cols="40" rows="5">{$user->meta_description}</textarea>
						
						<div class="px10"></div>
						
						<div class="admin_option_name">
							{$LANG_USER_KEYWORDS}
							{translate_content table='users' field='meta_keywords' row_id=$user->id field_type='text'}
						</div>
						<div class="admin_option_description">
							{$LANG_USER_KEYWORDS_DESCR}
						</div>
						<textarea name="meta_keywords" cols="40" rows="5">{$user->meta_keywords}</textarea>
					</div>
					{/if}

					<div class="admin_option">
						<div class="admin_option_name">
							{$LANG_USER_EMAIL}<span class="red_asterisk">*</span>
						</div>
						<input type=text name="email" value="{$user->email}" size="50" class="admin_option_input">
					</div>
					<div class="admin_option">
						<div class="admin_option_name">
							{$LANG_PASSWORD}
						</div>
						<div class="admin_option_description">
							{$LANG_PASSWORD_DESCR}
						</div>
						<input type=password name="password" size="50" class="admin_option_input">
						<div class="admin_option_name">
							{$LANG_PASSWORD_REPEAT}
						</div>
						<input type=password name="repeat_password" size="50" class="admin_option_input">
					</div>
					{if $user->users_group->logo_enabled}
						{$image_upload_block->setUploadBlock('files_upload/user_logo_upload_block.tpl')}
					{/if}
					{if $user->content_fields->fieldsCount()}
					<label class="block_title">{$LANG_LISTING_ADDITIONAL_INFORMATION}</label>
					<div class="admin_option">
						{$user->inputMode()}
					</div>
					{/if}
					<input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
					</form>
				</div>

{include file="backend/admin_footer.tpl"}