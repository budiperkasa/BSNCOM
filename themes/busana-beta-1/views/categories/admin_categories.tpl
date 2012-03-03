{include file="backend/admin_header.tpl"}

                <div class="content">
                     <h3>{$LANG_EDIT_CATEGORIES}</h3>
                     <h4>{$LANG_EDIT_CATEGORIES_DESCR}</h4>
                     
                     <a href="{$VH->site_url("admin/categories/create")}" title="{$LANG_NEW_CATEGORY_OPTION}"><img src="{$public_path}/images/buttons/page_add.png" /></a>
                     <a href="{$VH->site_url("admin/categories/create")}">{$LANG_NEW_CATEGORY_OPTION}</a>&nbsp;&nbsp;&nbsp;
                     <br />
                     <br />

                     <form action="" method="post">
                     <div class="admin_option_name">
                     	{$LANG_CATEGORIES_LIST}:
                     </div>
                     {render_frontend_block
						block_type='categories'
						block_template='backend/blocks/admin_categories_management.tpl'
						is_counter=false
						max_depth='max'
					 }

                     <br/>
                     <br/>
                     <input type="submit" class="button save_button" name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}