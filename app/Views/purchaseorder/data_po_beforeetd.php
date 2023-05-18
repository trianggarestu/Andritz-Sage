<style>
	.input-sm {
		padding: 4px 4px;
	}
</style>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Listing PO on 2 Weeks Before ETD</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Listing PO on 2 Weeks Before ETD<< /li>
		</ol>
	</section>

	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?= base_url() ?>pobeforeetdnotice/" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i> Refresh</a>
						<a href="<?= base_url("pobeforeetdnotice/preview") ?>" target="_blank" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Preview"><i class="fa fa-print"></i> Preview
						</a>
						<a href="<?= base_url("pobeforeetdnotice/export_excel") ?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh" target="_blank"><i class="fa fa-download"></i> Download
						</a>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<form id="mainform" name="mainform" action="" method="post">
										<div class="row">
											<div class="col-sm-8">
												Record Results : <?= $ct_po_beforeetd ?>
											</div>
											<div class="col-sm-4">
												<div class="box-tools">
													<div class="input-group input-group-sm pull-right">
														<input name="cari" id="cari" class="form-control" placeholder="Search..." type="text" value="" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', 'http://localhost:8082/OpenSID/index.php/surat_masuk/search');$('#'+'mainform').submit();}">
														<div class="input-group-btn">
															<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', 'http://localhost:8082/OpenSID/index.php/surat_masuk/search');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<div class="table-responsive">
													<table class="table table-bordered table-striped dataTable table-hover nowrap">
														<thead class="bg-gray disabled color-palette">
															<tr>
																<th>No.</th>
																<th>PO. Number</th>
																<th>PO. Date</th>
																<th>ETD (Date)</th>
																<th>Cargo<br>Readiness (Date)</th>
																<th>Origin Country</th>
																<th>Remarks</th>
																<th>Status</th>
																<th>Diff</th>
																<th style="background-color: white;"></th>
																<th>PR. Number</th>
																<th>PR. Date</th>


															</tr>
														</thead>
														<tbody>
															<?php
															$no = 0;
															?>
															<?php foreach ($pobeforeetdnotice_data as $po_list) {
																$povendate = substr($po_list['PODATE'], 4, 2) . "/" . substr($po_list['PODATE'], 6, 2) . "/" .  substr($po_list['PODATE'], 0, 4);
																$etddate = substr($po_list['ETDDATE'], 4, 2) . "/" . substr($po_list['ETDDATE'], 6, 2) . "/" .  substr($po_list['ETDDATE'], 0, 4);
																$creadinessdate = substr($po_list['CARGOREADINESSDATE'], 4, 2) . "/" . substr($po_list['CARGOREADINESSDATE'], 6, 2) . "/" .  substr($po_list['CARGOREADINESSDATE'], 0, 4);
																$rqndate = substr($po_list['RQNDATE'], 4, 2) . "/" . substr($po_list['RQNDATE'], 6, 2) . "/" .  substr($po_list['RQNDATE'], 0, 4);

															?>

																<tr>
																	<td><?= ++$no ?></td>
																	<td><strong><a href="#"><?= $po_list['PONUMBER'] ?></a></strong></td>
																	<td><?= $povendate ?></td>
																	<td><?= $etddate ?></td>
																	<td><?= $creadinessdate ?></td>
																	<td><?= $po_list['ORIGINCOUNTRY'] ?></td>
																	<td><?= $po_list['POREMARKS'] ?></td>
																	<td><?php $postingstat = $po_list['POSTINGSTAT'];
																		switch ($postingstat) {
																			case "0":
																				echo "Open";
																				break;
																			case "1":
																				echo "Posted";
																				break;
																			case "2":
																				echo "Deleted";
																				break;
																			default:
																				echo "Posted";
																		} ?></td>
																	<td><?= $po_list['diff'] ?></td>
																	<td style="background-color: white;"></td>
																	<td><strong><a href="#"><?= $po_list['RQNNUMBER'] ?></a></strong></td>
																	<td><?= $rqndate ?></td>

																</tr>

															<?php } ?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</form>
									<div class="row">
										<!-- Pagination template-->
										<div class="col-sm-6">

										</div>
										<div class="col-sm-6">
											<div class="dataTables_paginate paging_simple_numbers">

											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<?php //$this->load->view('global/confirm_delete'); 
?>

<div class="modal fade" id="modalBox" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class='modal-dialog'>
		<div class='modal-content'>
			<div class='modal-header'>
				<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
				<h4 class='modal-title' id='myModalLabel'></h4>
			</div>
			<div class="fetched-data"></div>
		</div>
	</div>
</div>