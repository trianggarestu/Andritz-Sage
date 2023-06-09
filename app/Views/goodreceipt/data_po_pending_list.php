<style>
	.input-sm {
		padding: 4px 4px;
	}
</style>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Good Receipt</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Good Receipt</li>
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
						<a href="<?= base_url() ?>goodreceipt/refresh" title="Refresh Data" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i> Refresh Data</a>

					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<form id="mainform" name="mainform" action="" method="post">
										<div class="row">
											<div class="col-sm-9">
												<p><i class="fa fa-info-circle"></i><code> { only viewed <strong>Purchase Orders</strong> data that waiting to be processed by the Good Receipt }</code></p>

											</div>
											<div class="col-sm-3" style="vertical-align: text-bottom;">
												<div class="box-tools">
													<div class="input-group input-group-sm pull-right">
														<input name="cari" id="cari" class="form-control" placeholder="Search..." type="text" value="<?= $keyword ?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= base_url('goodreceipt/search') ?>');$('#'+'mainform').submit();}">
														<div class="input-group-btn">
															<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= base_url('goodreceipt/search') ?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
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
																<th style="vertical-align: top;">Contract/Project/CRM<br>Contract Desc.<br>Customer</th>
																<th style="vertical-align: top;">Req. Date</th>
																<th style="background-color: white;"></th>
																<th style="vertical-align: top;">P/O Vendor</th>
																<th style="vertical-align: top;">P/O Date</th>
																<th style="background-color: white;"></th>
																<th style="vertical-align: top;">Action</th>
															</tr>
														</thead>
														<tbody>

															<?php
															$no = 1;
															foreach ($receipt_data as $rcp_list) {
																$crmreq_date = substr($rcp_list['CRMREQDATE'], 4, 2) . "/" . substr($rcp_list['CRMREQDATE'], 6, 2) . "/" . substr($rcp_list['CRMREQDATE'], 0, 4);
																$pocust_date = substr($rcp_list['PODATECUST'], 4, 2) . "/" . substr($rcp_list['PODATECUST'], 6, 2) . "/" . substr($rcp_list['PODATECUST'], 0, 4);
																$po_date = substr($rcp_list['PODATE'], 4, 2) . "/" . substr($rcp_list['PODATE'], 6, 2) . "/" . substr($rcp_list['PODATE'], 0, 4);


																if (null == $rcp_list['ETDORIGINDATE']) {
																	$etdorigin_date = '';
																} else {
																	$etdorigin_date = substr($rcp_list['ETDORIGINDATE'], 4, 2) . "/" . substr($rcp_list['ETDORIGINDATE'], 6, 2) . "/" . substr($rcp_list['ETDORIGINDATE'], 0, 4);
																}
																if (null == $rcp_list['ATDORIGINDATE']) {
																	$atdorigin_date = '';
																} else {
																	$atdorigin_date = substr($rcp_list['ATDORIGINDATE'], 4, 2) . "/" . substr($rcp_list['ATDORIGINDATE'], 6, 2) . "/" . substr($rcp_list['ATDORIGINDATE'], 0, 4);
																}
																if (null == $rcp_list['ETAPORTDATE']) {
																	$etaport_date = '';
																} else {
																	$etaport_date = substr($rcp_list['ETAPORTDATE'], 4, 2) . "/" . substr($rcp_list['ETAPORTDATE'], 6, 2) . "/" . substr($rcp_list['ETAPORTDATE'], 0, 4);
																}
																if (null == $rcp_list['PIBDATE']) {
																	$pib_date = '';
																} else {
																	$pib_date = substr($rcp_list['PIBDATE'], 4, 2) . "/" . substr($rcp_list['PIBDATE'], 6, 2) . "/" . substr($rcp_list['PIBDATE'], 0, 4);
																}

															?>

																<tr>
																	<td style="vertical-align: top;"><?= $no++; ?></td>
																	<td style="vertical-align: top;" nowrap>
																		<strong><a href="<?= base_url("administration/csrpostedview/" . $rcp_list['CSRUNIQ']) ?>" title="Click here for detail" target="_blank"><?= $rcp_list['CONTRACT'] ?></a></strong>
																		<?= " / " . $rcp_list['PROJECT'] . " / " . $rcp_list['CRMNO']; ?><br>
																		<strong><?= $rcp_list['CTDESC']; ?></strong><br>
																		<strong><?= $rcp_list['PONUMBERCUST'] . ' - ' . $pocust_date; ?></strong><br>
																		<small>(<?= $rcp_list['NAMECUST']; ?>)</small><br>

																	</td>

																	<td style="vertical-align: top;" nowrap><?= $crmreq_date;
																											?></td>

																	<td style="background-color: white;"></td>
																	<td style="vertical-align: top;">
																		<strong><a href="<?= base_url("administration/popostedview/" . $rcp_list['POUNIQ']) ?>" title="Click here for detail" target="_blank"><?= $rcp_list['PONUMBER']; ?></a></strong><br>
																		<?php if (!empty($rcp_list['LOGUNIQ'])) : ?>
																			<table class="table table-bordered table-striped dataTable">
																				<thead class="bg-gray disabled">
																					<tr>
																						<td colspan="3">Logistics-Info</td>
																					</tr>
																				</thead>
																				<tr>
																					<td><small>ETD Origin</small></td>
																					<td><small>:</small></td>
																					<td><small><?= $etdorigin_date ?></small></td>
																				</tr>
																				<tr>
																					<td><small>ATD Origin</small></td>
																					<td><small>:</small></td>
																					<td><small><?= $atdorigin_date ?></small></td>
																				</tr>
																				<tr>
																					<td><small>ETA Port</small></td>
																					<td><small>:</small></td>
																					<td><small><?= $etaport_date ?></small></td>
																				</tr>
																				<tr>
																					<td><small>PIB</small></td>
																					<td><small>:</small></td>
																					<td><small><?= $pib_date ?></small></td>
																				</tr>
																				<tr>
																					<td><small>shipment Status</small></td>
																					<td><small>:</small></td>
																					<td><strong><small><?= $rcp_list['VENDSHISTATUS'] ?></small></strong></td>
																				</tr>
																			</table>
																		<?php endif; ?>

																	</td>
																	<td style="vertical-align: top;">
																		<?= $po_date ?></td>
																	<td style="background-color: white;"></td>
																	<td style="vertical-align: top;" nowrap>
																		<div class="btn-group">
																			<button type="button" class="btn btn-social btn-flat btn-info btn-sm" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Choose Button</button>
																			<ul class="dropdown-menu" role="menu">
																				<?php if (($rcp_list['RCPPOSTINGSTAT'] == 0) or (empty($rcp_list['RCPOFFLINESTAT']))) :
																				?>
																					<li>
																						<a href="<?= base_url("goodreceipt/add/" . $rcp_list['POUNIQ'] . '/1/0') ?>" class="btn btn-social btn-flat btn-block btn-sm"><i class="fa fa-edit"></i> Add Good Receipt & Posting</a>
																					</li>
																					<li>
																						<a href="<?= base_url("goodreceipt/add/" . $rcp_list['POUNIQ'] . '/0/0') ?>" class="btn btn-social btn-flat btn-block btn-sm"><i class="fa fa-edit"></i> Add Good Receipt & Save</a>
																					</li>

																				<?php endif;
																				?>


																			</ul>
																		</div>
																		<?php if (is_array($grlist_data)) { ?>
																			<div class="table-responsive">
																				<table class="table table-bordered dataTable table-hover nowrap">
																					<thead class="bg-gray disabled">
																						<tr>
																							<td>Status</td>
																							<td colspan="2">G/R Info.</td>
																						</tr>
																					</thead>
																					<tbody>
																						<?php
																						$no_l = 0;

																						foreach ($grlist_data as $grheader) :
																							if ($rcp_list['POUNIQ'] == $grheader['POUNIQ']) :
																								$gr_date = substr($grheader['RECPDATE'], 4, 2) . "/" . substr($grheader['RECPDATE'], 6, 2) . "/" . substr($grheader['RECPDATE'], 0, 4);

																						?>
																								<tr>
																									<td><?php

																										$postingstat = $grheader['RCPPOSTINGSTAT'];
																										switch ($postingstat) {
																											case "0":
																												echo "<span class='label label-default'>Open</span>";
																												break;
																											case "1":
																												echo "<span class='label label-success'>Posted</span>";
																												break;
																											case "2":
																												echo "<span class='label label-danger'>Deleted</span>";
																												break;
																											default:
																												echo "<span class='label label-default'>Open</span>";
																										}
																										?></td>
																									<td style="width: 32%;"><?= trim($grheader['RECPNUMBER']) ?> <small>(<?= $gr_date ?>)</small></td>

																									<td>
																										<?php if ($grheader['RCPPOSTINGSTAT'] == 1) :
																										?>
																											<a href="<?= base_url("administration/rcppostedview/" . $grheader['RCPUNIQ']) ?>" class="btn btn-default btn-sm" title="GR View" target="_blank">
																												<i class="fa fa-file"></i>
																											</a>
																										<?php
																										endif;
																										?>
																										<?php if ($grheader['RCPPOSTINGSTAT'] == 0) :
																										?>

																											<a href="<?= base_url("goodreceipt/posting/" . $grheader['RCPUNIQ'] . '/' . $grheader['CSRUNIQ']) ?>" class="btn btn-social btn-flat bg-blue btn-sm">
																												<i class="fa fa-check-square-o"></i> Posting G/R
																											</a>


																											<a href="" data-href="<?= base_url("goodreceipt/delete/" . $grheader['RCPUNIQ']) ?>" class="btn bg-red btn-flat btn-sm" title="Delete Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																										<?php
																										endif;
																										?>


																										<?php if ($grheader['RCPPOSTINGSTAT'] == 1 and $grheader['RCPOFFLINESTAT'] == 1) :
																										?>
																											<a href="<?= base_url("goodreceipt/sendnotif/" . $grheader['RCPUNIQ']) ?>" class="btn bg-blue btn-social btn-flat btn-sm 
																											" title="Sending Notif Manually">
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

																	<td style="vertical-align: top;" colspan="5" nowrap>
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
																					foreach ($po_l_data as $items) :
																						if ($rcp_list['POUNIQ'] == $items['POUNIQ']) :

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
																	<td style="vertical-align: top;">
																		<div class="table-responsive">
																			<table class="table table-bordered dataTable table-hover nowrap">
																				<thead class="bg-gray disabled color-palette">
																					<tr>
																						<th class="padat">Status</th>
																						<th>Last <br>G/R Date</th>
																						<th>G/R Qty</th>
																						<th>G/R Status</th>
																					</tr>
																				</thead>
																				<tbody>
																					<?php

																					foreach ($po_l_data as $items) :
																						if ($rcp_list['POUNIQ'] == $items['POUNIQ']) :
																							if (empty($items['L_RECPDATE'])) {
																								$l_rcpdate = '';
																							} else {
																								$l_rcpdate = substr($items['L_RECPDATE'], 4, 2) . "/" . substr($items['L_RECPDATE'], 6, 2) . "/" . substr($items['L_RECPDATE'], 0, 4);
																							}
																					?>
																							<tr>

																								<td class="text-center" style="width: 10%;">
																									<?php

																									$postingstat = $rcp_list['RCPPOSTINGSTAT'] . $rcp_list['RCPOFFLINESTAT'];
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
																									?>
																								</td>
																								<td><?= $l_rcpdate
																									?></td>

																								<td><?= number_format($items['S_QTYRCP'], 0, ",", ".")
																									?></td>
																								<td>
																									<?php
																									if ($items['S_QTYRCP'] == 0) {
																										echo "<span class='label label-danger'>Pending</span>";
																									} else if ($items['S_QTYRCP'] > 0 and $items['QTY'] != $items['S_QTYRCP']) {
																										echo "<span class='label label-warning'>Partial</span>";
																									} else if ($items['S_QTYRCP'] > 0 and $items['QTY'] == $items['S_QTYRCP']) {
																										echo "<span class='label label-success'>Completed</span>";
																									}


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
										<div class="col-sm-12">&nbsp;
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

<?php echo view('settings/confirm_delete') ?>