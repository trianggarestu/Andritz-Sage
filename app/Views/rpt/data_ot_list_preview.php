<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>Order Tacking List - Preview</title>
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
						<h3><u>Order Tracking List</u></h3>
					</td>
				</tr>
				<tr>
					<td style="padding: 5px 20px;">
						<strong>Filter by : </strong><br>
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
									<th>Inventory No</th>
									<th>Material No</th>
									<th>Req Date</th>
									<th>Sales Person</th>
									<th>Order Description</th>
									<th>Service Type</th>
									<th>Qty</th>
									<th>UoM</th>
									<th>PR No</th>
									<th>PR Date</th>
									<th>PO No</th>
									<th>PO Date</th>
									<th>ETD(Date)</th>
									<th>Cargo Readiness(Date)</th>
									<th>Origin Country</th>
									<th>Remarks</th>
									<th>ETDORIGINDATE</th>
									<th>ATDORIGINDATE</th>
									<th>ETAPORTDATE</th>
									<th>PIBDATE</th>
									<th>Shipement Status</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$no = 0;

								?>
								<?php foreach ($so_data as $ot_list) {

									$crmpodate = substr($ot_list['PODATECUST'], 4, 2) . "/" . substr($ot_list['PODATECUST'], 6, 2) . "/" .  substr($ot_list['PODATECUST'], 0, 4);
									$crmreqdate = substr($ot_list['CRMREQDATE'], 4, 2) . '/' . substr($ot_list['CRMREQDATE'], 6, 2) . '/' . substr($ot_list['CRMREQDATE'], 0, 4);
									$rqndate = substr($ot_list['RQNDATE'], 4, 2) . '/' . substr($ot_list['RQNDATE'], 6, 2) . '/' . substr($ot_list['RQNDATE'], 0, 4);
									$podate = substr($ot_list['PODATE'], 4, 2) . '/' . substr($ot_list['PODATE'], 6, 2) . '/' . substr($ot_list['PODATE'], 0, 4);
									$etd = substr($ot_list['ETDDATE'], 4, 2) . '/' . substr($ot_list['ETDDATE'], 6, 2) . '/' . substr($ot_list['ETDDATE'], 0, 4);
									$cargo = substr($ot_list['CARGOREADINESSDATE'], 4, 2) . '/' . substr($ot_list['CARGOREADINESSDATE'], 6, 2) . '/' . substr($ot_list['CARGOREADINESSDATE'], 0, 4);
									$etdorigindate = substr($ot_list['ETDORIGINDATE'], 4, 2) . "/" . substr($ot_list['ETDORIGINDATE'], 6, 2) . "/" .  substr($ot_list['ETDORIGINDATE'], 0, 4);
									$atdorigindate = substr($ot_list['ATDORIGINDATE'], 4, 2) . "/" . substr($ot_list['ATDORIGINDATE'], 6, 2) . "/" .  substr($ot_list['ATDORIGINDATE'], 0, 4);
									$etaportdate = substr($ot_list['ETAPORTDATE'], 4, 2) . "/" . substr($ot_list['ETAPORTDATE'], 6, 2) . "/" .  substr($ot_list['ETAPORTDATE'], 0, 4);
									$pibdate = substr($ot_list['PIBDATE'], 4, 2) . "/" . substr($ot_list['PIBDATE'], 6, 2) . "/" .  substr($ot_list['PIBDATE'], 0, 4);
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
										<td><?= $ot_list['ITEMNO']; ?></td>
										<td><?= $ot_list['MATERIALNO']; ?></td>
										<td><?= $crmreqdate; ?></td>
										<td><?= $ot_list['SALESNAME']; ?></td>
										<td><?= $ot_list['ORDERDESC']; ?></td>
										<td><?= $ot_list['SERVICETYPE']; ?></td>
										<td><?= number_format($ot_list['QTY'], 0, ",", "."); ?></td>
										<td><?= $ot_list['STOCKUNIT']; ?></td>
										<td><?= $ot_list['RQNNUMBER']; ?></td>
										<td><?= $rqndate; ?></td>
										<td><?= $ot_list['PONUMBER']; ?></td>
										<td><?= $podate; ?></td>
										<td><?= $etd; ?></td>
										<td><?= $cargo; ?></td>
										<td><?= $ot_list['ORIGINCOUNTRY']; ?></td>
										<TD><?= $ot_list['POREMARKS']; ?></TD>
										<td><?= $etdorigindate; ?></td>
										<td><?= $atdorigindate; ?></td>
										<td><?= $etaportdate; ?></td>
										<td><?= $pibdate; ?></td>
										<td><?= $ot_list['VENDSHISTATUS']; ?></td>



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