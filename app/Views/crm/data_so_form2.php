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
			<li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('keluarga/clear') ?>"> Sales Order</a></li>
			<li class="active">Form</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="#confirm-delete" title="Reset Data" onclick="<?php echo base_url(); ?>" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class='fa fa-refresh'></i> Reset Sales Order Form</a>

						<a href="#" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Back to Sales Order List">
							<i class="fa fa-arrow-circle-left "></i>Back to Sales Order List
						</a>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group subtitle_head">
									<label class="text-right"><strong>Select Contract :</strong></label>
								</div>
							</div>
							<div class='col-sm-4'>
								<div class='form-group'>
									<label>Contract </label>
									<div class="input-group">
										<span class="input-group-addon input-sm"><a href="#"><i class="fa fa-search"></i></a></span>
										<input type="text" maxlength="10" size="10" class="form-control input-sm required" placeholder="Contract Number" value="SP1800IKPA" disabled>
									</div></input>
								</div>
							</div>
							<div class='col-sm-6'>
								<label>Contract Description </label>
								<input type="text" class="form-control input-sm required" id="output" name="output" placeholder="" value="PN.3120471,SEGMEN.WEAR PLATE,KONEWOOD" disabled />
							</div>
							<div class='col-sm-2'>
								<label>Start Date </label>
								<input type="text" class="form-control input-sm required" id="output" name="output" placeholder="" value="13-Oct-22" disabled />
							</div>
						</div>
						<div class="row">
							<div class='col-sm-2'>
								<label>Customer No. </label>
								<input type="text" class="form-control input-sm required" id="output" name="output" placeholder="" value="IKPIDR" disabled />
							</div>
							<div class='col-sm-2'>
								<label>Customer Email </label>
								<input type="text" class="form-control input-sm required" id="output" name="output" placeholder="" value="saputra_ade@app.co.id" disabled />
							</div>
							<div class='col-sm-8'>
								<label>Customer Name </label>
								<input type="text" class="form-control input-sm required" id="output" name="output" placeholder="" value="PT INDAH KIAT PULP & PAPER TBK." disabled />
							</div>

						</div>
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group subtitle_head">
									<label class="text-right"><strong>Project :</strong></label>
								</div>
							</div>
							<div class='col-sm-4'>
								<div class='form-group'>
									<label>Project </label>
									<div class="input-group">
										<span class="input-group-addon input-sm"><a href="#"><i class="fa fa-search"></i></a></span>
										<input type="text" maxlength="10" size="10" class="form-control input-sm required" placeholder="Project Number" value="1506644" disabled>
									</div></input>
								</div>
							</div>
							<div class='col-sm-4'>
								<label>Description </label>
								<input type="text" class="form-control input-sm required" id="output" name="output" placeholder="" value="PKW WP Chipper wear parts" disabled />
							</div>
							<div class='col-sm-2'>
								<label>PO Customer <code> (auto filled) </code> </label>
								<input type="text" class="form-control input-sm required" id="output" name="output" placeholder="" value="47625825" disabled />
							</div>
							<div class='col-sm-2'>
								<label>PO Date <code> (auto filled) </code> </label>
								<input type="text" class="form-control input-sm required" id="output" name="output" placeholder="" value="13-Oct-22" disabled />
							</div>

						</div>
						<div class="row">
							<div class='col-sm-2'>
								<label>CRM Number<code> (manual input)) </code> </label>
								<input type="text" class="form-control input-sm required" id="output" name="output" placeholder="input here.." value="" />
							</div>

							<div class='col-sm-2'>

								<div class='form-group'>
									<label>Req. Date <code> (manual input) </code> </label>
									<div class="input-group input-group-sm date">
										<div class="input-group-addon">
											<i class="fa fa-calendar"></i>
										</div>
										<input class="form-control input-sm pull-right" id="Reqdate" name="Reqdate" type="text" value="">
									</div>
								</div>
							</div>
							<div class='col-sm-6'>
								<label>Order Description <code> (manual input) </code> </label>
								<input type="text" class="form-control input-sm required" id="ord_desc" name="ord_desc" placeholder="input here.." value="" />
							</div>
							<div class='col-sm-2'>
								<div class="form-group">
									<label>Sales Person <code>(choose one)</code></label>
									<select class="form-control select2" style="width: 100%;">
										<option selected="selected">RACHMAT</option>
										<option>RACHMAT</option>
										<option>SAMSUL HADI</option>
										<option>ADI</option>
									</select>
								</div>
							</div>
						</div>
					</div>



					<div class="row>" <div class="table-responsive">

					</div>
					<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
						<form id="mainform" name="mainform" action="" method="post">
							<div class="row">
								<div class="col-sm-12">
									<div class="table-responsive">
										<table id="tabel2" class="table table-bordered dataTable table-hover nowrap">
											<thead class="bg-gray disabled color-palette">
												<tr>

													<th width="1%">No</th>
													<th width="5%">Inventory No.</th>
													<th>Material No. </th>
													<th>Order Description</th>
													<th>Qty</th>
													<th>UoM</th>
													<th width="5%" align="center">
														<a href="#" class="btn btn-success btn-flat btn-sm " title="Add Item"><i class="fa fa-plus"></i> </a>
													</th>
												</tr>
											</thead>
											<tbody>

												<tr>

													<td class="text-center">1</td>
													<td class="text-center" nowrap>
														200210024
													</td>
													<td>20137344</td>
													<td nowrap width="45%">PN.3120471,SEGMEN.WEAR PLATE,KONEWOOD</td>
													<td nowrap>6</td>
													<td>Pcs</td>
													<td nowrap>
														<a href="#" title="Edit" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Edit Item" class="btn bg-orange btn-flat btn-sm"><i class="fa fa-edit"></i></a>
														<a href="#" data-href="#" class="btn bg-maroon btn-flat btn-sm" title="Delete Item" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
													</td>
												</tr>
												<!--<tr>

													<td class="text-center">2</td>
													<td class="text-center" nowrap>
														200210065
													</td>
													<td>20140846</td>
													<td nowrap width="45%">DWG.3000992,NO.1,WEAR PLATE,KONE WOOD</td>
													<td nowrap>42</td>
													<td>Pcs</td>
													<td nowrap>
														<a href="#" title="Edit" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Edit Item" class="btn bg-orange btn-flat btn-sm"><i class="fa fa-edit"></i></a>
														<a href="#" data-href="#" class="btn bg-maroon btn-flat btn-sm" title="Delete Item" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
													</td>
												</tr>
												<tr>

													<td class="text-center">3</td>
													<td class="text-center" nowrap>
														200210230
													</td>
													<td>20217175</td>
													<td nowrap width="45%">DWG.K471634,SPRING,KONEWOOD</td>
													<td nowrap>471</td>
													<td>Pcs</td>
													<td nowrap>
														<a href="#" title="Edit" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Edit Item" class="btn bg-orange btn-flat btn-sm"><i class="fa fa-edit"></i></a>
														<a href="#" data-href="#" class="btn bg-maroon btn-flat btn-sm" title="Delete Item" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
													</td>
												</tr>
												<tr>

													<td class="text-center">4</td>
													<td class="text-center" nowrap>
														200210071
													</td>
													<td>20689144</td>
													<td nowrap width="45%">PN.1122954,KNIFE SEGMENT,ANDRITZ</td>
													<td nowrap>83</td>
													<td>Pcs</td>
													<td nowrap>
														<a href="#" title="Edit" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Edit Item" class="btn bg-orange btn-flat btn-sm"><i class="fa fa-edit"></i></a>
														<a href="#" data-href="#" class="btn bg-maroon btn-flat btn-sm" title="Delete Item" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
													</td>
												</tr>
												<tr>

													<td class="text-center">5</td>
													<td class="text-center" nowrap>
														200210153
													</td>
													<td>20769679</td>
													<td nowrap width="45%">PN.1121882,CLAMP.BOLT,ANDRITZ</td>
													<td nowrap>284</td>
													<td>EA</td>
													<td nowrap>
														<a href="#" title="Edit" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Edit Item" class="btn bg-orange btn-flat btn-sm"><i class="fa fa-edit"></i></a>
														<a href="#" data-href="#" class="btn bg-maroon btn-flat btn-sm" title="Delete Item" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
													</td>
												</tr>-->


											</tbody>
										</table>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class='box-footer'>
				<div class='col-xs-12'>
					<button type='reset' class='btn btn-social btn-flat btn-danger btn-sm'><i class='fa fa-times'></i> Cancel</button>
					<button type='submit' class='btn btn-social btn-flat btn-info btn-sm pull-right'><i class='fa fa-check'></i> Saved & Sent Notif</button>
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