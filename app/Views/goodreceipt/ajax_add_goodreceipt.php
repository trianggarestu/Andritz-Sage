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
    <h4 class='modal-title' id='myModalLabel'> Select Receipt Number</h4>
</div>

<form action="<?= $form_action;
                ?>" method="post" id="validasi">
    <div class='modal-body'>
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="rcph_seq">Select Receipt Number</label>
                            <select class="form-control input-sm select2 required" id="rcph_seq" name="rcph_seq" style="width:100%;">
                                <option option value="">-- Select Receipt Number --</option>
                                <?php foreach ($gr_by_po as $data) :
                                    $rcp_date = substr($data['RCPDATE'], 4, 2) . '/' . substr($data['RCPDATE'], 6, 2) . '/' . substr($data['RCPDATE'], 0, 4);
                                ?>
                                    <option value="<?= trim($data['RCPHSEQ'])
                                                    ?>"><?= trim($data['RCPNUMBER']) . ' - ' . $rcp_date
                                                        ?>
                                    </option>
                                <?php endforeach;
                                ?>
                            </select>
                        </div>
                        <input type="hidden" id="po_uniq" name="po_uniq" value="<?= $po_uniq ?>">
                        <input type="hidden" id="csr_uniq" name="csr_uniq" value="<?= $csr_uniq ?>">
                        <input type="hidden" id="post_stat" name="post_stat" value="<?= $post_stat ?>">
                        <input type="hidden" id="delgrline" name="delgrline" value="<?= $delgrline ?>">
                        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                            <small> Viewed Good Receipt with P/O Number : <strong><?= $po_number ?></strong></small>
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