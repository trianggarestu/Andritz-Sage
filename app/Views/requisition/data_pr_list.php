<style>
	.input-sm {
		padding: 4px 4px;
	}
</style>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Purchase Requisition List</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Purchase Requisition List</li>
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
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?= base_url() ?>requisitionlist/" title="Refresh Data" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i> Refresh Data</a>
						<a href="<?= base_url("requisitionlist/preview") ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak" target="_blank"><i class="fa fa-print"></i> Preview
						</a>
						<a href="<?= base_url("requisitionlist/export_excel") ?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh" target="_blank"><i class="fa fa-download"></i> Download
						</a>
						<!-- <a href="#" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Preview"><i class="fa fa-print"></i> Preview
						</a>
						<a href="" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh" target="_blank"><i class="fa fa-download"></i> Download
						</a> -->
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<form id="mainform" name="mainform" action="" method="post">
										<div class="row">
											<div class="col-sm-9">
												<label for="daterange">Filter by P/R Date : </label>
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
														<button type="submit" class="btn btn-default bg-maroon" onclick="$('#'+'mainform').attr('action', '<?= base_url('requisitionlist/search') ?>');$('#'+'mainform').submit();"><i class="fa fa-filter"></i>Go!</button>

													</div>
												</div>
											</div>

											<div class="col-sm-3">
												<div class="box-tools">
													<div class="input-group input-group-sm pull-right">
														<input name="cari" id="cari" class="form-control" placeholder="Search..." type="text" value="" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', 'http://localhost:8082/OpenSID/index.php/surat_masuk/search');$('#'+'mainform').submit();}">
														<div class="input-group-btn">
															<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= base_url('requisitionlist/search') ?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
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
															<th>PR Number</th>
															<th>PR Date</th>
															<th>Status</th>
															<th style="background-color: white;"></th>
															<th>PO Vendor</th>
															<th>PO Date</th>
															<th style="background-color: white;"></th>
															<th>Customer Name</th>
															<th>Contract No.</th>
															<th>Contract Desc.</th>
															<th>Project No.</th>
															<th>CRM Number</th>
															<th>CRM Req. Date</th>
															<th>Order Description</th>
															<th>Sales Person</th>
															<th>Inventory No</th>
															<th>Qty</th>
															<th>UoM</th>



														</tr>
													</thead>
													<tbody>
														<?php
														$no = 0 + (5 * ($currentpage - 1));

														?>
														<?php foreach ($pr_data as $ot_list) {
															$crmreq_date = substr($ot_list['CRMREQDATE'], 4, 2) . "/" . substr($ot_list['CRMREQDATE'], 6, 2) . "/" . substr($ot_list['CRMREQDATE'], 0, 4);
															$pocust_date = substr($ot_list['PODATECUST'], 4, 2) . "/" . substr($ot_list['PODATECUST'], 6, 2) . "/" . substr($ot_list['PODATECUST'], 0, 4);
															$rqn_date = substr($ot_list['RQNDATE'], 4, 2) . "/" . substr($ot_list['RQNDATE'], 6, 2) . "/" . substr($ot_list['RQNDATE'], 0, 4);
															if (!empty($ot_list['PODATE'])) {
																$po_date = substr($ot_list['PODATE'], 4, 2) . "/" . substr($ot_list['PODATE'], 6, 2) . "/" . substr($ot_list['PODATE'], 0, 4);
															} else {
																$po_date = "";
															}
														?>

															<tr>
																<td><?= ++$no; ?></td>
																<td><?= $ot_list['RQNNUMBER']; ?></td>
																<td><?= $rqn_date; ?></td>
																<td><?php $postingstat = $ot_list['POSTINGSTAT'];
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
																			echo "Open";
																	} ?></td>
																<td style="background-color: white;"></td>
																<td><?= $ot_list['PONUMBERCUST']; ?></td>
																<td><?= $pocust_date; ?></td>
																<td style="background-color: white;"></td>
																<td><?= $ot_list['NAMECUST']; ?></td>
																<td><?= $ot_list['CONTRACT']; ?></td>
																<td><?= $ot_list['CTDESC']; ?></td>
																<td><?= $ot_list['PROJECT']; ?></td>
																<td><?= $ot_list['CRMNO']; ?></td>
																<td><?= $crmreq_date; ?></td>
																<td><?= $ot_list['ORDERDESC']; ?></td>
																<td><?= $ot_list['SALESNAME']; ?></td>
																<td><?= $ot_list['MATERIALNO']; ?></td>
																<td><?= number_format($ot_list['QTY'], 0, ",", "."); ?></td>
																<td><?= $ot_list['STOCKUNIT']; ?></td>




															</tr>

														<?php } ?>
													</tbody>
												</table>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-sm-6">

										</div>
										<div class="col-sm-6">
											<div class="dataTables_paginate paging_simple_numbers">
												<div><?= $pager->links('req_data', 'bootstrap_pagination'); ?>
												</div>
											</div>
										</div>

										<!-- Pagination template
											<div class="col-sm-6">
											
										</div>
										<div class="col-sm-6">
											<div class="dataTables_paginate paging_simple_numbers">
												<ul class="pagination">
													<?php //if ($paging->start_link) : 
													?>
													<li>
														<a href="<? //= site_url('covid19/data_pemudik/' . $paging->start_link) 
																	?>" aria-label="First"><span aria-hidden="true">Awal</span></a>
													</li>
													<?php //endif; 
													?>
													<?php //if ($paging->prev) : 
													?>
													<li>
														<a href="<? //= site_url('covid19/data_pemudik/' . $paging->prev) 
																	?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
													</li>
													<?php //endif; 
													?>
													<?php //for ($i = $paging->start_link; $i <= $paging->end_link; $i++) : 
													?>

													<li class='active'>
														<a href="<? //= site_url('covid19/data_pemudik/' . $i) 
																	?>"><? //= $i 
																		?>
															1</a>
													</li>
													<?php //endfor; 
													?>
													<?php //if ($paging->next) : 
													?>
													<li>
														<a href="<? //= site_url('covid19/data_pemudik/' . $paging->next) 
																	?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
													</li>
													<?php //endif; 
													?>
													<?php //if ($paging->end_link) : 
													?>
													<li>
														<a href="<? //= site_url('covid19/data_pemudik/' . $paging->end_link) 
																	?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a>
													</li>
													<?php //endif; 
													?>
												</ul>
											</div>
										</div>
													-->
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