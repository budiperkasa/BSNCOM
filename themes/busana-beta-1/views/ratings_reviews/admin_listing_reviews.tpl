{include file="backend/admin_header.tpl"}

				<script language="javascript" type="text/javascript">
					var action_cmd;

					function submit_reviews_form()
	            	{ldelim}
	              		$("#reviews_form").attr("action", '{$VH->site_url('admin/reviews/')}' + action_cmd + '/');
	               		return true;
	            	{rdelim}
                </script>

                <div class="content">
                    <h3>{$LANG_ADMIN_REVIEWS_TITLE} "{$listing->title()}"</h3>

                    {include file="listings/admin_listing_options_menu.tpl"}

                    <form id="reviews_form" action="" method="post" onSubmit="submit_reviews_form();">
                    <input type="hidden" name="listing_id" value="{$listing->id}">
                    <ul class="root_reviews_block">
						{render_frontend_block
							block_type='reviews_comments'
							block_template='frontend/blocks/reviews_comments_add.tpl'
							objects_table='listings'
							objects_ids=$listing->id
							comment_area_enabled=true
							reviews_mode=$listing->level->reviews_mode
							admin_mode=true
							is_richtext=$listing->level->reviews_richtext_enabled
						}
					</ul>
                    {$LANG_WITH_SELECTED}:
	                 <select name="table_action" onchange="action_cmd=this.options[this.selectedIndex].value; submit_reviews_form(); this.form.submit()">
	                 	<option value="">{$LANG_CHOOSE_ACTION}</option>
	                 	<option value="delete">{$LANG_BUTTON_DELETE_REVIEWS}</option>
	                 	<option value="spam">{$LANG_BUTTON_SPAM_REVIEWS}</option>
	                 	<option value="active">{$LANG_BUTTON_ACTIVE_REVIEWS}</option>
	                 </select>
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}