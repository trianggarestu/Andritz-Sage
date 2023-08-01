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
			<li><a href="<?= base_url('administration') ?>"><i class="fa fa-home"></i> Home</a></li>
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
							<a href="<?= base_url(); ?>salesorderopen" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Back to Sales Orders Open">
								<i class="fa fa-arrow-circle-left "></i>Back to Sales Orders Open
							</a>
							<a href="<?= base_url(); ?>salesorder" title="Reset Data" onclick="<?php echo base_url(); ?>" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block <?php if (!empty($csruniq)) : echo 'disabled';
																																																																	endif; ?>"><i class='fa fa-refresh'></i> Reset Sales Order Form</a>
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
										<label class="text-right"><strong>CONTRACT :<?= $csruniq ?></strong></label>
									</div>
								</div>

								<div class='col-sm-3'>
									<div class='form-group'>
										<label for="ct_no">Contract<code>(choose)</code> </label>
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
												<a href="<?= base_url('salesorder/form_select_salesman/' . $ct_no); ?>" data-remote="false" data-toggle="modal" data-target="#modalBox" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block 
												<?php if ((empty($ct_staffcode) and empty($ct_no)) or (!empty($ct_staffcode) and !empty($ct_no) and empty($csruniq)) or (!empty($chk_salesperson) and  !empty($csruniq))) : echo 'disabled';
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
											<input type="text" class="form-control input-sm required" id="ct_email" name="ct_email" placeholder="" value="<?php //if (empty($ct_email)) {
																																							echo session()->get('cust_email');
																																							//} else {
																																							//echo $ct_email;
																																							//}  
																																							?>" readonly />
											<span class="input-group-btn">
												<a href="<?= base_url('salesorder/form_input_email/' . $ct_no . '/' . $prj_no); ?>" data-remote="false" data-toggle="modal" data-target="#modalBox" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block 
												<?php //if ((empty($ct_email) and empty($ct_no)) or (!empty($ct_email) and !empty($ct_no) and empty($csruniq)) or (!empty($chk_email) and  !empty($csruniq))) : echo 'disabled';
												//endif; 
												if (empty($ct_no)) : echo 'disabled';
												endif;
												?>">
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
										<label for="prj_no">Project<code>(choose)</code> </label>
										<div class="input-group">
											<span class="input-group-addon input-sm">
												<a href="<?= base_url() . 'salesorder/form_select_project_by_contract/' . $ct_no; ?>/" data-remote="false" data-toggle="modal" data-target="#modalBox"><i class=" fa fa-search"></i></a>
											</span>
											<input type="text" maxlength="10" size="10" class="form-control input-sm required" id="prj_no" name="prj_no" placeholder="Project Number" value="<?= $prj_no; ?>" readonly>
											</input>
										</div>
									</div>
								</div>
								<div class='col-sm-3'>
									<div class='form-group'>
										<label for="prj_desc">Description </label>
										<input type="text" class="form-control input-sm required" id="prj_desc" name="prj_desc" placeholder="" value="<?= $prj_desc; ?>" readonly />
									</div>
								</div>
								<div class='col-sm-3'>
									<div class='form-group'>
										<label for="po_cust">PO Customer</label>
										<div class="input-group input-group-sm">
											<input type="text" class="form-control input-sm required" id="po_cust" name="po_cust" placeholder="" value="<?php if (empty($po_cust)) {
																																							echo session()->get('po_cust');
																																						} else {
																																							echo $po_cust;
																																						}  ?>" readonly />
											<span class="input-group-btn">
												<a href="<?= base_url('salesorder/form_input_pocust/' . $ct_no . '/' . $prj_no); ?>" data-remote="false" data-toggle="modal" data-target="#modalBox" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block 
												<?php if ((empty($po_cust) and empty($prj_no)) or (!empty($po_cust) and !empty($prj_no) and empty($csruniq)) or (!empty($chk_po_cust) and !empty($csruniq))) : echo 'disabled';
												endif; ?>">
													Input !
												</a>
											</span>
										</div>

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
										<input type="text" class="form-control input-sm required" id="crm_no" name="crm_no" placeholder="input here.." value="<?php if (empty($crm_no)) {
																																									echo session()->get('crm_no');
																																								} else {
																																									echo $crm_no;
																																								}  ?>" />

									</div>
								</div>
								<div class='col-sm-2'>

									<div class='form-group'>
										<label for="req_date">Req. Date <code> (manual input) </code> </label>
										<div class="input-group input-group-sm date">
											<div class="input-group-addon">
												<i class="fa fa-calendar"></i>
											</div>
											<input class="datepicker form-control input-sm required" id="req_date" name="req_date" type="text" value="<?php if (empty($req_date)) {
																																							echo session()->get('req_date');
																																						} else {
																																							echo $req_date;
																																						}  ?>" readonly>
										</div>
									</div>
								</div>
								<div class='col-sm-4'>
									<div class='form-group'>
										<label for="ord_desc">Order Description <code> (manual input) </code> </label>
										<input type="text" class="form-control input-sm required" id="ord_desc" name="ord_desc" placeholder="input here.." value="<?php if (empty($order_desc)) {
																																										echo session()->get('ord_desc');
																																									} else {
																																										echo $order_desc;
																																									}  ?>" />
									</div>
								</div>
								<div class='col-sm-4'>
									<div class='form-group'>
										<label for="so_remarks">Remarks <code> (manual input) </code> </label>
										<div class="input-group input-group-sm">
											<input type="text" class="form-control input-sm required" id="so_remarks" name="so_remarks" placeholder="input here.." value="<?php if (empty($order_remarks)) {
																																												echo session()->get('so_remarks');
																																											} else {
																																												echo $order_remarks;
																																											}  ?>" />
											<span class="input-group-btn">
												<button type='submit' name="form_save" value="crm_save" class='btn btn-social btn-flat bg-navy btn-sm pull-right' <?php if ((empty($ct_no) and empty($prj_no)) or (empty($prj_no))) : echo 'disabled';
																																									endif; ?>>Save CRM</button>
											</span>
										</div>
									</div>
								</div>

							</div>
							<div class="row">
								<div class="col-sm-12">
									<div class="form-group subtitle_head">
										<label class="text-right"><strong>Item/Services :</strong></label>
									</div>
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<form id="mainform" name="mainform" action="" method="post">
											<div class="row">
												<div class="col-sm-12">
													<a href="<?= base_url('salesorder/form_input_item/' . $ct_no . '/' . $prj_no); ?>" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Add Item Sales Orders" title="Add Item Sales Orders" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class='fa fa-plus'></i> Add Item</a>
												</div>
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
																if (is_array($csropen_items) || is_object($csropen_items)) {
																	foreach ($csropen_items as $items) :
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
																				<a href="#" data-href="<?= base_url("salesorder/delete_item_open/" . $ct_no . "/" . $prj_no . "/" . $items['CSRLUNIQ']) ?>" class="btn bg-maroon btn-flat btn-sm" title="Delete Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																			</td>

																		</tr>
																<?php endforeach;
																}
																?>
																<?php
																//$no = 0;

																foreach ($cart->contents() as $items) :
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
																		<td><?= $items['qty']
																			?></td>
																		<td nowrap><?= $items['options']['so_uom']
																					?></td>
																		<td nowrap>
																			<!--<a href="<?php //base_url("salesorder/form_update_item/" . $ct_no . "/" . $prj_no . "/" . $items['options']['so_service'] . "/" . $items['id'] . "/" . $items['options']['material_no'] . "/" . $items['qty'] . "/" . $items['options']['so_uom']) 
																							?>" class="btn bg-orange btn-flat btn-sm" title="Update Item" data-toggle="modal" data-target="#modalUpdateitem"><i class="fa fa-edit"></i></a>-->
																			<a href="#" data-href="<?= base_url("salesorder/delete_item_cart/" . $ct_no . "/" . $prj_no . "/" . $items['rowid']) ?>" class="btn bg-maroon btn-flat btn-sm" title="Delete Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																		</td>
																	</tr>
																<?php endforeach;

																?>

															</tbody>
														</table>
													</div>
												</div>
											</div>
										</form>
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
						<input type="hidden" id="csruniq" name="csruniq" value="<?= $csruniq ?>">
						<input type="hidden" id="csr_uniq" name="csr_uniq" value="<?= $csr_uniq ?>">
						<input type="hidden" id="chk_item" name="chk_item" value="<?= $cart->totalItems() ?>">

						<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm'><i class='fa fa-times'></i> Cancel</button>
						<button type='submit' name="form_save" value="so_save" class='btn btn-social btn-flat btn-info btn-sm pull-right'><i class='fa fa-check'></i> Save</button>
					</div>
				</div>


			</div>
		</form>
</div>
</div>
</div>
</section>
</div>

<?php echo view('settings/modalbox/modal_confirm_delete') ?>