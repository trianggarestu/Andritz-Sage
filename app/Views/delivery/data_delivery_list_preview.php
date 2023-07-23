<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Delivery Order - Preview</title>
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
                        <h3><u>Delivery Order List</u></h3>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 5px 20px;">
                        <?php
                        $fromdate = date_format(date_create(substr($fromdate, 4, 2) . "/" . substr($fromdate, 6, 2) . "/" .  substr($fromdate, 0, 4)), "m/d/Y");
                        $todate = date_format(date_create(substr($todate, 4, 2) . '/' . substr($todate, 6, 2) . '/' . substr($todate, 0, 4)), "m/d/Y");
                        ?>
                        <strong>Filter Shipment Date From : <?= $fromdate; ?> to : <?= $todate; ?></strong> <br>
                        Keyword : <?= $keyword; ?>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 5px 20px;">
                        <table class="border thick">
                            <thead>
                                <tr class="border thick">
                                    <th>No.</th>
                                    <th>D/N Posting Status</th>
                                    <th>Shipment No</th>
                                    <th>Shipment Date</th>
                                    <th>Document No</th>
                                    <th>Customer Name</th>
                                    <th>Receipts Customer(Date)</th>
                                    <th>Origin e-D/N Upload Date</th>
                                    <th>Shipment Reference</th>


                                    <th style="background-color: white;"></th>
                                    <th>Contract</th>
                                    <th>Project</th>
                                    <th>PO Customer</th>
                                    <th>PR No</th>
                                    <th>PO Number</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                use Predis\Command\Redis\SUBSTR;

                                $no = 0;

                                ?>


                                <?php foreach ($deli_data as $data_list) {
                                    $shidate = substr($data_list['SHIDATE'], 4, 2) . "/" . substr($data_list['SHIDATE'], 6, 2) . "/" .  substr($data_list['SHIDATE'], 0, 4);
                                    $cusdate = substr($data_list['CUSTRCPDATE'], 4, 2) . "/" . substr($data_list['CUSTRCPDATE'], 6, 2) . "/" .  substr($data_list['CUSTRCPDATE'], 0, 4);
                                    $oridndate = substr($data_list['ORIGDNRCPSHIDATE'], 4, 2) . "/" . substr($data_list['ORIGDNRCPSHIDATE'], 6, 2) . "/" .  substr($data_list['ORIGDNRCPSHIDATE'], 0, 4);
                                    // $creadinessdate = substr($data_list['CARGOREADINESSDATE'], 4, 2) . "/" . substr($data_list['CARGOREADINESSDATE'], 6, 2) . "/" .  substr($data_list['CARGOREADINESSDATE'], 0, 4);
                                    // $etdorigindate = substr($data_list['ETDORIGINDATE'], 4, 2) . "/" . substr($data_list['ETDORIGINDATE'], 6, 2) . "/" .  substr($data_list['ETDORIGINDATE'], 0, 4);
                                    // $atdorigindate = substr($data_list['ATDORIGINDATE'], 4, 2) . "/" . substr($data_list['ATDORIGINDATE'], 6, 2) . "/" .  substr($data_list['ATDORIGINDATE'], 0, 4);
                                    // $etaportdate = substr($data_list['ETAPORTDATE'], 4, 2) . "/" . substr($data_list['ETAPORTDATE'], 6, 2) . "/" .  substr($data_list['ETAPORTDATE'], 0, 4);
                                    // $pibdate = substr($data_list['PIBDATE'], 4, 2) . "/" . substr($data_list['PIBDATE'], 6, 2) . "/" .  substr($data_list['PIBDATE'], 0, 4);

                                ?>

                                    <tr>
                                        <td><?= ++$no ?></td>
                                        <td><?php $dnpostingstat = $data_list['DNPOSTINGSTAT'];
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
                                                    echo "Posted";
                                            } ?>
                                        </td>
                                        <td><strong><a href="<?= base_url('administration/shipostedview/' . $data_list['SHIUNIQ']) ?>" title="Click here for detail" target="_blank"><?= $data_list['SHINUMBER'] ?></a></strong></td>
                                        <td><?= $shidate; ?></td>
                                        <td><?= $data_list['DOCNUMBER']; ?></td>
                                        <td><?= $data_list['NAMECUST']; ?></td>
                                        <td><?= $cusdate; ?></td>
                                        <td><?= $oridndate; ?></td>
                                        <td><?= $data_list['SHIREFERENCE']; ?></td>
                                        <td style="background-color: white;"></td>
                                        <td><?= $data_list['CONTRACT']; ?></td>
                                        <td><?= $data_list['PROJECT']; ?></td>
                                        <td><?= $data_list['PONUMBERCUST'] ?></td>
                                        <td><?= $data_list['RQNNUMBER'] ?></td>
                                        <td><strong><a href="<?= base_url('administration/popostedview/' . $data_list['POUNIQ']) ?>" title="Click here for detail" target="_blank"><?= $data_list['PONUMBER'] ?></a></strong></td>







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