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
			<div class='col-md-12'>
				<div class='box box-info'>
					<div class='box-body'>
						<div class="col-lg-4 col-xs-4">
							<div class="small-box bg-orange">
								<div class="inner">
									<h3>1</h3>
									<p>Sales Order</p>
								</div>
								<div class="icon">
									<i class="ion ion-compose"></i>
								</div>
								<a href="#" class="small-box-footer">
									More <i class="fa fa-arrow-circle-right"></i>
								</a>
							</div>
						</div>

						<div class="col-lg-4 col-xs-4">
							<div class="small-box bg-yellow">
								<div class="inner">

									<h3>2</h3>

									<p>Requisition</p>
								</div>
								<div class="icon">
									<i class="ion ion-share"></i>
								</div>
								<a href="#" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a>
							</div>
						</div>
						<div class="col-lg-4 col-xs-4">
							<div class="small-box bg-green">
								<div class="inner">

									<h3>3</h3>

									<p>Purchase Orders</p>
								</div>
								<div class="icon">
									<i class="ion ion-calendar"></i>
								</div>
								<a href="#" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a>
							</div>
						</div>
						<div class="col-lg-4 col-xs-4">
							<div class="small-box bg-aqua">
								<div class="inner">

									<h3>4</h3>

									<p>Logistics</p>
								</div>
								<div class="icon">
									<i class="ion ion-cube"></i>
								</div>
								<a href="#" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a>
							</div>
						</div>
						<div class="col-lg-4 col-xs-4">
							<div class="small-box bg-blue">
								<div class="inner">

									<h3>5</h3>

									<p>Inventory</p>
								</div>
								<div class="icon">
									<i class="ion ion-folder"></i>
								</div>
								<a href="#" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a>
							</div>
						</div>
						<div class="col-lg-4 col-xs-4">
							<div class="small-box bg-purple">
								<div class="inner">
									<h3>6</h3>
									<p>Delivery</p>
								</div>
								<div class="icon">
									<i class="ion ion-document-text"></i>
								</div>
								<a href="#" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a>
							</div>
						</div>

						<div class="col-lg-4 col-xs-4">
							<div class="small-box bg-green">
								<div class="inner">
									<?php //foreach ($rtm as $data) : 
									?>
									<h3>7</h3>
									<?php //endforeach; 
									?>
									<p>Sales Admin</p>
								</div>
								<div class="icon">
									<i class="ion ion-clipboard"></i>
								</div>
								<a href="#" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a>
							</div>
						</div>
						<div class="col-lg-4 col-xs-4">
							<div class="small-box bg-red">
								<div class="inner">
									<?php //foreach ($kelompok as $data) : 
									?>
									<h3>8</h3>
									<?php //endforeach; 
									?>
									<p>Finance</p>
								</div>
								<div class="icon">
									<i class="ion ion-filing"></i>
								</div>
								<a href="#" class="small-box-footer">More <i class="fa fa-arrow-circle-right"></i></a>
							</div>
						</div>
						<div class="col-lg-4 col-xs-4">
							<div class="small-box bg-teal-gradient">
								<div class="inner">
									<h3>9</h3>
									<p>Monitoring Delivery Process</p>
								</div>
								<div class="icon">
									<i class="ion ion-monitor"></i>
								</div>
								<div class="small-box-footer">
									<a href="#" class="inner text-white pengaturan" title="Pengaturan Program Bantuan" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Pengaturan Program Bantuan"><i class="fa fa-gear"></i></a>

									<a href="#" class="inner text-white">More <i class="fa fa-arrow-circle-right"></i></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--<div class='col-md-6'>
				<div class='box box-info'>
					<?php //$this->load->view('home/about.php'); 
					?>
				</div>
			</div>-->
		</div>
	</section>
</div>