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
    <h4 class='modal-title' id='myModalLabel'> Form Input Item Good Receipt by P/O </h4>
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
                                    <label for="row_id">rowid </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control input-sm required" id="row_id" name="row_id" placeholder="input here.." value="<?= $rowid ?>" maxlength="60" readonly />
                                </div>
                            </div>
                        </div>

                        <div class='form-group'>
                            <div class="col-sm-12" style="margin: 2px;">
                                <div class="col-sm-4" style="text-align: right;">
                                    <label for="so_qty">Qty <code> (input) </code> </label>
                                </div>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control input-sm required number" id="gr_qty" name="gr_qty" placeholder="input here.." value="<?= $qty ?>" maxlength="5" />
                                </div>
                            </div>
                        </div>


                        <div class="col-sm-12">
                            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                Input Item G/R By Item P/O

                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="po_uniq" name="po_uniq" value="<?= $po_uniq ?>">
                    <input type="hidden" id="rcph_seq" name="rcph_seq" value="<?= $rcphseq ?>">
                    <input type="hidden" id="post_stat" name="post_stat" value="<?= $post_stat ?>">
                    <input type="hidden" id="delgrline" name="delgrline" value="<?= $delgrline ?>">
                    <button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Close</button>
                    <button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Choose</button>
                </div>
            </div>
</form>