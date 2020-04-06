<div class="wrap">

	<h1>Forms List</h1>

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


              <a class="button-primary" href="/wp-admin/admin.php?page=formation&formation_action=form">New Form</a>

              <br><br>

              <table class="widefat">
              	<thead>
              	<tr>
              		<th>ID</th>
              		<th>Name</th>
                  <th>Shortcode</th>
              	</tr>
              	</thead>
              	<tbody>
                  <?php foreach($forms as $form) { ?>
                  	<tr>
                  		<td>
                        <?php echo $form->id; ?>
                      </td>
                  		<td>
                        <a href="/wp-admin/admin.php?page=formation&formation_action=form&form_id=<?php echo $form->id; ?>">
                          <?php echo $form->name; ?>
                        </a>
                      </td>
                      <td>
                        [formation id=<?php echo $form->id; ?>]
                      </td>
                  	</tr>
                  <?php } ?>
              	</tbody>
              </table>

					</div>
				</div>
			</div>

		<br class="clear">
	</div>
</div>
