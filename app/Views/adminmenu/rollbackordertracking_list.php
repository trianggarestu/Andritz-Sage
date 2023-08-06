	<script type="text/javascript">
		$(function() {
			var keyword = <? //= $keyword 
							?>;
			$("#cari").autocomplete({
				source: keyword,
				maxShowItems: 10,
			});
		});
	</script>
	<div class="content-wrapper">
		<section class="content-header">
			<h1>Select Order Tracking Header</h1>
			<ol class="breadcrumb">
				<li><a href="<?= base_url() ?>administration"><i class="fa fa-home"></i> Home</a></li>


				<li class="active">Rollback Process</li>

			</ol>
		</section>

		<!-- Untuk menampilkan modal bootstrap action success, failed  -->
		<input id="success-code" type="hidden" value="<?= $success_code ?>">
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



						<div class="box-body">

							<div class="row">
								<div class="col-sm-12">
									<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<form id="mainform" name="mainform" action="" method="post">
											<div class="row">
												<div class="col-sm-9">
													<div class="form-group" style="width:100%;max-width:90%;">
														<label for="daterange">Filter by Contract Number : </label>
														<div class="input-group input-group-sm">
															<div class="input-group-addon">Contract No. :
																<i class="fa fa-angle-double-down"></i>
															</div>

															<select class="form-control input-sm select2 required" id="contract_no" name="contract_no">
																<option option value="">
																	-----SELECT CONTRACT-----
																</option>
																<?php foreach ($contract_data as $ctr) :
																?>
																	<option value="<?= trim($ctr['CONTRACT'])
																					?>" <?php if ($keyword == trim($ctr['CONTRACT'])) {
																							echo "selected";
																						} ?>><?= trim($ctr['CONTRACT'])
																								?> - <?= $ctr['CTDESC']
																										?>
																	</option>
																<?php endforeach;
																?>
															</select>

															<div class="input-group-btn">
																<button type="submit" class="btn btn-default bg-maroon" onclick="$('#'+'mainform').attr('action', '<?= base_url('rollbackprocess/search') ?>');$('#'+'mainform').submit();"><i class="fa fa-filter"></i>Go!</button>
															</div>

														</div>
													</div>
												</div>
												<div class="col-sm-3">

												</div>
											</div>
										</form>
										<div class="row">
											<div class="col-sm-12">
												<div class="table-responsive">
													<table class="table table-bordered dataTable table-hover">
														<thead class="bg-gray disabled color-palette">
															<tr>
																<th width="1%">No</th>
																<th width="5%">Action</th>
																<th nowrap>Contract/Project/CRM<br>Contract Desc.<br>Customer</th>
																<th nowrap>PO Customer - P/O Date<br>Customer Name<br>Customer Email</th>
																<th style="vertical-align: top;">P/O Cust.<br>Date</th>
																<th style="vertical-align: top;">CRM Req.<br> Date</th>
																<th style="vertical-align: top;">S/O Status</th>
																<th style="vertical-align: top;">Requisition<br>Process</th>
																<th style="vertical-align: top;">P/O<br>Process</th>
																<th style="vertical-align: top;">Logistics<br>Process</th>
																<th style="vertical-align: top;">G/R Process</th>
																<th style="vertical-align: top;">D/N Process</th>
																<th style="vertical-align: top;">Confirm D/N<br>Origin<br> Process</th>
																<th style="vertical-align: top;">Fill Invoice<br> Process</th>
																<th style="vertical-align: top;">Fill RR Status<br> Process</th>

															</tr>
														</thead>
														<tbody>
															<?php
															$no = 0;
															if (is_array($otheader_data)) {
																foreach ($otheader_data as $data) :
																	$crmpodate = substr($data['PODATECUST'], 4, 2) . "/" . substr($data['PODATECUST'], 6, 2) . "/" .  substr($data['PODATECUST'], 0, 4);
																	$crmreqdate = substr($data['CRMREQDATE'], 4, 2) . '/' . substr($data['CRMREQDATE'], 6, 2) . '/' . substr($data['CRMREQDATE'], 0, 4);
															?>
																	<tr>
																		<td class="text-center"><?= ++$no; ?></td>
																		<td nowrap>
																			<a href="<?= base_url() . 'rollbackprocess/form/' . $data['CSRUNIQ']; ?>" class=" btn btn-danger btn-flat btn-sm" title="Rollback Process" data-remote="false" data-toggle="modal" data-target="#modalBox"><i class="fa fa-undo"></i>Rollback</a>
																		</td>
																		<td nowrap><strong><a href="<?= base_url('administration/csrpostedview/' . $data['CSRUNIQ']) ?>" target="_blank"><?= $data['CONTRACT'] ?></a></strong>
																			<?= " / " . $data['PROJECT'] . " / " . $data['CRMNO']; ?><br>
																			<strong><?= $data['CTDESC']; ?></strong><br>
																			<small>(<?= $data['NAMECUST']; ?>)</small><br>
																		</td>
																		<td style="vertical-align: top;">
																			<strong><?= $data['PONUMBERCUST']; ?></strong><br>
																			<?= $data['ORDERDESC']; ?><br>
																			CRM Remarks : <?= $data['CRMREMARKS']; ?>

																		</td>
																		<td style="vertical-align: top;" nowrap><?= $crmpodate; ?></td>
																		<td style="vertical-align: top;" nowrap><?= $crmreqdate; ?></td>
																		<td nowrap>
																			<?php $postingstat = $data['POSTINGSTAT'] . $data['OFFLINESTAT'];
																			switch ($postingstat) {
																				case "00":
																					echo "<span class='label label-warning'>Open</span>";
																					break;
																				case "11":
																					echo "<span class='label label-warning'>Posted Pending Notif</span>";
																					break;
																				case "10":
																					echo "<span class='label label-success'>Posted & Sending Notif</span>";
																					break;
																				case "20":
																					echo "<span class='label label-danger'>Deleted</span>";
																					break;
																				case "21":
																					echo "<span class='label label-danger'>Deleted</span>";
																					break;
																				default:
																					echo "<span class='label label-warning'>Open</span>";
																			} ?>
																		</td>
																		<td style="text-align: center;" nowrap>
																			<?php if (!empty($data['RQNSTAT'])) { ?>
																				<span title="Process" class="badge bg-green"><i class='fa fa-check-circle'></i></span>

																			<?php }
																			?>
																		</td>
																		<td style="text-align: center;" nowrap>
																			<?php if (!empty($data['CTPO'])) { ?>
																				<span title="Process" class="badge bg-green"><i class='fa fa-check-circle'></i></span>

																			<?php }
																			?>
																		</td>
																		<td style="text-align: center;" nowrap>
																			<?php if (!empty($data['CTLOG'])) { ?>
																				<span title="Process" class="badge bg-green"><i class='fa fa-check-circle'></i></span>

																			<?php }
																			?>
																		</td>
																		<td style="text-align: center;" nowrap>
																			<?php if (!empty($data['CTGR'])) { ?>
																				<span title="Process" class="badge bg-green"><i class='fa fa-check-circle'></i></span>

																			<?php }
																			?>
																		</td>
																		<td style="text-align: center;" nowrap>
																			<?php if (!empty($data['CTDN'])) { ?>
																				<span title="Process" class="badge bg-green"><i class='fa fa-check-circle'></i></span>

																			<?php }
																			?>
																		</td>
																		<td style="text-align: center;" nowrap>
																			<?php if (!empty($data['CTEDN'])) { ?>
																				<span title="Process" class="badge bg-green"><i class='fa fa-check-circle'></i></span>

																			<?php }
																			?>
																		</td>
																		<td style="text-align: center;" nowrap>
																			<?php if (!empty($data['CTFIN'])) { ?>
																				<span title="Process" class="badge bg-green"><i class='fa fa-check-circle'></i></span>

																			<?php }
																			?>
																		</td>
																		<td style="text-align: center;" nowrap>
																			<?php if (!empty($data['CTRR'])) { ?>
																				<span title="Process" class="badge bg-green"><i class='fa fa-check-circle'></i></span>

																			<?php }
																			?>
																		</td>
																	</tr>
															<?php endforeach;
															} ?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
										<div class="row">

											<div class="col-sm-6">
												<?php //=$currentpage
												?>
											</div>
											<div class="col-sm-6">

											</div>

										</div>
									</div>
								</div>
							</div>
						</div>

					</div>
		</section>
	</div>