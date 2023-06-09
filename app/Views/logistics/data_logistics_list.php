<style>
	.input-sm {
		padding: 4px 4px;
	}
</style>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Arrange Shipment PO List</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Arrange Shipment PO List</li>
		</ol>
	</section>

	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?= base_url() ?>arrangeshipmentlist/" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i> Refresh</a>
						<a href="<?= base_url("arrangeshipmentlist/preview") ?>" target="_blank" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Preview"><i class="fa fa-print"></i> Preview
						</a>
						<a href="<?= base_url("arrangeshipmentlist/export_excel") ?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh" target="_blank"><i class="fa fa-download"></i> Download
						</a>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<form id="mainform" name="mainform" action="" method="post">
										<div class="row">
											<div class="col-sm-9">
												<label for="daterange">Filter by P/O Date : </label>
												<div class="input-group input-group-sm date">
													<div class="input-group-addon">From Date :
														<i class="fa fa-calendar"></i>
													</div>
													<input class="datepicker form-control input-sm required" id="from_date" name="from_date" type="text" value="<?= $def_fr_date ?>" readonly>
												</div>
												<div class="input-group input-group-sm date">
													<div class="input-group-addon">To Date :
														<i class="fa fa-calendar"></i>
													</div>
													<input class="datepicker form-control input-sm required" id="to_date" name="to_date" type="text" value="<?= $def_to_date ?>" readonly>

													<div class="input-group-btn">
														<button type="submit" class="btn btn-default bg-maroon" onclick="$('#'+'mainform').attr('action', '<?= base_url('arrangeshipmentlist/search') ?>');$('#'+'mainform').submit();"><i class="fa fa-filter"></i>Go!</button>

													</div>
												</div>
											</div>

											<div class="col-sm-3">
												<div class="box-tools">
													<div class="input-group input-group-sm pull-right">
														<input name="cari" id="cari" class="form-control" placeholder="Search..." type="text" value="<?= $keyword ?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', 'http://localhost:8082/OpenSID/index.php/surat_masuk/search');$('#'+'mainform').submit();}">
														<div class="input-group-btn">
															<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= base_url('arrangeshipmentlist/search') ?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
														</div>
													</div>
												</div>
											</div>
										</div>
									</form>
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
															<th style="background-color: white;"></th>
															<th>ETD Origin<br>(Date)</th>
															<th>ATD Origin<br>(Date)</th>
															<th>ETA Port<br>(Date)</th>
															<th>PIB (Date)</th>
															<th>Shipment Status</th>
															<th>Status</th>
														</tr>
													</thead>
													<tbody>
														<?php
														$no = 0;
														?>
														<?php foreach ($log_data as $data_list) {
															$povendate = substr($data_list['PODATE'], 4, 2) . "/" . substr($data_list['PODATE'], 6, 2) . "/" .  substr($data_list['PODATE'], 0, 4);
															$etddate = substr($data_list['ETDDATE'], 4, 2) . "/" . substr($data_list['ETDDATE'], 6, 2) . "/" .  substr($data_list['ETDDATE'], 0, 4);
															$creadinessdate = substr($data_list['CARGOREADINESSDATE'], 4, 2) . "/" . substr($data_list['CARGOREADINESSDATE'], 6, 2) . "/" .  substr($data_list['CARGOREADINESSDATE'], 0, 4);
															$etdorigindate = substr($data_list['ETDORIGINDATE'], 4, 2) . "/" . substr($data_list['ETDORIGINDATE'], 6, 2) . "/" .  substr($data_list['ETDORIGINDATE'], 0, 4);
															$atdorigindate = substr($data_list['ATDORIGINDATE'], 4, 2) . "/" . substr($data_list['ATDORIGINDATE'], 6, 2) . "/" .  substr($data_list['ATDORIGINDATE'], 0, 4);
															$etaportdate = substr($data_list['ETAPORTDATE'], 4, 2) . "/" . substr($data_list['ETAPORTDATE'], 6, 2) . "/" .  substr($data_list['ETAPORTDATE'], 0, 4);
															$pibdate = substr($data_list['PIBDATE'], 4, 2) . "/" . substr($data_list['PIBDATE'], 6, 2) . "/" .  substr($data_list['PIBDATE'], 0, 4);

														?>

															<tr>
																<td><?= ++$no ?></td>
																<td><strong><a href="<?= base_url('administration/popostedview/' . $data_list['POUNIQ']) ?>" target="_blank"><?= $data_list['PONUMBER'] ?></a></strong></td>
																<td><?= $povendate ?></td>
																<td><?= $etddate ?></td>
																<td><?= $creadinessdate ?></td>
																<td><?= $data_list['ORIGINCOUNTRY'] ?></td>
																<td><?= $data_list['POREMARKS'] ?></td>
																<td><?php $postingstat = $data_list['POSTINGSTAT'];
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
																<td style="background-color: white;"></td>
																<td><?= $etdorigindate ?></td>
																<td><?= $atdorigindate ?></td>
																<td><?= $etaportdate ?></td>
																<td><?= $pibdate ?></td>
																<td><?= $data_list['VENDSHISTATUS'] ?></td>
																<td><?php $logpostingstat = $data_list['POSTINGSTAT'];
																	switch ($logpostingstat) {
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
																	} ?>
																</td>
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
												<?= $pager->links('log_posting_list', 'bootstrap_pagination');
												//$pager = \Config\Services::pager();
												?>
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