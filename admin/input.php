<div class="wrap">

  <h1><?php echo $form->name; ?></h1>
  <p>Created: <em><?php echo $input->created_at; ?></em></p>

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

              <?php echo stripslashes($form->html); ?>

              <br><br>
  					</div>
  				</div>
          <a class="button-primary" href="/wp-admin/admin.php?page=formation-inbox">Back</a>

  			</div>

  		<br class="clear">
  	</div>
  </div>
  <script type="text/javascript">
    var disable = true;
    var input = <?php echo strip_tags($input->data); ?>;
  </script>
