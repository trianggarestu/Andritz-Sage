<div class="content-wrapper">
	<section class="content-header">

		<h1>Pengaturan Modul</h1>

		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('modul/clear') ?>"> Daftar Modul</a></li>
			<?php //if ($modul['parent']!='0'): 
			?>
			<li><a href="<?= site_url() ?>modul/sub_modul/<? //= ($modul['parent']) 
															?>"> Daftar Sub Modul</a></li>
			<?php //endif 
			?>
			<li class="active">Pengaturan Modul</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<form id="validasi" action="<?= $form_action ?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
				<div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
							<a href="<?= base_url('menusetup') ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-o-left"></i>Back to Menu Settings</a>

						</div>
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="Menu Name">Name</label>
								<div class="col-sm-6">
									<input id="navname" name="navname" class="form-control input-sm required" type="text" placeholder="Navigation Name" value="<?= ($menuh['NAVL1NAME'])
																																								?>" maxlength="50"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="Menu Name">Description</label>
								<div class="col-sm-6">
									<textarea id="comment" name="comment" class="form-control input-sm required" placeholder="Description"><?= trim($menuh['COMMENT']) ?></textarea>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-4 control-label" for="icon">Icon</label>
								<div class="col-sm-6">
									<select class="form-control select2-ikon" name="icon">
										<option value="">Select Icon</option>
										<?php foreach ($list_icon as $icon) : ?>
											<option value="<?= $icon ?>" <?php if ($icon == trim($menuh['ICONDESC'])) { ?> selected="selected" <?php }
																																				?>><?= $icon ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>


						</div>
						<div class='box-footer'>
							<div class='col-xs-12'>
								<div class='col-sm-4'>
								</div>
								<div class="col-sm-6">
									<input type="hidden" id="idnav" name="idnav" value="<?= $menuh['IDNAVL1']
																						?>">
									<input type="hidden" id="navsorting" name="navsorting" value="<?= $menuh['NAVL1SORTING']
																									?>">
									<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm' onclick="reset_form($(this).val());"><i class='fa fa-times'></i> Cancel</button>
									<button type='submit' class='btn btn-social btn-flat btn-info btn-sm confirm'><i class='fa fa-check'></i> Save</button>
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