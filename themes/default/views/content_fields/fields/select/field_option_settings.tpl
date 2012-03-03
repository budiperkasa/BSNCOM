{include file="backend/admin_header.tpl"}
{assign var="field_id" value=$field->id}

                <div class="content">
                	{$VH->validation_errors()}
                     <h3>
                     {if $option_id == 'new'}{$LANG_CREATE_FIELD_OPTION_TITLE_1} "{$field->type_name}" {$LANG_CREATE_FIELD_OPTION_TITLE_2} "{$field->name}"
                     {else}
                     {$LANG_EDIT_FIELD_OPTION_TITLE_1} "{$field->type_name}" {$LANG_EDIT_FIELD_OPTION_TITLE_2} "{$field->name}"
                     {/if}
                     </h3>

                     <form action="" method="post">
					 <input type="hidden" name="id" value="{$field->id}">
                     <div class="admin_option">
						<div class="admin_option_name" >
							{$LANG_OPTION_NAME_TH}<span class="red_asterisk">*</span>
							{translate_content table='content_fields_type_select' field='option_name' row_id=$option_id}
						</div>
						<input type=text name="name" value="{$name}" size="40" class="admin_option_input">
					 </div>
					 {if $option_id != 'new'}
                     <input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
                     {else}
                     <input class="button create_button" type=submit name="submit" value="{$LANG_BUTTON_CREATE_FIELD_OPTION}">
                     {/if}
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}