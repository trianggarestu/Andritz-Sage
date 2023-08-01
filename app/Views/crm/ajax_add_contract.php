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
    <h4 class='modal-title' id='myModalLabel'> Select Contract Open</h4>
</div>

<form action="<?= $form_action;
                ?>" method="post" id="validasi">
    <div class='modal-body'>
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-success">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="contract">Select Contract</label>
                            <select class="form-control input-sm select2 required" id="contract" name="contract" style="width:100%;">
                                <option option value="">-- Select Contract--</option>
                                <?php foreach ($contractopen as $data) :
                                    $ct_startdate = substr($data['STARTDATE'], 4, 2) . "/" . substr($data['STARTDATE'], 6, 2) . "/" .  substr($data['STARTDATE'], 0, 4);
                                ?>
                                    <option value="<?= trim($data['CONTRACT'])
                                                    ?>">(<?= trim($data['CONTRACT'])
                                                            ?> - <?= $ct_startdate ?>) - <?= $data['DESC'] . " - " . $data['NAMECUST']
                                                                                            ?>
                                    </option>
                                <?php endforeach;
                                ?>
                            </select>


                            <input type="hidden" id="csruniq" name="csruniq" value="<?= session()->get('csruniq') ?>">
                            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                only viewed contract with open status

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

<script>
    $(".reset").click(function() {
        document.location.reload(true);
    });
</script>