<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>Sales Order List - Preview</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="shortcut icon" href="<?= base_url() ?>assets/images/logo/favicon.ico" />
	<link href="<?= base_url() ?>assets/css/report.css" rel="stylesheet" type="text/css">

</head>

<body>
	<div id="body">
		<table>
			<tbody>

				<tr>
					<td style="padding: 5px 20px;">
						<hr style="border-bottom: 2px solid #000000; height:0px;">
					</td>
				</tr>
				<tr>
					<td align="center">
						<h3><u>Sales Order List</u></h3>
					</td>
				</tr>
				<tr>
					<td style="padding: 5px 20px;">
						<?php
						$fromdate = date_format(date_create(substr($fromdate, 4, 2) . "/" . substr($fromdate, 6, 2) . "/" .  substr($fromdate, 0, 4)), "m/d/Y");
						$todate = date_format(date_create(substr($todate, 4, 2) . '/' . substr($todate, 6, 2) . '/' . substr($todate, 0, 4)), "m/d/Y");
						?>
						<strong>Filter P/O Date From : <?= $fromdate; ?> to : <?= $todate; ?></strong> <br>
						Keyword : <?= $keyword; ?>
					</td>
				</tr>
				<tr>
					<td style="padding: 5px 20px;">
						<table class="border thick">
							<thead>
								<tr class="border thick">
									<th>No.</th>
									<th>Customer Name</th>
									<th>Customer Email</th>
									<th>Contract No.</th>
									<th>Project No.</th>
									<th>CRM Number</th>
									<th>PO Customer</th>
									<th>PO Date</th>


									<th>Req Date</th>
									<th>Sales Person</th>
									<th>Order Description</th>

									<th>Status</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$no = 0;

								?>
								<?php foreach ($so_data as $ot_list) {

									$crmpodate = substr($ot_list['PODATECUST'], 4, 2) . "/" . substr($ot_list['PODATECUST'], 6, 2) . "/" .  substr($ot_list['PODATECUST'], 0, 4);
									$crmreqdate = substr($ot_list['CRMREQDATE'], 4, 2) . '/' . substr($ot_list['CRMREQDATE'], 6, 2) . '/' . substr($ot_list['CRMREQDATE'], 0, 4);
								?>
									<tr>
										<td><?= ++$no; ?></td>
										<td><?= $ot_list['NAMECUST']; ?></td>
										<td><?= $ot_list['EMAIL1CUST']; ?></td>
										<td nowrap><?= $ot_list['CONTRACT']; ?></td>
										<td nowrap><?= $ot_list['PROJECT']; ?></td>
										<td><?= $ot_list['CRMNO']; ?></td>
										<td><?= $ot_list['PONUMBERCUST']; ?></td>
										<td><?= $crmpodate; ?></td>

										<td><?= $crmreqdate; ?></td>
										<td><?= $ot_list['SALESNAME']; ?></td>
										<td><?= $ot_list['ORDERDESC']; ?></td>



										<td>
											<?php $postingstat = $ot_list['POSTINGSTAT'];
											switch ($postingstat) {
												case "0":
													echo "Open";
													break;
												case "1":
													echo "Posted";
													break;
												case "2":
													echo "Deleted";
													break;
												default:
													echo "Open";
											} ?>
										</td>
									</tr>

								<?php } ?>
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</body>

</html>