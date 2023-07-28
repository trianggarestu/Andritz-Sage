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
						<?php if ($shiopen_data['EDNPOSTINGSTAT'] == 0 or $shiopen_data['OFFLINESTAT'] == 1) {
						?>
							<a href="<?= base_url($link_action . $shiopen_data['SHIUNIQ'] . '/' . $shiopen_data['CSRUNIQ']) ?>" class="btn btn-social btn-flat <?= $btn_color ?> btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Posting"><i class="fa  fa-paper-plane-o"></i>
								<?= $button;
								?>
							</a>



						<?php } ?>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
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
															<td><strong><a href="<?= base_url("administration/csrpostedview/" . $shiopen_data['CSRUNIQ']) ?>" title="Click here for detail" target="_blank"><?= $shiopen_data['CONTRACT'] ?></a><?= '/' . $shiopen_data['PROJECT'] . '/' . $shiopen_data['CRMNO'] ?></strong></td>
														</tr>
														<tr>
															<td width="150">Contract Description </td>
															<td width="1">:</td>
															<td><strong><?= $shiopen_data['CTDESC'] ?></strong></td>
														</tr>
														<tr>
															<td width="150">Customer </td>
															<td width="1">:</td>
															<td><strong>
																	<?= $shiopen_data['NAMECUST'] ?>
																</strong><br>
																<small>(<?= $shiopen_data['EMAIL1CUST'] ?>)</small>


															</td>
														</tr>
														<tr>
															<td width="150">Sales Person </td>
															<td width="1">:</td>
															<td><strong><?= $shiopen_data['MANAGER'] . '(' . trim($shiopen_data['SALESNAME']) . ')'; ?></strong></td>
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
															<td><strong><?= $shiopen_data['PROJECT']; ?></strong></td>
														</tr>
														<tr>
															<td width="150">Project Description </td>
															<td width="1">:</td>
															<td><strong><?= $shiopen_data['PRJDESC']; ?></strong></td>
														</tr>
														<tr>
															<td width="150">PO Number Customer </td>
															<td width="1">:</td>
															<td><strong><?= $shiopen_data['PONUMBERCUST']; ?></strong></td>
														</tr>
														<tr>
															<td width="150">PO Customer Date </td>
															<td width="1">:</td>
															<td><strong><?php

																		$dd = substr($shiopen_data['PODATECUST'], 6, 2);
																		$mm = substr($shiopen_data['PODATECUST'], 4, 2);
																		$yyyy = substr($shiopen_data['PODATECUST'], 0, 4);
																		$pocustdate = $mm . '/' . $dd . '/' . $yyyy;
																		echo $pocustdate; ?></strong></td>
														</tr>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<div class="row">
										<div class="col-sm-12">
											<div class="table-responsive">
												<table class="table table-bordered table-striped table-hover">
													<tbody>

														<tr>
															<td colspan="3" class="subtitle_head"><strong>Delivery Orders</strong></td>
														</tr>
														<tr>
															<td width="250">Shipment Status </td>
															<td width="1">:</td>
															<td><strong><?php
																		$postingstat = $shiopen_data['POSTINGSTAT'];
																		switch ($postingstat) {
																			case "0":
																				echo "<span class='label label-default'>Open</span>";
																				break;
																			case "1":
																				echo "<span class='label label-success'>Posted</span>";
																				break;
																			case "2":
																				echo "<span class='label label-danger'>Deleted</span>";
																				break;
																			default:
																				echo "<span class='label label-default'>Open</span>";
																		}
																		?></strong></td>
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
															<td colspan="3" class="subtitle_head"><strong>e-Delivery Note</strong></td>
														</tr>
														<?php if (!empty($shiopen_data['EDNFILENAME'])) {
															$origdnrcpshidate = substr($shiopen_data['ORIGDNRCPSHIDATE'], 4, 2) . "/" . substr($shiopen_data['ORIGDNRCPSHIDATE'], 6, 2) . "/" .  substr($shiopen_data['ORIGDNRCPSHIDATE'], 0, 4);
														?>
															<tr>
																<td colspan="3">
																	<div class="table-responsive">
																		<table class="table table-bordered table-striped table-hover">
																			<thead>
																				<tr>
																					<th nowrap>e-Delivery Note File</th>
																					<th>D/N Receipt</th>
																					<th>Action</th>
																				</tr>
																			</thead>
																			<tbody>
																				<tr>
																					<td><strong>
																							<a href="<?= base_url($shiopen_data['EDNFILEPATH']) ?>" download><?= $shiopen_data['EDNFILENAME'] ?></a>
																						</strong>
																					</td>
																					<td>
																						<strong>
																							<?= $origdnrcpshidate ?>
																						</strong>
																					</td>
																					<td>
																						<a href="" data-href="<?= base_url("deliveryorders/deleteedn/" . $shiopen_data['SHIUNIQ']) ?>" class="btn bg-red btn-flat btn-sm" title="Delete Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
																					</td>
																				</tr>
																			</tbody>
																		</table>
																	</div>
																</td>
															</tr>

														<?php } else {
															echo 'File e-Delivery Note Not Found!.';
														} ?>




													</tbody>
												</table>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-12">
											<form action="<?= base_url() . 'deliveryorders/edn_upload_action' ?>" method="post" enctype="multipart/form-data">
												<div class="row">
													<div class="col-sm-12">
														<div class="col-sm-4">
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
																		<button type="button" class="btn btn-info btn-flat" id="file_browser" <?php if (!empty($shiopen_data['EDNFILENAME'])) : echo 'disabled';
																																				endif; ?>><i class="fa fa-search"></i> Browse</button>
																	</span>
																</div>
															</div>
														</div>
														<div class="col-sm-8">
															<div class="form-group">
																<label for="origdnrcpshidate">Original D/N Receipt</label>
																<div class="input-group input-group-sm date">
																	<div class="input-group-addon">
																		<i class="fa fa-calendar"></i>
																	</div>
																	<input class="form-control input-sm pull-right" id="origdnrcpshidate" name="origdnrcpshidate" type="text" value="<?= $todaydate ?>" readonly>
																</div>
															</div>
															<button type="submit" class="btn btn-social btn-flat btn-info btn-sm pull-right" <?php if (!empty($shiopen_data['EDNFILENAME'])) : echo 'disabled';
																																				endif; ?>><i class='fa fa-upload'></i>Upload File</button>
														</div>

													</div>
												</div>
											</form>
										</div>
									</div>

									<div class="row">
										<div class="col-sm-12">
											<div class="form-group subtitle_head">
												<label class="text-right"><strong>Item/Services :</strong></label>
											</div>
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

															<th>D/N Qty</th>
															<th>D/N Qty <br>(Outstanding)</th>

															<th>Uom</th>

														</tr>
													</thead>
													<tbody>
														<?php
														$no = 0;

														foreach ($shi_l_open_data as $items) :
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
																<td><?= number_format($items['SHIQTYOUTSTANDING'], 0, ",", ".")
																	?></td>

																<td nowrap><?= $items['STOCKUNIT']
																			?></td>


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
				</div>
			</div>
		</div>
		<div class="row">
			<div class='col-sm-12'>
				<?= validation_list_errors() ?>
			</div>
		</div>
	</section>
</div>

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