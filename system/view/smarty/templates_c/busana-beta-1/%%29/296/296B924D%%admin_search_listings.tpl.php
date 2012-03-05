<?php /* Smarty version 2.6.26, created on 2012-02-06 04:22:52
         compiled from listings/admin_search_listings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'addslashes', 'listings/admin_search_listings.tpl', 23, false),array('function', 'render_frontend_block', 'listings/admin_search_listings.tpl', 276, false),array('function', 'asc_desc_insert', 'listings/admin_search_listings.tpl', 358, false),array('modifier', 'cat', 'listings/admin_search_listings.tpl', 276, false),array('modifier', 'count', 'listings/admin_search_listings.tpl', 352, false),array('modifier', 'date_format', 'listings/admin_search_listings.tpl', 429, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

				<script language="javascript" type="text/javascript">
                // Global url string
                <?php if ($this->_tpl_vars['base_url']): ?>
                	var global_js_url = '<?php echo $this->_tpl_vars['base_url']; ?>
';
                <?php else: ?>
                	var global_js_url = '<?php echo $this->_tpl_vars['VH']->site_url("search/"); ?>
';
                <?php endif; ?>
                
                // Command variable, needs for delete, block listings buttons
                var action_cmd;
                
                // Is advanced search in use? Attach to global_js_url
                var use_advanced = '';
                
                // Was advanced search block loaded?
                var advanced_loaded = false;

                // Scripts code array - will be evaluated when advanced search attached
                var scripts = new Array();
                
                var default_what = '<?php echo smarty_function_addslashes(array('string' => $this->_tpl_vars['LANG_SEARCH_WHAT']), $this);?>
 (<?php echo smarty_function_addslashes(array('string' => $this->_tpl_vars['LANG_SEARCH_WHAT_DESCR']), $this);?>
)';
                var default_where = '<?php echo smarty_function_addslashes(array('string' => $this->_tpl_vars['LANG_SEARCH_WHERE']), $this);?>
 (<?php echo smarty_function_addslashes(array('string' => $this->_tpl_vars['LANG_SEARCH_WHERE_DESCR']), $this);?>
)';
                
                var use_districts = <?php echo $this->_tpl_vars['system_settings']['geocoded_locations_mode_districts']; ?>
;
                var use_provinces = <?php echo $this->_tpl_vars['system_settings']['geocoded_locations_mode_provinces']; ?>
;
                
                var ajax_autocomplete_request = '<?php echo $this->_tpl_vars['VH']->site_url("ajax/locations/autocomplete_request/"); ?>
';

                $(document).ready(function() {
                	<?php if (( ! $this->_tpl_vars['current_type'] ) || ( $this->_tpl_vars['current_type'] && $this->_tpl_vars['current_type']->search_type != 'disabled' )): ?>
	                	<?php if (( ( $this->_tpl_vars['system_settings']['single_type_structure'] || ! $this->_tpl_vars['current_type'] ) && $this->_tpl_vars['system_settings']['global_where_search'] ) || ( ! $this->_tpl_vars['system_settings']['single_type_structure'] && $this->_tpl_vars['current_type'] && $this->_tpl_vars['current_type']->where_search && $this->_tpl_vars['current_type']->locations_enabled )): ?>
	                		$('#where_search').autocomplete({
				               	source: function(request, response) {
				               		<?php if ($this->_tpl_vars['system_settings']['predefined_locations_mode'] != 'only'): ?>
				               			<?php if ($this->_tpl_vars['system_settings']['predefined_locations_mode'] != 'disabled'): ?>
				               				// geocode + ajax from DB
				               				geocodeAddress(request.term, '<?php echo $this->_tpl_vars['system_settings']['default_language']; ?>
', response, ajax_autocomplete_request, false);
				               			<?php else: ?>
				               				// only geocode
				               				geocodeAddress(request.term, '<?php echo $this->_tpl_vars['system_settings']['default_language']; ?>
', response, false, false);
				               			<?php endif; ?>
				               		<?php elseif ($this->_tpl_vars['system_settings']['predefined_locations_mode'] != 'disabled'): ?>
				               			// only ajax from DB
				               			$.post(ajax_autocomplete_request, {query: request.term}, function(data) {
					               			if (data = JSON.parse(data))
					               				response(data);
					               		});
				               		<?php endif; ?>

				               		/*<?php if ($this->_tpl_vars['system_settings']['predefined_locations_mode'] != 'only'): ?>
				               			geocodeAddress(request.term, '<?php echo $this->_tpl_vars['system_settings']['default_language']; ?>
', response);
				               		<?php elseif ($this->_tpl_vars['system_settings']['predefined_locations_mode'] != 'disabled'): ?>
					               		$.post('<?php echo $this->_tpl_vars['VH']->site_url("ajax/locations/autocomplete_request/"); ?>
', {query: request.term}, function(data) {
					               			if (data = JSON.parse(data))
					               				response(data);
					               		});
				               		<?php endif; ?>*/
								},
								focus: function(event, ui) {
									$(this).val(ui.item.label);
									return false;
								},
								select: function(event, ui) {
									$(this).val(ui.item.label);
									return false;
								},
								minLength: 2,
								delay: 600
							});
			                $('#where_radius_slider').slider({
								min: 0,
								max: 10,
								range: "min",
								value: $("#where_radius_label").val(),
								slide: function(event, ui) {
									$("#where_radius_label").val(ui.value);
									$("#where_radius").val(ui.value);
								}
							});
							if ($("#where_search").val() == default_where)
								$("#where_search").addClass("highlight_search_input");
						<?php endif; ?>
	
						<?php if (( ( $this->_tpl_vars['system_settings']['single_type_structure'] || ! $this->_tpl_vars['current_type'] ) && $this->_tpl_vars['system_settings']['global_categories_search'] ) || ( ! $this->_tpl_vars['system_settings']['single_type_structure'] && $this->_tpl_vars['current_type'] && $this->_tpl_vars['current_type']->categories_search )): ?>
		                $("#search_by_category_tree").bind("change_state.jstree", function (event, data) {
							var categories_list = [];
							$.each(data.inst.get_checked(), function() {
								categories_list.push($(this).attr('id').replace("search_", ""));
							});
							$("#search_category").val(categories_list.join(','));
							return false;
						});

		                $("#search_category_button, #search_by_category_tree").hover(
		                	function() {
			                	$("#search_by_category_tree").show();
			                	var pos = $("#search_category_button").offset();
			                	$("#search_by_category_tree").css({'top': parseInt(pos.top)+parseInt(14)+'px', 'left': parseInt(pos.left)+parseInt(5)+'px'});
			                	return false;
		                	},
		                	function() {
		                		$("#search_by_category_tree").hide();
		                	}
		                );
		                <?php endif; ?>
	
						<?php if (( ( $this->_tpl_vars['system_settings']['single_type_structure'] || ! $this->_tpl_vars['current_type'] ) && $this->_tpl_vars['system_settings']['global_what_search'] ) || ( ! $this->_tpl_vars['system_settings']['single_type_structure'] && $this->_tpl_vars['current_type'] && $this->_tpl_vars['current_type']->what_search )): ?>
						$("#what_search").blur( function() {
							if ($(this).val() == '' )
								$(this).val(default_what).addClass('highlight_search_input');
						});
						$("#what_search").focus( function() {
							if ($(this).val() == default_what)
								$(this).val('').removeClass('highlight_search_input');
						});
	
						var what_advanced_opened = false;
						$("#what_match").click( function() {
							$("#what_advanced_block").slideToggle(200);
							if (what_advanced_opened) {
								$("#what_match img").attr('src', '<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/add.png');
								what_advanced_opened = false;
							} else {
								$("#what_match img").attr('src', '<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/delete.png');
								what_advanced_opened = true;
							}
							return false;
						});
	
						if ($("#what_search").val() == default_what)
							$("#what_search").addClass("highlight_search_input");
						<?php endif; ?>
	
						<?php if (( ( $this->_tpl_vars['system_settings']['single_type_structure'] || ! $this->_tpl_vars['current_type'] ) && $this->_tpl_vars['system_settings']['global_where_search'] ) || ( ! $this->_tpl_vars['system_settings']['single_type_structure'] && $this->_tpl_vars['current_type'] && $this->_tpl_vars['current_type']->where_search && $this->_tpl_vars['current_type']->locations_enabled )): ?>
						$("#where_search").blur( function() {
							if ($(this).val() == '' )
								$(this).val(default_where).addClass('highlight_search_input');
						});
						$("#where_search").focus( function() {
							if ($(this).val() == default_where)
								$(this).val('').removeClass('highlight_search_input');
						});
						
						var where_advanced_opened = false;
						$("#where_advanced").click( function() {
							$("#where_advanced_block").slideToggle(200);
							if (where_advanced_opened) {
								$("#where_advanced img").attr('src', '<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/add.png');
								where_advanced_opened = false;
							} else {
								$("#where_advanced img").attr('src', '<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/delete.png');
								where_advanced_opened = true;
							}
							return false;
						});
						<?php endif; ?>
						
						// Form submit event
						$("#search_form").submit( function() {
							<?php if (( ( $this->_tpl_vars['system_settings']['single_type_structure'] || ! $this->_tpl_vars['current_type'] ) && $this->_tpl_vars['system_settings']['global_what_search'] ) || ( ! $this->_tpl_vars['system_settings']['single_type_structure'] && $this->_tpl_vars['current_type'] && $this->_tpl_vars['current_type']->what_search )): ?>
							if ($("#what_search").val() != '' && $("#what_search").val() != default_what) {
	                			global_js_url = global_js_url + 'what_search/' + urlencode($("#what_search").val()) + '/';
	                			global_js_url = global_js_url + "what_match" + '/' + $("input[name=what_match]:checked").val() + '/';
	                		}
	                		<?php endif; ?>
	
	                		<?php if (( ( $this->_tpl_vars['system_settings']['single_type_structure'] || ! $this->_tpl_vars['current_type'] ) && $this->_tpl_vars['system_settings']['global_where_search'] ) || ( ! $this->_tpl_vars['system_settings']['single_type_structure'] && $this->_tpl_vars['current_type'] && $this->_tpl_vars['current_type']->where_search && $this->_tpl_vars['current_type']->locations_enabled )): ?>
	                		if ($("#where_search").val() != '' && $("#where_search").val() != default_where) {
	                			global_js_url = global_js_url + 'where_search/' + urlencode($("#where_search").val()) + '/';
	                			if (where_advanced_opened && parseInt($("#where_radius").val())>0) {
	                				global_js_url = global_js_url + 'where_radius/' + $("#where_radius").val() + '/';
	                			}
	                		}
	                		<?php endif; ?>
	                		
	                		<?php if (( ( $this->_tpl_vars['system_settings']['single_type_structure'] || ! $this->_tpl_vars['current_type'] ) && $this->_tpl_vars['system_settings']['global_categories_search'] ) || ( ! $this->_tpl_vars['system_settings']['single_type_structure'] && $this->_tpl_vars['current_type'] && $this->_tpl_vars['current_type']->categories_search )): ?>
	                		if ($("#search_category").val() != '') {
	                			global_js_url = global_js_url + 'search_category/' + $("#search_category").val() + '/';
	                		}
	                		<?php endif; ?>
	                		
	                		if ($("input[name=search_claimed_listings]:checked").val() != 'any') {
	                			global_js_url = global_js_url + 'search_claimed_listings/' + $("input[name=search_claimed_listings]:checked").val() + '/';
	                		}
	
	                		global_js_url = global_js_url + use_advanced;
	                		window.location.href = global_js_url;
							return false;
						});
	
						<?php if ($this->_tpl_vars['advanced_search_fields']->fieldsCount() || $this->_tpl_vars['content_access_obj']->isPermission('Manage all listings')): ?>
						var advanced_opened = false;
						$(".advanced_search").bind('click', function() {
							if (advanced_opened) {
								$(".advanced_search img").attr('src', '<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/add.png');
								$("#advanced_search_block").hide();
								advanced_opened = false;
								use_advanced = '';
							} else {
								$(".advanced_search img").attr('src', '<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/delete.png');
								$("#advanced_search_block").show();
								advanced_opened = true;
	
								if (!advanced_loaded) {
									ajax_loader_show();
									$.post('<?php echo $this->_tpl_vars['VH']->site_url("ajax/listings/build_advanced_search/"); ?>
', {type_id: $("#search_type").val(), args: '<?php echo $this->_tpl_vars['VH']->json_encode($this->_tpl_vars['args']); ?>
'}, 
										function(data) {
											$("#advanced_search_block").html(parseScript(data));
											evalScripts(data);
											advanced_loaded = true;
											ajax_loader_hide();
										}
									);
								}
								use_advanced = 'use_advanced/true/';
							}
							return false;
						});
						<?php if ($this->_tpl_vars['args']['use_advanced']): ?>
						$('.advanced_search').triggerHandler('click');
						<?php endif; ?>
						<?php endif; ?>
					<?php endif; ?>
					
					$("#search_type").change( function() {
						if ($(this).val() != 0)
							global_js_url = '<?php echo $this->_tpl_vars['clean_url']; ?>
search_type_id/' + $(this).val() + '/';
						else
							global_js_url = '<?php echo $this->_tpl_vars['clean_url']; ?>
';
						window.location.href = global_js_url;
					});
                });

                function submit_listings_form()
                {
                	$("#listings_form").attr("action", '<?php echo $this->_tpl_vars['VH']->site_url('admin/listings/'); ?>
' + action_cmd + '/');
                	return true;
                }
                </script>

                <div class="content">
                     <h3><?php echo $this->_tpl_vars['LANG_SEARCH_LISTINGS']; ?>
</h3>

                     <form id="search_form" action="" method="post">
	                    <?php if (! $this->_tpl_vars['system_settings']['single_type_structure']): ?>
	                    <div class="search_filter_standart">
		                    <?php echo $this->_tpl_vars['LANG_SEARCH_BY_TYPE']; ?>
:
		                    <select id="search_type" name="search_type">
								<option value="0">- - - <?php echo $this->_tpl_vars['LANG_SEARCH_FOR_ALL_TYPES']; ?>
 - - -</option>
								<?php $_from = $this->_tpl_vars['types']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['type']):
