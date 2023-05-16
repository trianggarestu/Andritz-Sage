<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>
		PT. ANDRITZ - ORDER TRACKING
	</title>
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

	<link rel="shortcut icon" href="<?= base_url() ?>assets/images/logo/favicon.ico" />

	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?= base_url() ?>rss.xml" />

	<!-- Bootstrap 3.3.7 -->
	<link rel="stylesheet" href="<?= base_url() ?>assets/bootstrap/css/bootstrap.min.css">
	<!-- Jquery UI -->
	<link rel="stylesheet" href="<?= base_url() ?>assets/bootstrap/css/jquery-ui.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="<?= base_url() ?>assets/bootstrap/css/font-awesome.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="<?= base_url() ?>assets/bootstrap/css/ionicons.min.css">
	<!-- DataTables -->
	<link rel="stylesheet" href="<?= base_url() ?>assets/bootstrap/css/dataTables.bootstrap.min.css">
	<!-- bootstrap wysihtml5 - text editor -->
	<link rel="stylesheet" href="<?= base_url() ?>assets/bootstrap/css/bootstrap3-wysihtml5.min.css">
	<!-- Select2 -->
	<link rel="stylesheet" href="<?= base_url() ?>assets/bootstrap/css/select2.min.css">
	<!-- Bootstrap Color Picker -->
	<link rel="stylesheet" href="<?= base_url() ?>assets/bootstrap/css/bootstrap-colorpicker.min.css">
	<!-- Bootstrap Date time Picker -->
	<link rel="stylesheet" href="<?= base_url() ?>assets/bootstrap/css/bootstrap-datetimepicker.min.css">
	<!-- bootstrap datepicker -->
	<link rel="stylesheet" href="<?= base_url() ?>assets/bootstrap/css/bootstrap-datepicker.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?= base_url() ?>assets/css/AdminLTE.min.css">
	<!-- AdminLTE Skins. -->
	<link rel="stylesheet" href="<?= base_url() ?>assets/css/skins/_all-skins.min.css">
	<!-- Style Admin Modification Css -->
	<link rel="stylesheet" href="<?= base_url() ?>assets/css/admin-style.css">
	<!-- OpenStreetMap Css -->
	<link rel="stylesheet" href="<?= base_url() ?>assets/css/leaflet.css" />
	<link rel="stylesheet" href="<?= base_url() ?>assets/css/leaflet-geoman.css" />
	<link rel="stylesheet" href="<?= base_url() ?>assets/css/L.Control.Locate.min.css" />
	<link rel="stylesheet" href="<?= base_url() ?>assets/css/MarkerCluster.css" />
	<link rel="stylesheet" href="<?= base_url() ?>assets/css/MarkerCluster.Default.css" />
	<link rel="stylesheet" href="<?= base_url() ?>assets/css/leaflet-measure-path.css" />

	<!-- Untuk custom syle -->

	<link type='text/css' href="<?= base_url() ?>assets/css/siteman.css" rel='Stylesheet' />

	<!-- Diperlukan untuk script jquery khusus halaman -->
	<script src="<?= base_url() ?>assets/bootstrap/js/jquery.min.js"></script>

	<!-- Diperlukan untuk global automatic base_url oleh external js file -->
	<script type="text/javascript">
		var BASE_URL = "<?= base_url(); ?>";
	</script>

	<!-- Highcharts JS -->
	<script src="<?= base_url() ?>assets/js/highcharts/highcharts.js"></script>
	<script src="<?= base_url() ?>assets/js/highcharts/highcharts-3d.js"></script>
	<script src="<?= base_url() ?>assets/js/highcharts/exporting.js"></script>
	<script src="<?= base_url() ?>assets/js/highcharts/highcharts-more.js"></script>

</head>

