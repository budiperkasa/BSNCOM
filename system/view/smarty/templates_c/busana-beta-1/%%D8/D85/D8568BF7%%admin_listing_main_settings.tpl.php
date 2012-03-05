<?php /* Smarty version 2.6.26, created on 2012-02-06 04:25:24
         compiled from listings/admin_listing_main_settings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'addslashes', 'listings/admin_listing_main_settings.tpl', 12, false),array('function', 'translate_content', 'listings/admin_listing_main_settings.tpl', 502, false),array('function', 'render_frontend_block', 'listings/admin_listing_main_settings.tpl', 596, false),array('modifier', 'count', 'listings/admin_listing_main_settings.tpl', 190, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $this->assign('listing_id', $this->_tpl_vars['listing']->id); ?>

                <script language="javascript" type="text/javascript"><!--
                var select_icons_window_url = "<?php echo $this->_tpl_vars['VH']->site_url("admin/categories/select_icons_for_listings/"); ?>
";
                <?php if ($this->_tpl_vars['listing']->type->categories_type == 'global'): ?>
                var typeid_url_part = "";
                <?php elseif ($this->_tpl_vars['listing']->type->categories_type == 'local'): ?>
                var typeid_url_part = "/type_id/<?php echo $this->_tpl_vars['listing']->type->id; ?>
/";
                <?php endif; ?>

                var global_title = '<?php echo smarty_function_addslashes(array('string' => $this->_tpl_vars['listing']->title()), $this);?>
';
			    var global_logo = '<?php echo $this->_tpl_vars['listing']->logo_file; ?>
';
			    var global_server_path = '<?php echo $this->_tpl_vars['users_content']; ?>
';

			    var global_map_icons_path = '<?php echo $this->_tpl_vars['public_path']; ?>
map_icons/';
			    var coords_by_click = true;

			    <?php if ($this->_tpl_vars['listing']->type->locations_enabled && $this->_tpl_vars['listing']->level->locations_number): ?>
			    var selected_location_num = 0;
			    <?php $this->assign('locations_array', $this->_tpl_vars['listing']->locations_array()); ?>
			    <?php $this->assign('location', $this->_tpl_vars['locations_array'][0]); ?>
                <?php if ($this->_tpl_vars['location']->id != 'new'): ?>
			    var selected_location_id = <?php echo $this->_tpl_vars['location']->id; ?>
;
			    <?php else: ?>
			    var selected_location_id = <?php echo $this->_tpl_vars['location']->virtual_id; ?>
;
			    <?php endif; ?>
			    <?php endif; ?>

                var selected_category_id = 0;
                var categories_limit = <?php echo $this->_tpl_vars['listing']->level->categories_number; ?>
;
                var selected_categories_count = 0;
                var categories = new Array();
                var serialized_categories = '';
                
                var locations_limit = <?php echo $this->_tpl_vars['listing']->level->locations_number; ?>
;

                var description_max_size = <?php echo $this->_tpl_vars['listing']->level->description_length; ?>
;
                
                var use_districts = <?php echo $this->_tpl_vars['system_settings']['geocoded_locations_mode_districts']; ?>
;
                var use_provinces = <?php echo $this->_tpl_vars['system_settings']['geocoded_locations_mode_provinces']; ?>
;

                $(document).ready(function() {
                	<?php if ($this->_tpl_vars['content_access_obj']->isPermission('Edit listings expiration date') && ( ! $this->_tpl_vars['listing']->level->eternal_active_period || $this->_tpl_vars['listing']->level->allow_to_edit_active_period )): ?>
                	$("#expiration_date").datepicker({
						showOn: "both",
						buttonImage: "<?php echo $this->_tpl_vars['public_path']; ?>
images/calendar.png",
						buttonImageOnly: true
                    });
                    $("#expiration_date").datepicker('setDate', $.datepicker.parseDate('yy-mm-dd', '<?php echo $this->_tpl_vars['listing']->expiration_date; ?>
'));
                    $("#expiration_date").datepicker("option", $.datepicker.regional["<?php echo $this->_tpl_vars['current_language']; ?>
"]);
                    <?php endif; ?>
                
                	buildAccordionAndLocationAutocomplete();

				    <?php if ($this->_tpl_vars['listing']->level->description_mode == 'enabled'): ?>
				    ///////////////////////////////////////////////////////////////////////////////////
				    // Listing description chars counter
                    $("#listing_description_symbols_left").html(String(description_max_size - $("#listing_description").val().length));
                    $("#listing_description").keyup( function() {
						var left;
						left = description_max_size - $("#listing_description").val().length;
						if (left >= 0) {
							$("#listing_description_symbols_left").html(String(left));
						} else {
							alert("<?php echo smarty_function_addslashes(array('string' => $this->_tpl_vars['LANG_LISTING_DESCRIPTION_LIMIT_ERROR']), $this);?>
");
							text = $("#listing_description").val();
							$("#listing_description").val(text.substr(0, description_max_size));
						}
					});
					<?php endif; ?>

					<?php if ($this->_tpl_vars['listing']->type->categories_type != 'disabled'): ?>
					///////////////////////////////////////////////////////////////////////////////////
					// Load categories array into tree
                    <?php $_from = $this->_tpl_vars['listing']->categories_array(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['category']):
?>
						categories[<?php echo $this->_tpl_vars['category']->id; ?>
] = <?php echo $this->_tpl_vars['category']->id; ?>
;
						selected_categories_count++;
					<?php endforeach; endif; unset($_from); ?>
					serializeCategories();

					<?php if ($this->_tpl_vars['listing']->type->locations_enabled && $this->_tpl_vars['listing']->level->locations_number && $this->_tpl_vars['listing']->level->maps_enabled): ?>
					$.post('<?php echo $this->_tpl_vars['VH']->site_url("ajax/categories/is_icons"); ?>
'+typeid_url_part, {'categories_list': PHPSerializer.serialize(categories), 'selected_icons': serializeIcons()}, function(data) {showHideMarkerIconsBtn(data); }, "json");
					<?php endif; ?>

                    $(".categories_item").live("click", function() {
                        id =$(this).attr('id');
	                    $('#' + selected_category_id).css('background-color', '#FFFFFF');
	                    $('#' + id).css('background-color', '#90C2E1');
	                    selected_category_id = id;
                    });

                    ///////////////////////////////////////////////////////////////////////////////////
                    // Delete category item
                    $("#delete_category_from_list").click(function() {
                        if (selected_categories_count > 0 && selected_category_id != 0) {
                        	ajax_loader_show("Deleting category...");
                            for (i = 0; i < categories.length; i++) {
                                if ("category_" + categories[i] == selected_category_id) {
                                    $("#" + selected_category_id).remove();
                                    delete categories[i];
                                    serializeCategories();
                                    selected_categories_count--;
                                    selected_category_id = 0;
                                }
                            }
                            <?php if ($this->_tpl_vars['listing']->type->locations_enabled && $this->_tpl_vars['listing']->level->locations_number && $this->_tpl_vars['listing']->level->maps_enabled): ?>
                            $.post('<?php echo $this->_tpl_vars['VH']->site_url("ajax/categories/is_icons"); ?>
'+typeid_url_part, {'categories_list': PHPSerializer.serialize(categories), 'selected_icons': serializeIcons()}, function(data) {showHideMarkerIconsBtn(data); generateMap(); }, "json");
                            <?php else: ?>
                            ajax_loader_hide();
                            <?php endif; ?>
                        }
                    });
                    <?php endif; ?>

                    ///////////////////////////////////////////////////////////////////////////////////
                    // Open manual coordinates form
                    $(".manual_coords").live('click', function() {
                    	if ($(this).is(":checked"))
                    		$(this).parent().parent().find(".manual_coords_block").show(200);
                    	else
                    		$(this).parent().parent().find(".manual_coords_block").hide(200);
                    });
                    
                    <?php if ($this->_tpl_vars['system_settings']['predefined_locations_mode'] && $this->_tpl_vars['system_settings']['predefined_locations_mode'] != 'disabled' && $this->_tpl_vars['system_settings']['predefined_locations_mode'] != 'only'): ?>
                    ///////////////////////////////////////////////////////////////////////////////////
                    // Open predefined locations drop boxes
                    $(".use_predefined_locations").live('click', function() {
                    	if ($(this).is(":checked")) {
                    		$(this).parent().parent().find(".location_drop_boxes").show(200);
                    		$(this).parent().parent().find(".location_string").hide(200);
                    	} else {
                    		$(this).parent().parent().find(".location_string").show(200);
                    		$(this).parent().parent().find(".location_drop_boxes").hide(200);
                    	}
                    });
                    <?php endif; ?>

                    <?php if (! $this->_tpl_vars['system_settings']['predefined_locations_mode'] || ( $this->_tpl_vars['system_settings']['predefined_locations_mode'] && $this->_tpl_vars['system_settings']['predefined_locations_mode'] != 'only' )): ?>
					///////////////////////////////////////////////////////////////////////////////////
                    // Work with geocoded names
                    $(".geocoded_names_options").live('click', function() {
	            		$(".geocoded_name:eq("+selected_location_num+")").val($('input[name=geocoded_names_options_'+selected_location_num+']:checked').val());
					});
                    var response = (function(val, location_index) {
                    	if (!location_index)
                    		location_index = selected_location_num;
                    	ajax_loader_hide();
	            		this.res = val;
	            		if (val) {
	            			var suggestions_string = '<div style="padding: 5px 0 5px"><b><?php echo $this->_tpl_vars['LANG_GEO_NAME_DESCR']; ?>
<'+'/b>';
	            			for (var i=0; i<this.res.length; i++) {
	            				if (this.res.length == 1)
	            					$(".geocoded_name:eq("+location_index+")").val(this.res[i].label);

	            				if (i==0 || $(".geocoded_name:eq("+location_index+")").val()==this.res[i].label)
	            					var str_checked = 'checked';
	            				else
	            					var str_checked = '';
	            				suggestions_string = suggestions_string + '<label><input type="radio" name="geocoded_names_options_' + location_index + '" class="geocoded_names_options" ' + str_checked + ' value="' + this.res[i].label + '" /> ' + this.res[i].label + '<'+'/label>';
	            			}
	            			suggestions_string = suggestions_string + '<'+'/div>';
	            			$(".geocoded_suggestions:eq("+location_index+")").html(suggestions_string).show();
	            		}
	            	});

					$(".location").each(function(i, val) {
						if ($(".location:eq("+i+")").val()) {
							ajax_loader_show('GeoCoding...');
                    		geocodeAddress($(".location:eq("+i+")").val(), '<?php echo $this->_tpl_vars['system_settings']['default_language']; ?>
', response, false, i);
                    	}
                    });

                    $('.location').live('keyup', function() {
                    	var keyup_location = $(this);
	            		delay(function() {
	            			if (keyup_location.val()) {
		            			ajax_loader_show('GeoCoding...');
		            			geocodeAddress(keyup_location.val(), '<?php echo $this->_tpl_vars['system_settings']['default_language']; ?>
', response, false, false);
		            		} else {
	            				$(".geocoded_suggestions:eq("+selected_location_num+")").html('').hide();
	            				$(".geocoded_name:eq("+selected_location_num+")").val('');
	            			}
	            		}, 3500);
	            	});
                    <?php endif; ?>
                    
                    ///////////////////////////////////////////////////////////////////////////////////
                    // Show delete address button, only if there are more than 1 address
                    <?php if (count($this->_tpl_vars['listing']->locations_array()) > 1): ?>
                    $(".delete_location").show();
                    <?php endif; ?>
                    
                    ///////////////////////////////////////////////////////////////////////////////////
                    // Adds new locations block into accordion
                    $(".add_location").click(function() {
                    	$('#locations_accordion').accordion('destroy');
                    	$('.location').autocomplete('destroy');
                    	$("#locations_accordion").append($("#locations_accordion>h3:first").clone());

                    	var i = 1;
						$(".location_number").each(function() {
							$(this).html(i);
							i++;
						});
						
						$(".delete_location").show();

                    	var location = $("#locations_accordion>div:first").clone();
                    	var locations_blocks = location.find('input, select');
                    	var ddate = new Date();
                    	var virtual_location_id = parseInt($(".location_id").last().val())+ddate.getTime();
                    	locations_blocks.each( function() {
                    		// clear any value of location block,
                    		// except zoom - as it is the same for any location
                    		if (!$(this).is(".map_zoom"))
	                    		$(this).val('');

	                    	// it will be virtual location id while it isn't saved
	                    	if ($(this).is(".location_id"))
	                    		$(this).val(virtual_location_id);
	                    	if ($(this).is(".manual_coords")) {
	                    		$(this).val(virtual_location_id);
		                    	// clear manual coords block
		                    	$(this).removeAttr("checked");
	                    	}
	                    	$(this).removeAttr("disabled");
		                    $(this).removeAttr("selected");
	                    	if ($(this).is(".use_predefined_locations")) {
	                    		$(this).val(virtual_location_id);
	                    		<?php if ($this->_tpl_vars['system_settings']['predefined_locations_mode'] && ( $this->_tpl_vars['system_settings']['predefined_locations_mode'] == 'prefered' || $this->_tpl_vars['system_settings']['predefined_locations_mode'] == 'only' )): ?>
		                    	$(this).attr("checked", "checked");
		                    	<?php else: ?>
		                    	$(this).removeAttr("checked");
		                    	<?php endif; ?>
	                    	}
	                    	$(this).parent().parent().find(".manual_coords_block").hide();
	                    	// increment name and id attributes of inputs from their class
	                    	var id = $(this).attr('class')+'['+(i-1)+']';
	                    	$(this).attr('id', id);
	               		});
	               		// Clear geocoded suggestions list for new accordion's tab 
	               		location.find('.geocoded_suggestions').html('');

	               		<?php if ($this->_tpl_vars['CI']->load->is_module_loaded('i18n')): ?>
	               		///////////////////////////////////////////////////////////////////////////////////
                    	// content translation links
	               		$.post('<?php echo $this->_tpl_vars['VH']->site_url("ajax/languages/get_translation_link/listings_in_locations/location/new/string/"); ?>
'+virtual_location_id,
	                    function(data) {
	                    	location.find(".translate_location").html(data);
	                    });
	                    $.post('<?php echo $this->_tpl_vars['VH']->site_url("ajax/languages/get_translation_link/listings_in_locations/address_line_1/new/string/"); ?>
'+virtual_location_id,
	                    function(data) {
	                    	location.find(".translate_address_line_1").html(data);
	                    });
	                    $.post('<?php echo $this->_tpl_vars['VH']->site_url("ajax/languages/get_translation_link/listings_in_locations/address_line_2/new/string/"); ?>
'+virtual_location_id,
	                    function(data) {
	                    	location.find(".translate_address_line_2").html(data);
	                    });
	                    <?php endif; ?>
	                    
	                    <?php if ($this->_tpl_vars['system_settings']['predefined_locations_mode'] && ( $this->_tpl_vars['system_settings']['predefined_locations_mode'] == 'prefered' || $this->_tpl_vars['system_settings']['predefined_locations_mode'] == 'only' )): ?>
	                    location.find(".location_drop_boxes").show();
	                    location.find(".location_string").hide();
	                    <?php else: ?>
	                    location.find(".location_drop_boxes").hide();
	                    location.find(".location_string").show();
	                    <?php endif; ?>
	                    location.find(".location_dropdown_list").each(function() {
	                    	if ($(this).attr("order_num") != 1)
	                    		$(this).find("option").each(function() {
	                    			if ($(this).val())
	                    				$(this).remove();
	                    		});
	                    });

                    	$("#locations_accordion").append(location);
                    	
                    	///////////////////////////////////////////////////////////////////////////////////
                    	// check icons for new location
                    	<?php if ($this->_tpl_vars['listing']->type->categories_type != 'disabled' && $this->_tpl_vars['listing']->type->locations_enabled && $this->_tpl_vars['listing']->level->locations_number && $this->_tpl_vars['listing']->level->maps_enabled): ?>
						$.post('<?php echo $this->_tpl_vars['VH']->site_url("ajax/categories/is_icons"); ?>
'+typeid_url_part, {'categories_list': PHPSerializer.serialize(categories), 'selected_icons': serializeIcons()}, function(data) {showHideMarkerIconsBtn(data); }, "json");
						<?php endif; ?>

                    	buildAccordionAndLocationAutocomplete();

                    	if (locations_limit <= $("#locations_accordion>div").length)
                    		$(".add_location").hide();
						
                    	return false;
                    });

                    <?php if ($this->_tpl_vars['listing']->type->locations_enabled && $this->_tpl_vars['listing']->level->locations_number): ?>
                    ///////////////////////////////////////////////////////////////////////////////////
                    // Delete locations block from accordion
                    $(".delete_location").live('click', function() {
                    	$('#locations_accordion').accordion('destroy');
                    	$('.location').autocomplete('destroy');
                    	$(this).parent().prev().remove();
                    	$(this).parent().remove();
                    	
                    	var i = 1;
						$(".location_number").each(function() {
							$(this).html(i);
							i++;
						});
						if (i == 2) {
							$(".delete_location").hide();
						}

						$.each($("#locations_accordion>div"), function(i, val) {
							var locations_blocks = $("#locations_accordion>div:eq("+i+")").find('input');
	                    	locations_blocks.each( function() {
	                    		var id = $(this).attr('class')+'['+(i+1)+']';
	                    		$(this).attr('id', id);
	               			});
	               		});
	               		
	               		buildAccordionAndLocationAutocomplete();
	               		
	               		$('#locations_accordion').accordion("activate", 0);
	               		
	               		<?php if ($this->_tpl_vars['listing']->type->locations_enabled && $this->_tpl_vars['listing']->level->locations_number && $this->_tpl_vars['listing']->level->maps_enabled): ?>
	               		// Need to clear map zoom field
	               		$(".map_zoom").val('');
						generateMap();
						<?php endif; ?>

						if (locations_limit > $("#locations_accordion>div").length)
                    		$(".add_location").show();

                    	return false;
                    });
                    <?php endif; ?>
                    
                    <?php if ($this->_tpl_vars['listing']->level->categories_number): ?>
                    $("#suggest_category_link").click(function() {
                    	$("#suggest_category_block").slideToggle();
                    });
                    <?php endif; ?>
                });
                
                function buildAccordionAndLocationAutocomplete() {
                	<?php if ($this->_tpl_vars['listing']->type->locations_enabled && $this->_tpl_vars['listing']->level->locations_number): ?>
                	$("#locations_accordion").accordion({
						autoHeight: false,
						change: function(event, ui) {
							// Set location ID and number of the current selected tab
							selected_location_num = $("#locations_accordion").accordion("option", "active");
							selected_location_id = $(".location_id:eq("+selected_location_num+")").val();
							$("#selected_location_num").val(selected_location_num);
						}
					});

					var geocoder = new google.maps.Geocoder();
					$('.location').autocomplete({
	                	source: function(request, response) {
	                		geocodeAddress(request.term, '<?php echo $this->_tpl_vars['system_settings']['default_language']; ?>
', response, false, false);
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
	                <?php endif; ?>
				}
                
                ///////////////////////////////////////////////////////////////////////////////////
                // Add category item,
                // calls from categories tree
				function addCategory(node) {
					// Check categories count limit?
					if (selected_categories_count < categories_limit) {
						selected_cat_id = ($(node).parent().parent().attr("id")+"").replace('category_in_listing_', '');
						cat_bool = true;
						// does it already in list?
						for (i = 0; i < categories.length; i++) {
							if (categories[i] == selected_cat_id)
								cat_bool = false;
						}

						if (cat_bool) {
							ajax_loader_show("Category addition...");
							$.post('<?php echo $this->_tpl_vars['VH']->site_url("ajax/categories/get_categories_path"); ?>
'+typeid_url_part, {'category_id': selected_cat_id, 'categories_list': PHPSerializer.serialize(categories)}, addCategoryIntoList, "json");
						} else {
							alert('<?php echo smarty_function_addslashes(array('string' => $this->_tpl_vars['LANG_LISTING_DUBLE_CATEGORY_ERROR']), $this);?>
');
						}
					} else {
						alert('<?php echo smarty_function_addslashes(array('string' => $this->_tpl_vars['LANG_LISTING_CATEGORY_LIMIT_ERROR']), $this);?>
');
					}
					return false;
				}
				
				///////////////////////////////////////////////////////////////////////////////////
				// Adds category into html list
				function addCategoryIntoList(data) {
					$('<div id="category_' + data.selected_cat_id + '" class="categories_item" unselectable="on">' + data.selected_cat_name + '<'+'/div>').appendTo("#selected_categories_list");
					categories[data.selected_cat_id] = data.selected_cat_id;
					selected_categories_count++;
					serializeCategories();
					<?php if ($this->_tpl_vars['listing']->type->locations_enabled && $this->_tpl_vars['listing']->level->locations_number && $this->_tpl_vars['listing']->level->maps_enabled): ?>
					showHideMarkerIconsBtn(data);
					generateMap();
					<?php endif; ?>
					ajax_loader_hide();
				}
				
				function showHideMarkerIconsBtn(data) {
					<?php if ($this->_tpl_vars['listing']->type->locations_enabled && $this->_tpl_vars['listing']->level->locations_number && $this->_tpl_vars['listing']->level->maps_enabled): ?>
					var is_selected_icons = PHPSerializer.unserialize(data.is_selected_icons);
					for (var i=0; i<is_selected_icons.length; i++) {
						if ($(".map_icon_id:eq("+i+")").val() && !is_selected_icons[i]) {
							$(".map_icon_id:eq("+i+")").val('');
							$(".map_icon_file:eq("+i+")").val('');
						}
					}
					if (!data.is_icons) {
						$(".icons_btn").hide();
						$(".map_icon_id").val('');
						$(".map_icon_file").val('');
					} else {
						$(".icons_btn").show();
					}
					if (data.single_icon) {
						$(".map_icon_id").val(data.single_icon);
						$(".map_icon_file").val(data.single_icon_file);
						$("#single_icon").val(true);
					} else {
						$("#single_icon").val(false);
					}
					<?php endif; ?>
				}

                function serializeCategories() {
                    categories = jQuery.grep(categories, function(value) {
                    	return value != undefined;
                    });
                    serialized_categories = categories.join(',');
                    if (categories.length)
                    	$('#serialized_categories_list').attr('value', PHPSerializer.serialize(categories));
                    else
                    	$('#serialized_categories_list').attr('value', '');
                }
                
                function serializeIcons() {
                	var icons = new Array();
                	$.each($(".map_icon_id"), function(index, value) {
                		icons[index] = $(this).val();
                	});
                	return PHPSerializer.serialize(icons);
                }
                --></script>

                <div class="content">
                	<?php echo $this->_tpl_vars['VH']->validation_errors(); ?>

                	<?php if ($this->_tpl_vars['listing_id'] == 'new'): ?>
                    <h3><?php echo $this->_tpl_vars['LANG_CREATE_LISTING_1']; ?>
 "<?php echo $this->_tpl_vars['type_name']; ?>
" <?php echo $this->_tpl_vars['LANG_CREATE_LISTING_2']; ?>
 "<?php echo $this->_tpl_vars['level_name']; ?>
"</h3>
                    <h4><?php echo $this->_tpl_vars['LANG_CREATE_STEP2']; ?>
</h4>
                    <?php else: ?>
                    <h3><?php echo $this->_tpl_vars['LANG_EDIT_LISTING_1']; ?>
 "<?php echo $this->_tpl_vars['type_name']; ?>
" <?php echo $this->_tpl_vars['LANG_EDIT_LISTING_2']; ?>
 "<?php echo $this->_tpl_vars['level_name']; ?>
"</h3>
                    <?php endif; ?>

                    <?php if ($this->_tpl_vars['listing']->id != 'new'): ?>
                    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "listings/admin_listing_options_menu.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                    <?php endif; ?>

                    <form action="" method="post">
                    <input type="hidden" name="last_page" value="<?php echo $this->_tpl_vars['last_page']; ?>
">
                    <input type="hidden" name="id" value="<?php echo $this->_tpl_vars['listing']->id; ?>
">
                    <input type="hidden" id="serialized_categories_list" name="serialized_categories_list">
                    
                    <?php if ($this->_tpl_vars['content_access_obj']->isPermission('Manage ability to claim')): ?>
                    <label class="block_title"><?php echo $this->_tpl_vars['LANG_LISTING_ABILITY_TO_CLAIM']; ?>
</label>
					<div class="admin_option">
						<?php if (! $this->_tpl_vars['listing']->claim_row || ! $this->_tpl_vars['listing']->claim_row['approved']): ?>
							<?php if (! $this->_tpl_vars['listing']->claim_row || $this->_tpl_vars['listing']->claim_row['ability_to_claim']): ?>
								<label><input type="checkbox" value="1" name="ability_to_claim" <?php if ($this->_tpl_vars['listing']->claim_row['ability_to_claim']): ?>checked<?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_LISTING_ABILITY_TO_CLAIM_DESCR']; ?>
</label>
							<?php elseif ($this->_tpl_vars['listing']->claim_row['to_user_id'] && $this->_tpl_vars['listing']->claim_user): ?>
								<?php $this->assign('user_id', $this->_tpl_vars['listing']->claim_user->id); ?>
								<?php echo $this->_tpl_vars['LANG_LISTING_CLAIMED_BY']; ?>
 <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/users/view/".($this->_tpl_vars['user_id'])); ?>
"><?php echo $this->_tpl_vars['listing']->claim_user->login; ?>
</a><br />
								<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/approve_claim/".($this->_tpl_vars['listing_id'])); ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/accept.png" /> <?php echo $this->_tpl_vars['LANG_LISTING_CLAIME_APPROVE']; ?>
</a> <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/decline_claim/".($this->_tpl_vars['listing_id'])); ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/delete.png" /> <?php echo $this->_tpl_vars['LANG_LISTING_CLAIME_DECLINE']; ?>
</a>
							<?php endif; ?>
						<?php elseif ($this->_tpl_vars['listing']->claim_user): ?>
							<?php echo $this->_tpl_vars['LANG_LISTING_CLAIME_DELEGATED_TO']; ?>
 <?php echo $this->_tpl_vars['listing']->claim_user->login; ?>
 (<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/decline_claim/".($this->_tpl_vars['listing_id'])); ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/icons/delete.png" /> <?php echo $this->_tpl_vars['LANG_LISTING_CLAIM_ROLL_BACK']; ?>
</a>)
						<?php endif; ?>
                    </div>
					<?php endif; ?>
                    
                    <?php if ($this->_tpl_vars['listing']->level->title_enabled || $this->_tpl_vars['listing']->level->seo_title_enabled || $this->_tpl_vars['listing']->level->description_mode != 'disabled' || $this->_tpl_vars['listing']->level->meta_enabled): ?>
					<label class="block_title"><?php echo $this->_tpl_vars['LANG_LISTING_META_INFO']; ?>
</label>
					<div class="admin_option">
						<?php if ($this->_tpl_vars['listing']->level->title_enabled): ?>
						<div style="float: left;">
							<div class="admin_option_name">
								<?php echo $this->_tpl_vars['LANG_LISTING_TITLE']; ?>
<span class="red_asterisk">*</span>
								<?php echo smarty_function_translate_content(array('table' => 'listings','field' => 'title','row_id' => $this->_tpl_vars['listing_id']), $this);?>

							</div>
							<div class="admin_option_description">
								<?php echo $this->_tpl_vars['LANG_LISTING_TITLE_DESCR']; ?>

							</div>
							<input type=text name="name" value="<?php echo $this->_tpl_vars['listing']->title(); ?>
" size="45">
							<?php if ($this->_tpl_vars['listing']->level->seo_title_enabled): ?>
							&nbsp;&nbsp;<span class="seo_rewrite" title="<?php echo $this->_tpl_vars['LANG_WRITE_SEO_STYLE']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/arrow_seo.gif"></span>&nbsp;&nbsp;
							<?php endif; ?>
						</div>
						<?php endif; ?>
						<?php if ($this->_tpl_vars['listing']->level->seo_title_enabled): ?>
						<div style="float: left;">
							<div class="admin_option_name">
								<?php echo $this->_tpl_vars['LANG_LISTING_SEO_TITLE']; ?>

							</div>
							<div class="admin_option_description">
								<?php echo $this->_tpl_vars['LANG_SEO_DESCR']; ?>

							</div>
							<input type=text name="seo_name" id="seo_name" value="<?php echo $this->_tpl_vars['listing']->seo_title; ?>
" size="45">
						</div>
						<?php endif; ?>
						<div style="clear: both"></div>

						<div class="px10"></div>

						<?php if ($this->_tpl_vars['listing']->level->description_mode != 'disabled'): ?>
							<div class="admin_option_name">
								<?php echo $this->_tpl_vars['LANG_LISTING_DESCRIPTION']; ?>

								<?php echo smarty_function_translate_content(array('table' => 'listings','field' => 'listing_description','row_id' => $this->_tpl_vars['listing_id'],'field_type' => 'text'), $this);?>

							</div>
						    <?php if ($this->_tpl_vars['listing']->level->description_mode == 'enabled'): ?>
							    <div class="admin_option_description">
									<?php echo $this->_tpl_vars['LANG_LISTING_DESCRIPTION_DESCR']; ?>
 <?php echo $this->_tpl_vars['LANG_SYMBOLS_LEFT']; ?>
: <span id="listing_description_symbols_left" class="symbols_left"></span>
							    </div>
								<textarea name="listing_description" id="listing_description" cols="70" rows="8"><?php echo $this->_tpl_vars['listing']->listing_description; ?>
</textarea>
							<?php endif; ?>
							<?php if ($this->_tpl_vars['listing']->level->description_mode == 'richtext'): ?>
								<?php echo $this->_tpl_vars['listing']->description(); ?>

							<?php endif; ?>
							<div class="px10"></div>
						<?php endif; ?>

						<?php if ($this->_tpl_vars['listing']->level->meta_enabled): ?>
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_LISTING_META_DESCRIPTION']; ?>

							<?php echo smarty_function_translate_content(array('table' => 'listings','field' => 'listing_meta_description','row_id' => $this->_tpl_vars['listing_id'],'field_type' => 'text'), $this);?>

						</div>
						<div class="admin_option_description">
							<?php echo $this->_tpl_vars['LANG_LISTING_META_DESCRIPTION_DESCR']; ?>

						</div>
						<textarea name="listing_meta_description" cols="40" rows="5"><?php echo $this->_tpl_vars['listing']->listing_meta_description; ?>
</textarea>
						
						<div class="px10"></div>
						
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_LISTING_KEYWORDS']; ?>

							<?php echo smarty_function_translate_content(array('table' => 'listings','field' => 'listing_keywords','row_id' => $this->_tpl_vars['listing_id'],'field_type' => 'keywords'), $this);?>

						</div>
						<div class="admin_option_description">
							<?php echo $this->_tpl_vars['LANG_LISTING_KEYWORDS_DESCR']; ?>

						</div>
						<textarea name="listing_keywords" cols="40" rows="5"><?php echo $this->_tpl_vars['VH']->str_replace(", ","\n",$this->_tpl_vars['listing']->listing_keywords); ?>
</textarea>
						<?php endif; ?>
					</div>
					<?php endif; ?>
					
					<?php if ($this->_tpl_vars['content_access_obj']->isPermission('Edit listings expiration date')): ?>
					<label class="block_title"><?php echo $this->_tpl_vars['LANG_EXPIRATION_DATE']; ?>
</label>
					<div class="admin_option">
						<?php if (! $this->_tpl_vars['listing']->level->eternal_active_period || $this->_tpl_vars['listing']->level->allow_to_edit_active_period): ?>
							<div class="admin_option_description">
								<?php echo $this->_tpl_vars['LANG_EXPIRATION_DATE_DESCR']; ?>

							</div>
	                        <input type="text" size="10" value="" name="expiration_date" id="expiration_date"/>
                        <?php else: ?>
                        	<span class="green"><?php echo $this->_tpl_vars['LANG_ETERNAL']; ?>
</span>
                        <?php endif; ?>
                     </div>
					<?php endif; ?>

                    <?php if ($this->_tpl_vars['listing']->level->logo_enabled): ?>
                    <?php echo $this->_tpl_vars['image_upload_block']->setUploadBlock('files_upload/listing_logo_upload_block.tpl'); ?>

                    <?php endif; ?>

                    <?php if ($this->_tpl_vars['listing']->type->categories_type != 'disabled' && $this->_tpl_vars['listing']->level->categories_number): ?>
                    <label class="block_title"><?php echo $this->_tpl_vars['LANG_LISTING_CATEGORIES']; ?>
</label>
                    <div class="admin_option">
                        <div class="admin_option_name">
                        	<?php echo $this->_tpl_vars['LANG_LISTING_CATEGORIES_TREE']; ?>

                        </div>
                        <div class="admin_option_description">
                        	<?php echo $this->_tpl_vars['LANG_LISTING_CATEGORIES_DESCRIPTION']; ?>
 <b><?php echo $this->_tpl_vars['listing']->level->categories_number; ?>
</b>.
                        </div>
                        <?php echo smarty_function_render_frontend_block(array('block_type' => 'categories','block_template' => 'backend/blocks/admin_categories_in_listings.tpl','is_counter' => false,'type' => $this->_tpl_vars['listing']->type->id,'max_depth' => 'max'), $this);?>


                        <div class="px10"></div>

                        <div class="admin_option_name">
                        	<?php echo $this->_tpl_vars['LANG_LISTING_SELECTED_CATEGORIES']; ?>
<span class="red_asterisk">*</span>
                        </div>
                        <div class="admin_option_description">
                        	<?php echo $this->_tpl_vars['LANG_LISTING_SELECTED_CATEGORIES_DESCR']; ?>

                        </div>
                        <div id="selected_categories_list">
                        	<?php $_from = $this->_tpl_vars['listing']->categories_array(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['category']):
?>
                        	<div id="category_<?php echo $this->_tpl_vars['category']->id; ?>
" class="categories_item" unselectable="on">
                        		<?php echo $this->_tpl_vars['category']->getChainAsString(); ?>

                        	</div>
                        	<?php endforeach; endif; unset($_from); ?>
                        </div>
                        <br />
                        <input type="button" class="button delete_button" id="delete_category_from_list" value="<?php echo $this->_tpl_vars['LANG_BUTTON_DELETE_FROM_LIST']; ?>
">

                        <div class="px10"></div>

                        <a href="<?php echo $this->_tpl_vars['VH']->site_url('ajax/categories/send_suggestion/'); ?>
" class="nyroModal noborder" title="<?php echo $this->_tpl_vars['LANG_SUGGEST_CATEGORY']; ?>
"><?php echo $this->_tpl_vars['LANG_SUGGEST_CATEGORY']; ?>
</a>
                    </div>
                    <?php endif; ?>

                    <?php if ($this->_tpl_vars['listing']->type->locations_enabled && $this->_tpl_vars['listing']->level->locations_number): ?>
                    <input type="hidden" id="sssssss" value="1">
                    <input type="hidden" id="selected_location_num" value="0">
                    <label class="block_title"><?php echo $this->_tpl_vars['LANG_LISTING_LOCATIONS']; ?>
</label>
                    <div class="admin_option">
                        <div class="px10"></div>
                        <div id="locations_accordion">
                        <?php $this->assign('i', 1); ?>
                        <?php $_from = $this->_tpl_vars['listing']->locations_array(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['location']):
?>
                        	<!-- location_number - position required for jquery selector -->
	                        <h3><a href="javascript: void(0);"><?php echo $this->_tpl_vars['LANG_LISTING_ADDRESS']; ?>
 <span class="location_number"><?php echo $this->_tpl_vars['i']++; ?>
</span></a></h3>
	                        <div class="location_block">
	                        	<!-- location_id - position required for jquery selector -->
	                        	<?php if ($this->_tpl_vars['location']->id != 'new'): ?>
	                        	<input type="hidden" name="location_id[]" class="location_id" value="<?php echo $this->_tpl_vars['location']->id; ?>
">
	                        	<?php else: ?>
		                        <input type="hidden" name="location_id[]" class="location_id" value="<?php echo $this->_tpl_vars['location']->virtual_id; ?>
">
		                        <?php endif; ?>

		                        <div>
									<?php if ($this->_tpl_vars['system_settings']['predefined_locations_mode'] && $this->_tpl_vars['system_settings']['predefined_locations_mode'] != 'disabled'): ?>
			                        <div class="location_drop_boxes" <?php if (! $this->_tpl_vars['location']->use_predefined_locations): ?>style="display:none"<?php endif; ?>>
			                        	<?php echo $this->_tpl_vars['location']->renderDropBoxes(); ?>

			                        	<div class="px4"></div>
			                        </div>
			                        <?php endif; ?>

			                        <?php if (! $this->_tpl_vars['system_settings']['predefined_locations_mode'] || ( $this->_tpl_vars['system_settings']['predefined_locations_mode'] && $this->_tpl_vars['system_settings']['predefined_locations_mode'] != 'only' )): ?>
									<div class="location_string" <?php if ($this->_tpl_vars['location']->use_predefined_locations): ?>style="display:none"<?php endif; ?>>
				                        <div class="admin_option_description">
				                        	<?php echo $this->_tpl_vars['LANG_LISTING_LOCATION_DESCR']; ?>

				                        	<?php if ($this->_tpl_vars['location']->id != 'new'): ?>
				                        	<span class="translate_location"><?php echo smarty_function_translate_content(array('table' => 'listings_in_locations','field' => 'location','row_id' => $this->_tpl_vars['location']->id), $this);?>
</span>
				                        	<?php else: ?>
				                        	<span class="translate_location"><?php echo smarty_function_translate_content(array('table' => 'listings_in_locations','field' => 'location','row_id' => 'new','virtual_id' => $this->_tpl_vars['location']->virtual_id), $this);?>
</span>
				                        	<?php endif; ?>
				                        </div>
				                        <input type="text" size="50" name="location[]" class="location" value="<?php echo $this->_tpl_vars['location']->location; ?>
" />
				                        <br />
				                        <div class="geocoded_suggestions" style="display: none;"></div>
				                        <input type="hidden" name="geocoded_name[]" class="geocoded_name" value="<?php echo $this->_tpl_vars['location']->geocoded_name; ?>
">
				                        <div class="px4"></div>
			                        </div>
			                        <?php if ($this->_tpl_vars['system_settings']['predefined_locations_mode'] && $this->_tpl_vars['system_settings']['predefined_locations_mode'] != 'disabled' && $this->_tpl_vars['system_settings']['predefined_locations_mode'] != 'only'): ?>
			                        <label>
		                        		<img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/map_magnify.png" />&nbsp;
			                        	<input type="checkbox" name="use_predefined_locations[]" value="<?php if ($this->_tpl_vars['location']->id != 'new'): ?><?php echo $this->_tpl_vars['location']->id; ?>
<?php else: ?><?php echo $this->_tpl_vars['location']->virtual_id; ?>
<?php endif; ?>" class="use_predefined_locations" <?php if ($this->_tpl_vars['location']->use_predefined_locations): ?>checked<?php endif; ?>>&nbsp;<?php echo $this->_tpl_vars['LANG_USE_PREDEFINED_LOCATIONS']; ?>

			                        </label>
			                        <div class="px10"></div>
			                        <?php endif; ?>
			                        <?php endif; ?>
								</div>

		                        <div class="admin_option_description">
		                        	<?php echo $this->_tpl_vars['LANG_ADDRESS_LINE1_1']; ?>
 &nbsp;<i>(<?php echo $this->_tpl_vars['LANG_ADDRESS_LINE1_2']; ?>
)</i>
		                        	<?php if ($this->_tpl_vars['location']->id != 'new'): ?>
		                        	<span class="translate_address_line_1"><?php echo smarty_function_translate_content(array('table' => 'listings_in_locations','field' => 'address_line_1','row_id' => $this->_tpl_vars['location']->id), $this);?>
</span>
		                        	<?php else: ?>
		                        	<span class="translate_address_line_1"><?php echo smarty_function_translate_content(array('table' => 'listings_in_locations','field' => 'address_line_1','row_id' => 'new','virtual_id' => $this->_tpl_vars['location']->virtual_id), $this);?>
</span>
		                        	<?php endif; ?>
		                        </div>
		                        <input type="text" size="50" name="address_line_1[]" class="address_line_1" value="<?php echo $this->_tpl_vars['location']->address_line_1; ?>
">
		                        <div class="px4"></div>
		
		                        <div class="admin_option_description">
		                        	<?php echo $this->_tpl_vars['LANG_ADDRESS_LINE2_1']; ?>
 &nbsp;<i>(<?php echo $this->_tpl_vars['LANG_ADDRESS_LINE2_2']; ?>
)</i>
		                        	<?php if ($this->_tpl_vars['location']->id != 'new'): ?>
		                        	<span class="translate_address_line_2"><?php echo smarty_function_translate_content(array('table' => 'listings_in_locations','field' => 'address_line_2','row_id' => $this->_tpl_vars['location']->id), $this);?>
</span>
		                        	<?php else: ?>
		                        	<span class="translate_address_line_2"><?php echo smarty_function_translate_content(array('table' => 'listings_in_locations','field' => 'address_line_2','row_id' => 'new','virtual_id' => $this->_tpl_vars['location']->virtual_id), $this);?>
</span>
		                        	<?php endif; ?>
		                        </div>
		                        <input type="text" size="50" name="address_line_2[]" class="address_line_2" value="<?php echo $this->_tpl_vars['location']->address_line_2; ?>
">
		                        <div class="px4"></div>
		
		                        <?php if ($this->_tpl_vars['listing']->type->zip_enabled): ?>
		                        <div class="admin_option_description">
		                        	<?php echo $this->_tpl_vars['LANG_LISTING_ZIP']; ?>

		                        </div>
		                        <input type="text" size="50" name="zip_or_postal_index[]" class="zip_or_postal_index" value="<?php echo $this->_tpl_vars['location']->zip_or_postal_index; ?>
">
		                        <div class="px5"></div>
		                        <?php endif; ?>
		                        <?php if ($this->_tpl_vars['listing']->level->maps_enabled): ?>
		                        <!-- map_zoom - required in google_maps.js -->
		                        <input type="hidden" name="map_zoom[]" class="map_zoom" value="<?php echo $this->_tpl_vars['location']->map_zoom; ?>
">
		                        <a href="javascript: void(0);" onClick="$.jqURL.loc(select_icons_window_url+serialized_categories+typeid_url_part, {w:750,h:620,wintype:'_blank'}); return false;" class="icons_btn"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/marker_pencil.png" /></a>
		                        <a href="javascript: void(0);" onClick="$.jqURL.loc(select_icons_window_url+serialized_categories+typeid_url_part, {w:750,h:620,wintype:'_blank'}); return false;" class="icons_btn"><?php echo $this->_tpl_vars['LANG_BUTTON_MARKER_ICON']; ?>
</a>
		                        <div class="px4"></div>
		                        <label>
		                        	<img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/map_edit.png" />&nbsp;
		                        	<!-- manual_coords - required in google_maps.js -->
		                        	<input type="checkbox" name="manual_coords[]" value="<?php if ($this->_tpl_vars['location']->id != 'new'): ?><?php echo $this->_tpl_vars['location']->id; ?>
<?php else: ?><?php echo $this->_tpl_vars['location']->virtual_id; ?>
<?php endif; ?>" class="manual_coords" <?php if ($this->_tpl_vars['location']->manual_coords): ?>checked<?php endif; ?>>&nbsp;<?php echo $this->_tpl_vars['LANG_ENTER_LTLG_MANUALLY']; ?>

		                        </label>
		                        <!-- manual_coords_block - position required for jquery selector -->
		                        <div class="admin_option manual_coords_block" <?php if (! $this->_tpl_vars['location']->manual_coords): ?>style="display: none;"<?php endif; ?> style="margin:0">
		                        	<div class="admin_option_description">
			                        	<?php echo $this->_tpl_vars['LANG_MAP_LATITUDE']; ?>

			                        </div>
			                        <!-- map_coords_1 - required in google_maps.js -->
		                        	<input type="text" name="map_coords_1[]" class="map_coords_1" value="<?php echo $this->_tpl_vars['location']->map_coords_1; ?>
">
		                        	<div class="admin_option_description">
			                        	<?php echo $this->_tpl_vars['LANG_MAP_LONGITUDE']; ?>

			                        </div>
			                        <!-- map_coords_2 - required in google_maps.js -->
		                        	<input type="text" name="map_coords_2[]" class="map_coords_2" value="<?php echo $this->_tpl_vars['location']->map_coords_2; ?>
">
		                        </div>
		                        <!-- map_icon_id, map_icon_file - required in map_icons_for_listings.js -->
		                        <input type="hidden" name="map_icon_id[]" id="map_icon_id[<?php echo $this->_tpl_vars['i']-1; ?>
]" class="map_icon_id" value="<?php echo $this->_tpl_vars['location']->map_icon_id; ?>
">
		                        <input type="hidden" name="map_icon_file[]" id="map_icon_file[<?php echo $this->_tpl_vars['i']-1; ?>
]" class="map_icon_file" value="<?php echo $this->_tpl_vars['location']->map_icon_file; ?>
">
		                        <div class="px4"></div>
		                        <?php endif; ?>
		                        <a href="javascript: void(0);" style="display:none;" class="delete_location"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/map_delete.png" /></a>
		                        <a href="javascript: void(0);" style="display:none;" class="delete_location"><?php echo $this->_tpl_vars['LANG_DELETE_ADDRESS']; ?>
</a>
	                        </div>
	                    <?php endforeach; endif; unset($_from); ?>
                        </div>

                        <div class="px10"></div>
                        <a href="javascript: void(0);" class="add_location" <?php if ($this->_tpl_vars['listing']->level->locations_number <= count($this->_tpl_vars['listing']->locations_array())): ?>style="display:none"<?php endif; ?>><img src="<?php echo $this->_tpl_vars['public_path']; ?>
images/buttons/map_add.png" /></a>
                        <a href="javascript: void(0);" class="add_location" <?php if ($this->_tpl_vars['listing']->level->locations_number <= count($this->_tpl_vars['listing']->locations_array())): ?>style="display:none"<?php endif; ?>><?php echo $this->_tpl_vars['LANG_ADD_ADDRESS']; ?>
</a> 
                        <div class="px10 add_location" <?php if ($this->_tpl_vars['listing']->level->locations_number <= count($this->_tpl_vars['listing']->locations_array())): ?>style="display:none"<?php endif; ?>></div>
                        <?php if ($this->_tpl_vars['listing']->level->maps_enabled): ?>
                        <input type="button" onClick="generateMap(); return false;" name="maps_btn" class="button maps_button" value="<?php echo $this->_tpl_vars['LANG_BUTTON_MAP']; ?>
">
                        <div class="px10"></div>
                        <input type="hidden" name="single_icon" id="single_icon" value="false">
                        <div id="maps_canvas" class="maps_canvas" style="width: <?php echo $this->_tpl_vars['listing']->level->explodeSize('maps_size','width'); ?>
px; height: <?php echo $this->_tpl_vars['listing']->level->explodeSize('maps_size','height'); ?>
px"></div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>

                    <?php if ($this->_tpl_vars['listing']->content_fields->fieldsCount()): ?>
                    <label class="block_title"><?php echo $this->_tpl_vars['LANG_LISTING_ADDITIONAL_INFORMATION']; ?>
</label>
                    <div class="admin_option">
                    	<?php echo $this->_tpl_vars['listing']->inputMode(); ?>

                    </div>
                    <?php endif; ?>

                    <?php if ($this->_tpl_vars['listing']->id == 'new'): ?>
                    <input type=submit name="submit" class="button create_button" value="<?php echo $this->_tpl_vars['LANG_BUTTON_CREATE_LISTING']; ?>
">
                    <?php else: ?>
                    <input type=submit name="submit" class="button save_button" value="<?php echo $this->_tpl_vars['LANG_BUTTON_SAVE_CHANGES']; ?>
">
                    <?php endif; ?>
                    </form>
                </div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>