<style>
	.input-sm {
		padding: 4px 4px;
	}
</style>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Delivery Orders View</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
			<li>Delivery Orders</li>
			<li class="active">Delivery Orders Open View</li>
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
						<a href="<?= base_url('deliveryorders') ?>" title="Back to Good Receipt List" class="btn btn-social btn-flat bg-aqua btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Back to Delivery Orders List</a>
						<?php if ($shiopen_data['POSTINGSTAT'] == 0 or $shiopen_data['OFFLINESTAT'] == 1) {
						?>
							<a href="<?= base_url($link_action . $shiopen_data['SHIUNIQ']) ?>" class="btn btn-social btn-flat <?= $btn_color ?> btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Posting"><i class="fa  fa-paper-plane-o"></i>
								<?= $button;
								?>
							</a>



						<?php } ?>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">


									<div class="row">
										<div class="col-sm-12">
											<div class="table-responsive">
												<table class="table table-bordered table-striped table-hover">
													<tbody>
														<tr>
															<th colspan="3" class="subtitle_head"><strong>CONTRACT</strong></th>
														</tr>

														<tr>
															<th colspan="3" class="subtitle_head"><strong>Delivery Orders</strong></th>
														</tr>
														<tr>
															<td width="250">Shipment Number </td>
															<td width="1">:</td>
															<td><strong><?= $shiopen_data['DOCNUMBER']; ?></strong></td>
														</tr>
														<tr>
															<td width="250">Shipment Date </td>
															<td width="1">:</td>
															<td><strong><?php

																		$dd = substr($shiopen_data['SHIDATE'], 6, 2);
																		$mm = substr($shiopen_data['SHIDATE'], 4, 2);
																		$yyyy = substr($shiopen_data['SHIDATE'], 0, 4);
																		$shidate = $mm . '/' . $dd . '/' . $yyyy;
																		echo $shidate; ?></strong></td>
														</tr>
														<tr>
															<td width="250">DN Number </td>
															<td width="1">:</td>
															<td><strong><?= $shiopen_data['SHINUMBER']; ?></strong></td>
														</tr>

														<tr>
															<td width="250">Cust. Received Date </td>
															<td width="1">:</td>
															<td><strong><?php

																		$dd = substr($shiopen_data['CUSTRCPDATE'], 6, 2);
																		$mm = substr($shiopen_data['CUSTRCPDATE'], 4, 2);
																		$yyyy = substr($shiopen_data['CUSTRCPDATE'], 0, 4);
																		$custrcp_date = $mm . '/' . $dd . '/' . $yyyy;
																		echo $custrcp_date; ?></strong></td>
														</tr>


														<tr>
															<th colspan="3" class="subtitle_head"><strong>e-Delivery Note</strong></th>
														</tr>

														<tr>
															<td width="250">e-Delivery Note File</td>
															<td width="1">:</td>
															<td>
																<?php if (!empty($shiopen_data['EDNFILENAME'])) { ?>
																	<strong>
																		<a href="<?= base_url($shiopen_data['EDNFILEPATH']) ?>" download><?= $shiopen_data['EDNFILENAME'] ?></a>
																	</strong>
																<?php } else {
																	echo 'File e-Delivery Note Not Found!.';
																} ?>
															</td>
														</tr>


													</tbody>
												</table>
											</div>
										</div>
									</div>

									<form action="<?= base_url() . 'deliveryorders/edn_upload_action' ?>" method="post" enctype="multipart/form-data">
										<div class="row">
											<div class="col-sm-12">

												<div class="form-group">
													<label for="catatan">Upload e-Delivery Note</label>
													<div class="input-group input-group-sm">
														<input type="text" class="form-control" id="file_path" name="edn_path" style="width: 100%;">
														<input type="file" class="hidden" id="file" name="edn_file">
														<input type="hidden" name="old_edn_fname" value="<? //=$pamong['foto']
																											?>">
														<input type="hidden" name="shiuniq" value="<?= $shiopen_data['SHIUNIQ'] ?>">
														<input type="hidden" name="shidocnum" value="<?= $shiopen_data['DOCNUMBER'] ?>">
														<input type="hidden" name="shidate" value="<?= $shiopen_data['SHIDATE'] ?>">
														<span class="input-group-btn">
															<button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i> Browse</button>
														</span>
													</div>
												</div>

												<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right"><i class='fa fa-upload'></i>Upload File</button>

											</div>
										</div>
									</form>
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