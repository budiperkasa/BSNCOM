<?php /* Smarty version 2.6.26, created on 2012-02-06 02:23:50
         compiled from frontend/footer.tpl */ ?>
					<tr>
						<td class="footer_line" colspan="3">
							<div id="footer">
								Copyright © 2012 <a href="http://www.salephpscripts.com">Web 2.0 Directory script</a>
								<?php echo $this->_tpl_vars['VH']->buildContentPagesMenu_bottom($this->_tpl_vars['CI']); ?>

							</div>
						</td>
					</tr>
				</table>
		</div>
		<!-- Content Ends -->
	</div>

<?php if ($this->_tpl_vars['system_settings']['google_analytics_profile_id']): ?>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
<script type="text/javascript">
	_uacct = "<?php echo $this->_tpl_vars['system_settings']['google_analytics_account_id']; ?>
";
	urchinTracker();
</script>
<?php endif; ?>
</body>
</html>