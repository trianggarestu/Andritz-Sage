<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.validate.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/validasi.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/localization/messages_id.js"></script>
<script>
    $(function() {
        $('.select2').select2()
    })
</script>
<div class='modal-header'>
    <button type='button' class='close reset' data-dismiss='modal' aria-hidden='true'>&times;</button>
    <h4 class='modal-title' id='myModalLabel'> Form Input Item Good Receipt by P/O </h4>
</div>

<form name="update-item" id="update-item" action="<?= $form_action;
                                                    ?>" method="post" id="validasi">
    <div class='modal-body'>
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-body">
                        <div class='form-group'>
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-4" style="text-align: right;">
                                    <label for="rowid">Row ID </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control input-sm required" id="rowid" name="rowid" value="<?= $rowid ?>" maxlength="60" readonly />
                                </div>
                            </div>
                        </div>
                        <div class='form-group'>
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-4" style="text-align: right;">
                                    <label for="material_no">Service Type </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control input-sm required" id="service_type" name="service_type" placeholder="input here.." value="<?= $service_type ?>" maxlength="60" readonly />
                                </div>
                            </div>
                        </div>

                        <div class='form-group'>
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-4" style="text-align: right;">
                                    <label for="inventory_no">Inventory No.</label>
                                </div>
                                <div class="col-sm-8">
                                    <select class="form-control input-sm select2 required" id="inventory_no" name="inventory_no" style="width:100%;" disabled="true">


                                        <option value="<?= trim($itemno) ?>">
                                            <?= trim($itemno) ?>
                                        </option>
                                        <?php //endforeach;
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class='form-group'>
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-4" style="text-align: right;">
                                    <label for="material_no">Material No. </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control input-sm required" id="material_no" name="material_no" placeholder="input here.." value="<?= $material_no ?>" maxlength="60" readonly />
                                </div>
                            </div>
                        </div>

                        <div class='form-group'>
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-4" style="text-align: right;">
                                    <label for="itemdesc">Item Desc. </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control input-sm required" id="itemdesc" name="itemdesc" value="<?= $itemdesc ?>" maxlength="60" readonly />
                                </div>
                            </div>
                        </div>
                        <div class='form-group'>
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-4" style="text-align: right;">
                                    <label for="so_qty">Qty Outstanding</label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control input-sm required number" id="so_qty" name="so_qty" value="<?= $gr_qty ?>" maxlength="5" readonly />
                                </div>
                            </div>
                        </div>

                        <div class='form-group'>
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-4" style="text-align: right;">
                                    <label for="gr_qty">Qty <code> (input) </code> </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="number" class="form-control input-sm required" min="0" max="<?= $gr_qty ?>" id="gr_qty" name="gr_qty" value="<?= $gr_qty ?>" maxlength="5" />
                                </div>
                            </div>
                        </div>

                        <div class='form-group'>
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-4" style="text-align: right;">
                                    <label for="material_no">Uom </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control input-sm required" id="uom" name="uom" value="<?= $uom ?>" maxlength="60" readonly />
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-12">
                            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                Input Item Quantity G/R By Item P/O

                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="csr_uniq" name="csr_uniq" value="<?= $csr_uniq ?>">
                    <input type="hidden" id="csrl_uniq" name="csrl_uniq" value="<?= $csrl_uniq ?>">
                    <input type="hidden" id="po_uniq" name="po_uniq" value="<?= $po_uniq ?>">
                    <input type="hidden" id="pol_uniq" name="pol_uniq" value="<?= $pol_uniq ?>">
                    <input type="hidden" id="rcph_seq" name="rcph_seq" value="<?= $rcphseq ?>">
                    <input type="hidden" id="post_stat" name="post_stat" value="<?= $post_stat ?>">
                    <input type="hidden" id="delgrline" name="delgrline" value="<?= $delgrline ?>">
                    <input type="hidden" id="row_id" name="row_id" value="<?= $rowid ?>">
                    <button type="reset" class="btn btn-social btn-flat btn-danger btn-sm reset" data-dismiss="modal"><i class='fa fa-sign-out'></i> Close</button>
                    <button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Choose</button>
                </div>
            </div>
</form>


<script>
    /*$(document).ready(function() {
        $('#inventory_no').change(function() {
            $('#service_type').val($(this).find('option:selected').data('servicetype'));
            $('#material_no').val($(this).find('option:selected').data('materialno'));
            $('#item_desc').val($(this).find('option:selected').data('itemdesc'));
            $('#uom').val($(this).find('option:selected').data('uom'));
            $('#gr_qty').val($(this).find('option:selected').data('qty'));
        });
    });*/
    $(".reset").click(function() {
        document.location.reload(true);
    });
</script>