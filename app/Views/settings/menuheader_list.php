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
			<h1>Manage Menu</h1>
			<ol class="breadcrumb">
				<li><a href="<?= base_url() ?>administration"><i class="fa fa-home"></i> Home</a></li>


				<li class="active">Manage Menu</li>

			</ol>
		</section>


		<section class="content" id="maincontent">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">



						<div class="box-body">

							<div class="row">
								<div class="col-sm-12">
									<div class="table-responsive">
										<table class="table table-bordered dataTable table-hover">
											<thead class="bg-gray disabled color-palette">
												<tr>
													<th width="1%">No</th>
													<th width="5%">Action</th>
													<th>Menu Header Name</th>
													<th>Menu Header Description</th>
													<th width="5%" nowrap>Icon</th>
													<th width="5%" nowrap>View Icon</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$no = 0;
												foreach ($menuheader_data as $data) : ?>
													<tr>
														<td class="text-center"><?= ++$no; ?></td>
														<td nowrap>
															<a href="<?= base_url() . 'menusetup/form/' . $data['IDNAVL1']; ?>" class=" btn bg-orange btn-flat btn-sm" title="Update Data"><i class="fa fa-edit"></i></a>
															<?php //if ($data['aktif'] == '1') : 
															?>
															<a href="<?= base_url() . 'menusetup/sub/' . $data['IDNAVL1']; ?>" class="btn bg-olive btn-flat btn-sm" title="Lihat Sub Modul"><i class="fa fa-list"></i></a>
															<?php //endif; 
															?>
														</td>
														<td><?= $data['NAVL1NAME'] ?></td>
														<td><?= $data['COMMENT'] ?></td>
														<td class="text-center" nowrap><?= $data['ICONDESC'] ?></td>
														<td class="text-center"><i class="fa <?= $data['ICONDESC'] ?> fa-lg"></i></td>
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