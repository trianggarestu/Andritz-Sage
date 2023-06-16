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
		<h1>Sales Orders Form</h1>
		<ol class="breadcrumb">
			<li><a href="<?= base_url('') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="#"> Sales Order</a></li>
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
							<a href="<?= base_url(); ?>salesorderopen" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Back to Sales Order Open">
								<i class="fa fa-arrow-circle-left "></i>Back to Sales Order Open
							</a>
							<a href="<?= base_url(); ?>salesorder" title="Reset Data" onclick="<?php echo base_url(); ?>" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class='fa fa-refresh'></i> Reset Sales Order Form</a>
						</div>

						<div class="box-body">
							<div class="row">
								<?php if (!empty(session()->getFlashdata('messagesuccess'))) { ?>
									<div class="alert alert-success">
										<?php echo session()->getFlashdata('messagesuccess'); ?>
									</div>
								<?php } else if (!empty(session()->getFlashdata('messagefailed'))) { ?>
									<div class="alert alert-error">
										<?php echo session()->getFlashdata('messagefailed'); ?>
									</div>
								<?php } ?>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="form-group subtitle_head">
										<label class="text-right"><strong>CONTRACT :</strong></label>
									</div>
								</div>

								<div class='col-sm-3'>
									<div class='form-group'>
										<label for="ct_no">Contract </label>
										<div class="input-group">
											<span class="input-group-addon input-sm"><a href="<?= base_url(); ?>salesorder/form_select_contractopen/" data-remote="false" data-toggle="modal" data-target="#modalBox"><i class="fa fa-search"></i></a></span>
											<input type="text" maxlength="10" size="10" class="form-control input-sm required" name="ct_no" id="ct_no" placeholder="Contract Number" value="<?= $ct_no ?>" readonly>


										</div></input>
									</div>
								</div>
								<div class='col-sm-6'>
									<div class='form-group'>
										<label for="ct_desc">Contract Description </label>
										<input type="text" class="form-control input-sm required" id="ct_desc" name="ct_desc" placeholder="" value="<?= $ct_desc; ?>" readonly />
									</div>
								</div>

								<div class='col-sm-3'>
									<div class="form-group">
										<label for="ct_salesperson">Sales Person </label>
										<div class="input-group input-group-sm">
											<input type="text" class="form-control input-sm required" id="ct_salesperson" name="ct_salesperson" placeholder="" value="<?php if (empty($ct_staffcode)) {
																																											echo session()->get('salesman');
																																										} else {
																																											echo $ct_salesperson;
																																										}  ?>" readonly />

											<span class="input-group-btn">
												<a href="<?= base_url('salesorder/form_select_salesman/' . $ct_no); ?>" data-remote="false" data-toggle="modal" data-target="#modalBox" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block <?php if (!empty($ct_staffcode) or empty($ct_no)) : echo 'disabled';
																																																																																endif; ?>">
													Choose !
												</a>
											</span>
										</div>

										<input type="hidden" id="ct_manager" name="ct_manager" value="<?php if (empty($ct_staffcode)) {
																											echo session()->get('manager');
																										} else {
																											echo $ct_manager;
																										}  ?>">
									</div>
								</div>

							</div>
							<div class="row">
								<div class='col-sm-3'>
									<div class="form-group">
										<label for="ct_custno">Customer No. </label>
										<input type="text" class="form-control input-sm required" id="ct_custno" name="ct_custno" placeholder="" value="<?= $ct_custno; ?>" readonly />
									</div>
								</div>

								<div class='col-sm-6'>
									<div class="form-group">
										<label for="ct_namecust">Customer Name </label>
										<input type="text" class="form-control input-sm required" id="ct_namecust" name="ct_namecust" placeholder="" value="<?= $ct_custname; ?>" readonly />
									</div>
								</div>
								<div class='col-sm-3'>
									<div class="form-group">
										<label for="ct_email">Customer Email</label>
										<div class="input-group input-group-sm">
											<input type="text" class="form-control input-sm required" id="ct_email" name="ct_email" placeholder="" value="<?php if (empty($ct_email)) {
																																								echo session()->get('cust_email');
																																							} else {
																																								echo $ct_email;
																																							}  ?>" readonly />
											<span class="input-group-btn">
												<a href="<?= base_url('salesorder/form_input_email/' . $ct_no); ?>" data-remote="false" data-toggle="modal" data-target="#modalBox" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block <?php if (!empty($ct_email) or empty($ct_no)) : echo 'disabled';
																																																																															endif; ?>">
													Input !
												</a>
											</span>
										</div>

										<input type="hidden" id="ct_manager" name="ct_manager" value="<?= $ct_manager ?>">
									</div>
								</div>

							</div>
							<div class="row">

								<div class='col-sm-3'>
									<div class='form-group'>
										<label for="prj_no">Project </label>
										<div class="input-group">
											<span class="input-group-addon input-sm">
												<a href="<?= base_url() . 'salesorder/form_select_project_by_contract/' . $ct_no; ?>/" data-remote="false" data-toggle="modal" data-target="#modalBox"><i class=" fa fa-search"></i></a>
											</span>
											<input type="text" maxlength="10" size="10" class="form-control input-sm required" id="prj_no" name="prj_no" placeholder="Project Number" value="<?= $prj_no; ?>" readonly>
										</div></input>
									</div>
								</div>
								<div class='col-sm-4'>
									<div class='form-group'>
										<label for="prj_desc">Description </label>
										<input type="text" class="form-control input-sm required" id="prj_desc" name="prj_desc" placeholder="" value="<?= $prj_desc; ?>" readonly />
									</div>
								</div>
								<div class='col-sm-2'>
									<div class='form-group'>
										<label for="po_cust">PO Customer <code> (auto filled) </code> </label>
										<input type="text" class="form-control input-sm required" id="po_cust" name="po_cust" placeholder="" value="<?= $po_cust; ?>" <?php if ($po_cust == '') {
																																											echo '';
																																										} else {
																																											echo 'readonly';
																																										} ?> />
									</div>
								</div>
								<div class='col-sm-3'>
									<div class='form-group'>
										<label for="prj_startdate">PO Date <code> (auto filled) </code> </label>
										<div class="input-group input-group-sm date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input type="text" class="form-control input-sm required" id="prj_startdate" name="prj_startdate" placeholder="" value="<?= $prj_startdate; ?>" readonly />
										</div>
									</div>
								</div>

							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="form-group subtitle_head">
										<label class="text-right"><strong>CRM :</strong></label>
									</div>
								</div>
								<div class='col-sm-2'>
									<div class='form-group'>
										<label for="crm_no">CRM Number<code> (manual input) </code> </label>
										<input type="text" class="form-control input-sm required" id="crm_no" name="crm_no" placeholder="input here.." value="<?= $crm_no ?>" />

									</div>
								</div>
								<div class='col-sm-2'>

									<div class='form-group'>
										<label for="req_date">Req. Date <code> (manual input) </code> </label>
										<div class="input-group input-group-sm date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input class="datepicker form-control input-sm required" id="req_date" name="req_date" type="text" value="<?= $req_date ?>" readonly>
										</div>
									</div>
								</div>
								<div class='col-sm-4'>
									<div class='form-group'>
										<label for="ord_desc">Order Description <code> (manual input) </code> </label>
										<input type="text" class="form-control input-sm required" id="ord_desc" name="ord_desc" placeholder="input here.." value="<?= $order_desc ?>" />
									</div>
								</div>
								<div class='col-sm-4'>
									<div class='form-group'>
										<label for="so_remarks">Remarks <code> (manual input) </code> </label>
										<input type="text" class="form-control input-sm required" id="so_remarks" name="so_remarks" placeholder="input here.." value="<?= $order_remarks ?>" />
									</div>
								</div>

							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="form-group subtitle_head">
										<label class="text-right"><strong>Item/Services :</strong></label>
									</div>
								</div>
								<div class='col-sm-2'>
									<div class='form-group'>
										<label for="so_service">Service Type <code> (choose) </code> </label>
										<select name="so_service" class="form-control input-sm required">
											<option value="">--Choose One--</option>
											<option value="SPAREPARTS" <?php if ($service_type == "SPAREPARTS") {
																			echo "selected";
																		} ?>>SPAREPARTS</option>
											<option value="SERVICES" <?php if ($service_type == "SERVICES") {
																			echo "selected";
																		} ?>>SERVICES</option>

										</select>
									</div>
								</div>
								<div class='col-sm-4'>
									<div class='form-group'>
										<label for="inventory_no">Inventory No</label>
										<select class="form-control input-sm select2 required" id="inventory_no" name="inventory_no" style="width:100%;">
											<option option value="">--SELECT INVENTORY NO--</option>
											<?php foreach ($item_data as $icitem) :
											?>
												<option value="<?= trim($icitem['ITEMNO'])
																?>" <?php if ($inventory_no == trim($icitem['ITEMNO'])) {
																		echo "selected";
																	} ?>><?= trim($icitem['ITEMNO'])
																			?> - <?= $icitem['ITEMDESC']
																					?>
												</option>
											<?php endforeach;
											?>
										</select>
									</div>
								</div>
								<div class='col-sm-2'>
									<div class='form-group'>
										<label for="material_no">Material No <code> (auto filled) </code> </label>
										<input type="text" class="form-control input-sm required" id="material_no" name="material_no" placeholder="" value="<?= $material_no ?>" />
									</div>
								</div>
								<div class='col-sm-2'>
									<div class='form-group'>
										<label for="so_qty">Qty <code> (manual input) </code> </label>
										<input type="text" class="form-control input-sm required" id="so_qty" name="so_qty" placeholder="input here.." value="<?= $qty ?>" />
									</div>
								</div>
								<div class='col-sm-2'>
									<div class='form-group'>
										<label for="so_uom">Uom <code> (Choose) </code> </label>
										<select name="so_uom" class="form-control input-sm required">
											<option value="">--Choose One--</option>
											<option value="Ea" <?php if ($stock_unit == "Ea") {
																	echo "selected";
																} ?>>Ea</option>
											<option value="Pcs" <?php if ($stock_unit == "Pcs") {
																	echo "selected";
																} ?>>Pcs</option>
										</select>
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