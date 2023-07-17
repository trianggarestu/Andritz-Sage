<style>
	.input-sm {
		padding: 4px 4px;
	}
</style>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Confirm A/R Invoice</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Confirm A/R Invoice</li>
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
						<a href="<?= base_url() ?>fillrrstatus/refresh" title="Refresh Data" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i> Refresh Data</a>

					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<form id="mainform" name="mainform" action="" method="post">
										<div class="row">
											<div class="col-sm-9">
												<p><i class="fa fa-info-circle"></i><code> { only viewed <strong>A/R Invoice</strong> data that waiting to be processed by the Finance }</code></p>

											</div>
											<div class="col-sm-3" style="vertical-align: text-bottom;">
												<div class="box-tools">
													<div class="input-group input-group-sm pull-right">
														<input name="cari" id="cari" class="form-control" placeholder="Search..." type="text" value="<?= $keyword ?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= base_url('fillrrstatus/search') ?>');$('#'+'mainform').submit();}">
														<div class="input-group-btn">
															<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= base_url('fillrrstatus/search') ?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
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
																<th style="vertical-align: top;">Est. Cost</th>
																<th style="vertical-align: top;">Act. Cost</th>
																<th style="background-color: white;"></th>
																<th style="vertical-align: top;">Invoice. Number</th>
																<th style="vertical-align: top;">Status</th>
																<th style="background-color: white;"></th>
																<th style="vertical-align: top;">Action</th>
																<th style="vertical-align: top;">Status</th>
																<th style="vertical-align: top;">RR Status</th>
															</tr>
														</thead>
														<tbody>

															<?php
															$no = 1;
															foreach ($finance_data as $fin_list) {
																$crmreq_date = substr($fin_list['CRMREQDATE'], 4, 2) . "/" . substr($fin_list['CRMREQDATE'], 6, 2) . "/" . substr($fin_list['CRMREQDATE'], 0, 4);
																$pocust_date = substr($fin_list['PODATECUST'], 4, 2) . "/" . substr($fin_list['PODATECUST'], 6, 2) . "/" . substr($fin_list['PODATECUST'], 0, 4);
																if (null == $fin_list['DATEINVC']) {
																	$inv_date = '';
																} else {
																	$inv_date = substr($fin_list['DATEINVC'], 4, 2) . "/" . substr($fin_list['DATEINVC'], 6, 2) . "/" . substr($fin_list['DATEINVC'], 0, 4);
																}



															?>

																<tr>
																	<td style="vertical-align: top;"><?= $no++; ?></td>
																	<td style="vertical-align: top;" nowrap>
																		<strong><a href="<?= base_url("administration/csrpostedview/" . $fin_list['CSRUNIQ']) ?>" title="Click here for detail" target="_blank"><?= $fin_list['CSRCONTRACT'] ?></a></strong>
																		<?= " / " . $fin_list['CSRPROJECT'] . " / " . $fin_list['CRMNO']; ?><br>
																		<strong><?= $fin_list['CTDESC']; ?></strong><br>
																		<strong><?= $fin_list['PONUMBERCUST'] . ' - ' . $pocust_date; ?></strong><br>
																		<small>(<?= $fin_list['NAMECUST']; ?>)</small><br>
																	</td>

																	<td style="vertical-align: top;" nowrap><?= $crmreq_date;
																											?></td>
																	<td style="vertical-align: top;" nowrap><?= number_format($fin_list['CURMATECHM'], 0, ",", ".");
																											?></td>
																	<td style="vertical-align: top;" nowrap><?= number_format($fin_list['ACTMATECHM'], 0, ",", ".");
																											?></td>
																	<td style="background-color: white;"></td>
																	<td style="vertical-align: top;" nowrap>
																		<strong><a href="#"><?= $fin_list['IDINVC'] ?></a></strong><br>
																		<table class="table table-bordered table-striped dataTable">
																			<thead class="bg-gray disabled">
																				<tr>
																					<th colspan="3"><small>Invoice Info</small>
																					</th>
																				</tr>
																			</thead>
																			<tr>
																				<td><small>Invoice Number</small></td>
																				<td><small>:</small></td>
																				<td><small><?= $fin_list['IDINVC'] ?></small></td>
																			</tr>
																			<tr>
																				<td><small>Invoice Date</small></td>
																				<td><small>:</small></td>
																				<td><small><?= $inv_date ?></small></td>
																			</tr>
																			<tr>
																				<td><small>Status</small></td>
																				<td><small>:</small></td>
																				<td><strong><small><?php switch ($fin_list['FINSTATUS']) {
																										case "1":
																											echo "Partial";
																											break;
																										case "2":
																											echo "Completed";
																											break;
																										default:
																											echo "";
																									}
																									?> </small></strong></td>
																			</tr>

																		</table>
																	</td>
																	<td style="vertical-align: top;"><?php $postingstat = $fin_list['POSTINGSTAT'];
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
																												echo "";
																										} ?></td>
																	<td style="background-color: white;"></td>
																	<td style="vertical-align: top;" nowrap>
																		<div class="btn-group">
																			<button type="button" class="btn btn-social btn-flat btn-info btn-sm" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Choose Button</button>
																			<ul class="dropdown-menu" role="menu">
																				<?php if (($fin_list['RRPOSTINGSTAT'] == 0)) :
																				?>
																					<li>
																						<a href="<?= base_url("fillrrstatus/update/" . $fin_list['FINUNIQ'] . '/1') ?>" class="btn btn-social btn-flat btn-block btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox"><i class="fa fa-check-square-o"></i> Fill RR Status & Post</a>
																					</li>

																					<li>
																						<a href="<?= base_url("fillrrstatus/update/" . $fin_list['FINUNIQ'] . '/0') ?>" class="btn btn-social btn-flat btn-block btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox"><i class="fa fa-edit"></i> Fill RR Status & Save</a>
																					</li>
																				<?php endif;
																				?>



																			</ul>
																		</div>

																	</td>
																	<td style="vertical-align: top;" nowrap>
																		<?php $rrpostingstat = $fin_list['RRPOSTINGSTAT'];
																		switch ($rrpostingstat) {
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
																				echo "";
																		} ?>
																	</td>
																	<td style="vertical-align: top;" nowrap>
																		<?php $rrstatus = $fin_list['RRSTATUS'];
																		switch ($rrstatus) {
																			case "1":
																				echo "OPEN";
																				break;
																			case "2":
																				echo "DONE";
																				break;
																				echo "";
																		} ?>
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