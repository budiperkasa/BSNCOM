{include file="backend/admin_header.tpl"}

                <div class="content">
                     <h3 style="height: 100%;">{$LANG_MANAGE_CATEGORIES_BY_TYPE} "{$type->name}"</h3>
                     <h4 style="height: 100%;">{$LANG_EDIT_CATEGORIES_DESCR}</h4>
                     
                     <a href="{$VH->site_url("admin/categories/by_type/$type_id/create")}" title="{$LANG_NEW_CATEGORY_OPTION}"><img src="{$public_path}/images/buttons/page_add.png" /></a>
                     <a href="{$VH->site_url("admin/categories/by_type/$type_id/create")}">{$LANG_NEW_CATEGORY_OPTION}</a>&nbsp;&nbsp;&nbsp;
                     <br />
                     <br />

                     <form action="" method="post">
                     <div class="admin_option_name" style="height: 100%;">
                     	{$LANG_CATEGORIES_LIST}:
                     </div>
                     {render_frontend_block
						block_type='categories'
						block_template='backend/blocks/admin_categories_management.tpl'
						type=$type_id
						is_counter=false
						max_depth='max'
					 }

                     <br/>
                     <br/>
                     <input type="submit" class="button save_button" name="submit" value="{$LANG_BUTTON_SAVE_CHANGES}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}