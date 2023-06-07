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
						<a href="<?= base_url() ?>salesorderopen/refresh" title="Refresh Data" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i> Refresh Data</a>

					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<form id="mainform" name="mainform" action="" method="post">
										<div class="row">
											<div class="col-sm-6">
												<code> { only viewed <strong>Sales Order</strong> data status <strong>Open</strong> }</code>
											</div>
											<div class="col-sm-6">
												<div class="box-tools">
													<div class="input-group input-group-sm pull-right">
														<input name="cari" id="cari" class="form-control" placeholder="Search..." type="text" value="<?= $keyword ?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= base_url('salesorderopen/search') ?>');$('#'+'mainform').submit();}">
														<div class="input-group-btn">
															<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= base_url('salesorderopen/search') ?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
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
																<th style="vertical-align: top;">No.</th>
																<th style="vertical-align: top;">Action</th>
																<th style="vertical-align: top;">Status</th>
																<th nowrap>Contract/Project/CRM Number<br>Contract Desc.<br>Order Description</th>
																<th nowrap>PO Customer - Order Desc.<br>Customer Name<br>Customer Email</th>
																<th style="vertical-align: top;">P/O Cust. Date</th>
																<th style="vertical-align: top;">Req. Date</th>
																<th style="vertical-align: top;">Sales Person</th>
															</tr>
														</thead>
														<tbody>

															<?php
															$no = 0;
															foreach ($so_data as $ot_list) {

																$crmpodate = substr($ot_list['PODATECUST'], 4, 2) . "/" . substr($ot_list['PODATECUST'], 6, 2) . "/" .  substr($ot_list['PODATECUST'], 0, 4);
																$crmreqdate = substr($ot_list['CRMREQDATE'], 4, 2) . '/' . substr($ot_list['CRMREQDATE'], 6, 2) . '/' . substr($ot_list['CRMREQDATE'], 0, 4);
															?>
																<tr>
																	<td style="vertical-align: top;" nowrap><?= ++$no; ?></td>
																	<td style="vertical-align: top;" nowrap>
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
																	<td style="vertical-align: top;" nowrap>
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
																	<td nowrap><strong><a href="#"><?= $ot_list['CONTRACT'] ?></a></strong>
																		<?= " / " . $ot_list['PROJECT'] . " / " . $ot_list['CRMNO']; ?><br>
																		<strong><?= $ot_list['CTDESC']; ?></strong><br>
																		CRM Remarks : <?= $ot_list['CRMREMARKS']; ?>
																		<br>
																		<table class="table table-bordered table-striped dataTable">
																			<thead class="bg-gray disabled">
																				<tr>
																					<th colspan="3"><small>Inventory Info</small>
																					</th>
																				</tr>
																			</thead>
																			<tr>
																				<td style="width: 15%;"><small>Item No./Material No.</small></td>
																				<td style="width: 1%;"><small>:</small></td>
																				<td><small><?= $ot_list['ITEMNO'] . " / " .  $ot_list['MATERIALNO'];
																							?></small></td>
																			</tr>
																			<tr>
																				<td><small>Item Description</small></td>
																				<td><small>:</small></td>
																				<td><small><?= "<strong>" .  $ot_list['ITEMDESC'] . "</strong><br>"; ?></small></td>
																			</tr>
																			<tr>
																				<td><small>Type</small></td>
																				<td><small>:</small></td>
																				<td><small><?= $ot_list['SERVICETYPE']; ?></small></td>
																			</tr>
																			<tr>
																				<td><small>Qty</small></td>
																				<td><small>:</small></td>
																				<td><small><?= number_format($ot_list['QTY'], 0, ",", ".") . ' (' . trim($ot_list['STOCKUNIT']) . ')' ?></small></td>
																			</tr>
																		</table>

																	</td>

																	<td style="vertical-align: top;">
																		<strong><?= $ot_list['PONUMBERCUST'] . ' - ' . $ot_list['ORDERDESC']; ?></strong><br>
																		<strong><?= $ot_list['NAMECUST']; ?></strong><br>
																		e-Mail : <?= $ot_list['EMAIL1CUST']; ?>
																	</td>
																	<td style="vertical-align: top;"><?= $crmpodate ?></td>
																	<td style="vertical-align: top;"><?= $crmreqdate ?></td>
																	<td style="vertical-align: top;"><?= $ot_list['SALESNAME']; ?></td>

																</tr>

															<?php } ?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</form>
									<div class="row">


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

<?php echo view('settings/confirm_delete') ?>