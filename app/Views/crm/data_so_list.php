<style>
	.input-sm {
		padding: 4px 4px;
	}
</style>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Sales Order List</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Sales Order List</li>
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

	<!-- Untuk menampilkan modal bootstrap umum  -->
	<!--<div class="modal fade" id="modalBox" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class='modal-dialog'>
			<div class='modal-content'>
				<div class='modal-header'>
					<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
					<h4 class='modal-title' id='myModalLabel'> Pengaturan Pengguna</h4>
				</div>
				<div class="fetched-data"></div>
			</div>
		</div>
	</div>
-->
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?= base_url() ?>salesorder" title="Add Sales Order" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Add Sales Order</a>
						<a href="<?= base_url("salesorderlist/preview") ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak" target="_blank"><i class="fa fa-print"></i> Preview
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
														<button type="submit" class="btn btn-default bg-maroon" onclick="$('#'+'mainform').attr('action', '<?= base_url('salesorderlist/search') ?>');$('#'+'mainform').submit();"><i class="fa fa-filter"></i>Go!</button>

													</div>
												</div>
											</div>
											<div class="col-sm-3">
												<div class="box-tools">
													<div class="input-group input-group-sm pull-right">
														<input name="cari" id="cari" class="form-control" placeholder="Search..." type="text" value="<?= $keyword ?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= base_url('salesorderlist/search') ?>');$('#'+'mainform').submit();}">
														<div class="input-group-btn">
															<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= base_url('salesorderlist/search') ?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
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
															<th>Action</th>
															<th>Status</th>
															<th>Customer Name</th>
															<th>Customer Email</th>
															<th>Contract No.</th>
															<th>Project No.</th>
															<th>CRM Number</th>
															<th>PO Customer</th>
															<th>PO Date</th>
															<th>Inventory No</th>
															<th>Material No</th>
															<th>Item Desc.</th>
															<th>Req Date</th>
															<th>Sales Person</th>
															<th>Order Description</th>
															<th>Remarks</th>
															<th>Service Type</th>
															<th>Qty</th>
															<th>UoM</th>


														</tr>
													</thead>
													<tbody>
														<?php
														$no = 0 + (5 * ($currentpage - 1));

														?>
														<?php foreach ($so_data as $ot_list) {

															$crmpodate = substr($ot_list['PODATECUST'], 4, 2) . "/" . substr($ot_list['PODATECUST'], 6, 2) . "/" .  substr($ot_list['PODATECUST'], 0, 4);
															$crmreqdate = substr($ot_list['CRMREQDATE'], 4, 2) . '/' . substr($ot_list['CRMREQDATE'], 6, 2) . '/' . substr($ot_list['CRMREQDATE'], 0, 4);
														?>
															<tr>
																<td><?= ++$no; ?></td>
																<td nowrap>
																	<?php if (($ot_list['POSTINGSTAT'] == 1) and ($ot_list['OFFLINESTAT'] == 0)) {
																		$bysetting = 1; ?>
																		<a href="<?= base_url("salesorder/csropenview/" . $ot_list['CSRUNIQ']) ?>" class="btn btn-default btn-sm" title="SO View">
																			<i class="fa fa-file"></i>
																		</a>
																	<?php } ?>
																	<?php if ($ot_list['POSTINGSTAT'] == 0) { ?>
																		<a href="<?= base_url("salesorder/update/" . $ot_list['CSRUNIQ']) ?>" title="Update" class="btn btn-default btn-sm"><i class="fa fa-edit"></i></a>
																	<?php } ?>
																	<?php if (($ot_list['POSTINGSTAT'] == 1) and ($ot_list['OFFLINESTAT'] == 1)) { ?>
																		<a href="<?= base_url("salesorder/csropenview/" . $ot_list['CSRUNIQ']) ?>" class="btn btn-default btn-sm" title="Posting & Sending Notif">
																			<i class="fa fa-send-o"></i>
																		</a>
																	<?php } ?>
																	<?php if ($ot_list['POSTINGSTAT'] == 0) { ?>
																		<a href="#" title="Delete" class="btn btn-default btn-sm" data-toggle="modal" data-target="#confirm-delete" data-href="<?= base_url("salesorderlist/deletedata/$ot_list[CSRUNIQ]") ?>"><i class="fa fa-trash"></i></a>
																	<?php } ?>


																</td>
																<td>
																	<?php $postingstat = $ot_list['POSTINGSTAT'];
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
																	} ?>
																</td>
																<td><?= $ot_list['NAMECUST']; ?></td>
																<td><?= $ot_list['EMAIL1CUST']; ?></td>
																<td nowrap><?= $ot_list['CONTRACT']; ?></td>
																<td nowrap><?= $ot_list['PROJECT']; ?></td>
																<td><?= $ot_list['CRMNO']; ?></td>
																<td><?= $ot_list['PONUMBERCUST']; ?></td>
																<td><?= $crmpodate; ?></td>
																<td><?= $ot_list['ITEMNO']; ?></td>
																<td><?= $ot_list['MATERIALNO']; ?></td>
																<td><?= $ot_list['ITEMDESC']; ?></td>
																<td><?= $crmreqdate; ?></td>
																<td><?= $ot_list['SALESNAME']; ?></td>
																<td><?= $ot_list['ORDERDESC']; ?></td>
																<td><?= $ot_list['CRMREMARKS']; ?></td>
																<td><?= $ot_list['SERVICETYPE']; ?></td>
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
										<!-- Pagination template-->
										<div class="col-sm-6">

										</div>
										<div class="col-sm-6">
											<div class="dataTables_paginate paging_simple_numbers">
												<div><?= $pager->links('csr_data', 'bootstrap_pagination'); ?>
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
		</div>
	</section>
</div>


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

<?php //echo view('settings/confirm_delete') 
?>