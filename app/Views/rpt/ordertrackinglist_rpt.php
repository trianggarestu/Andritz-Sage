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
						<a href="<?= base_url() ?>ordertracking/" title="Refresh Data" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i> Refresh Data</a>
						<a href="#" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Preview" target="_blank"><i class="fa fa-print"></i>Preview
						</a>
						<a href="<?= base_url() ?>ordertracking/export_excel" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh" target="_blank"><i class="fa fa-download"></i> Download to Excel
						</a>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<form id="mainform" name="mainform" action="" method="post">
										<div class="row">
											<div class="col-sm-6">
												<code> </code>
											</div>
											<div class="col-sm-6">
												<div class="box-tools">
													<div class="input-group input-group-sm pull-right">
														<input name="search" id="search" class="form-control" placeholder="Search..." type="text" value="" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '#">
														<div class="input-group-btn">
															<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '#"><i class="fa fa-search"></i></button>
														</div>
													</div>
												</div>
											</div>
										</div>
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
																<th class="bg-olive">Delivered</th>
																<th class="bg-olive">Outstanding</th>
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
															<?php foreach ($ordtrack_data as $ot_list) {
																$pocust_date = substr($ot_list['PODATECUST'], 4, 2) . "/" . substr($ot_list['PODATECUST'], 6, 2) . "/" . substr($ot_list['PODATECUST'], 0, 4);
																$crmreq_date = substr($ot_list['CRMREQDATE'], 4, 2) . "/" . substr($ot_list['CRMREQDATE'], 6, 2) . "/" . substr($ot_list['CRMREQDATE'], 0, 4);

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
																	<td style="background-color: white;"></td>
																	<td><?= $ot_list['RQNNUMBER']; ?></td>
																	<td><?= $ot_list['RQNDATE']; ?></td>
																	<td style="background-color: white;"></td>
																	<td><?= $ot_list['PONUMBER']; ?></td>
																	<td><?= $ot_list['PODATE']; ?></td>
																	<td><?= $ot_list['ETDDATE']; ?></td>
																	<td><?= $ot_list['CARGOREADINESSDATE']; ?></td>
																	<td><?= $ot_list['ORIGINCOUNTRY']; ?></td>
																	<td><?= $ot_list['POREMARKS']; ?></td>
																	<td style="background-color: white;"></td>
																	<td><?= $ot_list['ETDORIGINDATE']; ?></td>
																	<td><?= $ot_list['ATDORIGINDATE']; ?></td>
																	<td><?= $ot_list['ETAPORTDATE']; ?></td>
																	<td><?= $ot_list['PIBDATE']; ?></td>
																	<td><?= $ot_list['VENDSHISTATUS']; ?></td>
																	<td style="background-color: white;"></td>
																	<td><?= $ot_list['RECPDATE']; ?></td>
																	<td><?= $ot_list['RECPQTY']; ?></td>
																	<td><?= $ot_list['GRSTATUS']; ?></td>
																	<td style="background-color: white;"></td>
																	<td><?= $ot_list['SHIDATE']; ?></td>
																	<td><?= $ot_list['SHINUMBER']; ?></td>
																	<td><?= $ot_list['CUSTRCPDATE']; ?></td>
																	<td><?= $ot_list['QTYDELIVERED']; ?></td>
																	<td><?= $ot_list['QTYOUTSTANDING']; ?></td>
																	<td><?= $ot_list['POCUSTSTATUS']; ?></td>
																	<td><?= $ot_list['DELIVERYSTATUS']; ?></td>
																	<td style="background-color: white;"></td>
																	<td><?= $ot_list['INVOICEDATE']; ?></td>
																	<td><?= $ot_list['FINSTATUS']; ?></td>
																	<td style="background-color: white;"></td>
																	<td><?= $ot_list['RRSTATUS']; ?></td>
																	<td style="background-color: white;"></td>
																	<td></td>
																	<td></td>
																	<td></td>
																	<td></td>
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