?>
								<option value="<?php echo $this->_tpl_vars['type']->id; ?>
" <?php if ($this->_tpl_vars['args']['search_type_id'] == $this->_tpl_vars['type']->id): ?>selected<?php endif; ?>><?php echo $this->_tpl_vars['type']->name; ?>
</option>
								<?php endforeach; endif; unset($_from); ?>
							</select>
						</div>
						<div class="clear_float"></div>
						<?php else: ?>
						<input type="hidden" id="search_type" name="search_type" value="0" />
						<?php endif; ?>

                     	<div>
                     		<?php if (( ( $this->_tpl_vars['system_settings']['single_type_structure'] || ! $this->_tpl_vars['current_type'] ) && $this->_tpl_vars['system_settings']['global_categories_search'] ) || ( ! $this->_tpl_vars['system_settings']['single_type_structure'] && $this->_tpl_vars['current_type'] && $this->_tpl_vars['current_type']->categories_search )): ?>
                     		<div class="search_filter_standart">
	                     		<input type="hidden" id="search_category" value="<?php echo $this->_tpl_vars['args']['search_category']; ?>
">
								<div id="search_category_button">
		                     		<a class="search_category"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/chart_organisation.png"></a> <a class="search_category"><?php echo $this->_tpl_vars['LANG_SEARCH_CATEGORIES']; ?>
