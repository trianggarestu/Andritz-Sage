<div class="content-wrapper">
	<section class="content-header">

		<h1>Update User</h1>

		<ol class="breadcrumb">
			<li><a href="<?= base_url('administration') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= base_url('usersetup') ?>">User</a></li>

			<li class="active">Update User</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<form id="validasi" action="<?= $form_action ?>" method="POST" class="form-horizontal" enctype="multipart/form-data">
				<div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
							<a href="<?= base_url('usersetup') ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-o-left"></i>Back to Users Settings</a>

						</div>
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="Username">Username</label>
								<div class="col-sm-6">
									<input id="username" name="username" class="form-control input-sm required" placeholder="Username" value="<?= old('username'); ?><?= $username
																																										?>" maxlength="50" autofocus style="text-transform: uppercase;"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="Name">Name</label>
								<div class="col-sm-6">
									<input id="Name" name="name" class="form-control input-sm required" placeholder="Name" value="<?= old('username'); ?><?= $name
																																							?>" maxlength="50" style="text-transform: uppercase;"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="email">E-mail</label>
								<div class="col-sm-6">
									<input id="email" name="email" class="form-control input-sm required" type="text" placeholder="E-mail" value="<?= old('email'); ?><?= $email
																																										?>" maxlength="50"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="Password">Password</label>
								<div class="col-sm-6">
									<input id="password" name="password" class="form-control input-sm required" type="password" placeholder="Password" maxlength="50"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="Password_Conf">Re-type Password</label>
								<div class="col-sm-6">
									<input id="password_conf" name="password_conf" class="form-control input-sm required" type="password" placeholder="Re-type Password" maxlength=" 50"></input>
								</div>
							</div>
							<div class="form-group">
								<label for="catatan" class="col-sm-4 control-label">Upload Photo</label>
								<div class="col-sm-6">
									<div class="box-body box-profile-4">
										<img class="profile-user-img" src="<?= base_url($photo); ?>" alt="Foto">
									</div>
									<div class="input-group input-group-sm-4">
										<div><input type="file" class="col-sm-12 control-label" id="photo" name="photo"></div>



									</div>



									<?php //if ($main['foto']) : 
									?>
									<!--<img class="profile-user-img img-responsive img-circle" src="<? //=AmbilFoto($main['foto'])
																										?>" alt="Foto">-->
									<?php //else : 
									?>

									<?php //endif; 
									?>
								</div>

							</div>
						</div>


						<div class='form-group'>
							<label class="col-sm-4 control-label" for="groupid">Group Role <code> (choose) </code> </label>
							<div class="col-sm-6">
								<select name="groupid" class="form-control input-sm required">
									<option value="">--Choose One--</option>
									<option value="1" <?php if ($groupdesc == "CSR") {
															echo "selected";
														} ?>>CSR</option>
									<option value="2" <?php if ($groupdesc == "REQUESTER") {
															echo "selected";
														} ?>>REQUESTER</option>
									<option value="3" <?php if ($groupdesc == "PROCUREMENT") {
															echo "selected";
														} ?>>PROCUREMENT</option>
									<option value="4" <?php if ($groupdesc == "LOGISTICS") {
															echo "selected";
														} ?>>LOGISTICS</option>
									<option value="5" <?php if ($groupdesc == "INVENTORY") {
															echo "selected";
														} ?>>INVENTORY</option>
									<option value="6" <?php if ($groupdesc == "DELIVERY") {
															echo "selected";
														} ?>>DELIVERY</option>
									<option value="7" <?php if ($groupdesc == "SALES ADMIN") {
															echo "selected";
														} ?>>SALES ADMIN</option>
									<option value="8" <?php if ($groupdesc == "FINANCE") {
															echo "selected";
														} ?>>FINANCE</option>


								</select>
							</div>
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
							<input type="hidden" id="useruniq" name="useruniq" value="<?= $useruniq ?>">
							<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm' onclick="reset_form($(this).val());"><i class='fa fa-times'></i> Cancel</button>
							<button type='submit' class='btn btn-social btn-flat btn-info btn-sm confirm'><i class='fa fa-check'></i> <?= $button ?></button>
						</div>
					</div>
				</div>
		</div>
</div>
</form>
</div>
</section>
</div>

<script>
	function reset_form() {
		<?php //if ($modul['aktif'] == '1' or $modul['aktif'] == NULL) : 
		?>
		$("#sx3").addClass('active');
		$("#sx4").removeClass("active");
		<?php //endif; 
		?>
		<?php //if ($modul['aktif'] == '2') : 
		?>
		$("#sx4").addClass('active');
		$("#sx3").removeClass("active");
		<?php //endif; 
		?>
	};
</script>