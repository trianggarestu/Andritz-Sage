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
									<th>No</th>
									<th class="bg-orange">Contract</th>
									<th class="bg-orange" nowrap>Customer Name</th>
									<th class="bg-orange">Customer Email</th>
									<th class="bg-orange">Project No.</th>
									<th class="bg-orange">CRM Number</th>
									<th class="bg-orange">PO Customer</th>
									<th class="bg-orange" nowrap>PO Date</th>
									<th class="bg-orange" nowrap>Req. Date</th>
									<th class="bg-orange">Sales Person</th>
									<th class="bg-orange">Inventory No</th>
									<th class="bg-orange">Material No</th>
									<th class="bg-orange">Order Description</th>
									<th class="bg-orange">Qty</th>
									<th class="bg-orange">Uom</th>
									<th style="background-color: white;"></th>
									<th class="bg-yellow">PR Number</th>
									<th class="bg-yellow">PR Date</th>
									<th style="background-color: white;"></th>
									<th class="bg-green">PO Vendor</th>
									<th class="bg-green">PO Date</th>
									<th class="bg-green">ETD</th>
									<th class="bg-green">Cargo Readiness</th>
									<th class="bg-green">Origin Country</th>
									<th class="bg-green">Remarks</th>
									<th style="background-color: white;"></th>
									<th class="bg-blue">ETD Origin</th>
									<th class="bg-blue">ATD Origin</th>
									<th class="bg-blue">ETA Port</th>
									<th class="bg-blue">PIB</th>
									<th class="bg-blue">Shipment Status</th>
									<th style="background-color: white;"></th>
									<th class="bg-success">GR Date</th>
									<th class="bg-success">Qty</th>
									<th class="bg-success">Status</th>
									<th style="background-color: white;"></th>
									<th class="bg-olive">Delivery Date</th>
									<th class="bg-olive">DN Number</th>
									<th class="bg-olive">Received Date</th>
									<th class="bg-olive">Delivered</th>
									<th class="bg-olive">Outstanding</th>
									<th class="bg-olive">PO Status</th>
									<th class="bg-olive">DN Status</th>
									<th style="background-color: white;"></th>
									<th class="bg-info">Invoice Date</th>
									<th class="bg-info">Status</th>
									<th style="background-color: white;"></th>
									<th class="bg-danger">RR Status</th>
									<th style="background-color: white;"></th>
									<th class="bg-secondary">PO Cust to PR</th>
									<th class="bg-secondary">PO to PO</th>
									<th class="bg-secondary">ON TIME DEL</th>
									<th class="bg-secondary">PO to DN</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$no = 0;

								?>
								<?php foreach ($ord_data as $ot_list) {

									$pocust_date = substr($ot_list['PODATECUST'], 4, 2) . "/" . substr($ot_list['PODATECUST'], 6, 2) . "/" . substr($ot_list['PODATECUST'], 0, 4);
									$crmreq_date = substr($ot_list['CRMREQDATE'], 4, 2) . "/" . substr($ot_list['CRMREQDATE'], 6, 2) . "/" . substr($ot_list['CRMREQDATE'], 0, 4);
									if ($ot_list['RQNDATE'] == '') {
										$rqn_date = '';
									} else {
										$rqn_date = substr($ot_list['RQNDATE'], 4, 2) . "/" . substr($ot_list['RQNDATE'], 6, 2) . "/" . substr($ot_list['RQNDATE'], 0, 4);
									}

									if ($ot_list['PODATE'] == '') {
										$po_date = '';
										$etd_date = '';
									} else {
										$po_date = substr($ot_list['PODATE'], 4, 2) . "/" . substr($ot_list['PODATE'], 6, 2) . "/" . substr($ot_list['PODATE'], 0, 4);
										$etd_date = substr($ot_list['ETDDATE'], 4, 2) . "/" . substr($ot_list['ETDDATE'], 6, 2) . "/" . substr($ot_list['ETDDATE'], 0, 4);
									}

									if ($ot_list['CARGOREADINESSDATE'] == '') {
										$cargo = '';
									} else {
										$cargo = substr($ot_list['CARGOREADINESSDATE'], 4, 2) . "/" . substr($ot_list['CARGOREADINESSDATE'], 6, 2) . "/" . substr($ot_list['CARGOREADINESSDATE'], 0, 4);
									}
									if ($ot_list['ETDORIGINDATE'] == '') {
										$etdori_date = '';
									} else {
										$etdori_date = substr($ot_list['ETDORIGINDATE'], 4, 2) . "/" . substr($ot_list['ETDORIGINDATE'], 6, 2) . "/" . substr($ot_list['ETDORIGINDATE'], 0, 4);
									}
									if ($ot_list['ATDORIGINDATE'] == '') {
										$atdori_date = '';
									} else {
										$atdori_date = substr($ot_list['ATDORIGINDATE'], 4, 2) . "/" . substr($ot_list['ATDORIGINDATE'], 6, 2) . "/" . substr($ot_list['ATDORIGINDATE'], 0, 4);
									}
									if ($ot_list['PIBDATE'] == '') {
										$pib_date = '';
									} else {
										$pib_date = substr($ot_list['PIBDATE'], 4, 2) . "/" . substr($ot_list['PIBDATE'], 6, 2) . "/" . substr($ot_list['PIBDATE'], 0, 4);
									}
									if ($ot_list['ETAPORTDATE'] == '') {
										$eta_date = '';
									} else {
										$eta_date = substr($ot_list['ETAPORTDATE'], 4, 2) . "/" . substr($ot_list['ETAPORTDATE'], 6, 2) . "/" . substr($ot_list['ETAPORTDATE'], 0, 4);
									}

									if ($ot_list['SHIDATE'] == '') {
										$shidate = '';
									} else {
										$shidate = substr($ot_list['SHIDATE'], 4, 2) . "/" . substr($ot_list['SHIDATE'], 6, 2) . "/" . substr($ot_list['SHIDATE'], 0, 4);
									}

									if ($ot_list['RECPDATE'] == '') {
										$grdate = '';
									} else {
										$grdate = substr($ot_list['RECPDATE'], 4, 2) . "/" . substr($ot_list['RECPDATE'], 6, 2) . "/" . substr($ot_list['RECPDATE'], 0, 4);
									}
									if ($ot_list['CUSTRCPDATE'] == '') {
										$cusdate = '';
									} else {
										$cusdate = substr($ot_list['CUSTRCPDATE'], 4, 2) . "/" . substr($ot_list['CUSTRCPDATE'], 6, 2) . "/" . substr($ot_list['CUSTRCPDATE'], 0, 4);
									}
									if ($ot_list['DATEINVC'] == '') {
										$invdate = '';
									} else {
										$invdate = substr($ot_list['DATEINVC'], 4, 2) . "/" . substr($ot_list['DATEINVC'], 6, 2) . "/" . substr($ot_list['DATEINVC'], 0, 4);
									}



								?>
									<td><?= ++$no; ?></td>

									<td><?= $ot_list['CONTRACT']; ?></td>
									<td><?= $ot_list['NAMECUST']; ?></td>
									<td><?= $ot_list['EMAIL1CUST']; ?></td>
									<td><?= $ot_list['PROJECT']; ?></td>
									<td><?= $ot_list['CRMNO']; ?></td>
									<td><?= $ot_list['PONUMBERCUST']; ?></td>
									<td><?= $pocust_date; ?></td>
									<td><?= $crmreq_date; ?></td>
									<td><?= $ot_list['SALESNAME']; ?></td>
									<td><?= $ot_list['ITEMNO']; ?></td>
									<td><?= $ot_list['MATERIALNO']; ?></td>
									<td><?= $ot_list['ORDERDESC']; ?></td>
									<td><?= number_format($ot_list['QTY'], 0, ",", "."); ?></td>
									<td><?= $ot_list['STOCKUNIT']; ?></td>
									<td style="background-color: white;"></td>
									<td><?= $ot_list['RQNNUMBER']; ?></td>
									<td><?= $rqn_date; ?></td>
									<td style="background-color: white;"></td>
									<td><?= $ot_list['PONUMBER']; ?></td>
									<td><?= $po_date; ?></td>
									<td><?= $etd_date; ?></td>
									<td><?= $cargo; ?></td>
									<td><?= $ot_list['ORIGINCOUNTRY']; ?></td>
									<td><?= $ot_list['POREMARKS']; ?></td>
									<td style="background-color: white;"></td>
									<td><?= $etdori_date; ?></td>
									<td><?= $atdori_date; ?></td>
									<td><?= $eta_date; ?></td>
									<td><?= $pib_date; ?></td>
									<td><?= $ot_list['VENDSHISTATUS']; ?></td>
									<td style="background-color: white;"></td>
									<td><?= $grdate ?></td>
									<td><?= $ot_list['RECPQTY']; ?></td>
									<td><?php $dnpostingstat = $ot_list['GRSTATUS'];
										switch ($dnpostingstat) {
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
												echo "";
										} ?>
									</td>
									<td style="background-color: white;"></td>
									<td><?= $shidate ?></td>
									<td><?= $ot_list['SHINUMBER']; ?></td>
									<td><?= $cusdate; ?></td>
									<td><?= $ot_list['SHIQTY']; ?></td>
									<td><?= $ot_list['SHIQTYOUTSTANDING']; ?></td>
									<td><?php $postatus = $ot_list['POCUSTSTATUS'];
										switch ($postatus) {
											case "0":
												echo "Partial";
												break;
											case "1":
												echo "Completed";
												break;
											default:
												echo "";
										} ?>
									<td><?php $dnpostingstat = $ot_list['DNSTATUS'];
										switch ($dnpostingstat) {
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
												echo "";
										} ?>
									</td>
									<td style="background-color: white;"></td>
									<td><?= $invdate ?></td>
									<td><?php $invstat = $ot_list['FINSTATUS'];
										switch ($invstat) {
											case "0":
												echo "Open";
												break;
											case "1":
												echo "Partial";
												break;
											case "2":
												echo "Completed";
												break;
											default:
												echo "";
										} ?>
									</td>
									<td style="background-color: white;"></td>
									<td><?php $rrstat = $ot_list['RRSTATUS'];
										switch ($rrstat) {
											case "0":
												echo "Open";
												break;
											case "1":
												echo "Posted";
												break;
											case "2":
												echo "Done";
												break;
											default:
												echo "";
										} ?>
									</td>
									<td style="background-color: white;"></td>
									<td><?= $ot_list['POCUSTTOPRDAYS']; ?></td>
									<td><?= $ot_list['POTOPODAYS']; ?></td>
									<td><?= $ot_list['ONTIMEDELDAYS']; ?></td>
									<td><?= $ot_list['POTODNDAYS']; ?></td>



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