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
    <h4 class='modal-title' id='myModalLabel'> Input P/O Customer Manually!</h4>
</div>

<form action="<?= $form_action;
                ?>" method="post" id="validasi">
    <div class='modal-body'>
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="contract">Input P/O Customer</label>
                            <input type="text" class="form-control input-sm required" id="po_cust" name="po_cust" placeholder="" value="" />
                            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                Input P/O Number Customer Manually if Blank Data P/O in the Sage !

                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="contract" name="contract" value="<?= $contract ?>">
                <input type="hidden" id="project" name="project" value="<?= $project ?>">
                <button type="reset" class="btn btn-social btn-flat btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Close</button>
                <button type="submit" class="btn btn-social btn-flat btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Choose</button>
            </div>
        </div>
</form>