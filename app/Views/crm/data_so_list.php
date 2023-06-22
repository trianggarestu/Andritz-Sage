<style>
	.input-sm {
		padding: 4px 4px;
	}
</style>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Sales Order List</h1>
		<ol class="breadcrumb">
			<li><a href="<?= base_url('administration') ?>"><i class="fa fa-home"></i> Home</a></li>
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
						<a href="<?= base_url() ?>salesorder" title="Add New Sales Orders" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i> Add New Sales Orders</a>
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
															<th>Contract Desc.</th>
															<th>Project No.</th>
															<th>Project Desc</th>
															<th>CRM Number</th>
															<th>PO Customer</th>
															<th>PO Date</th>
															<th>Req Date</th>
															<th>Sales Person</th>
															<th>Order Description</th>
															<th>Remarks</th>


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
																<td>
																	<a href="<?= base_url("salesorder/csropenview/" . $ot_list['CSRUNIQ']) ?>" class="btn btn-default btn-sm" title="SO View">
																		<i class="fa fa-file"></i>
																	</a>
																</td>
																<td>
																	<?php $postingstat = $ot_list['POSTINGSTAT'] . $ot_list['OFFLINESTAT'];
																	switch ($postingstat) {
																		case "00":
																			echo "<span class='label label-warning'>Open</span>";
																			break;
																		case "11":
																			echo "<span class='label label-warning'>Posted Pending Notif</span>";
																			break;
																		case "10":
																			echo "<span class='label label-success'>Posted & Sending Notif</span>";
																			break;
																		case "20":
																			echo "<span class='label label-danger'>Deleted</span>";
																			break;
																		case "21":
																			echo "<span class='label label-danger'>Deleted</span>";
																			break;
																		default:
																			echo "<span class='label label-warning'>Open</span>";
																	} ?>
																</td>
																<td><?= $ot_list['NAMECUST']; ?></td>
																<td><?= $ot_list['EMAIL1CUST']; ?></td>
																<td nowrap><?= $ot_list['CONTRACT']; ?></td>
																<td><?= $ot_list['CTDESC']; ?></td>
																<td nowrap><?= $ot_list['PROJECT']; ?></td>
																<td><?= $ot_list['PRJDESC']; ?></td>
																<td><?= $ot_list['CRMNO']; ?></td>
																<td><?= $ot_list['PONUMBERCUST']; ?></td>
																<td><?= $crmpodate; ?></td>
																<td><?= $crmreqdate; ?></td>
																<td><?= $ot_list['SALESNAME']; ?></td>
																<td><?= $ot_list['ORDERDESC']; ?></td>
																<td><?= $ot_list['CRMREMARKS']; ?></td>

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