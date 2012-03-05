<?php /* Smarty version 2.6.26, created on 2012-02-06 02:23:50
         compiled from frontend/search_block.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'addslashes', 'frontend/search_block.tpl', 22, false),array('function', 'render_frontend_block', 'frontend/search_block.tpl', 249, false),array('modifier', 'cat', 'frontend/search_block.tpl', 249, false),)), $this); ?>
<?php if (( ! $this->_tpl_vars['current_type'] ) || ( $this->_tpl_vars['current_type'] && $this->_tpl_vars['current_type']->search_type != 'disabled' )): ?>
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
							},
							focus: function(event, ui) {
								$(this).val(ui.item.label);
								return false;
							},
							select: function(event, ui) {
								$(this).val(ui.item.label);
								if (ui.item.label != ui.item.value)
									$("#predefined_location_id").val(ui.item.value);
								return false;
							},
							minLength: 2,
							delay: 600
						});
                		$('#where_search').keyup(function() {
                			$("#predefined_location_id").val('');
                		});
		                $('#where_radius_slider').slider({
							min: 0,
							max: 10,
<?php $this->assign('default_radius', 0); ?>
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
                			if (parseInt($("#where_radius").val())>0) {
                				global_js_url = global_js_url + 'where_radius/' + $("#where_radius").val() + '/';
                			}
                		}
                		if ($("#predefined_location_id").val() != '') {
                			global_js_url = global_js_url + 'predefined_location_id/' + urlencode($("#predefined_location_id").val()) + '/';
                		}
                		<?php endif; ?>

                		<?php if (! $this->_tpl_vars['system_settings']['single_type_structure']): ?>
                		if ($("#search_type").val() != '0') {
                			global_js_url = global_js_url + 'search_type/' + $("#search_type").val() + '/';
                		}
    					<?php endif; ?>
                		
                		<?php if (( ( $this->_tpl_vars['system_settings']['single_type_structure'] || ! $this->_tpl_vars['current_type'] ) && $this->_tpl_vars['system_settings']['global_categories_search'] ) || ( ! $this->_tpl_vars['system_settings']['single_type_structure'] && $this->_tpl_vars['current_type'] && $this->_tpl_vars['current_type']->categories_search )): ?>
                		if ($("#search_category").val() != '') {
                			global_js_url = global_js_url + 'search_category/' + $("#search_category").val() + '/';
                		}
                		<?php endif; ?>

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
', {type_id: $("#search_type").val(), args: '<?php echo smarty_function_addslashes(array('string' => $this->_tpl_vars['VH']->json_encode($this->_tpl_vars['args'])), $this);?>
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
                });
                </script>

                	 <div class="px5"></div>
                     <form id="search_form" action="" method="post">
                     	<?php if (! $this->_tpl_vars['system_settings']['single_type_structure'] && $this->_tpl_vars['current_type'] && $this->_tpl_vars['current_type']->categories_type == 'local'): ?>
                     	<input type="hidden" id="search_type" name="search_type" value="<?php echo $this->_tpl_vars['current_type']->id; ?>
" />
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
	                     			<input type="hidden" name="predefined_location_id" id="predefined_location_id" value="<?php if ($this->_tpl_vars['args']['predefined_location_id']): ?><?php echo $this->_tpl_vars['args']['predefined_location_id']; ?>
<?php endif; ?>" />
	                     		</div>
                     			<div id="where_advanced_block" style="display: none;">
                     				<div class="px5"></div>
                     				<?php echo $this->_tpl_vars['LANG_SEARCH_IN_RADIUS']; ?>

									<input type="text" id="where_radius_label" value="<?php if ($this->_tpl_vars['args']['where_radius']): ?><?php echo $this->_tpl_vars['args']['where_radius']; ?>
<?php else: ?><?php echo $this->_tpl_vars['default_radius']; ?>
<?php endif; ?>" size="1" maxlength="2" disabled />
									<input type="hidden" name="where_radius" id="where_radius" value="<?php if ($this->_tpl_vars['args']['where_radius']): ?><?php echo $this->_tpl_vars['args']['where_radius']; ?>
<?php else: ?><?php echo $this->_tpl_vars['default_radius']; ?>
<?php endif; ?>" />
									<?php if ($this->_tpl_vars['system_settings']['search_in_raduis_measure'] == 'miles'): ?>
										<?php echo $this->_tpl_vars['LANG_SEARCH_IN_RADIUS_MILES']; ?>

									<?php else: ?>
										<?php echo $this->_tpl_vars['LANG_SEARCH_IN_RADIUS_KILOMETRES']; ?>

									<?php endif; ?>
									<div id="where_radius_slider"></div>
	                     		</div>
                     		</div>
                     		<?php endif; ?>

	                     	<div id="search_button">
                     			<input type="submit" name="submit" class="front-btn" value="<?php echo $this->_tpl_vars['LANG_BUTTON_SEARCH_LISTINGS']; ?>
" />
                     		</div>
                     		
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
                     		<?php echo $this->_tpl_vars['search_fields']->inputMode($this->_tpl_vars['args']); ?>

                     	</div>
                     	<div class="clear_float"></div>
                     	<div id="advanced_search_block" style="display: none;"></div>
                     	<div class="clear_float"></div>
                     </form>
<?php endif; ?>

<?php if (( ! $this->_tpl_vars['current_type'] ) || ( $this->_tpl_vars['current_type'] && $this->_tpl_vars['current_type']->locations_enabled )): ?>
	<div class="px5"></div>
	<?php echo smarty_function_render_frontend_block(array('block_type' => 'locations','block_template' => 'frontend/blocks/locations_navigation.tpl','is_counter' => true,'is_only_labeled' => true,'max_depth' => 3), $this);?>

<?php endif; ?>

<div class="px5"></div>