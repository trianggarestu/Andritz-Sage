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

							<a href="<?= base_url() ?>usersetup/add" title="add new user" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
								<i class="fa fa-plus"></i>Add New User
							</a>
						</div>


						<div class="box-body">

							<div class="row">
								<div class="col-sm-12">

									<div class="table-responsive">
										<table class="table table-bordered table-striped dataTable table-hover nowrap" style="display:block; max-height:500px;">
											<thead class="bg-gray disabled color-palette">
												<tr>
													<th width="1%">No</th>
													<th width="5%">Action</th>
													<th>Photo</th>
													<th>UserName</th>
													<th>Name</th>
													<th>Email</th>
													<th>Groups</th>
												</tr>
											</thead>
											<tbody>
												<?php
												$no = 0;
												$grouph = '';
												foreach ($users_data as $data) :
													if ($grouph != $data['GROUPID']) {
												?>
														<thead class="bg-black disabled color-palette">
															<tr>
																<td></td>
																<td colspan="6">Group : <strong><?= $data['GROUPNAME'] ?></strong></td>
															</tr>
														</thead>
													<?php
														$grouph = $data['GROUPID'];
													} ?>
													<tr>
														<td class="text-center"><?= ++$no; ?></td>
														<td nowrap>
															<a href="<?= base_url() . 'usersetup/update/' . md5(trim($data['USERNAME'])); ?>" class=" btn bg-orange btn-flat btn-sm" title="Update Data"><i class="fa fa-edit"></i></a>
															<?php if ($data['INACTIVE'] == '0') : ?>
																<a href="<?= base_url("usersetup/setinactive/" . md5(trim($data['USERNAME']))) ?>" class="btn bg-navy btn-flat btn-sm" title="Set InActive"><i class="fa fa-unlock"></i></a>
															<?php elseif ($data['INACTIVE'] == '1') : ?>
																<a href="<?= base_url("usersetup/setactive/" . md5(trim($data['USERNAME']))) ?>" class="btn bg-navy btn-flat btn-sm" title="Set Active"><i class="fa fa-lock">&nbsp;</i></a>

															<?php endif ?>
															<a href="#" data-href="<?= base_url() . 'usersetup/delete/' . md5(trim($data['USERNAME'])) . '/1'; ?>" class="btn bg-maroon btn-flat btn-sm" title="Delete" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>
														</td>
														<td>
															<div class="user-panel">
																<div class="image2">
																	<img src="<?= !empty($data['PATH_PHOTO']) ? $data['PATH_PHOTO'] : base_url('assets/files/user_pict/kuser.png') ?>" class="img-circle" alt="Photo User" />
																</div>
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
								<div class="col-sm-12" style="text-align: left;">
									<code>Only deleted user with status inactive.</code>
								</div>
							</div>
						</div>

					</div>
		</section>
	</div>

	<?php echo view('settings/confirm_delete') ?>