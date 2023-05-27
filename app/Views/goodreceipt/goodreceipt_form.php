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
							<a href="<?= base_url(); ?>goodreceipt" title="Reset Data" onclick="<?php echo base_url(); ?>" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class='fa fa-refresh'></i> Reset Good Receipt Form</a>
						</div>

						<div class="box-body">

							<div class="row">
								<div class="col-sm-12">
									<div class="col-sm-6">
										<div class="box box-success">
											<div class="box-body">
												<div class="table-responsive">
													<table id="tabel" class="table table-bordered dataTable table-hover">
														<thead class="bg-gray disabled color-palette">
															<tr>
																<th nowrap>Contract </th>
																<th>:</th>
																<th nowrap></th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td nowrap> CRM Req. Date</td>
																<td>: </td>
																<td nowrap><?= $req_date ?></td>
															</tr>
															<tr>
																<td nowrap>Contract/Project/CRM No. </td>
																<td>: </td>
																<td nowrap><?= $ct_no . '/' . $prj_no . '/' . $crm_no ?></td>
															</tr>
															<tr>
																<td nowrap style="vertical-align: top;">Contract Desc. / Customer</td>
																<td style="vertical-align: top;">: </td>
																<td nowrap><?= $ct_desc ?><br><small>(<?= $ct_custname ?>)</small></td>
															</tr>
															<tr>
																<td nowrap style="vertical-align: top;">Item</td>
																<td style="vertical-align: top;">: </td>
																<td nowrap><?= $csr_item_no . '/' . $csr_material_no . '-' . $csr_item_desc ?><br>
																	<?= 'Type : ' . $csr_srvtype . '/ Qty : ' . number_format($csr_qty, 0, ",", ".") . ' (' . trim($csr_uom) . ')' ?>
																</td>
															</tr>

														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
									<div class="col-sm-6">
										<div class="box box-success">
											<div class="box-body">
												<div class="table-responsive">
													<table id="tabel" class="table table-bordered dataTable table-hover">
														<thead class="bg-gray disabled color-palette">
															<tr>
																<th nowrap>Purchase Order </th>
																<th>:</th>
																<th nowrap></th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td nowrap> PO Date</td>
																<td>: </td>
																<td nowrap><?= $po_date ?></td>
															</tr>
															<tr>
																<td nowrap>PO Number. </td>
																<td>: </td>
																<td nowrap><?= $po_number ?></td>
															</tr>
															<tr>
																<td nowrap>Vendor Shipment Status </td>
																<td>: </td>
																<td nowrap><?= $vendorshi_status ?></td>
															</tr>
															<tr>
																<td nowrap>Est. Arrival Date </td>
																<td>: </td>
																<td nowrap><?= $etaport_date ?></td>
															</tr>
														</tbody>
													</table>
												</div>
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
													<span class="input-group-addon input-sm"><a href="<?= base_url(); ?>goodreceipt/form_select_goodreceipt/<?= $po_uniq ?>" data-remote="false" data-toggle="modal" data-target="#modalBox"><i class="fa fa-search"></i></a></span>
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
										<div class='col-sm-2'>
											<div class='form-group'>
												<label for="rcp_item_no">Item No.</label>
												<input type="text" maxlength="10" size="10" class="form-control input-sm required" name="rcp_item_no" id="rcp_item_no" placeholder="Item No." value="<?= $item_no ?>" readonly>
											</div>
										</div>
										<div class='col-sm-2'>
											<div class='form-group'>
												<label for="rcp_material_no">Material No.</label>
												<input type="text" maxlength="10" size="10" class="form-control input-sm required" name="rcp_material_no" id="rcp_material_no" placeholder="Material No." value="<?= $material_no ?>" readonly>
											</div>
										</div>
										<div class='col-sm-4'>
											<div class='form-group'>
												<label for="item_desc">Item Desc</label>

												<input type="text" class="form-control input-sm required" id="item_desc" name="item_desc" placeholder="Item Description" value="<?= $item_desc ?>" readonly />

											</div>
										</div>
										<div class='col-sm-2'>
											<div class='form-group'>
												<label for="qty_rcp">Quantity</label>
												<input type="text" class="form-control input-sm required" id="qty_rcp" name="qty_rcp" placeholder="" value="<?php if ($qty_rcp <> 0) {
																																								echo number_format($qty_rcp, 0, ",", ".");
																																							} ?>" />
											</div>
										</div>
										<div class='col-sm-2'>
											<div class='form-group'>
												<label for="rcp_unit">Uom</label>
												<input type="text" class="form-control input-sm required" id="rcp_unit" name="rcp_unit" placeholder="" value="<?= $rcp_unit ?>" readonly />
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
					<input type="hidden" id="qty" name="qty" value="<?= $qty_rcp ?>">
					<input type="hidden" id="csr_qty" name="csr_qty" value="<?= $csr_qty ?>">
					<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm'><i class='fa fa-times'></i> Cancel</button>
					<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right'><i class='fa fa-check'></i> Save</button>
				</div>
			</div>


</div>
</form>
</div>
</div>
</div>
</section>
</div>
<?php //$this->load->view('global/confirm_delete');
?>