</a>
		                     	</div>
		                    </div>

							<?php if ($this->_tpl_vars['system_settings']['categories_block_type'] == 'categories-bar-ajax'): ?>
								<?php $this->assign('categories_search_template', 'categories_search_block_ajax.tpl'); ?>
							<?php else: ?>
								<?php $this->assign('categories_search_template', 'categories_search_block.tpl'); ?>
							<?php endif; ?>
							<?php echo smarty_function_render_frontend_block(array('block_type' => 'categories','block_template' => ((is_array($_tmp="frontend/blocks/")) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['categories_search_template']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['categories_search_template'])),'type' => $this->_tpl_vars['current_type']->id,'search_categories_array' => $this->_tpl_vars['search_categories_array'],'is_counter' => false,'max_depth' => 2), $this);?>

			                <?php endif; ?>

                     		<?php if (( ( $this->_tpl_vars['system_settings']['single_type_structure'] || ! $this->_tpl_vars['current_type'] ) && $this->_tpl_vars['system_settings']['global_what_search'] ) || ( ! $this->_tpl_vars['system_settings']['single_type_structure'] && $this->_tpl_vars['current_type'] && $this->_tpl_vars['current_type']->what_search )): ?>
                     		<div class="search_filter_standart">
                     			<div>
                     				<a href="javascript: void(0);" id="what_match"><img style="padding-top: 3px;" src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/add.png"></a> <input type="text" class="what_where_search_input" name="what_search" id="what_search" value="<?php if ($this->_tpl_vars['args']['what_search']): ?><?php echo $this->_tpl_vars['args']['what_search']; ?>
