<?php /* Smarty version 2.6.26, created on 2012-02-06 08:50:19
         compiled from settings/admin_frontend_configure.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'settings/admin_frontend_configure.tpl', 73, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php if ($this->_tpl_vars['type']): ?>
<script language="JavaScript" type="text/javascript">
$(document).ready(function() {
	$(".levels_visible").change( function() {
		var levels_list = [];
		$(".levels_visible").each(function() {
			if ($(this).is(':checked'))
				levels_list.push($(this).attr('id').replace("level_", ""));
		});
		$("#serialised_levels").val(levels_list.join(','));
	});
});
</script>
<?php endif; ?>
                <div class="content">
                	 <?php if ($this->_tpl_vars['type']): ?>
                     <h3><?php echo $this->_tpl_vars['LANG_EDIT_LISTINGS_VIEW_1']; ?>
 "<?php echo $this->_tpl_vars['type']->name; ?>
" <?php echo $this->_tpl_vars['LANG_EDIT_LISTINGS_VIEW_2']; ?>
 "<?php echo $this->_tpl_vars['listings_view']->page_name; ?>
"</h3>
                     <?php else: ?>
                     <h3><?php echo $this->_tpl_vars['LANG_FRONTEND_SETTINGS_PAGE']; ?>
 "<?php echo $this->_tpl_vars['listings_view']->page_name; ?>
"</h3>
                     <?php endif; ?>

                     <form action="" method="post">
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_LISTING_VIEW_TH']; ?>
<span class="red_asterisk">*</span>
                          </div>
                          <?php if ($this->_tpl_vars['listings_view']->page_key != 'quicklist'): ?>
                          <label><input type="radio" name="view" value="semitable" <?php if ($this->_tpl_vars['listings_view']->view == 'semitable'): ?> checked <?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_FRONTEND_SETTING_SEMITABLE']; ?>
</label>
                          <label><input type="radio" name="view" value="full" <?php if ($this->_tpl_vars['listings_view']->view == 'full'): ?> checked <?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_FRONTEND_SETTING_FULL']; ?>
</label>
                          <label><input type="radio" name="view" value="short" <?php if ($this->_tpl_vars['listings_view']->view == 'short'): ?> checked <?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_FRONTEND_SETTING_SHORT']; ?>
</label>
                          <?php else: ?>
                          <label><input type="radio" name="view" value="quicklist" <?php if ($this->_tpl_vars['listings_view']->view == 'quicklist'): ?> checked <?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_FRONTEND_SETTING_QUICK_LIST']; ?>
</label>
                          <?php endif; ?>
                     </div>
                     <div class="px10"></div>
                     <div class="admin_option">
						<div class="admin_option_name">
							<?php echo $this->_tpl_vars['LANG_LISTING_FORMAT']; ?>
<span class="red_asterisk">*</span>
						</div>
						<div class="admin_option_description">
							<?php echo $this->_tpl_vars['LANG_LISTING_FORMAT_DESCR']; ?>

						</div>
						<input type=text name="format" value="<?php echo $this->_tpl_vars['listings_view']->format; ?>
" size="5" />
					 </div>
                     <div class="px10"></div>
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_DEFAULT_LISTINGS_ORDER']; ?>
<span class="red_asterisk">*</span>
                          </div>
                          <label><input type="radio" name="order_by" value="l.creation_date" <?php if ($this->_tpl_vars['listings_view']->order_by == 'l.creation_date'): ?> checked <?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_SEARCH_CREATION_DATE']; ?>
</label>
                          <label><input type="radio" name="order_by" value="l.title" <?php if ($this->_tpl_vars['listings_view']->order_by == 'l.title'): ?> checked <?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_SEARCH_LISTING_TITLE']; ?>
</label>
                          <label><input type="radio" name="order_by" value="lev.order_num" <?php if ($this->_tpl_vars['listings_view']->order_by == 'lev.order_num'): ?> checked <?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_SEARCH_INFO_VALUE']; ?>
</label>
                          <label><input type="radio" name="order_by" value="rating" <?php if ($this->_tpl_vars['listings_view']->order_by == 'rating'): ?> checked <?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_SEARCH_RATING']; ?>
</label>
                          <label><input type="radio" name="order_by" value="rev_count" <?php if ($this->_tpl_vars['listings_view']->order_by == 'rev_count'): ?> checked <?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_SEARCH_REVIEWS_COUNT']; ?>
</label>
                          <label><input type="radio" name="order_by" value="rev_last" <?php if ($this->_tpl_vars['listings_view']->order_by == 'rev_last'): ?> checked <?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_SEARCH_LAST_REVIEW_DATE']; ?>
</label>
                          <label><input type="radio" name="order_by" value="random" <?php if ($this->_tpl_vars['listings_view']->order_by == 'random'): ?> checked <?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_SEARCH_RANDOM']; ?>
</label>
                          <div class="px5"></div>
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_DEFAULT_LISTINGS_ORDER_DIRECTION']; ?>
<span class="red_asterisk">*</span>
                          </div>
                          <label><input type="radio" name="order_direction" value="asc" <?php if ($this->_tpl_vars['listings_view']->order_direction == 'asc'): ?> checked <?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_SORT_ASCENDING']; ?>
</label>
                          <label><input type="radio" name="order_direction" value="desc" <?php if ($this->_tpl_vars['listings_view']->order_direction == 'desc'): ?> checked <?php endif; ?> /> <?php echo $this->_tpl_vars['LANG_SORT_DESCENDING']; ?>
</label>
                     </div>
                     <div class="px10"></div>
                     <?php if ($this->_tpl_vars['type']): ?>
                     <div class="admin_option">
                          <div class="admin_option_name">
                          	<?php echo $this->_tpl_vars['LANG_LISTING_LEVELS_VISIBLE']; ?>
<span class="red_asterisk">*</span>
                          </div>
                          <?php $_from = $this->_tpl_vars['type']->levels; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['level']):
?>
                          <label><input type="checkbox" id="level_<?php echo $this->_tpl_vars['level']->id; ?>
" class="levels_visible" <?php if ($this->_tpl_vars['VH']->in_array($this->_tpl_vars['level']->id,$this->_tpl_vars['listings_view']->levels_visible) || count($this->_tpl_vars['listings_view']->levels_visible) == 0): ?> checked <?php endif; ?> /> <?php echo $this->_tpl_vars['level']->name; ?>
</label>
                          <?php endforeach; endif; unset($_from); ?>
                          <input type="hidden" name="serialised_levels" id="serialised_levels" value="<?php echo $this->_tpl_vars['VH']->implode(',',$this->_tpl_vars['listings_view']->levels_visible); ?>
">
                     </div>
                     <?php endif; ?>
                     <div class="px10"></div>
                     <input class="button save_button" type=submit name="submit" value="<?php echo $this->_tpl_vars['LANG_BUTTON_SAVE_CHANGES']; ?>
">
                     </form>
                </div>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/admin_footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>