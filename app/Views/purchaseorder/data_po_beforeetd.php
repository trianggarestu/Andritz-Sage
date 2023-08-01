<style>
	.input-sm {
		padding: 4px 4px;
	}
</style>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Listing PO on 2 Weeks Before ETD</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Listing PO on 2 Weeks Before ETD</li>
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
						<a href="<?= base_url() ?>pobeforeetdnotice/refresh" title="Refresh Data" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i> Refresh Data</a>

					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<form id="mainform" name="mainform" action="" method="post">
										<div class="row">
											<div class="col-sm-6">
												<code> { only viewed <strong>Purchase Orders</strong> data that waiting to be processed by the Logistics }</code>
											</div>
											<div class="col-sm-6">
												<div class="box-tools">
													<div class="input-group input-group-sm pull-right">
														<input name="cari" id="cari" class="form-control" placeholder="Search..." type="text" value="<?= $keyword ?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= base_url('pobeforeetdnotice/search') ?>');$('#'+'mainform').submit();}">
														<div class="input-group-btn">
															<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= base_url('pobeforeetdnotice/search') ?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
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
																<th style="vertical-align: top;">P/O Number</th>
																<th style="vertical-align: top;">P/O Date</th>
																<th style="vertical-align: top;" nowrap>2 Weeks <br>before ETD</th>
																<th style="vertical-align: top;">Status</th>
																<th style="vertical-align: top;">Action</th>
																<th style="background-color: white;"></th>
																<th style="vertical-align: top;">Contract/Project/CRM<br>Contract Desc.<br>P/O Customer<br>Customer</th>
																<th style="vertical-align: top;">Req. Date</th>
																<th style="background-color: white;"></th>
																<th style="vertical-align: top;">Requisition Info</th>


															</tr>
														</thead>
														<tbody>

															<?php
															$no = 1;
															foreach ($purchaseOrder_data as $ot_list) :
																$crmreq_date = substr($ot_list['CRMREQDATE'], 4, 2) . "/" . substr($ot_list['CRMREQDATE'], 6, 2) . "/" . substr($ot_list['CRMREQDATE'], 0, 4);
																$crmpodate = substr($ot_list['PODATECUST'], 4, 2) . "/" . substr($ot_list['PODATECUST'], 6, 2) . "/" .  substr($ot_list['PODATECUST'], 0, 4);
																$rqn_date = substr($ot_list['RQNDATE'], 4, 2) . "/" . substr($ot_list['RQNDATE'], 6, 2) . "/" . substr($ot_list['RQNDATE'], 0, 4);

																$povendor_date = substr($ot_list['PODATE'], 4, 2) . "/" . substr($ot_list['PODATE'], 6, 2) . "/" . substr($ot_list['PODATE'], 0, 4);
																$etd_date = substr($ot_list['ETDDATE'], 4, 2) . "/" . substr($ot_list['ETDDATE'], 6, 2) . "/" . substr($ot_list['ETDDATE'], 0, 4);
																if (empty($ot_list['CARGOREADINESSDATE'])) {
																	$cargoreadiness_date = '';
																} else {
																	$cargoreadiness_date = substr($ot_list['CARGOREADINESSDATE'], 4, 2) . "/" . substr($ot_list['CARGOREADINESSDATE'], 6, 2) . "/" . substr($ot_list['CARGOREADINESSDATE'], 0, 4);
																}

															?>

																<tr>
																	<td style="vertical-align: top;"><?= $no++; ?></td>
																	<td style="vertical-align: top;">
																		<strong>
																			<a href="<?= base_url("administration/popostedview/" . $ot_list['POUNIQ']) ?>" title="Click here for detail" target="_blank"><?= $ot_list['PONUMBER'] ?></a>
																		</strong>
																	</td>
																	<td style="vertical-align: top;"><?= $povendor_date ?></td>
																	<td style="vertical-align: top;"><?= $ot_list['diff'] ?></td>
																	<td>

																		<table class="table table-bordered table-striped dataTable">

																			<tr>
																				<td><small>Status</small></td>
																				<td><small>:</small></td>
																				<td><small><?php $postingstat = $ot_list['POSTINGSTAT'] . $ot_list['OFFLINESTAT'];
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
																							} ?></small></td>
																			</tr>

																			<tr>
																				<td><small>ETD Date</small></td>
																				<td><small>:</small></td>
																				<td><small><?= $etd_date ?></small></td>
																			</tr>
																			<tr>
																				<td><small>Cargo Readiness</small></td>
																				<td><small>:</small></td>
																				<td><small><?= $cargoreadiness_date ?></small></td>
																			</tr>
																			<tr>
																				<td><small>Origin Country</small></td>
																				<td><small>:</small></td>
																				<td><strong><small><?= $ot_list['ORIGINCOUNTRY']; ?></small></strong></td>
																			</tr>
																			<tr>
																				<td><small>P/O Remarks</small></td>
																				<td><small>:</small></td>
																				<td><strong><small><?= $ot_list['POREMARKS']; ?></small></strong></td>
																			</tr>
																		</table>
																	</td>
																	<td style="vertical-align: top;">

																		<div class="btn-group">
																			<button type="button" class="btn btn-social btn-flat btn-info btn-sm" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Choose Button</button>
																			<ul class="dropdown-menu" role="menu">
																				<?php if ($ot_list['POSTINGSTAT'] == 1 and empty($ot_list['CARGOREADINESSDATE'])) :
																				?>
																					<li>
																						<a href="<?= base_url("pobeforeetdnotice/update_cargoreadiness/" . $ot_list['POUNIQ'] . '/1') ?>" class="btn btn-social btn-flat btn-block btn-sm" title="Update Cargo Readiness" data-remote="false" data-toggle="modal" data-target="#modalBox">
																							<i class="fa fa-check-square-o"></i>Update Cargo Readiness & Posting</a>
																					</li>
																				<?php
																				endif;
																				?>
																				<?php if ($ot_list['POSTINGSTAT'] == 1 and $ot_list['OFFLINESTAT'] == 1 and !empty($ot_list['CARGOREADINESSDATE'])) :
																				?>
																					<li>
																						<a href="<?= base_url("pobeforeetdnotice/sendnotif/" . $ot_list['POUNIQ']) ?>" class="btn btn-social btn-flat btn-block btn-sm" title="Sending Notification Manually">
																							<i class="fa fa-send-o"></i>Send Notif Manually</a>
																					</li>
																				<?php
																				endif;
																				?>
																			</ul>
																		</div>

																	</td>
																	<td style="background-color: white;vertical-align: top;"></td>
																	<td style="vertical-align: top;" nowrap><strong><a href="<?= base_url("administration/csrpostedview/" . $ot_list['CSRUNIQ']) ?>" title="Click here for detail" target="_blank">
																				<?= $ot_list['CONTRACT'] ?></a></strong>
																		<?= " / " . $ot_list['PROJECT'] . " / " . $ot_list['CRMNO']; ?><br>
																		<strong><?= $ot_list['CTDESC']; ?></strong><br>
																		<strong><?= $ot_list['PONUMBERCUST'] . ' - ' . $crmpodate; ?></strong><br>
																		<small>(<?= $ot_list['NAMECUST']; ?>)</small><br>


																	</td>

																	<td style="vertical-align: top;" nowrap><?= $crmreq_date;
																											?></td>

																	<td style="background-color: white;"></td>
																	<td style="vertical-align: top;">

																		<div class="table-responsive">
																			<table class="table table-bordered dataTable table-hover nowrap">
																				<tbody>
																					<tr>
																						<td>Requisition No. </td>
																						<td>: </td>
																						<td><strong><?= $ot_list['RQNNUMBER']; ?></strong></td>
																					</tr>
																					<tr>
																						<td>Date </td>
																						<td>: </td>
																						<td><strong><?= $rqn_date; ?></strong></td>
																					</tr>
																					<tr>
																						<td>Status </td>
																						<td>: </td>
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
																					</tr>
																				</tbody>
																			</table>
																		</div>
																	</td>






																</tr>

															<?php endforeach; ?>
														</tbody>
													</table>
													<div><?php //= $pager->links(); 
															?> </div>
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