					<tr>
						<td class="footer_line" colspan="3">
							<div id="footer">
							 {$VH->buildContentPagesMenu_bottom($CI)}
							 </div>
							 <div class="px5"></div>
							 <div class="copyright">
							 <p>© 1999-2012 PT Nasco | Use of this Web site constitutes acceptance of the Nasco User Agreement and Privacy Policy.</p>
							</div>
						</td>
					</tr>
			        <tr>
						<td>
						<div id="">
						  <div class="px5"></div>
						  <div class="px5"></div>
						</div>
						</td>
                   </tr>
				</table>
		</div>
		<!-- Content Ends -->
	</div>

{if $system_settings.google_analytics_profile_id}
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
<script type="text/javascript">
	_uacct = "{$system_settings.google_analytics_account_id}";
	urchinTracker();
</script>
{/if}
</body>
</html>