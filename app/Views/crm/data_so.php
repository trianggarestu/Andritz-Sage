<style>
	.input-sm {
		padding: 4px 4px;
	}
</style>

<div class="content-wrapper">
	<section class="content-header">
		<h1>SALES ORDER NOTIFICATION</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Data Sales Order - CS REP</li>
		</ol>
	</section>

	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<a href="#" title="Tambah Data Warga" class="btn btn-social btn-flat bg-olive btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-refresh"></i> Update SO Open from Sage</a>
						<a href="#" class="btn btn-social btn-flat bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Cetak" target="_blank"><i class="fa fa-print"></i> Cetak
						</a>
						<a href="<?= site_url("covid19/daftar/unduh") ?>" class="btn btn-social btn-flat bg-navy btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Unduh" target="_blank"><i class="fa fa-download"></i> Unduh
						</a>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-sm-12">
								<div class="dataTables_wrapper form-inline dt-bootstrap no-footer">
									<form id="mainform" name="mainform" action="" method="post">
										<div class="row">
											<div class="col-sm-6">

											</div>
											<div class="col-sm-6">
												<div class="box-tools">
													<div class="input-group input-group-sm pull-right">
														<input name="cari" id="cari" class="form-control" placeholder="Cari..." type="text" value="" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', 'http://localhost:8082/OpenSID/index.php/surat_masuk/search');$('#'+'mainform').submit();}">
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
													<table class="table table-bordered dataTable table-striped table-hover">
														<thead class="bg-gray disabled color-palette">
															<tr>
																<th>No</th>
																<th>Aksi</th>
																<th>Customer Name</th>
																<th>Project No.</th>
																<th>CRM Number</th>
																<th>Customer Email</th>
																<th>PO Customer</th>
																<th>Sales Person</th>
																<th>Status SO</th>

															</tr>
														</thead>
														<tbody>

															<tr>
																<td align="center" width="2">1</td>
																<td nowrap>

																	<a href="#" data-title="Rincian Sales Order" title="Rincian Sales Order" class="btn bg-purple btn-flat btn-sm"><i class="fa fa-list-ol"></i></a>
																	<a href="#" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Make Notif to Procurement" title="Make Notif to Procurement" class="btn bg-green btn-flat btn-sm"><i class="fa fa-sign-out "></i></a>

																</td>
																<td>PT INDAH KIAT PULP & PAPER TBK.</td>
																<td nowrap>SP1800IKPA</td>
																<td>3889567</td>

																<td><a href="">saputra_ade@app.co.id</a></td>
																<td>47625825</td>

																<td>Rachmat</td>
																<td><strong>Open</strong></td>
															</tr>

															<tr>
																<td align="center" width="2">3</td>
																<td nowrap>

																	<a href="<?= base_url() ?>salesorder/soview/1" data-title="Rincian Sales Order" title="Rincian Sales Order" class="btn bg-purple btn-flat btn-sm"><i class="fa fa-list-ol"></i></a>
																	<a href="#" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Make Notif to Procurement" title="Make Notif to Procurement" class="btn bg-green btn-flat btn-sm"><i class="fa fa-sign-out "></i></a>

																</td>
																<td>PT. OKI Pulp & Paper Mills</td>
																<td nowrap>SP1802OKIA</td>
																<td>3890538</td>

																<td><a href="">Trisna_Winarta@app.co.id</a></td>
																<td>45836578</td>
																<td>Rachmat</td>

																<td><strong>Open</strong></td>
															</tr>
															<tr>
																<td align="center" width="2">4</td>
																<td nowrap>

																	<a href="#" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Rincian Sales Order" title="Rincian Sales Order" class="btn bg-purple btn-flat btn-sm"><i class="fa fa-list-ol"></i></a>
																	<a href="#" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Make Notif to Procurement" title="Make Notif to Procurement" class="btn bg-green btn-flat btn-sm"><i class="fa fa-sign-out "></i></a>

																</td>
																<td>PT. OKI Pulp & Paper Mills</td>
																<td nowrap>SP1804OKIA</td>
																<td>3891307</td>

																<td><a href="">tomi_wahyudi@app.co.id</a></td>
																<td>45836041</td>
																<td>Rachmat</td>

																<td><strong>Open</strong></td>
															</tr>

															<?php //endforeach; 
															?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</form>
									<div class="row">
										<div class="col-sm-6">
											<div class="dataTables_length">
												<form id="paging" action="" method="post" class="form-horizontal">
													<label>
														Tampilkan
														<select name="per_page" class="form-control input-sm" onchange="$('#paging').submit()">
															<option value="10" <?php //selected($per_page, 10); 
																				?>>10</option>
															<option value="100" <?php //selected($per_page, 100); 
																				?>>100</option>
															<option value="200" <?php //selected($per_page, 200); 
																				?>>200</option>
														</select>
														Dari
														<strong>8</strong>
														Total Data
													</label>
												</form>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="dataTables_paginate paging_simple_numbers">
												<ul class="pagination">
													<?php //if ($paging->start_link) : 
													?>
													<li>
														<a href="<? //= site_url('covid19/data_pemudik/' . $paging->start_link) 
																	?>" aria-label="First"><span aria-hidden="true">Awal</span></a>
													</li>
													<?php //endif; 
													?>
													<?php //if ($paging->prev) : 
													?>
													<li>
														<a href="<? //= site_url('covid19/data_pemudik/' . $paging->prev) 
																	?>" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a>
													</li>
													<?php //endif; 
													?>
													<?php //for ($i = $paging->start_link; $i <= $paging->end_link; $i++) : 
													?>

													<li class='active'>
														<a href="<? //= site_url('covid19/data_pemudik/' . $i) 
																	?>"><? //= $i 
																		?>
															1</a>
													</li>
													<?php //endfor; 
													?>
													<?php //if ($paging->next) : 
													?>
													<li>
														<a href="<? //= site_url('covid19/data_pemudik/' . $paging->next) 
																	?>" aria-label="Next"><span aria-hidden="true">&raquo;</span></a>
													</li>
													<?php //endif; 
													?>
													<?php //if ($paging->end_link) : 
													?>
													<li>
														<a href="<? //= site_url('covid19/data_pemudik/' . $paging->end_link) 
																	?>" aria-label="Last"><span aria-hidden="true">Akhir</span></a>
													</li>
													<?php //endif; 
													?>
												</ul>
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