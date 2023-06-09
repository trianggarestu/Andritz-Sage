<div class="content-wrapper">
	<section class="content-header">

		<h1>Add New User</h1>

		<ol class="breadcrumb">
			<li><a href="<?= base_url('administration') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= base_url('usersetup') ?>">User</a></li>

			<li class="active">Add User</li>
		</ol>
	</section>
	<input id="success-code" type="hidden" value="<?= $success_code ?>">
	<!-- Untuk menampilkan modal bootstrap umum  -->
	<div class="modal fade" id="modalBox" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class='modal-dialog'>
			<div class='modal-content'>

				<div class="fetched-data"></div>
			</div>
		</div>
	</div>
	<section class="content" id="maincontent">
		<div class="row">
			<form id="validasi" action="<?= $form_action ?>" method="POST" class="form-horizontal" enctype="multipart/form-data">

				<div class="col-md-9">
					<div class="box box-primary">
						<div class="box-header with-border">
							<a href="<?= base_url('usersetup') ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-o-left"></i>Back to Users Settings</a>

						</div>


						<div class="box-body">
							<div class="form-group">
								<label style="text-align: right;" class="col-sm-4 control-label" for="Username">Username : </label>
								<div class="col-sm-6">
									<input id="username" name="username" class="form-control input-sm required" type="text" placeholder="Username" value="<?= old('username'); ?>" minlength="2" maxlength="20" autofocus style="text-transform: uppercase;"></input>
								</div>
							</div>
							<div class="form-group">
								<label style="text-align: right;" class="col-sm-4 control-label" for="Name">Name : </label>
								<div class="col-sm-6">
									<input id="Name" name="name" class="form-control input-sm required" type="text" placeholder="Name" value="<?= old('name'); ?>" maxlength="50"></input>
								</div>
							</div>
							<div class="form-group">
								<label style="text-align: right;" class="col-sm-4 control-label" for="email">E-mail : </label>
								<div class="col-sm-6">
									<input id="email" name="email" class="form-control input-sm required email" type="text" placeholder="E-mail" value="<?= old('email'); ?>" minlength="6" maxlength="50"></input>
								</div>
							</div>
							<div class="form-group">
								<label style="text-align: right;" class="col-sm-4 control-label" for="Password">Password : </label>
								<div class="col-sm-6">
									<input id="password" name="password" class="form-control input-sm required" type="password" placeholder="Password" minlength="6" maxlength="20" autofocus style="text-transform: uppercase;"></input>
								</div>
							</div>
							<div class="form-group">
								<label style="text-align: right;" class="col-sm-4 control-label" for="Password">Re-type Password : </label>
								<div class="col-sm-6">
									<input id="password_conf" name="password_conf" class="form-control input-sm required" type="password" placeholder="Re-type Password" minlength="6" maxlength="20" autofocus style="text-transform: uppercase;"></input>
								</div>
							</div>

							<!--<div class="form-group">
										<label for="photo" style="text-align: right;" class="col-sm-4 control-label">Upload Photo </label>
										<div class="col-sm-6">
											<div class="col-sm-4">
												<img class="img-thumbnail img-preview" src="/assets/files/user_pict/kuser.png">
											</div>
											<div class="custom-file">
												<input type="file" class="custom-file-input" id="photo" name="photo" onchange="previewimg()">
												<label class="custom-file-label" for="photo"></label>
											</div>
										</div>
									</div>-->
							<div class='form-group'>
								<label style="text-align: right;" class="col-sm-4 control-label" for="groupid">Group Role <code> (choose) </code> : </label>
								<div class="col-sm-6">
									<select name="groupid" class="form-control input-sm required">
										<option value="">--Choose One Group User--</option>
										<?php foreach ($groupuser as $grp) :
										?>
											<option value="<?= trim($grp['GROUPID'])
															?>" <?php if ($groupid == trim($grp['GROUPID'])) {
																	echo "selected";
																} ?>><?= trim($grp['GROUPNAME'])
																		?> <small>(<?= $grp['GROUPDESC']
																					?>)</small>
											</option>
										<?php endforeach;
										?>

									</select>
								</div>
							</div>

							<div class="form-group">
								<label for="isactive" style="text-align: right;" class="col-sm-4 control-label">Activate<code> (choose) </code> : </label>
								<div class="btn-group col-sm-6" data-toggle="buttons">
									<label id="sx3" class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-4 col-lg-2 form-check-label">
										<input id="g1" type="radio" name="isactive" class="form-check-input" type="radio" value="0" autocomplete="off"> Active
									</label>
									<label id="sx4" class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-4 col-lg-2 form-check-label">
										<input id="g2" type="radio" name="isactive" class="form-check-input" type="radio" value="1" autocomplete="off"> Inactive
									</label>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-4 control-label" for="Menu Name"></label>
								<div class="col-sm-6">
									<?= validation_list_errors() ?>
								</div>
							</div>
						</div>
						<div class='box-footer'>
							<div class='col-xs-12'>
								<div class='col-sm-4'>
								</div>
								<div class="col-sm-6">
									<!--<input type="hidden" id="useruniq" name="useruniq" value="">-->
									<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm' onclick="reset_form($(this).val());"><i class='fa fa-times'></i> Cancel</button>
									<button type='submit' class='btn btn-social btn-flat btn-info btn-sm confirm'><i class='fa fa-check'></i> <?= $button ?></button>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-3">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Upload Photo</h3>
						</div>
						<div class="box-body box-profile">
							<img class="profile-user-img img-responsive img-circle img-preview" src="<?php if (empty($photo)) {
																											echo base_url('/assets/files/user_pict/kuser.png');
																										} else {
																											echo base_url($photo);
																										} ?> ">
							<br />
							<p class="text-center text-bold">Profile Picture</p>
							<p class="text-center"><label class="custom-file-label" for="photo"></label></p>
							<p class="text-muted text-center text-red">(leave blank if the photo doesn't change)</p>
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

		</div>
		</form>

	</section>
</div>


<script>
	$('document').ready(function() {
		setTimeout(function() {
			$('#password_conf').rules('add', {
				equalTo: '#password'
			})
		}, 500);


	});
</script>