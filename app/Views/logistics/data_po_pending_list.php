<style>
	.input-sm {
		padding: 4px 4px;
	}
</style>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Arrange Shipment P/O</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Arrange Shipment P/O</li>
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
						<a href="<?= base_url() ?>arrangeshipment/" title="Refresh Data" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i> Refresh Data</a>

					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<form id="mainform" name="mainform" action="" method="post">
										<div class="row">
											<div class="col-sm-9">
												<p><i class="fa fa-info-circle"></i><code> { only viewed <strong>Purchase Orders</strong> data that waiting to be processed by the Logistics }</code></p>
												<p><code> Choose Button info :</code><br>
													<code>- Choose Arrange Shipment P/O & Save, data is only saved without updated to Order Tracking</code><br>
													<code>- Choose Arrange Shipment P/O & Posting, data will be saved and updated to Order Tracking</code>
												</p>
											</div>
											<div class="col-sm-3" style="vertical-align: text-bottom;">
												<div class="box-tools">
													<div class="input-group input-group-sm pull-right">
														<input name="cari" id="cari" class="form-control" placeholder="Search..." type="text" value="" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', 'http://localhost:8082/OpenSID/index.php/surat_masuk/search');$('#'+'mainform').submit();}">
														<div class="input-group-btn">
															<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', 'http://localhost:8082/OpenSID/index.php/surat_masuk/search');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
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
																<th style="vertical-align: top;">PO Vendor</th>
																<th style="vertical-align: top;">PO Date</th>
																<th style="vertical-align: top;">ETD Date</th>
																<th style="vertical-align: top;">Cargo Readiness</th>
																<th style="background-color: white;"></th>
																<th style="vertical-align: top;">Action</th>
																<th style="vertical-align: top;">Status</th>
																<th style="vertical-align: top;">ETD Origin</th>
																<th style="vertical-align: top;">ATD Origin</th>
																<th style="vertical-align: top;">ETA Port</th>
																<th style="vertical-align: top;">PIB</th>
																<th style="vertical-align: top;">Shipment Status</th>


															</tr>
														</thead>
														<tbody>

															<?php
															$no = 1;
															foreach ($logistics_data as $log_list) {
																$crmreq_date = substr($log_list['CRMREQDATE'], 4, 2) . "/" . substr($log_list['CRMREQDATE'], 6, 2) . "/" . substr($log_list['CRMREQDATE'], 0, 4);
																$po_date = substr($log_list['PODATE'], 4, 2) . "/" . substr($log_list['PODATE'], 6, 2) . "/" . substr($log_list['PODATE'], 0, 4);
																$etd_date = substr($log_list['ETDDATE'], 4, 2) . "/" . substr($log_list['ETDDATE'], 6, 2) . "/" . substr($log_list['ETDDATE'], 0, 4);
																$cargo_readiness_date = substr($log_list['CARGOREADINESSDATE'], 4, 2) . "/" . substr($log_list['CARGOREADINESSDATE'], 6, 2) . "/" . substr($log_list['CARGOREADINESSDATE'], 0, 4);
																if (null == $log_list['ETDORIGINDATE']) {
																	$etdorigin_date = '';
																} else {
																	$etdorigin_date = substr($log_list['ETDORIGINDATE'], 4, 2) . "/" . substr($log_list['ETDORIGINDATE'], 6, 2) . "/" . substr($log_list['ETDORIGINDATE'], 0, 4);
																}
																if (null == $log_list['ATDORIGINDATE']) {
																	$atdorigin_date = '';
																} else {
																	$atdorigin_date = substr($log_list['ATDORIGINDATE'], 4, 2) . "/" . substr($log_list['ATDORIGINDATE'], 6, 2) . "/" . substr($log_list['ATDORIGINDATE'], 0, 4);
																}
																if (null == $log_list['ETAPORTDATE']) {
																	$etaport_date = '';
																} else {
																	$etaport_date = substr($log_list['ETAPORTDATE'], 4, 2) . "/" . substr($log_list['ETAPORTDATE'], 6, 2) . "/" . substr($log_list['ETAPORTDATE'], 0, 4);
																}
																if (null == $log_list['PIBDATE']) {
																	$pib_date = '';
																} else {
																	$pib_date = substr($log_list['PIBDATE'], 4, 2) . "/" . substr($log_list['PIBDATE'], 6, 2) . "/" . substr($log_list['PIBDATE'], 0, 4);
																}

															?>

																<tr>
																	<td style="vertical-align: top;"><?= $no++; ?></td>
																	<td style="vertical-align: top;" nowrap><strong><a href="#"><?= $log_list['CONTRACT'] ?></a></strong> <?php echo "/" . $log_list['PROJECT'] . "/" . $log_list['CRMNO'] . "<br><strong>" .
																																												$log_list['CTDESC'] . "</strong><br><small>( " .
																																												trim($log_list['NAMECUST']) . " )</small>"; ?></td>

																	<td style="vertical-align: top;" nowrap><?= $crmreq_date;
																											?></td>

																	<td style="background-color: white;"></td>
																	<td style="vertical-align: top;"><strong><a href="#"><?= $log_list['PONUMBER']; ?></a></strong></td>
																	<td style="vertical-align: top;"><?= $po_date ?></td>
																	<td style="vertical-align: top;"><?= $etd_date ?></td>
																	<td style="vertical-align: top;"><?= $cargo_readiness_date ?></td>
																	<td style="background-color: white;"></td>
																	<td style="vertical-align: top;" nowrap>
																		<div class="btn-group">
																			<button type="button" class="btn btn-social btn-flat btn-info btn-sm" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Choose Button</button>
																			<ul class="dropdown-menu" role="menu">

																				<li>
																					<a href="<?= base_url("arrangeshipment/update/" . $log_list['POUNIQ'] .  '/1') ?>" class="btn btn-social btn-flat btn-block btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox"><i class="fa fa-check-square-o"></i> Arrange Shipment PO & Posting</a>
																				</li>
																				<li>
																					<a href="<?= base_url("arrangeshipment/update/" . $log_list['POUNIQ'] . '/0') ?>" class="btn btn-social btn-flat btn-block btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox"><i class="fa fa-edit"></i> Arrange Shipment PO & Save</a>
																				</li>
																				<?php if ($log_list['LOGPOSTINGSTAT'] == 1 and $log_list['LOGOFFLINESTAT'] == 1) :
																				?>
																					<li>
																						<a href="<?= base_url("arrangeshipment/sendnotif/" . $log_list['LOGUNIQ']) ?>" class="btn btn-social btn-flat btn-block btn-sm"><i class="fa fa-send-o"></i> Sending Notif Manually</a>
																					</li>
																				<?php endif;
																				?>

																			</ul>
																		</div>

																	</td>
																	<td style="vertical-align: top;"><?php $postingstat = $log_list['LOGPOSTINGSTAT'];
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
																	<td style="vertical-align: top;"><?= $etdorigin_date ?></td>
																	<td style="vertical-align: top;"><?= $atdorigin_date ?></td>
																	<td style="vertical-align: top;"><?= $etaport_date ?></td>
																	<td style="vertical-align: top;"><?= $pib_date ?></td>
																	<td nowrap style="vertical-align: top;"><?= $log_list['VENDSHISTATUS'] ?></td>

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
										<!-- Pagination template
											<div class="col-sm-6">
											
										</div>
										<div class="col-sm-6">
											<div class="dataTables_paginate paging_simple_numbers">
												<ul class="pagination">
													<?php //if ($paging->start_link) : 
													?>
													<li>
														<a href="<? //= site_url('covid19/data_pemudik/' . $paging->start_link) 
																	?>" aria-label="First"><span aria-hidden="true">Awal</span></a>
													</li>
													<?php //endif; 
													?>
													<?php //if ($paging->prev) : 
													?>
													<li>
														<a href="<? //= site_url('covid19/data_pemudik/' . $paging->prev) 
																	?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
													</li>
													<?php //endif; 
													?>
													<?php //for ($i = $paging->start_link; $i <= $paging->end_link; $i++) : 
													?>

													<li class='active'>
														<a href="<? //= site_url('covid19/data_pemudik/' . $i) 
																	?>"><? //= $i 
																		?>
															1</a>
													</li>
													<?php //endfor; 
													?>
													<?php //if ($paging->next) : 
													?>
													<li>
														<a href="<? //= site_url('covid19/data_pemudik/' . $paging->next) 
																	?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
													</li>
													<?php //endif; 
													?>
													<?php //if ($paging->end_link) : 
													?>
													<li>
														<a href="<? //= site_url('covid19/data_pemudik/' . $paging->end_link) 
																	?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a>
													</li>
													<?php //endif; 
													?>
												</ul>
											</div>
										</div>
													-->
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