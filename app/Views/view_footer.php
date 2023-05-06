			<footer class="main-footer">
				<div class="pull-right hidden-xs">


					<strong>ANDRITZ</strong>
				</div>
			</footer>
			<!--</div>-->

			<!-- jQuery 3 -->
			<script src="<?= base_url() ?>assets/bootstrap/js/jquery.min.js"></script>
			<!-- Jquery UI -->
			<script src="<?= base_url() ?>assets/bootstrap/js/jquery-ui.min.js"></script>
			<script src="<?= base_url() ?>assets/bootstrap/js/jquery.ui.autocomplete.scroll.min.js"></script>

			<script src="<?= base_url() ?>assets/bootstrap/js/moment.min.js"></script>
			<!-- Bootstrap 3.3.7 -->
			<script src="<?= base_url() ?>assets/bootstrap/js/bootstrap.min.js"></script>
			<!-- Select2 -->
			<script src="<?= base_url() ?>assets/bootstrap/js/select2.full.min.js"></script>
			<!-- DataTables -->
			<script src="<?= base_url() ?>assets/bootstrap/js/jquery.dataTables.min.js"></script>
			<script src="<?= base_url() ?>assets/bootstrap/js/dataTables.bootstrap.min.js"></script>
			<!-- bootstrap color picker -->
			<script src="<?= base_url() ?>assets/bootstrap/js/bootstrap-colorpicker.min.js"></script>
			<!-- bootstrap Date time picker -->
			<script src="<?= base_url() ?>assets/bootstrap/js/bootstrap-datetimepicker.min.js"></script>
			<script src="<?= base_url() ?>assets/bootstrap/js/id.js"></script>
			<!-- bootstrap Date picker -->
			<script src="<?= base_url() ?>assets/bootstrap/js/bootstrap-datepicker.min.js"></script>
			<script src="<?= base_url() ?>assets/bootstrap/js/bootstrap-datepicker.id.min.js"></script>
			<!-- Bootstrap WYSIHTML5 -->
			<script src="<?= base_url() ?>assets/bootstrap/js/bootstrap3-wysihtml5.all.min.js"></script>
			<!-- Slimscroll -->
			<script src="<?= base_url() ?>assets/bootstrap/js/jquery.slimscroll.min.js"></script>
			<!-- FastClick -->
			<script src="<?= base_url() ?>assets/bootstrap/js/fastclick.js"></script>
			<!-- AdminLTE App -->
			<script src="<?= base_url() ?>assets/js/adminlte.min.js"></script>
			<script src="<?= base_url() ?>assets/js/validasi.js"></script>
			<script src="<?= base_url() ?>assets/js/jquery.validate.min.js"></script>
			<script src="<?= base_url() ?>assets/js/localization/messages_id.js"></script>
			<!-- Numeral js -->
			<script src="<?= base_url() ?>assets/js/numeral.min.js"></script>
			<!-- Script-->
			<script src="<?= base_url() ?>assets/js/script.js"></script>

			<script type="text/javascript">
				$('document').ready(function() {

					setTimeout(function() {

						if ($("#b_inbox").length) {
							$("#b_inbox").load("<?= base_url() ?>notif/inbox/<?= $usernamelgn ?>");
							var refreshInbox = setInterval(function() {
								$("#b_inbox").load("<?= base_url() ?>notif/inbox/<?= $usernamelgn ?>");
							}, 10000);
						}
					}, 500);

					notification(notify, notify_msg);
					$('#success-code').val('');
				});

				$('document').ready(function() {

					setTimeout(function() {

						if ($("#c_inbox").length) {
							$("#c_inbox").load("<?= base_url() ?>notif/inbox/<?= $usernamelgn ?>");
							var refreshInbox = setInterval(function() {
								$("#c_inbox").load("<?= base_url() ?>notif/inbox/<?= $usernamelgn ?>");
							}, 10000);
						}
					}, 500);

					if ($('#success-code').val() == 1) {
						notify = 'success';
						notify_msg = 'data saved successfully';
					} else if ($('#success-code').val() == -1) {
						notify = 'error';
						notify_msg = 'data failed to save';
					} else {
						notify = '';
						notify_msg = '';
					}
					notification(notify, notify_msg);
					$('#success-code').val('');
				});
			</script>
			<?= session()->GET('success') == 0; ?>

			</body>

			</html>