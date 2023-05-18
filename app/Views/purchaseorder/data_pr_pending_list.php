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
						<a href="<?= base_url() ?>purchaseorder/" title="Refresh Data" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i> Refresh Data</a>

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
																<th style="vertical-align: top;">Inventory No./Material No.<br>
																	Inventory Desc.<br>
																	Service Type</th>
																<th style="vertical-align: top;">Qty</th>
																<th style="vertical-align: top;">Req. Date</th>
																<th style="background-color: white;"></th>
																<th style="vertical-align: top;">PR Number</th>
																<th style="vertical-align: top;">PR Date</th>
																<th style="background-color: white;"></th>
																<th style="vertical-align: top;">Action</th>
																<th style="vertical-align: top;">PO Vendor</th>
																<th style="vertical-align: top;">PO Date</th>
																<th style="vertical-align: top;">ETD</th>
																<th style="vertical-align: top;">Cargo<br>Readiness</th>
																<th style="vertical-align: top;">Origin Country</th>
																<th style="vertical-align: top;">Remarks</th>


															</tr>
														</thead>
														<tbody>

															<?php
															$no = 1;
															foreach ($purchaseOrder_data as $po_list) {
																$crmreq_date = substr($po_list['CRMREQDATE'], 4, 2) . "/" . substr($po_list['CRMREQDATE'], 6, 2) . "/" . substr($po_list['CRMREQDATE'], 0, 4);
																$rqn_date = substr($po_list['RQNDATE'], 4, 2) . "/" . substr($po_list['RQNDATE'], 6, 2) . "/" . substr($po_list['RQNDATE'], 0, 4);
																if ($po_list['PODATE'] == '') {
																	$po_date = '';
																	$etd_date = '';
																} else {
																	$po_date = substr($po_list['PODATE'], 4, 2) . "/" . substr($po_list['PODATE'], 6, 2) . "/" . substr($po_list['PODATE'], 0, 4);
																	$etd_date = substr($po_list['ETDDATE'], 4, 2) . "/" . substr($po_list['ETDDATE'], 6, 2) . "/" . substr($po_list['ETDDATE'], 0, 4);
																}

																if ($po_list['CARGOREADINESSDATE'] == '') {
																	$cargo_readiness_date = '';
																} else {
																	$cargo_readiness_date = substr($po_list['CARGOREADINESSDATE'], 4, 2) . "/" . substr($po_list['CARGOREADINESSDATE'], 6, 2) . "/" . substr($po_list['CARGOREADINESSDATE'], 0, 4);
																}
															?>

																<tr>
																	<td style="vertical-align: top;"><?= $no++; ?></td>
																	<td style="vertical-align: top;" nowrap><strong><a href="#"><?= $po_list['CONTRACT'] ?></a></strong> <?php echo "/" . $po_list['PROJECT'] . "/" . $po_list['CRMNO'] . "<br><strong>" .
																																												$po_list['CTDESC'] . "</strong><br><small>( " .
																																												trim($po_list['NAMECUST']) . " )</small>"; ?></td>
																	<td style="vertical-align: top;" nowrap><?= $po_list['ITEMNO'] . "/" . $po_list['MATERIALNO'] . "<br><strong>" . $po_list['ITEMDESC'] . "</strong><br>" .
																												"Type : " . $po_list['SERVICETYPE'] ?></td>
																	<td style="vertical-align: top;" nowrap><?= number_format($po_list['QTY'], 0, ",", ".") . ' (' . trim($po_list['STOCKUNIT']) . ')'; ?></td>
																	<td style="vertical-align: top;" nowrap><?= $crmreq_date;
																											?></td>

																	<td style="background-color: white;"></td>
																	<td style="vertical-align: top;"><strong><a href="#"><?= $po_list['RQNNUMBER']; ?></a></strong></td>
																	<td style="vertical-align: top;"><?= $rqn_date; ?></td>
																	<td style="background-color: white;"></td>
																	<td style="vertical-align: top;" nowrap>
																		<div class="btn-group">
																			<button type="button" class="btn btn-social btn-flat btn-info btn-sm" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Choose Button</button>
																			<ul class="dropdown-menu" role="menu">
																				<?php if (is_null($po_list['PONUMBER'])) :
																				?>
																					<li>
																						<a href="<?= base_url("purchaseorder/update/" . $po_list['RQNUNIQ'] . '/1') ?>" class="btn btn-social btn-flat btn-block btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox"><i class="fa fa-check-square-o"></i> Update Data PO & Posting</a>
																					</li>
																					<li>
																						<a href="<?= base_url("purchaseorder/update/" . $po_list['RQNUNIQ'] . '/0') ?>" class="btn btn-social btn-flat btn-block btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox"><i class="fa fa-edit"></i> Update Data PO & Save</a>
																					</li>
																				<?php endif;
																				?>
																				<?php if (!empty($po_list['PONUMBER']) and $po_list['POPOSTINGSTAT'] == 0) :
																				?>
																					<li>
																						<a href="<?= base_url("purchaseorder/update/" . $po_list['RQNUNIQ'] . '/1') ?>" class="btn btn-social btn-flat btn-block btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox"><i class="fa fa-check-square-o"></i> Update Data PO & Posting</a>
																					</li>
																				<?php endif;
																				?>
																				<?php if (!empty($po_list['PONUMBER']) and $po_list['POPOSTINGSTAT'] == 1 and empty($po_list['CARGOREADINESSDATE'])) :
																				?>
																					<li>
																						<a href="<?= base_url("purchaseorder/update_cargoreadiness/" . $po_list['POUNIQ']) ?>" class="btn btn-social btn-flat btn-block btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox"><i class="fa fa-calendar-plus-o"></i> Update Cargo Readiness & Posting</a>
																					</li>
																				<?php endif;
																				?>
																				<?php if ($po_list['POPOSTINGSTAT'] == 1 and $po_list['POOFFLINESTAT'] == 1 and !empty($po_list['CARGOREADINESSDATE'])) :
																				?>
																					<li>
																						<a href="<?= base_url("purchaseorder/sendnotif/" . $po_list['POUNIQ']) ?>" class="btn btn-social btn-flat btn-block btn-sm"><i class="fa fa-send-o"></i> Sending Notif Manually</a>
																					</li>
																				<?php endif;
																				?>

																			</ul>
																		</div>

																	</td>
																	<td style="vertical-align: top;" nowrap><?= $po_list['PONUMBER'] ?></td>
																	<td style="vertical-align: top;" nowrap><?= $po_date ?></td>
																	<td style="vertical-align: top;" nowrap><?= $etd_date ?></td>
																	<th style="vertical-align: top;" nowrap><?= $cargo_readiness_date ?></th>
																	<td style="vertical-align: top;" nowrap><?= $po_list['ORIGINCOUNTRY'] ?></td>
																	<td style="vertical-align: top;" nowrap><?= $po_list['POREMARKS'] ?></td>

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