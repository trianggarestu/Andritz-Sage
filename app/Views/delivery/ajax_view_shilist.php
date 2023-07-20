<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/validasi.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/localization/messages_id.js"></script>
<script>
    $(function() {
        $('.select2').select2()
    })
</script>
<div class='modal-header'>
    <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
    <h4 class='modal-title' id='myModalLabel'> Delivery Note List</h4>
</div>


<div class='modal-body'>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-success">
                <div class="box-body">
                    <?php if (is_array($shilist_by_po_data)) { ?>
                        <div class="table-responsive">
                            <table class="table table-bordered dataTable table-hover nowrap">
                                <thead class="bg-gray disabled">
                                    <tr>
                                        <th>Status</th>
                                        <th>D/N Date</th>
                                        <th>DOc. Number</th>
                                        <th>D/N Number</th>
                                        <th>D/N Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no_l = 0;
                                    $sumshiqty = 0;
                                    foreach ($shilist_by_po_data as $shiheader) :
                                        $sumshiqty += $shiheader['QTY'];
                                        $shi_date = substr($shiheader['SHIDATE'], 4, 2) . "/" . substr($shiheader['SHIDATE'], 6, 2) . "/" . substr($shiheader['SHIDATE'], 0, 4);
                                    ?>
                                        <tr>
                                            <td>
                                                <?php
                                                $postingstat = $shiheader['SHIPOSTINGSTAT'];
                                                switch ($postingstat) {
                                                    case "0":
                                                        echo "<span class='label label-default'>Open</span>";
                                                        break;
                                                    case "1":
                                                        echo "<span class='label label-success'>Posted</span>";
                                                        break;
                                                    case "2":
                                                        echo "<span class='label label-danger'>Deleted</span>";
                                                        break;
                                                    default:
                                                        echo "<span class='label label-default'>Open</span>";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?= $shi_date ?>
                                            </td>
                                            <td style="width: 32%;"><a href="<?= base_url("administration/shipostedview/" . $shiheader['SHIUNIQ']) ?>" title="SHI View" target="_blank"><?= trim($shiheader['DOCNUMBER']) ?></a></td>
                                            <td>
                                                <?= trim($shiheader['SHINUMBER']) ?>
                                            </td>
                                            <td>
                                                <?= number_format($shiheader['QTY'], 0, ",", ".") ?>
                                            </td>

                                        </tr>

                                    <?php
                                    endforeach;

                                    ?>
                                </tbody>
                                <tfoot class="bg-gray disabled">
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th>Total</th>
                                        <th><?= number_format($sumshiqty, 0, ",", ".") ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="reset" class="btn btn-social btn-flat btn-danger btn-sm reset" data-dismiss="modal"><i class='fa fa-sign-out'></i> Close</button>

    </div>
</div>

<script>
    $(".reset").click(function() {
        document.location.reload(true);
    });
</script>