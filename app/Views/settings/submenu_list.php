	<script type="text/javascript">
		$(function() {
			var keyword = <? //= $keyword 
							?>;
			$("#cari").autocomplete({
				source: keyword,
				maxShowItems: 10,
			});
		});
	</script>
	<div class="content-wrapper">
		<section class="content-header">
			<h1>Manage Sub Menu</h1>
			<ol class="breadcrumb">
				<li><a href="<?= base_url() ?>administration"><i class="fa fa-home"></i> Home</a></li>


				<li class="active">Manage Sub Menu</li>

			</ol>
		</section>


		<section class="content" id="maincontent">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">

						<div class="box-header with-border">
							<a href="<?= base_url('menusetup') ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-o-left"></i>Back to Menu Settings</a>

						</div>
						<div class="box-header with-border">
							<h3 class="box-title"><strong>Sub Menu of <?= $menuh ?></strong> (<small><?= $menuh_comment ?></small>)</h3>
						</div>
						<div class="box-body">

							<div class="row">
								<div class="col-sm-12">
									<div class="table-responsive">
										<table class="table table-bordered dataTable table-hover">
											<thead class="bg-gray disabled color-palette">
												<tr>
													<th width="1%">No</th>
													<th width="5%">Action</th>
													<th>Sub Menu Name</th>
													<th>Sub Menu Description</th>
													<th>Modul</th>
													<th width="5%" nowrap>Icon</th>
													<th width="5%" nowrap>View Icon</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$no = 0;
												foreach ($submenu_data as $data) : ?>
													<tr>
														<td class="text-center"><?= ++$no; ?></td>
														<td nowrap>
															<a href="<?= base_url() . 'menusetup/sort/' . $data['IDNAVL1'] . '/' . $data['IDNAVDL1'] . '/1'; ?>" class="btn bg-olive btn-flat btn-sm" title="Pindah Posisi Ke Bawah"><i class="fa fa-arrow-down"></i></a>
															<a href="<?= base_url() . 'menusetup/sort/' . $data['IDNAVL1'] . '/' . $data['IDNAVDL1'] . '/2'; ?>" class="btn bg-olive btn-flat btn-sm" title="Pindah Posisi Ke Atas"><i class="fa fa-arrow-up"></i></a>
															<a href="<?= base_url() . 'menusetup/subform/' . $data['IDNAVDL1']; ?>" class=" btn bg-orange btn-flat btn-sm" title="Update Data"><i class="fa fa-edit"></i></a>
															<?php //if ($data['aktif'] == '1') : 
															?>

														</td>
														<td><?= $data['NAVDL1NAME'] ?></td>
														<td><?= $data['COMMENT'] ?></td>
														<td><?= $data['NAVLINK'] ?></td>
														<td class="text-center" nowrap><?= $data['fa_icon'] ?></td>
														<td class="text-center"><i class="fa <?= $data['fa_icon'] ?> fa-lg"></i></td>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>

					</div>
		</section>
	</div>