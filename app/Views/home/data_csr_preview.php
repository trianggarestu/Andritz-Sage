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
        <div class="row">
            <div class="col-sm-12">
                <div class="dataTables_wrapper form-inline dt-bootstrap no-footer">


                    <div class="row">
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover">
                                    <tbody>
                                        <tr>
                                            <td colspan="3" style="padding: 5px 20px;">
                                                <hr style="border-bottom: 2px solid #000000; height:0px;">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" style="text-align: center;">
                                                <h3><u>Sales Order Details Preview</u></h3>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="subtitle_head"><strong>CONTRACT</strong></td>
                                        </tr>
                                        <tr>
                                            <td width="300">Status </td>
                                            <td width="1">:</td>
                                            <td><strong><?php $postingstat = $csrposted_data['POSTINGSTAT'];
                                                        switch ($postingstat) {
                                                            case "0":
                                                                echo "<span class='label label-warning'>Open</span>";
                                                                break;
                                                            case "1":
                                                                echo "<span class='label label-success'>Posted</span>";
                                                                break;
                                                            case "2":
                                                                echo "<span class='label label-danger'>Deleted</span>";
                                                                break;
                                                            default:
                                                                echo "<span class='label label-warning'>Open</span>";
                                                        } ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td width="300">Contract </td>
                                            <td width="1">:</td>
                                            <td><strong><?= $csrposted_data['CONTRACT']; ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td width="300">Contract Description </td>
                                            <td width="1">:</td>
                                            <td><strong><?= $csrposted_data['CTDESC']; ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td width="300" style="vertical-align: top;">Customer</td>
                                            <td width="1">:</td>
                                            <td><strong>
                                                    <?= $csrposted_data['CUSTOMER'] . ' - ' . $csrposted_data['NAMECUST']; ?>
                                                    <br>
                                                    <small>
                                                        &lt;&nbsp;<?= $csrposted_data['EMAIL1CUST']; ?>&nbsp;&gt;
                                                    </small>
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="300">Sales Person </td>
                                            <td width="1">:</td>
                                            <td><strong><?= $csrposted_data['MANAGER'] . '(' . $csrposted_data['SALESNAME'] . ')'; ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="subtitle_head"><strong>PROJECT</strong></td>
                                        </tr>
                                        <tr>
                                            <td width="300">Project </td>
                                            <td width="1">:</td>
                                            <td><strong><?= $csrposted_data['PROJECT']; ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td width="300">Project Description </td>
                                            <td width="1">:</td>
                                            <td><strong><?= $csrposted_data['PRJDESC']; ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td width="300">PO Number Customer </td>
                                            <td width="1">:</td>
                                            <td><strong><?= $csrposted_data['PONUMBERCUST']; ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td width="300">PO Customer Date </td>
                                            <td width="1">:</td>
                                            <td><strong><?php
                                                        $dd = substr($csrposted_data['PODATECUST'], 6, 2);
                                                        $mm = substr($csrposted_data['PODATECUST'], 4, 2);
                                                        $yyyy = substr($csrposted_data['PODATECUST'], 0, 4);
                                                        $pocustdate = $mm . '/' . $dd . '/' . $yyyy;
                                                        echo $pocustdate; ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="subtitle_head"><strong>CRM</strong></td>
                                        </tr>
                                        <tr>
                                            <td width="300">CRM Number </td>
                                            <td width="1">:</td>
                                            <td><strong><?= $csrposted_data['CRMNO']; ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td width="300">Request Date </td>
                                            <td width="1">:</td>
                                            <td><strong><?php

                                                        $dd = substr($csrposted_data['CRMREQDATE'], 6, 2);
                                                        $mm = substr($csrposted_data['CRMREQDATE'], 4, 2);
                                                        $yyyy = substr($csrposted_data['CRMREQDATE'], 0, 4);
                                                        $reqdate = $mm . '/' . $dd . '/' . $yyyy;
                                                        echo $reqdate; ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td width="300">Order Description </td>
                                            <td width="1">:</td>
                                            <td><strong><?= $csrposted_data['ORDERDESC']; ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td width="300">Remarks </td>
                                            <td width="1">:</td>
                                            <td><strong><?= $csrposted_data['CRMREMARKS']; ?></strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="subtitle_head"><strong>SPAREPARTS / SERVICES</strong></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered dataTable table-hover nowrap">
                                                        <thead class="bg-gray disabled color-palette">
                                                            <tr>

                                                                <th class="padat">No</th>

                                                                <th>Type </th>
                                                                <th>Inventory No.</th>
                                                                <th>Material No.</th>
                                                                <th>Item Desc.</th>
                                                                <th>Qty.</th>
                                                                <th>Uom</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            $no = 0;
                                                            foreach ($csrlposted_data as $items) :
                                                            ?>
                                                                <tr>

                                                                    <td class="text-center"><?= ++$no ?></td>
                                                                    <td><?= $items['SERVICETYPE']
                                                                        ?></td>

                                                                    <td><?= $items['ITEMNO']
                                                                        ?></td>
                                                                    <td><?= $items['MATERIALNO']
                                                                        ?></td>
                                                                    <td nowrap><?= $items['ITEMDESC']
                                                                                ?></td>
                                                                    <td style="text-align: center;"><?= number_format($items['QTY'], 0, ",", ".")
                                                                                                    ?></td>
                                                                    <td style="text-align: center;" nowrap><?= $items['STOCKUNIT']
                                                                                                            ?></td>

                                                                </tr>
                                                            <?php endforeach;
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td colspan="3" style="padding: 5px 20px;">

                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" style="padding: 5px 20px;">
                                                <hr style="border-bottom: 2px solid #000000; height:0px;">
                                            </td>
                                        </tr>





                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    </form>
                    <div class="row">

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>