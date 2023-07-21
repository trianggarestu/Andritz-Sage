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
		<h1>E-mail Template Settings</h1>
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>administration"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">mailtemplatesetup</li>
		</ol>
	</section>


	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<!--<a href="<?= base_url() ?>usergroupsetup/add" class="btn btn-social btn-flat btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Add Group">
							<i class="fa fa-plus"></i>Add Group
						</a>-->
					</div>
					<div class="box-body">

						<div class="row">
							<div class="col-sm-9">
								<div class="table-responsive">
									<table class="table table-bordered dataTable table-hover">
										<thead class="bg-gray disabled color-palette">
											<tr>
												<th width="1%">No</th>
												<th width="5%">Action</th>
												<th>E-mail Route</th>
												<th>Subject</th>


											</tr>
										</thead>
										<tbody>
											<?php
											$no = 0;
											foreach ($mailtemplate_data as $data) : ?>
												<tr>
													<td class="text-center"><?= ++$no; ?></td>
													<td nowrap>
														<a href="<?= base_url('mailtemplatesetup/update/' . $data['TMPLTUNIQ']); ?>" class=" btn bg-orange btn-flat btn-sm" title="Update Data"><i class="fa fa-edit"></i></a>
														<a href="<?= base_url() . 'usergroupsetup/role/'; ?>" class=" btn bg-aqua btn-flat btn-sm" title="Security Groups"><i class="fa fa-sitemap"></i></a>
													</td>
													<td>E-mail Template - Route from <strong><?= ucfirst(strtolower($data['ROUTEFROM'])) ?></strong> to <strong><?= ucfirst(strtolower($data['ROUTETO'])) ?></strong></td>
													<td><?= $data['SUBJECT_MAIL'] ?></td>

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