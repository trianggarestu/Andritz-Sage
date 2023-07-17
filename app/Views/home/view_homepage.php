<!-- Perubahan script coding untuk bisa menampilkan SID Home dalam bentuk tampilan bootstrap (AdminLTE)  -->
<style type="text/css">
	.text-white {
		color: white;
	}

	.pengaturan {
		float: left;
		padding-left: 10px;
	}
</style>
<div class="content-wrapper">
	<section class='content-header'>
		<h1>Dashboard <small>Order Tracking</small></h1>
		<ol class='breadcrumb'>
			<li><a href='<?= site_url() ?>'><i class='fa fa-home'></i> Home</a></li>
			<li class='active'>Order Tracking</li>
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
	<section class='content' id="maincontent">
		<div class='row'>
			<div class='col-md-8'>
				<?php if ($usergrplgn == 1 or $issuperuserlgn == 1) : ?>
					<!-- TABLE: LATEST ORDERS -->
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Latest Sales Orders</h3>

							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
								</button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
							</div>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<div class="table-responsive">
								<table class="table no-margin">
									<thead>
										<tr>
											<th>PO Cust. Date</th>
											<th>Contract</th>
											<th>Description</th>
											<th>Customer</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($csr_data as $data) :
											$crmpodate = substr($data['PODATECUST'], 4, 2) . "/" . substr($data['PODATECUST'], 6, 2) . "/" .  substr($data['PODATECUST'], 0, 4); ?>
											<tr>

												<td><?= $crmpodate ?></td>
												<td><a href="#"><?= $data['CONTRACT'] ?></a></td>
												<td><?= $data['CTDESC'] ?></td>
												<td><?= $data['NAMECUST'] ?></td>
												<td><?php $postingstat = $data['POSTINGSTAT'] . $data['OFFLINESTAT'];
													switch ($postingstat) {
														case "00":
															echo "<span class='label label-warning'>Open</span>";
															break;
														case "11":
															echo "<span class='label label-warning'>Posted Pending Notif</span>";
															break;
														case "10":
															echo "<span class='label label-success'>Posted & Sending Notif</span>";
															break;
														case "20":
															echo "<span class='label label-danger'>Deleted</span>";
															break;
														case "21":
															echo "<span class='label label-danger'>Deleted</span>";
															break;
														default:
															echo "<span class='label label-warning'>Open</span>";
													} ?>

												</td>

											</tr>
										<?php endforeach; ?>

									</tbody>
								</table>
							</div>
							<!-- /.table-responsive -->
						</div>
						<!-- /.box-body -->
						<div class="box-footer clearfix">

							<a href="<?= base_url('/salesorderopen') ?>" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>
						</div>
						<!-- /.box-footer -->
					</div>
				<?php endif; ?>
				<!-- /.box -->
				<?php if ($usergrplgn == 2 or $issuperuserlgn == 1) : ?>
					<!-- TABLE: LATEST ORDERS -->
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Latest Purchase Requisition</h3>

							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
								</button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
							</div>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<div class="table-responsive">
								<table class="table no-margin">
									<thead>
										<tr>
											<th>Requisition Date</th>
											<th>Requisition No.</th>
											<th>Contract</th>
											<th>Description</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($rqn_data as $data) :
											$rqndate = substr($data['RQNDATE'], 4, 2) . "/" . substr($data['RQNDATE'], 6, 2) . "/" .  substr($data['RQNDATE'], 0, 4); ?>
											<tr>

												<td><?= $rqndate ?></td>
												<td><?= $data['RQNNUMBER'] ?></td>
												<td><a href="#"><?= $data['CONTRACT'] ?></a></td>
												<td><?= $data['CTDESC'] ?></td>

												<td>
													<?php $postingstat = $data['POSTINGSTAT'] . $data['OFFLINESTAT'];
													switch ($postingstat) {
														case "00":
															echo "<span class='label label-warning'>Open</span>";
															break;
														case "11":
															echo "<span class='label label-warning'>Posted Pending Notif</span>";
															break;
														case "10":
															echo "<span class='label label-success'>Posted & Sending Notif</span>";
															break;
														case "20":
															echo "<span class='label label-danger'>Deleted</span>";
															break;
														case "21":
															echo "<span class='label label-danger'>Deleted</span>";
															break;
														default:
															echo "<span class='label label-warning'>Open</span>";
													} ?>

												</td>

											</tr>
										<?php endforeach; ?>

									</tbody>
								</table>
							</div>
							<!-- /.table-responsive -->
						</div>
						<!-- /.box-body -->
						<div class="box-footer clearfix">

							<a href="<?= base_url('/requisition') ?>" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>
						</div>
						<!-- /.box-footer -->
					</div>
				<?php endif; ?>

				<?php if ($usergrplgn == 3 or $issuperuserlgn == 1) : ?>
					<!-- TABLE: LATEST ORDERS -->
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Latest Purchase Orders</h3>

							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
								</button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
							</div>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<div class="table-responsive">
								<table class="table no-margin">
									<thead>
										<tr>
											<th>P/O Date</th>
											<th>RQN. Number</th>
											<th>P/O Number</th>
											<th>Origin Country</th>
											<th>Remarks</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($po_data as $data) :
											$podate = substr($data['PODATE'], 4, 2) . "/" . substr($data['PODATE'], 6, 2) . "/" .  substr($data['PODATE'], 0, 4); ?>
											<tr>

												<td><?= $podate ?></td>
												<td><?= $data['RQNNUMBER'] ?></td>
												<td><?= $data['PONUMBER'] ?></td>
												<td><?= $data['ORIGINCOUNTRY'] ?></td>
												<td><?= $data['POREMARKS'] ?></td>

												<td>
													<?php $postingstat = $data['POSTINGSTAT'] . $data['OFFLINESTAT'];
													switch ($postingstat) {
														case "00":
															echo "<span class='label label-warning'>Open</span>";
															break;
														case "11":
															echo "<span class='label label-warning'>Posted Pending Notif</span>";
															break;
														case "10":
															echo "<span class='label label-success'>Posted & Sending Notif</span>";
															break;
														case "20":
															echo "<span class='label label-danger'>Deleted</span>";
															break;
														case "21":
															echo "<span class='label label-danger'>Deleted</span>";
															break;
														default:
															echo "<span class='label label-warning'>Open</span>";
													} ?>

												</td>

											</tr>
										<?php endforeach; ?>

									</tbody>
								</table>
							</div>
							<!-- /.table-responsive -->
						</div>
						<!-- /.box-body -->
						<div class="box-footer clearfix">

							<a href="<?= base_url('/purchaseorder') ?>" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>
						</div>
						<!-- /.box-footer -->
					</div>
				<?php endif; ?>

				<?php if ($usergrplgn == 4 or $issuperuserlgn == 1) : ?>
					<!-- TABLE: LATEST ORDERS -->
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Latest Logistics/Arrange Shipments</h3>

							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
								</button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
							</div>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<div class="table-responsive">
								<table class="table no-margin">
									<thead>
										<tr>
											<th>P/O Date</th>
											<th>P/O Number</th>
											<th>PIB Date</th>
											<th>VENDSHISTATUS</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($logistics_data as $data) :
											$podate = substr($data['PODATE'], 4, 2) . "/" . substr($data['PODATE'], 6, 2) . "/" .  substr($data['PODATE'], 0, 4);
											$pibdate = substr($data['PIBDATE'], 4, 2) . "/" . substr($data['PIBDATE'], 6, 2) . "/" .  substr($data['PIBDATE'], 0, 4); ?>
											<tr>

												<td><?= $podate ?></td>
												<td><?= $data['PONUMBER'] ?></td>
												<td><?= $pibdate ?></td>
												<td><?= $data['VENDSHISTATUS'] ?></td>

												<td><?php $postingstat = $data['POSTINGSTAT'] . $data['OFFLINESTAT'];
													switch ($postingstat) {
														case "00":
															echo "<span class='label label-warning'>Open</span>";
															break;
														case "11":
															echo "<span class='label label-warning'>Posted Pending Notif</span>";
															break;
														case "10":
															echo "<span class='label label-success'>Posted & Sending Notif</span>";
															break;
														case "20":
															echo "<span class='label label-danger'>Deleted</span>";
															break;
														case "21":
															echo "<span class='label label-danger'>Deleted</span>";
															break;
														default:
															echo "<span class='label label-warning'>Open</span>";
													} ?>

												</td>

											</tr>
										<?php endforeach; ?>

									</tbody>
								</table>
							</div>
							<!-- /.table-responsive -->
						</div>
						<!-- /.box-body -->
						<div class="box-footer clearfix">

							<a href="<?= base_url('/purchaseorder') ?>" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>
						</div>
						<!-- /.box-footer -->
					</div>
				<?php endif; ?>

				<?php if ($usergrplgn == 5 or $issuperuserlgn == 1) : ?>
					<!-- TABLE: LATEST ORDERS -->
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Latest Good Receipts</h3>

							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
								</button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
							</div>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<div class="table-responsive">
								<table class="table no-margin">
									<thead>
										<tr>
											<th>G/R Date</th>
											<th>P/O Number</th>
											<th>G/R Number</th>
											<th>Vendor</th>
											<th>Description</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($gr_data as $data) :
											$grdate = substr($data['RECPDATE'], 4, 2) . "/" . substr($data['RECPDATE'], 6, 2) . "/" .  substr($data['RECPDATE'], 0, 4); ?>
											<tr>

												<td><?= $grdate ?></td>
												<td><?= $data['PONUMBER'] ?></td>
												<td><?= $data['RECPNUMBER'] ?></td>
												<td><?= $data['VDNAME'] ?></td>
												<td><?= $data['DESCRIPTIO'] ?></td>

												<td><?php $postingstat = $data['POSTINGSTAT'] . $data['OFFLINESTAT'];
													switch ($postingstat) {
														case "00":
															echo "<span class='label label-warning'>Open</span>";
															break;
														case "11":
															echo "<span class='label label-warning'>Posted Pending Notif</span>";
															break;
														case "10":
															echo "<span class='label label-success'>Posted & Sending Notif</span>";
															break;
														case "20":
															echo "<span class='label label-danger'>Deleted</span>";
															break;
														case "21":
															echo "<span class='label label-danger'>Deleted</span>";
															break;
														default:
															echo "<span class='label label-warning'>Open</span>";
													} ?>

												</td>

											</tr>
										<?php endforeach; ?>

									</tbody>
								</table>
							</div>
							<!-- /.table-responsive -->
						</div>
						<!-- /.box-body -->
						<div class="box-footer clearfix">

							<a href="<?= base_url('/goodreceipt') ?>" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>
						</div>
						<!-- /.box-footer -->
					</div>
				<?php endif; ?>

				<?php if ($usergrplgn == 6 or $usergrplgn == 7 or $issuperuserlgn == 1) : ?>
					<!-- TABLE: LATEST ORDERS -->
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Latest Shipments</h3>

							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
								</button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
							</div>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<div class="table-responsive">
								<table class="table no-margin">
									<thead>
										<tr>
											<th>Shipment Date</th>
											<th>Doc. Number</th>
											<th>Shipment Number</th>
											<th>Cust. Rcp Date</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($shi_data as $data) :
											$shidate = substr($data['SHIDATE'], 4, 2) . "/" . substr($data['SHIDATE'], 6, 2) . "/" .  substr($data['SHIDATE'], 0, 4);
											$cusrcpdate = substr($data['CUSTRCPDATE'], 4, 2) . "/" . substr($data['CUSTRCPDATE'], 6, 2) . "/" .  substr($data['CUSTRCPDATE'], 0, 4); ?>
											<tr>

												<td><?= $shidate ?></td>
												<td><?= $data['DOCNUMBER'] ?></td>
												<td><?= $data['SHINUMBER'] ?></td>
												<td><?= $cusrcpdate ?></td>

												<td><?php if ($data['POSTINGSTAT'] == 1 and $data['OFFLINESTAT'] == 0) : ?>
														<span class="label label-success">Processed</span>
													<?php endif; ?>
													<?php if ($data['POSTINGSTAT'] == 1 and $data['OFFLINESTAT'] == 1) : ?>
														<span class="label label-danger">Pending Notif</span>
													<?php endif; ?>
													<?php if ($data['POSTINGSTAT'] == 0) : ?>
														<span class="label label-warning">Open</span>
													<?php endif; ?>

												</td>

											</tr>
										<?php endforeach; ?>

									</tbody>
								</table>
							</div>
							<!-- /.table-responsive -->
						</div>
						<!-- /.box-body -->
						<div class="box-footer clearfix">

							<a href="<?= base_url('/deliveryorders') ?>" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>
						</div>
						<!-- /.box-footer -->
					</div>
				<?php endif; ?>

				<?php if ($usergrplgn == 8 or $issuperuserlgn == 1) : ?>
					<!-- TABLE: LATEST ORDERS -->
					<div class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">Latest Finance</h3>

							<div class="box-tools pull-right">
								<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
								</button>
								<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
							</div>
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<div class="table-responsive">
								<table class="table no-margin">
									<thead>
										<tr>
											<th>A/R Invoice Date</th>
											<th>A/R Invoice No.</th>
											<th>Finance Status</th>
											<th>RR Status</th>
											<th>Status</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($fin_data as $data) :
											$invdate = substr($data['DATEINVC'], 4, 2) . "/" . substr($data['DATEINVC'], 6, 2) . "/" .  substr($data['DATEINVC'], 0, 4); ?>
											<tr>

												<td><?= $invdate ?></td>
												<td><?= $data['IDINVC'] ?></td>
												<td><?php if ($data['FINSTATUS'] == 1) : ?>
														<span class="label label-warning">Partial</span>
													<?php endif; ?>
													<?php if ($data['FINSTATUS'] == 2) : ?>
														<span class="label label-success">Completed</span>
													<?php endif; ?>
												</td>
												<td><?php if ($data['RRSTATUS'] == 1 or empty($data['RRSTATUS'])) : ?>
														<span class="label label-warning">PENDING</span>
													<?php endif; ?>
													<?php if ($data['RRSTATUS'] == 2) : ?>
														<span class="label label-success">DONE</span>
													<?php endif; ?>
												</td>

												<td><?php if ($data['POSTINGSTAT'] == 1 and $data['OFFLINESTAT'] == 0) : ?>
														<span class="label label-success">Processed</span>
													<?php endif; ?>
													<?php if ($data['POSTINGSTAT'] == 1 and $data['OFFLINESTAT'] == 1) : ?>
														<span class="label label-danger">Pending Notif</span>
													<?php endif; ?>
													<?php if ($data['POSTINGSTAT'] == 0) : ?>
														<span class="label label-warning">Open</span>
													<?php endif; ?>

												</td>

											</tr>
										<?php endforeach; ?>

									</tbody>
								</table>
							</div>
							<!-- /.table-responsive -->
						</div>
						<!-- /.box-body -->
						<div class="box-footer clearfix">

							<a href="<?= base_url('/fillinvoice') ?>" class="btn btn-sm btn-default btn-flat pull-right">View All Orders</a>
						</div>
						<!-- /.box-footer -->
					</div>
				<?php endif; ?>

			</div>
			<!-- /.col -->

			<div class='col-md-4'>
				<!-- PRODUCT LIST -->
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title">Recently Email Inbox</h3>

						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
							</button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<ul class="products-list product-list-in-box">
							<?php foreach ($mailbox_in_data as $inbox_data) : ?>
								<li class="item">
									<div class="product-img">
										<img src="<?php if (empty($inbox_data['PATH_PHOTO'])) {
														echo base_url('assets/files/user_pict/kuser.png');
													} else {
														echo base_url($inbox_data['PATH_PHOTO']);
													} ?>" alt="Product Image">
									</div>
									<div class="product-info">
										<a href="<?= base_url(); ?>mailbox/view_messages/<?= $inbox_data['MAILSEQ'] ?>" data-remote="false" data-toggle="modal" data-tittle="View Messages" data-target="#modalBox" class="product-title"><?= $inbox_data['FROM_NAME'] ?>
											<?php if ($inbox_data['IS_READ'] == 0) : ?>
												<span class="label label-danger pull-right">Unread</span>
											<?php endif ?>
										</a>
										<span class="product-description">
											<?= substr($inbox_data['SUBJECT'], 0, 40); ?>
										</span>
									</div>
								</li>
							<?php endforeach; ?>

						</ul>
					</div>
					<!-- /.box-body -->
					<div class="box-footer text-center">
						<a href="<?= base_url('mailbox/inbox') ?>">View All Inbox</a>
					</div>
					<!-- /.box-footer -->
				</div>
				<!-- /.box -->
			</div>
		</div>


</div>
</section>
</div>