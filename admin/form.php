<div class="wrap">

  <?php if (isset($form->id)) { ?>
    <h1>Edit <em><?php echo $form->name; ?></em></h1>
  <?php } else { ?>
    <h1>New Form</h1>
  <?php } ?>

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

            <form id="formation_form" action="/wp-admin/admin.php?page=formation" method="post">
              <label for="name">Form Name</label><br>
              <input type="text" name="name" class="regular-text" value="<?php echo (isset($form) ? $form->name : ''); ?>" /><br>
              <br>

              <label for="html">Form HTML</label><br>
              <textarea name="html" id="formation-form-html" cols="80" rows="10" class="large-text"><?php echo (isset($form) ? stripslashes(htmlspecialchars($form->html)) : ''); ?></textarea>
              <br><br>

              <label for="email">Email Notification</label><br>
              <input type="text" name="email" class="regular-text" placeholder="Can use a comma separated list of emails." value="<?php echo (isset($form) ? $form->email : ''); ?>" /><br>
              <br>

              <label for="confirmation">Confirmation Message</label><br>
              <textarea name="confirmation" id="formation-confirmation-html" cols="80" rows="10" class="large-text" placeholder="Copy 'n Paste some HTML"><?php echo (isset($form) ? stripslashes(htmlspecialchars($form->confirmation)) : ''); ?></textarea>
              <br><br>

              <?php if (isset($form->id)) { ?>
                <input type="hidden" name="form_id" id="form_id" value="<?php echo (isset($form) ? $form->id : ''); ?>" />
                <input class="button-secondary" type="submit" name="update_form" value="Update Form" />
                <div style="float: right">
                  <input type="submit" name="delete_form" id="delete_form" class="button button-small" value="Delete Form" />
                </div>
              <?php } else { ?>
                <input class="button-secondary" type="submit" name="new_form" value="Save Form" />
              <?php } ?>
            </form>

            <br><br>
					</div>
				</div>
        <a class="button-primary" href="/wp-admin/admin.php?page=formation">Back</a>

			</div>

		<br class="clear">
	</div>
</div>
