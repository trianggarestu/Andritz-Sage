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
						<a href="<?= base_url() ?>arrangeshipmentlist/" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i> Refresh</a>
						<a href="<?= base_url("arrangeshipmentlist/preview") ?>" target="_blank" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Preview"><i class="fa fa-print"></i> Preview
						</a>
						<a href="<?= base_url("arrangeshipmentlist/export_excel") ?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh" target="_blank"><i class="fa fa-download"></i> Download
						</a>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<form id="mainform" name="mainform" action="" method="post">
										<div class="row">
											<div class="col-sm-8">

											</div>
											<div class="col-sm-4">
												<div class="box-tools">
													<div class="input-group input-group-sm pull-right">
														<input name="cari" id="cari" class="form-control" placeholder="Search..." type="text" value="" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', 'http://localhost:8082/OpenSID/index.php/surat_masuk/search');$('#'+'mainform').submit();}">
														<div class="input-group-btn">
															<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', 'http://localhost:8082/OpenSID/index.php/surat_masuk/search');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<div class="table-responsive">
													<table class="table table-bordered table-striped dataTable table-hover nowrap">
														<thead class="bg-gray disabled color-palette">
															<tr>
																<th>No.</th>

																<th>xxx</th>
																<th>xx</th>
																<th>Status</th>
																<th style="background-color: white;"></th>
																<th>Invoice Number</th>

																<th>Status</th>
															</tr>
														</thead>
														<tbody>
															<?php
															$no = 0;
															?>
															<?php foreach ($fin_data as $data_list) {
																//$povendate = substr($data_list['PODATE'], 4, 2) . "/" . substr($data_list['PODATE'], 6, 2) . "/" .  substr($data_list['PODATE'], 0, 4);


															?>

																<tr>
																	<td><?= ++$no ?></td>
																	<td></td>
																	<td></td>
																	<td></td>

																	<td style="background-color: white;"></td>
																	<td><?= $data_list['IDINVC'] ?></td>

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