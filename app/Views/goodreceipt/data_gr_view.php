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
						<a href="<?= base_url('goodreceiptlist') ?>" title="Back to Good Receipt List" class="btn btn-social btn-flat bg-aqua btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Back to Good Receipts List</a>
						<?php if ($rcpopen_data['POSTINGSTAT'] == 0 or $rcpopen_data['OFFLINESTAT'] == 1) {
						?>
							<a href="<?= base_url($link_action . $rcpopen_data['RCPUNIQ']) ?>" class="btn btn-social btn-flat <?= $btn_color ?> btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Posting"><i class="fa  fa-paper-plane-o"></i>
								<?= $button;
								?>
							</a>


							<a href="<?= base_url('goodreceipt/update/' . $rcpopen_data['POUNIQ']) ?>" title="Edit" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Back to Form Entry</a>
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
															<th colspan="3" class="subtitle_head"><strong>Good Receipts</strong></th>
														</tr>
														<tr>
															<td width="300">Receipt Number </td>
															<td width="1">:</td>
															<td><strong><?= $rcpopen_data['RECPNUMBER']; ?></strong></td>
														</tr>
														<tr>
															<td width="300">Receipt Date </td>
															<td width="1">:</td>
															<td><strong><?php

																		$dd = substr($rcpopen_data['RECPDATE'], 6, 2);
																		$mm = substr($rcpopen_data['RECPDATE'], 4, 2);
																		$yyyy = substr($rcpopen_data['RECPDATE'], 0, 4);
																		$rcpdate = $mm . '/' . $dd . '/' . $yyyy;
																		echo $rcpdate; ?></strong></td>
														</tr>
														<tr>
															<td width="300">Vendor Name </td>
															<td width="1">:</td>
															<td><strong><?= $rcpopen_data['VDNAME']; ?></strong></td>
														</tr>
														<tr>
															<td width="300">Receipt Description </td>
															<td width="1">:</td>
															<td><strong><?= $rcpopen_data['DESCRIPTIO']; ?></strong></td>
														</tr>
														<tr>
															<th colspan="3" class="subtitle_head"><strong>ITEM</strong></th>
														</tr>
														<tr>
															<td width="300">ITEM </td>
															<td width="1">:</td>
															<td><strong><?= $rcpopen_data['ITEMNO'] . ' - ' . $rcpopen_data['ITEMDESC']; ?></strong></td>
														</tr>
														<tr>
															<td width="300">QTY </td>
															<td width="1">:</td>
															<td><strong><?= number_format($rcpopen_data['RECPQTY'], 0, ",", ".") . ' (' . $rcpopen_data['RECPUNIT'] . ')'; ?></strong></td>
														</tr>
														<tr>
															<td width="300">Status Receipt </td>
															<td width="1">:</td>
															<td><strong><?php if ($rcpopen_data['GRSTATUS'] == 1) {
																			echo 'Completed';
																		} else {
																			echo 'Partial';
																		} ?></strong></td>
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