<?php else: ?><?php echo $this->_tpl_vars['LANG_SEARCH_WHAT']; ?>
 (<?php echo $this->_tpl_vars['LANG_SEARCH_WHAT_DESCR']; ?>
)<?php endif; ?>" />
                     			</div>
                     			<div id="what_advanced_block" style="display: none;">
                     				<label><input type="radio" name="what_match" value="any" <?php if ($this->_tpl_vars['args']['what_match'] == 'any' || ! $this->_tpl_vars['args'][$this->_tpl_vars['field_mode']]): ?>checked<?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_ANY_MATCH']; ?>
</label>
	                     			<label><input type="radio" name="what_match" value="exact" <?php if ($this->_tpl_vars['args']['what_match'] == 'exact'): ?>checked<?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_EXACT_MATCH']; ?>
</label>
	                     		</div>
                     		</div>
                     		<?php endif; ?>
                     		
                     		<?php if (( ( $this->_tpl_vars['system_settings']['single_type_structure'] || ! $this->_tpl_vars['current_type'] ) && $this->_tpl_vars['system_settings']['global_where_search'] ) || ( ! $this->_tpl_vars['system_settings']['single_type_structure'] && $this->_tpl_vars['current_type'] && $this->_tpl_vars['current_type']->where_search && $this->_tpl_vars['current_type']->locations_enabled )): ?>
                     		<div class="search_filter_standart">
                     			<div>
	                     			<a href="javascript: void(0);" id="where_advanced"><img style="padding-top: 3px;" src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/add.png"></a>
	                     			<input type="text" class="what_where_search_input" name="where_search" id="where_search" value="<?php if ($this->_tpl_vars['args']['where_search']): ?><?php echo $this->_tpl_vars['args']['where_search']; ?>
