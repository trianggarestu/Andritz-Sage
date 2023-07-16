<style>
	.input-sm {
		padding: 4px 4px;
	}
</style>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Delivery Orders</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Delivery Orders</li>
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
						<a href="<?= base_url() ?>deliveryorders/refresh" title="Refresh Data" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i> Refresh Data</a>

					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<form id="mainform" name="mainform" action="" method="post">
										<div class="row">
											<div class="col-sm-9">
												<p><i class="fa fa-info-circle"></i><code> { only viewed <strong>Good Receipts</strong> data that waiting to be processed by the Delivery Team }</code></p>

											</div>
											<div class="col-sm-3" style="vertical-align: text-bottom;">
												<div class="box-tools">
													<div class="input-group input-group-sm pull-right">
														<input name="cari" id="cari" class="form-control" placeholder="Search..." type="text" value="<?= $keyword ?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= base_url('deliveryorders/search') ?>');$('#'+'mainform').submit();}">
														<div class="input-group-btn">
															<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= base_url('deliveryorders/search') ?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
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
																<th style="vertical-align: top;">Status</th>

																<th style="background-color: white;"></th>
																<th style="vertical-align: top;">Action</th>


															</tr>
														</thead>
														<tbody>

															<?php
															$no = 1;
															foreach ($delivery_data as $shi_list) {
																$crmreq_date = substr($shi_list['CRMREQDATE'], 4, 2) . "/" . substr($shi_list['CRMREQDATE'], 6, 2) . "/" . substr($shi_list['CRMREQDATE'], 0, 4);
																$pocust_date = substr($shi_list['PODATECUST'], 4, 2) . "/" . substr($shi_list['PODATECUST'], 6, 2) . "/" . substr($shi_list['PODATECUST'], 0, 4);



															?>

																<tr>
																	<td style="vertical-align: top;"><?= $no++; ?></td>
																	<td style="vertical-align: top;" nowrap>
																		<strong><a href="<?= base_url("administration/csrpostedview/" . $shi_list['CRMCSRUNIQ']) ?>" title="Click here for detail" target="_blank"><?= $shi_list['CSRCONTRACT'] ?></a></strong>
																		<?= " / " . $shi_list['CSRPROJECT'] . " / " . $shi_list['CRMNO']; ?><br>
																		<strong><?= $shi_list['CTDESC']; ?></strong><br>
																		<strong><?= $shi_list['PONUMBERCUST'] . ' - ' . $pocust_date; ?></strong><br>
																		<small>(<?= $shi_list['NAMECUST']; ?>)</small><br>

																	</td>

																	<td nowrap><?= $crmreq_date;
																				?></td>
																	<td nowrap>
																		<?php

																		$postingstat = $shi_list['POSTINGSTAT'];
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
																		?>
																	</td>



																	<td style="background-color: white;"></td>
																	<td style="vertical-align: top;" nowrap>
																		<div class="btn-group">
																			<button type="button" class="btn btn-social btn-flat btn-info btn-sm" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Choose Button</button>
																			<ul class="dropdown-menu" role="menu">
																				<?php if (($shi_list['SHIPOSTINGSTAT'] == 0) or (empty($shi_list['SHIPOSTINGSTAT'])) or ($shi_list['RCPQTY'] <> $shi_list['SHIQTY'])) :
																				?>
																					<li>
																						<a href="<?= base_url("deliveryorders/add/" . $shi_list['CRMCSRUNIQ'] . '/1/0') ?>" class="btn btn-social btn-flat btn-block btn-sm"><i class="fa fa-edit"></i> Input Delivery Orders & Posting</a>
																					</li>
																					<li>
																						<a href="<?= base_url("deliveryorders/add/" . $shi_list['CRMCSRUNIQ'] . '/0/0') ?>" class="btn btn-social btn-flat btn-block btn-sm"><i class="fa fa-edit"></i> Input Delivery Orders & Save</a>
																					</li>
																				<?php endif;
																				?>

																			</ul>
																		</div>

																		<?php if (is_array($shilist_data)) { ?>
																			<div class="table-responsive">
																				<table class="table table-bordered dataTable table-hover nowrap">
																					<thead class="bg-gray disabled">
																						<tr>
																							<td>Status</td>
																							<td colspan="2">D/N Info.</td>
																						</tr>
																					</thead>
																					<tbody>
																						<?php
																						$no_l = 0;

																						foreach ($shilist_data as $shiheader) :
																							if ($shi_list['CRMCSRUNIQ'] == $shiheader['CSRUNIQ']) :
																								$shi_date = substr($shiheader['SHIDATE'], 4, 2) . "/" . substr($shiheader['SHIDATE'], 6, 2) . "/" . substr($shiheader['SHIDATE'], 0, 4);

																						?>
																								<tr>
																									<td><?php

																										$postingstat = $shiheader['SHIPOSTINGSTAT'];
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
																									<td style="width: 32%;"><?= trim($shiheader['DOCNUMBER']) ?> <small>(<?= $shi_date ?>)</small></td>

																									<td>
																										<?php if ($shiheader['SHIPOSTINGSTAT'] == 1 and $shiheader['SHIATTACHED'] == 0) :
																										?>
																											<a href="<?= base_url("deliveryorders/shipmentopenview/" . $shiheader['SHIUNIQ']) ?>" class="btn btn-social btn-flat bg-blue btn-sm"><i class="fa fa-upload"></i> Upload e-DN & Send Notif</a>
																										<?php
																										endif;
																										?>
																										<?php if ($shiheader['SHIOFFLINESTAT'] == 1 and $shiheader['SHIATTACHED'] == 1) :
																										?>
																											<a href="<?= base_url("deliveryorders/sendnotif/" . $shiheader['SHIUNIQ']) ?>" class="btn bg-blue btn-social btn-flat btn-sm"><i class="fa fa-send-o"></i>Send Notif</a>
																										<?php
																										endif;
																										?>
																										<?php if ($shiheader['SHIPOSTINGSTAT'] == 1) :
																										?>
																											<a href="<?= base_url("administration/shipostedview/" . $shiheader['SHIUNIQ']) ?>" class="btn btn-default btn-sm" title="DN View" target="_blank">
																												<i class="fa fa-file"></i>
																											</a>

																										<?php
																										endif;
																										?>
																										<?php if ($shiheader['SHIPOSTINGSTAT'] == 0) :
																										?>

																											<a href="<?= base_url("deliveryorders/posting/" . $shiheader['SHIUNIQ'] . '/' . $shiheader['CSRUNIQ']) ?>" class="btn btn-social btn-flat bg-blue btn-sm">
																												<i class="fa fa-check-square-o"></i> Posting D/N
																											</a>


																											<a href="" data-href="<?= base_url("deliveryorders/delete/" . $shiheader['SHIUNIQ']) ?>" class="btn bg-red btn-flat btn-sm" title="Delete Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																										<?php
																										endif;
																										?>


																										<?php if ($shiheader['SHIPOSTINGSTAT'] == 1 and $shiheader['SHIOFFLINESTAT'] == 1 and !empty($shiheader['EDNFILENAME'])) :
																										?>
																											<a href="<?= base_url("goodreceipt/sendnotif/" . $shiheader['SHIUNIQ']) ?>" class="btn bg-blue btn-social btn-flat btn-sm 
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
																	<td style="vertical-align: top;" colspan="4" nowrap>
																		<div class="table-responsive">
																			<table class="table table-bordered dataTable table-hover nowrap">
																				<thead class="bg-gray disabled color-palette">
																					<tr>

																						<th class="padat">No</th>

																						<th>Type</th>
																						<th>Inventory <br>No.</th>
																						<th>Material <br>No.</th>
																						<th>Item Description</th>
																						<th>CSR Qty.</th>
																						<th>Uom</th>
																						<th>P/O Number</th>
																						<th>G/R No.</th>
																						<th>G/R Qty.<br>(Sum)</th>
																						<th>G/R Status</th>




																					</tr>
																				</thead>
																				<tbody>
																					<?php
																					$no_l = 0;
																					foreach ($shi_l_data as $items) :
																						if ($shi_list['CRMCSRUNIQ'] == $items['CSRUNIQ']) :

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
																								<td nowrap style="width: 10%;"><?= $items['PONUMBER']
																																?></td>
																								<td nowrap style="text-align: center;">
																									<a href="<?= base_url('deliveryorders/view_gr_number/' . $items['POUNIQ'] . '/' . $items['ITEMNO']) ?>" title="View G/R Number" data-toggle="modal" data-target="#modalBox">
																										View
																									</a>
																								</td>
																								<td nowrap style="width: 10%;"><?= number_format($items['RCPQTY'], 0, ",", ".")
																																?></td>
																								<td nowrap style="width: 10%;"><?php if ($items['RCPSTATUS'] == 'COMPLETED') : ?>
																										<span class='label label-success'>
																										<?php endif; ?>
																										<?php if ($items['RCPSTATUS'] == 'PARTIAL') : ?>
																											<span class='label label-warning'>
																											<?php endif; ?>
																											<?php if ($items['RCPSTATUS'] == 'WAITING') : ?>
																												<span class='label label-danger'>
																												<?php endif; ?>
																												<?= $items['RCPSTATUS']
																												?>
																												</span>
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
																	<td style="background-color: white;"></td>
																	<td colspan="3">
																		<div class="table-responsive">
																			<table class="table table-bordered dataTable table-hover nowrap">
																				<thead class="bg-gray disabled color-palette">
																					<tr>
																						<th>Last <br>D/N Date </th>
																						<th>D/N Qty <br>(Sum)</th>
																						<th>D/N Qty <br>Outstanding(Sum)</th>

																					</tr>
																				</thead>
																				<tbody>
																					<?php
																					foreach ($shi_l_data as $items) :
																						if ($shi_list['CRMCSRUNIQ'] == $items['CSRUNIQ']) :
																							if (empty($items['L_SHIDATE'])) {
																								$l_shidate = '';
																							} else {
																								$l_shidate = substr($items['L_SHIDATE'], 4, 2) . "/" . substr($items['L_SHIDATE'], 6, 2) . "/" . substr($items['L_SHIDATE'], 0, 4);
																							}
																					?>
																							<tr>
																								<td>
																									<?= $l_shidate; ?>
																								</td>
																								<td>
																									<?= number_format($items['SHIQTY'], 0, ",", ".") ?>
																								</td>
																								<td>
																									<?= number_format(($items['RCPQTY'] - $items['SHIQTY']), 0, ",", ".") ?>
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