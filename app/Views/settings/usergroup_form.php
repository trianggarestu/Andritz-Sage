<div class="content-wrapper">
	<section class="content-header">

		<h1>Add Group</h1>

		<ol class="breadcrumb">
			<li><a href="<?= base_url('administration') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= base_url('usergroupsetup') ?>">Groups</a></li>

			<li class="active">Add Group</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<form id="validasi" action="<?= $form_action ?>" method="POST" class="form-horizontal">
				<div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
							<a href="<?= base_url('usergroupsetup') ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-o-left"></i>Back to Group Settings</a>

						</div>
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="Group Name">Group Name</label>
								<div class="col-sm-6">
									<input id="groupname" name="groupname" class="form-control input-sm required" type="text" placeholder="Group Name" value="<?= $groupname
																																								?>" maxlength="50"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="Menu Name">Group Description</label>
								<div class="col-sm-6">
									<textarea id="groupdesc" name="groupdesc" class="form-control input-sm required" placeholder="Group Description"><?= trim($groupdesc) ?></textarea>
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
									<input type="hidden" id="groupid" name="groupid" value="<?= $groupid ?>">
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