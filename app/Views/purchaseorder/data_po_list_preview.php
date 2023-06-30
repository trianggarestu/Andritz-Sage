<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Purchase Order List - Preview</title>
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
                        <h3><u>Purchase Order List</u></h3>
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
                                    <th>PO Number</th>
                                    <th>PO Date</th>
                                    <th>ETD(Date)</th>
                                    <th>Cargo Readiness(Date)</th>
                                    <th>Origin Country</th>
                                    <th>Remarks</th>
                                    <th>Status</th>

                                    <th>PR. Number</th>
                                    <th>PR. Date</th>

                                    <th>Contract. No</th>
                                    <th>Contract Desc.</th>
                                    <th>Customer</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                use Predis\Command\Redis\SUBSTR;

                                $no = 0;

                                ?>
                                <?php foreach ($po_data as $po_list) {

                                    $povendate = substr($po_list['PODATE'], 4, 2) . "/" . substr($po_list['PODATE'], 6, 2) . "/" .  substr($po_list['PODATE'], 0, 4);
                                    $etddate = substr($po_list['ETDDATE'], 4, 2) . "/" . substr($po_list['ETDDATE'], 6, 2) . "/" .  substr($po_list['ETDDATE'], 0, 4);
                                    $creadinessdate = substr($po_list['CARGOREADINESSDATE'], 4, 2) . "/" . substr($po_list['CARGOREADINESSDATE'], 6, 2) . "/" .  substr($po_list['CARGOREADINESSDATE'], 0, 4);
                                    $rqndate = substr($po_list['RQNDATE'], 4, 2) . "/" . substr($po_list['RQNDATE'], 6, 2) . "/" .  substr($po_list['RQNDATE'], 0, 4);

                                ?>
                                    <tr>
                                        <td><?= ++$no; ?></td>
                                        <td><?= $po_list['PONUMBER']; ?></td>
                                        <td><?= $povendate; ?></td>
                                        <td><?= $etddate; ?></td>
                                        <td><?= $creadinessdate; ?></td>
                                        <td><?= $po_list['ORIGINCOUNTRY']; ?></td>
                                        <td><?= $po_list['POREMARKS']; ?></td>
                                        <td>
                                            <?php $postingstat = $po_list['POSTINGSTAT'];
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
                                        <td><?= $po_list['RQNNUMBER']; ?></td>
                                        <td><?= $rqndate; ?></td>
                                        <td><?= $po_list['CONTRACT']; ?></td>
                                        <td><?= $po_list['CTDESC']; ?></td>
                                        <td><?= $po_list['NAMECUST']; ?></td>


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