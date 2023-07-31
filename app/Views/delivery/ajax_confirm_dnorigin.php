<!-- Bootstrap Date time Picker -->
<link rel="stylesheet" href="<?= base_url() ?>assets/bootstrap/css/bootstrap-datetimepicker.min.css">
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="<?= base_url() ?>assets/bootstrap/css/bootstrap-datepicker.min.css">
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
    <h4 class='modal-title' id='myModalLabel'> Confirm D/N Origin</h4>
</div>

<form action="<?= $form_action;
                ?>" method="post" id="validasi">
    <div class='modal-body'>
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="docnumber">Doc. Number : </label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm required" id="docnumber" name="docnumber" placeholder="" value="<?= $docnumber; ?>" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="shinumber">DN Number : </label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm required" id="shinumber" name="shinumber" placeholder="" value="<?= $shinumber; ?>" readonly />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="shidate">Shipment Date :</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-sm date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control input-sm required" id="shidate" name="shidate" placeholder="" value="<?= $shidate; ?>" readonly />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="custrcpdate">Received Date :</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-sm date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control input-sm required" id="custrcpdate" name="custrcpdate" placeholder="" value="<?= $custrcpdate; ?>" readonly />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="customer">Customer :</label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm required" id="customer" name="customer" placeholder="" value="<?= $customer . '-' . $cust_name; ?>" readonly />

                                </div>
                            </div>

                        </div>
                        <div class="col-sm-12">
                            <div class="table-responsive">
                                <table class="table table-bordered dataTable table-hover nowrap">
                                    <thead class="bg-gray disabled color-palette">
                                        <tr>

                                            <th class="padat">No</th>

                                            <th>Type </th>
                                            <th>Inventory No.</th>
                                            <th>Item Desc</th>
                                            <th>Qty.</th>
                                            <th>Uom</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 0;
                                        foreach ($shi_l as $items) :
                                        ?>
                                            <tr>

                                                <td class="text-center"><?= ++$no ?></td>
                                                <td><?= $items['SERVICETYPE']
                                                    ?></td>

                                                <td><?= $items['ITEMNO']
                                                    ?></td>
                                                <td><?= $items['ITEMDESC']
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
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-4" style="text-align: right;">
                                    <label for="dnstatus">Confirm D/N Origin :</label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-control input-sm select2 required" id="dnstatus" name="dnstatus" style="width:100%;">
                                        <option option value="">-- Select Status --</option>

                                        <option value="1" <?php if ($dnstatus == 1) {
                                                                echo "selected";
                                                            } ?>>Received
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-4" style="text-align: right;">
                                    <label for="origdnrcpshidate">Original D/N Receipt :</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-sm date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input class="form-control input-sm pull-right datepicker" id="origdnrcpslsdate" name="origdnrcpslsdate" type="text" value="<?= $todaydate ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">

        <input type="hidden" id="shiuniq" name="shiuniq" value="<?= $shiuniq ?>">
        <input type="hidden" id="csruniq" name="csruniq" value="<?= $csruniq ?>">
        <input type="hidden" id="post_stat" name="post_stat" value="<?= $post_stat ?>">
        <button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Close</button>
        <button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> <?= $button ?></button>
    </div>
    </div>
</form>



<!-- bootstrap Date picker -->
<script src="<?= base_url() ?>assets/bootstrap/js/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url() ?>assets/bootstrap/js/bootstrap-datepicker.id.min.js"></script>
<!-- Script-->
<script src="<?= base_url() ?>assets/js/script.js"></script>