<style>
	.input-sm {
		padding: 4px 4px;
	}
</style>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Confirm D/N Origin</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Confirm DN Origin</li>
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
						<a href="<?= base_url() ?>confirmdnorigin/refresh/" title="Refresh Data" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i> Refresh Data</a>

					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<form id="mainform" name="mainform" action="" method="post">
										<div class="row">
											<div class="col-sm-9">
												<p><i class="fa fa-info-circle"></i><code> { only viewed <strong>Delivery Note</strong> data that waiting to be processed by the Sales Admin }</code></p>

											</div>
											<div class="col-sm-3" style="vertical-align: text-bottom;">
												<div class="box-tools">
													<div class="input-group input-group-sm pull-right">
														<input name="cari" id="cari" class="form-control" placeholder="Search..." type="text" value="<?= $keyword ?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= base_url('confirmdnorigin/search') ?>');$('#'+'mainform').submit();}">
														<div class="input-group-btn">
															<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= base_url('confirmdnorigin/search') ?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
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
																<th style="vertical-align: top;">Doc. Number<br>DN Number</th>
																<th style="vertical-align: top;">Shipment Date</th>
																<th style="vertical-align: top;">Status</th>
																<th style="background-color: white;"></th>
																<th style="vertical-align: top;">Action</th>
																<th style="vertical-align: top;" nowrap>Posting<br> Status </th>
																<th style="vertical-align: top;" nowrap>D/N Origin<br> Status</th>

															</tr>
														</thead>
														<tbody>

															<?php
															$no = 1;
															foreach ($delivery_data as $shi_list) {
																$crmreq_date = substr($shi_list['CRMREQDATE'], 4, 2) . "/" . substr($shi_list['CRMREQDATE'], 6, 2) . "/" . substr($shi_list['CRMREQDATE'], 0, 4);
																if (null == $shi_list['SHIDATE']) {
																	$shi_date = '';
																} else {
																	$shi_date = substr($shi_list['SHIDATE'], 4, 2) . "/" . substr($shi_list['SHIDATE'], 6, 2) . "/" . substr($shi_list['SHIDATE'], 0, 4);
																}
																if (null == $shi_list['CUSTRCPDATE']) {
																	$custrcp_date = '';
																} else {
																	$custrcp_date = substr($shi_list['CUSTRCPDATE'], 4, 2) . "/" . substr($shi_list['CUSTRCPDATE'], 6, 2) . "/" . substr($shi_list['CUSTRCPDATE'], 0, 4);
																}


															?>

																<tr>
																	<td style="vertical-align: top;"><?= $no++; ?></td>
																	<td style="vertical-align: top;" nowrap><strong><a href="#"><?= $shi_list['CSRCONTRACT'] ?></a></strong>
																		<?php echo "/" . $shi_list['CSRPROJECT'] . "/" . $shi_list['CRMNO'] . "<br>
																	<strong>" . $shi_list['CTDESC'] . "</strong><br>
																	<small>( " . trim($shi_list['NAMECUST']) . " )</small>"; ?><br>
																		<table class="table table-bordered table-striped dataTable">
																			<thead class="bg-gray disabled">
																				<tr>
																					<th colspan="3"><small>Inventory Info</small>
																					</th>
																				</tr>
																			</thead>
																			<tr>
																				<td><small>Item No./Material No.</small></td>
																				<td>:</td>
																				<td><small><?= $shi_list['ITEMNO'] . " / " . $shi_list['MATERIALNO'];
																							?></small></td>
																			</tr>
																			<tr>
																				<td><small>Item Description</small></td>
																				<td>:</td>
																				<td><?= "<strong><small>" . $shi_list['ITEMDESC'] . "</small></strong><br>"; ?></td>
																			</tr>
																			<tr>
																				<td><small>Type</small></td>
																				<td><small>:</small></td>
																				<td><small><?= $shi_list['SERVICETYPE']; ?></small></td>
																			</tr>
																			<tr>
																				<td><small>Qty</small></td>
																				<td><small>:</small></td>
																				<td><small><?= number_format($shi_list['QTY'], 0, ",", ".") . ' (' . trim($shi_list['STOCKUNIT']) . ')' ?></small></td>
																			</tr>
																		</table>
																	</td>

																	<td style="vertical-align: top;" nowrap><?= $crmreq_date;
																											?></td>

																	<td style="background-color: white;"></td>
																	<td style="vertical-align: top;" nowrap>
																		<strong><a href="#"><?= $shi_list['DOCNUMBER'] ?></a></strong><br>
																		<?= $shi_list['SHINUMBER'] ?><br>
																		<table class="table table-bordered table-striped dataTable">
																			<thead class="bg-gray disabled">
																				<tr>
																					<th colspan="3"><small>Delivery Note Info</small>
																					</th>
																				</tr>
																			</thead>

																			<tr>
																				<td><small>Received Date</small></td>
																				<td><small>:</small></td>
																				<td><small><?= $custrcp_date ?></small></td>
																			</tr>
																			<tr>
																				<td><small>Delivered (Qty)</small></td>
																				<td><small>:</small></td>
																				<td><small><?php if (!empty($shi_list['SHIQTY'])) {
																								echo number_format($shi_list['SHIQTY'], 0, ",", ".") . ' (' . trim($shi_list['SHIUNIT']) . ')';
																							} ?></small></td>
																			</tr>
																			<tr>
																				<td><small>Outstanding (Qty)</small></td>
																				<td><small>:</small></td>
																				<td><small><?php if (!empty($shi_list['SHIQTYOUTSTANDING'])) {
																								echo number_format($shi_list['SHIQTYOUTSTANDING'], 0, ",", ".") . ' (' . trim($shi_list['SHIUNIT']) . ')';
																							} ?></small></td>
																			</tr>
																			<tr>
																				<td><small>P/O Status</small></td>
																				<td><small>:</small></td>
																				<td><small><?php
																							$pocuststatus = $shi_list['POCUSTSTATUS'];
																							switch ($pocuststatus) {
																								case "0":
																									echo "Outstanding";
																									break;
																								case "1":
																									echo "Completed";
																									break;
																								default:
																									echo "";
																							}

																							?></small></td>
																			</tr>
																			<tr>
																				<td><small>e-Delivery Note</small></td>
																				<td><small>:</small></td>
																				<td><a href="<?= base_url($shi_list['EDNFILEPATH']) ?>" download>
																						<small><?php echo $shi_list['EDNFILENAME'] ?></small>
																					</a></td>
																			</tr>
																		</table>
																	</td>
																	<td style="vertical-align: top;"><?= $shi_date ?></td>
																	<td style="vertical-align: top;"><?php $postingstat = $shi_list['POSTINGSTAT'];
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
																				<?php if (($shi_list['POSTINGSTAT'] == 1) and !empty($shi_list['EDNFILENAME']) and $shi_list['DNPOSTINGSTAT'] == 0) :
																				?>
																					<li>
																						<a href="<?= base_url("confirmdnorigin/update/" . $shi_list['SHIUNIQ'] . '/1') ?>" class="btn btn-social btn-flat btn-block btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox"><i class="fa fa-check-square-o"></i> Confirm D/N Origin & Post</a>
																					</li>

																					<li>
																						<a href="<?= base_url("confirmdnorigin/update/" . $shi_list['SHIUNIQ'] . '/0') ?>" class="btn btn-social btn-flat btn-block btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox"><i class="fa fa-edit"></i> Confirm D/N Origin & Save</a>
																					</li>
																				<?php endif;
																				?>

																				<?php if ($shi_list['DNPOSTINGSTAT'] == 1 and $shi_list['DNOFFLINESTAT'] == 1 and $shi_list['DNSTATUS'] == 1) :
																				?>
																					<li>
																						<a href="<?= base_url("confirmdnorigin/sendnotif/" . $shi_list['SHIUNIQ']) ?>" class="btn btn-social btn-flat btn-block btn-sm"><i class="fa fa-send-o"></i> Sending Notif Manually</a>
																					</li>
																				<?php endif;
																				?>

																			</ul>
																		</div>

																	</td>

																	<td style="vertical-align: top;" nowrap><?php
																											$dnpostingstatus = $shi_list['DNPOSTINGSTAT'];
																											switch ($dnpostingstatus) {
																												case "0":
																													echo "Open";
																													break;
																												case "1":
																													echo "Posted";
																													break;
																												default:
																													echo "";
																											}

																											?></td>
																	<td style="vertical-align: top;" nowrap>
																		<?php
																		$dnstatus = $shi_list['DNSTATUS'];
																		switch ($dnstatus) {
																			case "1":
																				echo "RECEIVED";
																				break;

																			default:
																				echo "";
																		}

																		?></td>

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