<?php elseif ($this->_tpl_vars['current_location']): ?><?php echo $this->_tpl_vars['current_location']->getChainAsString(); ?>
<?php else: ?><?php echo $this->_tpl_vars['LANG_SEARCH_WHERE']; ?>
 (<?php echo $this->_tpl_vars['LANG_SEARCH_WHERE_DESCR']; ?>
)<?php endif; ?>" />
	                     		</div>
                     			<div id="where_advanced_block" style="display: none;">
                     				<div class="px5"></div>
                     				<?php echo $this->_tpl_vars['LANG_SEARCH_IN_RADIUS']; ?>

									<input type="text" id="where_radius_label" value="<?php if ($this->_tpl_vars['args']['where_radius']): ?><?php echo $this->_tpl_vars['args']['where_radius']; ?>
<?php else: ?>0<?php endif; ?>" size="1" maxlength="2" disabled />
									<input type="hidden" name="where_radius" id="where_radius" value="<?php if ($this->_tpl_vars['args']['where_radius']): ?><?php echo $this->_tpl_vars['args']['where_radius']; ?>
<?php else: ?>0<?php endif; ?>" />
									<?php if ($this->_tpl_vars['search_in_raduis_measure'] == 'miles'): ?>
										<?php echo $this->_tpl_vars['LANG_SEARCH_IN_RADIUS_MILES']; ?>

									<?php else: ?>
										<?php echo $this->_tpl_vars['LANG_SEARCH_IN_RADIUS_KILOMETRES']; ?>

									<?php endif; ?>
									<div id="where_radius_slider"></div>
	                     		</div>
                     		</div>
                     		<?php endif; ?>

                     		<?php if ($this->_tpl_vars['advanced_search_fields']->fieldsCount() || $this->_tpl_vars['content_access_obj']->isPermission('Manage all listings')): ?>
                     		<div class="search_filter_standart">
	                     		<div id="advanced_search_button">
	                     			<a href="javascript: void(0);" class="toggle advanced_search"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/add.png"></a> <a href="javascript: void(0);" class="toggle advanced_search"><?php echo $this->_tpl_vars['LANG_ADVANCED_SEARCH']; ?>
</a>
	                     		</div>
	                     	</div>
	                     	<?php endif; ?>
	                     	<div class="clear_float"></div>
	                    </div>
	                     	
                     	<div class="search_block">
                     		<?php if ($this->_tpl_vars['content_access_obj']->isPermission('Manage ability to claim')): ?>
                     		<div class="search_item">
                     			<label><input type="radio" name="search_claimed_listings" value="any" <?php if (! $this->_tpl_vars['args']['search_claimed_listings']): ?>checked<?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_SEARCH_ANY_LISTINGS']; ?>
</label>
                     			<label><input type="radio" name="search_claimed_listings" value="ability_to_claim" <?php if ($this->_tpl_vars['args']['search_claimed_listings'] == 'ability_to_claim'): ?>checked<?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_LISTINGS_ABILITY_TO_CLAIM_STATUS']; ?>
</label>
                     			<label><input type="radio" name="search_claimed_listings" value="claimed" <?php if ($this->_tpl_vars['args']['search_claimed_listings'] == 'claimed'): ?>checked<?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_LISTINGS_CLAIMED_STATUS']; ?>
</label>
                     			<label><input type="radio" name="search_claimed_listings" value="approved_claim" <?php if ($this->_tpl_vars['args']['search_claimed_listings'] == 'approved_claim'): ?>checked<?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_LISTINGS_APPROVED_CLAIM_STATUS']; ?>
</label>
                     		</div>
                     		<?php endif; ?>

                     		<?php echo $this->_tpl_vars['search_fields']->inputMode($this->_tpl_vars['args']); ?>

                     	</div>
                     	<div class="clear_float"></div>
                     	<div id="advanced_search_block" style="display: none;"></div>
                     	<div class="clear_float"></div>
                     	<div class="search_item_button">
	                    	<input type="submit" class="button search_button" id="process_search" value="<?php echo $this->_tpl_vars['LANG_BUTTON_SEARCH_LISTINGS']; ?>
