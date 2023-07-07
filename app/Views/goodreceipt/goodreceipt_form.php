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
		<h1>Good Receipt Form</h1>
		<ol class="breadcrumb">
			<li><a href="<?= base_url('') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="#"> Good Receipt</a></li>
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
							<a href="<?= base_url(); ?>goodreceipt" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Back to Waiting List Good Receipts">
								<i class="fa fa-arrow-circle-left "></i>Back to Waiting List Good Receipts
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

												</table>
											</div>
										</div>
									</div>

									<div class="col-sm-6">
										<div class="box box-default">
											<div class="box-header with-border">
												<i class="fa fa-file-text"></i>

												<h3 class="box-title">Purchase Orders</h3>
											</div>
											<!-- /.box-header -->
											<div class="box-body">
												<table class="table table-bordered table-striped table-hover">
													<tr>
														<td nowrap>P/O Number. </td>
														<td>: </td>
														<td nowrap><strong><a href="<?= base_url("administration/popostedview/" . $po_uniq) ?>" title="Click here for detail" target="_blank"><?= $po_number ?></a></strong></td>
													</tr>
													<tr>
														<td nowrap> P/O Date</td>
														<td>: </td>
														<td nowrap><?= $po_date ?></td>
													</tr>
													<tr>
														<td nowrap>Origin Country </td>
														<td>: </td>
														<td nowrap><?= $origin_country ?></td>
													</tr>
													<tr>
														<td nowrap>Vendor Shipment Status </td>
														<td>: </td>
														<td nowrap><?= $vendorshi_status ?></td>
													</tr>

												</table>
											</div>
										</div>

									</div>

									<div class="row">
										<div class="col-sm-12">
											<div class="form-group subtitle_head">
												<label class="text-right"><strong>Good Receipt :</strong></label>
											</div>
										</div>
									</div>
									<div class="row">
										<div class='col-sm-2'>
											<div class='form-group'>
												<label for="rcp_number">Receipt Number <code> (choose)</code></label>
												<div class="input-group">
													<span class="input-group-addon input-sm"><a href="<?= base_url('goodreceipt/form_select_goodreceipt/' . $po_uniq . '/' . $post_stat . '/' . $delgrline); ?>" data-remote="false" data-toggle="modal" data-target="#modalBox"><i class="fa fa-search"></i></a></span>
													<input type="text" maxlength="22" size="22" class="form-control input-sm required" name="rcp_number" id="rcp_number" placeholder="Receipt Number" value="<?= $rcp_number ?>" readonly>
												</div></input>
											</div>
										</div>
										<div class='col-sm-2'>
											<div class='form-group'>
												<label for="rcp_date">GR. Date</label>
												<div class="input-group input-group-sm date">
													<div class="input-group-addon">
														<i class="fa fa-calendar"></i>
													</div>
													<input class="form-control input-sm required" id="rcp_date" name="rcp_date" type="text" value="<?= $rcp_date ?>" readonly>
												</div>
											</div>
										</div>
										<div class='col-sm-4'>
											<div class='form-group'>
												<label for="vd_name">Vendor Name</label>
												<input type="text" class="form-control input-sm required" id="vd_name" name="vd_name" placeholder="Vendor Name" value="<?= $vd_name ?>" readonly />
											</div>
										</div>
										<div class='col-sm-4'>
											<div class='form-group'>
												<label for="rcp_desc">Description</label>
												<input type="text" class="form-control input-sm required" id="rcp_desc" name="rcp_desc" placeholder="Description" value="<?= $rcp_desc ?>" readonly />
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<div class="form-group subtitle_head">
												<label class="text-right"><strong>Item/Services :</strong></label>
											</div>
											<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
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
																		<th>Qty.</th>
																		<th>Uom</th>
																		<th class="padat">Action</th>
																	</tr>
																</thead>
																<tbody>
																	<?php
																	$no = 0;

																	foreach ($polforrcp_data as $items) :
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
																				<a href="<?= base_url('goodreceipt/form_update_item/' . $po_uniq . '/' . $post_stat . '/' . $items['rowid'] . '/' . $items['id'] . '/1') ?>" class="btn bg-orange btn-flat btn-sm" title="Update Item" data-toggle="modal" data-target="#modalBox"><i class="fa fa-edit"></i></a>
																				<a href="" data-href="<?= base_url("goodreceipt/delete_item_cart/" . $po_uniq . '/' . $post_stat . '/' . $items['rowid']  . '/1') ?>" class="btn bg-maroon btn-flat btn-sm" title="Delete Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																			</td>
																		</tr>
																	<?php endforeach;

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
					<input type="hidden" id="po_uniq" name="po_uniq" value="<?= $po_uniq ?>">
					<input type="hidden" id="rcp_uniq" name="rcp_uniq" value="<?= $rcpuniq ?>">
					<input type="hidden" id="po_number" name="po_number" value="<?= $po_number ?>">
					<input type="hidden" id="rcph_seq" name="rcph_seq" value="<?= $rcphseq ?>">
					<input type="hidden" id="post_stat" name="post_stat" value="<?= $post_stat ?>">
					<input type="hidden" id="delgrline" name="delgrline" value="<?= $delgrline ?>">
					<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm'><i class='fa fa-times'></i> Cancel</button>
					<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right'><i class='fa fa-check'></i> <?= $button_text ?></button>
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