<!-- Perubahan script coding untuk bisa menampilkan modal bootstrap edit password pengguna login -->
<div class='modal-header'>
	<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
	<h4 class='modal-title' id='myModalLabel'> Manage User</h4>
</div>
<form action="<?= $form_action ?>" method="POST" id="validasi" enctype="multipart/form-data">
	<div class="modal-body" id="maincontent">
		<div class="row">
			<div class="col-md-3">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Upload Photo</h3>
					</div>
					<div class="box-body box-profile">
						<img class="profile-user-img img-responsive img-circle img-preview" src="<?php if (empty($main['PATH_PHOTO'])) {
																										echo base_url('/assets/files/user_pict/kuser.png');
																									} else {
																										echo base_url($main['PATH_PHOTO']);
																									} ?> ">
						<br />
						<p class="text-center text-bold">Profile Picture</p>
						<p class="text-center"><label class="custom-file-label" for="photo"></label></p>
						<p class="text-muted text-center text-red"><small>(leave blank if the photo doesn't change)</small></p>
						<br />
						<div class="input-group input-group-sm">
							<input type="text" class="form-control" id="file_path" name="foto">
							<input type="file" class="hidden" id="file" name="photo" onchange="previewimg()">
							<span class="input-group-btn">
								<button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i> Browse</button>
							</span>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-9">
				<div class="box box-danger">
					<div class="box-body">
						<div class="form-group">
							<label for="tgl_peristiwa">Username : </label>
							<input class="form-control input-sm" type="text" name="username" value="<?= trim($main['USERNAME'])
																									?>" readonly></input>
						</div>
						<div class="form-group">
							<label for="catatan">Group User : </label>
							<input class="form-control input-sm" type="text" name="group" value="<?= trim($main['GROUPNAME'])
																									?>" readonly></input>
						</div>
						<div class="form-group">
							<label for="catatan">Email : </label>
							<input class="form-control input-sm" type="text" name="group" value="<?= trim($main['EMAIL'])
																									?>" readonly></input>
						</div>
						<div class="form-group">
							<label for="catatan">Name : </label>
							<input class="form-control input-sm" type="text" name="name" value="<?= trim($main['NAME'])
																								?>"></input>
						</div>



						<div class="form-group">
							<label for="catatan">Old Password</label>
							<input class="form-control input-sm" type="password" maxlength="20" name="old_pass" autofocus style="text-transform: uppercase;"></input>
						</div>
						<div class="form-group">
							<label for="catatan">New Password</label>
							<input class="form-control input-sm" minlength="6" maxlength="20" type="password" id="new_pass" name="new_pass" autofocus style="text-transform: uppercase;"></input>
						</div>
						<div class="form-group">
							<label for="catatan">Repeat New Password</label>
							<input class="form-control input-sm" minlength="6" maxlength="20" type="password" id="re_new_pass" name="re_new_pass" autofocus style="text-transform: uppercase;"></input>
						</div>

					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<input type="hidden" id="userhash" name="userhash" value="<?= $main['USERHASH'] ?>">
			<input type="hidden" id="old_photo" name="old_photo" value="<?= $main['PATH_PHOTO'] ?>">
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