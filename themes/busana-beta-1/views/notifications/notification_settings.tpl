{include file="backend/admin_header.tpl"}

                <div class="content">
                	{$VH->validation_errors()}
                     <h3>{$LANG_EMAIL_NOTIFICATIONS}</h3>

                     <form action="" method="post">
                     <div class="admin_option">
                     	<div class="admin_option_name" >
                     		{$LANG_NOTIFICATION_RAISE} "{$notification_array.event}"
                        </div>
                        <div class="admin_option_description" >
                        	{$notification_array.description|nl2br}
                        </div>
                     </div>
                     <div class="admin_option">
						<div class="admin_option_name">
							{$LANG_NOTIFICATION_SUBJECT}<span class="red_asterisk">*</span>
						</div>
						<input type=text name="subject" value="{$notification->getSubject()}" size="70" />
					</div>
                     <div class="admin_option">
                     	<div class="admin_option_name" >
                     		{$LANG_NOTIFICATION_BODY}<span class="red_asterisk">*</span>
                        </div>
                        <textarea name="body" cols="70" rows="15">{$notification->getBody()}</textarea>
                     </div>
                     <input type="submit" name="submit" class="button save_button" value="{$LANG_BUTTON_SAVE_CHANGES}" />
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}