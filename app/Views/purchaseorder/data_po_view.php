<style>
	.input-sm {
		padding: 4px 4px;
	}
</style>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Purchase Requisition View</h1>
		<ol class="breadcrumb">
			<li><a href="<?= base_url('administration') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= base_url('requisitionlist/') ?>">Purchase Requisition List</li></a>
			<li class="active">Purchase Requisition Detail View</li>
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
						<form action="<?= $link_action; ?>" method="post" id="validasi">

						</form>
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
															<td colspan="3" class="subtitle_head"><strong>CONTRACT</strong></td>
														</tr>
														<tr>
															<td width="300">Status </td>
															<td width="1">:</td>
															<td><strong><?php $postingstat = $csropen_data['POSTINGSTAT'];
																		switch ($postingstat) {
																			case "0":
																				echo "<span class='label label-warning'>Open</span>";
																				break;
																			case "1":
																				echo "<span class='label label-success'>Posted</span>";
																				break;
																			case "2":
																				echo "<span class='label label-danger'>Deleted</span>";
																				break;
																			default:
																				echo "<span class='label label-warning'>Open</span>";
																		} ?></strong></td>
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
															<td colspan="3" class="subtitle_head"><strong>PROJECT</strong></td>
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
															<td colspan="3" class="subtitle_head"><strong>CRM</strong></td>
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
															<td colspan="3" class="subtitle_head"><strong>Purchase Requisition</strong></td>
														</tr>
														<tr>
															<td width="300">PR Number</td>
															<td width="1">:</td>
															<td><strong><?= $csropen_data['RQNNUMBER']; ?></strong></td>
														</tr>
														<tr>
															<td width="300">PR Date</td>
															<td width="1">:</td>
															<td><strong><?php
																		$dd = substr($csropen_data['RQNDATE'], 6, 2);
																		$mm = substr($csropen_data['RQNDATE'], 4, 2);
																		$yyyy = substr($csropen_data['RQNDATE'], 0, 4);
																		$pocustdate = $mm . '/' . $dd . '/' . $yyyy;
																		echo $pocustdate; ?></strong></td>
															</td>
														</tr>
														<tr>
															<td colspan="3" class="subtitle_head"><strong>Purchase Order</strong></td>
														</tr>
														<tr>
															<td width="300">PO Number</td>
															<td width="1">:</td>
															<td><strong><?= $csropen_data['PONUMBER']; ?></strong></td>
														</tr>
														<tr>
															<td width="300">PO Date</td>
															<td width="1">:</td>
															<td><strong><?php
																		$dd = substr($csropen_data['PODATE'], 6, 2);
																		$mm = substr($csropen_data['PODATE'], 4, 2);
																		$yyyy = substr($csropen_data['PODATE'], 0, 4);
																		$pocustdate = $mm . '/' . $dd . '/' . $yyyy;
																		echo $pocustdate; ?></strong></td>

														</tr>
														<tr>
															<td width="300">Cargoreadiness(Date)</td>
															<td width="1">:</td>
															<td><strong><?php
																		$dd = substr($csropen_data['CARGOREADINESSDATE'], 6, 2);
																		$mm = substr($csropen_data['CARGOREADINESSDATE'], 4, 2);
																		$yyyy = substr($csropen_data['CARGOREADINESSDATE'], 0, 4);
																		$pocustdate = $mm . '/' . $dd . '/' . $yyyy;
																		echo $pocustdate; ?></strong></td>

														</tr>
														<tr>
															<td width="300">Country Origin</td>
															<td width="1">:</td>
															<td><strong><?= $csropen_data['ORIGINCOUNTRY']; ?></td>

														</tr>
														<tr>
															<td width="300">PO Remarks</td>
															<td width="1">:</td>
															<td><strong><?= $csropen_data['POREMARKS']; ?></td>

														</tr>
														<tr>
															<td colspan="3" class="subtitle_head"><strong>SPAREPARTS / SERVICES</strong></td>
														</tr>
														<tr>
															<td colspan="3">
																<div class="table-responsive">
																	<table class="table table-bordered dataTable table-hover nowrap">
																		<thead class="bg-gray disabled color-palette">
																			<tr>

																				<th class="padat">No</th>

																				<th>Type </th>
																				<th>Inventory No.</th>
																				<th>Item Desc</th>
																				<th>Qty.</th>
																				<th>Uom</th>

																			</tr>
																		</thead>
																		<tbody>
																			<?php
																			$no = 0;
																			foreach ($csrlopen_data as $items) :
																			?>
																				<tr>

																					<td class="text-center"><?= ++$no ?></td>
																					<td><?= $items['SERVICETYPE']
																						?></td>

																					<td><?= $items['ITEMNO']
																						?></td>
																					<td><?= $items['ITEMDESC']
																						?></td>

																					<td><?= $items['QTY']
																						?></td>
																					<td nowrap><?= $items['STOCKUNIT']
																								?></td>

																				</tr>
																			<?php endforeach;
																			?>
																		</tbody>
																	</table>
																</div>
															</td>

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