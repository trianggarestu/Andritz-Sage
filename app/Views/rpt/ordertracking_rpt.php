<style>
	.input-sm {
		padding: 4px 4px;
	}
</style>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Order Tracking</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Order Tracking</li>
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
												<code> { only viewed unfullfilled data <strong>order tracking</strong> }</code>
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
														<thead class="bg-gray disabled color-palette">
															<tr>
																<th>No</th>
																<th>Contract</th>
																<th nowrap>Customer Name</th>
																<th>Customer Email</th>
																<th>Project No.</th>
																<th>CRM Number</th>
																<th>PO Customer</th>
																<th nowrap>PO Date</th>
																<th nowrap>Req. Date</th>
																<th>Sales Person</th>
																<th>Inventory No</th>
																<th>Material No</th>
																<th>Order Description</th>
																<th>Qty</th>
																<th>Uom</th>
																<th style="background-color: white;"></th>
																<th>PR Number</th>
																<th>PR Date</th>
																<th style="background-color: white;"></th>
																<th>PO Vendor</th>
																<th>PO Date</th>
																<th>ETD</th>
																<th>Cargo Readiness</th>
																<th>Origin Country</th>
																<th>Remarks</th>
																<th style="background-color: white;"></th>
																<th>ETD Origin</th>
																<th>ATD Origin</th>
																<th>ETA Port</th>
																<th>PIB</th>
																<th>Vend. Shipment Stat</th>
																<th style="background-color: white;"></th>
																<th>GR Date</th>
																<th>Qty</th>
																<th>Status</th>
																<th style="background-color: white;"></th>
																<th>Delivery Date</th>
																<th>DN Number</th>
																<th>Received Date</th>
																<th>Delivered</th>
																<th>Outstanding</th>
																<th>PO Status</th>
																<th>DN Status</th>
																<th style="background-color: white;"></th>
																<th>Invoice Date</th>
																<th>Status</th>
																<th style="background-color: white;"></th>
																<th>RR Status</th>
																<th style="background-color: white;"></th>
																<th>PO Cust to PR</th>
																<th>PO to PO</th>
																<th>ON TIME DEL</th>
																<th>PO to DN</th>

															</tr>
														</thead>
														<tbody>
															<?php
															$no = 0;

															?>
															<?php foreach ($ordtrack_data as $ot_list) {

																if ($ot_list['PrDate'] == '') {
																	$pr_date = '';
																} else {
																	$pr_date = substr($ot_list['PrDate'], 6, 2) . "/" . substr($ot_list['PrDate'], 4, 2) . "/" . substr($ot_list['PrDate'], 0, 4);
																}
															?>
																<tr>
																	<td><?= ++$no; ?></td>
																	<td><?= $ot_list['ContractNo']; ?></td>
																	<td><?= $ot_list['CustomerName']; ?></td>
																	<td><?= $ot_list['CustomerEmail']; ?></td>
																	<td><?= $ot_list['ProjectNo']; ?></td>
																	<td><?= $ot_list['CrmNo']; ?></td>
																	<td><?= $ot_list['PoCustomer']; ?></td>
																	<td><?= $ot_list['PoDate']; ?></td>
																	<td><?= $ot_list['ReqDate']; ?></td>
																	<td><?= $ot_list['SalesPerson']; ?></td>
																	<td><?= $ot_list['InventoryNo']; ?></td>
																	<td><?= $ot_list['MaterialNo']; ?></td>
																	<td><?= $ot_list['OrderDesc']; ?></td>
																	<td><?= $ot_list['Qty']; ?></td>
																	<td><?= $ot_list['Uom']; ?></td>
																	<td style="background-color: white;"></td>
																	<td><?= $ot_list['PrNumber']; ?></td>
																	<td><?= $pr_date; ?></td>
																	<td style="background-color: white;"></td>
																	<td><?= $ot_list['PoVendor']; ?></td>
																	<td><?= $ot_list['PoVendorDate']; ?></td>
																	<td><?= $ot_list['EtdDate']; ?></td>
																	<td><?= $ot_list['CargoreadinessDate']; ?></td>
																	<td><?= $ot_list['OriginCountry']; ?></td>
																	<td><?= $ot_list['Remarks']; ?></td>
																	<td style="background-color: white;"></td>
																	<td><?= $ot_list['EtdOriginDate']; ?></td>
																	<td><?= $ot_list['AtdOriginDate']; ?></td>
																	<td><?= $ot_list['EtaPortDate']; ?></td>
																	<td><?= $ot_list['PIBDate']; ?></td>
																	<td><?= $ot_list['PoReceiptStatus']; ?></td>
																	<td style="background-color: white;"></td>
																	<td><?= $ot_list['GrDate']; ?></td>
																	<td><?= $ot_list['GrQty']; ?></td>
																	<td><?= $ot_list['GrStatus']; ?></td>
																	<td style="background-color: white;"></td>
																	<td><?= $ot_list['ShDeliveryDate']; ?></td>
																	<td><?= $ot_list['ShDnNumber']; ?></td>
																	<td><?= $ot_list['ShReceiptDate']; ?></td>
																	<td><?= $ot_list['ShDelivered']; ?></td>
																	<td><?= $ot_list['ShOutstanding']; ?></td>
																	<td><?= $ot_list['ShPoStatus']; ?></td>
																	<td><?= $ot_list['ShDnStatus']; ?></td>
																	<td style="background-color: white;"></td>
																	<td><?= $ot_list['FinInvDate']; ?></td>
																	<td><?= $ot_list['FinStatus']; ?></td>
																	<td style="background-color: white;"></td>
																	<td><?= $ot_list['FinRrStatus']; ?></td>
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