">
	                    </div>
                     </form>

                     <div class="search_results_title">
                     	<?php echo $this->_tpl_vars['LANG_SEARCH_RESULTS_1']; ?>
 (<?php echo $this->_tpl_vars['listings_count']; ?>
 <?php echo $this->_tpl_vars['LANG_SEARCH_RESULTS_2']; ?>
):
                     </div>

                     <?php if (count($this->_tpl_vars['listings']) > 0): ?>
                     <form id="listings_form" action="" method="post" onSubmit="submit_listings_form();">
                     <table class="standardTable" border="0" cellpadding="2" cellspacing="2">
                       <tr>
                         <th><input type="checkbox"></th>
                         <!--<th><?php echo $this->_tpl_vars['LANG_LOGO_TH']; ?>
</th>-->
                         <th><?php echo smarty_function_asc_desc_insert(array('base_url' => $this->_tpl_vars['search_url'],'orderby' => 'title','orderby_query' => $this->_tpl_vars['orderby'],'direction' => $this->_tpl_vars['direction'],'title' => $this->_tpl_vars['LANG_TITLE_TH']), $this);?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_OWNER_TH']; ?>
</th>
                         <?php if (! $this->_tpl_vars['system_settings']['single_type_structure']): ?>
                         <th><?php echo $this->_tpl_vars['LANG_TYPE_TH']; ?>
</th>
                         <?php endif; ?>
                         <th><?php echo $this->_tpl_vars['LANG_LEVEL_TH']; ?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_STATUS_TH']; ?>
</th>
                         <th><?php echo smarty_function_asc_desc_insert(array('base_url' => $this->_tpl_vars['search_url'],'orderby' => 'creation_date','orderby_query' => $this->_tpl_vars['orderby'],'direction' => $this->_tpl_vars['direction'],'title' => $this->_tpl_vars['LANG_CREATION_DATE_TH']), $this);?>
</th>
                         <th><?php echo smarty_function_asc_desc_insert(array('base_url' => $this->_tpl_vars['search_url'],'orderby' => 'expiration_date','orderby_query' => $this->_tpl_vars['orderby'],'direction' => $this->_tpl_vars['direction'],'title' => $this->_tpl_vars['LANG_EXPIRATION_DATE_TH']), $this);?>
</th>
                         <th><?php echo $this->_tpl_vars['LANG_OPTIONS_TH']; ?>
</th>
                       </tr>
                       <?php $_from = $this->_tpl_vars['listings']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['listing']):
?>
                       <?php $this->assign('listing_id', $this->_tpl_vars['listing']->id); ?>
					   <?php $this->assign('listing_owner_id', $this->_tpl_vars['listing']->owner_id); ?>
					   <?php $this->assign('listing_owner_login', $this->_tpl_vars['listing']->user->login); ?>
                       <tr>
                         <td>
                    	  	<input type="checkbox" name="cb_<?php echo $this->_tpl_vars['listing']->id; ?>
" value="<?php echo $this->_tpl_vars['listing']->id; ?>
">
                    	 </td>
                         <!--<td>
                         <?php if ($this->_tpl_vars['listing']->level->logo_enabled): ?>
                         	 <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/edit/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_EDIT_LISTING_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['users_content']; ?>
/users_images/logos/<?php echo $this->_tpl_vars['listing']->logo_file; ?>
" /></a>&nbsp;
                         <?php endif; ?>
                         </td>-->
                         <td>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/view/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_VIEW_LISTING_OPTION']; ?>
"><?php echo $this->_tpl_vars['listing']->title(); ?>
</a>&nbsp;
                         </td>
                         <td>
                         	 <?php if ($this->_tpl_vars['listing_owner_id'] != 1 && $this->_tpl_vars['listing_owner_id'] != $this->_tpl_vars['session_user_id'] && $this->_tpl_vars['content_access_obj']->getUserAccess($this->_tpl_vars['listing_owner_id'],'View all users')): ?>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/view/".($this->_tpl_vars['listing_owner_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_VIEW_USER_OPTION']; ?>
