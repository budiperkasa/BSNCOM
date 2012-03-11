{include file="backend/admin_header.tpl"}

				<script language="javascript" type="text/javascript">
					
					//var loc_levels_count = {$loc_levels_count};
					var action_cmd;
					
					function submit_locations_form()
	                {ldelim}
	                	$("#locations_form").attr("action", '{$VH->site_url('admin/locations/')}' + action_cmd + '/');
	                	return true;
	                {rdelim}
				</script>

                <div class="content">
                     <h3>{$LANG_EDIT_LOCATIONS}</h3>
                     <h4>{$LANG_EDIT_LOCATIONS_DESCR}</h4>

                     <a href="{$VH->site_url("admin/locations/create")}" title="{$LANG_NEW_LOCATION_OPTION}"><img src="{$public_path}/images/buttons/page_add.png" /></a>
                     <a href="{$VH->site_url("admin/locations/create")}">{$LANG_NEW_LOCATION_OPTION}</a>&nbsp;&nbsp;&nbsp;
                     <div class="px10"></div>

                     <form id="locations_form" action="" method="post" onSubmit="submit_locations_form();">
                     <div class="admin_option_name">
                         {$LANG_LOCATIONS_LIST}
                     </div>
                     {render_frontend_block
						block_type='locations'
						block_template='backend/blocks/admin_locations_management.tpl'
						is_counter=false
						max_depth='max'
					 }
                     <div class="px10"></div>
					 {$LANG_WITH_SELECTED}:
	                 <select name="table_action" onchange="action_cmd=this.options[this.selectedIndex].value; submit_locations_form(); this.form.submit()">
	                 	<option value="">{$LANG_CHOOSE_ACTION}</option>
	                 	<option value="label">{$LANG_BUTTON_LABEL_LOCATIONS}</option>
	                 	<option value="delete">{$LANG_BUTTON_DELETE_LOCATIONS}</option>
	                 </select>
	                 <div class="px10"></div>
	                 <input type="button" class="button save_button" onClick="action_cmd='save'; submit_locations_form(); this.form.submit()" value="{$LANG_BUTTON_SAVE_CHANGES}">
                     &nbsp;&nbsp;
					 <input type=submit class="button activate_button" onClick="action_cmd='synchronize'; submit_locations_form(); this.form.submit()" value="{$LANG_BUTTON_SYNCHRONIZE_LOCATIONS}">
					 &nbsp;&nbsp;
					 <input type=submit class="button refresh_button" onClick="action_cmd='regeocode'; submit_locations_form(); this.form.submit()" value="{$LANG_BUTTON_REGEOCODE_LOCATIONS}">
                     </form>
                </div>

{include file="backend/admin_footer.tpl"}