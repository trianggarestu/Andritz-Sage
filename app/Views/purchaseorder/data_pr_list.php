<style>
	.input-sm {
		padding: 4px 4px;
	}
</style>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Purchase Requisition List Waiting to process by Procurement</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Purchase Requisition List Waiting to process</li>
		</ol>
	</section>

	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="#" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i> Refresh</a>
						<a href="#" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Preview"><i class="fa fa-print"></i> Preview
						</a>
						<a href="<?= base_url("salesorderlist/export_excel") ?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh" target="_blank"><i class="fa fa-download"></i> Download
						</a>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<form id="mainform" name="mainform" action="" method="post">
										<div class="row">
											<div class="col-sm-8">
												<code> { only viewed <strong>Purchase Requisition</strong> data that waiting to be processed by the Procurement }</code>
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
																<th>Action</th>
																<th>Customer Name</th>
																<th>Contract No.</th>
																<th>Project No.</th>
																<th>CRM Number</th>
																<th>Inventory No</th>
																<th>Material No</th>
																<th>Req Date</th>
																<th>Sales Person</th>
																<th>Order Description</th>
																<th>Qty</th>
																<th>UoM</th>
																<th style="background-color: white;"></th>
																<th>PR Number</th>
																<th>PR Date</th>
																<th style="background-color: white;"></th>
																<th>PO Vendor</th>
																<th>PO Date</th>

															</tr>
														</thead>
														<tbody>
															<?php
															$no = 0;

															?>
															<?php foreach ($PurchaseOrder_data as $ot_list) {
																if ($ot_list['PrDate'] == '') {
																	$pr_date = '';
																} else {
																	$pr_date = substr($ot_list['PrDate'], 6, 2) . "/" . substr($ot_list['PrDate'], 4, 2) . "/" . substr($ot_list['PrDate'], 0, 4);
																}
															?>

																<tr>
																	<td><?= ++$no; ?></td>
																	<td nowrap>
																		<a href="<?= base_url("PurchaseOrder/update/" . $ot_list['ID_SO']) ?>" title="Update" class="btn btn-default btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox"><i class="fa fa-edit"></i></a>
																		<a href="<?= base_url("PurchaseOrder/sending_notif/" . $ot_list['ID_SO']) ?>" class="btn btn-default btn-sm" title="Resend Notif">
																			<i class="fa fa-send-o"></i>
																		</a>

																	</td>
																	<td><?= $ot_list['CustomerName']; ?></td>
																	<td nowrap><?= $ot_list['ContractNo']; ?></td>
																	<td nowrap><?= $ot_list['ProjectNo']; ?></td>
																	<td><?= $ot_list['CrmNo']; ?></td>
																	<td><?= $ot_list['InventoryNo']; ?></td>
																	<td><?= $ot_list['MaterialNo']; ?></td>
																	<td><?= $ot_list['ReqDate']; ?></td>
																	<td><?= $ot_list['SalesPerson']; ?></td>
																	<td><?= $ot_list['OrderDesc']; ?></td>
																	<td><?= $ot_list['Qty']; ?></td>
																	<td><?= $ot_list['Uom']; ?></td>
																	<td style="background-color: white;"></td>
																	<td><?= $ot_list['PrNumber']; ?></td>
																	<td><?= $pr_date; ?></td>
																	<td style="background-color: white;"></td>
																	<td><?= $ot_list['PoVendor']; ?></td>
																	<td><?= $ot_list['PoVendorDate']; ?></td>
																</tr>

															<?php } ?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</form>
									<div class="row">
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