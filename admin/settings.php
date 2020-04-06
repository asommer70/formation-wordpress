<div class="wrap">

	<h1>Settings</h1>

  <div class="flash">
		<?php echo (isset($flash) ? $flash : ''); ?>
  </div>

	<div id="poststuff">

		<div id="post-body" class="metabox-holder">

			<!-- main content -->
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<div class="postbox">

						<div class="inside">
							<br/>

							<form class="" action="/wp-admin/admin.php?page=formation-settings" method="post">
								<label for="from_email">From Email</label><br>
								<input type="text" name="from_email" class="regular-text" value="<?php echo $from_email; ?>" /><br>
								<small>Email address messages will be sent from.</small>
								<br/><br/>

								<label for="form_css">Form CSS</label><br>
								<input type="text" name="form_css" class="regular-text" value="<?php echo $form_css; ?>" /><br>
								<small>URL of CSS to apply to form (used to view Forms in the Inbox).</small>
								<br/>

								<br/>
								<input class="button-secondary" type="submit" name="save_settings" value="Save Settings" />
							</form>

							<br/>
					</div>
				</div>
			</div>

		<br class="clear">
	</div>
</div>
