<div class="wrap">

	<h1>Inbox</h1>

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

							<table class="widefat">
								<thead>
								<tr>
									<th>ID</th>
									<th>Form Name</th>
									<th>Data</th>
									<th>Created At</th>
								</tr>
								</thead>
								<tbody>
									<?php foreach($forms as $form) { ?>
										<tr>
											<td>
												<?php echo $form->id; ?>
											</td>
											<td>
												<?php
													$args = '&input_id='. $form->id .'&form_id='. $form->form_id;
												?>
												<a href="/wp-admin/admin.php?page=formation-inbox&formation_action=input<?php echo $args; ?>">
													<?php echo $form->name; ?>
												</a>
											</td>
											<td>
												<?php echo substr($form->data, 0, 120) .'...'; ?>
											</td>
											<td>
												<?php echo $form->created_at; ?>
											</td>
										</tr>
									<?php } ?>
								</tbody>
							</table>

							<?php
								if (isset($_GET['paged'])) {
									$paged = $_GET['paged'];
								} else {
									$paged = 1;
								}

								if (isset($prev) && $prev == 0) {
									$prev = 1;
								} else {
									$prev = $paged - 1;
								}

								$last = round($forms[0]->total / 10);

								if ($paged > $last) {
									$next = $last;
								} else {
									$next = $paged + 1;
								}
							?>

							<div class="tablenav">
								<div class="tablenav-pages">
									<a class='first-page disabled' href="/wp-admin/admin.php?page=formation-inbox">&laquo;</a>
									<a class='prev-page disabled' href="/wp-admin/admin.php?page=formation-inbox&paged=<?php echo $prev; ?>">&lsaquo;</a>

									<span class="paging-input">
										<input class='current-page' type='text' name='paged' value="<?php echo $paged; ?>" size='1' />
										 of <span class='total-pages'><?php echo $last; ?></span>
									 </span>

									<a class='next-page' href="/wp-admin/admin.php?page=formation-inbox&paged=<?php echo $next; ?>">&rsaquo;</a>
									<a class='last-page' href="/wp-admin/admin.php?page=formation-inbox&paged=<?php echo $last; ?>">&raquo;</a>
								</div>
							</div>

					</div>
				</div>
			</div>
		<br class="clear">
	</div>
</div>
