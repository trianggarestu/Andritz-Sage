<style>
	.input-sm {
		padding: 4px 4px;
	}
</style>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Waiting List Sales Order to process by Requester</h1>
		<ol class="breadcrumb">
			<li><a href="<?= base_url('administration'); ?>"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Waiting List Sales Order</li>
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
						<a href="<?= base_url() ?>requisition/refresh" title="Refresh Data" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i> Refresh Data</a>

					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<form id="mainform" name="mainform" action="" method="post">
										<div class="row">
											<div class="col-sm-6">
												<code> { only viewed <strong>Sales Order</strong> data that waiting to be processed by the requester }</code>

											</div>
											<div class="col-sm-6">
												<div class="box-tools">
													<div class="input-group input-group-sm pull-right">
														<input name="cari" id="cari" class="form-control" placeholder="Search..." type="text" value="<?= $keyword ?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= base_url('requisition/search') ?>');$('#'+'mainform').submit();}">
														<div class="input-group-btn">
															<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= base_url('requisition/search') ?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
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
																<th>No.</th>
																<th nowrap>Contract/Project/CRM<br>Contract Desc.<br>Customer</th>
																<th nowrap>PO Customer - P/O Date<br>Customer Name<br>Customer Email</th>
																<th style="vertical-align: top;">P/O Cust.<br>Date</th>
																<th style="vertical-align: top;">CRM Req.<br> Date</th>
																<th style="vertical-align: top;">S/O Status</th>
																<th style="background-color: white;"></th>
																<th style="vertical-align: top;">Action</th>
																<th>Requisition No.</th>
																<th>Requisition Date.</th>
																<th>Status</th>
															</tr>
														</thead>
														<tbody>

															<?php
															$no = 1;
															foreach ($requisition_data as $ot_list) {
																$crmpodate = substr($ot_list['PODATECUST'], 4, 2) . "/" . substr($ot_list['PODATECUST'], 6, 2) . "/" .  substr($ot_list['PODATECUST'], 0, 4);
																$crmreqdate = substr($ot_list['CRMREQDATE'], 4, 2) . '/' . substr($ot_list['CRMREQDATE'], 6, 2) . '/' . substr($ot_list['CRMREQDATE'], 0, 4);
																if (empty($ot_list['RQNDATE'])) {
																	$rqndate = '';
																} else {
																	$rqndate = substr($ot_list['RQNDATE'], 4, 2) . '/' . substr($ot_list['RQNDATE'], 6, 2) . '/' . substr($ot_list['RQNDATE'], 0, 4);
																}
															?>

																<tr>
																	<td><?= $no++; ?></td>
																	<td nowrap><strong><a href="#"><?= $ot_list['CONTRACT'] ?></a></strong>
																		<?= " / " . $ot_list['PROJECT'] . " / " . $ot_list['CRMNO']; ?><br>
																		<strong><?= $ot_list['CTDESC']; ?></strong><br>
																		<small>(<?= $ot_list['NAMECUST']; ?>)</small><br>


																	</td>
																	<td style="vertical-align: top;">
																		<strong><?= $ot_list['PONUMBERCUST']; ?></strong><br>
																		<?= $ot_list['ORDERDESC']; ?><br>
																		CRM Remarks : <?= $ot_list['CRMREMARKS']; ?>

																	</td>
																	<td style="vertical-align: top;" nowrap><?= $crmpodate; ?></td>
																	<td style="vertical-align: top;" nowrap><?= $crmreqdate; ?></td>
																	<td nowrap>
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
																	<td style="background-color: white;"></td>
																	<td nowrap>
																		<div class="btn-group">
																			<button type="button" class="btn btn-social btn-flat btn-info btn-sm" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Choose Button</button>
																			<ul class="dropdown-menu" role="menu">
																				<?php if ($ot_list['RQNPOSTINGSTAT'] == 0) :
																				?>
																					<li>
																						<a href="<?= base_url("requisition/update/" . $ot_list['CSRUNIQ'] . '/1') ?>" class="btn btn-social btn-flat btn-block btn-sm"><i class="fa fa-check-square-o"></i> Select Requisition & Post</a>
																					</li>

																					<li>
																						<a href="<?= base_url("requisition/update/" . $ot_list['CSRUNIQ'] . '/0') ?>" class="btn btn-social btn-flat btn-block btn-sm"><i class="fa fa-edit"></i> Select Requisition & Save</a>
																					</li>
																				<?php endif;
																				?>

																				<?php if ($ot_list['RQNPOSTINGSTAT'] == 1 and $ot_list['RQNOFFLINESTAT'] == 1) :
																				?>
																					<li>
																						<a href="<?= base_url("requisition/sendnotif/" . $ot_list['RQNUNIQ']) ?>" class="btn btn-social btn-flat btn-block btn-sm"><i class="fa fa-send-o"></i> Sending Notif Manually</a>
																					</li>
																				<?php endif;
																				?>

																			</ul>
																		</div>

																	</td>
																	<td><strong><?= $ot_list['RQNNUMBER']; ?></strong></td>
																	<td><?= $rqndate ?></td>
																	<td>
																		<?php $postingstat = $ot_list['RQNPOSTINGSTAT'] . $ot_list['RQNOFFLINESTAT'];
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
																		} ?>
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
																						<th>Inventory No.</th>
																						<th>Material No.</th>
																						<th>Item Desc.</th>
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