"><?php echo $this->_tpl_vars['listing_owner_login']; ?>
</a>&nbsp;
                             <?php else: ?>
                             <?php echo $this->_tpl_vars['listing_owner_login']; ?>

                             <?php endif; ?>

                             <?php if ($this->_tpl_vars['content_access_obj']->isPermission('Manage ability to claim')): ?>
                             	 <?php if (! $this->_tpl_vars['listing']->claim_row['approved']): ?>
									<?php if (! $this->_tpl_vars['listing']->claim_row['to_user_id'] && $this->_tpl_vars['listing']->claim_row['ability_to_claim']): ?>
										<br />(<i>May be claimed</i>)
									<?php elseif ($this->_tpl_vars['listing']->claim_row['to_user_id'] && $this->_tpl_vars['listing']->claim_user): ?>
										<?php $this->assign('user_id', $this->_tpl_vars['listing']->claim_user->id); ?>
										<br />(<i><?php echo $this->_tpl_vars['LANG_LISTING_SHORT_CLAIMED_BY']; ?>
 <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/view/".($this->_tpl_vars['user_id'])); ?>
"><?php echo $this->_tpl_vars['listing']->claim_user->login; ?>
</a><br />
											<nobr><a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/approve_claim/".($this->_tpl_vars['listing_id'])); ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/accept.png" /> <?php echo $this->_tpl_vars['LANG_LISTING_CLAIME_APPROVE']; ?>
</a></nobr>
											<nobr><a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/decline_claim/".($this->_tpl_vars['listing_id'])); ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/delete.png" /> <?php echo $this->_tpl_vars['LANG_LISTING_CLAIME_DECLINE']; ?>
</a></nobr>
										</i>)
									<?php endif; ?>
								 <?php elseif ($this->_tpl_vars['listing']->claim_user): ?>
									<br />(<i><nobr><a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/decline_claim/".($this->_tpl_vars['listing_id'])); ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/delete.png" /> <?php echo $this->_tpl_vars['LANG_LISTING_CLAIM_ROLL_BACK']; ?>
</a></nobr></i>)
								 <?php endif; ?>
							 <?php endif; ?>
                         </td>
                         <?php if (! $this->_tpl_vars['system_settings']['single_type_structure']): ?>
                         <td>
                             <?php echo $this->_tpl_vars['listing']->type->name; ?>
&nbsp;
                         </td>
                         <?php endif; ?>
                         <td>
                            <?php if ($this->_tpl_vars['content_access_obj']->isPermission('Change listing level') && count($this->_tpl_vars['listing']->type->buildLevels()) > 1): ?>
								<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/change_level/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_CHANGE_LISTING_LEVEL_OPTION']; ?>
"><?php echo $this->_tpl_vars['listing']->level->name; ?>
</a> <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/change_level/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_CHANGE_LISTING_LEVEL_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/icons/upgrade.png" /></a>
							<?php else: ?>
								<?php echo $this->_tpl_vars['listing']->level->name; ?>

							<?php endif; ?>
                         </td>
                         <td>
                         	<?php if ($this->_tpl_vars['listing']->status == 1): ?><nobr><a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/change_status/".($this->_tpl_vars['listing_id'])); ?>
" class="status_active" title="<?php echo $this->_tpl_vars['LANG_CHANGE_LISTING_STATUS_OPTION']; ?>
"><?php echo $this->_tpl_vars['LANG_STATUS_ACTIVE']; ?>
</a></nobr><?php endif; ?>
                         	<?php if ($this->_tpl_vars['listing']->status == 2): ?><nobr><a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/change_status/".($this->_tpl_vars['listing_id'])); ?>
" class="status_blocked" title="<?php echo $this->_tpl_vars['LANG_CHANGE_LISTING_STATUS_OPTION']; ?>
"><?php echo $this->_tpl_vars['LANG_STATUS_BLOCKED']; ?>
</a></nobr><?php endif; ?>
                         	<?php if ($this->_tpl_vars['listing']->status == 3): ?><nobr><a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/change_status/".($this->_tpl_vars['listing_id'])); ?>
" class="status_suspended" title="<?php echo $this->_tpl_vars['LANG_CHANGE_LISTING_STATUS_OPTION']; ?>
"><?php echo $this->_tpl_vars['LANG_STATUS_SUSPENDED']; ?>
</a>&nbsp;<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/prolong/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_PROLONG_ACTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/date_add.png"></a></nobr><?php endif; ?>
                         	<?php if ($this->_tpl_vars['listing']->status == 4): ?><nobr><a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/change_status/".($this->_tpl_vars['listing_id'])); ?>
" class="status_unapproved" title="<?php echo $this->_tpl_vars['LANG_CHANGE_LISTING_STATUS_OPTION']; ?>
"><?php echo $this->_tpl_vars['LANG_STATUS_UNAPPROVED']; ?>
</a></nobr><?php endif; ?>
                         	<?php if ($this->_tpl_vars['listing']->status == 5): ?><nobr><a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/change_status/".($this->_tpl_vars['listing_id'])); ?>
