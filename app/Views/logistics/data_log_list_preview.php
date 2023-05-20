<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Purchase Order Before ETD List - Preview</title>
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
                        <h3><u>Purchase Order Before ETD List</u></h3>
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
                                    <th>PO Number</th>
                                    <th>PO Date</th>
                                    <th>ETD(Date)</th>
                                    <th>Cargo Readiness(Date)</th>
                                    <th>Origin Country</th>
                                    <th>Remarks</th>
                                    <th>Status</th>

                                    <th>ETD Origin (Date)</th>
                                    <th>ATD Origin(Date) </th>

                                    <th>ETA Port (Date)</th>
                                    <th>PIB (Date)</th>
                                    <th>Shipement Status</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                use Predis\Command\Redis\SUBSTR;

                                $no = 0;

                                ?>


                                <?php foreach ($log_data as $data_list) {
                                    $povendate = substr($data_list['PODATE'], 4, 2) . "/" . substr($data_list['PODATE'], 6, 2) . "/" .  substr($data_list['PODATE'], 0, 4);
                                    $etddate = substr($data_list['ETDDATE'], 4, 2) . "/" . substr($data_list['ETDDATE'], 6, 2) . "/" .  substr($data_list['ETDDATE'], 0, 4);
                                    $creadinessdate = substr($data_list['CARGOREADINESSDATE'], 4, 2) . "/" . substr($data_list['CARGOREADINESSDATE'], 6, 2) . "/" .  substr($data_list['CARGOREADINESSDATE'], 0, 4);
                                    $etdorigindate = substr($data_list['ETDORIGINDATE'], 4, 2) . "/" . substr($data_list['ETDORIGINDATE'], 6, 2) . "/" .  substr($data_list['ETDORIGINDATE'], 0, 4);
                                    $atdorigindate = substr($data_list['ATDORIGINDATE'], 4, 2) . "/" . substr($data_list['ATDORIGINDATE'], 6, 2) . "/" .  substr($data_list['ATDORIGINDATE'], 0, 4);
                                    $etaportdate = substr($data_list['ETAPORTDATE'], 4, 2) . "/" . substr($data_list['ETAPORTDATE'], 6, 2) . "/" .  substr($data_list['ETAPORTDATE'], 0, 4);
                                    $pibdate = substr($data_list['PIBDATE'], 4, 2) . "/" . substr($data_list['PIBDATE'], 6, 2) . "/" .  substr($data_list['PIBDATE'], 0, 4);

                                ?>

                                    <tr>
                                        <td><?= ++$no; ?></td>
                                        <td><?= $data_list['PONUMBER']; ?></td>
                                        <td><?= $povendate; ?></td>
                                        <td><?= $etddate; ?></td>
                                        <td><?= $creadinessdate; ?></td>
                                        <td><?= $data_list['ORIGINCOUNTRY']; ?></td>
                                        <td><?= $data_list['POREMARKS']; ?></td>
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
                                        <td><?= $etdorigindate; ?></td>
                                        <td><?= $atdorigindate; ?></td>
                                        <td><?= $etaportdate; ?></td>
                                        <td><?= $pibdate; ?></td>
                                        <td><?= $data_list['VENDSHISTATUS']; ?> </td>




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