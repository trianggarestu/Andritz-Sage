<style>
	.input-sm {
		padding: 4px 4px;
	}
</style>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Order Tracking List</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Order Tracking List</li>
		</ol>
	</section>

	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?= base_url() ?>ordertrackinglist/" title="Refresh Data" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i> Refresh Data</a>
						<a href="<?= base_url("ordertrackinglist/preview") ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak" target="_blank"><i class="fa fa-print"></i> Preview
						</a>
						<a href="<?= base_url() ?>ordertrackinglist/export_excel" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh" target="_blank"><i class="fa fa-download"></i> Download to Excel
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
														<button type="submit" class="btn btn-default bg-maroon" onclick="$('#'+'mainform').attr('action', '<?= base_url('ordertrackinglist/search') ?>');$('#'+'mainform').submit();"><i class="fa fa-filter"></i>Go!</button>

													</div>
												</div>
											</div>
											<div class="col-sm-3">
												<div class="box-tools">
													<div class="input-group input-group-sm pull-right">
														<input name="cari" id="cari" class="form-control" placeholder="Search..." type="text" value="<?= $keyword ?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= base_url('ordertrackinglistlist/search') ?>');$('#'+'mainform').submit();}">
														<div class="input-group-btn">
															<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= base_url('ordertrackinglist/search') ?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
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
													<thead class="bg-gray color-palette">
														<tr>
															<th>No</th>
															<th class="bg-orange">Contract</th>
															<th class="bg-orange" nowrap>Customer Name</th>
															<th class="bg-orange">Customer Email</th>
															<th class="bg-orange">Project No.</th>
															<th class="bg-orange">CRM Number</th>
															<th class="bg-orange">PO Customer</th>
															<th class="bg-orange" nowrap>PO Date</th>
															<th class="bg-orange" nowrap>Req. Date</th>
															<th class="bg-orange">Sales Person</th>
															<th class="bg-orange">Inventory No</th>
															<th class="bg-orange">Material No</th>
															<th class="bg-orange">Order Description</th>
															<th class="bg-orange">Qty</th>
															<th class="bg-orange">Uom</th>
															<th class="bg-orange">CRM Remarks</th>
															<th style="background-color: white;"></th>
															<th class="bg-yellow">PR Number</th>
															<th class="bg-yellow">PR Date</th>
															<th style="background-color: white;"></th>
															<th class="bg-green">PO Vendor</th>
															<th class="bg-green">PO Date</th>
															<th class="bg-green">ETD</th>
															<th class="bg-green">Cargo Readiness</th>
															<th class="bg-green">Origin Country</th>
															<th class="bg-green">Remarks</th>
															<th style="background-color: white;"></th>
															<th class="bg-blue">ETD Origin</th>
															<th class="bg-blue">ATD Origin</th>
															<th class="bg-blue">ETA Port</th>
															<th class="bg-blue">PIB</th>
															<th class="bg-blue">Shipment Status</th>
															<th style="background-color: white;"></th>
															<th class="bg-success">GR Date</th>
															<th class="bg-success">Qty</th>
															<th class="bg-success">Status</th>
															<th style="background-color: white;"></th>
															<th class="bg-olive">Delivery Date</th>
															<th class="bg-olive">DN Number</th>
															<th class="bg-olive">Received Date</th>
															<th class="bg-olive">Qty Delivered</th>
															<th class="bg-olive">Qty Outstanding</th>
															<th class="bg-olive">PO Status</th>
															<th class="bg-olive">DN Status</th>
															<th style="background-color: white;"></th>
															<th class="bg-info">Invoice Date</th>
															<th class="bg-info">Status</th>
															<th style="background-color: white;"></th>
															<th class="bg-danger">RR Status</th>
															<th style="background-color: white;"></th>
															<th class="bg-secondary">PO Cust to PR</th>
															<th class="bg-secondary">PO to PO</th>
															<th class="bg-secondary">ON TIME DEL</th>
															<th class="bg-secondary">PO to DN</th>

														</tr>
													</thead>
													<tbody>
														<?php
														$no = 0;

														?>
														<?php foreach ($ord_data as $ot_list) {
															$pocust_date = substr($ot_list['PODATECUST'], 4, 2) . "/" . substr($ot_list['PODATECUST'], 6, 2) . "/" . substr($ot_list['PODATECUST'], 0, 4);
															$crmreq_date = substr($ot_list['CRMREQDATE'], 4, 2) . "/" . substr($ot_list['CRMREQDATE'], 6, 2) . "/" . substr($ot_list['CRMREQDATE'], 0, 4);
															if ($ot_list['RQNDATE'] == '') {
																$rqn_date = '';
															} else {
																$rqn_date = substr($ot_list['RQNDATE'], 4, 2) . "/" . substr($ot_list['RQNDATE'], 6, 2) . "/" . substr($ot_list['RQNDATE'], 0, 4);
															}

															if ($ot_list['PODATE'] == '') {
																$po_date = '';
																$etd_date = '';
															} else {
																$po_date = substr($ot_list['PODATE'], 4, 2) . "/" . substr($ot_list['PODATE'], 6, 2) . "/" . substr($ot_list['PODATE'], 0, 4);
																$etd_date = substr($ot_list['ETDDATE'], 4, 2) . "/" . substr($ot_list['ETDDATE'], 6, 2) . "/" . substr($ot_list['ETDDATE'], 0, 4);
															}

															if ($ot_list['CARGOREADINESSDATE'] == '') {
																$cargoreadiness_date = '';
															} else {
																$cargoreadiness_date = substr($ot_list['CARGOREADINESSDATE'], 4, 2) . "/" . substr($ot_list['CARGOREADINESSDATE'], 6, 2) . "/" . substr($ot_list['CARGOREADINESSDATE'], 0, 4);
															}
															if ($ot_list['ETDORIGINDATE'] == '') {
																$etdori_date = '';
															} else {
																$etd_date = substr($ot_list['ETDORIGINDATE'], 4, 2) . "/" . substr($ot_list['ETDORIGINDATE'], 6, 2) . "/" . substr($ot_list['ETDORIGINDATE'], 0, 4);
															}

															$podate = substr($ot_list['PODATE'], 4, 2) . "/" . substr($ot_list['PODATE'], 6, 2) . "/" . substr($ot_list['PODATE'], 0, 4);
															$rqndate = substr($ot_list['RQNDATE'], 4, 2) . "/" . substr($ot_list['RQNDATE'], 6, 2) . "/" . substr($ot_list['RQNDATE'], 0, 4);
															$etd = substr($ot_list['ETDDATE'], 4, 2) . "/" . substr($ot_list['ETDDATE'], 6, 2) . "/" . substr($ot_list['ETDDATE'], 0, 4);
															$cargo = substr($ot_list['CARGOREADINESSDATE'], 4, 2) . "/" . substr($ot_list['CARGOREADINESSDATE'], 6, 2) . "/" . substr($ot_list['CARGOREADINESSDATE'], 0, 4);
															$etdorigindate = substr($ot_list['ETDORIGINDATE'], 4, 2) . "/" . substr($ot_list['ETDORIGINDATE'], 6, 2) . "/" .  substr($ot_list['ETDORIGINDATE'], 0, 4);
															$atdorigindate = substr($ot_list['ATDORIGINDATE'], 4, 2) . "/" . substr($ot_list['ATDORIGINDATE'], 6, 2) . "/" .  substr($ot_list['ATDORIGINDATE'], 0, 4);
															$etaportdate = substr($ot_list['ETAPORTDATE'], 4, 2) . "/" . substr($ot_list['ETAPORTDATE'], 6, 2) . "/" .  substr($ot_list['ETAPORTDATE'], 0, 4);
															$pibdate = substr($ot_list['PIBDATE'], 4, 2) . "/" . substr($ot_list['PIBDATE'], 6, 2) . "/" .  substr($ot_list['PIBDATE'], 0, 4);
															$shidate = substr($ot_list['SHIDATE'], 4, 2) . "/" . substr($ot_list['SHIDATE'], 6, 2) . "/" .  substr($ot_list['SHIDATE'], 0, 4);
															$grdate = substr($ot_list['RECPDATE'], 4, 2) . "/" . substr($ot_list['RECPDATE'], 6, 2) . "/" .  substr($ot_list['RECPDATE'], 0, 4);
															$cusdate = substr($ot_list['CUSTRCPDATE'], 4, 2) . "/" . substr($ot_list['CUSTRCPDATE'], 6, 2) . "/" .  substr($ot_list['CUSTRCPDATE'], 0, 4);
															$invdate = substr($ot_list['INVOICEDATE'], 4, 2) . "/" . substr($ot_list['INVOICEDATE'], 6, 2) . "/" .  substr($ot_list['INVOICEDATE'], 0, 4);

														?>
															<tr>
																<td><?= ++$no; ?></td>
																<td><?= $ot_list['CONTRACT']; ?></td>
																<td><?= $ot_list['NAMECUST']; ?></td>
																<td><?= $ot_list['EMAIL1CUST']; ?></td>
																<td><?= $ot_list['PROJECT']; ?></td>
																<td><?= $ot_list['CRMNO']; ?></td>
																<td><?= $ot_list['PONUMBERCUST']; ?></td>
																<td><?= $pocust_date; ?></td>
																<td><?= $crmreq_date; ?></td>
																<td><?= $ot_list['SALESNAME']; ?></td>
																<td><?= $ot_list['ITEMNO']; ?></td>
																<td><?= $ot_list['MATERIALNO']; ?></td>
																<td><?= $ot_list['ORDERDESC']; ?></td>
																<td><?= number_format($ot_list['QTY'], 0, ",", "."); ?></td>
																<td><?= $ot_list['STOCKUNIT']; ?></td>
																<td><?= $ot_list['CRMREMARKS']; ?></td>
																<td style="background-color: white;"></td>
																<td><?= $ot_list['RQNNUMBER']; ?></td>
																<td><?= $rqndate; ?></td>
																<td style="background-color: white;"></td>
																<td><?= $ot_list['PONUMBER']; ?></td>
																<td><?= $podate; ?></td>
																<td><?= $etd; ?></td>
																<td><?= $cargo; ?></td>
																<td><?= $ot_list['ORIGINCOUNTRY']; ?></td>
																<td><?= $ot_list['POREMARKS']; ?></td>
																<td style="background-color: white;"></td>
																<td><?= $etdorigindate; ?></td>
																<td><?= $atdorigindate; ?></td>
																<td><?= $etaportdate; ?></td>
																<td><?= $pibdate; ?></td>
																<td><?= $ot_list['VENDSHISTATUS']; ?></td>
																<td style="background-color: white;"></td>
																<td><?= $grdate ?></td>
																<td><?= $ot_list['RECPQTY']; ?></td>
																<td><?php $dnpostingstat = $ot_list['GRSTATUS'];
																	switch ($dnpostingstat) {
																		case "0":
																			echo "Partial";
																			break;
																		case "1":
																			echo "Completed";
																			break;
																		default:
																			echo "";
																	} ?>
																</td>
																<td style="background-color: white;"></td>
																<td><?= $shidate ?></td>
																<td><?= $ot_list['SHINUMBER']; ?></td>
																<td><?= $cusdate; ?></td>
																<td><?= $ot_list['SHIQTY']; ?></td>
																<td><?= $ot_list['SHIQTYOUTSTANDING']; ?></td>
																<td><?php $postatus = $ot_list['POCUSTSTATUS'];
																	switch ($postatus) {
																		case "0":
																			echo "Partial";
																			break;
																		case "1":
																			echo "Completed";
																			break;
																		default:
																			echo "";
																	} ?>
																<td><?php $dnpostingstat = $ot_list['DNSTATUS'];
																	switch ($dnpostingstat) {
																		case "0":
																			echo "";
																			break;
																		case "1":
																			echo "RECEIVED";
																			break;

																		default:
																			echo "";
																	} ?>
																</td>
																<td style="background-color: white;"></td>
																<td><?= $invdate ?></td>
																<td><?php $invstat = $ot_list['FINSTATUS'];
																	switch ($invstat) {
																		case "0":
																			echo "Open";
																			break;
																		case "1":
																			echo "Posted";
																			break;
																		case "2":
																			echo "Done";
																			break;
																		default:
																			echo "";
																	} ?>
																</td>
																<td style="background-color: white;"></td>
																<td><?php $rrstat = $ot_list['RRSTATUS'];
																	switch ($rrstat) {
																		case "0":
																			echo "Open";
																			break;
																		case "1":
																			echo "Posted";
																			break;
																		case "2":
																			echo "Done";
																			break;
																		default:
																			echo "";
																	} ?>
																</td>
																<td style="background-color: white;"></td>
																<td><?= $ot_list['POCUSTTOPRDAYS']; ?></td>
																<td><?= $ot_list['POTOPODAYS']; ?></td>
																<td><?= $ot_list['ONTIMEDELDAYS']; ?></td>
																<td><?= $ot_list['POTODNDAYS']; ?></td>
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
												<ul class="pagination">
													<?php //if ($paging->start_link) : 
													?>
													<li>
														<a href="<? //= site_url('covid19/data_pemudik/' . $paging->start_link) 
																	?>" aria-label="First"><span aria-hidden="true">First</span></a>
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
																	?>" aria-label="Last"><span aria-hidden="true">Last</span></a>
													</li>
													<?php //endif; 
													?>
												</ul>
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