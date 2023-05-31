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
    <h4 class='modal-title' id='myModalLabel'> Confirm Delivery Note Origin</h4>
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

                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="shiitemno">Item :</label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-addon">
                                            Inventory No. :
                                        </div>
                                        <input type="text" class="form-control input-sm required" id="shiitemno" name="shiitemno" placeholder="" value="<?= $shiitemno ?>" readonly />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="inventory_desc"></label>
                                </div>
                                <div class="col-sm-9">

                                    <input type="text" class="form-control input-sm required" id="inventory_desc" name="inventory_desc" placeholder="" value="<?= $inventory_desc ?>" readonly />

                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="shiqty"></label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-addon">
                                            Qty. :
                                        </div>
                                        <input type="text" class="form-control input-sm required" id="shiqty" name="shiqty" placeholder="" value="<?= number_format($shiqty, 0, ",", ".") ?> ( <?= $uom ?> )" readonly />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="shiqtyouts"></label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-addon">
                                            Qty. Outstanding :
                                        </div>
                                        <input type="text" class="form-control input-sm required" id="shiqtyouts" name="shiqtyouts" placeholder="" value="<?= number_format($shiqtyouts, 0, ",", ".") ?> ( <?= $uom ?> )" readonly />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="pocuststatus">PO Status</label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" style="width:100%;" class="form-control input-sm required" id="pocuststatus" name="pocuststatus" placeholder="" value="<?php

                                                                                                                                                                                switch ($pocuststatus) {
                                                                                                                                                                                    case "0":
                                                                                                                                                                                        echo "Outstanding";
                                                                                                                                                                                        break;
                                                                                                                                                                                    case "1":
                                                                                                                                                                                        echo "Completed";
                                                                                                                                                                                        break;
                                                                                                                                                                                    default:
                                                                                                                                                                                        echo "";
                                                                                                                                                                                }

                                                                                                                                                                                ?>" readonly />

                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
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