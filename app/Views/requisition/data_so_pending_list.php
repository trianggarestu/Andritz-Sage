<style>
	.input-sm {
		padding: 4px 4px;
	}
</style>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Waiting List Sales Order to process by Requester</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
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
						<a href="<?= base_url() ?>requisition/" title="Refresh Data" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i> Refresh Data</a>

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
																<th>No.</th>
																<th nowrap>Contract/Project/CRM<br>Contract Desc.<br>Customer</th>
																<th style="vertical-align: top;">Req Date</th>
																<th style="background-color: white;"></th>
																<th style="vertical-align: top;">Action</th>
																<th style="vertical-align: top;">Status</th>
																<th style="vertical-align: top;">PR Number</th>
																<th style="vertical-align: top;">PR Date</th>


															</tr>
														</thead>
														<tbody>

															<?php
															$no = 1;
															foreach ($requisition_data as $ot_list) {
																$crmreq_date = substr($ot_list['CRMREQDATE'], 4, 2) . "/" . substr($ot_list['CRMREQDATE'], 6, 2) . "/" . substr($ot_list['CRMREQDATE'], 0, 4);
																if ($ot_list['RQNDATE'] == '') {
																	$pr_date = '';
																} else {
																	$pr_date = substr($ot_list['RQNDATE'], 4, 2) . "/" . substr($ot_list['RQNDATE'], 6, 2) . "/" . substr($ot_list['RQNDATE'], 0, 4);
																}
															?>

																<tr>
																	<td><?= $no++; ?></td>
																	<td nowrap><strong><a href="#"><?= $ot_list['CONTRACT'] ?></a></strong>
																		<?= " / " . $ot_list['PROJECT'] . " / " . $ot_list['CRMNO']; ?><br>
																		<strong><?= $ot_list['CTDESC']; ?></strong><br>
																		<small>(<?= $ot_list['NAMECUST']; ?>)</small><br>
																		<table class="table table-bordered table-striped dataTable">
																			<thead class="bg-gray disabled">
																				<tr>
																					<th colspan="3"><small>Inventory Info</small>
																					</th>
																				</tr>
																			</thead>
																			<tr>
																				<td style="width: 15%;"><small>Item No./Material No.</small></td>
																				<td style="width: 1%;"><small>:</small></td>
																				<td><small><?= $ot_list['ITEMNO'] . " / " .  $ot_list['MATERIALNO'];
																							?></small></td>
																			</tr>
																			<tr>
																				<td><small>Item Description</small></td>
																				<td><small>:</small></td>
																				<td><small><?= "<strong>" .  $ot_list['ITEMDESC'] . "</strong><br>"; ?></small></td>
																			</tr>
																			<tr>
																				<td><small>Type</small></td>
																				<td><small>:</small></td>
																				<td><small><?= $ot_list['SERVICETYPE']; ?></small></td>
																			</tr>
																			<tr>
																				<td><small>Qty</small></td>
																				<td><small>:</small></td>
																				<td><small><?= number_format($ot_list['QTY'], 0, ",", ".") . ' (' . trim($ot_list['STOCKUNIT']) . ')' ?></small></td>
																			</tr>
																		</table>

																	</td>
																	<td style="vertical-align: top;" nowrap><?= $crmreq_date; ?></td>

																	<td style="background-color: white;"></td>
																	<td style="vertical-align: top;" nowrap>
																		<div class="btn-group">
																			<button type="button" class="btn btn-social btn-flat btn-info btn-sm" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Choose Button</button>
																			<ul class="dropdown-menu" role="menu">
																				<?php if ($ot_list['POSTINGSTAT'] == 0) :
																				?>
																					<li>
																						<a href="<?= base_url("requisition/update/" . $ot_list['CSRUNIQ'] . '/1') ?>" class="btn btn-social btn-flat btn-block btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox"><i class="fa fa-check-square-o"></i> Select Requisition & Post</a>
																					</li>

																					<li>
																						<a href="<?= base_url("requisition/update/" . $ot_list['CSRUNIQ'] . '/0') ?>" class="btn btn-social btn-flat btn-block btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox"><i class="fa fa-edit"></i> Select Requisition & Save</a>
																					</li>
																				<?php endif;
																				?>

																				<?php if ($ot_list['POSTINGSTAT'] == 1 and $ot_list['OFFLINESTAT'] == 1) :
																				?>
																					<li>
																						<a href="<?= base_url("requisition/sendnotif/" . $ot_list['RQNUNIQ']) ?>" class="btn btn-social btn-flat btn-block btn-sm"><i class="fa fa-send-o"></i> Sending Notif Manually</a>
																					</li>
																				<?php endif;
																				?>

																			</ul>
																		</div>

																	</td>
																	<td style="vertical-align: top;" nowrap>
																		<?php $postingstat = $ot_list['POSTINGSTAT'];
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
																		} ?>
																	</td>
																	<td style="vertical-align: top;" nowrap><?= $ot_list['RQNNUMBER']; ?></td>
																	<td style="vertical-align: top;" nowrap><?= $pr_date; ?></td>

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