<style>
	.input-sm {
		padding: 4px 4px;
	}
</style>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Sales Order View</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
			<li>Sales Order</li>
			<li class="active">Sales Order Open View</li>
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
						<a href="<?= base_url('salesorderopen') ?>" title="Back to Sales Order Open" class="btn btn-social btn-flat bg-aqua btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Back to Sales Order Open</a>
						<?php if ($csropen_data['POSTINGSTAT'] == 0 or $csropen_data['OFFLINESTAT'] == 1) {
						?>
							<a href="<?= base_url($link_action . $csropen_data['CSRUNIQ']) ?>" class="btn btn-social btn-flat <?= $btn_color ?> btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Posting"><i class="fa  fa-paper-plane-o"></i>
								<?= $button;
								?>
							</a>


							<a href="<?= base_url('salesorder/update/' . $csropen_data['CSRUNIQ']) ?>" title="Edit" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Back to Form Entry</a>
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
															<td width="300">Contract </td>
															<td width="1">:</td>
															<td><strong><?= $csropen_data['CONTRACT']; ?></strong></td>
														</tr>
														<tr>
															<td width="300">Contract Description </td>
															<td width="1">:</td>
															<td><strong><?= $csropen_data['CTDESC']; ?></strong></td>
														</tr>
														<tr>
															<td width="300">Customer </td>
															<td width="1">:</td>
															<td><strong>
																	<?= $csropen_data['CUSTOMER'] . ' - ' . $csropen_data['NAMECUST']; ?>
																	<br>
																	<small>
																		&lt;&nbsp;<?= $csropen_data['EMAIL1CUST']; ?>&nbsp;&gt;
																	</small>
																</strong>
															</td>
														</tr>
														<tr>
															<td width="300">Sales Person </td>
															<td width="1">:</td>
															<td><strong><?= $csropen_data['MANAGER'] . '(' . $csropen_data['SALESNAME'] . ')'; ?></strong></td>
														</tr>
														<tr>
															<th colspan="3" class="subtitle_head"><strong>PROJECT</strong></th>
														</tr>
														<tr>
															<td width="300">Project </td>
															<td width="1">:</td>
															<td><strong><?= $csropen_data['PROJECT']; ?></strong></td>
														</tr>
														<tr>
															<td width="300">Project Description </td>
															<td width="1">:</td>
															<td><strong><?= $csropen_data['PRJDESC']; ?></strong></td>
														</tr>
														<tr>
															<td width="300">PO Number Customer </td>
															<td width="1">:</td>
															<td><strong><?= $csropen_data['PONUMBERCUST']; ?></strong></td>
														</tr>
														<tr>
															<td width="300">PO Customer Date </td>
															<td width="1">:</td>
															<td><strong><?php
																		$dd = substr($csropen_data['PODATECUST'], 6, 2);
																		$mm = substr($csropen_data['PODATECUST'], 4, 2);
																		$yyyy = substr($csropen_data['PODATECUST'], 0, 4);
																		$pocustdate = $mm . '/' . $dd . '/' . $yyyy;
																		echo $pocustdate; ?></strong></td>
														</tr>
														<tr>
															<th colspan="3" class="subtitle_head"><strong>CRM</strong></th>
														</tr>
														<tr>
															<td width="300">CRM Number </td>
															<td width="1">:</td>
															<td><strong><?= $csropen_data['CRMNO']; ?></strong></td>
														</tr>
														<tr>
															<td width="300">Request Date </td>
															<td width="1">:</td>
															<td><strong><?php

																		$dd = substr($csropen_data['CRMREQDATE'], 6, 2);
																		$mm = substr($csropen_data['CRMREQDATE'], 4, 2);
																		$yyyy = substr($csropen_data['CRMREQDATE'], 0, 4);
																		$reqdate = $mm . '/' . $dd . '/' . $yyyy;
																		echo $reqdate; ?></strong></td>
														</tr>
														<tr>
															<td width="300">Order Description </td>
															<td width="1">:</td>
															<td><strong><?= $csropen_data['ORDERDESC']; ?></strong></td>
														</tr>
														<tr>
															<td width="300">Remarks </td>
															<td width="1">:</td>
															<td><strong><?= $csropen_data['CRMREMARKS']; ?></strong></td>
														</tr>
														<tr>
															<th colspan="3" class="subtitle_head"><strong>SERVICES</strong></th>
														</tr>
														<tr>
															<td width="300">Service Type </td>
															<td width="1">:</td>
															<td><strong><?= $csropen_data['SERVICETYPE']; ?></strong></td>
														</tr>
														<tr>
															<td width="300">Inventory No. </td>
															<td width="1">:</td>
															<td><strong><?= $csropen_data['ITEMNO']; ?></strong></td>
														</tr>
														<tr>
															<td width="300">Material No. </td>
															<td width="1">:</td>
															<td><strong><?= $csropen_data['MATERIALNO']; ?></strong></td>
														</tr>
														<tr>
															<td width="300">Item Description </td>
															<td width="1">:</td>
															<td><strong><?= $csropen_data['ITEMDESC']; ?></strong></td>
														</tr>
														<tr>
															<td width="300">Qty. </td>
															<td width="1">:</td>
															<td><strong><?= number_format($csropen_data['QTY'], 0, ",", ".") . ' (' . trim($csropen_data['STOCKUNIT']) . ')'; ?></strong></td>
														</tr>


													</tbody>
												</table>
											</div>
										</div>
									</div>
									</form>
									<div class="row">

									</div>
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