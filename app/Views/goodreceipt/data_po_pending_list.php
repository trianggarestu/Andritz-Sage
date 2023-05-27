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
						<a href="<?= base_url() ?>arrangeshipment/" title="Refresh Data" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i> Refresh Data</a>

					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<form id="mainform" name="mainform" action="" method="post">
										<div class="row">
											<div class="col-sm-9">
												<p><i class="fa fa-info-circle"></i><code> { only viewed <strong>Purchase Orders</strong> data that waiting to be processed by the Good Receipt }</code></p>
												<p><code> Choose Button info :</code><br>
													<code>- Choose Good Receipt P/O & Save, data is only saved without updated to Order Tracking</code><br>
													<code>- Choose Good Receipt P/O & Posting, data will be saved and updated to Order Tracking</code>
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
																<th style="background-color: white;"></th>
																<th style="vertical-align: top;">Action</th>
																<th style="vertical-align: top;">Status</th>
																<th style="vertical-align: top;">GR Number</th>
																<th style="vertical-align: top;">GR Date</th>
																<th style="vertical-align: top;">GR Status</th>
																<th style="vertical-align: top;">GR Qty</th>
															</tr>
														</thead>
														<tbody>

															<?php
															$no = 1;
															foreach ($receipt_data as $rcp_list) {
																$crmreq_date = substr($rcp_list['CRMREQDATE'], 4, 2) . "/" . substr($rcp_list['CRMREQDATE'], 6, 2) . "/" . substr($rcp_list['CRMREQDATE'], 0, 4);
																$po_date = substr($rcp_list['PODATE'], 4, 2) . "/" . substr($rcp_list['PODATE'], 6, 2) . "/" . substr($rcp_list['PODATE'], 0, 4);
																if (null == $rcp_list['RECPDATE']) {
																	$rcp_date = '';
																} else {
																	$rcp_date = substr($rcp_list['RECPDATE'], 4, 2) . "/" . substr($rcp_list['RECPDATE'], 6, 2) . "/" . substr($rcp_list['RECPDATE'], 0, 4);
																}

															?>

																<tr>
																	<td style="vertical-align: top;"><?= $no++; ?></td>
																	<td style="vertical-align: top;" nowrap><strong><a href="#"><?= $rcp_list['CONTRACT'] ?></a></strong> <?php echo "/" . $rcp_list['PROJECT'] . "/" . $rcp_list['CRMNO'] . "<br><strong>" .
																																												$rcp_list['CTDESC'] . "</strong><br><small>( " .
																																												trim($rcp_list['NAMECUST']) . " )</small>"; ?></td>

																	<td style="vertical-align: top;" nowrap><?= $crmreq_date;
																											?></td>

																	<td style="background-color: white;"></td>
																	<td style="vertical-align: top;"><strong><a href="#"><?= $rcp_list['PONUMBER']; ?></a></strong></td>
																	<td style="vertical-align: top;"><?= $po_date ?></td>
																	<td style="background-color: white;"></td>
																	<td style="vertical-align: top;" nowrap>
																		<div class="btn-group">
																			<button type="button" class="btn btn-social btn-flat btn-info btn-sm" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Choose Button</button>
																			<ul class="dropdown-menu" role="menu">
																				<?php if (($rcp_list['RCPPOSTINGSTAT'] == 0) or (empty($rcp_list['RCPOFFLINESTAT']))) :
																				?>
																					<li>
																						<a href="<?= base_url("goodreceipt/update/" . $rcp_list['POUNIQ']) ?>" class="btn btn-social btn-flat btn-block btn-sm"><i class="fa fa-edit"></i> Choose Good Receipt & Save</a>
																					</li>
																				<?php endif;
																				?>
																				<?php if ($rcp_list['RCPPOSTINGSTAT'] == 1 and $rcp_list['RCPOFFLINESTAT'] == 1) :
																				?>
																					<li>
																						<a href="<?= base_url("goodreceipt/sendnotif/" . $rcp_list['RCPUNIQ']) ?>" class="btn btn-social btn-flat btn-block btn-sm"><i class="fa fa-send-o"></i> Sending Notif Manually</a>
																					</li>
																				<?php endif;
																				?>

																			</ul>
																		</div>

																	</td>
																	<td style="vertical-align: top;"><?php $postingstat = $rcp_list['RCPPOSTINGSTAT'];
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
																	<td style="vertical-align: top;"><?= $rcp_list['RECPNUMBER']; ?></td>
																	<td style="vertical-align: top;"><?= $rcp_date; ?></td>
																	<td style="vertical-align: top;"><?php $grstatus = $rcp_list['GRSTATUS'];
																										switch ($grstatus) {
																											case "0":
																												echo "Partial";
																												break;
																											case "1":
																												echo "Completed";
																												break;
																											default:
																												echo "";
																										} ?></td>
																	<td style="vertical-align: top;"><?= number_format($rcp_list['RECPQTY'], 0, ",", "."); ?></td>
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