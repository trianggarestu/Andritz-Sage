<div class="content-wrapper">
	<section class="content-header">

		<h1>Pengaturan Modul</h1>

		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('modul/clear') ?>"> Menu</a></li>
			<?php //if ($modul['parent']!='0'): 
			?>
			<li><a href="<?= base_url() ?>modul/sub_modul/<? //= ($modul['parent']) 
															?>"> Sub Menu</a></li>
			<?php //endif 
			?>
			<li class="active">Manage Sub Menu</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<form id="validasi" action="<?= $form_action ?>" method="POST" enctype="multipart/form-data" class="form-horizontal">
				<div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
							<a href="<?= base_url('menusetup/sub/' . $submenu['IDNAVL1']) ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-o-left"></i>Back to Sub Menu Settings</a>
							<a href="<?= base_url('menusetup') ?>" class="btn btn-social btn-flat btn-primary btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-o-left"></i> Back to Menu Settings</a>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label class="col-sm-4 control-label" for="Menu Name">Name</label>
								<div class="col-sm-6">
									<input id="navdname" name="navdname" class="form-control input-sm required" type="text" placeholder="Navigation Name" value="<?= ($submenu['NAVDL1NAME'])
																																									?>" maxlength="50"></input>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="Menu Name">Description</label>
								<div class="col-sm-6">
									<textarea id="comment" name="comment" class="form-control input-sm required" placeholder="Description" rows="2"><?= trim($submenu['COMMENT']) ?></textarea>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-4 control-label" for="icon">Icon</label>
								<div class="col-sm-6">
									<select class="form-control select2-ikon" name="icon">
										<option value="">Select Icon</option>
										<?php foreach ($list_icon as $icon) : ?>
											<option value="<?= $icon ?>" <?php if ($icon == trim($submenu['fa_icon'])) { ?> selected="selected" <?php }
																																				?>><?= $icon ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-4 control-label" for="Modul">Modul</label>
								<div class="col-sm-6">
									<input id="modul" name="modul" class="form-control input-sm required" type="text" placeholder="Modul / Function" value="<?= ($submenu['NAVLINK'])
																																							?>" maxlength="50" readonly></input>
								</div>
							</div>


						</div>
						<div class='box-footer'>
							<div class='col-xs-12'>
								<div class='col-sm-4'>
								</div>
								<div class="col-sm-6">
									<input type="hidden" id="idnav" name="idnav" value="<?= $submenu['IDNAVL1'] ?>">
									<input type="hidden" id="idnavdl1" name="idnavdl1" value="<?= $submenu['IDNAVDL1'] ?>">
									<input type="hidden" id="navsorting" name="navsorting" value="<?= $submenu['SORTING']
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