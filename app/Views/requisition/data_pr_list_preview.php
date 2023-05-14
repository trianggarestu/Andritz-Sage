<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Purchase Requisition List - Preview</title>
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
                        <h3><u>Purchase Requisition List</u></h3>
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
                                    <th>PR Number</th>
                                    <th>PR Date</th>
                                    <th>Status</th>
                                    <th>PO Vendor</th>
                                    <th>PO Date</th>
                                    <th>Customer Name</th>
                                    <th>Contract No</th>
                                    <th>Project No</th>
                                    <th>Contract Desc</th>
                                    <th>CRM Number</th>
                                    <th>CRM Req. Date</th>
                                    <th>Order Description</th>
                                    <th>Sales Person</th>
                                    <th>Material No</th>
                                    <th>Services Type</th>
                                    <th>Qty</th>
                                    <th>UOM</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                use Predis\Command\Redis\SUBSTR;

                                $no = 0;

                                ?>
                                <?php foreach ($pr_data as $ot_list) {

                                    $crmpodate = substr($ot_list['PODATECUST'], 4, 2) . "/" . substr($ot_list['PODATECUST'], 6, 2) . "/" .  substr($ot_list['PODATECUST'], 0, 4);
                                    $crmreqdate = substr($ot_list['CRMREQDATE'], 4, 2) . '/' . substr($ot_list['CRMREQDATE'], 6, 2) . '/' . substr($ot_list['CRMREQDATE'], 0, 4);
                                    $prdate = substr($ot_list['RQNDATE'], 4, 2) . "/" . substr($ot_list['RQNDATE'], 6, 2) . "/" . substr($ot_list['RQNDATE'], 0, 4);
                                ?>
                                    <tr>
                                        <td><?= ++$no; ?></td>
                                        <td><?= $ot_list['RQNNUMBER']; ?></td>
                                        <td><?= $prdate; ?></td>
                                        <td><?= $ot_list['POSTINGSTAT']; ?></td>
                                        <td><?= $ot_list['PONUMBERCUST']; ?></td>
                                        <td><?= $crmpodate; ?></td>
                                        <td><?= $ot_list['NAMECUST']; ?></td>
                                        <td nowrap><?= $ot_list['CONTRACT']; ?></td>
                                        <td nowrap><?= $ot_list['PROJECT']; ?></td>
                                        <td> <?= $ot_list['CTDESC']; ?></td>
                                        <td><?= $ot_list['CRMNO']; ?></td>
                                        <td><?= $crmreqdate; ?></td>
                                        <td><?= $ot_list['ORDERDESC']; ?></td>
                                        <td><?= $ot_list['SALESNAME']; ?></td>
                                        <td><?= $ot_list['MATERIALNO']; ?></td>
                                        <td><?= $ot_list['SERVICETYPE']; ?></td>
                                        <td><?= number_format($ot_list['QTY'], 0, ",", "."); ?></td>
                                        <td><?= $ot_list['STOCKUNIT']; ?></td>

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