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
			<h1>Order Tracking Header List</h1>
			<ol class="breadcrumb">
				<li><a href="<?= base_url() ?>administration"><i class="fa fa-home"></i> Home</a></li>


				<li class="active">Rollback Process</li>

			</ol>
		</section>


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
													<label for="daterange">Filter by P/O Customer Date : </label>
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
															<button type="submit" class="btn btn-default bg-maroon" onclick="$('#'+'mainform').attr('action', '<?= base_url('rollbackprocess/search') ?>');$('#'+'mainform').submit();"><i class="fa fa-filter"></i>Go!</button>

														</div>
													</div>
												</div>
												<div class="col-sm-3">
													<div class="box-tools">
														<div class="input-group input-group-sm pull-right">
															<input name="cari" id="cari" class="form-control" placeholder="Search..." type="text" value="<?= $keyword ?>" onkeypress="if (event.keyCode == 13){$('#'+'mainform').attr('action', '<?= base_url('rollbackprocess/search') ?>');$('#'+'mainform').submit();}">
															<div class="input-group-btn">
																<button type="submit" class="btn btn-default" onclick="$('#'+'mainform').attr('action', '<?= base_url('rollbackprocess/search') ?>');$('#'+'mainform').submit();"><i class="fa fa-search"></i></button>
															</div>
														</div>
													</div>
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
																<th>Contract</th>
																<th>Desc</th>

															</tr>
														</thead>
														<tbody>
															<?php
															$no = 0;
															foreach ($otheader_data as $data) : ?>
																<tr>
																	<td class="text-center"><?= ++$no; ?></td>
																	<td nowrap>
																		<a href="<?= base_url() . 'menusetup/form/' . $data['CSRUNIQ']; ?>" class=" btn bg-orange btn-flat btn-sm" title="Update Data"><i class="fa fa-edit"></i></a>
																	</td>
																	<td><?= $data['CONTRACT'] ?></td>
																	<td><?= $data['CTDESC'] ?></td>
																</tr>
															<?php endforeach; ?>
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
												<div class="dataTables_paginate paging_simple_numbers">
													<?= $pager->links('csrposting_data', 'bootstrap_pagination');
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
		</section>
	</div>