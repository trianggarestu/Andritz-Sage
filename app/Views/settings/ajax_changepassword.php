<!-- Perubahan script coding untuk bisa menampilkan modal bootstrap edit password pengguna login -->
<div class='modal-header'>
	<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
	<h4 class='modal-title' id='myModalLabel'> Change Password</h4>
</div>
<form action="<?= $form_action;
				?>" method="post" id="validasi">
	<div class="modal-body" id="maincontent">
		<div class="row">
			<div class="col-sm-6">
				<div class="box box-danger">
					<div class="box-body">



						<div class="form-group">
							<label for="catatan">New Password</label>
							<input class="form-control input-sm required" minlength="6" maxlength="20" type="password" id="new_pass" name="new_pass" autofocus style="text-transform: uppercase;"></input>
						</div>
						<div class="form-group">
							<label for="catatan">Retype New Password</label>
							<input class="form-control input-sm required" minlength="6" maxlength="20" type="password" id="re_new_pass" name="re_new_pass" autofocus style="text-transform: uppercase;"></input>
						</div>

					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<input type="hidden" id="userhash" name="userhash" value="<?= $userhash ?>">
			<button type="button" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Close</button>
			<button id="btnSubmit" type="submit" class="btn btn-social btn-flat btn-info btn-sm"><i class='fa fa-check'></i> Save</button>
		</div>
	</div>
</form>
<script src="<?= base_url() ?>assets/bootstrap/js/jquery.min.js"></script>
<script src="<?= base_url() ?>assets/js/jquery.validate.min.js"></script>
<script src="<?= base_url() ?>assets/js/validasi.js"></script>
<script src="<?= base_url() ?>assets/js/localization/messages_id.js"></script>
<script>
	$('document').ready(function() {
		setTimeout(function() {
			$('#re_new_pass').rules('add', {
				equalTo: '#new_pass'
			})
		}, 500);

		$('#file_browser').click(function(e) {
			e.preventDefault();
			$('#file').click();
		});
		$('#file').change(function() {
			$('#file_path').val($(this).val());
		});
		$('#file_path').click(function() {
			$('#file_browser').click();
		});
	});
</script>