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
    <h4 class='modal-title' id='myModalLabel'> Form Input Item S/O</h4>
</div>

<form action="<?= $form_action;
                ?>" method="post" id="validasi">
    <div class='modal-body'>
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-body">
                        <div class='form-group'>
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-4" style="text-align: right;">
                                    <label for="so_service">Service Type <code> (choose) </code> </label>
                                </div>
                                <div class="col-sm-8">
                                    <select name="so_service_temp" class="form-control input-sm required" readonly disabled>
                                        <option value="">--Choose One--</option>
                                        <option value="SPAREPARTS" selected>SPAREPARTS</option>
                                        <option value="SERVICES" <?php //if ($so_service == "SERVICES") {
                                                                    //echo "selected";
                                                                    //} 
                                                                    ?>>SERVICES</option>

                                    </select>
                                    <input type="hidden" id="so_service" name="so_service" value="SPAREPARTS">
                                </div>
                            </div>
                        </div>
                        <div class='form-group'>
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-4" style="text-align: right;">
                                    <label for="inventory_no">Inventory No. <code> (choose) </code></label>
                                </div>
                                <div class="col-sm-8">
                                    <select class="form-control input-sm select2 required" id="inventory_no" name="inventory_no" style="width:100%;">
                                        <option option value="">--SELECT INVENTORY NO--</option>
                                        <?php foreach ($item_data as $icitem) :
                                        ?>
                                            <option value="<?= trim($icitem['ITEMNO'])
                                                            ?>" <?php if ($inventory_no == trim($icitem['ITEMNO'])) {
                                                                    echo "selected";
                                                                } ?>><?= trim($icitem['ITEMNO'])
                                                                        ?> - <?= $icitem['ITEMDESC']
                                                                                ?>
                                            </option>
                                        <?php endforeach;
                                        ?>
                                    </select>

                                </div>
                            </div>
                        </div>
                        <div class='form-group'>
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-4" style="text-align: right;">
                                    <label for="material_no">Material No. <code> (input) </code> </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control input-sm required" id="material_no" name="material_no" placeholder="input here.." value="<?= $material_no ?>" maxlength="24" />
                                </div>
                            </div>
                        </div>

                        <div class='form-group'>
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-4" style="text-align: right;">
                                    <label for="so_qty">Qty <code> (input) </code> </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control input-sm required number" id="so_qty" name="so_qty" placeholder="input here.." value="<?= $so_qty ?>" maxlength="5" />
                                </div>
                            </div>
                        </div>

                        <div class='form-group'>
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-4" style="text-align: right;">
                                    <label for="so_uom">Uom <code> (Choose) </code> </label>
                                </div>
                                <div class="col-sm-8">
                                    <select name="so_uom" class="form-control input-sm required">
                                        <option value="">--Choose One--</option>
                                        <option value="Pcs" <?php if ($so_uom == "Pcs") {
                                                                echo "selected";
                                                            } ?>>Set</option>
                                        <option value="Set" <?php if ($so_uom == "Set") {
                                                                echo "selected";
                                                            } ?>>Set</option>
                                        <option value="Unit" <?php if ($so_uom == "Unit") {
                                                                    echo "selected";
                                                                } ?>>Unit</option>
                                        <option value="ACT" <?php if ($so_uom == "ACT") {
                                                                echo "selected";
                                                            } ?>>ACT</option>
                                        <option value="Kg" <?php if ($so_uom == "Kg") {
                                                                echo "selected";
                                                            } ?>>Kg</option>
                                        <option value="Meter" <?php if ($so_uom == "Meter") {
                                                                    echo "selected";
                                                                } ?>>Meter</option>
                                        <option value="Others" <?php if ($so_uom == "Others") {
                                                                    echo "selected";
                                                                } ?>>Others</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                Input Item S/O

                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="contract" name="contract" value="<?= $contract ?>">
                    <input type="hidden" id="project" name="project" value="<?= $project ?>">
                    <input type="hidden" id="rowid" name="rowid" value="<?= $rowid ?>">
                    <button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Close</button>
                    <button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Choose</button>
                </div>
            </div>
</form>