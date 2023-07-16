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
		<h1>Delivery Note Form</h1>
		<ol class="breadcrumb">
			<li><a href="<?= base_url('') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= base_url('delveryorders') ?>"> Delivery Note</a></li>
			<li class="active">Form</li>
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
		<form action="<?= $form_action;
						?>" method="post" id="validasi">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
						<div class="box-header with-border">
							<a href="<?= base_url(); ?>deliveryorders" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Back to Waiting List Good Receipts">
								<i class="fa fa-arrow-circle-left "></i>Back to Waiting List Delivery Note
							</a>
						</div>

						<div class="box-body">

							<div class="row">
								<div class="col-sm-12">
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
														<td width=" 150">Contract / Project / CRM No.
														</td>
														<td width="1">:</td>
														<td><strong><a href="<?= base_url("administration/csrpostedview/" . $csr_uniq) ?>" title="Click here for detail" target="_blank"><?= $ct_no ?></a><?= '/' . $prj_no . '/' . $crm_no ?></strong></td>
													</tr>
													<tr>
														<td width="150">Contract Description </td>
														<td width="1">:</td>
														<td><strong><?= $ct_desc ?></strong></td>
													</tr>
													<tr>
														<td width="150">Customer </td>
														<td width="1">:</td>
														<td><strong>
																<?= $ct_custname ?>
															</strong><br>
															<small>(<?= $ct_email1 ?>)</small>


														</td>
													</tr>
													<tr>
														<td width="150">Sales Person </td>
														<td width="1">:</td>
														<td><strong><?= $manager . '(' . trim($salesperson) . ')'; ?></strong></td>
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
														<td><strong><?= $prj_no; ?></strong></td>
													</tr>
													<tr>
														<td width="150">Project Description </td>
														<td width="1">:</td>
														<td><strong><?= $prj_desc; ?></strong></td>
													</tr>
													<tr>
														<td width="150">PO Number Customer </td>
														<td width="1">:</td>
														<td><strong><?= $ponumbercust; ?></strong></td>
													</tr>
													<tr>
														<td width="150">PO Customer Date </td>
														<td width="1">:</td>
														<td><strong><?= $pocustdate; ?></strong></td>
													</tr>
												</table>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="form-group subtitle_head">
										<label class="text-right"><strong>Delivery Note :</strong></label>
									</div>
								</div>
							</div>
							<div class="row">
								<div class='col-sm-2'>
									<div class='form-group'>
										<label for="shi_number">Ref. Delivery Number <code> (choose)</code></label>
										<div class="input-group">
											<span class="input-group-addon input-sm"><a href="<?= base_url('deliveryorders/form_select_sage_shipment/' . $csr_uniq . '/' . $post_stat . '/' . $uf_pocustdate); ?>" data-remote="false" data-toggle="modal" data-target="#modalBox"><i class="fa fa-search"></i></a></span>
											<input type="text" maxlength="22" size="22" class="form-control input-sm required" name="shi_number" id="shi_number" placeholder="D/N Number" value="<?= session()->get('sage_shidoc') ?>" readonly>
										</div></input>
									</div>
								</div>
								<div class='col-sm-2'>
									<div class='form-group'>
										<label for="dn_number">D/N Number</label>
										<input type="text" class="form-control input-sm required" id="dn_number" name="dn_number" value="<?= session()->get('sage_dnnumber') ?>" readonly />
									</div>
								</div>
								<div class='col-sm-2'>
									<div class='form-group'>
										<label for="shi_date">Delivery. Date</label>
										<div class="input-group input-group-sm date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input class="form-control input-sm required" id="shi_date" name="shi_date" type="text" value="<?= session()->get('sage_shidate') ?>" readonly>
										</div>
									</div>
								</div>

								<div class='col-sm-2'>
									<div class='form-group'>
										<label for="received_date">Received Date</label>
										<div class="input-group input-group-sm date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input class="form-control input-sm required" id="received_date" name="received_date" type="text" value="<?= session()->get('sage_rcpdate') ?>" readonly>
										</div>
									</div>
								</div>
								<div class='col-sm-2'>
									<div class='form-group'>
										<label for="shi_ref">Reference</label>
										<input type="text" class="form-control input-sm" id="shi_ref" name="shi_ref" placeholder="Ref." value="<?= session()->get('sage_shiref') ?>" readonly />
									</div>
								</div>
								<div class='col-sm-2'>
									<div class='form-group'>
										<label for="rcp_desc">Description</label>
										<input type="text" class="form-control input-sm" id="shi_desc" name="shi_desc" placeholder="Description" value="<?= session()->get('sage_shidesc') ?>" readonly />
									</div>
								</div>
							</div>



							<div class="row">
								<div class="col-sm-12">
									<div class="form-group subtitle_head">
										<label class="text-right"><strong>Item/Services :</strong></label>
									</div>
								</div>
							</div>
							<div class="row">

								<div class="col-sm-12">
									<div class="table-responsive">
										<table class="table table-bordered dataTable table-hover nowrap">
											<thead class="bg-gray disabled color-palette">
												<tr>

													<th class="padat">No</th>

													<th>Type</th>
													<th>Inventory No.</th>
													<th>Material No.</th>
													<th>Item Desc.</th>
													<th>P/O Number</th>
													<th>Original <br>Qty</th>
													<th>G/R Qty</th>
													<th>D/N Qty <br>(Outstanding)</th>
													<th>D/N Qty</th>
													<th>Uom</th>
													<th class="padat">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$no = 0;
												$total_shi = 0;

												foreach ($rcplforshi_data as $items) :
													$total_shi += $items['options']['shi_qty'];
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
														<td nowrap>
															<a href="<?= base_url("administration/popostedview/" . $items['options']['pouniq']) ?>" title="GR View" target="_blank">
																<?= $items['options']['ponumber'] ?>
															</a>
														</td>
														<td><?= number_format($items['options']['so_qty'], 0, ",", ".")
															?></td>
														<td><?= number_format($items['options']['gr_qty'], 0, ",", ".")
															?></td>
														<td><?= number_format($items['options']['shi_qty_outs'], 0, ",", ".")
															?></td>
														<td><?= number_format($items['options']['shi_qty'], 0, ",", ".")
															?></td>
														<td nowrap><?= $items['options']['so_uom']
																	?></td>
														<td nowrap>
															<a href="<?= base_url('deliveryorders/form_update_item/' . $csr_uniq . '/' . $post_stat . '/' . $items['rowid'] . '/' . $items['id'] . '/1') ?>" class="btn bg-orange btn-flat btn-sm" title="Update Item" data-toggle="modal" data-target="#modalBox"><i class="fa fa-edit"></i></a>
															<a href="#" data-href="<?= base_url("deliveryorders/delete_item_cart/" . $csr_uniq . '/' . $post_stat . '/' . $items['rowid']  . '/1') ?>" class="btn bg-maroon btn-flat btn-sm" title="Delete Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
														</td>

													</tr>

												<?php

												endforeach;

												?>


											</tbody>
										</table>
									</div>
								</div>

							</div>
						</div>
					</div>

				</div>

			</div>





			<div class="row">
				<div class='col-sm-12'>
					<?= validation_list_errors() ?>
				</div>
			</div>
</div>

</div>




</div>
<div class='box-footer'>
	<div class='col-xs-12'>
		<input type="hidden" id="csr_uniq" name="csr_uniq" value="<?= $csr_uniq ?>">
		<input type="hidden" id="cust_no" name="cust_no" value="<?= $ct_custno ?>">
		<input type="hidden" id="post_stat" name="post_stat" value="<?= $post_stat ?>">
		<input type="hidden" id="delshiline" name="delshiline" value="<?= $delshiline ?>">
		<input type="hidden" id="shi_total" name="shi_total" value="<?= $total_shi ?>">
		<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm'><i class='fa fa-times'></i> Cancel</button>
		<button type='submit' id="posting" class='btn btn-social btn-flat btn-info btn-sm pull-right'><i class='fa fa-check'></i> <?= $button_text ?></button>
	</div>
</div>


</div>
</form>
</div>
</div>
</div>
</section>
</div>
<?php echo view('settings/modalbox/modal_confirm_delete')
?>