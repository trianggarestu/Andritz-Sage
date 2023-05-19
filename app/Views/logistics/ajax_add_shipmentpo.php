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
    <h4 class='modal-title' id='myModalLabel'> Arrange Shipment P/O to Sales Order</h4>
</div>

<form action="<?= $form_action;
                ?>" method="post" id="validasi">
    <div class='modal-body'>
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="tabel" class="table table-bordered dataTable table-hover">
                                <thead class="bg-gray disabled color-palette">
                                    <tr>
                                        <th nowrap>Purchase Order </th>
                                        <th>:</th>
                                        <th nowrap>Data</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td nowrap>PO. Number </td>
                                        <td>: </td>
                                        <td nowrap><?= $po_number ?></td>
                                    </tr>
                                    <tr>
                                        <td nowrap>PO. Remarks </td>
                                        <td>: </td>
                                        <td nowrap><?= $po_remarks ?></td>
                                    </tr>
                                    <tr>
                                        <td nowrap>PO. Date </td>
                                        <td>: </td>
                                        <td nowrap><?= $po_date ?></td>
                                    </tr>
                                    <tr>
                                        <td nowrap>ETD (Date) </td>
                                        <td>: </td>
                                        <td nowrap><?= $etd_date ?></td>
                                    </tr>
                                    <tr>
                                        <td nowrap>Cargo Readiness (Date) </td>
                                        <td>: </td>
                                        <td nowrap><?= $cargoreadiness_date ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="box box-primary">
                    <div class="box-body">


                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="etdorigin_date">ETD Origin : </label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-sm date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input class="<?php if ($post_stat_data == 0 and (empty($etdorigin_date) or !empty($etdorigin_date)) or $post_stat_data == 1 and empty($etdorigin_date)) :
                                                            echo 'datepicker';
                                                        endif; ?> form-control input-sm pull-right required" id="etdorigin_date" name="etdorigin_date" type="text" value="<?= $etdorigin_date; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="atdorigin_date">ATD Origin : </label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-sm date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input class="<?php if ($post_stat_data == 0 and (empty($atdorigin_date) or !empty($atdorigin_date)) or $post_stat_data == 1 and empty($atdorigin_date)) :
                                                            echo 'datepicker';
                                                        endif; ?> form-control input-sm pull-right" id="atdorigin_date" name="atdorigin_date" type="text" value="<?= $atdorigin_date; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="etaport_date">ETA Port : </label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-sm date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input class="<?php if ($post_stat_data == 0 and (empty($etaport_date) or !empty($etaport_date)) or $post_stat_data == 1 and empty($etaport_date)) :
                                                            echo 'datepicker';
                                                        endif; ?> form-control input-sm pull-right" id="etaport_date" name="etaport_date" type="text" value="<?= $etaport_date; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="pib_date">PIB : </label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-sm date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input class="<?php if ($post_stat_data == 0 and (empty($pib_date) or !empty($pib_date)) or $post_stat_data == 1 and empty($pib_date)) :
                                                            echo 'datepicker';
                                                        endif; ?> form-control input-sm pull-right" id="pib_date" name="pib_date" type="text" value="<?= $pib_date; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="vendorshi_status">Shipment Status : </label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm required" id="vendorshi_status" name="vendorshi_status" placeholder="" value="<?= $vendorshistatus; ?>" <?php if ($post_stat_data == 1 and !empty($vendorshistatus)) :
                                                                                                                                                                                                echo 'readonly';
                                                                                                                                                                                            endif; ?> />

                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="box-body">
                        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                            Field ETD Origin, Shipment Status, Remarks required.<br>
                            Notification to next process running when data posting and all fields are required in completely.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <input type="hidden" id="id_so" name="id_so" value="<?= $csruniq ?>">
            <input type="hidden" id="id_po" name="id_po" value="<?= $pouniq ?>">
            <input type="hidden" id="po_number" name="po_number" value="<?= $po_number ?>">
            <input type="hidden" id="id_log" name="id_log" value="<?= $loguniq ?>">
            <input type="hidden" id="post_stat" name="post_stat" value="<?= $post_stat ?>">
            <input type="hidden" id="post_stat_data" name="post_stat_data" value="<?= $post_stat_data ?>">
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