<style>
	.input-sm {
		padding: 4px 4px;
	}
</style>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Waiting List Purchase Requisition to process by Procurement</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Requisition List Waiting to process</li>
		</ol>
	</section>
	<!-- Untuk menampilkan modal bootstrap action success, failed  -->
	<input id="success-code" type="hidden" value="<?= $success_code ?>">
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
						<a href="<?= base_url() ?>purchaseorder/refresh" title="Refresh Data" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i> Refresh Data</a>

					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<form id="mainform" name="mainform" action="" method="post">
										<div class="row">
											<div class="col-sm-6">
												<code> { only viewed <strong>Purchase Requisition</strong> data that waiting to be processed by the Procurement }</code>
											</div>
											<div class="col-sm-6">
												<div class="box-tools">
													<div class="input-group input-group-sm pull-right">
														<input name="cari" id="cari" class="form-control" placeholder="Search..." type="text" value="<?= $keyword ?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= base_url('purchaseorder/search') ?>');$('#'+'mainform').submit();}">
														<div class="input-group-btn">
															<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= base_url('purchaseorder/search') ?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<div class="table-responsive">
													<table class="table table-bordered table-striped dataTable table-hover">
														<thead class="bg-gray disabled">
															<tr>
																<th style="vertical-align: top;">No.</th>
																<th style="vertical-align: top;">Contract/Project/CRM<br>Contract Desc.<br>P/O Customer<br>Customer</th>
																<!--<th style="vertical-align: top;">Inventory No./Material No./Service Type<br>
																	Inventory Desc.<br>
																	Qty
																</th>-->
																<th style="vertical-align: top;">Req. Date</th>
																<th style="background-color: white;"></th>
																<th style="vertical-align: top;">Rqn. Number</th>
																<th style="vertical-align: top;">Rqn. Date</th>
																<th style="vertical-align: top;">Status</th>
																<th style="background-color: white;"></th>
																<th style="vertical-align: top; width:30%;">Action</th>
																<th style="vertical-align: top;">P/O Number</th>
															</tr>
														</thead>
														<tbody>

															<?php
															$no = 1;
															foreach ($purchaseOrder_data as $ot_list) {
																$crmreq_date = substr($ot_list['CRMREQDATE'], 4, 2) . "/" . substr($ot_list['CRMREQDATE'], 6, 2) . "/" . substr($ot_list['CRMREQDATE'], 0, 4);
																$crmpodate = substr($ot_list['PODATECUST'], 4, 2) . "/" . substr($ot_list['PODATECUST'], 6, 2) . "/" .  substr($ot_list['PODATECUST'], 0, 4);
																$rqn_date = substr($ot_list['RQNDATE'], 4, 2) . "/" . substr($ot_list['RQNDATE'], 6, 2) . "/" . substr($ot_list['RQNDATE'], 0, 4);
																/*if ($ot_list['PODATE'] == '') {
																	$po_date = '';
																	$etd_date = '';
																} else {
																	$po_date = substr($ot_list['PODATE'], 4, 2) . "/" . substr($ot_list['PODATE'], 6, 2) . "/" . substr($ot_list['PODATE'], 0, 4);
																	$etd_date = substr($ot_list['ETDDATE'], 4, 2) . "/" . substr($ot_list['ETDDATE'], 6, 2) . "/" . substr($ot_list['ETDDATE'], 0, 4);
																}

																if ($ot_list['CARGOREADINESSDATE'] == '') {
																	$cargo_readiness_date = '';
																} else {
																	$cargo_readiness_date = substr($ot_list['CARGOREADINESSDATE'], 4, 2) . "/" . substr($ot_list['CARGOREADINESSDATE'], 6, 2) . "/" . substr($ot_list['CARGOREADINESSDATE'], 0, 4);
																}*/
															?>

																<tr>
																	<td style="vertical-align: top;"><?= $no++; ?></td>
																	<td nowrap><strong><a href="<?= base_url("administration/csrpostedview/" . $ot_list['CSRUNIQ']) ?>" title="Click here for detail" target="_blank"><?= $ot_list['CONTRACT'] ?></a></strong>
																		<?= " / " . $ot_list['PROJECT'] . " / " . $ot_list['CRMNO']; ?><br>
																		<strong><?= $ot_list['CTDESC']; ?></strong><br>
																		<strong><?= $ot_list['PONUMBERCUST'] . ' - ' . $crmpodate; ?></strong><br>
																		<small>(<?= $ot_list['NAMECUST']; ?>)</small><br>


																	</td>

																	<td nowrap><?= $crmreq_date;
																				?></td>

																	<td style="background-color: white;"></td>
																	<td><strong><?= $ot_list['RQNNUMBER']; ?></strong></td>
																	<td><?= $rqn_date; ?></td>
																	<td><?php $postingstat = $ot_list['RQNPOSTINGSTAT'] . $ot_list['RQNOFFLINESTAT'];
																		switch ($postingstat) {
																			case "00":
																				echo "<span class='label label-default'>Open</span>";
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
																				echo "<span class='label label-default'>Open</span>";
																		} ?></td>
																	<td style="background-color: white;"></td>
																	<td>
																		<?php if (($ot_list['CTITEMCSR'] != ($ot_list['CTITEMPO'] + $ot_list['CTITEMPOOPEN']))) :
																		?>
																			<div class="btn-group">
																				<button type="button" class="btn btn-social btn-flat btn-info btn-sm" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Choose Button</button>
																				<ul class="dropdown-menu" role="menu">

																					<li>
																						<a href="<?= base_url("purchaseorder/add/" . $ot_list['RQNUNIQ'] . '/1/0') ?>" class="btn btn-social btn-flat btn-block btn-sm"><i class="fa fa-check-square-o"></i>Add P/O & Posting</a>
																					</li>
																					<li>
																						<a href="<?= base_url("purchaseorder/add/" . $ot_list['RQNUNIQ'] . '/0/0') ?>" class="btn btn-social btn-flat btn-block btn-sm"><i class="fa fa-edit"></i> Add P/O & Save</a>
																					</li>
																				</ul>
																			</div>
																		<?php endif;
																		?>
																	</td>
																	<td style="vertical-align: top;" nowrap>
																		<?php if (is_array($polist_data)) { ?>
																			<div class="table-responsive">
																				<table class="table table-bordered dataTable table-hover nowrap">

																					<tbody>
																						<?php
																						$no_l = 0;

																						foreach ($polist_data as $poheader) :
																							if ($ot_list['RQNUNIQ'] == $poheader['RQNUNIQ']) :
																								$povend_date = substr($poheader['PODATE'], 4, 2) . "/" . substr($poheader['PODATE'], 6, 2) . "/" . substr($poheader['PODATE'], 0, 4);

																						?>
																								<tr>
																									<td style="width: 32%;"><?= trim($poheader['PONUMBER']) ?> <small>(<?= $povend_date ?>)</small></td>

																									<td>
																										<?php if ($poheader['POPOSTINGSTAT'] == 0) :
																										?>

																											<a href="<?= base_url("purchaseorder/update/" . $poheader['POUNIQ'] . '/1') ?>" data-remote="false" data-toggle="modal" data-target="#modalBox" class="btn btn-social btn-flat btn-info btn-sm">
																												<i class="fa fa-check-square-o"></i> Update & Posting
																											</a>

																											<a href="<?= base_url("purchaseorder/update/" . $poheader['POUNIQ'] . '/0') ?>" data-remote="false" data-toggle="modal" data-target="#modalBox" class="btn btn-social bg-yellow btn-flat btn-sm">
																												<i class="fa fa-edit"></i> Update & Save
																											</a>
																											<a href="" data-href="<?= base_url("purchaseorder/delete/" . $poheader['POUNIQ']) ?>" class="btn bg-red btn-flat btn-sm" title="Delete Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																										<?php
																										endif;
																										?>

																										<?php if ($poheader['POPOSTINGSTAT'] == 1 and empty($poheader['CARGOREADINESSDATE'])) :
																										?>
																											<a href="<?= base_url("purchaseorder/update_cargoreadiness/" . $poheader['POUNIQ'] . '/1') ?>" class="btn btn-social btn-flat btn-info btn-sm 
																											<?php if (!empty($poheader['CARGOREADINESSDATE'])) {
																												echo 'disabled';
																											} ?>" title="Update Cargo Readiness" data-remote="false" data-toggle="modal" data-target="#modalBox">
																												<i class="fa fa-check-square-o"></i> Update Cargo Readiness & Posting
																											</a>
																										<?php
																										endif;
																										?>
																										<?php if ($poheader['POPOSTINGSTAT'] == 1 and $poheader['POOFFLINESTAT'] == 1) :
																										?>
																											<a href="<?= base_url("purchaseorder/sendnotif/" . $poheader['POUNIQ']) ?>" class="btn bg-blue btn-social btn-flat btn-sm 
																											<?php if (empty($poheader['CARGOREADINESSDATE'])) {
																												echo 'disabled';
																											} ?>" title="Sending Notif Manually">
																												<i class="fa fa-send-o"></i>Send Notif
																											</a>
																										<?php
																										endif;
																										?>
																									</td>
																								</tr>

																						<?php
																							endif;
																						endforeach;

																						?>
																					</tbody>
																				</table>
																			</div>
																		<?php } ?>
																	</td>



																</tr>
																<tr>
																	<td style="vertical-align: top;" nowrap></td>

																	<td style="vertical-align: top;" colspan="6" nowrap>
																		<div class="table-responsive">
																			<table class="table table-bordered dataTable table-hover nowrap">
																				<thead class="bg-gray disabled color-palette">
																					<tr>

																						<th class="padat">No</th>

																						<th>Type</th>
																						<th>Inventory <br>No.</th>
																						<th>Material <br>No.</th>
																						<th>Item Description</th>
																						<th>Qty.</th>
																						<th>Uom</th>




																					</tr>
																				</thead>
																				<tbody>
																					<?php
																					$no_l = 0;
																					foreach ($so_l_data as $items) :
																						if ($ot_list['CSRUNIQ'] == $items['CSRUNIQ']) :
																							if (empty($items['RQNDATE'])) {
																								$rqndate = '';
																							} else {
																								$rqndate = substr($items['RQNDATE'], 6, 2) . "/" . substr($items['RQNDATE'], 4, 2) . "/" . substr($items['RQNDATE'], 0, 4);
																							}
																					?>
																							<tr>

																								<td class="text-center" style="width: 5%;"><?= ++$no_l ?></td>
																								<td style="width: 10%;"><?= $items['SERVICETYPE']
																														?></td>

																								<td style="width: 12%;"><?= $items['ITEMNO']
																														?></td>
																								<td style="width: 12%;"><?= $items['MATERIALNO']
																														?></td>
																								<td nowrap><?= $items['ITEMDESC']
																											?></td>
																								<td nowrap style="width: 10%;"><?= number_format($items['QTY'], 0, ",", ".")
																																?></td>
																								<td nowrap style="width: 10%;"><?= $items['STOCKUNIT']
																																?></td>



																							</tr>

																					<?php
																						endif;
																					endforeach;
																					?>
																				</tbody>
																			</table>
																		</div>
																	</td>
																	<td style="background-color: white;"></td>
																	<td colspan="2">
																		<div class="table-responsive">
																			<table class="table table-bordered dataTable table-hover nowrap">
																				<thead class="bg-gray disabled color-palette">
																					<tr>
																						<th class="padat">Status</th>
																						<th>P/O Number</th>
																						<th>P/O <br>(Date)</th>
																						<th>ETD <br>(Date)</th>
																						<th>Cargo Readiness<br> (Date)</th>
																						<th>Original Country</th>
																						<th>Remarks</th>






																					</tr>
																				</thead>
																				<tbody>
																					<?php
																					//$no_l = 0;
																					foreach ($so_l_data as $items) :
																						if ($ot_list['CSRUNIQ'] == $items['CSRUNIQ']) :
																							if (empty($items['PODATE'])) {
																								$podate = '';
																							} else {
																								$podate = substr($items['PODATE'], 4, 2) . "/" . substr($items['PODATE'], 6, 2) . "/" . substr($items['PODATE'], 0, 4);
																							}
																							if (empty($items['ETDDATE'])) {
																								$etddate = '';
																							} else {
																								$etddate = substr($items['ETDDATE'], 4, 2) . "/" . substr($items['ETDDATE'], 6, 2) . "/" . substr($items['ETDDATE'], 0, 4);
																							}
																							if (empty($items['CARGOREADINESSDATE'])) {
																								$cargoreadinessdate = '';
																							} else {
																								$cargoreadinessdate = substr($items['CARGOREADINESSDATE'], 4, 2) . "/" . substr($items['CARGOREADINESSDATE'], 6, 2) . "/" . substr($items['CARGOREADINESSDATE'], 0, 4);
																							}

																					?>
																							<tr>
																								<td style="width: 12%;"><?php

																														$postingstat = $items['POPOSTINGSTAT'] . $items['POOFFLINESTAT'];
																														switch ($postingstat) {
																															case "00":
																																echo "<span class='label label-default'>Open</span>";
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
																																echo "<span class='label label-default'>Open</span>";
																														}
																														?>&nbsp;</td>

																								<td style="width: 10%;"><?= $items['PONUMBER']
																														?></td>
																								<td style="width: 10%;"><?= $podate
																														?></td>
																								<td style="width: 12%;"><?= $etddate
																														?></td>
																								<td style="width: 12%;"><?= $cargoreadinessdate
																														?></td>
																								<td style="width: 12%;"><?= $items['ORIGINCOUNTRY']
																														?></td>
																								<td style="width: 12%;"><?= $items['POREMARKS']
																														?></td>





																							</tr>

																					<?php
																						endif;
																					endforeach;
																					?>
																				</tbody>
																			</table>
																		</div>
																	</td>


																</tr>
															<?php } ?>
														</tbody>
													</table>
													<div><?php //= $pager->links(); 
															?> </div>
												</div>
											</div>
										</div>
									</form>
									<div class="row">
										<div class='col-sm-12'>
											<?= validation_list_errors() ?>
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

<?php echo view('settings/modalbox/modal_confirm_delete') ?>