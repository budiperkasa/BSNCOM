					<tr>
						<td class="footer_line" colspan="3">
							<div id="footer">
								Copyright © 2012 <a href="http://www.salephpscripts.com">Web 2.0 Directory script</a>
								{$VH->buildContentPagesMenu_bottom($CI)}
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