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
						<a href="<?= base_url() ?>fillinvoice/refresh" title="Refresh Data" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i> Refresh Data</a>

					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<form id="mainform" name="mainform" action="" method="post">
										<div class="row">
											<div class="col-sm-9">
												<p><i class="fa fa-info-circle"></i><code> { only viewed <strong>Delivery Note</strong> data that waiting to be processed by the Finance }</code></p>

											</div>
											<div class="col-sm-3" style="vertical-align: text-bottom;">
												<div class="box-tools">
													<div class="input-group input-group-sm pull-right">
														<input name="cari" id="cari" class="form-control" placeholder="Search..." type="text" value="<?= $keyword ?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= base_url('fillinvoice/search') ?>');$('#'+'mainform').submit();}">
														<div class="input-group-btn">
															<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= base_url('fillinvoice/search') ?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
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
																<td style="vertical-align: top;">No.</td>
																<td style="vertical-align: top;">Contract/Project/CRM<br>Contract Desc.<br>Customer</td>
																<td style="vertical-align: top;">Req. Date</td>
																<td style="background-color: white;"></td>

																<td style="vertical-align: top;">Action</td>

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
																		<strong><a href="<?= base_url("administration/csrpostedview/" . $shi_list['CSRUNIQ']) ?>" title="Click here for detail" target="_blank"><?= $shi_list['CSRCONTRACT'] ?></a></strong>
																		<?= " / " . $shi_list['CSRPROJECT'] . " / " . $shi_list['CRMNO']; ?><br>
																		<strong><?= $shi_list['CTDESC']; ?></strong><br>
																		<strong><?= $shi_list['PONUMBERCUST'] . ' - ' . $pocust_date; ?></strong><br>
																		<small>(<?= $shi_list['NAMECUST']; ?>)</small><br>

																	</td>

																	<td style="vertical-align: top;" nowrap><?= $crmreq_date;
																											?></td>



																	<td style="background-color: white;"></td>
																	<td style="vertical-align: top;" nowrap>
																		<div class="btn-group">
																			<button type="button" class="btn btn-social btn-flat btn-info btn-sm" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Choose Button</button>
																			<ul class="dropdown-menu" role="menu">
																				<?php //if (($shi_list['SHIPOSTINGSTAT'] == 1) and $shi_list['DNPOSTINGSTAT'] == 1 and (empty($shi_list['POSTINGSTAT']) or ($shi_list['POSTINGSTAT'] == 0))) :
																				?>
																				<li>
																					<a href="<?= base_url("fillinvoice/update/" . $shi_list['CSRUNIQ'] . '/1') ?>" class="btn btn-social btn-flat btn-block btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox"><i class="fa fa-check-square-o"></i> Fill Invoice & Post</a>
																				</li>

																				<li>
																					<a href="<?= base_url("fillinvoice/update/" . $shi_list['CSRUNIQ'] . '/0') ?>" class="btn btn-social btn-flat btn-block btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox"><i class="fa fa-edit"></i> Fill Invoice & Save</a>
																				</li>
																				<?php //endif;
																				?>

																				<?php //if ($shi_list['POSTINGSTAT'] == 1) :
																				?>
																				<li>
																					<a href="<?= base_url("fillinvoice/viewinvoiceposted/") ?>" class="btn btn-social btn-flat btn-block btn-sm"><i class="fa fa-file-o"></i> View A/R Invoice</a>
																				</li>
																				<?php //endif;
																				?>

																			</ul>
																		</div>

																		<?php if (is_array($finlist_data)) { ?>
																			<div class="table-responsive">
																				<table class="table table-bordered dataTable table-hover nowrap">
																					<thead class="bg-gray disabled">
																						<tr>
																							<td>Status</td>
																							<td colspan="2">A/R Info.</td>
																						</tr>
																					</thead>
																					<tbody>
																						<?php
																						$no_l = 0;

																						foreach ($finlist_data as $finheader) :
																							if ($shi_list['CSRUNIQ'] == $finheader['CSRUNIQ']) :
																								//$shi_date = substr($shiheader['SHIDATE'], 4, 2) . "/" . substr($shiheader['SHIDATE'], 6, 2) . "/" . substr($shiheader['SHIDATE'], 0, 4);

																						?>
																								<tr>
																									<td><?php

																										$postingstat = $finheader['POSTINGSTAT'];
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
																									<td style="width: 32%;"><?= trim($finheader['IDINVC']) ?> <small>()</small></td>

																									<td>

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
																	<td style="vertical-align: top;" colspan="3" nowrap>
																		<?php if (is_array($shilist_data)) : ?>
																			<div class="table-responsive">
																				<table class="table table-bordered dataTable table-hover nowrap">
																					<thead class="bg-gray disabled color-palette">
																						<tr>

																							<th class="padat">No</th>
																							<th>Doc. Number</th>
																							<th>Shi. Number</th>
																							<th>D/N Date</th>
																							<th>Customer<br>Received Date</th>
																							<th>D/N Status</th>
																						</tr>
																					</thead>
																					<tbody>
																						<?php
																						$no_l = 0;
																						foreach ($shilist_data as $shi_data) :
																							$shi_date = substr($shi_data['SHIDATE'], 4, 2) . "/" . substr($shi_data['SHIDATE'], 6, 2) . "/" . substr($shi_data['SHIDATE'], 0, 4);
																							$custrcp_date = substr($shi_data['CUSTRCPDATE'], 4, 2) . "/" . substr($shi_data['CUSTRCPDATE'], 6, 2) . "/" . substr($shi_data['CUSTRCPDATE'], 0, 4);
																							if ($shi_list['CSRUNIQ'] == $shi_data['CSRUNIQ']) :

																						?>
																								<tr>

																									<td class="text-center" style="width: 5%;"><?= ++$no_l ?></td>
																									<td style="width: 10%;">
																										<strong>
																											<a href="<?= base_url("administration/shipostedview/" . $shi_data['SHIUNIQ']) ?>" title="D/N View" target="_blank">
																												<?= $shi_data['DOCNUMBER']
																												?>
																											</a>
																										</strong>
																									</td>

																									<td style="width: 12%;"><?= $shi_data['SHINUMBER']
																															?></td>
																									<td style="width: 12%;"><?= $shi_date
																															?></td>
																									<td nowrap><?= $custrcp_date
																												?></td>

																									<td nowrap style="width: 10%;">
																										<?php $dnstatus = $shi_data['DNSTATUS'];
																										switch ($dnstatus) {
																											case "0":
																												echo "";
																												break;
																											case "1":
																												echo "RECEIVED";
																												break;

																											default:
																												echo "";
																										} ?>
																									</td>



																								</tr>

																						<?php
																							endif;
																						endforeach;
																						?>
																					</tbody>
																				</table>
																			</div>
																		<?php endif; ?>

																	</td>
																	<td style="background-color: white;"></td>
																	<td style="vertical-align: top;">
																		<div class="table-responsive">
																			<table class="table table-bordered dataTable table-hover nowrap">
																				<thead class="bg-gray disabled color-palette">
																					<tr>
																						<th>X </th>
																						<th>X</th>
																						<th>X</th>

																					</tr>
																				</thead>

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