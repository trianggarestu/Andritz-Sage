<style>
	.input-sm {
		padding: 4px 4px;
	}
</style>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Sales Order List Waiting to process by Requester</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Sales Order List Waiting to process</li>
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
						<a href="#" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Preview"><i class="fa fa-print"></i> Preview
						</a>
						<a href="<?= base_url("salesorderlist/export_excel") ?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh" target="_blank"><i class="fa fa-download"></i> Download
						</a>
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
																<th>Customer Name</th>
																<th>Contract No.</th>
																<th>Contract Name</th>
																<th>Project No.</th>

																<th>CRM Number</th>
																<th>Req Date</th>
																<th style="background-color: white;"></th>
																<th>Action</th>
																<th>PR Number</th>
																<th>PR Date</th>
																<th style="background-color: white;"></th>


															</tr>
														</thead>
														<tbody>

															<?php
															$no = 1;
															foreach ($requisition_data as $ot_list) {
																$crmreq_date = substr($ot_list['CRMREQDATE'], 6, 2) . "/" . substr($ot_list['CRMREQDATE'], 4, 2) . "/" . substr($ot_list['CRMREQDATE'], 0, 4);
																if ($ot_list['RQNDATE'] == '') {
																	$pr_date = '';
																} else {
																	$pr_date = substr($ot_list['RQNDATE'], 6, 2) . "/" . substr($ot_list['RQNDATE'], 4, 2) . "/" . substr($ot_list['RQNDATE'], 0, 4);
																}
															?>

																<tr>
																	<td><?= $no++; ?></td>
																	<td><?= $ot_list['NAMECUST']; ?></td>
																	<td nowrap><?= $ot_list['CONTRACT']; ?></td>
																	<td nowrap><?= $ot_list['CTDESC']; ?></td>
																	<td nowrap><?= $ot_list['PROJECT']; ?></td>
																	<td nowrap><?= $ot_list['CRMNO']; ?></td>
																	<td nowrap><?= $crmreq_date; ?></td>

																	<td style="background-color: white;"></td>
																	<td nowrap>
																		<div class="btn-group">
																			<button type="button" class="btn btn-social btn-flat btn-info btn-sm" data-toggle="dropdown"><i class='fa fa-arrow-circle-down'></i> Choose Button</button>
																			<ul class="dropdown-menu" role="menu">
																				<?php if (is_null($ot_list['RQNNUMBER'])) :
																				?>
																					<li>
																						<a href="<?= base_url("requisition/update/" . $ot_list['CSRUNIQ'] . '/1') ?>" class="btn btn-social btn-flat btn-block btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox"><i class="fa fa-check-square-o"></i> Select Requisition & Post</a>
																					</li>

																					<li>
																						<a href="<?= base_url("requisition/update/" . $ot_list['CSRUNIQ'] . '/0') ?>" class="btn btn-social btn-flat btn-block btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox"><i class="fa fa-edit"></i> Select Requisition Only</a>
																					</li>
																				<?php endif;
																				?>
																				<?php if (!empty($ot_list['RQNNUMBER']) and $ot_list['POSTINGSTAT'] == 0) :
																				?>
																					<li>
																						<a href="<?= base_url("requisition/update/" . $ot_list['CSRUNIQ'] . '/1') ?>" class="btn btn-social btn-flat btn-block btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox"><i class="fa fa-check-square-o"></i> Check & Posting Requisition</a>
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
																	<td nowrap><?= $ot_list['RQNNUMBER']; ?></td>
																	<td nowrap><?= $pr_date; ?></td>
																	<td style="background-color: white;"></td>

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