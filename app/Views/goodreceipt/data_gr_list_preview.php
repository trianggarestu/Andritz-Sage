<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Inventory Good Receipt - Preview</title>
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
                        <h3><u>Inventory-Good Receipt List</u></h3>
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
                                    <th>Receipt Number</th>
                                    <th>Receipt Date</th>
                                    <th>Vendor Name</th>
                                    <th>Item No</th>
                                    <th>Item Desc</th>
                                    <th>Qty Receipt</th>
                                    <th>Receipt Status</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                use Predis\Command\Redis\SUBSTR;

                                $no = 0;

                                ?>


                                <?php foreach ($gr_data as $data_list) {
                                    $povendate = substr($data_list['PODATE'], 4, 2) . "/" . substr($data_list['PODATE'], 6, 2) . "/" .  substr($data_list['PODATE'], 0, 4);
                                    $rcpdate = substr($data_list['RECPDATE'], 4, 2) . "/" . substr($data_list['RECPDATE'], 6, 2) . "/" .  substr($data_list['ETDDATE'], 0, 4);
                                    // $creadinessdate = substr($data_list['CARGOREADINESSDATE'], 4, 2) . "/" . substr($data_list['CARGOREADINESSDATE'], 6, 2) . "/" .  substr($data_list['CARGOREADINESSDATE'], 0, 4);
                                    // $etdorigindate = substr($data_list['ETDORIGINDATE'], 4, 2) . "/" . substr($data_list['ETDORIGINDATE'], 6, 2) . "/" .  substr($data_list['ETDORIGINDATE'], 0, 4);
                                    // $atdorigindate = substr($data_list['ATDORIGINDATE'], 4, 2) . "/" . substr($data_list['ATDORIGINDATE'], 6, 2) . "/" .  substr($data_list['ATDORIGINDATE'], 0, 4);
                                    // $etaportdate = substr($data_list['ETAPORTDATE'], 4, 2) . "/" . substr($data_list['ETAPORTDATE'], 6, 2) . "/" .  substr($data_list['ETAPORTDATE'], 0, 4);
                                    // $pibdate = substr($data_list['PIBDATE'], 4, 2) . "/" . substr($data_list['PIBDATE'], 6, 2) . "/" .  substr($data_list['PIBDATE'], 0, 4);

                                ?>

                                    <tr>
                                        <td><?= ++$no; ?></td>
                                        <td><?= $data_list['PONUMBER']; ?></td>
                                        <td><?= $povendate; ?></td>
                                        <td><?= $data_list['RECPNUMBER']; ?></td>
                                        <td><?= $rcpdate; ?></td>
                                        <td><?= $data_list['VDNAME']; ?></td>
                                        <td><?= $data_list['RECPITEMNO']; ?></td>
                                        <td><?= $data_list['ITEMDESC']; ?></td>
                                        <td><?= $data_list['RECPQTY']; ?></td>
                                        <td>
                                            <?php $postingstat = $data_list['POSTINGSTAT'];
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