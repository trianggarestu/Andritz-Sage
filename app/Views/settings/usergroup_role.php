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
		<h1>Manage Security Groups</h1>
		<ol class="breadcrumb">
			<li><a href="<?= base_url() ?>administration"><i class="fa fa-home"></i> Home</a></li>
			<li class="active">Group Role</li>
		</ol>
	</section>


	<section class="content" id="maincontent">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">

					<div class="box-body">
						<div class="box-header with-border">
							<a href="<?= base_url('usergroupsetup') ?>" class="btn btn-social btn-flat btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-o-left"></i>Back to Groups</a>

						</div>
						<div class="box-header with-border">
							<h3 class="box-title"><strong>Security Group for <?= $groupname ?></strong> (<small><?= $groupdesc ?></small>)</h3>
						</div>
						<div class="row">
							<div class="col-sm-9">
								<div class="table-responsive">
									<table class="table table-bordered dataTable table-hover">
										<thead class="bg-gray disabled color-palette">

											<tr>
												<th width="5%">No.</td>
												<th>Menu</td>

												<th>Check Menu</td>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td></td>
												<td></td>
												<td></td>
											</tr>
											<?php
											echo form_open($form_action, array('target' => '', 'id' => 'formgrouprole'));
											$key = 0;
											$navh = '';
											$start = 0;
											foreach ($grouprole_data as $userrole) :
												if ($navh != $userrole['IDNAVL1']) {
											?>

													<tr style="background-color:lightgrey;">
														<td></td>
														<td>
															<i class="fa <?php echo $userrole['ICONDESC']; ?> text-blue"></i>
															<strong>
																<?php echo $userrole['NAVL1NAME']; ?>
															</strong>
														</td>
														<td></td>
													</tr>
												<?php
													$navh = $userrole['IDNAVL1'];
												} ?>

												<tr>
													<td><?php echo ++$start ?></td>
													<td>
														<i class="fa <?php echo $userrole['fa_icon']; ?> text-purple"></i>
														<?php echo $userrole['NAVDL1NAME']; ?>
													</td>

													<td style="text-align:center">
														<input type="hidden" name="grisactive[<?php echo $userrole['IDNAVDL1']; ?>]" value="0" />
														<input type="checkbox" name="grisactive[<?php echo $userrole['IDNAVDL1']; ?>]" value="1" <?php if ($userrole['ISACTIVE'] == 0) {
																																						echo '';
																																					} else {
																																						echo 'checked';
																																					} ?> />

													</td>
												</tr>
												<input type="hidden" name="GROUPID[<?php echo $userrole['IDNAVDL1']; ?>]" value="<?php echo $groupid; ?>" />
												<input type="hidden" name="IDNAVL1[<?php echo $userrole['IDNAVDL1']; ?>]" value="<?php echo $userrole['IDNAVL1']; ?>" />
												<input type="hidden" name="IDNAVDL1[<?php echo $userrole['IDNAVDL1']; ?>]" value="<?php echo $userrole['IDNAVDL1']; ?>" />
											<?php endforeach; ?>
										</tbody>
									</table>
									<div class='box-footer'>
										<div class='col-xs-9'>
											<div class='col-sm-3'>
											</div>
											<div class="col-sm-6">
												<input type="hidden" name="idgrusergroup" value="<?php echo $groupid; ?>" />
												<button type='submit' name="saved_grouprole" class='btn btn-social btn-flat btn-info btn-sm confirm'><i class='fa fa-check'></i> <?= $button ?></button>
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