" class="status_notpaid" title="<?php echo $this->_tpl_vars['LANG_CHANGE_LISTING_STATUS_OPTION']; ?>
"><?php echo $this->_tpl_vars['LANG_STATUS_NOTPAID']; ?>
</a><?php if ($this->_tpl_vars['listing_owner_id'] == $this->_tpl_vars['session_user_id']): ?>&nbsp;<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/payment/invoices/my/"); ?>
" title="<?php echo $this->_tpl_vars['LANG_VIEW_MY_INVOICES_MENU']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/money_add.png"></a><?php endif; ?></nobr><?php endif; ?>
                         	&nbsp;
                         </td>
                         <td>
                             <?php echo ((is_array($_tmp=$this->_tpl_vars['listing']->creation_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%D %T") : smarty_modifier_date_format($_tmp, "%D %T")); ?>
&nbsp;
                         </td>
                         <td>
                            <?php if (! $this->_tpl_vars['listing']->level->active_years && ! $this->_tpl_vars['listing']->level->active_months && ! $this->_tpl_vars['listing']->level->active_days && ! $this->_tpl_vars['listing']->level->allow_to_edit_active_period): ?>
                         		<span class="green"><?php echo $this->_tpl_vars['LANG_ETERNAL']; ?>
</span>
                         	<?php else: ?>
                         		<?php echo ((is_array($_tmp=$this->_tpl_vars['listing']->expiration_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%D %T") : smarty_modifier_date_format($_tmp, "%D %T")); ?>
&nbsp;
                         	<?php endif; ?>
                         </td>
                         <td>
                         	<nobr>
                         	 <?php if ($this->_tpl_vars['listing']->status == 1): ?>
                         	 <?php $this->assign('listing_unique_id', $this->_tpl_vars['listing']->getUniqueId()); ?>
                         	 <a href="<?php echo $this->_tpl_vars['VH']->site_url("listings/".($this->_tpl_vars['listing_unique_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_LISTING_LOOK_FRONTEND']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/house_go.png" /></a>
                         	 <?php endif; ?>
                         	 <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/view/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_VIEW_LISTING_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/page.png"></a>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/edit/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_EDIT_LISTING_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/page_edit.png"></a>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/delete/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_DELETE_LISTING_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/page_delete.png"></a>
                             <?php if ($this->_tpl_vars['listing']->level->images_count > 0): ?>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/images/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_LISTING_IMAGES_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/images.png"></a>
                             <?php endif; ?>
                             <?php if ($this->_tpl_vars['listing']->level->video_count > 0): ?>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/videos/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_LISTING_VIDEOS_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/videos.png"></a>
                             <?php endif; ?>
                             <?php if ($this->_tpl_vars['listing']->level->files_count > 0): ?>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/files/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_LISTING_FILES_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/page_link.png"></a>
                             <?php endif; ?>
                             <?php if (( $this->_tpl_vars['system_settings']['google_analytics_profile_id'] && $this->_tpl_vars['system_settings']['google_analytics_email'] && $this->_tpl_vars['system_settings']['google_analytics_password'] ) && ( $this->_tpl_vars['content_access_obj']->isPermission('View all statistics') || ( $this->_tpl_vars['content_access_obj']->isPermission('View self statistics') && $this->_tpl_vars['content_access_obj']->checkListingAccess($this->_tpl_vars['listing_id']) ) )): ?>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/statistics/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_LISTING_STATISTICS_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/chart_bar.png"></a>
                             <?php endif; ?>
                             <?php if ($this->_tpl_vars['listing']->level->ratings_enabled && $this->_tpl_vars['content_access_obj']->isPermission('Manage all ratings')): ?>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/ratings/listings/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_LISTING_RATINGS_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/star.png"></a>
                             <?php endif; ?>
                             <?php if ($this->_tpl_vars['listing']->level->reviews_mode && $this->_tpl_vars['listing']->level->reviews_mode != 'disabled' && $this->_tpl_vars['content_access_obj']->isPermission('Manage all reviews')): ?>
                             <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/reviews/listings/".($this->_tpl_vars['listing_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_LISTING_REVIEWS_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/comments.png"></a>
                             <?php endif; ?>
                            </nobr>
                         </td>
                       </tr>
                       <?php endforeach; endif; unset($_from); ?>
                     </table>
                     <?php echo $this->_tpl_vars['LANG_WITH_SELECTED']; ?>
:
	                 <select name="table_action" onchange="action_cmd=this.options[this.selectedIndex].value; submit_listings_form(); this.form.submit()">
	                 	<option value=""><?php echo $this->_tpl_vars['LANG_CHOOSE_ACTION']; ?>
</option>
	                 	<option value="delete"><?php echo $this->_tpl_vars['LANG_BUTTON_DELETE_LISTINGS']; ?>
</option>
	                 	<option value="activate"><?php echo $this->_tpl_vars['LANG_BUTTON_ACTIVATE_LISTINGS']; ?>
</option>
	                 	<option value="block"><?php echo $this->_tpl_vars['LANG_BUTTON_BLOCK_LISTINGS']; ?>
</option>
	                 </select>
                     </form>
                     <?php echo $this->_tpl_vars['paginator']; ?>

                     <?php endif; ?>
                </div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>