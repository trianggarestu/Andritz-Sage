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
			<h1>Manage Users</h1>
			<ol class="breadcrumb">
				<li><a href="<?= base_url() ?>administration"><i class="fa fa-home"></i> Home</a></li>


				<li class="active">Users</li>

			</ol>
		</section>


		<section class="content" id="maincontent">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-info">
						<div class="box-header with-border">

							<a href="<?= base_url() ?>usersetup/add" title="add new user" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
								<i class="fa fa-plus"></i>Add New User
							</a>
						</div>


						<div class="box-body">

							<div class="row">
								<div class="col-sm-12">
									<div class="table-responsive">
										<table class="table table-bordered dataTable table-hover">
											<thead class="bg-gray disabled color-palette">
												<tr>
													<th width="1%">No</th>
													<th width="5%">Action</th>
													<th>UserName</th>
													<th>Name</th>
													<th>Email</th>
													<th>Groups</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$no = 0;
												foreach ($users_data as $data) : ?>
													<tr>
														<td class="text-center"><?= ++$no; ?></td>
														<td nowrap>
															<a href="<?= base_url() . 'usersetup/formupdate/' . $data['USERUNIQ']; ?>" class=" btn bg-orange btn-flat btn-sm" title="Update Data"><i class="fa fa-edit"></i></a>
															<a href="<?= base_url() . 'usersetup/delete/' . $data['USERUNIQ']; ?>" data-href="#" class="btn bg-maroon btn-flat btn-sm" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
														</td>
														<td><?= $data['USERNAME'] ?></td>
														<td><?= $data['NAME'] ?></td>
														<td><?= $data['EMAIL'] ?></td>
														<td><?= $data['GROUPNAME'] ?></td>
													</tr>
												<?php endforeach; ?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>

					</div>
		</section>
	</div>