<body class="skin-blue sidebar-mini fixed ">
	<div class="wrapper">
		<header class="main-header">
			<a href="#" class="logo">
				<span class="logo-mini"><b>ANDRITZ</b></span>
				<span class="logo-lg"><b>PT. ANDRITZ</b></span>
			</a>
			<nav class="navbar navbar-static-top">
				<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
					<span class="sr-only">Toggle navigation</span>
				</a>
				<div class="navbar-custom-menu">
					<ul class="nav navbar-nav">
						<?php //if ($this->CI->cek_hak_akses('b', 'mailbox')): 
						?>
						<li class="dropdown messages-menu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<i class="fa fa-envelope-o"></i>
								<span class="badge" id="b_inbox"></span>
							</a>
							<ul class="dropdown-menu">
								<li class="header">You have <span id="c_inbox"></span> new messages</li>
								<li>
									<!-- inner menu: contains the actual data -->
									<ul class="menu">
										<?php foreach ($notif_messages as $notifications) : ?>
											<li><!-- start message -->

												<a href="<?= base_url(); ?>mailbox/view_messages/<?= $notifications['MAILSEQ'] ?>" data-remote="false" data-toggle="modal" data-tittle="View Messages" data-target="#modalBox">
													<div class="pull-left">
														<img src="<?= base_url() ?>assets/files/user_pict/kuser.png" class="img-circle" alt="User Image">
													</div>
													<h4>
														<?= ucwords(strtolower($notifications['FROM_NAME'])); ?>
														<small><i class="fa fa-clock-o"></i>
															<?php
															$todaydate = date("M d Y");
															$sending_date = substr($notifications['SENDING_DATE'], 4, 2) . "/" . substr($notifications['SENDING_DATE'], 6, 2) . "/" . substr($notifications['SENDING_DATE'], 0, 4);
															$sending_date = date('M d Y', strtotime($sending_date));
															$time = strlen($notifications['SENDING_TIME']);
															if ($time == 5) {
																$sending_time = substr($notifications['SENDING_TIME'], 0, 1) . ":" . substr($notifications['SENDING_TIME'], 2, 2);
															} else if ($time == 6) {
																$sending_time = substr($notifications['SENDING_TIME'], 0, 2) . ":" . substr($notifications['SENDING_TIME'], 3, 2);
															}
															if ($todaydate == $sending_date) {
																echo $sending_time;
															} else {
																echo $sending_date;
															} ?></small>
													</h4>
													<p><?= substr($notifications['SUBJECT'], 0, 30); ?></p>
												</a>
											</li>
										<?php endforeach; ?>
										<!-- end message -->
									</ul>
								</li>
								<li class="footer"><a href="<?= base_url()
															?>mailbox/unread">See All Messages</a></li>
							</ul>
						</li>

						<!--<li>
							<a href="<? //= base_url() 
										?>mailbox">
								<i class="fa fa-envelope fa-lg" title="Pesan masuk baru"></i><span class="badge" id="b_inbox"></span>
							</a>
						</li>-->
						<?php //endif; 
						?>
						<li class="dropdown user user-menu">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<?php //if ($foto): 
								?>
								<img src="<?= base_url() ?>assets/files/user_pict/kuser.png" class="user-image" alt="User Image" />
								<?php //else :
								?>

								<span class="hidden-xs"><?php echo $namalgn;
														?> </span>
							</a>
							<ul class="dropdown-menu">
								<li class="user-header">

									<img src="<?= base_url() ?>assets/files/user_pict/kuser.png" class="img-circle" alt="User Image" />

									<p>You are logged in as</p>
									<p><strong><?php echo $namalgn;
												?></strong></p>
								</li>
								<li class="user-footer">
									<div class="pull-left">
										<a href="<?= base_url() ?>user_setting/" data-remote="false" data-toggle="modal" data-tittle="Pengaturan Pengguna" data-target="#modalBox">
											<button data-toggle="modal" class="btn bg-maroon btn-flat btn-sm">Profile</button>
										</a>
									</div>
									<div class="pull-right">
										<a href="<?= base_url() ?>login/logout" class="btn bg-maroon btn-flat btn-sm">Logout</a>
									</div>
								</li>
							</ul>
						</li>
					</ul>
				</div>
			</nav>
		</header>

		<!-- Untuk menampilkan modal bootstrap umum  -->
		<div class="modal fade" id="modalBox" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class='modal-dialog'>
				<div class='modal-content'>
					<!--<div class='modal-header'>
						<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
						<h4 class='modal-title' id='myModalLabel'> Pengaturan Pengguna</h4>
					</div>-->
					<div class="fetched-data"></div>
				</div>
			</div>
		</div>