<?php /* Smarty version 2.6.26, created on 2012-02-06 04:22:55
         compiled from types_levels/admin_level_settings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'translate_content', 'types_levels/admin_level_settings.tpl', 77, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<?php $this->assign('level_id', $this->_tpl_vars['level']->id); ?>

<script language="JavaScript" type="text/javascript">
$(document).ready(function() {
	$("#eternal_active_period").click( function() {
		if ($('#eternal_active_period').is(':checked')) {
        	$('#active_years').attr('disabled', true);
        	$('#active_months').attr('disabled', true);
        	$('#active_days').attr('disabled', true);
	    } else {
	        $('#active_years').removeAttr('disabled');
	        $('#active_months').removeAttr('disabled');
	        $('#active_days').removeAttr('disabled');
	    }
	});
	$("input[name='description_mode']").click( function() {
		if ($("input[name='description_mode']:checked").val() == 'enabled') {
        	$('#description_length').removeAttr('disabled');
	    } else {
	        $('#description_length').attr('disabled', true);
	    }
	});
	$("#logo_enabled").click( function() {
		if ($('#logo_enabled').is(':checked')) {
        	$('#logo_width').removeAttr('disabled');
        	$('#logo_height').removeAttr('disabled');
	    } else {
	        $('#logo_width').attr('disabled', true);
	        $('#logo_height').attr('disabled', true);
	    }
	});
	$("#maps_enabled").click( function() {
		if ($('#maps_enabled').is(':checked')) {
        	$('#maps_width').removeAttr('disabled');
        	$('#maps_height').removeAttr('disabled');
	    } else {
	        $('#maps_width').attr('disabled', true);
	        $('#maps_height').attr('disabled', true);
	    }
	});
	$("input[name='reviews_mode']").click( function() {
		if ($("input[name='reviews_mode']:checked").val() == 'disabled') {
        	$('#reviews_richtext_enabled').attr('disabled', true);
	    } else {
	    	$('#reviews_richtext_enabled').removeAttr('disabled');
	    }
	});
});
</script>

                <div class="content">
                	<?php echo $this->_tpl_vars['VH']->validation_errors(); ?>

                     <h3><?php if ($this->_tpl_vars['level_id'] != 'new'): ?><?php echo $this->_tpl_vars['LANG_EDIT_LEVEL_1']; ?>
 "<?php echo $this->_tpl_vars['level']->name; ?>
" <?php echo $this->_tpl_vars['LANG_EDIT_LEVEL_2']; ?>
 "<?php echo $this->_tpl_vars['type']->name; ?>
" <?php else: ?><?php echo $this->_tpl_vars['LANG_CREATE_LEVEL']; ?>
 "<?php echo $this->_tpl_vars['type']->name; ?>
"<?php endif; ?></h3>

                     <?php if ($this->_tpl_vars['level_id'] != 'new'): ?>
                     <div class="admin_top_menu_cell">
	                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/levels/create/type_id/".($this->_tpl_vars['type_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_CREATE_LEVEL_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_add.png" /></a>
	                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/levels/create/type_id/".($this->_tpl_vars['type_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_CREATE_LEVEL_OPTION']; ?>
</a>&nbsp;&nbsp;&nbsp;
					 </div>
					 <div class="admin_top_menu_cell">
	                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/levels/delete/".($this->_tpl_vars['level_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_DELETE_LEVEL_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_delete.png" /></a>
	                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/levels/delete/".($this->_tpl_vars['level_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_DELETE_LEVEL_OPTION']; ?>
</a>&nbsp;&nbsp;&nbsp;
	                 </div>
	                 <div class="admin_top_menu_cell">
	                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/groups/manage_fields/".($this->_tpl_vars['custom_group_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_CONFIGURE_FIELDS_OPTION']; ?>
"><img src="<?php echo $this->_tpl_vars['public_path']; ?>
/images/buttons/page_green.png"></a>
	                     <a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/fields/groups/manage_fields/".($this->_tpl_vars['custom_group_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_CONFIGURE_FIELDS_OPTION']; ?>
</a>&nbsp;&nbsp;&nbsp;
					 </div>
                     <div class="clear_float"></div>
					 <div class="px10"></div>
                     <?php endif; ?>

                     <form action="" method="post">
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_LEVEL_NAME']; ?>

                          	<?php echo smarty_function_translate_content(array('table' => 'levels','field' => 'name','row_id' => $this->_tpl_vars['level_id']), $this);?>

                          </div>
                          <div class="admin_option_description">
                          	<?php echo $this->_tpl_vars['LANG_LEVEL_NAME_DESCR']; ?>

                          </div>
                          <input type=text name="name" value="<?php echo $this->_tpl_vars['level']->name; ?>
" size="40" class="admin_option_input">
                     </div>
                     
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_LEVEL_ACTIVE_PERIOD']; ?>

                          </div>
                          <div class="admin_option_description">
                          	<?php echo $this->_tpl_vars['LANG_LEVEL_ACTIVE_PERIOD_DESCR']; ?>

                          </div>
                          <select name="active_years" id="active_years" class="no_min_width" <?php if ($this->_tpl_vars['level']->eternal_active_period): ?>disabled="disabled"<?php endif; ?>>
                          	<option value="0" <?php if ($this->_tpl_vars['level']->active_years == 0): ?>selected<?php endif; ?>>0</option>
                          	<option value="1" <?php if ($this->_tpl_vars['level']->active_years == 1): ?>selected<?php endif; ?>>1</option>
                          	<option value="2" <?php if ($this->_tpl_vars['level']->active_years == 2): ?>selected<?php endif; ?>>2</option>
                          	<option value="3" <?php if ($this->_tpl_vars['level']->active_years == 3): ?>selected<?php endif; ?>>3</option>
                          	<option value="4" <?php if ($this->_tpl_vars['level']->active_years == 4): ?>selected<?php endif; ?>>4</option>
                          	<option value="5" <?php if ($this->_tpl_vars['level']->active_years == 5): ?>selected<?php endif; ?>>5</option>
                          	<option value="6" <?php if ($this->_tpl_vars['level']->active_years == 6): ?>selected<?php endif; ?>>6</option>
                          	<option value="7" <?php if ($this->_tpl_vars['level']->active_years == 7): ?>selected<?php endif; ?>>7</option>
                          	<option value="8" <?php if ($this->_tpl_vars['level']->active_years == 8): ?>selected<?php endif; ?>>8</option>
                          	<option value="9" <?php if ($this->_tpl_vars['level']->active_years == 9): ?>selected<?php endif; ?>>9</option>
                          	<option value="10" <?php if ($this->_tpl_vars['level']->active_years == 10): ?>selected<?php endif; ?>>10</option>
                          </select> <?php echo $this->_tpl_vars['LANG_YEARS']; ?>

                          &nbsp;&nbsp;
                          <select name="active_months" id="active_months" class="no_min_width" <?php if ($this->_tpl_vars['level']->eternal_active_period): ?>disabled="disabled"<?php endif; ?>>
                          	<option value="0" <?php if ($this->_tpl_vars['level']->active_months == 0): ?>selected<?php endif; ?>>0</option>
                          	<option value="1" <?php if ($this->_tpl_vars['level']->active_months == 1): ?>selected<?php endif; ?>>1</option>
                          	<option value="2" <?php if ($this->_tpl_vars['level']->active_months == 2): ?>selected<?php endif; ?>>2</option>
                          	<option value="3" <?php if ($this->_tpl_vars['level']->active_months == 3): ?>selected<?php endif; ?>>3</option>
                          	<option value="4" <?php if ($this->_tpl_vars['level']->active_months == 4): ?>selected<?php endif; ?>>4</option>
                          	<option value="5" <?php if ($this->_tpl_vars['level']->active_months == 5): ?>selected<?php endif; ?>>5</option>
                          	<option value="6" <?php if ($this->_tpl_vars['level']->active_months == 6): ?>selected<?php endif; ?>>6</option>
                          	<option value="7" <?php if ($this->_tpl_vars['level']->active_months == 7): ?>selected<?php endif; ?>>7</option>
                          	<option value="8" <?php if ($this->_tpl_vars['level']->active_months == 8): ?>selected<?php endif; ?>>8</option>
                          	<option value="9" <?php if ($this->_tpl_vars['level']->active_months == 9): ?>selected<?php endif; ?>>9</option>
                          	<option value="10" <?php if ($this->_tpl_vars['level']->active_months == 10): ?>selected<?php endif; ?>>10</option>
                          	<option value="11" <?php if ($this->_tpl_vars['level']->active_months == 11): ?>selected<?php endif; ?>>11</option>
                          	<option value="12" <?php if ($this->_tpl_vars['level']->active_months == 12): ?>selected<?php endif; ?>>12</option>
                          </select> <?php echo $this->_tpl_vars['LANG_MONTHS']; ?>

                          &nbsp;&nbsp;
                          <select name="active_days" id="active_days" class="no_min_width" <?php if ($this->_tpl_vars['level']->eternal_active_period): ?>disabled="disabled"<?php endif; ?>>
                          	<option value="0" <?php if ($this->_tpl_vars['level']->active_days == 0): ?>selected<?php endif; ?>>0</option>
                          	<option value="1" <?php if ($this->_tpl_vars['level']->active_days == 1): ?>selected<?php endif; ?>>1</option>
                          	<option value="2" <?php if ($this->_tpl_vars['level']->active_days == 2): ?>selected<?php endif; ?>>2</option>
                          	<option value="3" <?php if ($this->_tpl_vars['level']->active_days == 3): ?>selected<?php endif; ?>>3</option>
                          	<option value="4" <?php if ($this->_tpl_vars['level']->active_days == 4): ?>selected<?php endif; ?>>4</option>
                          	<option value="5" <?php if ($this->_tpl_vars['level']->active_days == 5): ?>selected<?php endif; ?>>5</option>
                          	<option value="6" <?php if ($this->_tpl_vars['level']->active_days == 6): ?>selected<?php endif; ?>>6</option>
                          	<option value="7" <?php if ($this->_tpl_vars['level']->active_days == 7): ?>selected<?php endif; ?>>7</option>
                          	<option value="8" <?php if ($this->_tpl_vars['level']->active_days == 8): ?>selected<?php endif; ?>>8</option>
                          	<option value="9" <?php if ($this->_tpl_vars['level']->active_days == 9): ?>selected<?php endif; ?>>9</option>
                          	<option value="10" <?php if ($this->_tpl_vars['level']->active_days == 10): ?>selected<?php endif; ?>>10</option>
                          	<option value="11" <?php if ($this->_tpl_vars['level']->active_days == 11): ?>selected<?php endif; ?>>11</option>
                          	<option value="12" <?php if ($this->_tpl_vars['level']->active_days == 12): ?>selected<?php endif; ?>>12</option>
                          	<option value="13" <?php if ($this->_tpl_vars['level']->active_days == 13): ?>selected<?php endif; ?>>13</option>
                          	<option value="14" <?php if ($this->_tpl_vars['level']->active_days == 14): ?>selected<?php endif; ?>>14</option>
                          	<option value="15" <?php if ($this->_tpl_vars['level']->active_days == 15): ?>selected<?php endif; ?>>15</option>
                          	<option value="16" <?php if ($this->_tpl_vars['level']->active_days == 16): ?>selected<?php endif; ?>>16</option>
                          	<option value="17" <?php if ($this->_tpl_vars['level']->active_days == 17): ?>selected<?php endif; ?>>17</option>
                          	<option value="18" <?php if ($this->_tpl_vars['level']->active_days == 18): ?>selected<?php endif; ?>>18</option>
                          	<option value="19" <?php if ($this->_tpl_vars['level']->active_days == 19): ?>selected<?php endif; ?>>19</option>
                          	<option value="20" <?php if ($this->_tpl_vars['level']->active_days == 20): ?>selected<?php endif; ?>>20</option>
                          	<option value="21" <?php if ($this->_tpl_vars['level']->active_days == 21): ?>selected<?php endif; ?>>21</option>
                          	<option value="22" <?php if ($this->_tpl_vars['level']->active_days == 22): ?>selected<?php endif; ?>>22</option>
                          	<option value="23" <?php if ($this->_tpl_vars['level']->active_days == 23): ?>selected<?php endif; ?>>23</option>
                          	<option value="24" <?php if ($this->_tpl_vars['level']->active_days == 24): ?>selected<?php endif; ?>>24</option>
                          	<option value="25" <?php if ($this->_tpl_vars['level']->active_days == 25): ?>selected<?php endif; ?>>25</option>
                          	<option value="26" <?php if ($this->_tpl_vars['level']->active_days == 26): ?>selected<?php endif; ?>>26</option>
                          	<option value="27" <?php if ($this->_tpl_vars['level']->active_days == 27): ?>selected<?php endif; ?>>27</option>
                          	<option value="28" <?php if ($this->_tpl_vars['level']->active_days == 28): ?>selected<?php endif; ?>>28</option>
                          	<option value="29" <?php if ($this->_tpl_vars['level']->active_days == 29): ?>selected<?php endif; ?>>29</option>
                          	<option value="30" <?php if ($this->_tpl_vars['level']->active_days == 30): ?>selected<?php endif; ?>>30</option>
                          </select> <?php echo $this->_tpl_vars['LANG_DAYS']; ?>

                          <div style="padding-top:4px;">
                          	<label><input type="checkbox" value="1" id="eternal_active_period" name="eternal_active_period" <?php if ($this->_tpl_vars['level']->eternal_active_period): ?>checked<?php endif; ?>> <?php echo $this->_tpl_vars['LANG_ETERNAL']; ?>
</label>
                          	<label><input type="checkbox" value="1" name="allow_to_edit_active_period" <?php if ($this->_tpl_vars['level']->allow_to_edit_active_period): ?>checked<?php endif; ?>> <?php echo $this->_tpl_vars['LANG_ALLOW_TO_EDIT_ACTIVE_PERIOD']; ?>
</label>
                          </div>
                     </div>
                     
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_LEVEL_FEATURED']; ?>

                          </div>
                          <div class="admin_option_description">
                          	<?php echo $this->_tpl_vars['LANG_LEVEL_FEATURED_DESCR']; ?>

                          </div>
                          <input type="checkbox" name="featured" <?php if ($this->_tpl_vars['level']->featured == 1): ?> checked <?php endif; ?> />&nbsp;<?php echo $this->_tpl_vars['LANG_ENABLED']; ?>

                     </div>
                     
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_LEVEL_TITLE_ENABLED']; ?>

                          </div>
                          <div class="admin_option_description">
                          	<?php echo $this->_tpl_vars['LANG_LEVEL_TITLE_ENABLED_DESCR']; ?>

                          </div>
                          <input type="checkbox" name="title_enabled" <?php if ($this->_tpl_vars['level']->title_enabled == 1): ?> checked <?php endif; ?> />&nbsp;<?php echo $this->_tpl_vars['LANG_ENABLED']; ?>

                          
                          <div class="px5"></div>
                          
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_LEVEL_SEO_TITLE_ENABLED']; ?>

                          </div>
                          <div class="admin_option_description">
                          	<?php echo $this->_tpl_vars['LANG_LEVEL_SEO_TITLE_ENABLED_DESCR']; ?>

                          </div>
                          <input type="checkbox" name="seo_title_enabled" <?php if ($this->_tpl_vars['level']->seo_title_enabled == 1): ?> checked <?php endif; ?> />&nbsp;<?php echo $this->_tpl_vars['LANG_ENABLED']; ?>

                          
                          <div class="px5"></div>
                          
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_LEVEL_META_ENABLED']; ?>

                          </div>
                          <div class="admin_option_description">
                          	<?php echo $this->_tpl_vars['LANG_LEVEL_META_ENABLED_DESCR']; ?>

                          </div>
                          <input type="checkbox" name="meta_enabled" <?php if ($this->_tpl_vars['level']->meta_enabled == 1): ?> checked <?php endif; ?> />&nbsp;<?php echo $this->_tpl_vars['LANG_ENABLED']; ?>

                     </div>
                     
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_LEVEL_LISTING_DESCRIPTION']; ?>

                          </div>
                          <label><input type="radio" name="description_mode" value="enabled" <?php if ($this->_tpl_vars['level']->description_mode == 'enabled'): ?> checked <?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_ENABLED']; ?>
</label>
                          <label><input type="radio" name="description_mode" value="richtext" <?php if ($this->_tpl_vars['level']->description_mode == 'richtext'): ?> checked <?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_LEVEL_LISTING_DESCRIPTION_RICHTEXT']; ?>
</label>
                          <label><input type="radio" name="description_mode" value="disabled" <?php if ($this->_tpl_vars['level']->description_mode == 'disabled'): ?> checked <?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_DISABLED']; ?>
</label>
                          
                          <div class="px5"></div>
                          
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_LEVEL_LISTING_DESCRIPTION_LENGTH']; ?>

                          </div>
                          <input type="input" name="description_length" id="description_length" value="<?php echo $this->_tpl_vars['level']->description_length; ?>
" size="3" <?php if ($this->_tpl_vars['level']->description_mode != 'enabled'): ?>disabled<?php endif; ?> />
                     </div>
                     
                     <?php if ($this->_tpl_vars['type']->categories_type != 'disabled'): ?>
	                     <div class="admin_option">
	                          <div class="admin_option_name">
	                          	<?php echo $this->_tpl_vars['LANG_LEVEL_CATEGORIES_NUMBER']; ?>

	                          </div>
	                          <div class="admin_option_description">
	                          	<?php echo $this->_tpl_vars['LANG_LEVEL_CATEGORIES_NUMBER_DESCR']; ?>

	                          </div>
	                          <input type=text name="categories_number" value="<?php echo $this->_tpl_vars['level']->categories_number; ?>
" size="2" class="admin_option_input">
	                     </div>
					 <?php endif; ?>
                     
                     <?php if ($this->_tpl_vars['type']->locations_enabled): ?>
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_LEVEL_LOCATIONS_NUMBER']; ?>

                          </div>
                          <div class="admin_option_description">
                          	<?php echo $this->_tpl_vars['LANG_LEVEL_LOCATIONS_NUMBER_DESCR']; ?>

                          </div>
                          <input type=text name="locations_number" value="<?php echo $this->_tpl_vars['level']->locations_number; ?>
" size="2" class="admin_option_input">
                     </div>
                     <?php endif; ?>
                     
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_TYPE_MODERATION']; ?>

                          </div>
                          <div class="admin_option_description">
                          	<?php echo $this->_tpl_vars['LANG_TYPE_MODERATION_DESCR']; ?>

                          </div>
                          <input type="checkbox" name="preapproved_mode" <?php if ($this->_tpl_vars['level']->preapproved_mode == 1): ?> checked <?php endif; ?> />&nbsp;<?php echo $this->_tpl_vars['LANG_ENABLED']; ?>

                     </div>

                     <div class="admin_option">
                         <div class="admin_option_name">
                         	<?php echo $this->_tpl_vars['LANG_LEVEL_ENABLE_LOGO']; ?>

                         </div>
                         <div class="admin_option_description">
                         	<?php echo $this->_tpl_vars['LANG_LEVEL_ENABLE_LOGO_DESCR']; ?>

                         </div>
                         <input type="checkbox" name="logo_enabled" id="logo_enabled" <?php if ($this->_tpl_vars['level']->logo_enabled == 1): ?> checked <?php endif; ?> />&nbsp;<?php echo $this->_tpl_vars['LANG_ENABLED']; ?>

                         <div class="px10"></div>
                         <div class="admin_option_name">
                         	<?php echo $this->_tpl_vars['LANG_LEVEL_LOGO_SIZE_1']; ?>
 <i>(308*400 <?php echo $this->_tpl_vars['LANG_LEVEL_LOGO_SIZE_2']; ?>
)</i>
                         </div>
                         <?php echo $this->_tpl_vars['LANG_WIDTH']; ?>
, <?php echo $this->_tpl_vars['LANG_PX']; ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['LANG_HEIGHT']; ?>
, <?php echo $this->_tpl_vars['LANG_PX']; ?>
<br>
                         <input type=text name="logo_width" id="logo_width" value="<?php echo $this->_tpl_vars['level']->explodeSize('logo_size',0); ?>
" size="4" class="admin_option_input" <?php if ($this->_tpl_vars['level']->logo_enabled == 0): ?>disabled<?php endif; ?>>&nbsp;&nbsp;&nbsp;&nbsp;
                         <input type=text name="logo_height" id="logo_height" value="<?php echo $this->_tpl_vars['level']->explodeSize('logo_size',1); ?>
" size="4" class="admin_option_input" <?php if ($this->_tpl_vars['level']->logo_enabled == 0): ?>disabled<?php endif; ?>>
                     </div>
                     <div class="admin_option">
                         <div class="admin_option_name">
                         	<?php echo $this->_tpl_vars['LANG_LEVEL_IMAGES_COUNT']; ?>

                         </div>
                         <div class="admin_option_description">
                         	<?php echo $this->_tpl_vars['LANG_LEVEL_IMAGES_COUNT_DESCR']; ?>

                         </div>
                         <input type=text name="images_count" value="<?php echo $this->_tpl_vars['level']->images_count; ?>
" size="1" class="admin_option_input">
                         <div class="px10"></div>
                         <div class="admin_option_name">
                         	<?php echo $this->_tpl_vars['LANG_LEVEL_IMAGES_SIZE']; ?>

                         </div>
                         <?php echo $this->_tpl_vars['LANG_WIDTH']; ?>
, <?php echo $this->_tpl_vars['LANG_PX']; ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['LANG_HEIGHT']; ?>
, <?php echo $this->_tpl_vars['LANG_PX']; ?>
<br>
                         <input type=text name="images_width" value="<?php echo $this->_tpl_vars['level']->explodeSize('images_size',0); ?>
" size="4" class="admin_option_input">&nbsp;&nbsp;&nbsp;&nbsp;
                         <input type=text name="images_height" value="<?php echo $this->_tpl_vars['level']->explodeSize('images_size',1); ?>
" size="4" class="admin_option_input">
                         <br />
                     	 <br />
                         <div class="admin_option_name">
                         	<?php echo $this->_tpl_vars['LANG_LEVEL_THMBS_SIZE']; ?>

                          </div>
                         <?php echo $this->_tpl_vars['LANG_WIDTH']; ?>
, <?php echo $this->_tpl_vars['LANG_PX']; ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['LANG_HEIGHT']; ?>
, <?php echo $this->_tpl_vars['LANG_PX']; ?>
<br>
                         <input type=text name="images_thumbnail_width" value="<?php echo $this->_tpl_vars['level']->explodeSize('images_thumbnail_size',0); ?>
" size="4" class="admin_option_input">&nbsp;&nbsp;&nbsp;&nbsp;
                         <input type=text name="images_thumbnail_height" value="<?php echo $this->_tpl_vars['level']->explodeSize('images_thumbnail_size',1); ?>
" size="4" class="admin_option_input">
                     </div>
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_LEVEL_VIDEOS_COUNT']; ?>

                          </div>
                          <div class="admin_option_description">
                          	<?php echo $this->_tpl_vars['LANG_LEVEL_VIDEOS_COUNT_DESCR']; ?>

                          </div>
                          <input type=text name="video_count" value="<?php echo $this->_tpl_vars['level']->video_count; ?>
" size="1" class="admin_option_input">
                          <div class="px10"></div>
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_LEVEL_VIDEO_SIZE']; ?>

                          </div>
                         <?php echo $this->_tpl_vars['LANG_WIDTH']; ?>
, <?php echo $this->_tpl_vars['LANG_PX']; ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['LANG_HEIGHT']; ?>
, <?php echo $this->_tpl_vars['LANG_PX']; ?>
<br>
                         <input type=text name="video_width" value="<?php echo $this->_tpl_vars['level']->explodeSize('video_size',0); ?>
" size="4" class="admin_option_input">&nbsp;&nbsp;&nbsp;&nbsp;
                         <input type=text name="video_height" value="<?php echo $this->_tpl_vars['level']->explodeSize('video_size',1); ?>
" size="4" class="admin_option_input">
                     </div>
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_LEVEL_FILES_COUNT']; ?>

                          </div>
                          <input type=text name="files_count" value="<?php echo $this->_tpl_vars['level']->files_count; ?>
" size="1" class="admin_option_input">
                     </div>
                     
                     <?php if ($this->_tpl_vars['type']->locations_enabled): ?>
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_LEVEL_MAPS']; ?>

                          </div>
                          <div class="admin_option_description">
                          	<?php echo $this->_tpl_vars['LANG_LEVEL_MAPS_DESCR']; ?>

                          </div>
                          <input type="checkbox" name="maps_enabled" id="maps_enabled" <?php if ($this->_tpl_vars['level']->maps_enabled == 1): ?> checked <?php endif; ?>>&nbsp;<?php echo $this->_tpl_vars['LANG_ENABLED']; ?>

                          <div class="px10"></div>
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_LEVEL_MAPS_SIZE']; ?>

                          </div>
                         <?php echo $this->_tpl_vars['LANG_WIDTH']; ?>
, <?php echo $this->_tpl_vars['LANG_PX']; ?>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->_tpl_vars['LANG_HEIGHT']; ?>
, <?php echo $this->_tpl_vars['LANG_PX']; ?>
<br>
                         <input type=text name="maps_width" id="maps_width" value="<?php echo $this->_tpl_vars['level']->explodeSize('maps_size',0); ?>
" size="4" class="admin_option_input" <?php if ($this->_tpl_vars['level']->maps_enabled == 0): ?>disabled<?php endif; ?>>&nbsp;&nbsp;&nbsp;&nbsp;
                         <input type=text name="maps_height" id="maps_height" value="<?php echo $this->_tpl_vars['level']->explodeSize('maps_size',1); ?>
" size="4" class="admin_option_input" <?php if ($this->_tpl_vars['level']->maps_enabled == 0): ?>disabled<?php endif; ?>>
                     </div>
                     <?php endif; ?>

                     <div class="admin_option">
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_LEVEL_OPTIONS']; ?>

                          </div>
                          <div class="admin_option_description">
                          	<?php echo $this->_tpl_vars['LANG_LEVEL_OPTIONS_DESC']; ?>

                          </div>
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_LEVEL_PRINT_OPTION']; ?>

                          </div>
                          <input type="checkbox" name="option_print"  <?php if ($this->_tpl_vars['level']->option_print == 1): ?> checked <?php endif; ?>>&nbsp;<?php echo $this->_tpl_vars['LANG_ENABLED']; ?>

                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_LEVEL_PDF_OPTION']; ?>

                          </div>
                          <input type="checkbox" name="option_pdf"  <?php if ($this->_tpl_vars['level']->option_pdf == 1): ?> checked <?php endif; ?>>&nbsp;<?php echo $this->_tpl_vars['LANG_ENABLED']; ?>

                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_LEVEL_QUICKLIST_OPTION']; ?>

                          </div>
                          <input type="checkbox" name="option_quick_list" <?php if ($this->_tpl_vars['level']->option_quick_list == 1): ?> checked <?php endif; ?>>&nbsp;<?php echo $this->_tpl_vars['LANG_ENABLED']; ?>

                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_LEVEL_EMAILFRIEND_OPTION']; ?>

                          </div>
                          <input type="checkbox" name="option_email_friend" <?php if ($this->_tpl_vars['level']->option_email_friend == 1): ?> checked <?php endif; ?>>&nbsp;<?php echo $this->_tpl_vars['LANG_ENABLED']; ?>

                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_LEVEL_EMAILOWNER_OPTION']; ?>

                          </div>
                          <input type="checkbox" name="option_email_owner" <?php if ($this->_tpl_vars['level']->option_email_owner == 1): ?> checked <?php endif; ?>>&nbsp;<?php echo $this->_tpl_vars['LANG_ENABLED']; ?>

                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_LEVEL_REPORT_OPTION']; ?>

                          </div>
                          <input type="checkbox" name="option_report" <?php if ($this->_tpl_vars['level']->option_report == 1): ?> checked <?php endif; ?>>&nbsp;<?php echo $this->_tpl_vars['LANG_ENABLED']; ?>

                     </div>
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_LEVEL_SOCIAL']; ?>

                          </div>
                          <input type="checkbox" name="social_bookmarks_enabled" <?php if ($this->_tpl_vars['level']->social_bookmarks_enabled == 1): ?> checked <?php endif; ?>>&nbsp;<?php echo $this->_tpl_vars['LANG_ENABLED']; ?>

                     </div>
                     <div class="admin_option">
                     	  <div class="admin_option_name">
                     	  	<?php echo $this->_tpl_vars['LANG_RATINGS_ENABLED']; ?>

                          </div>
                          <label><input type="checkbox" name="ratings_enabled" <?php if ($this->_tpl_vars['level']->ratings_enabled == 1): ?> checked <?php endif; ?>>&nbsp;<?php echo $this->_tpl_vars['LANG_ENABLED']; ?>
</label>
                          <div class="px10"></div>
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_REVIEWS_MODE']; ?>

                          </div>
                          <label><input type="radio" name="reviews_mode" value="disabled" <?php if ($this->_tpl_vars['level']->reviews_mode == 'disabled'): ?> checked <?php endif; ?>>&nbsp;<?php echo $this->_tpl_vars['LANG_DISABLED']; ?>
</label>
                          <label><input type="radio" name="reviews_mode" value="reviews" <?php if ($this->_tpl_vars['level']->reviews_mode == 'reviews'): ?> checked <?php endif; ?>>&nbsp;<?php echo $this->_tpl_vars['LANG_SINGLE_REVIEWS']; ?>
</label>
                          <label><input type="radio" name="reviews_mode" value="comments" <?php if ($this->_tpl_vars['level']->reviews_mode == 'comments'): ?> checked <?php endif; ?>>&nbsp;<?php echo $this->_tpl_vars['LANG_COMMENTS_TREE']; ?>
</label>
                          <div class="px10"></div>
                          <div class="admin_option_name">
                     	  	<?php echo $this->_tpl_vars['LANG_REVIEWS_RICHTEXT_ENABLED']; ?>

                          </div>
                          <label><input type="checkbox" name="reviews_richtext_enabled" id="reviews_richtext_enabled" <?php if ($this->_tpl_vars['level']->reviews_richtext_enabled == 1): ?>checked<?php endif; ?> <?php if ($this->_tpl_vars['level']->reviews_mode == 'disabled'): ?>disabled<?php endif; ?> />&nbsp;<?php echo $this->_tpl_vars['LANG_ENABLED']; ?>
</label>
                     </div>
                     <input type=submit name="submit" value="<?php if ($this->_tpl_vars['level']->id != 'new'): ?><?php echo $this->_tpl_vars['LANG_BUTTON_SAVE_CHANGES']; ?>
<?php else: ?><?php echo $this->_tpl_vars['LANG_BUTTON_CREATE_LEVEL']; ?>
<?php endif; ?>">
                     </form>
                </div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>