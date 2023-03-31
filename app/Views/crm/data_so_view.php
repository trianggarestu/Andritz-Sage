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
		<h1>Daftar Anggota Keluarga</h1>
		<ol class="breadcrumb">
			<li><a href="<?= site_url('hom_sid') ?>"><i class="fa fa-home"></i> Home</a></li>
			<li><a href="<?= site_url('keluarga/clear') ?>"> Sales Order</a></li>
			<li class="active">View</li>
		</ol>
	</section>
	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="#" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Tambah Anggota Keluarga" title="Tambah Anggota Dari Penduduk Yang Sudah Ada" class="btn btn-social btn-flat btn-success btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class='fa fa-plus'></i> Tambah Anggota</a>
						<a href="#confirm-delete" title="Hapus Data" onclick="deleteAllBox('mainform','')" class="btn btn-social btn-flat	btn-danger btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block hapus-terpilih"><i class='fa fa-trash-o'></i> Hapus Data Terpilih</a>
						<a href="#" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-book"></i> Kartu Keluarga</a>
						<a href="#" class="btn btn-social btn-flat btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Keluarga">
							<i class="fa fa-arrow-circle-left "></i>Kembali Ke Daftar Keluarga
						</a>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="table-responsive">
									<table class="table table-bordered table-striped table-hover tabel-info">
										<tbody>
											<tr>
												<td width="15%">Nomor Kartu Keluarga (KK)</td>
												<td> : </td>
											</tr>
											<tr>
												<td>Kepala Keluarga</td>
												<td> : </td>
											</tr>
											<tr>
												<td>Alamat</td>
												<td> : </td>
											</tr>
											<tr>
												<td>

													<a href="" target="_blank">Program Bantuan</a>

													Program Bantuan

												</td>
												<td> :

													<a href="" target="_blank"><span class="label label-success"></span>&nbsp;</a>

												</td>
											</tr>
										</tbody>
									</table>
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
																<th width="5%">Aksi</th>
																<th>Inventory No.</th>
																<th>Material No. </th>
																<th>Item Description</th>
																<th>Qty</th>
																<th>UoM</th>
															</tr>
														</thead>
														<tbody>

															<tr>

																<td class="text-center">1</td>
																<td class="text-center" nowrap>
																	<a href="#" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Tracking Sales Order" title="Tracking Sales Order" class="btn bg-red btn-flat btn-sm"><i class="fa fa-history"></i></a>

																</td>
																<td></td>
																<td nowrap width="45%"></td>
																<td nowrap></td>
																<td></td>
																<td nowrap></td>
															</tr>

														</tbody>
													</table>
												</div>
											</div>
										</div>
									</form>
								</div>
							</div>
						</div>
						<div class='modal fade' id='confirm-status' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
							<div class='modal-dialog'>
								<div class='modal-content'>
									<div class='modal-header'>
										<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
										<h4 class='modal-title' id='myModalLabel'><i class='fa fa-exclamation-triangle text-red'></i> Konfirmasi</h4>
									</div>
									<div class='modal-body btn-info'>
										Apakah Anda yakin ingin memecah Data Keluarga ini?
									</div>
									<div class='modal-footer'>
										<button type="button" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
										<a class='btn-ok'>
											<button type="button" class="btn btn-social btn-flat btn-info btn-sm" id="ok-delete"><i class='fa fa-check'></i> Simpan</button>
										</a>
									</div>
								</div>
							</div>
						</div>
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
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<?php //$this->load->view('global/confirm_delete');
?>