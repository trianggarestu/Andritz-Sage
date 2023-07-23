<style>
	.input-sm {
		padding: 4px 4px;
	}
</style>

<div class="content-wrapper">
	<section class="content-header">
		<h1>Fill A/R Invoice List</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Fill A/R Invoice List</li>
		</ol>
	</section>

	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="<?= base_url() ?>fillinvoicelist/" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i> Refresh</a>
						<a href="<?= base_url("fillinvoicelist/preview") ?>" target="_blank" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Preview"><i class="fa fa-print"></i> Preview
						</a>
						<a href="<?= base_url("fillinvoicelist/export_excel") ?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh" target="_blank"><i class="fa fa-download"></i> Download
						</a>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<form id="mainform" name="mainform" action="" method="post">
										<div class="row">
											<div class="col-sm-9">
												<label for="daterange">Filter by Invoice Date : </label>
												<div class="input-group input-group-sm date">
													<div class="input-group-addon">From Date :
														<i class="fa fa-calendar"></i>
													</div>
													<input class="datepicker form-control input-sm required" id="from_date" name="from_date" type="text" value="<?= $def_fr_date ?>" readonly>
												</div>
												<div class="input-group input-group-sm date">
													<div class="input-group-addon">To Date :
														<i class="fa fa-calendar"></i>
													</div>
													<input class="datepicker form-control input-sm required" id="to_date" name="to_date" type="text" value="<?= $def_to_date ?>" readonly>

													<div class="input-group-btn">
														<button type="submit" class="btn btn-default bg-maroon" onclick="$('#'+'mainform').attr('action', '<?= base_url('fillinvoicelist/search') ?>');$('#'+'mainform').submit();"><i class="fa fa-filter"></i>Go!</button>

													</div>
												</div>
											</div>
											<div class="col-sm-3">
												<div class="box-tools">
													<div class="input-group input-group-sm pull-right">
														<input name="cari" id="cari" class="form-control" placeholder="Search..." type="text" value="<?= $keyword ?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= base_url('fillinvoicelist/search') ?>');$('#'+'mainform').submit();}">
														<div class="input-group-btn">
															<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= base_url('fillinvoicelist/search') ?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
														</div>
													</div>
												</div>
											</div>
										</div>
									</form>
									<div class="row">
										<div class="col-sm-12">
											<div class="table-responsive">
												<table class="table table-bordered table-striped dataTable table-hover nowrap">
													<thead class="bg-gray disabled color-palette">
														<tr>
															<th>No.</th>
															<th>Invoice Number</th>
															<th>Invioce Date</th>
															<th>Origin D/N Date</th>



															<th style="background-color: white;"></th>
															<th>Contract</th>
															<th>Project</th>
															<th>Contract Desc</th>
															<th>RR Status</th>
															<th>Status</th>


														</tr>
													</thead>
													<tbody>
														<?php
														$no = 0;
														?>
														<?php foreach ($fin_data as $data_list) {
															$invdate = substr($data_list['DATEINVC'], 4, 2) . "/" . substr($data_list['DATEINVC'], 6, 2) . "/" .  substr($data_list['DATEINVC'], 0, 4);
															$orgdate = substr($data_list['ORIGDNRCPFINDATE'], 4, 2) . "/" . substr($data_list['ORIGDNRCPFINDATE'], 6, 2) . "/" .  substr($data_list['ORIGDNRCPFINDATE'], 0, 4);

														?>

															<tr>
																<td><?= ++$no ?></td>
																<td><?= $data_list['IDINVC'] ?></td>
																<td><?= $invdate; ?></td>
																<td><?= $orgdate; ?></td>





																<td style="background-color: white;"></td>
																<td><?= $data_list['CONTRACT']; ?></td>
																<td><?= $data_list['PROJECT']; ?></td>
																<td nowrap><?= $data_list['CTDESC']; ?></td>
																<td><?php $postingstat = $data_list['RRSTATUS'];
																	switch ($postingstat) {
																		case "0":
																			echo "Open";
																			break;
																		case "1":
																			echo "Done";
																			break;
																		default:
																			echo "Done";
																	} ?>
																</td>
																<td><?php $dnpostingstat = $data_list['POSTINGSTAT'];
																	switch ($dnpostingstat) {
																		case "0":
																			echo "Open";
																			break;
																		case "1":
																			echo "Posted";
																			break;
																		case "2":
																			echo "Deleted";
																			break;
																		default:
																			echo "Posted";
																	} ?>
																</td>


															</tr>

														<?php } ?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
									</form>
									<div class="row">
										<!-- Pagination template-->
										<div class="col-sm-6">

										</div>
										<div class="col-sm-6">
											<div class="dataTables_paginate paging_simple_numbers">
												<?= $pager->links('fin_posting_list', 'bootstrap_pagination');
												//$pager = \Config\Services::pager();
												?>
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