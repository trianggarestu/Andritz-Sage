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
		<h1>Delivery Orders Form</h1>
		<ol class="breadcrumb">
			<li><a href="<?= base_url('') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="#"> Delivery Orders</a></li>
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
								<i class="fa fa-arrow-circle-left "></i>Back to Waiting List Delivery Orders
							</a>
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
																<td nowrap><strong><a href="#"><?= $ct_no . '/' . $prj_no . '/' . $crm_no ?></a></strong></td>
															</tr>
															<tr>
																<td nowrap style="vertical-align: top;">Contract Desc. / Customer</td>
																<td style="vertical-align: top;">: </td>
																<td nowrap><?= $ct_desc ?><br><strong><small>(<?= $ct_custname ?>)</small></strong></td>
															</tr>
															<tr>
																<td nowrap style="vertical-align: top;">Item No / Material No<br>Type<br>Item Desc</td>
																<td style="vertical-align: top;">: </td>
																<td nowrap><?= $csr_item_no . '/' . $csr_material_no ?><br>
																	<?= $csr_srvtype ?><br>
																	<strong><?= $csr_item_desc ?></strong>
																</td>
															</tr>
															<tr>
																<td nowrap style="vertical-align: top;">QTY Orders</td>
																<td style="vertical-align: top;">: </td>
																<td nowrap>
																	<?= number_format($csr_qty, 0, ",", ".") . ' (' . trim($csr_uom) . ')' ?>
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
																<th nowrap>Good Receipt </th>
																<th>:</th>
																<th nowrap></th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td nowrap> P/O Number - Date</td>
																<td>: </td>
																<td nowrap><strong><a href="#"><?= $po_number ?></a></strong> - <?= $po_date ?></td>
															</tr>
															<tr>
																<td nowrap>Receipt Number - Date </td>
																<td>: </td>
																<td nowrap><strong><a href="#"><?= $rcp_number ?></a></strong> - <?= $rcp_date ?></td>
															</tr>
															<tr>
																<td nowrap>Description </td>
																<td>: </td>
																<td nowrap><?= $rcp_desc ?></td>
															</tr>
															<tr>
																<td nowrap style="vertical-align: top;">Item No / Material No<br>Type<br>Item Desc</td>
																<td style="vertical-align: top;">: </td>
																<td nowrap><?= $csr_item_no . '/' . $csr_material_no ?><br>
																	<?= $csr_srvtype ?><br>
																	<strong><?= $csr_item_desc ?></strong>
																</td>
															</tr>
															<tr>
																<td nowrap style="vertical-align: top;">QTY Receipt</td>
																<td style="vertical-align: top;">: </td>
																<td nowrap>
																	<?= number_format($rcp_qty, 0, ",", ".") . ' (' . trim($rcp_unit) . ') / Status : ' ?>
																	<strong><?php
																			switch ($gr_status) {
																				case "0":
																					echo "Partial";
																					break;
																				case "1":
																					echo "Completed";
																					break;
																				default:
																					echo "";
																			} ?></strong>
																</td>
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
												<label class="text-right"><strong>Delivery Orders :</strong></label>
											</div>
										</div>
									</div>
									<div class="row">
										<div class='col-sm-2'>
											<div class='form-group'>
												<label for="sage_shi_number">Shipment Number <code> (choose)</code></label>
												<div class="input-group">
													<span class="input-group-addon input-sm"><a href="<?= base_url(); ?>deliveryorders/form_select_sage_shipment/<?= $rcp_uniq ?>" data-remote="false" data-toggle="modal" data-target="#modalBox"><i class="fa fa-search"></i></a></span>
													<input type="text" maxlength="22" size="22" class="form-control input-sm required" name="sage_shi_number" id="sage_shi_number" placeholder="Shipment Number" value="<?= $sage_shi_number ?>" readonly>
												</div></input>
											</div>
										</div>
										<div class='col-sm-2'>
											<div class='form-group'>
												<label for="shi_date">Delivery Date</label>
												<div class="input-group input-group-sm date">
													<div class="input-group-addon">
														<i class="fa fa-calendar"></i>
													</div>
													<input class="form-control input-sm required" id="shi_date" name="shi_date" type="text" value="<?= $shi_date ?>" readonly>
												</div>
											</div>
										</div>
										<div class='col-sm-2'>
											<div class='form-group'>
												<label for="cust_rcp_date">Cust. Received Date</label>
												<div class="input-group input-group-sm date">
													<div class="input-group-addon">
														<i class="fa fa-calendar"></i>
													</div>
													<input class="datepicker form-control input-sm required" id="cust_rcp_date" name="cust_rcp_date" type="text" value="<?= $cust_rcp_date ?>">
												</div>
											</div>
										</div>
										<div class='col-sm-6'>
											<div class='form-group'>
												<label for="shi_number">DN Number</label>
												<input type="text" class="form-control input-sm required" id="shi_number" name="shi_number" placeholder="DN Number" value="<?= $shi_number ?>" />
											</div>
										</div>
									</div>
									<div class="row">
										<div class='col-sm-2'>
											<div class='form-group'>
												<label for="shi_itemno">Item No</label>
												<input type="text" class="form-control input-sm required" id="shi_itemno" name="shi_itemno" placeholder="Item No" value="<?= $shi_itemno ?>" readonly />
											</div>
										</div>
										<div class='col-sm-2'>
											<div class='form-group'>
												<label for="shi_materialno">Material No</label>
												<input type="text" class="form-control input-sm required" id="shi_materialno" name="shi_materialno" placeholder="Material No" value="<?= $shi_materialno ?>" readonly />
											</div>
										</div>
										<div class='col-sm-3'>
											<div class='form-group'>
												<label for="shi_itemdesc">Item Desc.</label>
												<input type="text" class="form-control input-sm required" id="shi_itemdesc" name="shi_itemdesc" placeholder="Item Description" value="<?= $shi_itemdesc ?>" readonly />
											</div>
										</div>
										<div class='col-sm-2'>
											<div class='form-group'>
												<label for="shi_qty">Delivered</label>
												<input type="text" class="form-control input-sm required" id="shi_qty" name="shi_qty" placeholder="Qty Shipment" value="<?= number_format($shi_qty, 0, ",", ".") ?>" />
											</div>
										</div>

										<div class='col-sm-2'>
											<div class='form-group'>
												<label for="shi_qty_outs">Del. Outstanding</label>
												<input type="text" maxlength="10" size="10" class="form-control input-sm" name="shi_qty_outs" id="shi_qty_outs" placeholder="Qty Oustanding" value="<?= number_format($shi_qty_outs, 0, ",", ".") ?>" readonly>
											</div>
										</div>

										<div class='col-sm-1'>
											<div class='form-group'>
												<label for="shi_unit">Uom</label>
												<input type="text" class="form-control input-sm required" id="shi_unit" name="shi_unit" placeholder="Uom.." value="<?= $shi_unit ?>" readonly />
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
					<input type="hidden" id="rcp_uniq" name="rcp_uniq" value="<?= $rcp_uniq ?>">
					<input type="hidden" id="doc_uniq" name="doc_uniq" value="<?= $doc_uniq ?>">
					<input type="hidden" id="shi_uniq" name="shi_uniq" value="<?= $shi_uniq ?>">
					<input type="hidden" id="csr_qty" name="csr_qty" value="<?= $csr_qty ?>">
					<input type="hidden" id="csr_contract" name="csr_contract" value="<?= $ct_no ?>">
					<input type="hidden" id="csr_project" name="csr_project" value="<?= $prj_no ?>">
					<input type="hidden" id="csr_custno" name="csr_custno" value="<?= $ct_custno ?>">

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