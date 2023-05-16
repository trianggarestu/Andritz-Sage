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
    <h4 class='modal-title' id='myModalLabel'> Update Cargo Readiness Date</h4>
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
                                    <label for="po_number">Select PO : </label>
                                </div>
                                <div class="col-sm-9">
                                    <select class="form-control input-sm select2 required" id="po_number" name="po_number" style="width:100%;" disabled="true">
                                        <option option value="">___ PO Number - PO Date - Description ___</option>
                                        <?php foreach ($posage_list as $data) :
                                            $po_date = substr($data['PODATE'], 6, 2) . "/" . substr($data['PODATE'], 4, 2) . "/" . substr($data['PODATE'], 0, 4);
                                        ?>
                                            <option value="<?= trim($data['PONUMBER'])
                                                            ?>" <?php if ($po_number == $data['PONUMBER']) {
                                                                    echo "selected";
                                                                } ?>><?= trim($data['PONUMBER'])
                                                                        ?> - <?= $po_date . " - " . $data['DESCRIPTIO']
                                                                                ?>
                                            </option>
                                        <?php endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="etd_date">ETD : </label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-sm date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input class="form-control input-sm pull-right required" id="etd_date" name="etd_date" type="text" value="<?= $etd_date; ?>" readonly />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="cargoreadiness_date">Cargo Readiness : </label>
                                </div>
                                <div class="col-sm-9">
                                    <div class="input-group input-group-sm date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input class="datepicker form-control input-sm pull-right required" id="cargoreadiness_date" name="cargoreadiness_date" type="text" value="<?= $cargoreadiness_date; ?>" readonly />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="origin_country">Origin Country : </label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm required" id="origin_country" name="origin_country" placeholder="" value="<?= $origin_country; ?>" readonly />

                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-3" style="text-align: right;">
                                    <label for="po_remarks">Remarks : </label>
                                </div>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control input-sm required" id="po_remarks" name="po_remarks" placeholder="" value="<?= $po_remarks; ?>" readonly />

                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <input type="hidden" id="id_so" name="id_so" value="<?= $csruniq ?>">
            <input type="hidden" id="id_po" name="id_po" value="<?= $pouniq ?>">

            <button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Close</button>
            <button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Save</button>
        </div>
    </div>
</form>

<!-- bootstrap Date picker -->
<script src="<?= base_url() ?>assets/bootstrap/js/bootstrap-datepicker.min.js"></script>
<script src="<?= base_url() ?>assets/bootstrap/js/bootstrap-datepicker.id.min.js"></script>
<!-- Script-->
<script src="<?= base_url() ?>assets/js/script.js"></script>