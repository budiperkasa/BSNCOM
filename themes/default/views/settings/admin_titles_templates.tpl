{include file="backend/admin_header.tpl"}

				<div class="content">
					{$VH->validation_errors()}
					<h3>{$LANG_TITLES_TEMPLATES_TITLE}</h3>
					
					<div>
						{$LANG_TITLES_TEMPLATES_DESCR}
					</div>
					
					<div class="px10"></div>
					
					<form action="" method="post">
					{foreach from=$types item=type}
						{foreach from=$type->levels item=level}
						<div class="admin_option">
							<div class="admin_option_name">
								{$LANG_TITLES_OF_TYPE} "{$type->name}" {$LANG_TITLES_OF_LEVEL} "{$level->name}"<span class="red_asterisk">*</span>
							</div>
							<input type=text name="titles[{$level->id}]" value="{$level->titles_template}" size="90" />
						</div>
						{/foreach}
					{/foreach}

					<input class="button save_button" type=submit name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
					</form>
				</div>

{include file="backend/admin_footer.tpl"}