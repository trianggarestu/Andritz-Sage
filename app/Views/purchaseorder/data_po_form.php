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
		<h1>Purchase Orders Form</h1>
		<ol class="breadcrumb">
			<li><a href="<?= base_url('administration') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= base_url('purchaseorder') ?>">Purchase Orders</li></a>
			<li class="active">P/O Entry</li>
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
							<?php if ($rqnopen_data['POSTINGSTAT'] == 1 and $rqnopen_data['OFFLINESTAT'] == 0) :
							?>
								<a href="<?= base_url('purchaseorderlist') ?>" title="Back to Purchase Orders List" class="btn btn-social btn-flat bg-aqua btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Back to Purchase Orders List</a>
							<?php endif; ?>

							<?php if ($rqnopen_data['POSTINGSTAT'] == 0 or ($rqnopen_data['POSTINGSTAT'] == 1 and $rqnopen_data['OFFLINESTAT'] == 1)) :
							?>
								<a href="<?= base_url('purchaseorder') ?>" title="Back to Purchase Orders" class="btn btn-social btn-flat bg-aqua btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Back to Purchase Orders Open</a>
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
																				<td><strong><?= $rqnopen_data['CONTRACT']; ?></strong></td>
																			</tr>
																			<tr>
																				<td width="150">Contract Description </td>
																				<td width="1">:</td>
																				<td><strong><?= $rqnopen_data['CTDESC']; ?></strong></td>
																			</tr>
																			<tr>
																				<td width="150">Customer </td>
																				<td width="1">:</td>
																				<td><strong>
																						<?= $rqnopen_data['CUSTOMER'] . ' - ' . $rqnopen_data['NAMECUST']; ?>
																					</strong>

																					<br>
																					<small>
																						< <?= $rqnopen_data['EMAIL1CUST']; ?>>
																					</small>

																				</td>
																			</tr>
																			<tr>
																				<td width="150">Sales Person </td>
																				<td width="1">:</td>
																				<td><strong><?= $rqnopen_data['MANAGER'] . '(' . $rqnopen_data['SALESNAME'] . ')'; ?></strong></td>
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
																				<td><strong><?= $rqnopen_data['PROJECT']; ?></strong></td>
																			</tr>
																			<tr>
																				<td width="150">Project Description </td>
																				<td width="1">:</td>
																				<td><strong><?= $rqnopen_data['PRJDESC']; ?></strong></td>
																			</tr>
																			<tr>
																				<td width="150">PO Number Customer </td>
																				<td width="1">:</td>
																				<td><strong><?= $rqnopen_data['PONUMBERCUST']; ?></strong></td>
																			</tr>
																			<tr>
																				<td width="150">PO Customer Date </td>
																				<td width="1">:</td>
																				<td><strong><?php
																							$dd = substr($rqnopen_data['PODATECUST'], 6, 2);
																							$mm = substr($rqnopen_data['PODATECUST'], 4, 2);
																							$yyyy = substr($rqnopen_data['PODATECUST'], 0, 4);
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
													<td colspan="3">
														<div class="row">
															<div class="col-md-6">
																<div class="box box-default">
																	<div class="box-header with-border">
																		<i class="fa fa-file-text"></i>

																		<h3 class="box-title">CRM</h3>
																	</div>
																	<!-- /.box-header -->
																	<div class="box-body">
																		<table class="table table-bordered table-striped table-hover">
																			<tr>
																				<td width="300">CRM Number </td>
																				<td width="1">:</td>
																				<td><strong><?= $rqnopen_data['CRMNO']; ?></strong></td>
																			</tr>
																			<tr>
																				<td width="300">Request Date </td>
																				<td width="1">:</td>
																				<td><strong><?php

																							$dd = substr($rqnopen_data['CRMREQDATE'], 6, 2);
																							$mm = substr($rqnopen_data['CRMREQDATE'], 4, 2);
																							$yyyy = substr($rqnopen_data['CRMREQDATE'], 0, 4);
																							$reqdate = $mm . '/' . $dd . '/' . $yyyy;
																							echo $reqdate; ?></strong></td>
																			</tr>
																			<tr>
																				<td width="300">Order Description </td>
																				<td width="1">:</td>
																				<td><strong><?= $rqnopen_data['ORDERDESC']; ?></strong></td>
																			</tr>
																			<tr>
																				<td width="300">Remarks </td>
																				<td width="1">:</td>
																				<td><strong><?= $rqnopen_data['CRMREMARKS']; ?></strong></td>
																			</tr>
																			<tr>
																				<td width="150">S/O Status </td>
																				<td width="1">:</td>
																				<td><strong><?php $postingstat = $rqnopen_data['POSTINGSTAT'];
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
																		</table>
																	</div>
																</div>
															</div>
															<div class="col-md-6">
																<div class="box box-default">
																	<div class="box-header with-border">
																		<i class="fa fa-file-text"></i>

																		<h3 class="box-title">Requisition</h3>
																	</div>
																	<!-- /.box-header -->
																	<div class="box-body">
																		<table class="table table-bordered table-striped table-hover">
																			<tr>
																				<td width="150">Requisition No. </td>
																				<td width="1">:</td>
																				<td><strong><?= $rqnopen_data['RQNNUMBER']; ?></strong></td>
																			</tr>
																			<tr>
																				<td width="150">Requisition Date </td>
																				<td width="1">:</td>
																				<td><strong><?php

																							$dd = substr($rqnopen_data['RQNDATE'], 6, 2);
																							$mm = substr($rqnopen_data['RQNDATE'], 4, 2);
																							$yyyy = substr($rqnopen_data['RQNDATE'], 0, 4);
																							$rqndate = $mm . '/' . $dd . '/' . $yyyy;
																							echo $rqndate; ?></strong></td>
																			</tr>
																			<tr>
																				<td width="150"></td>
																				<td width="1"></td>
																				<td><strong><?php $postingstat = $rqnopen_data['RQNPOSTINGSTAT'];
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
																				<td width="150"></td>
																				<td width="1"></td>
																				<td><strong></strong></td>
																			</tr>
																		</table>
																	</div>
																</div>
															</div>
														</div>
													</td>
												</tr>
												<tr>
													<td colspan="3" class="subtitle_head"><strong>SELECT PURCHASE ORDERS</strong></td>
												</tr>
												<tr>
													<td width="150">Select P/O No. </td>
													<td width="1">:</td>
													<td>
														<div class="form-group">
															<div class="input-group input-group-sm">

																<span class="input-group-btn">
																	<a href="<?= base_url('purchaseorder/form_select_po/' . $rqnopen_data['RQNUNIQ'] . '/' . trim($rqnopen_data['RQNNUMBER']) . '/' . $post_stat); ?>" data-remote="false" data-toggle="modal" data-target="#modalBox" class="btn btn-flat bg-navy btn-sm pull-right">
																		<i class='fa fa-search'></i> Input Purchase Orders Info.
																	</a>
																</span>

															</div>
														</div>

														<div class="form-group">
															<div class="input-group input-group-sm">
																<div class="input-group input-group-sm">
																	<span class="input-group-btn">
																		<a href="<?= base_url("purchaseorder/resetpo/" . $rqnopen_data['RQNUNIQ'] . '/' . $post_stat . '/0') ?>" class="btn btn-flat bg-red btn-sm pull-right">
																			<i class='fa fa-eraser'></i>Clear P/O Data
																		</a>
																	</span>
																</div>

															</div>
														</div>


													</td>
												</tr>
												<tr>
													<td colspan="3">
														<div class='col-sm-2'>
															<div class='form-group'>
																<label for="crm_no">P/O Number</label>
																<input type="text" class="form-control input-sm required" id="po_number" name="po_number" placeholder="P/O Number" value="<?php if (empty($po_number)) {
																																																echo session()->get('po_number');
																																															} else {
																																																echo $po_number;
																																															}  ?>" readonly />

															</div>
														</div>
														<div class='col-sm-2'>
															<div class='form-group'>
																<label for="req_date">P/O Date </label>
																<div class="input-group input-group-sm date">
																	<div class="input-group-addon">
																		<i class="fa fa-calendar"></i>
																	</div>
																	<input class="form-control input-sm required" id="po_date" name="po_date" type="text" value="<?php if (empty($po_date)) {
																																										echo session()->get('po_date');
																																									} else {
																																										echo $po_date;
																																									} ?>" readonly>
																</div>
															</div>
														</div>
														<div class='col-sm-2'>
															<div class='form-group'>
																<label for="req_date">ETD Date </label>
																<div class="input-group input-group-sm date">
																	<div class="input-group-addon">
																		<i class="fa fa-calendar"></i>
																	</div>
																	<input class="form-control input-sm required" id="etd_date" name="etd_date" type="text" value="<?php if (empty($etd_date)) {
																																										echo session()->get('etd_date');
																																									} else {
																																										echo $etd_date;
																																									}  ?>" readonly>
																</div>
															</div>
														</div>
														<div class='col-sm-2'>
															<div class='form-group'>
																<label for="req_date">Cargo Readiness Date </label>
																<div class="input-group input-group-sm date">
																	<div class="input-group-addon">
																		<i class="fa fa-calendar"></i>
																	</div>
																	<input class="form-control input-sm" id="cargoreadiness_date" name="cargoreadiness_date" type="text" value="<?php if (empty($cargoreadiness_date)) {
																																													echo session()->get('cargoreadiness_date');
																																												} else {
																																													echo $cargoreadiness_date;
																																												}  ?>" readonly>
																</div>
															</div>
														</div>
														<div class='col-sm-2'>
															<div class='form-group'>
																<label for="crm_no">Origin Country</label>
																<input type="text" class="form-control input-sm required" id="origin_country" name="origin_country" placeholder="Origin Country" value="<?php if (empty($origin_country)) {
																																																			echo session()->get('origin_country');
																																																		} else {
																																																			echo $origin_country;
																																																		} ?>" readonly />

															</div>
														</div>
														<div class='col-sm-2'>
															<div class='form-group'>
																<label for="crm_no">P/O Remarks</label>
																<input type="text" class="form-control input-sm required" id="po_remarks" name="po_remarks" placeholder="P/O Remarks" value="<?php if (empty($po_remarks)) {
																																																	echo session()->get('po_remarks');
																																																} else {
																																																	echo $po_remarks;
																																																} ?>" readonly />

															</div>
														</div>
													</td>

												</tr>

												<tr>
													<td colspan="3" class="subtitle_head"><strong>Item Parts / Services S/O</strong></td>
												</tr>
												<tr>
													<td colspan="3">
														<div class="col-sm-12">
															<code> { Delete Line / Detail Item S/O if you split P/O or Multi P/O to Vendor.}</code>
														</div>
													</td>
												<tr>

													<td colspan="3">
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
																		<th>Action</th>


																	</tr>
																</thead>
																<tbody>
																	<?php
																	$no = 0;
																	if (is_array($csrlforpo_edit_data) || is_object($csrlforpo_edit_data)) {
																		foreach ($csrlforpo_edit_data as $items) :
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
																				<td><?= $items['QTY']
																					?></td>
																				<td nowrap><?= $items['STOCKUNIT']
																							?></td>
																				<td nowrap>
																					<!--<a href="<?php //base_url("salesorder/form_update_item/" . $ct_no . "/" . $prj_no . "/" . $items['options']['so_service'] . "/" . $items['id'] . "/" . $items['options']['material_no'] . "/" . $items['qty'] . "/" . $items['options']['so_uom']) 
																									?>" class="btn bg-orange btn-flat btn-sm" title="Update Item" data-toggle="modal" data-target="#modalUpdateitem"><i class="fa fa-edit"></i></a>-->
																					<a href="" data-href="<?= base_url("purchaseorder/delete_item_poopen/" . $items['POUNIQ'] . "/" . $post_stat . "/" . $items['POLUNIQ'] . "/1") ?>" class="btn bg-maroon btn-flat btn-sm" title="Delete Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																				</td>

																			</tr>
																	<?php endforeach;
																	}
																	?>
																	<?php
																	$no = 0;

																	foreach ($csrlforpo_data as $items) :
																	?>
																		<tr>

																			<td class="text-center"><?= ++$no ?></td>
																			<td><?= $items['options']['so_service']
																				?></td>

																			<td><?= $items['id']
																				?></td>
																			<td><?= $items['options']['material_no']
																				?></td>
																			<td nowrap><?= $items['options']['itemdesc']
																						?></td>
																			<td><?= number_format($items['options']['so_qty'], 0, ",", ".")
																				?></td>
																			<td nowrap><?= $items['options']['so_uom']
																						?></td>
																			<td nowrap>

																				<!--<a href="<?php //base_url("salesorder/form_update_item/" . $ct_no . "/" . $prj_no . "/" . $items['options']['so_service'] . "/" . $items['id'] . "/" . $items['options']['material_no'] . "/" . $items['qty'] . "/" . $items['options']['so_uom']) 
																								?>" class="btn bg-orange btn-flat btn-sm" title="Update Item" data-toggle="modal" data-target="#modalUpdateitem"><i class="fa fa-edit"></i></a>-->
																				<a href="" data-href="<?= base_url("purchaseorder/delete_item_cart/" . $rqnuniq . "/" . $post_stat . "/" . $items['rowid'] . "/1") ?>" class="btn bg-maroon btn-flat btn-sm" title="Delete Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																			</td>
																		</tr>
																	<?php endforeach;

																	?>
																</tbody>
															</table>
														</div>
													</td>
												</tr>


											</tbody>
										</table>
									</div>
									<div class="row">
										<div class='col-sm-12'>
											<?= $cart->totalItems() ?>
											<?= validation_list_errors() ?>
										</div>
									</div>
									<div class='box-footer'>
										<input type="hidden" id="csruniq" name="csruniq" value="<?= $csruniq ?>">
										<input type="hidden" id="rqnuniq" name="rqnuniq" value="<?= $rqnuniq ?>">
										<input type="hidden" id="post_stat" name="post_stat" value="<?= $post_stat ?>">
										<input type="hidden" id="del_poline" name="del_poline" value="<?= $post_stat ?>">
										<input type="hidden" id="chk_item" name="chk_item" value="<?= $cart->totalItems() ?>">
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

<?php //$this->load->view('settings/modalbox/modal_confirm_delete');
?>

<?php echo view('settings/modalbox/modal_confirm_delete')
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