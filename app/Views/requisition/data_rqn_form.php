<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/validasi.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/localization/messages_id.js"></script>
<style>
	.input-sm {
		padding: 4px 4px;
	}

	.tabel-info,
	td {
		height: 30px;
		padding: 5px;
		word-wrap: break-word;
	}
</style>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Requisition Entry Form</h1>
		<ol class="breadcrumb">
			<li><a href="<?= base_url('administration') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= base_url('requisition') ?>">Purchase Requisition</li></a>
			<li class="active">Requisition Entry</li>
		</ol>
	</section>

	<input id="success-code" type="hidden" value="<?= $success_code ?>">
	<!-- Untuk menampilkan modal bootstrap umum  -->
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
						<form action="<?= $form_action; ?>" method="post" id="validasi">
							<?php if ($csropen_data['POSTINGSTAT'] == 1 and $csropen_data['OFFLINESTAT'] == 0) :
							?>
								<a href="<?= base_url('requisition') ?>" title="Back to Purchase Requisition" class="btn btn-social btn-flat bg-aqua btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Back to Purchase Requisition</a>
							<?php endif; ?>

							<?php if ($csropen_data['POSTINGSTAT'] == 0 or ($csropen_data['POSTINGSTAT'] == 1 and $csropen_data['OFFLINESTAT'] == 1)) :
							?>
								<a href="<?= base_url('salesorderopen') ?>" title="Back to Sales Order Open" class="btn btn-social btn-flat bg-aqua btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Back to Sales Order Open</a>
							<?php endif; ?>
					</div>

					<div class="box-body">

						<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
							<div class="row">
								<div class="col-sm-12">
									<div class="table-responsive">
										<table class="table table-bordered table-striped table-hover">
											<tbody>
												<tr>
													<td colspan="3" class="subtitle_head"><strong>CONTRACT / PROJECT</strong></td>
												</tr>
												<tr>
													<td width="300">Status </td>
													<td width="1">:</td>
													<td><strong><?php $postingstat = $csropen_data['POSTINGSTAT'];
																switch ($postingstat) {
																	case "0":
																		echo "<span class='label label-warning'>Open</span>";
																		break;
																	case "1":
																		echo "<span class='label label-success'>Posted</span>";
																		break;
																	case "2":
																		echo "<span class='label label-danger'>Deleted</span>";
																		break;
																	default:
																		echo "<span class='label label-warning'>Open</span>";
																} ?></strong></td>
												</tr>
												<tr>
													<td colspan="3">
														<div class="row">
															<div class="col-md-6">
																<div class="box box-default">
																	<div class="box-header with-border">
																		<i class="fa fa-file-text"></i>

																		<h3 class="box-title">Contract</h3>
																	</div>
																	<!-- /.box-header -->
																	<div class="box-body">
																		<table class="table table-bordered table-striped table-hover">
																			<tr>
																				<td width=" 150">Contract
																				</td>
																				<td width="1">:</td>
																				<td><strong><?= $csropen_data['CONTRACT']; ?></strong></td>
																			</tr>
																			<tr>
																				<td width="150">Contract Description </td>
																				<td width="1">:</td>
																				<td><strong><?= $csropen_data['CTDESC']; ?></strong></td>
																			</tr>
																			<tr>
																				<td width="150">Customer </td>
																				<td width="1">:</td>
																				<td><strong>
																						<?= $csropen_data['CUSTOMER'] . ' - ' . $csropen_data['NAMECUST']; ?>
																					</strong>

																					<br>
																					<small>
																						< <?= $csropen_data['EMAIL1CUST']; ?>>
																					</small>

																				</td>
																			</tr>
																			<tr>
																				<td width="150">Sales Person </td>
																				<td width="1">:</td>
																				<td><strong><?= $csropen_data['MANAGER'] . '(' . $csropen_data['SALESNAME'] . ')'; ?></strong></td>
																			</tr>
																		</table>
																	</div>
																</div>
															</div>
															<div class="col-md-6">
																<div class="box box-default">
																	<div class="box-header with-border">
																		<i class="fa fa-file-text"></i>

																		<h3 class="box-title">Project</h3>
																	</div>
																	<!-- /.box-header -->
																	<div class="box-body">
																		<table class="table table-bordered table-striped table-hover">
																			<tr>
																				<td width="150">Project </td>
																				<td width="1">:</td>
																				<td><strong><?= $csropen_data['PROJECT']; ?></strong></td>
																			</tr>
																			<tr>
																				<td width="150">Project Description </td>
																				<td width="1">:</td>
																				<td><strong><?= $csropen_data['PRJDESC']; ?></strong></td>
																			</tr>
																			<tr>
																				<td width="150">PO Number Customer </td>
																				<td width="1">:</td>
																				<td><strong><?= $csropen_data['PONUMBERCUST']; ?></strong></td>
																			</tr>
																			<tr>
																				<td width="150">PO Customer Date </td>
																				<td width="1">:</td>
																				<td><strong><?php
																							$dd = substr($csropen_data['PODATECUST'], 6, 2);
																							$mm = substr($csropen_data['PODATECUST'], 4, 2);
																							$yyyy = substr($csropen_data['PODATECUST'], 0, 4);
																							$pocustdate = $mm . '/' . $dd . '/' . $yyyy;
																							echo $pocustdate; ?></strong></td>
																			</tr>
																		</table>
																	</div>
																</div>
															</div>
														</div>
													</td>
												</tr>




												<tr>
													<th colspan="3" class="subtitle_head"><strong>CRM</strong></th>
												</tr>
												<tr>
													<td width="300">CRM Number </td>
													<td width="1">:</td>
													<td><strong><?= $csropen_data['CRMNO']; ?></strong></td>
												</tr>
												<tr>
													<td width="300">Request Date </td>
													<td width="1">:</td>
													<td><strong><?php

																$dd = substr($csropen_data['CRMREQDATE'], 6, 2);
																$mm = substr($csropen_data['CRMREQDATE'], 4, 2);
																$yyyy = substr($csropen_data['CRMREQDATE'], 0, 4);
																$reqdate = $mm . '/' . $dd . '/' . $yyyy;
																echo $reqdate; ?></strong></td>
												</tr>
												<tr>
													<td width="300">Order Description </td>
													<td width="1">:</td>
													<td><strong><?= $csropen_data['ORDERDESC']; ?></strong></td>
												</tr>
												<tr>
													<td width="300">Remarks </td>
													<td width="1">:</td>
													<td><strong><?= $csropen_data['CRMREMARKS']; ?></strong></td>
												</tr>

												<tr>
													<td colspan="3" class="subtitle_head"><strong>Item Parts / Services S/O</strong></td>
												</tr>
												<tr>
													<td width="300" style="vertical-align: top;">Item Details S/O</td>
													<td width="1">:</td>
													<td>
														<div class="table-responsive">
															<table class="table table-bordered dataTable table-hover nowrap">
																<thead class="bg-gray disabled color-palette">
																	<tr>

																		<th class="padat">No</th>

																		<th>Type </th>
																		<th>Inventory No.</th>
																		<th>Material No.</th>
																		<th>Item Desc.</th>
																		<th>Qty.</th>
																		<th>Uom</th>



																	</tr>
																</thead>
																<tbody>
																	<?php
																	$no = 0;
																	foreach ($csrlopen_data as $items) :
																	?>
																		<tr>

																			<td class="text-center"><?= ++$no ?></td>
																			<td><?= $items['SERVICETYPE']
																				?></td>

																			<td><?= $items['ITEMNO']
																				?></td>
																			<td><?= $items['MATERIALNO']
																				?></td>
																			<td nowrap><?= $items['ITEMDESC']
																						?></td>
																			<td><?= number_format($items['QTY'], 0, ",", ".")
																				?></td>
																			<td nowrap><?= $items['STOCKUNIT']
																						?></td>

																		<?php endforeach;
																		?>
																</tbody>
															</table>
														</div>
													</td>
												</tr>
												<tr>
													<td colspan="3" class="subtitle_head"><strong>SELECT REQUISITION FOR S/O</strong></td>
												</tr>
												<tr>
													<td width="300">Select Requisition No. </td>
													<td width="1">:</td>
													<td>
														<div class="form-group">
															<div class="input-group input-group-sm">

																<span class="input-group-btn">
																	<a href="<?= base_url('requisition/form_select_rqn_all/' . $csropen_data['CSRUNIQ'] . '/' . $post_stat); ?>" data-remote="false" data-toggle="modal" data-target="#modalBox" class="btn btn-flat bg-navy btn-sm pull-right">
																		<i class='fa fa-search'></i> Select Requisition No.
																	</a>
																</span>

															</div>
														</div>

														<div class="form-group">
															<div class="input-group input-group-sm">
																<div class="input-group input-group-sm">
																	<span class="input-group-btn">
																		<a href="<?= base_url("requisition/resetrqn/" . $csropen_data['CSRUNIQ'] . '/' . $post_stat) ?>" class="btn btn-flat bg-red btn-sm pull-right">
																			<i class='fa fa-eraser'></i>Clear Requisition Data
																		</a>
																	</span>
																</div>

															</div>
														</div>


													</td>
												</tr>
												<tr>
													<td width="300">Requisition No. </td>
													<td width="1">:</td>
													<td>
														<div class="form-group">
															<div class="input-group input-group-sm">
																<div class="input-group input-group-sm">
																	<input type="text" class="form-control input-sm required" id="rqnnumber_all" name="rqnnumber_all" placeholder="Select Requisition to All S/O Item" value="<?php if (!empty(session()->get('rqnnumber_all'))) {
																																																									echo session()->get('rqnnumber_all');
																																																								} else {
																																																									echo $rqn_number;
																																																								}
																																																								?>" size="50" readonly />

																</div>

															</div>
														</div>


													</td>
												</tr>
												<tr>
													<td width="300">Requisition Date </td>
													<td width="1">:</td>
													<td>
														<div class='form-group'>
															<div class="input-group input-group-sm date">
																<div class="input-group-addon">
																	<i class="fa fa-calendar"></i>
																</div>
																<input type="text" class="form-control input-sm required" id="rqndate_disp_all" name="rqndate_disp_all" placeholder="" value="<?php if (!empty(session()->get('rqndate_disp_all'))) {
																																																	echo session()->get('rqndate_disp_all');
																																																} else {
																																																	echo $rqn_date_disp;
																																																}
																																																?>" readonly />
																<input type="hidden" id="rqndate_all" name="rqndate_all" value="<?php if (!empty(session()->get('rqndate_all'))) {
																																	echo session()->get('rqndate_all');
																																} else {
																																	echo $rqn_date;
																																}
																																?>">
															</div>
														</div>

													</td>
												</tr>


											</tbody>
										</table>
									</div>
									<div class='col-sm-12'>
										<?= validation_list_errors() ?>
									</div>
									<div class='box-footer'>
										<input type="hidden" id="csruniq" name="csruniq" value="<?= $csruniq ?>">
										<input type="hidden" id="rqnuniq" name="rqnuniq" value="<?= $id_pr ?>">
										<input type="hidden" id="post_stat" name="post_stat" value="<?= $post_stat ?>">
										<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm'><i class='fa fa-times'></i> Cancel</button>
										<button type='submit' name="form_save" value="so_save" class='btn btn-social btn-flat btn-info btn-sm pull-right'><i class='fa fa-check'></i> <?= $button ?></button>

									</div>
								</div>


							</div>
						</div>
						</form>
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