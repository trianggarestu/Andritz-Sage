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
    <h4 class='modal-title' id='myModalLabel'> Select Item Good Receipt</h4>
</div>

<form action="<?= $form_action;
                ?>" method="post" id="validasi">
    <div class='modal-body'>
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="sage_rcplseq">Select Item</label>
                            <select class="form-control input-sm select2 required" id="sage_rcplseq" name="sage_rcplseq" style="width:100%;">
                                <option option value="">-- Select Item Number --</option>
                                <?php foreach ($getrcpldata as $data) :
                                ?>
                                    <option value="<?= trim($data['RCPLSEQ'])
                                                    ?>"><?= trim($data['CONTRACT']) . '-' . trim($data['ITEMNO']) . '-' . trim($data['ITEMDESC'])
                                                        ?>
                                    </option>
                                <?php endforeach;
                                ?>
                            </select>
                        </div>
                        <input type="hidden" id="sage_rcphseq" name="sage_rcphseq" value="<?= $data['RCPHSEQ'] ?>">
                        <input type="hidden" id="po_uniq" name="po_uniq" value="<?= $po_uniq ?>">
                        <input type="hidden" id="csr_uniq" name="csr_uniq" value="<?= $csr_uniq ?>">
                        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;"><small>
                                Only Viewed Good Receipt Line with :<br>
                                RCP Number : <strong><?= $rcp_number ?></strong><br>
                                Contract : <strong><?= $ct_no ?> - <?= $ct_desc ?></strong>
                            </small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Close</button>
            <button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Choose</button>
        </div>
    </div>
</form>