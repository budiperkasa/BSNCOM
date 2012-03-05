<?php /* Smarty version 2.6.26, created on 2012-03-05 07:19:14
         compiled from frontend/listing_page.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'frontend/listing_page.tpl', 41, false),array('modifier', 'date_format', 'frontend/listing_page.tpl', 91, false),array('modifier', 'nl2br', 'frontend/listing_page.tpl', 217, false),array('function', 'render_frontend_block', 'frontend/listing_page.tpl', 257, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "frontend/header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php $this->assign('listing_id', $this->_tpl_vars['listing']->id); ?>
<?php $this->assign('listing_unique_id', $this->_tpl_vars['listing']->getUniqueId()); ?>
<?php $this->assign('user_unique_id', $this->_tpl_vars['listing']->user->getUniqueId()); ?>
<?php $this->assign('user_login', $this->_tpl_vars['listing']->user->login); ?>

<?php $this->assign('max_images_for_carousel', 9); ?>

			<tr>
				<td id="search_bar" colspan="3">
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "frontend/search_block.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
				</td>
			</tr>
			<tr>
				<td id="left_sidebar">
				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "frontend/left-sidebar.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
				</td>
      			<td id="content_block" valign="top">
      				<div id="content_wrapper">
      				<?php if ($this->_tpl_vars['listing']->title != 'untranslated'): ?>
						<script language="Javascript" type="text/javascript">
						$(document).ready(function() {
							var $tabs = $("#tabs").tabs();
							$('.open_tab').click(function() {
								$tabs.tabs('select', $(this).attr("href"));
								moveSlowly($(this));
								return false;
							});

							$('a.lightbox_image').live('mouseover', function(){
								$('a.lightbox_image, a.hidden_imgs').lightBox({
									overlayOpacity: 0.75,
									imageLoading: '<?php echo $this->_tpl_vars['public_path']; ?>
images/lightbox-ico-loading.gif',
									imageBtnClose: '<?php echo $this->_tpl_vars['public_path']; ?>
images/lightbox-btn-close.gif',
									imageBtnPrev: '<?php echo $this->_tpl_vars['public_path']; ?>
images/lightbox-btn-prev.gif',
									imageBtnNext: '<?php echo $this->_tpl_vars['public_path']; ?>
images/lightbox-btn-next.gif'
								});
							});

							<?php if ($this->_tpl_vars['listing']->level->images_count && count($this->_tpl_vars['images'])): ?>
							$(".img_small").click(function() {
								if ($("#listing_logo").find("img").attr("src") != "<?php echo $this->_tpl_vars['public_path']; ?>
images/lightbox-ico-loading.gif") {
									var img_small = $(this).clone();
									// retrieve basename of image file
									var file_name = img_small.find("img").attr('src').replace(/^.*\/|\.*$/g, '');

									// Set the size of big image and place ajax loader there
									var big_image_width = $("#listing_logo").find("img").width();
									var big_image_height = $("#listing_logo").find("img").height();
									
									$(".lightbox_image").html("<img src='<?php echo $this->_tpl_vars['public_path']; ?>
images/lightbox-ico-loading.gif' />");
									$("#listing_logo").css("width", big_image_width);
									$("#listing_logo").css("height", big_image_height);
									$(".lightbox_image").css("position", "relative");
									$(".lightbox_image").css("top", (big_image_height/2)-20);
									$(".lightbox_image").css("left", (big_image_width/2)-20);

									// Remove thmb of logo from lighbox images
									$(".hidden_divs").each(function() {
										if (file_name == $(this).find("a").attr('href').replace(/^.*\/|\.*$/g, ''))
											$(this).find("a").removeClass("hidden_imgs");
										else
											$(this).find("a").addClass("hidden_imgs");
									});

									// Load new image into big image container
									var img = new Image();
							        $(img).load(function () {
							            $(this).hide();
							            img_small.html(this);
							            img_small.removeClass("img_small").addClass("lightbox_image");
							            $("#listing_logo").html(img_small);
							            $("#listing_logo").css("width", img_small.find("img").width());
										$("#listing_logo").css("height", img_small.find("img").height());
							            $(this).fadeIn();
							        }).attr('src', '<?php echo $this->_tpl_vars['users_content']; ?>
/users_images/thmbs_big/'+file_name);
								}
								return false;
							});
							<?php endif; ?>
						});
						</script>

						<div class="breadcrumbs"><a href="<?php echo $this->_tpl_vars['VH']->index_url(); ?>
"><?php echo $this->_tpl_vars['LANG_HOME_PAGE']; ?>
</a><?php $_from = $this->_tpl_vars['breadcrumbs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['source_url'] => $this->_tpl_vars['source_page']):
?><?php if ($this->_tpl_vars['source_page']): ?> » <a href="<?php echo $this->_tpl_vars['source_url']; ?>
"><?php echo $this->_tpl_vars['source_page']; ?>
</a><?php endif; ?><?php endforeach; endif; unset($_from); ?> » <span><?php echo $this->_tpl_vars['listing']->title(); ?>
</span></div>
						<div id="messages" style="display: none;"></div>

						<div id="listing_header_block">
	                        <div style="float: left; width: 345px;">
	                        	<h1 class="listing_title"><?php echo $this->_tpl_vars['listing']->title(); ?>
</h1>
		                        <div class="listing_author"><?php echo $this->_tpl_vars['LANG_SUMITTED_1']; ?>
 <?php if ($this->_tpl_vars['listing']->user->users_group->is_own_page && $this->_tpl_vars['listing']->user->users_group->id != 1): ?><a href="<?php echo $this->_tpl_vars['VH']->site_url("users/".($this->_tpl_vars['user_unique_id'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_VIEW_USER_PAGE_OPTION']; ?>
"><?php echo $this->_tpl_vars['listing']->user->login; ?>
</a><?php else: ?><strong><?php echo $this->_tpl_vars['listing']->user->login; ?>
</strong><?php endif; ?> <?php echo $this->_tpl_vars['LANG_SUMITTED_2']; ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['listing']->creation_date)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%D %H:%M") : smarty_modifier_date_format($_tmp, "%D %H:%M")); ?>
</div>
		                        <?php if ($this->_tpl_vars['listing']->type->categories_type != 'disabled' && $this->_tpl_vars['listing']->level->categories_number && count($this->_tpl_vars['listing']->categories_array())): ?>
			                        <div class="listing_page_categories"><?php echo $this->_tpl_vars['LANG_SUMITTED_3']; ?>
 
			                        <?php $_from = $this->_tpl_vars['listing']->categories_array(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['category']):
?>
			                        	<a href="<?php echo $this->_tpl_vars['VH']->site_url($this->_tpl_vars['category']->getUrl()); ?>
" class="listing_cat_link"><?php echo $this->_tpl_vars['category']->name; ?>
</a>&nbsp;&nbsp;
			                        <?php endforeach; endif; unset($_from); ?>
			                        </div>
		                        <?php endif; ?>
		                        <div class="clear_float"></div>

		                        <?php if ($this->_tpl_vars['listing']->level->ratings_enabled): ?>
		                        	<?php $this->assign('avg_rating', $this->_tpl_vars['listing']->getRatings()); ?>
									<?php echo $this->_tpl_vars['avg_rating']->view(); ?>

								<?php endif; ?>
		                        <div class="clear_float"></div>
		                        <div class="px5"></div>
		                        
		                        <?php if (( $this->_tpl_vars['listing']->level->logo_enabled && $this->_tpl_vars['listing']->logo_file ) || count($this->_tpl_vars['images'])): ?>
		                        <div id="listing_logo">
		                        	<?php if ($this->_tpl_vars['listing']->level->logo_enabled && $this->_tpl_vars['listing']->logo_file): ?>
		                        		<?php $this->assign('logo', $this->_tpl_vars['listing']->logo_file); ?>
		                        	<?php else: ?>
		                        		<?php $this->assign('logo', $this->_tpl_vars['images'][0]->file); ?>
		                        	<?php endif; ?>
									<a href="<?php echo $this->_tpl_vars['users_content']; ?>
users_images/images/<?php echo $this->_tpl_vars['logo']; ?>
" class="lightbox_image" title="<?php echo $this->_tpl_vars['listing']->title(); ?>
"><img src="<?php echo $this->_tpl_vars['users_content']; ?>
/users_images/thmbs_big/<?php echo $this->_tpl_vars['logo']; ?>
" alt="<?php echo $this->_tpl_vars['listing']->title(); ?>
"/></a>
								</div>
								<?php endif; ?>
								
								<?php if ($this->_tpl_vars['listing']->level->images_count && count($this->_tpl_vars['images'])): ?>
								<?php if (count($this->_tpl_vars['images']) <= $this->_tpl_vars['max_images_for_carousel']): ?>
									<?php $this->assign('columns_num', 3); ?>
									<div class="listing_images_gallery">
										<table>
											<tr>
											<?php $this->assign('i', 0); ?>
											<?php $_from = $this->_tpl_vars['images']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['image']):
?>
												<?php $this->assign('i', $this->_tpl_vars['i']+1); ?>
												<td align="center" valign="middle" class="small_image_bg">
													<a href="<?php echo $this->_tpl_vars['users_content']; ?>
users_images/images/<?php echo $this->_tpl_vars['image']->file; ?>
" class="img_small" title="<?php echo $this->_tpl_vars['image']->title; ?>
"><img src="<?php echo $this->_tpl_vars['users_content']; ?>
/users_images/thmbs_small/<?php echo $this->_tpl_vars['image']->file; ?>
"/></a>
													<div class="hidden_divs" style="display:none"><a href="<?php echo $this->_tpl_vars['users_content']; ?>
users_images/images/<?php echo $this->_tpl_vars['image']->file; ?>
" <?php if ($this->_tpl_vars['image']->file != $this->_tpl_vars['listing']->logo_file): ?>class="hidden_imgs"<?php endif; ?>></a></div>
												</td>
												<?php if ($this->_tpl_vars['i'] >= $this->_tpl_vars['columns_num']): ?>
												</tr><tr>
												<?php $this->assign('i', 0); ?>
												<?php endif; ?>
											<?php endforeach; endif; unset($_from); ?>
											</tr>
										</table>
									</div>
								<?php endif; ?>
								<?php endif; ?>
							</div>

							<div id="listing_options_panel">
								<?php if ($this->_tpl_vars['listing']->level->social_bookmarks_enabled): ?>
									<a class="a2a_dd" href="http://www.addtoany.com/share_save"><img src="http://static.addtoany.com/buttons/share_save_171_16.png" width="171" height="16" border="0" alt="Share/Bookmark"/></a><script type="text/javascript" src="http://static.addtoany.com/menu/page.js"></script>
								<?php endif; ?>
								<?php if ($this->_tpl_vars['listing']->user->id == $this->_tpl_vars['session_user_id'] || $this->_tpl_vars['content_access_obj']->isPermission('Manage all listings')): ?>
									<span class="listing_options_panel_item edit"><a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/edit/".($this->_tpl_vars['listing_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_EDIT_LISTING_OPTION']; ?>
</a></span>
								<?php endif; ?>
								<?php if ($this->_tpl_vars['listing']->level->option_print): ?>
									<span class="listing_options_panel_item print"><a href="javascript: void(0);" rel="nofollow" onClick="$.jqURL.loc('<?php echo $this->_tpl_vars['VH']->site_url("print_listing/".($this->_tpl_vars['listing_unique_id'])."/"); ?>
', {w:590,h:750,wintype:'_blank'}); return false;"><?php echo $this->_tpl_vars['LANG_PRINT_PAGE']; ?>
</a></span>
								<?php endif; ?>
								<?php if ($this->_tpl_vars['listing']->level->option_pdf): ?>
									<span class="listing_options_panel_item pdf"><a href="http://pdfmyurl.com/?url=<?php echo $this->_tpl_vars['VH']->site_url("listings/".($this->_tpl_vars['listing_unique_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_PDF_PAGE']; ?>
</a></span>
								<?php endif; ?>
								<?php if ($this->_tpl_vars['listing']->level->option_quick_list): ?>
									<a href="javascript: void(0);" class="add_to_favourites" listingid="<?php echo $this->_tpl_vars['listing']->id; ?>
" title="<?php echo $this->_tpl_vars['LANG_ADD_REMOVE_QUICK_LIST']; ?>
"></a>&nbsp;<a href="javascript: void(0);" class="add_to_favourites_button"><?php echo $this->_tpl_vars['LANG_ADD_REMOVE_QUICK_LIST']; ?>
</a>&nbsp;
								<?php endif; ?>
								<?php if ($this->_tpl_vars['listing']->level->option_email_friend): ?>
									<span class="listing_options_panel_item friend"><a href="<?php echo $this->_tpl_vars['VH']->site_url("email/send/listing_id/".($this->_tpl_vars['listing_id'])."/target/friend/"); ?>
" class="nyroModal"><?php echo $this->_tpl_vars['LANG_EMAIL_FRIEND']; ?>
</a></span>
								<?php endif; ?>
								<?php if ($this->_tpl_vars['listing']->level->option_email_owner): ?>
									<span class="listing_options_panel_item owner"><a href="<?php echo $this->_tpl_vars['VH']->site_url("email/send/listing_id/".($this->_tpl_vars['listing_id'])."/target/owner/"); ?>
" class="nyroModal"><?php echo $this->_tpl_vars['LANG_EMAIL_OWNER']; ?>
</a></span>
								<?php endif; ?>
								<span class="user_options_panel_item all_listings"><a href="<?php echo $this->_tpl_vars['VH']->site_url("search/search_owner/".($this->_tpl_vars['user_login'])); ?>
" title="<?php echo $this->_tpl_vars['LANG_USER_VIEW_ALL_LISTINGS']; ?>
"><?php echo $this->_tpl_vars['LANG_USER_VIEW_ALL_LISTINGS']; ?>
</a></span>
								<?php if ($this->_tpl_vars['listing']->level->option_report): ?>
		                        	<span class="listing_options_panel_item report"><a href="<?php echo $this->_tpl_vars['VH']->site_url("email/send/listing_id/".($this->_tpl_vars['listing_id'])."/target/report/"); ?>
" class="nyroModal"><?php echo $this->_tpl_vars['LANG_REPORT_ADMIN']; ?>
</a></span>
		                        <?php endif; ?>
								<?php if ($this->_tpl_vars['listing']->type->locations_enabled && $this->_tpl_vars['listing']->locations_count() && $this->_tpl_vars['listing']->level->maps_enabled): ?>
									<span class="listing_options_panel_item map"><a href="#addresses-tab" id="addresses" class="open_tab"><?php echo $this->_tpl_vars['LANG_LISTING_ADDRESSES_OPTION']; ?>
</a></span>
								<?php endif; ?>
								<?php if ($this->_tpl_vars['listing']->level->video_count && $this->_tpl_vars['listing']->getAssignedVideos()): ?>
									<span class="listing_options_panel_item videos"><a href="#videos-tab" id="videos" class="open_tab"><?php echo $this->_tpl_vars['LANG_LISTING_VIDEOS_OPTION']; ?>
</a></span>
								<?php endif; ?>
								<?php if ($this->_tpl_vars['listing']->level->files_count && $this->_tpl_vars['listing']->getAssignedFiles()): ?>
									<span class="listing_options_panel_item files"><a href="#files-tab" id="files" class="open_tab"><?php echo $this->_tpl_vars['LANG_LISTING_FILES_OPTION']; ?>
</a></span>
								<?php endif; ?>
								<?php if ($this->_tpl_vars['listing']->level->reviews_mode && $this->_tpl_vars['listing']->level->reviews_mode != 'disabled'): ?>
		                        	<span class="listing_options_panel_item reviews"><a href="#reviews-tab" id="reviews" class="open_tab"><?php echo $this->_tpl_vars['listing']->getReviewsCount(); ?>
 <?php if ($this->_tpl_vars['listing']->level->reviews_mode == 'reviews'): ?><?php echo $this->_tpl_vars['LANG_REVIEWS']; ?>
<?php else: ?><?php echo $this->_tpl_vars['LANG_COMMENTS']; ?>
<?php endif; ?></a></span>
		                        <?php endif; ?>
		                        <?php if ($this->_tpl_vars['content_access_obj']->isPermission('Claim on listings') && $this->_tpl_vars['listing']->claim_row['ability_to_claim']): ?>
		                        	<span class="listing_options_panel_item claim"><a href="<?php echo $this->_tpl_vars['VH']->site_url("listings/claim/".($this->_tpl_vars['listing_unique_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_CLAIM_LISTING_OPTION']; ?>
</a></span>
		                        <?php endif; ?>
		                        <?php if ($this->_tpl_vars['listing']->user->users_group->is_own_page && $this->_tpl_vars['listing']->user->users_group->id != 1): ?>
		                        	<span class="listing_options_panel_item author"><a href="<?php echo $this->_tpl_vars['VH']->site_url("users/".($this->_tpl_vars['user_unique_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_VIEW_USER_PAGE_OPTION']; ?>
</a></span>
		                        <?php endif; ?>
		                        <?php if ($this->_tpl_vars['content_access_obj']->isPermission('Change listing level') && count($this->_tpl_vars['listing']->type->buildLevels()) > 1): ?>
		                        	<span class="listing_options_panel_item upgrade"><?php echo $this->_tpl_vars['LANG_LEVEL_TH']; ?>
: <?php echo $this->_tpl_vars['listing']->level->name; ?>
 (<a href="<?php echo $this->_tpl_vars['VH']->site_url("admin/listings/change_level/".($this->_tpl_vars['listing_id'])); ?>
"><?php echo $this->_tpl_vars['LANG_CHANGE_LISTING_LEVEL_OPTION']; ?>
</a>)</span>
		                        <?php endif; ?>
							</div>
							<div class="clear_float"></div>
						</div>
						<?php if (count($this->_tpl_vars['images']) > $this->_tpl_vars['max_images_for_carousel']): ?>
							<!-- Carousel js gallery -->
							<script type="text/javascript">
								jQuery(document).ready(function() {
								    jQuery('#mycarousel').jcarousel({visible:4, scroll:2});
								});
							</script>
							<ul id="mycarousel" class="jcarousel-skin-tango">
								<?php $_from = $this->_tpl_vars['images']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['image']):
?>
									<li>
										<a href="<?php echo $this->_tpl_vars['users_content']; ?>
users_images/images/<?php echo $this->_tpl_vars['image']->file; ?>
" class="img_small" title="<?php echo $this->_tpl_vars['image']->title; ?>
"><img src="<?php echo $this->_tpl_vars['users_content']; ?>
/users_images/thmbs_small/<?php echo $this->_tpl_vars['image']->file; ?>
"/></a>
										<div class="hidden_divs" style="display:none"><a href="<?php echo $this->_tpl_vars['users_content']; ?>
users_images/images/<?php echo $this->_tpl_vars['image']->file; ?>
" <?php if ($this->_tpl_vars['image']->file != $this->_tpl_vars['listing']->logo_file): ?>class="hidden_imgs"<?php endif; ?>></a></div>
									</li>
								<?php endforeach; endif; unset($_from); ?>
							</ul>
						<?php endif; ?>

						<?php if ($this->_tpl_vars['listing']->listing_description): ?>
						<h1><?php echo $this->_tpl_vars['LANG_LISTING_SHORT_DESCRIPTION']; ?>
</h1>
						<div id="listing_description">
							<?php if ($this->_tpl_vars['listing']->level->description_mode == 'richtext'): ?>
							<?php echo $this->_tpl_vars['listing']->listing_description; ?>

							<?php else: ?>
							<?php echo ((is_array($_tmp=$this->_tpl_vars['listing']->listing_description)) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>

							<?php endif; ?>
						</div>
						<?php endif; ?>

						<?php if ($this->_tpl_vars['listing']->content_fields->fieldsCount() && $this->_tpl_vars['listing']->content_fields->isAnyValue()): ?>
							<h1><?php echo $this->_tpl_vars['LANG_LISTING_INFORMATION']; ?>
</h1>
		                   	<?php echo $this->_tpl_vars['listing']->content_fields->outputMode(); ?>

		                   	<div class="px10"></div>
						<?php endif; ?>
						
							<?php if ($this->_tpl_vars['available_options_count'] > 1): ?>
						<div id="tabs">
							<ul>
								<?php if ($this->_tpl_vars['listing']->type->locations_enabled && $this->_tpl_vars['listing']->locations_count()): ?>
									<li><a href="#addresses-tab"><?php echo $this->_tpl_vars['LANG_LISTING_ADDRESS']; ?>
</a></li>
								<?php endif; ?>
								<?php if ($this->_tpl_vars['listing']->level->video_count && $this->_tpl_vars['listing']->getAssignedVideos()): ?>
									<li><a href="#videos-tab"><?php echo $this->_tpl_vars['LANG_LISTING_VIDEOS']; ?>
</a></li>
								<?php endif; ?>
								<?php if ($this->_tpl_vars['listing']->level->files_count && $this->_tpl_vars['listing']->getAssignedFiles()): ?>
									<li><a href="#files-tab"><?php echo $this->_tpl_vars['LANG_LISTING_FILES']; ?>
</a></li>
								<?php endif; ?>
								<?php if ($this->_tpl_vars['listing']->level->reviews_mode && $this->_tpl_vars['listing']->level->reviews_mode != 'disabled'): ?>
									<li><a href="#reviews-tab"><?php if ($this->_tpl_vars['listing']->level->reviews_mode == 'reviews'): ?><?php echo $this->_tpl_vars['LANG_REVIEWS']; ?>
<?php elseif ($this->_tpl_vars['listing']->level->reviews_mode == 'comments'): ?><?php echo $this->_tpl_vars['LANG_COMMENTS']; ?>
<?php endif; ?> (<?php echo $this->_tpl_vars['listing']->getReviewsCount(); ?>
)</a></li>
								<?php endif; ?>
							</ul>
							<?php endif; ?>


	                        <?php if ($this->_tpl_vars['listing']->type->locations_enabled && $this->_tpl_vars['listing']->locations_count()): ?>
								<div id="addresses-tab">
									<h1 id="map"><?php echo $this->_tpl_vars['LANG_LISTING_ADDRESS']; ?>
</h1>
									<div class="listing_address_block">
										<?php $this->assign('i', 1); ?>
										<?php $_from = $this->_tpl_vars['listing']->locations_array(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['location']):
?>
										<div class="address_line"><?php if ($this->_tpl_vars['listing']->locations_count() > 1): ?><span class="address_label"><?php echo $this->_tpl_vars['LANG_LISTING_ADDRESS']; ?>
 <?php echo $this->_tpl_vars['i']++; ?>
:</span> <?php endif; ?><?php echo $this->_tpl_vars['location']->compileAddress(); ?>
</div>
										<?php endforeach; endif; unset($_from); ?>
									</div>
									<?php if ($this->_tpl_vars['listing']->level->maps_enabled && $this->_tpl_vars['listing']->locations_count(true)): ?>
							      		<?php echo smarty_function_render_frontend_block(array('block_type' => 'map_and_markers','block_template' => 'frontend/blocks/map_standart_directions.tpl','existed_listings' => $this->_tpl_vars['listing'],'map_width' => $this->_tpl_vars['listing']->level->explodeSize('maps_size','width'),'map_height' => $this->_tpl_vars['listing']->level->explodeSize('maps_size','height'),'show_anchors' => false,'show_links' => false), $this);?>

									<?php endif; ?>
								</div>
							<?php endif; ?>

							<?php if ($this->_tpl_vars['listing']->level->video_count && $this->_tpl_vars['listing']->getAssignedVideos()): ?>
								<div id="videos-tab">
									<h1 id="videos"><?php echo $this->_tpl_vars['LANG_LISTING_VIDEOS']; ?>
</h1>
									<?php $_from = $this->_tpl_vars['videos']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['video']):
?>
									<div class="listing_videos_block">
										<div>
		                         			<object width="<?php echo $this->_tpl_vars['listing']->level->explodeSize('video_size','width'); ?>
" height="<?php echo $this->_tpl_vars['listing']->level->explodeSize('video_size','height'); ?>
"><param name="movie" value="http://www.youtube.com/v/<?php echo $this->_tpl_vars['video']->video_code; ?>
&hl=en&fs=1&"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/<?php echo $this->_tpl_vars['video']->video_code; ?>
&hl=en&fs=1&" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="<?php echo $this->_tpl_vars['listing']->level->explodeSize('video_size','width'); ?>
" height="<?php echo $this->_tpl_vars['listing']->level->explodeSize('video_size','height'); ?>
"></embed></object>
		                         		</div>
		                         		<div class="listing_videos_title">
		                         			<?php echo $this->_tpl_vars['video']->title; ?>

		                         		</div>
		                         	</div>
									<?php endforeach; endif; unset($_from); ?>
								</div>
							<?php endif; ?>
							
							<?php if ($this->_tpl_vars['listing']->level->files_count && $this->_tpl_vars['listing']->getAssignedFiles()): ?>
								<div id="files-tab">
									<h1 id="files"><?php echo $this->_tpl_vars['LANG_LISTING_FILES']; ?>
</h1>
									<div class="listing_files_block">
									<?php $_from = $this->_tpl_vars['files']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['file']):
?>
										<?php $this->assign('file_id', $this->_tpl_vars['file']->id); ?>
										<span class="listing_file_item" style="background: url('<?php echo $this->_tpl_vars['public_path']; ?>
images/file_types/<?php echo $this->_tpl_vars['file']->file_format; ?>
.png') 0 0 no-repeat;"><a href="<?php echo $this->_tpl_vars['VH']->site_url("download/".($this->_tpl_vars['file_id'])); ?>
" target="_blank"><?php echo $this->_tpl_vars['file']->title; ?>
 (<?php echo $this->_tpl_vars['file']->file_size; ?>
)</a></span>
									<?php endforeach; endif; unset($_from); ?>
									</div>
								</div>
							<?php endif; ?>
							
							<?php if ($this->_tpl_vars['listing']->level->reviews_mode && $this->_tpl_vars['listing']->level->reviews_mode != 'disabled'): ?>
								<div id="reviews-tab">
									<?php echo smarty_function_render_frontend_block(array('block_type' => 'reviews_comments','block_template' => 'frontend/blocks/reviews_comments_add.tpl','objects_table' => 'listings','objects_ids' => $this->_tpl_vars['listing']->id,'comment_area_enabled' => true,'reviews_mode' => $this->_tpl_vars['listing']->level->reviews_mode,'admin_mode' => false,'is_richtext' => $this->_tpl_vars['listing']->level->reviews_richtext_enabled), $this);?>

								</div>
							<?php endif; ?>
							
						<?php if ($this->_tpl_vars['available_options_count'] > 1): ?>
						</div>
						<?php endif; ?>
					<?php else: ?>
					<div class="error_msg rounded_corners">
						<ul>
							<li><?php echo $this->_tpl_vars['LANG_LISTING_TRANSLATION_ERROR']; ?>
</li>
						</ul>
					</div>
					<?php endif; ?>
						
                 	</div>
                </td>
                <td id="right_sidebar">
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "frontend/right-sidebar.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
                </td>
			</tr>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "frontend/footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>